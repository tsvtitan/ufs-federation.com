//
//  UFSActionsVC.m
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSActionsVC.h"
#import "AnalyticsCounter.h"

@interface UFSActionsVC ()
{
    UIPanGestureRecognizer *customRecognizer;
}

@end

@implementation UFSActionsVC
@synthesize fetchedResultsController;

- (id)initWithCategoryIdentifier:(NSString *)categoryId
{
	self = [super init];
	if (self)
	{
        _categoryId = [[NSString alloc] initWithString:categoryId];
		if ([UFSLoader reachable])
		{
			[UFSLoader requestPostAuth:@"" andWidth:@""];
			[UFSLoader requestPostActionsWithCategoryIdentifier:categoryId];
		}
	}
	return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    if (self.navigationController.viewControllers.count>1)
    {
        self.navigationController.viewControllers = @[self.navigationController.topViewController];
    }

    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOn:) name:kReachableOk object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOff:) name:kNotReachable object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(loadFaild:) name:kNotificationRequestFaild object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(dataNotFound:) name:kNotificationDataNotFound object:nil];

	SWRevealViewController *revealController = [self revealViewController];
    self.revealViewController.delegate = self;
    self.view.backgroundColor = [UIColor whiteColor];
   
    UIButton *menu = [[UIButton alloc] initWithFrame:CGRectMake(0.0f, (self.view.frame.size.height - 44)/2.0f, 44.0f, 44.0f)];
    [menu setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
    [menu addTarget:revealController action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
    UIBarButtonItem *revealButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:menu] autorelease];
    [menu release];
    self.navigationItem.leftBarButtonItem = revealButtonItem;
    self.titleText = self.titleNavBar;
	// Основная таблица
	_actionTableView = [[UITableView alloc] initWithFrame:CGRectMake(10.0f, 0.0f, self.view.width - 20.0f, self.view.height) style:UITableViewStylePlain];
	_actionTableView.delegate = self;
	_actionTableView.dataSource = self;
	_actionTableView.autoresizingMask = UIViewAutoresizingFlexibleHeight;
	_actionTableView.separatorStyle = UITableViewCellSeparatorStyleNone;
	_actionTableView.backgroundColor = [UIColor clearColor];
	
	// Footer для таблицы
	UIView *tableFooterView = [[UIView alloc] initWithFrame:CGRectMake(0.0f, 0.0f, _actionTableView.width, 10.0f)];
	[_actionTableView setTableFooterView:tableFooterView];
	[tableFooterView release];
	
	[self.view addSubview:_actionTableView];
	[_actionTableView release];
	
	activityIndicatorView = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
	activityIndicatorView.color = RGBA(3.0f, 68.0f, 124.0f, 1.0f);
	//activityIndicatorView.center = CGPointMake(self.view.width/2, self.view.center.y-(IS_IPHONE_5?60.0f:10.0f));
    activityIndicatorView.center = CGPointMake(self.view.width/2, self.view.height/2-activityIndicatorView.frame.size.height/2);
	[self.view addSubview:activityIndicatorView];
	[activityIndicatorView startAnimating];
	[activityIndicatorView release];
	
    
    NSError *error = nil;
    if (![[self fetchedResultsController] performFetch:&error])
	{
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
    }
	
    if (fetchedResultsController.fetchedObjects.count)
    {
        [activityIndicatorView stopAnimating];
        activityIndicatorView = nil;
    }
    else if (![UFSLoader reachable])
    {
        if (activityIndicatorView)
        {
            
            [activityIndicatorView stopAnimating];
            activityIndicatorView = nil;
            imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
            [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
            [self.view addSubview:imageForNotReachble];
            [imageForNotReachble release];
        }
    }

}
- (void)viewDidAppear:(BOOL)animated
{
	[super viewDidAppear:animated];
    
    
}
-(void) viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    customRecognizer = [[[UIPanGestureRecognizer alloc] initWithTarget:[self revealViewController] action:@selector(_handleRevealGesture:)] autorelease];
    [self.navigationController.navigationBar addGestureRecognizer:customRecognizer];
    [self.view addGestureRecognizer:self.revealViewController.panGestureRecognizer];

}
-(void)viewWillDisappear:(BOOL)animated
{
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [self.navigationController.navigationBar removeGestureRecognizer:customRecognizer];
    [super viewWillDisappear:animated];
    
}
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
}
-(void)dealloc
{
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    _actionTableView.delegate=nil;
    _actionTableView.dataSource = nil;
    fetchedResultsController.delegate=nil;
    SAFE_KILL(fetchedResultsController);
    [_categoryId release];
    [super dealloc];
}
#pragma mark - Buttons Actions



