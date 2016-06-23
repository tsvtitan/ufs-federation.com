//
//  UFSDebtMarketVC.m
//  UFS
//
//  Created by mihail on 16.09.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSDebtMarketVC.h"
#import "AnalyticsCounter.h"

@interface UFSDebtMarketVC ()
{
    UIImageView *imageForNotReachble;
    UIPanGestureRecognizer *customRecognizer;
}

@end

@implementation UFSDebtMarketVC
@synthesize fetchedResultsController;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
        selectedPosition = 0;
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
    if ([UFSLoader reachable])
    {
        [UFSLoader requestPostAuth:@"" andWidth:@""];
        
        
            [UFSLoader requestPostDebtMarketWithSubCategoryId:[NSString stringWithFormat:@"%d",_subCatId]];
        
    }
    SWRevealViewController *revealController = [self revealViewController];
    self.revealViewController.delegate = self;
    self.view.backgroundColor = [UIColor whiteColor];
    if (_type)
    {
        UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
        UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
        [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
        [backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
        [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
        self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
        [backbutton release];
    }
    else
    {
        UIButton *menu = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
        [menu setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
        [menu addTarget:revealController action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
        UIBarButtonItem *revealButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:menu] autorelease];
        [menu release];
            self.navigationItem.leftBarButtonItem = revealButtonItem;
    }
       self.titleText = _titleNavBar;
	// Do any additional setup after loading the view.
    _groupsTableView = [[UITableView alloc] initWithFrame:CGRectMake(10, 0, self.view.width-20, self.view.height) style:UITableViewStylePlain];
    _groupsTableView.delegate = self;
    _groupsTableView.dataSource = self;
    _groupsTableView.separatorStyle = UITableViewCellSeparatorStyleNone;
    //    [_newsTableView setBackgroundColor:RGBA(235, 238, 240, 1.0f)];
    _groupsTableView.autoresizingMask = UIViewAutoresizingFlexibleHeight;
    [_groupsTableView setBackgroundColor:[UIColor clearColor]];
    [_groupsTableView setScrollsToTop:YES];
    [self.view addSubview:_groupsTableView];
    [_groupsTableView release];
    reloadAnimation = ReloadAnimationNone;
    if(reloadAnimation != ReloadAnimationNone) {
		
		CATransition *animation = [CATransition animation];
		[animation setDuration:0.4];
		[animation setType:kCATransitionPush];
		[animation setSubtype:reloadAnimation == ReloadAnimationPush ? kCATransitionFromRight : kCATransitionFromLeft];
		[animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
        [animation setRemovedOnCompletion:NO];
		[_groupsTableView.layer addAnimation:animation forKey:@"reloadAnimation"];
	}

    indicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
    indicator.color = RGBA(3, 68, 124, 1.0f);
    //indicator.center = CGPointMake(self.view.width/2, self.view.center.y-44.0f);
    /* tsv */indicator.center = CGPointMake(self.view.width/2, self.view.height/2-indicator.frame.size.height/2);
    [self.view addSubview:indicator];
    [indicator startAnimating];
    [indicator release];

    
    NSError *error = nil;
    if (![[self fetchedResultsController] performFetch:&error]) {
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
    }
    
}
-(void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOn:) name:kReachableOk object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOff:) name:kNotReachable object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(loadFaild:) name:kNotificationRequestFaild object:nil];

    if (self.type!=2)
    {
    customRecognizer = [[[UIPanGestureRecognizer alloc] initWithTarget:[self revealViewController] action:@selector(_handleRevealGesture:)] autorelease];
    [self.navigationController.navigationBar addGestureRecognizer:customRecognizer];
    [self.view addGestureRecognizer:self.revealViewController.panGestureRecognizer];
    }
    if (![UFSLoader reachable] && !fetchedResultsController.fetchedObjects.count)
    {
        if ([indicator isAnimating])
        {
            [indicator stopAnimating];
        }
        if (!imageForNotReachble)
        {
            imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
            [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
            [self.view addSubview:imageForNotReachble];
            [imageForNotReachble release];
        }
    }
    if (fetchedResultsController.fetchedObjects.count)
    {
        if ([indicator isAnimating])
        {
            [indicator stopAnimating];
        }
    }
}
-(void)viewWillDisappear:(BOOL)animated
{
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    /* tsv */fetchedResultsController.delegate = nil;
//    [self.navigationController.navigationBar removeGestureRecognizer:customRecognizer];
//    [self.view removeGestureRecognizer:[self revealViewController].panGestureRecognizer];
    [super viewWillDisappear:animated];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
-(void)dealloc
{
    fetchedResultsController.delegate = nil;
    _groupsTableView.delegate = nil;
    _groupsTableView.dataSource = nil;
    SAFE_KILL(fetchedResultsController);
    [super dealloc];
}
#pragma -mark TableView DataSourse & Delegate

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSArray *emitentsArr = [((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:indexPath.section]).items allObjects];
    int appendix = (self.type==2)?1:0;
    if (indexPath.row==0)
    {
        if (self.type==2)
            return 45;
        return 40.0f;
    }
    else if (indexPath.row==emitentsArr.count-appendix)
        return 48.0f;
    return 43.0f;
}
- (CGFloat)tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section
{
//    if (self.type!=2)
        return 10.0f;
//    return 0.0f;
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    NSArray *emitentsArr = [((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:section]).items allObjects];
    int count = 0;
    if (self.type!=2)
    {
        emitentsArr = [emitentsArr filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"actual==1"]];
        count = emitentsArr.count+1;
    }
    else
    {
        count = [((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:selectedPosition]).items allObjects].count;
    }
    NSLog(@"count %d %d",count, self.type);
    return count;
    
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    if (self.type!=2)
    {
    return fetchedResultsController.fetchedObjects.count;
    }
    return 1;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *cellId = [NSString stringWithFormat:@"%d %d", indexPath.row, indexPath.section];
    int index = selectedPosition;
     if (self.type!=2)
     {
         index = indexPath.section;
     }
    NSArray *arr = [((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:index]).items allObjects];
    if (self.type!=2)
    {
        arr = [arr filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"actual==1"]];
    }
    else
    {
        arr = [((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:selectedPosition]).items allObjects];
    }

    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellId];
    if (cell==nil)
    {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellId] autorelease];
    
    }
    // Configuring cell
    cell.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
    [cell setSelectionStyle:UITableViewCellSelectionStyleNone];
        UIImageView *acces = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_arrow"]];
        cell.accessoryView = acces;
