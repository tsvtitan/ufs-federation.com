//
//  UFSActionsDetailVC.m
//  UFS
//
//  Created by mihail on 15.11.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSActionsDetailVC.h"

@interface UFSActionsDetailVC ()
{
    UIImageView *imageForNotReachble;
}

@end

@implementation UFSActionsDetailVC
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
    float currentHeight=0;
       
    
    if (self.type==5)
    {
        if (self.navigationController.viewControllers.count>1)
        {
            self.navigationController.viewControllers = @[self.navigationController.topViewController];
        }
        
        oldHtml = NO;
        if ([UFSLoader reachable])
        {
            [UFSLoader requestPostHTMLDataWithCategoryIdentifier:[NSString stringWithFormat:@"%d",self.catID] andSubCategoryId:[NSString stringWithFormat:@"%d",self.subCatID]];
            oldHtml = YES;
        }
        
        [[NSNotificationCenter defaultCenter] removeObserver:self];
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOn:) name:kReachableOk object:nil];
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOff:) name:kNotReachable object:nil];
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(loadFaild:) name:kNotificationRequestFaild object:nil];

        SWRevealViewController *revealController = [self revealViewController];
        self.revealViewController.delegate = self;
        self.view.backgroundColor = [UIColor whiteColor];
        
        UIPanGestureRecognizer *customRecognizer =
        [[[UIPanGestureRecognizer alloc] initWithTarget:[self revealViewController] action:@selector(_handleRevealGesture:)] autorelease];
        [self.navigationController.navigationBar addGestureRecognizer:customRecognizer];
        [self.view addGestureRecognizer:revealController.panGestureRecognizer];
        UIButton *menu = [[UIButton alloc] initWithFrame:CGRectMake(0.0f, (self.view.frame.size.height - 44)/2.0f, 44.0f, 44.0f)];
        [menu setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
        [menu addTarget:revealController action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
        UIBarButtonItem *revealButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:menu] autorelease];
        [menu release];
        
        self.navigationItem.leftBarButtonItem = revealButtonItem;
        
        textForStock = [[UIWebView alloc] initWithFrame:CGRectMake(0, 0, self.view.width, self.view.height)];
        [textForStock setBackgroundColor:[UIColor whiteColor]];
        [textForStock.scrollView setScrollEnabled:TRUE];
        [textForStock setDelegate:self];
        [textForStock setAutoresizingMask:UIViewAutoresizingFlexibleHeight];
        [textForStock setHidden:YES];
        [self.view addSubview:textForStock];
        
        
        indicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
        indicator.color = RGBA(3, 68, 124, 1.0f);
        indicator.center = CGPointMake(self.view.width/2, self.view.height/2-indicator.frame.size.height/2);
        [self.view addSubview:indicator];
        [indicator startAnimating];
        
        NSError *error = nil;
        if (![[self fetchedResultsController] performFetch:&error]) {
            NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
        }
        
        if (fetchedResultsController.fetchedObjects.count && !oldHtml)
        {
            [indicator stopAnimating];
            HTMLDataDB *htmlObj = [fetchedResultsController.fetchedObjects objectAtIndex:0];
            [textForStock loadHTMLString:htmlObj.text baseURL:[NSURL URLWithString:kServerBaseURL]];
        }
        else if (![UFSLoader reachable])
        {
            [indicator stopAnimating];
            imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
            [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
            [self.view addSubview:imageForNotReachble];
            [imageForNotReachble release];
        }
        
        
    }
    else
    {
        UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
        UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
        [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
        [backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
        [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
        self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
        [backbutton release];

        bgScroll = [[UIScrollView alloc] initWithFrame:self.view.bounds];
        [bgScroll setBackgroundColor:[UIColor whiteColor]];
        bgScroll.autoresizingMask = UIViewAutoresizingFlexibleHeight;
        [self.view addSubview:bgScroll];
        [bgScroll release];
        if (self.stockObj)
        {
            if (_stockObj.mainImg.length)
            {
                /* tsv *///imageForStock = [[UIImageView alloc] initWithFrame:CGRectMake(0, 0, self.view.width, 200)];
                if ([FileSystem pathExisted:[_stockObj.mainImg stringByReplacingOccurrencesOfString:@"files" withString:@"image"]])
                {
                    NSLog(@"image found");
                     /* tsv *///[imageForStock setImage:[FileSystem imageWithPath:[_stockObj.mainImg stringByReplacingOccurrencesOfString:@"files" withString:@"image"]]];
                }
                else
                {
                    NSLog(@"start loading");
                    [UFSLoader getImage:_stockObj.mainImg AndName:[_stockObj.mainImg stringByReplacingOccurrencesOfString:@"files" withString:@"image"]];
                    //            [[NSNotificationCenter defaultCenter] removeObserver:self];
                    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(updateImageInActionPreview:) name:kNotificationImageLoaded object:[_stockObj.mainImg stringByReplacingOccurrencesOfString:@"files" withString:@"image"]];
                    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(updateImageInActionPreview:) name:kNotificationImageLoadFailed object:[_stockObj.mainImg stringByReplacingOccurrencesOfString:@"files" withString:@"image"]];
                }

    //            [imageForStock setImageWithURL:[NSURL URLWithString:[NSString stringWithFormat:kServerBasePath,_stockObj.mainImg]]];
                /* tsv */
                /*[imageForStock.layer setShadowColor:RGBA(123, 159, 183, 1.0f).CGColor];
                [imageForStock.layer setShadowOffset:CGSizeMake(-1.0, 7.0)];
                [imageForStock.layer setShadowOpacity:0.3f];
                [bgScroll addSubview:imageForStock];
                [imageForStock release];
                currentHeight = imageForStock.height;*/
                currentHeight = 0;
                /* tsv */
            }
            if (_stockObj.text.length)
            {
                //textForStock = [[UIWebView alloc] initWithFrame:CGRectMake(0, imageForStock.height+10.0f, self.view.width, self.view.height-(imageForStock.height+5.0f))];
                /*tsv */textForStock = [[UIWebView alloc] initWithFrame:CGRectMake(0, 10.0f, self.view.width, self.view.height-(5.0f))];
                [textForStock setBackgroundColor:[UIColor clearColor]];
                [textForStock loadHTMLString:_stockObj.text baseURL:nil];
                [textForStock.scrollView setScrollEnabled:false];
                [textForStock setDelegate:self];
                [bgScroll addSubview:textForStock];
                //[textForStock release];
                //currentHeight = self.view.height-(imageForStock.height+5.0f);
                /* tsv */currentHeight = self.view.height-(5.0f);
            }
            if (_stockObj.url.length)
            {
                detailLoadButton = [[UIButton alloc] initWithFrame:CGRectMake((self.view.width-100)/2.0f, currentHeight+10.0f, 100.0f, 30.0f)];
                [detailLoadButton addTarget:self action:@selector(linkTapped:) forControlEvents:UIControlEventTouchUpInside];
                [detailLoadButton setBackgroundImage:[[UIImage imageNamed:@"btn_load_more"] stretchableImageWithLeftCapWidth:10.0f topCapHeight:0.0f] forState:UIControlStateNormal];
                [detailLoadButton setTitle:@"Подробнее" forState:UIControlStateNormal];
                [detailLoadButton setTitleColor:RGBA(91, 119, 142, 1.0f) forState:UIControlStateNormal];
                [bgScroll addSubview:detailLoadButton];
                [detailLoadButton release];
            }
            
        }
    }
     self.titleText = _titleNavBar;
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

-(void)dealloc
{
    /* tsv */
    fetchedResultsController.delegate = nil;
    SAFE_KILL(fetchedResultsController);
    /* tsv */
    [super dealloc];
}

/* tsv */
-(void)viewWillDisappear:(BOOL)animated
{
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [super viewWillDisappear:animated];
}
/* tsv */

-(void)updateImageInActionPreview:(NSNotification *)notification
{
    if ([notification.name isEqualToString:kNotificationImageLoaded])
    {
        /* tsv *///[imageForStock setImage:[FileSystem imageWithPath:[notification.object stringByReplacingOccurrencesOfString:@"files" withString:@"image"]]];
    }
    else
    {
        /* tsv *///[imageForStock setImage:[UIImage imageNamed:@"icn_logo"]];
    }
}

-(void)BackButtonTapped: (UIButton *)sender
{
    [self.navigationController popViewControllerAnimated:YES];
}
- (void)webViewDidFinishLoad:(UIWebView *)webView
{
    if ([indicator isAnimating])
    {
        [indicator stopAnimating];
    }
    if (self.type==2)
    {
        float h = webView.scrollView.contentSize.height;
        //textForStock.frame = CGRectMake(0, imageForStock.height+10.0f, self.view.width, h);
        /* tsv */textForStock.frame = CGRectMake(0, 10.0f, self.view.width, h);
        if (_stockObj.url.length)
        {
            //detailLoadButton.frame = CGRectMake((self.view.width-100)/2.0f, imageForStock.height+20.0f+h, 100.0f, 30.0f);
            /* tsv */detailLoadButton.frame = CGRectMake((self.view.width-100)/2.0f, 20.0f+h, 100.0f, 30.0f);
        }
        //[bgScroll setContentSize:CGSizeMake(self.view.width, imageForStock.height+textForStock.height+detailLoadButton.height)];
        /* tsv */[bgScroll setContentSize:CGSizeMake(self.view.width, textForStock.height+detailLoadButton.height)];
    }
}
-(void)webView:(UIWebView *)webView didFailLoadWithError:(NSError *)error
{
    if (self.type==5)
    {
        if (!imageForNotReachble)
        {
            /*[indicator stopAnimating];
            imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
            [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
            [self.view addSubview:imageForNotReachble];
            [imageForNotReachble release];*/
        }
    }
}
- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType
{
    if (navigationType==UIWebViewNavigationTypeLinkClicked)
    {
        requestUri = request.URL;
        NSString *message = @"Перейти по ссылке?";
        NSString *str = [[NSString stringWithFormat:@"%@",requestUri] stringByReplacingOccurrencesOfString:@"%20" withString:@""];
        if ([str rangeOfString:@"tel:"].location!=NSNotFound)
        {
            requestUri = [NSURL URLWithString:[str replaceHTMLTags]];
            str = [str stringByReplacingOccurrencesOfString:@"tel:" withString:@""];
            message = @"Позвонить?";
        }
        if ([str rangeOfString:@"mailto:"].location!=NSNotFound)
        {
            requestUri = [NSURL URLWithString:[str replaceHTMLTags]];
            str = [str stringByReplacingOccurrencesOfString:@"mailto:" withString:@""];
            message = @"Написать письмо?";
        }
        UIAlertView *alertForURL = [[UIAlertView alloc] initWithTitle:message message:str delegate:self cancelButtonTitle:@"Отмена" otherButtonTitles:@"Ok", nil];
        [alertForURL show];
        [alertForURL release];
       
        return NO;
    }
    if (navigationType == UIWebViewNavigationTypeOther)
        return YES;
    
    return NO;
}

#pragma  -mark AlertView Delegate
- (void)alertView:(UIAlertView *)alertView willDismissWithButtonIndex:(NSInteger)buttonIndex
{
    if (buttonIndex)
    {
        NSString *url = alertView.message;
        if ([alertView.title rangeOfString:@"Позвонить"].location!=NSNotFound)
        {
            url = [NSString stringWithFormat:@"tel:%@",url];
        }
        if ([alertView.title rangeOfString:@"Написать"].location!=NSNotFound)
        {
             url = [NSString stringWithFormat:@"mailto:%@",url];
        }
       [[UIApplication sharedApplication] openURL:[NSURL URLWithString:url]];
    }
}

#pragma  - mark NSFetchedResultControllerDelegate
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller {
    
    NSLog(@"%@",fetchedResultsController.fetchedObjects);
    if ((self.fetchedResultsController.fetchedObjects.count > 0))
    {
        if (self.type==5 && oldHtml) {
        
            HTMLDataDB *htmlObj = [fetchedResultsController.fetchedObjects objectAtIndex:0];
            [textForStock loadHTMLString:htmlObj.text baseURL:[NSURL URLWithString:kServerBaseURL]];
            [textForStock setHidden:NO];
            oldHtml = NO;
        }
    }
    
}

- (NSFetchedResultsController *)fetchedResultsController {
    
    if (fetchedResultsController != nil) {
        return fetchedResultsController;
    }
    
	// Create the fetch request for the entity.
	NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
	// Edit the entity name as appropriate.
   	NSEntityDescription *entity = [NSEntityDescription entityForName:@"HTMLDataDB" inManagedObjectContext:CoreDataManager.shared.managedObjectContext];
    
	[fetchRequest setEntity:entity];
	
	// Set the batch size to a suitable number.
	[fetchRequest setFetchBatchSize:20];
    [fetchRequest setFetchLimit:20];
    
	// Sort using the timeStamp property..
    
    
    NSArray *sortDescriptors = @[];
    NSPredicate *predicatForResult = [NSPredicate predicateWithFormat:@"identifier == %d",_dataID];
    if (self.type==5)
    {
        //predicateFormat = _catID?[NSString stringWithFormat:@"categoryID == %d AND identifier == %d",_catID, _dataID]:[NSString stringWithFormat:@"subcategoryID == %d AND identifier == %d",_subCatID, _dataID];
        
        /* tsv */
        if (self.catID) {
            
            NSString *s1 = [@(self.catID) stringValue];
            if (self.catID==0) s1 = @"00";
            
            NSString *s2 = [@(self.subCatID) stringValue];
            if (self.subCatID==0) s2 = @"00";
            
            NSInteger ident = [[NSString stringWithFormat:@"%@%@",s1,s2] integerValue];
            predicatForResult = [NSPredicate predicateWithFormat:@"identifier == %d",ident];
            
        } else {
            
            predicatForResult = [NSPredicate predicateWithFormat:@"subcategoryID == %d AND identifier == %d",_subCatID, _dataID];
        }
        /* tsv */
    }
    
    [fetchRequest setPredicate: predicatForResult];
	[fetchRequest setSortDescriptors:sortDescriptors];
    
    // Use the sectionIdentifier property to group into sections.
	
    NSFetchedResultsController *localFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:fetchRequest managedObjectContext:CoreDataManager.shared.managedObjectContext sectionNameKeyPath:nil  cacheName:nil];
    localFetchedResultsController.delegate = self;
	self.fetchedResultsController = localFetchedResultsController;
	
	[localFetchedResultsController release];
	[fetchRequest release];
    
    return fetchedResultsController;
}

