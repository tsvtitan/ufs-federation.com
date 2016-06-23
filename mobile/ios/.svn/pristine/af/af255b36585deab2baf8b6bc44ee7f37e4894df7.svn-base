//
//  UFSEmitentsVC.m
//  UFS
//
//  Created by mihail on 16.09.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
// and !!!

#import "UFSEmitentsVC.h"
#import "AnalyticsCounter.h"

/* tsv */
@interface UIButtonEx : UIButton
@property(nonatomic, retain) NSString *url;
@end

@implementation UIButtonEx
@end
/* tsv */

@interface UFSEmitentsVC ()
{
    UIPanGestureRecognizer *customRecognizer;
    UIImageView *imageForNotReachble;
}

@end



@implementation UFSEmitentsVC



@synthesize fetchedResultsController;

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
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

    currentTable = 0;
    lastSelectedCaption = -5;
    //       UFSFakeVC *fakeVC = [[UFSFakeVC alloc] initWithNibName:nil bundle:nil];
//    [self.navigationController pushViewController:fakeVC animated:NO];
//    [fakeVC release];
//    [[UIApplication sharedApplication] setStatusBarOrientation:UIInterfaceOrientationLandscapeLeft animated:YES];
    NSError *error = nil;
    if (![[self fetchedResultsController] performFetch:&error]) {
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
    }