#pragma mark - UITableView DataSource & Delegate

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
	NSString *cellIdentifier = [NSString stringWithFormat:@"%d %d", indexPath.section, indexPath.row];
	UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellIdentifier];
	
	if (!cell)
	{
		cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellIdentifier] autorelease];
		
        [cell setSelectionStyle:UITableViewCellSelectionStyleNone];
		NSArray *actionsArray = fetchedResultsController.fetchedObjects;
		actionsArray = [actionsArray sortedArrayUsingDescriptors:@[ [NSSortDescriptor sortDescriptorWithKey:@"actionID" ascending:YES] ]];
		
		// Получаем ссылку на изображение акции
        UIView *bgView = [[UIView alloc] initWithFrame:CGRectMake(5.0f, 5.0f, tableView.width - 10.0f, 150.0f)];
        [bgView setBackgroundColor:[UIColor clearColor]];
        [cell.contentView addSubview:bgView];
        [bgView release];
		UIImageView *actionBannerImageView = [[UIImageView alloc] initWithFrame:CGRectMake(5.0f, 5.0f, tableView.width - 10.0f, 150.0f)];
//		actionBannerImageView.backgroundColor = [UIColor clearColor];
//        actionBannerImageView.contentMode = UIViewContentModeScaleToFill;
        actionBannerImageView.clipsToBounds = YES;
        NSString *imgUrl = [NSString stringWithFormat:@"%@",((ActionsDB *)actionsArray[indexPath.row]).mainImg];
        NSString *imageName = [imgUrl stringByReplacingOccurrencesOfString:@"files" withString:@"image"];
       
        NSLog(@"image %@",imgUrl);
//        [actionBannerImageView setImageWithURL:[NSURL URLWithString:[NSString stringWithFormat:kServerBasePath,((ActionsDB *)actionsArray[indexPath.row]).mainImg]]];
        [actionBannerImageView.layer setCornerRadius:5.0f];
        [actionBannerImageView.layer setBorderWidth:.5f];
        [actionBannerImageView.layer setBorderColor:RGBA(136, 173, 193, 1.0f).CGColor];
        [bgView.layer setShadowColor:RGBA(123, 159, 183, 1.0f).CGColor];
//        [actionBannerImageView.layer setFrame:actionBannerImageView.bounds];
//        [actionBannerImageView.layer setShadowColor:[UIColor blackColor].CGColor];
        [bgView.layer setShadowOffset:CGSizeMake(-1.0, 7.0)];
        [actionBannerImageView setTag:kImageTag+indexPath.row];
        [bgView.layer setShadowOpacity:0.3f];