//        [cell.accessoryView setFrame:CGRectMake(250, 0, 8, 11)];
        
    [cell.contentView removeAllSubviews];
    if (indexPath.row==0 && self.type!=2)
    {
        NSString *str =  ((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:index]).name;
        UIView *bgView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, tableView.width, 40)];
        UIImageView *imageBg = [[UIImageView alloc] initWithFrame:CGRectMake(0, 0, bgView.width, 40)];
        [imageBg setImage:[[UIImage imageNamed:@"bg_table_top_light"] stretchableImageWithLeftCapWidth:10 topCapHeight:0]];
        if (!arr.count)
        {
            [imageBg setImage:[[UIImage imageNamed:@"btn_big_round"] stretchableImageWithLeftCapWidth:10 topCapHeight:0]];
        }
        [bgView addSubview:imageBg];
        [imageBg release];
        UILabel *titleLable = [[UILabel alloc] initWithFrame:CGRectMake(10, 0, tableView.width-20, bgView.height)];
        [titleLable setBackgroundColor:[UIColor clearColor]];
        [titleLable setFont:[UIFont fontWithName:@"Helvetica-Bold" size:16]];
        [titleLable setTextColor:RGBA(3, 68, 124, 1.0f)];
        [titleLable setText:str];
        [bgView addSubview:titleLable];
        [titleLable release];
        [cell.contentView addSubview:bgView];
        [bgView release];
        UIImageView *actualImage = [[UIImageView alloc] initWithFrame:CGRectMake(235, 3, 37, 32)];
        [actualImage setImage:[UIImage imageNamed:@"bg_gold_bubble"]];
        [bgView addSubview:actualImage];
        [actualImage release];
        
        UILabel * actualData = [[UILabel alloc] initWithFrame:CGRectMake(235, 3, 37, 32)];
        [actualData  setTextColor:RGBA(2, 69, 125, 1)];
        actualData.text = [NSString stringWithFormat:@"%lu",(unsigned long)[((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:index]).items allObjects].count];
        [actualData setTextAlignment:NSTextAlignmentCenter];
        [actualData setBackgroundColor:[UIColor clearColor]];
        [bgView addSubview:actualData];
        [actualData release];

    }
    else
    {
        NSInteger heigth = 43;
       
        int insect=0;
        NSInteger appendix = (self.type!=2)?1:0;
        NSArray *sortArr = [arr sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"date" ascending:NO]]];
        UIImage *btnImage =  [[UIImage imageNamed:@"bg_table_middle_cell"] stretchableImageWithLeftCapWidth:12 topCapHeight:12];
        if (indexPath.row==0)
        {
            heigth = 45;
            insect = 4;
            btnImage =  [[UIImage imageNamed:@"bg_table_medium_cell_"] resizableImageWithCapInsets:UIEdgeInsetsMake(10, 12, 10, 12)];
            cell.accessoryType = UITableViewCellAccessoryNone;
            cell.accessoryView = nil;
            [acces setCenter:CGPointMake(self.view.width-34, 26)];
            [cell.contentView addSubview:acces];
        }
        if (indexPath.row==sortArr.count+appendix-1)
        {
            cell.accessoryType = UITableViewCellAccessoryNone;
            cell.accessoryView = nil;
            [acces setCenter:CGPointMake(self.view.width-34, 20)];
            [cell.contentView addSubview:acces];
            btnImage = [[UIImage imageNamed:@"bg_table_medium_cell"] stretchableImageWithLeftCapWidth:15 topCapHeight:15];
            heigth = 45;
            
        }
        if (sortArr.count==1 && self.type==2)
        {
           btnImage = [[UIImage imageNamed:@"btn_big_round"] stretchableImageWithLeftCapWidth:10 topCapHeight:0];
        }
        UIImageView *imageForCell = [[UIImageView alloc] initWithFrame:CGRectMake(0, 0, self.groupsTableView.width, heigth)];
        [imageForCell setImage:btnImage];
        [cell.contentView addSubview:imageForCell];
        [imageForCell release];
        float dot_pos = indexPath.row?0:(indexPath.row==sortArr.count+appendix-1)?-5.0f:5.0f;
        if ([((DebtMarketDB *)[sortArr objectAtIndex:indexPath.row-appendix]).isNew isEqualToNumber:@(1)])
        {
            UIImageView *blueBubble = [[UIImageView alloc] initWithFrame:CGRectMake(15, 17+dot_pos, 14, 14)];
            [blueBubble setImage:[UIImage imageNamed:@"icn_blue_dot"]];
            [cell.contentView addSubview:blueBubble];
            [blueBubble release];
        }
        UILabel *dateLable = [[UILabel alloc] initWithFrame:CGRectMake(40, 3+insect, 200, 20)];
        dateLable.text = ((DebtMarketDB *)[sortArr objectAtIndex:indexPath.row-appendix]).strDate;
        [dateLable setBackgroundColor:[UIColor clearColor]];
        [dateLable setFont:[UIFont fontWithName:@"Helvetica" size:12]];

        [dateLable setTextColor:RGBA(142, 163, 188, 1.0f)];
        [cell.contentView addSubview:dateLable];
        [dateLable release];
        
        UILabel *nameLable = [[UILabel alloc] initWithFrame:CGRectMake(40, 17+insect, 200, 20)];
        nameLable.text = ((DebtMarketDB *)[sortArr objectAtIndex:indexPath.row-appendix]).name;
        [nameLable setBackgroundColor:[UIColor clearColor]];
        [nameLable setFont:[UIFont fontWithName:@"Helvetica" size:15]];

        [nameLable setTextColor:RGBA(2, 69, 125, 1.0f)];
        [cell.contentView addSubview:nameLable];
        [nameLable release];
        [cell.contentView bringSubviewToFront:acces];
    }
        [acces release];
    
    return cell;
}
-(UIView *) tableView:(UITableView *)tableView viewForHeaderInSection:(NSInteger)section
{
    UIView *bgView = [[[UIView alloc] initWithFrame:CGRectMake(0, 0, self.groupsTableView.width, 10)]autorelease];
    [bgView setBackgroundColor:[UIColor clearColor]];
//    if (self.type==2)
//        [bgView setFrame:CGRectZero];
    return bgView;
    
}

