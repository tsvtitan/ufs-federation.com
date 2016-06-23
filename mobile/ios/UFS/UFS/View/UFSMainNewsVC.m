//
//  UFSMainNewsVC.m
//  UFS
//
//  Created by mihail on 10.07.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSMainNewsVC.h"
#import "AnalyticsCounter.h"

@interface UFSMainNewsVC ()
{
    UIImage *topImage;
    UIImage *btnImage;
    
}



@end

@implementation UFSMainNewsVC

@synthesize fetchedResultsController;

#pragma -mark Life Circle
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        _catId=0;
        _subCatId=0;
        lastID = 16;
        
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    /*[[NSNotificationCenter defaultCenter] removeObserver:self];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(afterNewAuth) name:kNotificationNewAuth object:nil];*/
    
    if (self.navigationController.viewControllers.count>1)
    {
        self.navigationController.viewControllers = @[self.navigationController.topViewController];
    }

    SWRevealViewController *revealController = [self revealViewController];
    self.revealViewController.delegate = self;
    self.view.backgroundColor = [UIColor whiteColor];
    [self.navigationController.navigationBar addGestureRecognizer:revealController.panGestureRecognizer];
    [self.view addGestureRecognizer:revealController.panGestureRecognizer];
    _menu = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
    [_menu setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
    _menu.tag=100;
    [_menu addTarget:revealController action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
    UIBarButtonItem *revealButtonItem = [[UIBarButtonItem alloc] initWithCustomView:_menu];
    [_menu release];
     self.navigationItem.leftBarButtonItem = revealButtonItem;
 
    predicateFilter = @"";

    _newsTableView = [[UITableView alloc] initWithFrame:self.view.bounds style:UITableViewStylePlain];
    _newsTableView.delegate = self;
    _newsTableView.dataSource = self;
    _newsTableView.separatorStyle = UITableViewCellSeparatorStyleNone;

    _newsTableView.autoresizingMask = UIViewAutoresizingFlexibleHeight;
    [_newsTableView setBackgroundColor:[UIColor clearColor]];
    [_newsTableView setScrollsToTop:YES];
    [self.view addSubview:_newsTableView];
    [_newsTableView release];
    
    // Reachble installation
    
    /* tsv */viewHeight = self.view.height;
    
    reloadAnimation = ReloadAnimationNone;
    [self reloadVC];
    if (![UFSLoader reachable] && !fetchedResultsController.fetchedObjects.count)
    {
        /* tsv */[self stopIndicator];
    }
    
    

}

/* tsv */
/*-(void)afterNewAuth
{
    
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    if ([userDef boolForKey:kFirstMenuShow]) {
        
        NSTimeInterval interval = 1.000;
        if ([userDef valueForKey:kAuthMenuDelay]) {
            interval = [[userDef valueForKey:kAuthMenuDelay] floatValue] / 1000;
        }
        [NSObject cancelPreviousPerformRequestsWithTarget:self selector:@selector(showRevealViewController) object:nil];
        [self performSelector:@selector(showRevealViewController) withObject:nil afterDelay:interval];
    }
    
}

-(void)showRevealViewController
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    if ([userDef boolForKey:kFirstMenuShow])
    {
        [userDef setBool:NO forKey:kFirstMenuShow];
        [userDef synchronize];

        [self.revealViewController revealToggleAnimated:YES];
    }
    
}*/

-(void)stopIndicator
{
    if (indicator!=nil) {
        if ([indicator isAnimating]) {
            [indicator stopAnimating];
        }
        indicator = nil;
    }
}
/* tsv */

-(void)reloadVC
{
    NSLog(@"start");
    predicateFormat = _subCatId? @"subcategoryID":@"categoryID";
    /* tsv */fetchLimit = 10;
    filterTap = NO;
    NSError *error = nil;
    
    SAFE_KILL(fetchedResultsController);
    if (![[self fetchedResultsController] performFetch:&error]) {
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
    }
    
    if (self.type==1)
    {
        fetchedResultsController.delegate = nil;
        [self removeAnimationsFromScrollView];
    }
    
    if (![UFSLoader reachable] && !fetchedResultsController.fetchedObjects.count)
    {
        NSLog(@"fuuuuuu");
        /* tsv */[self stopIndicator];
        
        if (!imageForNotReachble)
        {
            imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
            [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
            [self.view addSubview:imageForNotReachble];
            [imageForNotReachble release];
        }
        ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = true;
    }
    
    if (fetchedResultsController.fetchedObjects.count && [UFSLoader reachable])
    {
        if (_catId!=16 && (self.typeOfNews==0 || self.typeOfNews==1) && self.type==0)
        {
            //NSLog(@"fuck 111");
            UIButton *filterButton = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44)];
            [filterButton addTarget:self action:@selector(filterButtonTap:) forControlEvents:UIControlEventTouchUpInside];
            [filterButton setImage:[UIImage imageNamed:@"icn_calculator"] forState:UIControlStateNormal];
            [filterButton setImage:[UIImage imageNamed:@"icn_calculator"] forState:UIControlStateSelected|UIControlStateHighlighted];
            UIBarButtonItem *filter = [[UIBarButtonItem alloc] initWithCustomView:filterButton];
            [filterButton release];
            self.navigationItem.rightBarButtonItem = filter;
            [filter release];
            
            
        }

        if ([UFSLoader reachable] && self.type==0 && self.allNewsCount>fetchedResultsController.fetchedObjects.count)
        {
            UIView *footerView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.newsTableView.width, 60)];
            footerView.backgroundColor = [UIColor whiteColor];
            UIActivityIndicatorView *loadMoreIndicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
            loadMoreIndicator.color = RGBA(3, 68, 124, 1.0f);
            loadMoreIndicator.center = footerView.center;
            [footerView addSubview:loadMoreIndicator];
            /* tsv *///indicator = loadMoreIndicator;
            [loadMoreIndicator startAnimating];
            [loadMoreIndicator release];
            [_newsTableView setTableFooterView:footerView];
            [footerView release];
            
        }
    }
    else
    {
        if ([UFSLoader reachable])
        {
            if (imageForNotReachble)
            {
                [imageForNotReachble removeFromSuperview];
                imageForNotReachble = nil;
            }

        }
    
    }
    //if ((self.typeOfNews==0 || self.typeOfNews==1) && self.type==0)
    /* tsv */if ((self.typeOfNews==0 || self.typeOfNews==1) && self.type==0 && self.allNewsCount>0)
    {
        NSLog(@"fuck 333");
        if ([UFSLoader reachable])
        {
            if (imageForNotReachble)
            {
                [imageForNotReachble removeFromSuperview];
                imageForNotReachble = nil;
            }
                [UFSLoader requestPostMainNews:@"" CategoryId:(_catId?[NSString stringWithFormat:@"%d",_catId]:@"") andSubCategoryId:(_subCatId?[NSString stringWithFormat:@"%d",_subCatId]:@"") andNewsID:@""];
            isPreviosLoadFailed = false;
            
        }
        
    }
    if (_catId==16 || _catId==-1)
    {
         _titleNavBar = @"Новости компании";
    }
    [self removeAnimationsFromScrollView];
    [self setTitleText:_titleNavBar];    
    if(reloadAnimation != ReloadAnimationNone) {
		NSLog(@"fuck 444");
		CATransition *animation = [CATransition animation];
		[animation setDuration:0.4];
		[animation setType:kCATransitionPush];
		[animation setSubtype:reloadAnimation == ReloadAnimationPush ? kCATransitionFromRight : kCATransitionFromLeft];
		[animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
        [animation setRemovedOnCompletion:YES];
		[_newsTableView.layer addAnimation:animation forKey:nil];
       
	}

    if (fetchedResultsController.fetchedObjects.count)
    {
        /* tsv */[self stopIndicator];
    }
    else
    {
        if ([UFSLoader reachable])
        {
            //if (![indicator isAnimating])
            /* tsv */if (![indicator isAnimating] && self.allNewsCount>0)
            {
              float delay = reloadAnimation==ReloadAnimationNone?0:0.4;
            
              double delayInSeconds = delay;
              dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
              dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
                
                    
              indicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
              indicator.color = RGBA(3, 68, 124, 1.0f);
              
              //indicator.center = CGPointMake(self.view.width/2, self.view.center.y-(IS_IPHONE_5?28.0f:10.0f));
              /* tsv */indicator.center = CGPointMake(self.view.width/2, viewHeight/2 - indicator.frame.size.height/2);
              
              [self.view addSubview:indicator];
              [indicator startAnimating];
              [indicator release];
                
            });
            }

        }
        
    }
    

    [_newsTableView reloadData];
}