//        [actionBannerImageView.layer setShadowRadius:5.0f];
		[bgView addSubview:actionBannerImageView];
		[actionBannerImageView release];
        UIActivityIndicatorView *imageSpinner = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleGray];
        imageSpinner.center = bgView.center;
        imageSpinner.color = RGBA(3, 68, 124, 1.0f);
        [actionBannerImageView addSubview:imageSpinner];
        [imageSpinner startAnimating];
        [imageSpinner release];
        
                CATransition *animationScroll = [CATransition animation];
        [animationScroll setDuration:1.0];
        //animation.fromValue = [NSValue valueWithCGPoint:startPoint];
        //animation.toValue = [NSValue valueWithCGPoint:CGPointMake(200, 200)];
        [animationScroll setFillMode:kCAFillModeForwards];
        [animationScroll setRemovedOnCompletion:YES];
        [animationScroll setTimingFunction:[CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseOut]];
        //    [animation setType:@"rippleEffect"];
        [animationScroll setType:kCATransition];
        [imageSpinner.layer addAnimation:animationScroll forKey:@""];
        if ([FileSystem pathExisted:imageName])
        {
            [imageSpinner stopAnimating];
            NSLog(@"image found");
            [actionBannerImageView setImage:[FileSystem imageWithPath:imageName]];
        }
        else
        {
            NSLog(@"start loading");
            [UFSLoader getImage:imgUrl AndName:imageName];
//            [[NSNotificationCenter defaultCenter] removeObserver:self];
            [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(updateImageInTV:) name:kNotificationImageLoaded object:imageName];
            [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(updateImageInTV:) name:kNotificationImageLoadFailed object:imageName];
        }
	}
	
	return cell;
}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    UFSActionsDetailVC *detailVC = [[UFSActionsDetailVC alloc] initWithNibName:nil bundle:nil];
    detailVC.stockObj = [fetchedResultsController.fetchedObjects objectAtIndex:indexPath.row];
    detailVC.titleNavBar = self.titleNavBar;
    detailVC.type = 2;
    /* tsv */[AnalyticsCounter eventScreens:self.titles category:detailVC.stockObj.name action:nil value:nil];
    [self.navigationController pushViewController:detailVC animated:YES];
    [detailVC release];
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
	if (fetchedResultsController.fetchedObjects.count)
	{
		NSArray *actionsArray = fetchedResultsController.fetchedObjects;
		return actionsArray.count;
	}
	
	return 0;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
	return 160.0f;
}
-(void)updateImageInTV:(NSNotification *) notification
{
    NSLog(@"Notify resevide");
    NSIndexPath *index=nil;
    if ([notification.object isKindOfClass:[NSString class]])
    {
        NSIndexSet *indexesOfRowsToUpdate = [fetchedResultsController.fetchedObjects indexesOfObjectsPassingTest:^BOOL(id obj, NSUInteger idx, BOOL *stop) {
            NSString *str = [NSString stringWithFormat:@"%@",((ActionsDB *)obj).mainImg];
            str = [str stringByReplacingOccurrencesOfString:@"files" withString:@"image"];
            if ([str isEqualToString:notification.object])
                return YES;
            else
                return NO;

        }];
        if (indexesOfRowsToUpdate.count)
        {
            index = [NSIndexPath indexPathForRow:[indexesOfRowsToUpdate lastIndex] inSection:0];
           
        }
        if ([notification.name isEqualToString:kNotificationImageLoaded])
        {
//             NSLog(@"load complete");
            if (index !=nil)
            {
//                NSLog(@"indexes %@",index);
                [_actionTableView reloadRowsAtIndexPaths:@[index] withRowAnimation:YES];
            }
        }
        else
        {
//             NSLog(@"load faild");
        }
    }
}

#pragma mark - NSFetchedResultsControllerDelegate

- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
	if (fetchedResultsController.fetchedObjects.count)
	{
		if (activityIndicatorView)
		{
			[activityIndicatorView stopAnimating];
			activityIndicatorView = nil;
		}
        if (imageForNotReachble)
        {
            [imageForNotReachble removeFromSuperview];
            imageForNotReachble=nil;
        }
	}
	
	[_actionTableView reloadData];
}

- (NSFetchedResultsController *)fetchedResultsController
{
	if (fetchedResultsController != nil)
	{
		return fetchedResultsController;
	}
	
	// Настройки
	NSString *entityName = @"ActionsDB";
	NSString *sortDescr = @"identifier";
	BOOL isAscending = YES;
	// Создаем запрос
	NSFetchRequest *fetchedRequest = [[NSFetchRequest alloc] init];
	NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:CoreDataManager.shared.managedObjectContext];
	[fetchedRequest setEntity:entity];
	[fetchedRequest setFetchBatchSize:20];
	[fetchedRequest setFetchLimit:20];
	
	// Сортировка по идентификатору
	NSSortDescriptor *sortDescriptor = [[NSSortDescriptor alloc] initWithKey:sortDescr ascending:isAscending];
	NSArray *sortDescriptors = [[NSArray alloc] initWithObjects:sortDescriptor, nil];
	
	[fetchedRequest setPredicate:nil];
	[fetchedRequest setSortDescriptors:sortDescriptors];
	
	NSFetchedResultsController *localFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:fetchedRequest managedObjectContext:CoreDataManager.shared.managedObjectContext sectionNameKeyPath:nil cacheName:nil];
	localFetchedResultsController.delegate = self;
	self.fetchedResultsController = localFetchedResultsController;
	
	[localFetchedResultsController release];
	[fetchedRequest release];
	[sortDescriptor release];
	[sortDescriptors release];
	
	return fetchedResultsController;
}