- (void)revealController:(SWRevealViewController *)revealController willMoveToPosition:(FrontViewPosition)position
{
    if (position == FrontViewPositionLeft)
    {
        [textForStock setUserInteractionEnabled:YES];
        [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
    }
    else if (position==FrontViewPositionRight)
    {
        [textForStock setUserInteractionEnabled:NO];
        [((UIButton *)self.navigationItem.leftBarButtonItem.customView) setImage:[UIImage imageNamed:@"btn_nav_menu_yellow"] forState:UIControlStateNormal];
    }
}

#pragma -mark Reachable notifications
- (void) reachOn: (NSNotification *)notif
{
    /*if (self.type==5 && !fetchedResultsController.fetchedObjects.count)
        if (imageForNotReachble)
        {
            [imageForNotReachble removeFromSuperview];
            imageForNotReachble = nil;
            indicator.center = CGPointMake(self.view.width/2, self.view.center.y-(IS_IPHONE_5?60.0f:10.0f));
            [indicator startAnimating];
            if ([UFSLoader reachable])
            {
                [UFSLoader requestPostHTMLDataWithCategoryIdentifier:[NSString stringWithFormat:@"%d",self.catID] andSubCategoryId:[NSString stringWithFormat:@"%d",self.subCatID]];
                oldHtml = YES;
            }

        } */
    
    /* tsv */
    if (self.type==5 && !oldHtml) {
     
        [indicator startAnimating];
        if ([UFSLoader reachable]) {
            
            [UFSLoader requestPostHTMLDataWithCategoryIdentifier:[NSString stringWithFormat:@"%d",self.catID] andSubCategoryId:[NSString stringWithFormat:@"%d",self.subCatID]];
            oldHtml = YES;
        }
     
     }
    /* tsv */
}

- (void) reachOff: (NSNotification *)notif
{
    if (self.type==5)
    {
        [indicator stopAnimating];
        if (!fetchedResultsController.fetchedObjects.count && !imageForNotReachble)
        {
            imageForNotReachble = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_logo"]];
            [imageForNotReachble setCenter:CGPointMake(self.view.center.x, (self.view.height-imageForNotReachble.height/2.0f)/2.0f)];
            [self.view addSubview:imageForNotReachble];
            [imageForNotReachble release];
        }
    }
}

-(void)loadFaild: (NSNotification *) notify
{
    if ([indicator isAnimating])
    {
        [indicator stopAnimating];
        
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
            [UFSLoader stopAndClear];
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