-(void)dealloc
{
    fetchedResultsController.delegate = nil;
    _newsTableView.delegate = nil;
    _newsTableView.dataSource = nil;
    SAFE_KILL(fetchedResultsController);
    [_titleNavBar release];
    [super dealloc];
}

-(void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    reloadAnimation = ReloadAnimationNone;
    if (self.navigationItem.leftBarButtonItem.customView.tag==100)
    {
        customRecognizer =
        [[[UIPanGestureRecognizer alloc] initWithTarget:[self revealViewController] action:@selector(_handleRevealGesture:)] autorelease];
        [self.navigationController.navigationBar addGestureRecognizer:customRecognizer];
        [self.view addGestureRecognizer:[self revealViewController].panGestureRecognizer];
    }
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOn:) name:kReachableOk object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOff:) name:kNotReachable object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(loadFaild:) name:kNotificationRequestFaild object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(setMenuEnabled:) name:kNotificationDisableMenu object:nil];
    [self removeAnimationsFromScrollView];
    if (_catId==16 || _catId==-1)
    {
        _titleNavBar = @"Новости компании";
    }
    self.titleText = self.titleNavBar;

}

-(void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
}

-(void)viewWillDisappear:(BOOL)animated
{
   if (customRecognizer)
   {
       [self.navigationController.navigationBar removeGestureRecognizer:customRecognizer];
       [self.view removeGestureRecognizer:[self revealViewController].panGestureRecognizer];
   }
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [super viewWillDisappear:animated];
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
#pragma -mark TableView Delegate & DataSource
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (self.type==0)
        return 153;
    return 116;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //    return [self.childViewControllers count];
    if (self.type)
    {
        if (fetchedResultsController.fetchedObjects.count)
        {
           NSArray * categoryArrayTV = [fetchedResultsController.fetchedObjects filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"type!=4 AND type!=3"]];
            if (categoryArrayTV.count)
                return categoryArrayTV.count;
        }
    }
    return fetchedResultsController.fetchedObjects.count;
    
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
    
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    NSString *cellIdentifier = [NSString stringWithFormat:@"cellId"];
   
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellIdentifier];
    if (cell==nil)
    {
    cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellIdentifier] autorelease];
    }
    
    // Configuring cell
    [cell setSelectionStyle:UITableViewCellSelectionStyleNone];
    if (fetchedResultsController.fetchedObjects.count)
    {
        if (self.type==0)
        {
// Only news
            [cell.contentView removeAllSubviews];
            NewsDB *newsObj = [fetchedResultsController.fetchedObjects objectAtIndex:indexPath.row];
        
    //        UIImage *topImage = [[UIImage imageNamed:@"bg_table_top_dark"] resizableImageWithCapInsets:UIEdgeInsetsMake(0, 30, 0, 10) resizingMode:UIImageResizingModeStretch];
           
        //    UIImage *topImage = [[UIImage imageNamed:@"bg_table_top_dark"] stretchableImageWithLeftCapWidth:300 topCapHeight:0];
           
            topImage = [[UIImage imageNamed:@"bg_table_top_light"] resizableImageWithCapInsets:UIEdgeInsetsMake(0, 10, 0, 10)];
            btnImage = [[UIImage imageNamed:@"bg_table_big_cell"] stretchableImageWithLeftCapWidth:10 topCapHeight:0];

            UIImageView *topButton = [[UIImageView alloc] initWithFrame:CGRectMake(10, 10, 300, 39)];
            [topButton setImage:topImage];
            topButton.tag = [newsObj.identifier intValue];
            
            [cell.contentView addSubview:topButton];
            [topButton release];
            UIImageView *imageForCell = [[UIImageView alloc] initWithFrame:CGRectMake(10, 49, 300,153-49)];
            [imageForCell setImage:btnImage];
            [cell.contentView addSubview:imageForCell];
            [imageForCell release];
       
            bool isNew = ([newsObj.actual isEqualToNumber:@(1)]);
            
            UILabel *catLabel = [[UILabel alloc] initWithFrame:CGRectMake(isNew?40:14, 0,topButton.width-50.0f,topButton.height)];
    //    catLabel.text = ![newsObj.subcategoryID isEqualToNumber:[NSNumber numberWithInt:0]]?[[catdb.title substringToIndex:catdb.title.length-3] stringByAppendingString:@"... / "]:catdb.title;
            catLabel.text = newsObj.title;
            [catLabel setBackgroundColor:[UIColor clearColor]];
            [catLabel setTextColor:RGBA(3, 68, 124, 1)];
            [catLabel setFont:[UIFont fontWithName:@"Helvetica-Bold" size:16]];
            [catLabel setLineBreakMode:NSLineBreakByTruncatingTail];
            [topButton addSubview:catLabel];
            [catLabel release];
            if (![newsObj.subcategoryID isEqualToNumber:[NSNumber numberWithInt:0]])
            {
               
                catLabel.text = newsObj.title ;
                [catLabel setBackgroundColor:[UIColor clearColor]];
                [catLabel setTextColor:RGBA(3, 68, 124, 1)];
                [catLabel setLineBreakMode:NSLineBreakByTruncatingTail];
                [topButton addSubview:catLabel];
            }
            NSArray *pdfArray = [newsObj.files allObjects];
            pdfArray = [pdfArray filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"type==3"]];
            
            int pdfCount = (pdfArray.count)?pdfArray.count:0;
            if (pdfCount>0)
            {
                UIButton *pdfButton = [[UIButton alloc] initWithFrame:CGRectMake(self.view.width-70, 80, 50, 50)];
                [pdfButton setImage:[UIImage imageNamed:@"btn_download"] forState:UIControlStateNormal];
                pdfButton.tag = indexPath.row;
                [pdfButton addTarget:self action:@selector(pdfButtonTap:) forControlEvents:UIControlEventTouchUpInside];
                [cell.contentView addSubview:pdfButton];
                [pdfButton release];
            }

            UILabel *dateLabel = [[UILabel alloc] initWithFrame:CGRectMake(20, topButton.height+10, cell.contentView.width-(pdfCount>0?90:40), 30)];
            [dateLabel setTextColor:RGBA(159, 176, 189, 1)];
            [dateLabel setBackgroundColor:[UIColor clearColor]];
            [dateLabel setText:newsObj.strDate];
            [cell.contentView addSubview:dateLabel];
            [dateLabel release];
            UILabel * newTextLabel = [[UILabel alloc] initWithFrame:CGRectMake(20, 74, cell.contentView.width-(pdfCount>0?90:40), 66)];
            [newTextLabel setTextColor:RGBA(9, 74, 128, 1)];
            [newTextLabel setBackgroundColor:[UIColor clearColor]];
            [newTextLabel setText:newsObj.text];
            [newTextLabel setNumberOfLines:0];
            [newTextLabel setLineBreakMode:NSLineBreakByTruncatingTail];
            [cell.contentView addSubview:newTextLabel];
            [newTextLabel release];
            
            if (isNew) {
                UILabel *labelNew = [[UILabel alloc] initWithFrame:CGRectMake(20, 20, 25, 20)];
                labelNew.backgroundColor = RGBA(198, 0, 0, 1.0f);
                labelNew.textColor = [UIColor whiteColor];
                labelNew.text = @"New";
                labelNew.textAlignment = NSTextAlignmentCenter;
                labelNew.font = [UIFont fontWithName:@"Helvetica-Bold" size:10];
                [cell.contentView addSubview:labelNew];
                [labelNew release];
            }
            
            if (true) {
                
                /*UILabel * matchLabel = [[UILabel alloc] initWithFrame:CGRectMake(20, cell.contentView.height - 5, cell.contentView.width-40, 30)];
                [matchLabel setTextColor:RGBA(9, 74, 128, 1)];
                [matchLabel setBackgroundColor:[UIColor clearColor]];
                [matchLabel setText:@"США"];
                [matchLabel setNumberOfLines:0];
                [matchLabel setLineBreakMode:NSLineBreakByTruncatingTail];
                [cell.contentView addSubview:matchLabel];
                [matchLabel release];*/
            }
            
        }
        else
        {
// Category list
            [cell.contentView removeAllSubviews];
//            reloadAnimation = ReloadAnimationPush;
            NSArray * categoryArrayTV = [fetchedResultsController.fetchedObjects filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"type!=4 AND type!=3"]];
            categoryArrayTV = [categoryArrayTV sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"index" ascending:YES]]];
            SubCatDB *subCatObj = [categoryArrayTV objectAtIndex:indexPath.row];
            topImage = [[UIImage imageNamed:@"bg_table_top_light"] resizableImageWithCapInsets:UIEdgeInsetsMake(0, 10, 0, 10)];
            btnImage = [[UIImage imageNamed:@"bg_table_footer_cell"] stretchableImageWithLeftCapWidth:10 topCapHeight:0];
            
            UIImageView *topButton = [[UIImageView alloc] initWithFrame:CGRectMake(10, 10, 300, 39)];
            [topButton setImage:topImage];
            topButton.tag = [subCatObj.identifier intValue];
            [cell.contentView addSubview:topButton];
            [topButton release];
            UIImageView *imageForCell = [[UIImageView alloc] initWithFrame:CGRectMake(10, 49, 300,116-49)];
            [imageForCell setImage:btnImage];
            [cell.contentView addSubview:imageForCell];
            [imageForCell release];
            
            UILabel *catLabel = [[UILabel alloc] initWithFrame:CGRectMake(20, 0,topButton.width-30.0f,topButton.height)];
            //    catLabel.text = ![newsObj.subcategoryID isEqualToNumber:[NSNumber numberWithInt:0]]?[[catdb.title substringToIndex:catdb.title.length-3] stringByAppendingString:@"... / "]:catdb.title;
            catLabel.text = subCatObj.title;
            [catLabel setBackgroundColor:[UIColor clearColor]];
            [catLabel setTextColor:RGBA(3, 68, 124, 1)];
            [catLabel setFont:[UIFont fontWithName:@"Helvetica-Bold" size:16]];
            [topButton addSubview:catLabel];
            [catLabel release];
            
            
             UILabel * actualText = [[UILabel alloc] initWithFrame:CGRectMake(30, 32, 150, 60)];
            [actualText  setTextColor:RGBA(2, 69, 125, 1)];
            actualText.text = @"Всего материалов";
            [actualText setBackgroundColor:[UIColor clearColor]];
            [cell.contentView addSubview:actualText];
            [actualText release];
            UILabel * allText = [[UILabel alloc] initWithFrame:CGRectMake(30, 59, 170, 60)];
            [allText  setTextColor:RGBA(2, 69, 125, 1)];
            allText.text = @"Из них актуальных";
            [allText setBackgroundColor:[UIColor clearColor]];
            [cell.contentView addSubview:allText];
            [allText release];
            
            UIImageView *actualImage = [[UIImageView alloc] initWithFrame:CGRectMake(255, 75, 40, 30)];
            [actualImage setImage:[UIImage imageNamed:@"bg_gold_bubble"]];
            [cell.contentView addSubview:actualImage];
            [actualImage release];
            
            UILabel * actualData = [[UILabel alloc] initWithFrame:CGRectMake(255, 49, 40, 30)];
            [actualData  setTextColor:RGBA(2, 69, 125, 1)];
            actualData.text = [NSString stringWithFormat:@"%@",subCatObj.allNewsCount];
            [actualData setTextAlignment:NSTextAlignmentCenter];
            [actualData setBackgroundColor:[UIColor clearColor]];
            [cell.contentView addSubview:actualData];
            [actualData release];
            UILabel * allData = [[UILabel alloc] initWithFrame:CGRectMake(255, 75, 40, 30)];
            [allData  setTextColor:RGBA(2, 69, 125, 1)];
            allData.text = [NSString stringWithFormat:@"%@",subCatObj.actualNewsCount];
            [allData setTextAlignment:NSTextAlignmentCenter];
            [allData setBackgroundColor:[UIColor clearColor]];
            [cell.contentView addSubview:allData];
            [allData release];

        }
    }
    
    return cell;
}