//    [[UIApplication sharedApplication] setStatusBarOrientation:UIDeviceOrientationLandscapeLeft animated:YES];
    [self.view setBackgroundColor:RGBA(231, 234, 236, 1.0f)];
    if ([UFSLoader reachable])
    {
        if (_subcatID)
            [UFSLoader requestPostTableNewsWithSubCategoryId:[NSString stringWithFormat:@"%d",_subcatID]];
    }
        
    if (!UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
    {
        SWRevealViewController *revealController = [self revealViewController];
        self.revealViewController.delegate = self;
        UIButton *menu = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
        [menu setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
        [menu addTarget:revealController action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
        UIBarButtonItem *revealButtonItem = [[UIBarButtonItem alloc] initWithCustomView:menu];
        [menu release];
        self.navigationItem.leftBarButtonItem = revealButtonItem;
    }

// Caption Buttons
        
    
    bgScroll = [[UIScrollView alloc] initWithFrame:CGRectMake(0, 0, self.view.width, self.view.height)];
    [bgScroll setBackgroundColor:[UIColor clearColor]];
    bgScroll.delegate = self;
    [self.view addSubview:bgScroll];
    [bgScroll release];
    captionView = [[UIView alloc] initWithFrame:CGRectMake(0, 0, self.view.width, 48.0f)];
    [captionView setBackgroundColor:RGBA(231, 234, 236, 1.0f)];
    
    [bgScroll addSubview:captionView];
    [captionView release];

    _landscapeTableView = [[UITableView alloc] initWithFrame:CGRectMake(0, 0, self.view.width, self.view.height-captionView.height) style:UITableViewStylePlain];
    [_landscapeTableView setBackgroundColor:RGBA(231, 234, 236, 1.0f)];
    [_landscapeTableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    [_landscapeTableView setSeparatorColor:RGBA(192, 207, 220, 1.0f)];
    [_landscapeTableView setScrollEnabled:YES];

    [_landscapeTableView setClipsToBounds:YES];
    [_landscapeTableView setPagingEnabled:NO];
    
    

    [bgScroll setBackgroundColor:_landscapeTableView.backgroundColor];
    
//    [_landscapeTableView setIndicatorStyle:UIScrollViewIndicatorStyleDefault];
    [_landscapeTableView setDataSource:self];
    [_landscapeTableView setDelegate:self];
    [bgScroll addSubview:_landscapeTableView];
    [_landscapeTableView release];
    
    separatorLine = [[UILabel alloc] initWithFrame:CGRectMake(0, 47,_landscapeTableView.width, 1.0f)];
    separatorLine.backgroundColor = RGBA(192, 207, 220, 1.0f);
    [captionView addSubview:separatorLine];
    [separatorLine release];
    indicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
    indicator.color = RGBA(3, 68, 124, 1.0f);
    //indicator.center = CGPointMake(self.view.width/2, self.view.center.y-44.0f);
    /* tsv */indicator.center = CGPointMake(self.view.width/2, self.view.height/2-indicator.frame.size.height/2);
    [self.view addSubview:indicator];
    [indicator startAnimating];
    [indicator release];

    if (![UFSLoader reachable])
    {
        [indicator stopAnimating];
    }

   
        
    if ([fetchedResultsController fetchedObjects].count)
        [self setInitialTableView];
}

-(void) viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    if (!UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
    {
        customRecognizer = [[[UIPanGestureRecognizer alloc] initWithTarget:[self revealViewController] action:@selector(_handleRevealGesture:)] autorelease];
        [self.navigationController.navigationBar addGestureRecognizer:customRecognizer];
        
        [self.view addGestureRecognizer:[self revealViewController].panGestureRecognizer];

        splashView = [[UIView alloc] initWithFrame:self.view.bounds];
        [splashView setBackgroundColor:RGBA(231, 234, 236, 1.0f)];
        [self.view addSubview:splashView];
        [splashView release];
        UIImageView *imageForSplash = [[UIImageView alloc] initWithFrame:CGRectMake((self.view.width-190)/2,( self.view.height-160)/2, 190, 160)];
        [imageForSplash setImage:[UIImage imageNamed:@"icn_rotate"]];
        [splashView addSubview:imageForSplash];
        [imageForSplash release];
    }

}
-(void) setInitialTableView
{
//    NSLog(@"setInitialTableView");
    if (buttonsInSection.count)
    {
        for (int i=0;i<[buttonsInSection count];i++)
        {
            [((UIButton *)[buttonsInSection objectAtIndex:i]) removeFromSuperview];
        }
       [buttonsInSection removeAllObjects];
        [buttonsInSection release];
        buttonsInSection = nil;
    }
    if (valueAll.count)
    {
        [valueAll removeAllObjects];
        [valueAll release];
        valueAll = nil;
    }
    if (navButtons.count)
    {
        [navButtons removeAllObjects];
        [navButtons release];
        navButtons = nil;
    }
    if (sectionsNames.count)
    {
        [sectionsNames removeAllObjects];
        [sectionsNames release];
        sectionsNames = nil;
    }
    if (aligmentArray.count)
    {
        [aligmentArray removeAllObjects];
        [aligmentArray release];
        aligmentArray = nil;
    }
    /* tsv */
    if (buyurlsAll.count)
    {
        [buyurlsAll removeAllObjects];
        [buyurlsAll release];
        buyurlsAll = nil;
    }
    /* tsv */
    if (columnsNames.count)
    {
        [columnsNames removeAllObjects];
        [columnsNames release];
        columnsNames = nil;
    }
    if (sectionViewArray.count)
    {
        [sectionViewArray removeAllObjects];
        [sectionViewArray release];
        sectionViewArray = nil;
    }
    sectionViewArray = [[NSMutableArray alloc] init];
    buttonsInSection = [[NSMutableArray alloc] init];
    valueAll = [[NSMutableArray alloc] init];
    navButtons = [[NSMutableArray alloc] init];
    sectionsNames = [[NSMutableArray alloc] init];
    aligmentArray = [[NSMutableArray alloc] init];
    /* tsv */buyurlsAll = [[NSMutableArray alloc] init];
    columnsNames = [[NSMutableArray alloc] init];
    
    
    [_landscapeTableView reloadData];
    [self.navigationItem.rightBarButtonItem.customView removeFromSuperview];
    TableViewDB *tableViewObj = [fetchedResultsController.fetchedObjects objectAtIndex:currentTable];
    if (tableViewObj.descriptionText.length)
    {
        UIImage *descrImage = [UIImage imageNamed:@"icn_nav_inf"];
        //    imgBtn = [imgBtn stretchableImageWithLeftCapWidth:12 topCapHeight:0];
        UIButton *descriptionButton = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
        [descriptionButton setImage:descrImage forState:UIControlStateNormal];
        [descriptionButton addTarget:self action:@selector(descriptionButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
        [descriptionButton.titleLabel setTextAlignment:NSTextAlignmentCenter];
        //    [backbutton setTag:[subCat.categories.identifier intValue]];
        self.navigationItem.rightBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:descriptionButton] autorelease];
        [descriptionButton release];
    }
    if (fetchedResultsController.fetchedObjects.count>1)
    {
        
        for (UIButton *button in self.navigationController.navigationBar.subviews)
        {
            if ([button isKindOfClass:[UIButton class]])
                [button removeFromSuperview];
        }

        float widthForNavBarButton = 50.0f;
        for (int i=0; i<fetchedResultsController.fetchedObjects.count; i++)
        {
            TableViewDB *tableObj = [fetchedResultsController.fetchedObjects objectAtIndex:i];
            UIButton *navButton = [[UIButton alloc] initWithFrame:CGRectMake(widthForNavBarButton,!UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation)?5:2, ((self.view.width-100.0f)/fetchedResultsController.fetchedObjects.count), 27)];
            navButton.backgroundColor = [UIColor clearColor];
            [navButton setTitle:tableObj.name forState:UIControlStateNormal];
            if (!i)
            {
            [navButton setBackgroundImage:[[UIImage imageNamed:@"btn_switch_left"] stretchableImageWithLeftCapWidth:12.0f topCapHeight:12.0f] forState:UIControlStateNormal];
            [navButton setBackgroundImage:[[UIImage imageNamed:@"btn_switch_left_"] stretchableImageWithLeftCapWidth:12.0f topCapHeight:12.0f] forState:UIControlStateSelected];
            }
            else if (i==fetchedResultsController.fetchedObjects.count-1)
            {
                [navButton setBackgroundImage:[[UIImage imageNamed:@"btn_switch_rite"] stretchableImageWithLeftCapWidth:12.0f topCapHeight:12.0f] forState:UIControlStateNormal];
                [navButton setBackgroundImage:[[UIImage imageNamed:@"btn_switch_rite_"] stretchableImageWithLeftCapWidth:12.0f topCapHeight:12.0f] forState:UIControlStateSelected];
            }
            else
            {
                [navButton setBackgroundImage:[[UIImage imageNamed:@"btn_switch_mid"] stretchableImageWithLeftCapWidth:12.0f topCapHeight:12.0f] forState:UIControlStateNormal];
                [navButton setBackgroundImage:[[UIImage imageNamed:@"btn_switch_mid_"] stretchableImageWithLeftCapWidth:12.0f topCapHeight:12.0f] forState:UIControlStateSelected];
            }
            [navButton setTitleColor:RGBA(169, 186, 199, 1.0f) forState:UIControlStateNormal];
            [navButton setTitleColor:RGBA(228, 206, 79, 1.0f) forState:UIControlStateSelected];
            [navButton.titleLabel setFont:[UIFont fontWithName:@"Arial-BoldItalicMT" size:13]];
            [navButton.titleLabel setLineBreakMode:NSLineBreakByWordWrapping|NSLineBreakByTruncatingTail];
            [navButton.titleLabel setNumberOfLines:1];
            [navButton setTitleEdgeInsets:UIEdgeInsetsMake(5, 2, 5, 2)];
            navButton.tag = i+700;
            [navButton addTarget:self action:@selector(changeTableForTableView:) forControlEvents:UIControlEventTouchUpInside];
            [self.navigationController.navigationBar addSubview:navButton];
            [navButtons addObject:navButton];
            [navButton release];
            if (i==currentTable)
                [navButton setSelected:YES];
            widthForNavBarButton+= ((self.view.width-100)/fetchedResultsController.fetchedObjects.count);
            if (!UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
            {
                [navButton setHidden:YES];
                self.titleText = self.titleNavBar;
            }
        }
    }
    else
    {
        self.titleText = tableViewObj.name;
    }
// Creating arrays and fillings it
    
    if (fetchedResultsController.fetchedObjects.count)
    {
        if (indicator.isAnimating)
        {
            [indicator stopAnimating];
           
        }
        
        NSString *dataString = ((TVRows *)[[((TableViewDB *)[fetchedResultsController.fetchedObjects objectAtIndex:currentTable]).columns allObjects] objectAtIndex:0]).column;
        BOOL stop = false;
//    NSArray *columnsArray = [((TableViewDB *)[fetchedResultsController.fetchedObjects objectAtIndex:currentTable]).columns allObjects];
    NSArray *valueArray = [((TableViewDB *)[fetchedResultsController.fetchedObjects objectAtIndex:currentTable]).values allObjects];
        
        while (!stop) {
            NSString *str = @"";
            NSRange locationOfDevider = [dataString rangeOfString:@" | "];
            if (locationOfDevider.location!=NSNotFound)
            {
                str = [dataString substringToIndex:locationOfDevider.location];
//                dataString = [dataString stringByReplacingOccurrencesOfString:[NSString stringWithFormat:@"%@ | ",str] withString:@""];
                int len = (locationOfDevider.location+locationOfDevider.length);
                NSRange newRange = {0,len};
                dataString = [dataString stringByReplacingCharactersInRange:newRange withString:@""];
//                NSLog(@"caption %@",dataString);
                
            }
            else
            {
                str = dataString;
                stop = true;
            }
            if (str.length){
                [columnsNames addObject:str];
            }

        }
        int columnsCount = columnsNames.count;
    
      /* tsv */
      NSMutableArray *buyurlsInSections = [[NSMutableArray alloc] init];
      NSMutableArray *buyurlsTemp = [[NSMutableArray alloc] init];
      NSArray *sa = [((TableViewDB *)[fetchedResultsController.fetchedObjects objectAtIndex:currentTable]).buyurls allObjects];
      if (sa.count) {
          NSString *s = ((TVRows *)[sa objectAtIndex:0]).column;
          stop = false;
          while (!stop) {
              NSString *str = @"";
              NSRange locationOfDevider = [s rangeOfString:@"| "];
              if (locationOfDevider.location!=NSNotFound) {
                  str = [s substringToIndex:locationOfDevider.location];
                  int len = (locationOfDevider.location+locationOfDevider.length);
                  NSRange newRange = {0,len};
                  s = [s stringByReplacingCharactersInRange:newRange withString:@""];
              } else {
                  str = s;
                  stop = true;
              }
              str = [str stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
              if (str.length) {
           
              }
              [buyurlsTemp addObject:str];
          }
      }
      /* tsv */
        
    // Parsing values data for Section and rows
        int k=0;
    NSMutableArray *valuesInSections = [[NSMutableArray alloc] init];
    for (int i=0;i<valueArray.count;i++)
    {
        
        /* tsv */ NSPredicate *predicate = [NSPredicate predicateWithFormat:@"index==%d",i+3];
        //NSPredicate *predicate = [NSPredicate predicateWithFormat:@"index==%d",i+2];
        NSArray *arr = [valueArray filteredArrayUsingPredicate:predicate];
        NSMutableArray *valuesArray = [[NSMutableArray alloc] init];
        dataString = ((TVRows *)[arr objectAtIndex:0]).column;
//        NSLog(@"values count %@",dataString);
        stop = false;
        while (!stop) {
            NSString *str = @"";
            NSRange locationOfDevider = [dataString rangeOfString:@" | "];
//            NSLog(@"range %d",locationOfDevider.location);
            if (locationOfDevider.location!=NSNotFound)
            {
                str = [dataString substringToIndex:locationOfDevider.location];
//                dataString = [dataString stringByReplacingOccurrencesOfString:[NSString stringWithFormat:@"%@ | ",str] withString:@""];
                int len = (locationOfDevider.location+locationOfDevider.length);
                NSRange newRange = {0,len};
                dataString = [dataString stringByReplacingCharactersInRange:newRange withString:@""];
//                NSLog(@"%@",dataString);
            }
            else
            {
                str = dataString;
                stop = true;
            }
            if (str.length){
                [valuesArray addObject:str];
            }
            
        }
//        NSLog(@"%@",valuesArray);
       if (valuesArray.count)
       {
           BOOL isGroup = true;
           for (int k=0;k<valuesArray.count-1;k++)
           {
               if (![[valuesArray objectAtIndex:k] isEqual:[valuesArray objectAtIndex:k+1]])
               {
                   isGroup = false;
               }
           }
        if (isGroup && valuesArray.count==columnsCount)
        {
            [sectionsNames addObject:[valuesArray objectAtIndex:0]];
            if (valuesInSections.count)
            {
//                NSLog(@"section %d",i);
                NSArray *arrayFromArray = [NSArray arrayWithArray:valuesInSections];
                [valueAll setObject:arrayFromArray atIndexedSubscript:k];
                [valuesInSections removeAllObjects];
                
                /* tsv */
                NSArray *temp = [NSArray arrayWithArray:buyurlsInSections];
                [buyurlsAll setObject:temp atIndexedSubscript:k];
                [buyurlsInSections removeAllObjects];
                /* tsv */
                
                k++;
            }
        }
        else
        {
            [valuesInSections addObject:valuesArray];
            
            /* tsv */
            if (buyurlsTemp.count>i) {
              [buyurlsInSections addObject:[buyurlsTemp objectAtIndex:i]];
            }
            /* tsv */
            
//            for (int j = 0;j<columnsCount;j++)
//           {
//               NSString *str = ((TVRows *)[valuesArray objectAtIndex:j]).column;
//               float widthForCaption = [str sizeWithFont:[UIFont fontWithName:@"Helvetica-Bold" size:14] constrainedToSize:CGSizeMake(MAXFLOAT, 48)].width;
//               if (widthForCaption>[[widthC objectAtIndex:j] integerValue])
//               {
//                   [widthC setObject:@(widthForCaption) atIndexedSubscript:j];
//               }
//           }
        }
//           NSLog(@"values count %d",valuesArray.count);
       }
//        [valuesArray removeAllObjects];
        [valuesArray release];
        
    }
    /* tsv */[buyurlsTemp release];
//        [widthForCell addObjectsFromArray:widthArray];
        
        
        if (valuesInSections.count)
            [valueAll addObject:valuesInSections];
        [valuesInSections release];
        
        /* tsv */
        if (buyurlsInSections.count)
            [buyurlsAll addObject:buyurlsInSections];
        [buyurlsInSections release];
        /* tsv */
        
        [self setWidthForRowsInCells];
        
        
//        if ([widthForCell count])
//        {
//            for (int i=0;i<widthForCell.count;i++)
//            {
//                tableW+=[[widthForCell objectAtIndex:i] floatValue]+12.0f;
//            }
//            [captionView setFrame:CGRectMake(0, 0, tableW, 48.0f)];
//            [self setCaptionButtons];
//            [self.landscapeTableView setFrame:CGRectMake(0, captionView.height, tableW, self.view.height-captionView.height)];
//            [self.landscapeTableView setContentSize:CGSizeMake(tableW, self.view.height-captionView.height)];
//            [bgScroll setFrame:CGRectMake(0, 0, self.view.width, self.view.height)];
//            [bgScroll setContentSize:CGSizeMake(_landscapeTableView.width, self.view.height)];
//           
//            [_landscapeTableView reloadData];
//        
//        }
    }
}
- (void)setWidthForRowsInCells
{
//    NSLog(@"setWidthForRowsInCells");
    if (widthForCell.count)
    {
        [widthForCell release];
        widthForCell = nil;
    }
    if (widthC.count)
    {
        [widthC removeAllObjects];
        [widthC release];
        widthC = nil;
    }
    widthC = [[NSMutableArray alloc] init];
    int count = [columnsNames count];
    NSMutableArray *aligmentArr = [[NSMutableArray alloc] init];
    NSString *dataString = ((TVRows *)[[((TableViewDB *)[fetchedResultsController.fetchedObjects objectAtIndex:currentTable]).aligment allObjects] objectAtIndex:0]).column;
    BOOL stop = false;
    while (!stop) {
        NSString *str = @"";
        NSRange locationOfDevider = [dataString rangeOfString:@" | "];
        if (locationOfDevider.location!=NSNotFound)
        {
            str = [dataString substringToIndex:locationOfDevider.location];
            int len = (locationOfDevider.location+locationOfDevider.length);
            NSRange newRange = {0,len};
            dataString = [dataString stringByReplacingCharactersInRange:newRange withString:@""];
//            NSLog(@"%@",dataString);
        }
        else
        {
            str = dataString;
            stop = true;
        }
        if (str.length){
            [aligmentArr addObject:str];
        }
        
    }
    

    
    NSString *logStr = @"";
    for (int i=0;i<count;i++)
    {
        
        NSString *str = [columnsNames objectAtIndex:i];
        float widthForCaption = [str sizeWithFont:[UIFont fontWithName:@"Helvetica-Bold" size:14]].width;
        
        
        widthC[i] = @(widthForCaption);
        if (i<aligmentArr.count)
            [aligmentArray addObject:[aligmentArr objectAtIndex:i]];
        logStr = [logStr stringByAppendingFormat:@"%f  ",widthForCaption];
        
    }
//    NSLog(@"%@",logStr);
    logStr = @"";
//    NSLog(@"widhts count int %d",widthC.count);
    if ([valueAll count])
        for (int i=0;i<valueAll.count;i++)
        {
            NSArray *sectionOne = [valueAll objectAtIndex:i];
            if (sectionOne.count)
            {
                for (int k=0;k<sectionOne.count;k++)
                {
                    if ([[sectionOne objectAtIndex:k] isKindOfClass:[NSArray class]])
                    {
                        NSArray *tempArr = ((NSArray *)[sectionOne objectAtIndex:k]);
//                            tempArr = [tempArr sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];                        
                                NSMutableArray *arrW = [[NSMutableArray alloc] init];
//                        NSLog(@"temp arr count = %d",tempArr.count);
                                for (int x=0;x<widthC.count;x++)
                                {
                                    if (widthC.count>tempArr.count)
                                    {
                                       [arrW addObject:[widthC objectAtIndex:x]]; 
                                    }
                                    else
                                    {
                                        NSString *str = [tempArr objectAtIndex:x];
                                        float widthForCaption = [str sizeWithFont:[UIFont fontWithName:@"Helvetica-Bold" size:14] constrainedToSize:CGSizeMake(MAXFLOAT, 48)].width;
    //                                    float widthForCaption = [str sizeWithFont:[UIFont fontWithName:@"Helvetica-Bold" size:14]].width;
                                        if (widthForCaption>[[widthC objectAtIndex:x] floatValue])
                                        {
//                                            NSLog(@"%d = %f old %f",x,widthForCaption,[[widthC objectAtIndex:x] floatValue]);
    //                                        [widthC setObject:@(widthForCaption) atIndexedSubscript:x];
                                            [arrW setObject:@(widthForCaption) atIndexedSubscript:x];
//                                                NSLog(@"%d = %f",x,widthForCaption);
                                        }
                                        else
                                        {
                                             [arrW addObject:[widthC objectAtIndex:x]];
                                        }
//                                            logStr = [logStr stringByAppendingFormat:@"%f  ",widthForCaption];

                                    }
                                                                    }
                                SAFE_KILL(widthC);
                                widthC = [[NSMutableArray alloc] initWithArray:arrW copyItems:YES];
                                SAFE_KILL(arrW);
//                                 NSLog(@"%@",logStr);
                                logStr = @"";
                            }
                            
                        }

                    }
        }
//    NSLog(@"Final count");
    
    /* tsv */
    BOOL exists = false;
    for (int i=0;i<buyurlsAll.count;i++) {
        
        NSArray *urls = [buyurlsAll objectAtIndex:i];
        
        for (int j=0;j<urls.count;j++) {
            NSString *s  = [urls objectAtIndex:j];
            exists = (![s isEqualToString:@""]);
            if (exists) {
                break;
            }
        }
        if (exists) {
            break;
        }
    }
    /* tsv */
    
    if (widthC.count)
    {
         /* tsv */
        float w = [[widthC objectAtIndex:0] floatValue];
        if (exists) {
            w = w + 40.0f;
        }
        [widthC replaceObjectAtIndex:0 withObject:@(w)];
        /* tsv */
        
        widthForCell = [[NSArray alloc] initWithArray:widthC copyItems:YES];
        
    }
//    NSLog(@"widhts count %d",widthForCell.count);
    float tableW=0;
    if ([widthForCell count])
    {
        for (int i=0;i<widthForCell.count;i++)
        {
            logStr = [logStr stringByAppendingFormat:@"%f  ",[[widthForCell objectAtIndex:i] floatValue]];
            tableW+=[[widthForCell objectAtIndex:i] floatValue]+12.0f;
        }
//        NSLog(@"%@",logStr);
        [captionView setFrame:CGRectMake(0, 0, tableW, 48.0f)];
        [self setCaptionButtons];
        [self.landscapeTableView setFrame:CGRectMake(0, captionView.height, tableW, self.view.height-captionView.height)];
        [self.landscapeTableView setContentSize:CGSizeMake(tableW, self.view.height-captionView.height)];
        [bgScroll setFrame:CGRectMake(0, 0, self.view.width, self.view.height)];
        [bgScroll setContentSize:CGSizeMake(_landscapeTableView.width, self.view.height)];
        [separatorLine setFrame:CGRectMake(0, 47, _landscapeTableView.width, 1.0f)];
        [_landscapeTableView reloadData];
        
    }

}

- (void)setCaptionButtons
{
// NSLog(@"setCaptionButtons");
    [captionView removeAllSubviews];
    if (fetchedResultsController.fetchedObjects.count)
    {
        float width = 0;
        float wForLab=0.0f;
        NSString *logStr= @"";
//        TableViewDB *tableViewObj = [fetchedResultsController.fetchedObjects objectAtIndex:currentTable];
//        int columnsCount = [tableViewObj.columns allObjects].count;
//        NSArray *columnsArray = [((TableViewDB *)[fetchedResultsController.fetchedObjects objectAtIndex:currentTable]).columns allObjects];
//        
//        columnsArray = [columnsArray sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];
       
        
      
        for (int i=0; i<[columnsNames count]; i++)
        {
            
            UIButton *buttonColumn = [[UIButton alloc] initWithFrame:CGRectMake(width, 0, [[widthForCell objectAtIndex:i] integerValue]+10, 48.0f)];
            [buttonColumn setBackgroundColor:[UIColor clearColor]];
            [buttonColumn setBackgroundImage:[UIImage imageNamed:@"bg_table_header_select"] forState:UIControlStateSelected];
            buttonColumn.tag = i+500;
            [buttonColumn setTitle:[columnsNames objectAtIndex:i] forState:UIControlStateNormal];
            [captionView addSubview:buttonColumn];
            [buttonColumn.titleLabel setFont:[UIFont fontWithName:@"Helvetica-Bold" size:14]];
            [buttonColumn setTitleColor:RGBA(106, 132, 162, 1.0f) forState:UIControlStateNormal];
            [buttonColumn setTitleColor:RGBA(216, 185, 48, 1.0f) forState:UIControlStateSelected];
            UIImageView *arrowImage = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_arr_gold"]];
            arrowImage.center = CGPointMake(buttonColumn.width-15.0f, 40.0f);
            arrowImage.tag = 1024;
            arrowImage.hidden = YES;
            [buttonColumn addSubview:arrowImage];
            [arrowImage release];
            [buttonColumn addTarget:self action:@selector(captionButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
             wForLab+=[[widthForCell objectAtIndex:i] floatValue]+10;
            UILabel *thinLable = [[UILabel alloc] initWithFrame:CGRectMake(wForLab, 0, 1, 48)];
            [thinLable setBackgroundColor:RGBA(192, 207, 220, 1.0f)];
            if (i<columnsNames.count-1)
                [captionView addSubview:thinLable];
            [thinLable release];
            if (i<[aligmentArray count])
            {
                if ([[aligmentArray objectAtIndex:i] isEqualToString:@"left"])
                {
                    [buttonColumn  setTitleEdgeInsets:UIEdgeInsetsMake(0, 5, 0, -5)];
                    [buttonColumn setContentHorizontalAlignment:UIControlContentHorizontalAlignmentLeft];
                    [buttonColumn.titleLabel setTextAlignment:NSTextAlignmentLeft];
                }
                else  if ([[aligmentArray objectAtIndex:i] isEqualToString:@"center"])
                {
                    [buttonColumn setContentHorizontalAlignment:UIControlContentHorizontalAlignmentCenter];
                    [buttonColumn.titleLabel setTextAlignment:NSTextAlignmentCenter];
                }
                else  if ([[aligmentArray objectAtIndex:i] isEqualToString:@"right"])
                {
                     [buttonColumn  setTitleEdgeInsets:UIEdgeInsetsMake(0, -5, 0, 5)];
                    [buttonColumn setContentHorizontalAlignment:UIControlContentHorizontalAlignmentRight];
                    [buttonColumn.titleLabel setTextAlignment:NSTextAlignmentRight];
                }
                
            }
            else
            {
                [buttonColumn setContentHorizontalAlignment:UIControlContentHorizontalAlignmentCenter];
                [buttonColumn.titleLabel setTextAlignment:NSTextAlignmentCenter];
            }
            logStr = [logStr stringByAppendingFormat:@"%f  ",[[widthForCell objectAtIndex:i] floatValue]];
            [buttonsInSection addObject:buttonColumn];
            [buttonColumn release];
            
            //        float widthForCaption = [buttonColumn.titleLabel.text sizeWithFont:buttonColumn.titleLabel.font constrainedToSize:CGSizeMake(MAXFLOAT, 48)].width;
            //        [buttonColumn setFrame:CGRectMake(width, 0, widthForCaption+10, 48)];
            width+=([[widthForCell objectAtIndex:i] floatValue]+10);
            //        [widthForCell addObject:@(widthForCaption)];
            if (buttonColumn.tag==lastSelectedCaption)
            {
                [buttonColumn setSelected:YES];
                //            lastSelectedCaption = buttonColumn.tag;
            }
            
            

            
        }
        //        NSLog(@"Caption \n %@",logStr);
    }
    separatorLine = [[UILabel alloc] initWithFrame:CGRectMake(0, 47,bgScroll.contentSize.width, 1.0f)];
    separatorLine.backgroundColor = RGBA(192, 207, 220, 1.0f);
    [captionView addSubview:separatorLine];
    [separatorLine release];
}
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
-(void)dealloc
{
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    if (scrennForDescript)
    {
        [scrennForDescript release];
        scrennForDescript = nil;
    }
    [sectionsNames removeAllObjects];
    [sectionsNames release];
    [aligmentArray removeAllObjects];
    [aligmentArray release];
    [buyurlsAll removeAllObjects];
    [buyurlsAll release];
    [valueAll removeAllObjects];
    [valueAll release];
    [navButtons removeAllObjects];
    [navButtons release];
    fetchedResultsController.delegate = nil;
    _landscapeTableView.dataSource = nil;
    _landscapeTableView.delegate=nil;
    [buttonsInSection removeAllObjects];
    [buttonsInSection release];
    [widthC removeAllObjects];
    [widthC release];
    [widthForCell release];
    _landscapeTableView.dataSource = nil;
    _landscapeTableView.delegate = nil;
    fetchedResultsController.delegate = nil;
    SAFE_KILL(fetchedResultsController);
    [super dealloc];
}
-(void)BackButtonTapped:(UIButton *)sender
{
    for (UIButton *button in self.navigationController.navigationBar.subviews)
    {
        if ([button isKindOfClass:[UIButton class]])
            [button removeFromSuperview];
    }
//    for (int i=0;i<navButtons.count;i++)
//    {
//        [(UIButton *)[navButtons objectAtIndex:i] removeFromSuperview];
//    }
//    [self.navigationController.navigationBar removeAllSubviews];
//    [self.navigationController.navigationBar setBackgroundImage:[UIImage imageNamed:@"navbar"] forBarMetrics:UIBarMetricsDefault];
    [self.navigationController popViewControllerAnimated:NO];
}
#pragma -mark TableView DataSourse & Delegate

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
            return 48.0f;
   
}
- (CGFloat)tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section
{
    if (sectionsNames.count)
    {
        return 28.0f;
    }
    return 0.0f;
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    if (valueAll.count)
    {
        if (((NSMutableArray *)[valueAll objectAtIndex:section]).count)
        {
            return ((NSMutableArray *)[valueAll objectAtIndex:section]).count;
        }
        
    }
    return 0;
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    
    return valueAll.count;
}
-(UIView *) tableView:(UITableView *)tableView viewForHeaderInSection:(NSInteger)section
{
//     NSLog(@"viewForHeaderInSection");
    UIView *bgView= [[UIView alloc] initWithFrame:CGRectZero];
    if (sectionsNames.count)
    {
//        NSLog(@"sections %d",section);
        
        int width = 0;
        for (int i=0; i<widthForCell.count; i++) {
            width+=[[widthForCell objectAtIndex:i] intValue]+12;
        }
            [bgView setFrame:CGRectMake(bgScroll.contentOffset.x, 0, bgScroll.width-bgScroll.contentOffset.x,28.0f)];
        [bgView setBackgroundColor:RGBA(200, 205, 206, 1.0f)];
        // Gradient
    //        CAGradientLayer *gradient = [[CAGradientLayer alloc] init];
    //        [gradient setColors:[NSArray arrayWithObjects:(id)[[UIColor clearColor] CGColor],(id)[RGBA(225, 225, 225, 1.0f) CGColor], nil]];
    //        gradient.frame = bgView.bounds;
    //        [gradient setStartPoint:CGPointMake(0.0, 0.0)];
    //        [gradient setEndPoint:CGPointMake(0.0, 1.0)];
    //
    //        [bgView.layer addSublayer:gradient];
    //        [gradient release];

        // End Gradient
        UILabel *titleLable = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, self.view.width, 28)];
        [titleLable setBackgroundColor:[UIColor clearColor]];
        titleLable.text = [sectionsNames objectAtIndex:section];
        titleLable.textColor = RGBA(0, 69, 109, 1.0f);
        titleLable.font = [UIFont fontWithName:@"Helvetica-Bold" size:14];
        titleLable.textAlignment = NSTextAlignmentCenter;
        [bgView addSubview:titleLable];
            if ([sectionViewArray count])
            {
                if (sectionViewArray.count<section)
                {
                    if (![[sectionViewArray objectAtIndex:section] isEqual:titleLable])
                    {
                        [sectionViewArray setObject:titleLable atIndexedSubscript:section];
                    }
                }
                else
                {
                    [sectionViewArray addObject:titleLable];
                }
            }
            else
            {
                [sectionViewArray addObject:titleLable];
            }
        [titleLable release];
    
    }
        return bgView;
}

//- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath
//{
////    [UIView animateWithDuration:0.4f animations:^{
////        for (int i=0;i<sectionViewArray.count;i++)
////        {
////            [(UILabel *)[sectionViewArray objectAtIndex:i] setFrame:CGRectMake(bgScroll.contentOffset.x, 0, self.view.width,28.0f)];
////        }
////    }];
//    for (int i=0;i<sectionViewArray.count;i++)
//    {
//        [(UILabel *)[sectionViewArray objectAtIndex:i] setFrame:CGRectMake(bgScroll.contentOffset.x, 0, self.view.width,28.0f)];
//    }
//
//
//}

/* tsv */
- (IBAction)customActionPressed:(UIButton *)sender
{
    UIButtonEx *button = (UIButtonEx *)sender;
    NSString *s = [NSString stringWithFormat:kServerRootFmt,button.url];
    
    s = [s stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    if (![s isEqualToString:@""]) {
        [AnalyticsCounter eventScreens:self.titles category:button.titleLabel.text action:@"open" value:s];
        [[UIApplication sharedApplication] openURL:[NSURL URLWithString:s]];
    }
}
/* tsv */

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
//     NSLog(@"cellForRowAtIndexPath");
//    height = [perodLabel.text sizeWithFont:perodLabel.font constrainedToSize:CGSizeMake(147, MAXFLOAT)].height;
    // Initializing cells
    if ([valueAll count])
    {
   
    NSInteger row = indexPath.row;
    NSInteger section = indexPath.section;
    NSArray *arr = [valueAll objectAtIndex:section];
    NSString *cellId = [NSString stringWithFormat:@"CellID"];
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellId];
       
    if (cell==nil)
    {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellId] autorelease];
        
    }
        else
        {
         [cell.contentView removeAllSubviews];
        }
        
        // Configuring cell
     
        [cell setSelectionStyle:UITableViewCellSelectionStyleNone];
       
    
        {
            NSArray *rowArray  = [arr objectAtIndex:indexPath.row];
//            rowArray = [rowArray sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];
            float width = 0;
            float wForLab = 0;
            for (int i=0;i<columnsNames.count;i++)
            {
                
                    
                    
                    
                wForLab+=[[widthForCell objectAtIndex:i] floatValue]+10;
                UILabel *thinLable = [[UILabel alloc] initWithFrame:CGRectMake(wForLab, 0, 1, 48)];
                [thinLable setBackgroundColor:RGBA(192, 207, 220, 1.0f)];
                if (i<columnsNames.count-1)
                    [cell.contentView addSubview:thinLable];
                [thinLable release];
                    
                UILabel *valueLable = [[UILabel alloc] initWithFrame:CGRectMake(width+5, 0, [[widthForCell objectAtIndex:i] floatValue], 48)];
                [valueLable setBackgroundColor:[UIColor clearColor]];
                if (i<rowArray.count)
                    [valueLable setText:[rowArray objectAtIndex:i]];
                [valueLable setFont:[UIFont fontWithName:@"Helvetica-Bold" size:14]];
                [valueLable setTextColor:RGBA(3, 68, 124, 1.0f)];
                if (i<[aligmentArray count])
                {
                    if ([[aligmentArray objectAtIndex:i] isEqualToString:@"left"])
                    {
                        
                        [valueLable setTextAlignment:NSTextAlignmentLeft];

                    }
                    else  if ([[aligmentArray objectAtIndex:i] isEqualToString:@"center"])
                    {
                        valueLable.frame = CGRectMake(width+5, 0, [[widthForCell objectAtIndex:i] floatValue], 48);
                        [valueLable setTextAlignment:NSTextAlignmentCenter];
                    }
                    else  if ([[aligmentArray objectAtIndex:i] isEqualToString:@"right"])
                    {
                        valueLable.frame = CGRectMake(width-5, 0, [[widthForCell objectAtIndex:i] floatValue]+5, 48);
                        [valueLable setTextAlignment:NSTextAlignmentRight];
                    }
//                    else  if ([((TVRows *)[aligmentArray objectAtIndex:i]).column isEqualToString:@"justify"])
//                    {
//                        valueLable.frame = CGRectMake(width+2, 0, [[widthForCell objectAtIndex:i] floatValue]+2, 48);
//                        [valueLable setTextAlignment:NSTextAlignmentN];
//                    }

                    
                }
                else
                {
                    valueLable.frame = CGRectMake(width, 0, [[widthForCell objectAtIndex:i] floatValue], 48);
                    [valueLable setTextAlignment:NSTextAlignmentCenter];
                }
                [valueLable setNumberOfLines:0];
                [cell.contentView addSubview:valueLable];
                [valueLable release];
                
                width+=([[widthForCell objectAtIndex:i] floatValue]+10);

                 /* tsv */
                BOOL showBuyButton = (i==0);
                NSString *url = @"";
                if (showBuyButton) {
                    
                    if (buyurlsAll.count>section) {
                        NSArray *urls = [buyurlsAll objectAtIndex:section];
                        url  = [urls objectAtIndex:row];
                        showBuyButton = (![url isEqualToString:@""]);
                    }  else {
                        showBuyButton = FALSE;
                    }
                }
                
                if (showBuyButton) {
     
                    UIButtonEx *button = [UIButtonEx buttonWithType:UIButtonTypeCustom];
                    [button setImage:[UIImage imageNamed:@"ico-cart-32x32"] forState:UIControlStateNormal];
                    [button addTarget:self action:@selector(customActionPressed:)forControlEvents:UIControlEventTouchDown];
                    button.frame = CGRectMake(width - 35.0f, 5.0f, 30.0f, 30.0f);
                    button.backgroundColor = [UIColor clearColor];
                    button.url = url;
                    [button setTitle:[rowArray objectAtIndex:i] forState:UIControlStateNormal];
                    [cell.contentView addSubview:button];
                    
                }
                /* tsv */
                
                
            }
            
            /*UILabel *separatorLineRorCell = [[UILabel alloc] initWithFrame:CGRectMake(0, 47, self.landscapeTableView.width, 1.0f)];
            separatorLineRorCell.backgroundColor = RGBA(192, 207, 220, 1.0f);
            [cell.contentView addSubview:separatorLineRorCell];
            [separatorLineRorCell release];*/
        }
    
    
    
    return cell;
}
    return nil;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    
}