/* tsv */
- (void)BackButtonTappedEx:(UIButton *)backbutton {
    
    [self.titles removeLastObject];
    [self performSelector:@selector(BackButtonTapped:) withObject:backbutton];
}
/* tsv */

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (indexPath.row || self.type==2)
    {
        int index = selectedPosition;
        int appendix = (self.type!=2)?1:0;
        if (self.type!=2) index = indexPath.section;
        NSArray *arr = [((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:index]).items allObjects];
        if (self.type!=2)
        {
            arr = [arr filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"actual==1"]];
        }
        else
        {
            arr = [((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:selectedPosition]).items allObjects];
        }

        NSArray *sortArr = [arr sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"date" ascending:NO]]];
        
        /* tsv */
        NSMutableArray *titles = [[NSMutableArray alloc] init];
        if (self.titles) {
            for (int i=0; i<self.titles.count; i++) {
                [titles addObject:[self.titles objectAtIndex:i]];
            }
        }
        DebtMarketDB *db = ((DebtMarketDB *)[sortArr objectAtIndex:(indexPath.row-appendix)]);
        [AnalyticsCounter eventScreens:titles category:db.name action:nil value:nil];
        [titles addObject:db.name];
        /* tsv */
        
        NSInteger newsId = [db.linkID intValue];
        UFSDetailVC *detailEmitent = [[UFSDetailVC alloc] initWithNewsId:newsId CategoryID:_catId andSubcategoryID:_subCatId];
        
        /* tsv */
        detailEmitent.titles = titles;
        detailEmitent.title = db.name;
        /* tsv */
        
        [detailEmitent setType:1];
        [self.navigationController pushViewController:detailEmitent animated:YES];
        [detailEmitent release];
        
    }
    else if (self.type!=2 && !indexPath.row)
    {
        selectedPosition = indexPath.section;
        NSString *name = ((GroupDB *)[fetchedResultsController.fetchedObjects objectAtIndex:selectedPosition]).name;
        
        /* tsv */
        [AnalyticsCounter eventScreens:self.titles category:name action:nil value:nil];
        [self.titles addObject:name];
        /* tsv */
        
        [self.navigationController.navigationBar removeGestureRecognizer:customRecognizer];
        [self.view removeGestureRecognizer:self.revealViewController.panGestureRecognizer];
        reloadAnimation = ReloadAnimationPush;
        self.type=2;
        self.title = name;
        
        if(reloadAnimation != ReloadAnimationNone) {
            
            CATransition *animation = [CATransition animation];
            [animation setDuration:0.4];
            [animation setType:kCATransitionPush];
            [animation setSubtype:reloadAnimation == ReloadAnimationPush ? kCATransitionFromRight : kCATransitionFromLeft];
            [animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
            [animation setRemovedOnCompletion:NO];
            [_groupsTableView.layer addAnimation:animation forKey:@"reloadAnimation"];
        }

        [tableView reloadData];
        UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
        UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
        [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
        //[backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
        /* tsv */[backbutton addTarget:self action:@selector(BackButtonTappedEx:) forControlEvents:UIControlEventTouchUpInside];
        [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
        self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
        [backbutton release];

    }
}
#pragma -mark FetchResult Delegate
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller {
    if (fetchedResultsController.fetchedObjects.count)
    {
        if ([indicator isAnimating])
        {
            [indicator stopAnimating];
        }
    }
    //    id object = (self.fetchedResultsController.fetchedObjects.count > 0) ? [self.fetchedResultsController.fetchedObjects objectAtIndex:0] : nil;
    //    [menuTableView setActionDate:[(NewsDB *)object date]];
//    [UFSLoader requestPostMainNews:@"" CategoryId:@"" andSubCategoryId:[NSString stringWithFormat:@"%d",_subCatId] andNewsID:@""];
    [_groupsTableView reloadData];
    
}



- (NSFetchedResultsController *)fetchedResultsController {
    
    if (fetchedResultsController != nil) {
        return fetchedResultsController;
    }
    
    /*
	 Set up the fetched results controller.
     */
    NSString *entityName = @"GroupDB";
    NSString *sortDescr = @"identifier";
    BOOL isACS = true;
	// Create the fetch request for the entity.
	NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
	// Edit the entity name as appropriate.
    NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:CoreDataManager.shared.managedObjectContext];
    
	[fetchRequest setEntity:entity];
	
	// Set the batch size to a suitable number.
	[fetchRequest setFetchBatchSize:20];
    [fetchRequest setFetchLimit:20];
    
	// Sort using the timeStamp property..
    NSSortDescriptor *sortDescriptor = [[NSSortDescriptor alloc] initWithKey:sortDescr ascending:isACS];
    
    NSArray *sortDescriptors = [[NSArray alloc] initWithObjects:sortDescriptor,nil];
    
    
//    NSPredicate * predicatForResult = [NSPredicate predicateWithFormat:@"%K == %d",predicateFormat,_subCatId?_subCatId:_catId];
    [fetchRequest setPredicate : nil];
	[fetchRequest setSortDescriptors:sortDescriptors];
	
    // Use the sectionIdentifier property to group into sections.
	
    NSFetchedResultsController *localFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:fetchRequest managedObjectContext:CoreDataManager.shared.managedObjectContext sectionNameKeyPath:nil  cacheName:nil];
    localFetchedResultsController.delegate = self;
	self.fetchedResultsController = localFetchedResultsController;
	
	[localFetchedResultsController release];
	[fetchRequest release];
	[sortDescriptor release];
	[sortDescriptors release];
    
    return fetchedResultsController;
}