/* tsv */
- (void)BackButtonTappedEx:(UIButton *)backbutton {
    
    [self.titles removeLastObject];
    [self performSelector:@selector(BackButtonTapped:) withObject:backbutton];
}
/* tsv */

-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    if (self.type==0)
    {
       
        UFSDetailVC *detailVC = [[UFSDetailVC alloc] initWithNibName:nil bundle:nil];
        detailVC.newsObj = [self.fetchedResultsController.fetchedObjects objectAtIndex:indexPath.row];
        /* tsv */
        detailVC.titles = self.titles;
        detailVC.title = detailVC.newsObj.title;
        [AnalyticsCounter eventScreens:self.titles category:detailVC.newsObj.title action:nil value:nil];
        /* tsv */
        [self.navigationController pushViewController:detailVC animated:YES];
        [detailVC release];
    }
    else
    {
        /* tsv */
        NSMutableArray *titles = [[NSMutableArray alloc] init];
        if (self.titles) {
            for (int i=0; i<self.titles.count; i++) {
              [titles addObject:[self.titles objectAtIndex:i]];
            }
        }
        /* tsv */
        
        [self.navigationController.navigationBar removeGestureRecognizer:customRecognizer];
        [self.view removeGestureRecognizer:[self revealViewController].panGestureRecognizer];
        customRecognizer = nil;
        NSArray * categoryArrayTV = [fetchedResultsController.fetchedObjects filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"type!=4 AND type!=3"]];
        categoryArrayTV = [categoryArrayTV sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"index" ascending:YES]]];
        SubCatDB *subCat = [categoryArrayTV objectAtIndex:indexPath.row];
        if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:1]])
        {
            /* tsv */
            [AnalyticsCounter eventScreens:titles category:subCat.title action:nil value:nil];
            [self.titles addObject:subCat.title];
            /* tsv */
            
            [self removeAnimationsFromScrollView];
            self.subCatId = [subCat.identifier intValue];
            self.catId = [[((CategoriesDB *)subCat.categories) identifier] intValue];
            self.type = 0;
            self.allNewsCount = [subCat.allNewsCount intValue];
            self.typeOfNews = [subCat.type intValue];

            self.titleNavBar = subCat.title;
            reloadAnimation = ReloadAnimationPush;
            SAFE_KILL(fetchedResultsController);
            [self reloadVC];
            
            UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];

            UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(0.0f, (self.view.frame.size.height - 44)/2.0f, 44.0f, 44.0f)];
            [backbutton setImage:imgBtn forState:UIControlStateNormal];
            backbutton.tag = 200;
            //[backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
            /* tsv */[backbutton addTarget:self action:@selector(BackButtonTappedEx:) forControlEvents:UIControlEventTouchUpInside];
            [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
            [backbutton setTag:[subCat.categories.identifier intValue]];
            self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
            [backbutton release];
            

        }
        else if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:4]])
        {
            /* tsv */
            [AnalyticsCounter eventScreens:titles category:subCat.title action:nil value:nil];
            [titles addObject:subCat.title];
            /* tsv */
            
            UFSDebtMarketVC *groupVC = [[UFSDebtMarketVC alloc] initWithNibName:nil bundle:nil];
            /* tsv */groupVC.titles = titles;
            [groupVC setType:1];
            [groupVC setSubCatId:[subCat.identifier intValue]];
            [groupVC setTitleNavBar:subCat.title];
            [self.navigationController pushViewController:groupVC animated:YES];
            [groupVC release];
        }
        else if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:3]])
        {
            /* tsv */
            [AnalyticsCounter eventScreens:titles category:[subCat title] action:nil value:nil];
            [titles addObject:[subCat title]];
            /* tsv */

            UFSEmitentsVC* ViewController = [[[UFSEmitentsVC alloc] init] autorelease];
            /* tsv */((UFSEmitentsVC *)ViewController).titles = titles;
            [((UFSEmitentsVC *)ViewController) setSubcatID:[subCat.identifier integerValue]];
            [((UFSEmitentsVC *)ViewController) setTitleNavBar:[subCat title]];
            [((UFSEmitentsVC *)ViewController) setType:0];
            [self.navigationController pushViewController:ViewController animated:YES];
        }
    }
}