#pragma mark - Supported Inteface Orientation

- (BOOL)shouldAutorotate
{
	return NO;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
	return (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}

- (NSUInteger)supportedInterfaceOrientations
{
	return UIInterfaceOrientationMaskPortrait;
}

#pragma mark - Slide menu Delegate

- (void)revealController:(SWRevealViewController *)revealController willMoveToPosition:(FrontViewPosition)position
{
    if (position == FrontViewPositionLeft)
    {
        [_actionTableView setUserInteractionEnabled:YES];
        [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
    }
    else if (position==FrontViewPositionRight)
    {
        [_actionTableView setUserInteractionEnabled:NO];
        [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"btn_nav_menu_yellow"] forState:UIControlStateNormal];
    }
}
#pragma -mark Reachable Methods
- (void) reachOn: (NSNotification *)notif
{
   
  
    if (imageForNotReachble)
    {
        [imageForNotReachble removeFromSuperview];
        imageForNotReachble = nil;
    }
    
    if (!fetchedResultsController.fetchedObjects.count)
    {
        activityIndicatorView = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
        activityIndicatorView.color = RGBA(3, 68, 124, 1.0f);
        //activityIndicatorView.center = CGPointMake(self.view.width/2, self.view.center.y-(IS_IPHONE_5?60.0f:10.0f));
        activityIndicatorView.center = CGPointMake(self.view.width/2, self.view.height/2-activityIndicatorView.frame.size.height/2);
        [self.view addSubview:activityIndicatorView];
        [activityIndicatorView startAnimating];
        [activityIndicatorView release];
        
        [UFSLoader requestPostAuth:@"" andWidth:@""];
        [UFSLoader requestPostActionsWithCategoryIdentifier:_categoryId];
    }
       
    
}

- (void) reachOff: (NSNotification *)notif
{
    NSLog(@"reach no actions");
    if (activityIndicatorView)
    {
        [activityIndicatorView stopAnimating];
        activityIndicatorView = nil;
        imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
        [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
        [self.view addSubview:imageForNotReachble];
        [imageForNotReachble release];
        ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = true;
        
    }
}

- (void) dataNotFound: (NSNotification *)notif
{
    if (activityIndicatorView)
    {
        [activityIndicatorView stopAnimating];
        activityIndicatorView = nil;
    }
}

-(void)loadFaild: (NSNotification *) notify
{
          if ([activityIndicatorView isAnimating])
        {
            [activityIndicatorView stopAnimating];
            //            indicator = nil;
        }
        
 
    NSString *messageNotify = @"Загрузка не удалась. Попробуйте позже";
    if (((NSString *)notify.object).length)
    {
        messageNotify = ((NSString *)notify.object);
        NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
        [userDef setValue:@"" forKey:kTokenForSession];
        [userDef setValue:@(0) forKey:kTokenExpiredTime];
        [userDef synchronize];
    }
    else
    {
        if ([UFSLoader reachable])
        {
            UIAlertView *alertFaild = [[UIAlertView alloc] initWithTitle:@"Внимание" message:messageNotify delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil];
            [alertFaild show];
            [alertFaild release];
        }
        else
        {
            if (!fetchedResultsController.fetchedObjects.count)
            {
                if (!imageForNotReachble)
                {
                    imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
                    [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
                    [self.view addSubview:imageForNotReachble];
                    [imageForNotReachble release];
                }
            }
        }
    }
    ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = true;
}

@end