#pragma  - mark NSFetchedResultControllerDelegate
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller {
    
    //    id object = (self.fetchedResultsController.fetchedObjects.count > 0) ? [self.fetchedResultsController.fetchedObjects objectAtIndex:0] : nil;
    //    [menuTableView setActionDate:[(NewsDB *)object date]];
    if (fetchedResultsController.fetchedObjects.count)
    {
        
        [self setInitialTableView];
        
//        TableViewDB *tableViewObj = [fetchedResultsController.fetchedObjects objectAtIndex:currentTable];
//        int columnsCount = [tableViewObj.columns allObjects].count;
//        
//        [widthForCell removeAllObjects];
//        NSArray *columnsArray = [((TableViewDB *)[fetchedResultsController.fetchedObjects objectAtIndex:currentTable]).columns allObjects];
//        columnsArray = [columnsArray sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];
//        float tableW = 0;
//        for (int i=0;i<columnsArray.count;i++)
//        {
//            float widthForCaption = [((TVRows *)[columnsArray objectAtIndex:i]).column sizeWithFont:[UIFont fontWithName:@"Helvetica-Bold" size:14] constrainedToSize:CGSizeMake(MAXFLOAT, 48)].width;
//            tableW+=widthForCaption+10;
//            [widthForCell addObject:@(widthForCaption)];
//        }
//        [self.landscapeTableView setFrame:CGRectMake(0, 0, tableW, self.view.height)];
//        [self.landscapeTableView setContentSize:CGSizeMake(tableW, _landscapeTableView.contentSize.height)];
//        [bgScroll setFrame:self.view.frame];
//        [bgScroll setContentSize:CGSizeMake(_landscapeTableView.width, _landscapeTableView.height)];
    }
    [_landscapeTableView reloadData];
    
}