-(void) BackButtonTapped:(UIButton *)sender
{
    [_newsTableView setTableFooterView:nil];
    customRecognizer = [[[UIPanGestureRecognizer alloc] initWithTarget:[self revealViewController] action:@selector(_handleRevealGesture:)] autorelease];
    
    
    [self.navigationController.navigationBar addGestureRecognizer:customRecognizer];
    [self.view addGestureRecognizer:[self revealViewController].panGestureRecognizer];
     [self removeAnimationsFromScrollView];
    
    [self.fetchedResultsController setDelegate:nil];
    
    if (scrennForFilter)
    {
         [self performSelector:@selector(cancelFilterTap:) withObject:nil];
    }
    if (imageForNotReachble)
    {
        [imageForNotReachble removeFromSuperview];
        imageForNotReachble = nil;
    }
    CategoriesDB *catDB = [CoreDataManager object:@"CategoriesDB" withIdentifier:@(sender.tag) inMainContext:YES];
//    self.titleText = @"";
    [self setTitleText:catDB.title];
    [self removeAnimationsFromScrollView];
    [self setTitleText:catDB.title];
    
    self.subCatId = 0;
    self.catId = [catDB.identifier intValue];
    self.type = 1;
    self.titleNavBar = catDB.title;
    
    reloadAnimation = ReloadAnimationPop;
    UIButton *menu = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
    [menu setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
    menu.tag = 100;
    [menu addTarget:self.revealViewController action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
    UIBarButtonItem *revealButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:menu] autorelease];
    [menu release];
    self.navigationItem.leftBarButtonItem = revealButtonItem;
    self.navigationItem.rightBarButtonItem = nil;
    [self.view addGestureRecognizer:self.revealViewController.panGestureRecognizer];
    [self removeAnimationsFromScrollView];
    [self setTitleText:catDB.title];
    [self reloadVC];
    
}