#pragma -mark Supported Inteface Orientation
-(BOOL)shouldAutorotate
{
    return NO;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
   	return  (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}
-(NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskPortrait;
}
#pragma -mark Slide menu Delegate
- (void)revealController:(SWRevealViewController *)revealController willMoveToPosition:(FrontViewPosition)position
{
    if (position == FrontViewPositionLeft)
    {
        [self.groupsTableView setUserInteractionEnabled:YES];
        if (reloadAnimation == ReloadAnimationNone || reloadAnimation==ReloadAnimationPop)
            [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
    }
    else if (position==FrontViewPositionRight)
    {
        [self.groupsTableView setUserInteractionEnabled:NO];
        if (reloadAnimation == ReloadAnimationNone || reloadAnimation==ReloadAnimationPop)
            [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"btn_nav_menu_yellow"] forState:UIControlStateNormal];
    }
}
-(void) BackButtonTapped:(UIButton *)sender
{
    reloadAnimation = ReloadAnimationPop;
     [self.fetchedResultsController setDelegate:nil];
    
    customRecognizer = [[[UIPanGestureRecognizer alloc] initWithTarget:[self revealViewController] action:@selector(_handleRevealGesture:)] autorelease];
    [self.navigationController.navigationBar addGestureRecognizer:customRecognizer];
    [self.view addGestureRecognizer:self.revealViewController.panGestureRecognizer];
    self.type = 0;
    self.title = _titleNavBar;
    if(reloadAnimation != ReloadAnimationNone) {
		
		CATransition *animation = [CATransition animation];
		[animation setDuration:0.4];
		[animation setType:kCATransitionPush];
		[animation setSubtype:reloadAnimation == ReloadAnimationPush ? kCATransitionFromRight : kCATransitionFromLeft];
		[animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
        [animation setRemovedOnCompletion:NO];
		[_groupsTableView.layer addAnimation:animation forKey:@"reloadAnimation"];
	}

    [_groupsTableView reloadData];
    UIButton *menu = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
    [menu setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
    [menu addTarget:[self revealViewController] action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
    UIBarButtonItem *revealButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:menu] autorelease];
    [menu release];
    self.navigationItem.leftBarButtonItem = revealButtonItem;

}
#pragma -mark Reachability notification
- (void) reachOn: (NSNotification *)notif
{
    if (imageForNotReachble)
    {
        [imageForNotReachble removeFromSuperview];
        imageForNotReachble = nil;
        [indicator startAnimating];
        [UFSLoader requestPostAuth:@"" andWidth:@""];
        [UFSLoader requestPostDebtMarketWithSubCategoryId:[NSString stringWithFormat:@"%d",_subCatId]];
    }
    
}

- (void) reachOff: (NSNotification *)notif
{
    if (!fetchedResultsController.fetchedObjects.count && !imageForNotReachble)
    {
        if ([indicator isAnimating])
        {
            [indicator stopAnimating];
        }
        imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
        [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
        [self.view addSubview:imageForNotReachble];
        [imageForNotReachble release];
    }
}
-(void)loadFaild: (NSNotification *) notify
{
    float delay = reloadAnimation==ReloadAnimationNone?0:0.4;
    double delayInSeconds = delay;
    dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
    dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
        if ([indicator isAnimating])
        {
            [indicator stopAnimating];
            //            indicator = nil;
        }
        
    });
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