- (NSFetchedResultsController *)fetchedResultsController {
    
    if (fetchedResultsController != nil) {
        return fetchedResultsController;
    }
    
    /*
	 Set up the fetched results controller.
     */
    NSString *entityName = @"TableViewDB";
    NSString *sortDescr = @"identifier";
    BOOL isACS = YES;
	// Create the fetch request for the entity.
	NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
	// Edit the entity name as appropriate.
    NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:CoreDataManager.shared.managedObjectContext];
    
	[fetchRequest setEntity:entity];
	
	// Set the batch size to a suitable number.
	[fetchRequest setFetchBatchSize:60];
    [fetchRequest setFetchLimit:0];
    
	// Sort using the timeStamp property..
    NSSortDescriptor *sortDescriptor = [[NSSortDescriptor alloc] initWithKey:sortDescr ascending:isACS];
    
    NSArray *sortDescriptors = [[NSArray alloc] initWithObjects:sortDescriptor,nil];
    
    
    NSPredicate * predicatForResult = [NSPredicate predicateWithFormat:@"subcatID == %d",_subcatID];
   
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

#pragma -mark Supported Inteface Orientation
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration
{
    [self removeAnimationsFromScrollView];
    if (scrennForDescript)
    {
        [self performSelector:@selector(cancelDescriptionTap:) withObject:nil];
    }

    if (toInterfaceOrientation!=UIInterfaceOrientationPortrait && toInterfaceOrientation!=UIInterfaceOrientationPortraitUpsideDown)
    {
        
        
        ((UIButton *)self.navigationItem.leftBarButtonItem.customView).hidden = YES;
//        ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = YES;
        [customRecognizer removeTarget:[self revealViewController] action:@selector(_handleRevealGesture:)];
        [self.view removeGestureRecognizer:[self revealViewController].panGestureRecognizer];
        if (scrennForDescript)
        {
            [scrennForDescript setFrame:CGRectMake(0, 0, self.view.width, self.view.height)];
            [[self.view viewWithTag:1700] setFrame:CGRectMake(0, 0, self.view.width, self.view.height-100)];
        }
        if (splashView)
        {
            CATransition *animation = [CATransition animation];
            [animation setDuration:duration];
            [animation setType:kCATransitionFade];
            [animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
            [animation setRemovedOnCompletion:YES];
            [splashView.layer addAnimation:animation forKey:@""];
            [splashView removeFromSuperview];
            
            splashView = nil;
            
        }
//        if (indicator.isAnimating)
            indicator.center = self.view.center;
        for (int i=0;i<sectionViewArray.count;i++)
        {
            [(UILabel *)[sectionViewArray objectAtIndex:i] setFrame:CGRectMake(bgScroll.contentOffset.x, 0, self.view.width,28.0f)];
        }
        float tableW=0;
        for (int i=0;i<widthForCell.count;i++)
        {
            tableW+=[[widthForCell objectAtIndex:i] floatValue]+10.0f;
        }
        [captionView setFrame:CGRectMake(0, 0, tableW, 48.0f)];
        [self.landscapeTableView setFrame:CGRectMake(0, 48, tableW, self.view.height-captionView.height)];
        [self.landscapeTableView setContentSize:CGSizeMake(tableW, _landscapeTableView.contentSize.height)];
        [bgScroll setFrame:CGRectMake(0, 0, self.view.width, self.view.height)];
        [bgScroll setContentSize:CGSizeMake(_landscapeTableView.width, self.view.height)];
        [separatorLine setFrame:CGRectMake(0, 47, _landscapeTableView.width, 1.0f)];
    }
    else
    {
               
        if (navButtons.count)
        {
            for (int i=0;i<navButtons.count;i++)
            {
                ((UIButton *)[navButtons objectAtIndex:i]).hidden = YES;
            }
        }

//        ((UIButton *)self.navigationItem.rightBarButtonItem.customView).hidden = NO;
        ((UIButton *)self.navigationItem.leftBarButtonItem.customView).hidden = NO;
        [customRecognizer addTarget:[self revealViewController] action:@selector(_handleRevealGesture:)];
        [self.view addGestureRecognizer:[self revealViewController].panGestureRecognizer];
       
            splashView = [[UIView alloc] initWithFrame:self.view.bounds];
            [splashView setBackgroundColor:RGBA(231, 234, 236, 1.0f)];
            UIImageView *imageForSplash = [[UIImageView alloc] initWithFrame:CGRectMake((self.view.width-190)/2,( self.view.height-160)/2, 190, 160)];
            [imageForSplash setImage:[UIImage imageNamed:@"icn_rotate"]];
            [splashView addSubview:imageForSplash];
            [imageForSplash release];
            
            CATransition *animation = [CATransition animation];
            [animation setDuration:duration];
            [animation setType:kCATransitionFade];
            [animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
            [animation setRemovedOnCompletion:YES];
            [splashView.layer addAnimation:animation forKey:@""];
             [self.view addSubview:splashView];
             [splashView release];
        
    
    }
}
- (void)didRotateFromInterfaceOrientation:(UIInterfaceOrientation)fromInterfaceOrientation
{
    if (fromInterfaceOrientation==UIInterfaceOrientationPortrait || fromInterfaceOrientation==UIInterfaceOrientationPortraitUpsideDown)
    {

        if ([self revealViewController].frontViewPosition == FrontViewPositionRight)
        {
            [[self revealViewController] revealToggleAnimated:YES];
        }
        if (!fetchedResultsController.fetchedObjects.count && ![UFSLoader reachable])
        {
            if (!imageForNotReachble)
            {
                [indicator stopAnimating];
                imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
                [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
                [self.view addSubview:imageForNotReachble];
                [imageForNotReachble release];
            }

        }
        if (fetchedResultsController.fetchedObjects.count>1)
        {
            float widthForNavBarButton = 50.0f;
            self.titleText = @"";
            for (int i=0; i<fetchedResultsController.fetchedObjects.count; i++)
            {
                if (navButtons.count)
                {
                    [(UIButton *)[navButtons objectAtIndex:i] setFrame:CGRectMake(widthForNavBarButton, 2, ((self.view.width-100.0f)/fetchedResultsController.fetchedObjects.count), 27)];
                    widthForNavBarButton+= ((self.view.width-100)/fetchedResultsController.fetchedObjects.count);
                    ((UIButton *)[navButtons objectAtIndex:i]).hidden = NO;
                }
            }
        }
        else if (fetchedResultsController.fetchedObjects.count)
        {
            self.titleText = self.titleNavBar;
        }

    }
    else
    {
        if (self.fetchedResultsController.fetchedObjects.count)
            self.titleText = self.titleNavBar;
    }
}
-(BOOL)shouldAutorotate
{
    return YES;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
//   	return  (toInterfaceOrientation == UIInterfaceOrientationLandscapeLeft|| toInterfaceOrientation == UIInterfaceOrientationLandscapeRight);
    return YES;
}
-(NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskAll;
}

-(void)captionButtonTapped:(UIButton *)sender
{
//    if (!sender.selected)
//    {
//        CATransition *animation = [CATransition animation];
//        animation.timingFunction = [CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseOut];
//        animation.type = kCATransitionFade;
//        animation.duration = 0.5f;
//        animation.removedOnCompletion = YES;
//
//        for (int i=0;i<buttonsInSection.count;i++)
//        {
//            if (((UIButton *)[buttonsInSection objectAtIndex:i]).tag==lastSelectedCaption)
//            {
//                [((UIButton *)[buttonsInSection objectAtIndex:i]).layer addAnimation:animation forKey:@""];
//                ((UIButton *)[buttonsInSection objectAtIndex:i]).selected = NO;
//                ((UIImageView *)[((UIButton *)[buttonsInSection objectAtIndex:i]) viewWithTag:1024]).hidden = true;
//            }
//        }
//        
//        [sender.layer addAnimation:animation forKey:@""];
//        [sender setSelected:YES];
//        ((UIImageView *)[sender viewWithTag:1024]).hidden = false;
//        [sender bringSubviewToFront:((UIImageView *)[sender viewWithTag:1024])];
//        lastSelectedCaption = sender.tag;
////        valueAll = [valueAll sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:sortStr ascending:YES]]];
//                       
//    }
//    else
//    {
//        ((UIImageView *)[sender viewWithTag:1024]).transform = CGAffineTransformMakeRotation(90*(3.14/180.0f));
//    }
//    [valueAll sortUsingComparator:^NSComparisonResult(id obj1, id obj2) {
//        NSArray *arrValue1 = [(NSArray *)obj1 sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];
//        NSArray *arrValue2 = [(NSArray *)obj2 sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];
//        int index = sender.tag-500;
//        NSComparisonResult isTrue = [((TVRows *)[arrValue1 objectAtIndex:index]).column compare:((TVRows *)[arrValue2 objectAtIndex:index]).column];
//        if (isTrue == NSOrderedAscending)
//        {
//            return NSOrderedAscending;
//        }
//        else if (isTrue==NSOrderedDescending)
//        {
//            return NSOrderedDescending;
//        }
//        else
//            return NSOrderedSame;
//    }];
//
//    self.landscapeTableView.dataSource = nil;
//    [_landscapeTableView reloadData];
//    
//    double delayInSeconds = 3.0;
//    dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
//    dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
//        [_landscapeTableView setDataSource:self];
//        [_landscapeTableView reloadData];
//    });

}
-(void)changeTableForTableView:(UIButton *)sender
{
    if (scrennForDescript)
    {
         [self performSelector:@selector(cancelDescriptionTap:) withObject:nil];
    }
//    if (!sender.selected)
    {
        ((UIButton *)[self.navigationController.navigationBar viewWithTag:currentTable+700]).selected = false;
        sender.selected = YES;
        int numberOfTableToChange = sender.tag-700;
        currentTable = numberOfTableToChange;
                [self setInitialTableView];

    }
}
-(void)descriptionButtonTapped:(UIButton *)sender
{
//    if (UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
    if (fetchedResultsController.fetchedObjects.count)
    {
     TableViewDB *tableViewObj = [fetchedResultsController.fetchedObjects objectAtIndex:currentTable];
    if ([tableViewObj descriptionText].length)
    {
        scrennForDescript = [[UIView alloc] initWithFrame:self.view.bounds];
        [scrennForDescript setBackgroundColor:[UIColor blackColor]];
        [scrennForDescript setAlpha:0.1f];
        
        CATransition *animation = [CATransition animation];
        [animation setDuration:0.4];
        [animation setType:kCATransitionPush];
        [animation setSubtype:kCATransitionFromBottom];
        [animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
        [animation setRemovedOnCompletion:YES];
//        [scrennForDescript.layer addAnimation:animation forKey:nil];
        [self.view addSubview:scrennForDescript];
      
        UILabel *lableDescript = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, self.view.width, (self.view.height-(!UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation)?130:100)))];
        lableDescript.numberOfLines = 0;
        lableDescript.font = [UIFont fontWithName:@"Helvetica-Bold" size:16];
        lableDescript.textColor = RGBA(0, 61, 109, 1.0f);
        
        
        lableDescript.backgroundColor = [UIColor whiteColor];
        lableDescript.tag=1700;
        [lableDescript.layer addAnimation:animation forKey:@""];
        [self.view addSubview:lableDescript];
        [lableDescript release];
        UILabel *lableText = [[UILabel alloc] initWithFrame:CGRectMake(5, 0, lableDescript.width-5, lableDescript.height)];
        lableText.autoresizingMask = UIViewAutoresizingFlexibleWidth|UIViewAutoresizingFlexibleHeight;
        lableText.numberOfLines = 0;
        lableText.font = [UIFont fontWithName:@"Helvetica-Bold" size:16];
        lableText.textColor = RGBA(0, 61, 109, 1.0f);
        
        lableText.text = [tableViewObj descriptionText];
        lableText.backgroundColor = [UIColor whiteColor];
        [lableDescript addSubview:lableText];
        [lableText release];
        UISwipeGestureRecognizer *swipeGesture = [[UISwipeGestureRecognizer alloc] initWithTarget:self action:@selector(panDescription:)];
        swipeGesture.direction = UISwipeGestureRecognizerDirectionUp;
        [scrennForDescript addGestureRecognizer:swipeGesture];
        [swipeGesture release];
        
//        UIButton *cancelButton = [[UIButton alloc] initWithFrame:CGRectMake((self.view.width-140)/2.0f, self.view.height-50.0f, 140, 30)];
//        [cancelButton.layer setCornerRadius:12.0f];
//        [cancelButton setTag:1600];
//        [cancelButton setBackgroundColor:RGBA(52, 69, 67, 1.0f)];
//        [cancelButton setTitle:@"Отмена" forState:UIControlStateNormal];
//        [cancelButton.titleLabel setFont:[UIFont fontWithName:@"Helvetica-Bold" size:16]];
//        [cancelButton setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
//        [cancelButton addTarget:self action:@selector(cancelDescriptionTap:) forControlEvents:UIControlEventTouchUpInside];
//        [cancelButton.layer addAnimation:animation forKey:nil];
//        [self.view addSubview:cancelButton];
//        [cancelButton release];
        [sender setImage:[UIImage imageNamed:@"btn_close_calendar"] forState:UIControlStateNormal];
//        [sender setImage:[UIImage imageNamed:@"btn_close_calendar"] forState:UIControlStateNormal];

        [((UIButton *)self.navigationItem.rightBarButtonItem.customView) removeTarget:self action:@selector(descriptionButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
        [((UIButton *)self.navigationItem.rightBarButtonItem.customView) addTarget:self action:@selector(cancelDescriptionTap:) forControlEvents:UIControlEventTouchUpInside];
           }
    }

}
-(void)cancelDescriptionTap:(UIButton *)sender
{
    CATransition *animation = [CATransition animation];
    [animation setDuration:0.4];
    [animation setType:kCATransitionReveal];
    [animation setSubtype:kCATransitionFromTop];
    [animation setTimingFunction:[CAMediaTimingFunction  functionWithName:kCAMediaTimingFunctionEaseInEaseOut]];
    [animation setRemovedOnCompletion:YES];
//    [[self.view viewWithTag:1600].layer addAnimation:animation forKey:nil];
    [[self.view viewWithTag:1700].layer addAnimation:animation forKey:nil];
//    [[self.view viewWithTag:1600] setHidden:YES];
    [[self.view viewWithTag:1700] setHidden:YES];
    //    [[self.view viewWithTag:600] removeFromSuperview];
    //    [[self.view viewWithTag:700] removeFromSuperview];
//    [scrennForDescript.layer addAnimation:animation forKey:nil];
    [scrennForDescript setHidden:YES];
    [((UIButton *)self.navigationItem.rightBarButtonItem.customView) setImage:[UIImage imageNamed:@"icn_nav_inf"] forState:UIControlStateNormal];

    [((UIButton *)self.navigationItem.rightBarButtonItem.customView) removeTarget:self action:@selector(cancelDescriptionTap:) forControlEvents:UIControlEventTouchUpInside];
     [((UIButton *)self.navigationItem.rightBarButtonItem.customView) addTarget:self action:@selector(descriptionButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
    [self.navigationController.navigationBar setUserInteractionEnabled:true];
    double delayInSeconds = 0.5;
    dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, (int64_t)(delayInSeconds * NSEC_PER_SEC));
    dispatch_after(popTime, dispatch_get_main_queue(), ^(void){
//        [[self.view viewWithTag:1600] removeFromSuperview];
        [[self.view viewWithTag:1700] removeFromSuperview];
        [scrennForDescript removeFromSuperview];
        [scrennForDescript release];
        scrennForDescript = nil;
    });

}
-(void)panDescription:(UISwipeGestureRecognizer *)gesture
{
    [self performSelector:@selector(cancelDescriptionTap:) withObject:nil];
}
#pragma -mark Scroll Delegate
- (void)scrollViewDidScroll:(UIScrollView *)scrollView
{
    for (int i=0;i<sectionViewArray.count;i++)
    {
        [(UILabel *)[sectionViewArray objectAtIndex:i] setFrame:CGRectMake(bgScroll.contentOffset.x, 0, self.view.width,28.0f)];
    }

}
- (void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView
{
    [UIView animateWithDuration:0.4f animations:^{
        for (int i=0;i<sectionViewArray.count;i++)
        {
            [(UILabel *)[sectionViewArray objectAtIndex:i] setFrame:CGRectMake(bgScroll.contentOffset.x, 0, self.view.width,28.0f)];
        }
    }];
}
#pragma -mark Slide menu Delegate
- (void)revealController:(SWRevealViewController *)revealController willMoveToPosition:(FrontViewPosition)position
{
    //    if (!self.type)
    {
        if (position == FrontViewPositionLeft)
        {
//            [self.landscapeTableView setUserInteractionEnabled:YES];
           
                [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
        }
        else if (position==FrontViewPositionRight)
        {
//            [self.landscapeTableView setUserInteractionEnabled:NO];
            
                [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"btn_nav_menu_yellow"] forState:UIControlStateNormal];
        }
    }
}
#pragma -mark Reachble notifications
- (void) reachOn: (NSNotification *)notif
{
    if (!fetchedResultsController.fetchedObjects.count)
    {
        if (UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
        {
            if (imageForNotReachble)
            {
                [imageForNotReachble removeFromSuperview];
                imageForNotReachble = nil;
                [indicator startAnimating];
                if (_subcatID)
                    [UFSLoader requestPostTableNewsWithSubCategoryId:[NSString stringWithFormat:@"%d",_subcatID]];
            }
        }
    }
    
}
- (void) reachOff: (NSNotification *)notif
{
    if (!fetchedResultsController.fetchedObjects.count)
    {
        if (UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
        {
            [indicator stopAnimating];
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
-(void)loadFaild: (NSNotification *) notify
{
        if ([indicator isAnimating])
        {
            [indicator stopAnimating];
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