-(void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView
{
    if (self.type==0)
    {
         int y_position = scrollView.contentOffset.y/153+_newsTableView.width/153;
        if (((self.allNewsCount!=0 && self.allNewsCount<=fetchedResultsController.fetchedObjects.count) || ![UFSLoader reachable]) && y_position==fetchedResultsController.fetchedObjects.count-1)
        {
            if (_newsTableView.tableFooterView)
            {
            [_newsTableView setContentOffset:CGPointMake(0, self.newsTableView.contentOffset.y-60) animated:YES];
            double delayInSeconds = _newsTableView.decelerationRate;
            dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
            dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
                [_newsTableView setTableFooterView:nil];
            });
            }
            
        }
        else
        {
           
            if (y_position==fetchedResultsController.fetchedObjects.count-1)
            {
                [self performSelector:@selector(loadMoreTap:) withObject:nil];
            }
        }
    }
   
}

- (void)scrollViewDidScroll:(UIScrollView *)scrollView
{   }

#pragma  - mark NSFetchedResultControllerDelegate
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller {
    
    /*if ([indicator isAnimating])
        [indicator stopAnimating];*/
    /* tsv */[self stopIndicator];
    
    if ([UFSLoader reachable] && self.type==0 && !filterTap && (self.allNewsCount>fetchedResultsController.fetchedObjects.count || !self.allNewsCount))
    {
        
        UIView *footerView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.newsTableView.width, 60)];
        footerView.backgroundColor = [UIColor whiteColor];
        UIActivityIndicatorView *loadMoreIndicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
        loadMoreIndicator.color = RGBA(3, 68, 124, 1.0f);
        loadMoreIndicator.center = footerView.center;
        [footerView addSubview:loadMoreIndicator];
        /*tsv*///indicator = loadMoreIndicator;
        [loadMoreIndicator startAnimating];
        [loadMoreIndicator release];
        [_newsTableView setTableFooterView:footerView];
        [footerView release];
    }
    else
    {
        [_newsTableView setTableFooterView:nil];
    }

    if (_catId!=16 && (self.typeOfNews==0 || self.typeOfNews==1) && self.type==0 && !self.navigationItem.rightBarButtonItem.customView && [UFSLoader reachable])
    {
        UIButton *filterButton = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
        [filterButton addTarget:self action:@selector(filterButtonTap:) forControlEvents:UIControlEventTouchUpInside];
        [filterButton setImage:[UIImage imageNamed:@"icn_calculator"] forState:UIControlStateNormal];
        [filterButton setImage:[UIImage imageNamed:@"icn_calculator"] forState:UIControlStateSelected|UIControlStateHighlighted];
        UIBarButtonItem *filter = [[UIBarButtonItem alloc] initWithCustomView:filterButton];
        [filterButton release];
        self.navigationItem.rightBarButtonItem = filter;
        [filter release];
    }
    
    if (_catId==16 || _catId==-1)
    {
        _titleNavBar = @"Новости компании";
    }
    
    if (self.type==0)
    {
        if (![self.titleText isEqualToString:_titleNavBar])
        {
            [self removeAnimationsFromScrollView];
            [self setTitleText:_titleNavBar];
        }
        fetchLimit = fetchedResultsController.fetchedObjects.count;
        [_newsTableView reloadData];
        

    }
    
          
}



- (NSFetchedResultsController *)fetchedResultsController {
    
    if (fetchedResultsController != nil) {
        
        
        return fetchedResultsController;
    }
    
    
    
    /*
	 Set up the fetched results controller.
     */
    NSString *entityName = @"NewsDB";
    NSString *sortDescr = @"date";
    BOOL isACS = false;
	// Create the fetch request for the entity.
	NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
	// Edit the entity name as appropriate.
    if (self.type == 1)
    {
        isACS = true;
        entityName = @"SubCatDB";
        sortDescr = @"index";
        predicateFormat = @"categories.identifier";
    }
	NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:CoreDataManager.shared.managedObjectContext];
    
	[fetchRequest setEntity:entity];
	
	// Set the batch size to a suitable number.
	[fetchRequest setFetchBatchSize:fetchLimit];
    [fetchRequest setFetchLimit:fetchLimit];
    
	// Sort using the timeStamp property..
    NSSortDescriptor *sortDescriptor = [[NSSortDescriptor alloc] initWithKey:sortDescr ascending:isACS];
    
    NSArray *sortDescriptors = [[NSArray alloc] initWithObjects:sortDescriptor,nil];
    
    NSPredicate * predicatForResult = [NSPredicate predicateWithFormat:@"%K == %d",predicateFormat,_subCatId?_subCatId:_catId];
    if ([predicateFormat isEqualToString:@""])
    {
        predicatForResult = [NSPredicate predicateWithFormat:predicateFilter];
    }
    [fetchRequest setPredicate : predicatForResult];
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

-(void)categorieButtonTap:(UIButton *)sender
{

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
//    if (!self.type)
    {
        if (position == FrontViewPositionLeft)
        {
            [self.newsTableView setUserInteractionEnabled:YES];
            if (reloadAnimation == ReloadAnimationNone || reloadAnimation==ReloadAnimationPop)
                [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
        }
        else if (position==FrontViewPositionRight)
        {
            [self.newsTableView setUserInteractionEnabled:NO];
           if (reloadAnimation == ReloadAnimationNone || reloadAnimation==ReloadAnimationPop)
               [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"btn_nav_menu_yellow"] forState:UIControlStateNormal];
        }
    }
}
#pragma -mark PDF Button Tap
-(void)pdfButtonTap: (UIButton *)sender
{
    NSArray *arr = [((NewsDB *)[fetchedResultsController.fetchedObjects objectAtIndex:sender.tag]).files allObjects];
    arr = [arr filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"type==3"]];
    FileImageUrlDB *filePdf = arr[0];
    if (arr.count>1)
    {

        NSSortDescriptor *sortD= [NSSortDescriptor sortDescriptorWithKey:@"name" ascending:YES];
        NSArray *sortedArr = [arr sortedArrayUsingDescriptors:[NSArray arrayWithObject:sortD]];
        PdfTableViewController *pdfTView = [[PdfTableViewController alloc] initWithNibName:nil bundle:nil];
        [pdfTView setPdfArray:sortedArr];
        [pdfTView setNameOfNews:((NewsDB *)[fetchedResultsController.fetchedObjects objectAtIndex:sender.tag]).title];
        [self.navigationController pushViewController:pdfTView animated:YES];
        [pdfTView release];
    }
    else
    {
        ReaderViewController *readerPDF = [[ReaderViewController alloc] initWithReaderDocumentURL:filePdf.url AndName:filePdf.url];
        [readerPDF setPdfName:((NewsDB *)filePdf.news).title];
        [self.navigationController pushViewController:readerPDF animated:YES];
        [readerPDF release];
    }
}

-(void)filterButtonTap:(UIButton *)sender
{
       
    if ([UFSLoader reachable])
    {
        AFJSONRequestOperation *oper = [UFSLoader requestGetDateForNewsWithCategoryId:[NSString stringWithFormat:@"%ld",(long)_catId] AndSubCatId:[NSString stringWithFormat:@"%ld",(long)_subCatId]];
        [[UFSLoader shared] enqueueHTTPRequestOperation:oper];
        [oper setCompletionBlockWithSuccess:^(AFHTTPRequestOperation *operation, id responseObject) {
            dateArray = [[responseObject objectForKey:@"result"] retain];
            [((CKCalendarView *)[self.view viewWithTag:700]) layoutSubviews];
            
        } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
            
        }];

    }

    [sender setImage:[UIImage imageNamed:@"btn_close_calendar"] forState:UIControlStateNormal];
    scrennForFilter = [[UIView alloc] initWithFrame:self.view.bounds];
    [scrennForFilter setBackgroundColor:[UIColor blackColor]];
    [scrennForFilter setAlpha:0.6f];
    
    CATransition *animation = [CATransition animation];
    [animation setDuration:0.4];
    [animation setType:kCATransitionPush];
    [animation setSubtype:kCATransitionFromTop];
    [animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
    [animation setRemovedOnCompletion:YES];
    [scrennForFilter.layer addAnimation:animation forKey:nil];
    [self.view addSubview:scrennForFilter];
        
    CKCalendarView *calendarView = [[CKCalendarView alloc] initWithStartDay:startMonday];
    calendarView.tag=700;
    [calendarView.layer addAnimation:animation forKey:nil];
    [calendarView setOnlyShowCurrentMonth:NO];
    [calendarView setDelegate:self];
    
    [self.view addSubview:calendarView];
    [calendarView release];
    UISwipeGestureRecognizer *swipeGesture = [[UISwipeGestureRecognizer alloc] initWithTarget:self action:@selector(panDescription:)];
    swipeGesture.direction = UISwipeGestureRecognizerDirectionDown;
    [calendarView addGestureRecognizer:swipeGesture];
    [swipeGesture release];
    // animation
    
    [((UIButton *)self.navigationItem.rightBarButtonItem.customView) removeTarget:self action:@selector(filterButtonTap:) forControlEvents:UIControlEventTouchUpInside];
    [((UIButton *)self.navigationItem.rightBarButtonItem.customView) addTarget:self action:@selector(cancelFilterTap:) forControlEvents:UIControlEventTouchUpInside];

}
-(void)panDescription:(UISwipeGestureRecognizer *)gesture
{
    [self performSelector:@selector(cancelFilterTap:) withObject:nil];
}

-(void)cancelFilterTap:(UIButton *)sender
{
    CATransition *animation = [CATransition animation];
    [animation setDuration:0.4];
    [animation setType:kCATransitionReveal];
    [animation setSubtype:kCATransitionFromBottom];
    [animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
    [animation setRemovedOnCompletion:YES];
    [[self.view viewWithTag:600].layer addAnimation:animation forKey:nil];
    [[self.view viewWithTag:700].layer addAnimation:animation forKey:nil];
    [[self.view viewWithTag:600] setHidden:YES];
    [[self.view viewWithTag:700] setHidden:YES];
    [scrennForFilter.layer addAnimation:animation forKey:nil];
    [scrennForFilter setHidden:YES];
    [self.navigationController.navigationBar setUserInteractionEnabled:true];
    [((UIButton *)self.navigationItem.rightBarButtonItem.customView) setImage:[UIImage imageNamed:@"icn_calculator"] forState:UIControlStateNormal];
    
    [((UIButton *)self.navigationItem.rightBarButtonItem.customView) removeTarget:self action:@selector(cancelFilterTap:) forControlEvents:UIControlEventTouchUpInside];
    [((UIButton *)self.navigationItem.rightBarButtonItem.customView) addTarget:self action:@selector(filterButtonTap:) forControlEvents:UIControlEventTouchUpInside];
    
    double delayInSeconds = 0.5;
    dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
    dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
        [[self.view viewWithTag:600] removeFromSuperview];
        [[self.view viewWithTag:700] removeFromSuperview];
        [scrennForFilter removeFromSuperview];
        scrennForFilter = nil;
    });
    
}
#pragma -mark Calendar delegate
- (void)calendar:(CKCalendarView *)calendar configureDateItem:(UIButton *)dateItem forDate:(NSDate *)date
{
    [[dateItem viewWithTag:99990] removeFromSuperview];
    if (dateArray.count)
    {
        for (int i=0; i<dateArray.count;i++)
        {
            NSInteger dateInt = [(NSNumber *)[dateArray objectAtIndex:i] intValue];
            NSDate *dateFromArray = [NSDate dateWithTimeIntervalSince1970:dateInt];
            NSString *str = @"icn_calendar_dot_blue";
            if ([dateFromArray compare:date]==NSOrderedSame)
            {
                if ([calendar date:dateFromArray isSameDayAsDate:[NSDate date]])
                {
                    str = @"icn_calendar_dot_yellow";
                }
                UIImageView *buble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:str]];

                [dateItem addSubview:buble];
                [buble setCenter:CGPointMake(22,38)];
                buble.tag = 99990;
                [buble release];
            }
        }
    }
}
-(BOOL)calendar:(CKCalendarView *)calendar willSelectDate:(NSDate *)date
{
    NSDateComponents *day = [[NSCalendar currentCalendar] components:NSYearCalendarUnit|NSMonthCalendarUnit fromDate:date];
    NSDateComponents *day2 = [[NSCalendar currentCalendar] components:NSYearCalendarUnit|NSMonthCalendarUnit fromDate:[NSDate date]];
    if (day.month > day2.month) {
        return NO;
    } else {
        return YES;
    }

    
}
- (void)calendar:(CKCalendarView *)calendar didSelectDate:(NSDate *)date
{
        NSInteger dateFor = [date timeIntervalSince1970];
    if ([dateArray indexOfObject:@(dateFor)]!=NSNotFound)
    {
        date = [date dateByAddingTimeInterval:86400];
        NSInteger curr = [date timeIntervalSince1970];
       if ([UFSLoader reachable])
        {
            [UFSLoader requestPostAuth:@"" andWidth:@""];
            [UFSLoader requestPostMainNews:[NSString stringWithFormat:@"%d %d",curr,dateFor] CategoryId:[NSString stringWithFormat:@"%d",_catId] andSubCategoryId:[NSString stringWithFormat:@"%d",_subCatId] andNewsID:@""];
            predicateFormat = @"";
            predicateFilter = [NSString stringWithFormat:@"categoryID==%d AND subcategoryID = %d AND date>%d AND date<%d",_catId,_subCatId,dateFor,curr];
            
            NSError *error = nil;
            SAFE_KILL(fetchedResultsController);
            if (![[self fetchedResultsController] performFetch:&error]) {
                NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
            }
            filterTap = YES;
            [_newsTableView reloadData];
            [self performSelector:@selector(cancelFilterTap:) withObject:nil];

        }

    }

}
- (BOOL)calendar:(CKCalendarView *)calendar willChangeToMonth:(NSDate *)date
{
    NSInteger dateInt = [(NSNumber *)[dateArray lastObject] intValue];
    NSDate *lastDate = [NSDate dateWithTimeIntervalSince1970:dateInt];
    NSDateComponents *currMonth = [[NSDateComponents alloc] init];
    [currMonth setCalendar:[NSCalendar currentCalendar]];
     NSDateComponents *newMonth = [currMonth.calendar components:(NSYearCalendarUnit | NSMonthCalendarUnit) fromDate:date];
    NSDateComponents *topMonth = [currMonth.calendar components:(NSYearCalendarUnit| NSMonthCalendarUnit) fromDate:lastDate];
    currMonth = [currMonth.calendar components:(NSYearCalendarUnit | NSMonthCalendarUnit) fromDate:[NSDate date]];
    [topMonth setMonth:topMonth.month-1];
    if (([newMonth month]>[currMonth month] && [newMonth year]==[currMonth year]) || [newMonth isEqual:topMonth])
    {
        return NO;
    }
    return YES;
}

-(void)loadMoreTap:(UIButton *)sender
{

    NSDate *date = [NSDate dateWithTimeIntervalSince1970:([((NewsDB *)fetchedResultsController.fetchedObjects.lastObject).date intValue]-1)];
    //NSString *dateFrom = [NSString stringWithFormat:@"%f",[date timeIntervalSince1970]]; bug
    /* tsv */NSString *dateFrom = [NSString stringWithFormat:@"%d",(int)[date timeIntervalSince1970]];
    if ([UFSLoader reachable])
    {
        [UFSLoader requestPostAuth:@"" andWidth:@""];
        [UFSLoader requestPostMainNews:dateFrom CategoryId:(_catId?[NSString stringWithFormat:@"%d",_catId]:@"") andSubCategoryId:(_subCatId?[NSString stringWithFormat:@"%d",_subCatId]:@"") andNewsID:@""];
        
    }
    fetchLimit=fetchedResultsController.fetchedObjects.count+20;
    NSError *error;
    SAFE_KILL(fetchedResultsController);
    if (![[self fetchedResultsController] performFetch:&error]) {
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
    }
    [_newsTableView reloadData];


}
#pragma -mark Reachable Methods
- (void) reachOn: (NSNotification *)notif
{
    if (exitAlert)
    {
        [exitAlert dismissWithClickedButtonIndex:1 animated:YES];
        exitAlert = nil;
    }
    
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    NSLog(@"reach in tv");

    if (imageForNotReachble)
    {
        [imageForNotReachble removeFromSuperview];
        imageForNotReachble = nil;
    }

    if (!fetchedResultsController.fetchedObjects.count && indicator!=nil && ![indicator isAnimating])
    {
        [indicator startAnimating];
        
    }
    //if (fetchedResultsController.fetchedObjects.count && [indicator isAnimating])
    if (fetchedResultsController.fetchedObjects.count)
    {
        //[indicator stopAnimating];
        /* tsv */[self stopIndicator];
    }
    
        if (self.type==0 && fetchedResultsController.fetchedObjects.count)
        {
                       UIView *footerView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.newsTableView.width, 60)];
            footerView.backgroundColor = [UIColor whiteColor];
            UIActivityIndicatorView *loadMoreIndicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
            loadMoreIndicator.color = RGBA(3, 68, 124, 1.0f);
            loadMoreIndicator.center = footerView.center;
            [footerView addSubview:loadMoreIndicator];
            /* tsv *///indicator = loadMoreIndicator;
            [loadMoreIndicator startAnimating];
            [loadMoreIndicator release];
            [_newsTableView setTableFooterView:footerView];
            [footerView release];
            int y_position = _newsTableView.contentOffset.y/153+_newsTableView.width/153;
            if (y_position==fetchedResultsController.fetchedObjects.count-1 && (fetchedResultsController.fetchedObjects.count<self.allNewsCount || _allNewsCount==0))
            {
                [_newsTableView setContentOffset:CGPointMake(0, _newsTableView.contentOffset.y+60) animated:YES];
                [self performSelector:@selector(loadMoreTap:) withObject:nil];
            }
        }
        else if (self.type==0 && !fetchedResultsController.fetchedObjects.count)
        {
            [self reloadVC];
        }
        if ([[userDef valueForKey:kFirstTimeStart] isEqualToNumber:@(0)])
        {
            [userDef setValue:@(1) forKey:kFirstTimeStart];
            [userDef synchronize];
        }
    

}
- (void) reachOff: (NSNotification *)notif
{
    NSLog(@"reach no main news");
    
    //if ([indicator isAnimating])
    /* tsv */if(indicator!=nil && [indicator isAnimating])
    {
        //[indicator stopAnimating];
        /* tsv */[self stopIndicator];
        if (!imageForNotReachble)
        {
            imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
            [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
            [self.view addSubview:imageForNotReachble];
            [imageForNotReachble release];
        }
        ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = true;

    }
    
    if (![UFSLoader reachable] && !fetchedResultsController.fetchedObjects.count)
    {
        
        {
            
            ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = true;
            if (!exitAlert)
            {
                double delayInSeconds = 1.0;
                dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
                dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
                    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
                    if ([[userDef valueForKey:kFirstTimeStart] isEqual:@(0)])
                    {
                        exitAlert = [[UIAlertView alloc] initWithTitle:@"Ошибка" message:@"Для отображения данных необходимо подключение к сети" delegate:self cancelButtonTitle:@"Выход" otherButtonTitles: nil] ;
                        exitAlert.tag = 11111;
                        [exitAlert show];
                        [exitAlert release];
                        
                    }
                    
                });
            }
        }
    }

                if (_newsTableView.tableFooterView)
                {
                    int y_position = _newsTableView.contentOffset.y/153+_newsTableView.width/153;
                    if (y_position==fetchedResultsController.fetchedObjects.count-1 && (fetchedResultsController.fetchedObjects.count<self.allNewsCount || _allNewsCount==0))
                    {
                    [_newsTableView setContentOffset:CGPointMake(0, self.newsTableView.contentOffset.y-60) animated:YES];
                    }
                    double delayInSeconds = _newsTableView.decelerationRate;
                    dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
                    dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
                        [_newsTableView setTableFooterView:nil];
                    });
                }
    
                

}
- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex
{
    
    if (alertView==exitAlert)
    {
        if (buttonIndex==0)
        {
//            abort();
            exit(0);
        }
        exitAlert = nil;
    }
    else
    {
        if ([UFSLoader reachable])
        {
            [UFSLoader requestPostAuth:@"" andWidth:@""];
            [UFSLoader requestPostMainNews:@"" CategoryId:(_catId?[NSString stringWithFormat:@"%d",_catId]:@"") andSubCategoryId:(_subCatId?[NSString stringWithFormat:@"%d",_subCatId]:@"") andNewsID:@""];
        }
  
    }
}
-(void)loadFaild: (NSNotification *) notify
{
    isPreviosLoadFailed = true;
    float delay = reloadAnimation==ReloadAnimationNone?0:0.4;
    double delayInSeconds = delay;
    dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
    dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
        
        /* tsv */[self stopIndicator];

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
    {
        if ([UFSLoader reachable])
        {
            UIAlertView *alertFaild = [[UIAlertView alloc] initWithTitle:@"Внимание" message:messageNotify delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil];
            [alertFaild show];
            [alertFaild release];
        }
        
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
    ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = true;
}

-(void) setMenuEnabled:(NSNotification *)notify
{
    BOOL enabled = [notify.object boolValue];
    NSLog(@"menu is %c",enabled);
    if (enabled)
    {
        [_menu addTarget:[self revealViewController] action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
    }
    else
    {
        [_menu removeTarget:self action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
    }
}
@end
