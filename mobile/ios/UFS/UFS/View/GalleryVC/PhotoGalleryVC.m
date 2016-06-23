//
//  PhotoGalleryVC.m
//
//  Copyright 2012 id-East. All rights reserved.
//

//include header
#import <QuartzCore/QuartzCore.h>
#import "PhotoGalleryVC.h"
#import "ZoomingPhotoScrollView.h"
#import "BackTapDetectingView.h"

//padding for pages
#define PADDING                 10 
//max
#define PAGE_INDEX_TAG_OFFSET   1000

#define kLabelTag               3

#define PAGE_INDEX(page)        ([(page) tag] - PAGE_INDEX_TAG_OFFSET)

// Private
@interface PhotoGalleryVC () {
    
	// Data
    NSUInteger    _photoCount;
	NSArray      *_itemsArray;
	
	// Views
	UIScrollView *_pagingScrollView;
    UIImageView  *_panelGallery;
    
	// Paging
	NSMutableSet *_visiblePages, *_recycledPages;
	NSUInteger    _currentPageIndex;
	NSUInteger    _pageIndexBeforeRotation;
	
	NSTimer      *_controlVisibilityTimer;
    
    // Appearance
    UIStatusBarStyle _previousStatusBarStyle;
    UIBarButtonItem *_previousViewControllerBackButton;
    
    // Misc
    BOOL _displayActionButton;
	BOOL _performingLayout;
	BOOL _rotating;
    BOOL _viewIsActive; // active as in it's in the view heirarchy
    BOOL _didSavePreviousStateOfNavBar;
    NSString       *_title;
    NSString       *_typeForGallery;
    BOOL           _otherSource;
    NSString       *_sourceUrl;
    NSString       *_sourceName;

}

// Private Properties
@property (nonatomic, retain) UIBarButtonItem *previousViewControllerBackButton;

// Private Methods

// Layout
- (void)performLayout;

// Nav Bar Appearance
- (void)setNavBarAppearance:(BOOL)animated;
- (void)storePreviousNavBarAppearance;
- (void)restorePreviousNavBarAppearance:(BOOL)animated;

// Paging
- (void)tilePages;
- (BOOL)isDisplayingPageForIndex:(NSUInteger)index;
- (ZoomingPhotoScrollView *)pageDisplayedAtIndex:(NSUInteger)index;
- (ZoomingPhotoScrollView *)dequeueRecycledPage;
- (void)configurePage:(ZoomingPhotoScrollView *)page forIndex:(NSUInteger)index;

// Frames
- (CGRect)frameForPagingScrollView;
- (CGRect)frameForPageAtIndex:(NSUInteger)index;
- (CGSize)contentSizeForPagingScrollView;
- (CGPoint)contentOffsetForPageAtIndex:(NSUInteger)index;

// Navigation
- (void)updateNavigation;
- (void)jumpToPageAtIndex:(NSUInteger)index;
- (void)gotoPreviousPage;
- (void)gotoNextPage;

// Controls
- (void)cancelControlHiding;
- (void)toggleControls;
- (void)setControlsHidden:(BOOL)hidden animated:(BOOL)animated permanent:(BOOL)permanent;

// Data
- (NSUInteger)numberOfPhotos;

@end


// PhotoGalleryVC
@implementation PhotoGalleryVC

// Properties
@synthesize displayActionButton = _displayActionButton;
@synthesize previousViewControllerBackButton = _previousViewControllerBackButton;
@synthesize titleGallery = _titleGallery;
@synthesize fetchedResultsController;


#pragma mark - NSObject
//init
- (id)init {
    if ((self = [super init])) {
        
        // Defaults
        self.wantsFullScreenLayout = NO;
        //self.hidesBottomBarWhenPushed = YES;
        _photoCount = NSNotFound;
		_currentPageIndex = 0;
		_performingLayout = NO; // Reset on view did appear
		_rotating = NO;
        _viewIsActive = NO;
        _visiblePages = [[NSMutableSet alloc] init];
        _recycledPages = [[NSMutableSet alloc] init];
        _displayActionButton = NO;
        _didSavePreviousStateOfNavBar = NO;
       
    }
    return self;
}

- (id)initWithItems:(NSArray *)objectsArray {
	if ((self = [self init])) {
		//set array
        _itemsArray = [[NSArray alloc] initWithArray: objectsArray];
	}
	return self;
}

- (id)initWithObject {
    
    if (self = [self init]) {
		//set array
//        if (object) {
//            if ([object isKindOfClass: [PreviewGalleryDB class]]) {
//                NSArray *sortedObjects = [((GalleriesDB*)object).photos.allObjects sortedArrayUsingComparator:^NSComparisonResult(id obj1, id obj2) {
//                    
//                    if ( [[obj1 identifier] intValue] > [[obj2 identifier] intValue])
//                        return NSOrderedAscending;
//                    if ([[obj1 identifier] intValue] < [[obj2 identifier] intValue])
//                        return NSOrderedDescending;
//                    return NSOrderedSame;
//                }];
//                
//                _itemsArray = [[NSArray alloc] initWithArray:sortedObjects];
//                
//                _itemsArray = [((GalleriesDB*)object).photos.allObjects retain];
//                
//            }else if([object isKindOfClass:[InfographicsDB class]]){
//                
//                _otherSource = YES;
//                _title = [@"Инфографика" retain];
//                SAFE_KILL(_itemsArray);
//                _itemsArray = [[NSArray alloc] initWithObjects: [object photo_full] ? [object photo_full] : [object previewPhoto], nil];
//                SAFE_KILL(_typeForGallery);
//                _typeForGallery = [[NSString alloc] initWithString: [object title]];
//                
//                SAFE_KILL(descriptionInf);
//                descriptionInf = [[NSString alloc] initWithString: [object descriptionIG]];
//                SAFE_KILL(_sourceName);
//                _sourceName = [[NSString alloc] initWithString: [object source].length > 0 ? [NSString stringWithFormat: @"(c) %@", [object source]] : @""];
//                
//                SAFE_KILL(_sourceUrl);
//                _sourceUrl = [[NSString alloc] initWithString: [object sourceUrl].length > 0 ? [object sourceUrl] : @""];
//            }else if ([object isKindOfClass:[NSDictionary class]]){
//                
//                _otherSource = YES;
//                _title = [@"Сюжет" retain];
//                SAFE_KILL(_itemsArray);
//                _itemsArray = [[NSArray alloc] initWithObjects: [object objectForKey:@"photo"] ? [object objectForKey:@"photo"] : @"", nil];
//                SAFE_KILL(_typeForGallery);
//                _typeForGallery = [[object objectForKey:@"title"] retain];
//                SAFE_KILL(descriptionInf);
//                descriptionInf = [[object objectForKey:@"description"] retain];
//                
//            }
//       }
    }
	return self;
}

//releas
- (void)dealloc {
    SAFE_KILL(_previousViewControllerBackButton);
	SAFE_KILL(_pagingScrollView);
	SAFE_KILL(_visiblePages);
	SAFE_KILL(_recycledPages);
  	SAFE_KILL(_itemsArray);
    SAFE_KILL(_titleGallery);
    SAFE_KILL(_title);
    SAFE_KILL(_typeForGallery);
    SAFE_KILL(fetchedResultsController);
    SAFE_KILL(descriptionInf);
    
    SAFE_KILL(_sourceUrl);
    SAFE_KILL(_sourceName);
    fetchedResultsController.delegate = nil;
    [super dealloc];
}

//remove recycled Pages
- (void)didReceiveMemoryWarning {
	
	[_recycledPages removeAllObjects];
    
    [super didReceiveMemoryWarning];
}

//====================================
#pragma mark - View Loading

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
	
    // Super
    [super viewDidLoad];
    shouldUpdate = YES;
    UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
    //    imgBtn = [imgBtn stretchableImageWithLeftCapWidth:12 topCapHeight:0];
    UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
    [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
    [backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
    [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
    self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
    [backbutton release];
    NSError *error = nil;
    if (!_itemsArray.count)
    {
        if (![[self fetchedResultsController] performFetch:&error]) {
            NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
        }
    }
        
//        imgBtn = [UIImage imageNamed:@"icn_fav"];
//    favor_button = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 40)/2.0f, 40.0f, 40.0f)];
//     
//        [favor_button setImage:imgBtn forState:UIControlStateNormal];
//        [favor_button addTarget:self action:@selector(favoriteButtonTaped:) forControlEvents:UIControlEventTouchUpInside];
//     UIBarButtonItem *favorB = [[[UIBarButtonItem alloc] initWithCustomView:favor_button] autorelease];
//    [favor_button release];
//    imgBtn = [UIImage imageNamed:@"icn_share"];
//    UIButton *share_button = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 40)/2.0f, 40.0f, 40.0f)];
//    
//    [share_button setImage:imgBtn forState:UIControlStateNormal];
//    [share_button addTarget:self action:@selector(shareButtonTaped:) forControlEvents:UIControlEventTouchUpInside];
//    UIBarButtonItem *sharerB = [[[UIBarButtonItem alloc] initWithCustomView:share_button] autorelease];
//    [share_button release];
//
//    self.navigationItem.rightBarButtonItems =  [[[NSArray alloc] initWithObjects:sharerB, favorB, nil] autorelease];
//    
    self.view.backgroundColor = [UIColor blackColor];
    [self.navigationController.navigationBar setTintColor:[UIColor blackColor]];
    [self.navigationController.navigationBar setTranslucent:YES];
    [self.navigationController.navigationBar setAlpha:0.8f];
    
       
	// View
	self.view.backgroundColor = [UIColor blackColor];
	
	// Setup paging scroll
	CGRect pagingScrollViewFrame = [self frameForPagingScrollView];
	_pagingScrollView = [[UIScrollView alloc] initWithFrame:pagingScrollViewFrame];
	_pagingScrollView.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleTopMargin | UIViewAutoresizingFlexibleLeftMargin;
	_pagingScrollView.pagingEnabled = YES;
	_pagingScrollView.delegate = self;
	_pagingScrollView.showsHorizontalScrollIndicator = NO;
	_pagingScrollView.showsVerticalScrollIndicator = NO;
	_pagingScrollView.backgroundColor = [UIColor clearColor];
    _pagingScrollView.contentSize = [self contentSizeForPagingScrollView];
	[self.view addSubview:_pagingScrollView];
    
    
    
    
        
    // Update
//    [self setObjects:nil];

	[self setControlsHidden:NO animated:NO permanent:YES];
    
    _panelGallery = [[[UIImageView alloc] initWithFrame:CGRectMake(0, self.view.height - 118, 320, 120)] autorelease];
    [self.view addSubview:_panelGallery];
    [_panelGallery setAutoresizesSubviews:YES];
    [_panelGallery setAutoresizingMask:UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleBottomMargin| UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleTopMargin];

     _panelGallery.image = [UIImage imageNamed: @"panel_gallery.png"];
        [self performLayout];
}

- (void)performLayout {
    
    // Setup
    _performingLayout = YES;    
	// Setup pages
    [_visiblePages removeAllObjects];
    [_recycledPages removeAllObjects];
    
    // Navigation buttons
//    if ([self.navigationController.viewControllers objectAtIndex:0] == self) {
//        // We're first on stack so show done button
//        UIBarButtonItem *doneButton = [[[UIBarButtonItem alloc] initWithTitle:NSLocalizedString(@"Done", nil) style:UIBarButtonItemStylePlain target:self action:@selector(doneButtonPressed:)] autorelease];
//        // Set appearance
//        if ([UIBarButtonItem respondsToSelector:@selector(appearance)]) {
//            [doneButton setBackgroundImage:nil forState:UIControlStateNormal barMetrics:UIBarMetricsDefault];
//            [doneButton setBackgroundImage:nil forState:UIControlStateNormal barMetrics:UIBarMetricsLandscapePhone];
//            [doneButton setBackgroundImage:nil forState:UIControlStateHighlighted barMetrics:UIBarMetricsDefault];
//            [doneButton setBackgroundImage:nil forState:UIControlStateHighlighted barMetrics:UIBarMetricsLandscapePhone];
//            [doneButton setTitleTextAttributes:[NSDictionary dictionary] forState:UIControlStateNormal];
//            [doneButton setTitleTextAttributes:[NSDictionary dictionary] forState:UIControlStateHighlighted];
//        }
//        self.navigationItem.rightBarButtonItem = doneButton;
//    } else {
//        //show back button
//        UIViewController *previousViewController = [self.navigationController.viewControllers objectAtIndex:self.navigationController.viewControllers.count-2];
//        NSString *backButtonTitle = previousViewController.navigationItem.backBarButtonItem ? previousViewController.navigationItem.backBarButtonItem.title : previousViewController.title;
//        UIBarButtonItem *newBackButton = [[[UIBarButtonItem alloc] initWithTitle:backButtonTitle style:UIBarButtonItemStylePlain target:nil action:nil] autorelease];
//        // Appearance
//        if ([UIBarButtonItem respondsToSelector:@selector(appearance)]) {
//            [newBackButton setBackButtonBackgroundImage:nil forState:UIControlStateNormal barMetrics:UIBarMetricsDefault];
//            [newBackButton setBackButtonBackgroundImage:nil forState:UIControlStateNormal barMetrics:UIBarMetricsLandscapePhone];
//            [newBackButton setBackButtonBackgroundImage:nil forState:UIControlStateHighlighted barMetrics:UIBarMetricsDefault];
//            [newBackButton setBackButtonBackgroundImage:nil forState:UIControlStateHighlighted barMetrics:UIBarMetricsLandscapePhone];
//            [newBackButton setTitleTextAttributes:[NSDictionary dictionary] forState:UIControlStateNormal];
//            [newBackButton setTitleTextAttributes:[NSDictionary dictionary] forState:UIControlStateHighlighted];
//        }
//        self.previousViewControllerBackButton = previousViewController.navigationItem.backBarButtonItem; // remember previous
//        previousViewController.navigationItem.backBarButtonItem = newBackButton;
//    }
//    
    // Scroll offset
	_pagingScrollView.contentOffset = [self contentOffsetForPageAtIndex:_currentPageIndex];
    [self tilePages];
    _performingLayout = NO;
    NSString *title;
    title = (_itemsArray.count ? [NSString stringWithFormat: @"1 из %d", _itemsArray.count] : @"");
    self.navigationItem.title = title;

    
}

- (void)viewDidUnload {
	
    _currentPageIndex = 0;
    SAFE_KILL(_pagingScrollView);
    SAFE_KILL(_visiblePages);
    SAFE_KILL(_recycledPages);
    [super viewDidUnload];
}

#pragma mark - Appearance

- (void)viewWillAppear:(BOOL)animated {
    
	// Super
	[super viewWillAppear:animated];
	

    backTapped = NO;
    
    [self viewWillLayoutSubviews];
    
    // Status bar
//    if (self.wantsFullScreenLayout && UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone) {
//        _previousStatusBarStyle = [[UIApplication sharedApplication] statusBarStyle];
//        [[UIApplication sharedApplication] setStatusBarStyle:UIStatusBarStyleBlackTranslucent animated:animated];
//    }
    
    // Navigation bar appearance
//    if (!_viewIsActive && [self.navigationController.viewControllers objectAtIndex:0] != self) {
//        [self storePreviousNavBarAppearance];
//    }
//
    [self setNavBarAppearance:animated];
//    
    [self updateNavigation];
    
}

- (void)viewWillDisappear:(BOOL)animated {
    
    // Check that we're being popped for good
    if ([self.navigationController.viewControllers objectAtIndex:0] != self &&
        ![self.navigationController.viewControllers containsObject:self]) {
        
        // State
        _viewIsActive = NO;
        
        // Bar state / appearance
         [self.navigationController.navigationBar setBackgroundImage:[UIImage imageNamed:@"navbar"] forBarMetrics:UIBarMetricsDefault];
           [self.navigationController.navigationBar setAlpha:1.0f];
           [self.navigationController.navigationBar setTranslucent:NO];
        [self restorePreviousNavBarAppearance:animated];
        
    }
        
    // Stop all animations on nav bar
    [self.navigationController.navigationBar.layer removeAllAnimations]; 
    
    // Cancel any pending toggles from taps
    [NSObject cancelPreviousPerformRequestsWithTarget:self];
    [self setControlsHidden:NO animated:NO permanent:YES];
    
    // Status bar
    if (self.wantsFullScreenLayout && UI_USER_INTERFACE_IDIOM() == UIUserInterfaceIdiomPhone) {
        [[UIApplication sharedApplication] setStatusBarStyle:_previousStatusBarStyle animated:animated];
    }
	// Super
//    [CoreDataManager saveParsingContext];
//    [CoreDataManager saveMainContext];
	[super viewWillDisappear:animated];
    
}

- (void)viewDidAppear:(BOOL)animated {
    
    [super viewDidAppear:animated];
    _viewIsActive = YES;
}

#pragma mark - Nav Bar Appearance

- (void)setNavBarAppearance:(BOOL)animated {
    self.navigationController.navigationBar.tintColor = nil;
    self.navigationController.navigationBar.barStyle = UIBarStyleBlackTranslucent;
    if ([[UINavigationBar class] respondsToSelector:@selector(appearance)]) {
        [self.navigationController.navigationBar setBackgroundImage:nil forBarMetrics:UIBarMetricsDefault];
        [self.navigationController.navigationBar setBackgroundImage:nil forBarMetrics:UIBarMetricsLandscapePhone];
    }
}

- (void)storePreviousNavBarAppearance {
    _didSavePreviousStateOfNavBar = YES;

}

- (void)restorePreviousNavBarAppearance:(BOOL)animated {
    if (_didSavePreviousStateOfNavBar) {
        // Restore back button if we need to
        if (_previousViewControllerBackButton) {
            UIViewController *previousViewController = [self.navigationController topViewController]; // We've disappeared so previous is now top
            previousViewController.navigationItem.backBarButtonItem = _previousViewControllerBackButton;
            self.previousViewControllerBackButton = nil;
        }
    }
}

#pragma mark - Layout

- (void)viewWillLayoutSubviews {
    
    // Super
    if (SYSTEM_VERSION_GREATER_THAN_OR_EQUAL_TO(@"5"))
        [super viewWillLayoutSubviews];
	
	// Flag
	_performingLayout = YES;
	
    // Remember index
	NSUInteger indexPriorToLayout = _currentPageIndex;
	
	// Get paging scroll view frame to determine if anything needs changing
	CGRect pagingScrollViewFrame = [self frameForPagingScrollView];
    
	// Frame needs changing
	_pagingScrollView.frame = pagingScrollViewFrame;
	
	// Recalculate contentSize based on current orientation
	_pagingScrollView.contentSize = [self contentSizeForPagingScrollView];
	
	// Adjust frames and configuration of each visible page
	for (ZoomingPhotoScrollView *page in _visiblePages) {
        NSUInteger index = PAGE_INDEX(page);
		page.frame = [self frameForPageAtIndex:index];
		[page setMaxMinZoomScalesForCurrentBounds];
	}
	
	// Adjust contentOffset to preserve page location based on values collected prior to location
	_pagingScrollView.contentOffset = [self contentOffsetForPageAtIndex:indexPriorToLayout];

	// Reset
	_currentPageIndex = indexPriorToLayout;
	_performingLayout = NO;
    
}

#pragma mark - Rotation
////ios 6
//- (BOOL)shouldAutorotate{
//    
//    return YES;
//}
//
//- (NSUInteger)supportedInterfaceOrientations{
//
//    return UIInterfaceOrientationMaskAll;
//}
////- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
////    return YES;
////}
-(BOOL)shouldAutorotate
{
    return YES;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
   	return  YES;
}
-(NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskAll;
}

- (void)willRotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
    
	// Remember page index before rotation
	_pageIndexBeforeRotation = _currentPageIndex;
	_rotating = YES;
    
    if ((toInterfaceOrientation == UIInterfaceOrientationPortrait)||(toInterfaceOrientation == UIInterfaceOrientationPortraitUpsideDown)) {
        self.view.backgroundColor = [UIColor blackColor];
    }else {
        self.view.backgroundColor = [UIColor blackColor];
    }
	[self.navigationItem setTitle:((_itemsArray.count ? [NSString stringWithFormat: @"%d из %d", _currentPageIndex + 1, _itemsArray.count] : @""))];
}

- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration {
	
	// Perform layout
	_currentPageIndex = _pageIndexBeforeRotation;
	
//    [self viewWillLayoutSubviews];
    [self updateNavigation];
    [_pagingScrollView setContentOffset:CGPointMake(_pagingScrollView.width*_pageIndexBeforeRotation, _pagingScrollView.contentOffset.y) animated:YES];


}

- (void)didRotateFromInterfaceOrientation:(UIInterfaceOrientation)fromInterfaceOrientation {
	_rotating = NO;
    [self viewWillLayoutSubviews];
//    [self updateNavigation];
//    _currentPageIndex = _pageIndexBeforeRotation;
//    [_pagingScrollView setContentOffset:CGPointMake(_pagingScrollView.width*_pageIndexBeforeRotation, _pagingScrollView.contentOffset.y) animated:YES];
}

#pragma mark - Data

- (void)reloadData {
    
    // Reset
    _photoCount = NSNotFound;
    
    // Get data
    //NSUInteger numberOfPhotos = [self numberOfPhotos];
    
    // Update
   
    [self performLayout];
    
    // Layout
    [self viewWillLayoutSubviews];
    
}

- (NSUInteger)numberOfPhotos {
    
    if (_itemsArray) {
            _photoCount = _itemsArray.count;
    }
    
    if (_photoCount == NSNotFound)
        _photoCount = 0;
    
    return _photoCount;
}

#pragma mark - Paging

- (void)tilePages {
	
	// Calculate which pages should be visible
	// Ignore padding as paging bounces encroach on that
	// and lead to false page loads
	CGRect visibleBounds = _pagingScrollView.bounds;
	int iFirstIndex = (int)floorf((CGRectGetMinX(visibleBounds)+PADDING*2) / CGRectGetWidth(visibleBounds));
	int iLastIndex  = (int)floorf((CGRectGetMaxX(visibleBounds)-PADDING*2-1) / CGRectGetWidth(visibleBounds));
    if (iFirstIndex < 0) iFirstIndex = 0;
    if (iFirstIndex > [self numberOfPhotos] - 1) iFirstIndex = [self numberOfPhotos] - 1;
    if (iLastIndex < 0) iLastIndex = 0;
    if (iLastIndex > [self numberOfPhotos] - 1) iLastIndex = [self numberOfPhotos] - 1;
	
	// Recycle no longer needed pages
    NSInteger pageIndex;
	for (ZoomingPhotoScrollView *page in _visiblePages) {
        pageIndex = PAGE_INDEX(page);
		if (pageIndex < (NSUInteger)iFirstIndex || pageIndex > (NSUInteger)iLastIndex) {
			[_recycledPages addObject:page];
			[page removeFromSuperview];
			NSLog(@"Removed page at index %i", PAGE_INDEX(page));
		}
	}
	[_visiblePages minusSet:_recycledPages];
    while (_recycledPages.count > 2) // Only keep 2 recycled pages
        [_recycledPages removeObject:[_recycledPages anyObject]];
	
	// Add missing pages
	for (NSUInteger index = (NSUInteger)iFirstIndex; index <= (NSUInteger)iLastIndex; index++) {
		if (![self isDisplayingPageForIndex:index]) {
            
            // Add new page
			ZoomingPhotoScrollView *page = [self dequeueRecycledPage];
			if (!page) {
                page = [[[ZoomingPhotoScrollView alloc] initWithPhotoScroll:self] autorelease];
            }
			if (!page.getParentScroll) {
                [page removeFromSuperview];
                [page setParentScroll: self];
                [page setAutoresizingMask: UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight];
            }
            [_pagingScrollView addSubview:page];
            [self configurePage:page forIndex:index];
			[page setMaxMinZoomScalesForCurrentBounds];
            [_visiblePages addObject:page];
            
            NSLog(@"Added page at index %i", index);
            
		}
	}
	
}

- (BOOL)isDisplayingPageForIndex:(NSUInteger)index {
	for (ZoomingPhotoScrollView *page in _visiblePages)
		if (PAGE_INDEX(page) == index)
            return YES;
	return NO;
}

- (ZoomingPhotoScrollView *)pageDisplayedAtIndex:(NSUInteger)index {
	ZoomingPhotoScrollView *thePage = nil;
	for (ZoomingPhotoScrollView *page in _visiblePages) {
		if (PAGE_INDEX(page) == index) {
			thePage = page; break;
		}
	}
	return thePage;
}


- (void)configurePage:(ZoomingPhotoScrollView *)page forIndex:(NSUInteger)index {
	if (_itemsArray.count == 0) {
        return;
    }
    page.frame = [self frameForPageAtIndex:index];
    page.tag = PAGE_INDEX_TAG_OFFSET + index;
    [page displayImage:[_itemsArray objectAtIndex:index]];
    //page.photo = [self photoAtIndex:index];
}

- (ZoomingPhotoScrollView *)dequeueRecycledPage {
	ZoomingPhotoScrollView *page = [_recycledPages anyObject];
	if (page) {
		[[page retain] autorelease];
		[_recycledPages removeObject:page];
	}
	return page;
}

#pragma mark - Frame setup

- (CGRect)frameForPagingScrollView {
    CGRect frame = self.view.bounds;// [[UIScreen mainScreen] bounds];
    frame.origin.x -= PADDING;
    frame.size.width += (2 * PADDING);
    return frame;
}

- (CGRect)frameForPageAtIndex:(NSUInteger)index {
    
    CGRect bounds = _pagingScrollView.bounds;
    CGRect pageFrame = bounds;
    pageFrame.size.width -= (2 * PADDING);
    pageFrame.origin.x = (bounds.size.width * index) + PADDING;
    return pageFrame;
}

- (CGSize)contentSizeForPagingScrollView {

    CGRect bounds = _pagingScrollView.bounds;
    return CGSizeMake(bounds.size.width * ([self numberOfPhotos] + 1*(!_otherSource)), bounds.size.height);
}

- (CGPoint)contentOffsetForPageAtIndex:(NSUInteger)index {
	
    CGFloat pageWidth = _pagingScrollView.bounds.size.width;
	CGFloat newOffset = index * pageWidth;
	return CGPointMake(newOffset, 0);
}

#pragma mark - UIScrollView Delegate

- (void)scrollViewDidScroll:(UIScrollView *)scrollView {
	if (([self numberOfPhotos] < 2) && ![_itemsArray.lastObject isKindOfClass:[NSString class]]) {
        [scrollView setScrollEnabled:NO];
        return;
        
    }
    else
    {
        [scrollView setScrollEnabled:YES];
    }
    // Checks
	if (!_viewIsActive || _performingLayout || _rotating)
        return;
	
	// Tile pages
	[self tilePages];
	
	// Calculate current page
	int index = (_pagingScrollView.contentOffset.x + _pagingScrollView.width * 0.5f) / _pagingScrollView.width;
    if (index < 0)
        index = 0;
	if (index == [self numberOfPhotos]) {
        index = 0;
        _currentPageIndex = 0;
        scrollView.scrollEnabled = NO;
        float width = scrollView.width;
        [scrollView setContentOffset:CGPointMake(0, scrollView.contentOffset.y) animated:NO];
        scrollView.frame = CGRectMake(scrollView.originX+width, scrollView.originY, width, scrollView.height);
        [UIView beginAnimations:@"move scroll" context:nil];
        [UIView setAnimationDuration:0.3f];
        [UIView setAnimationCurve:UIViewAnimationCurveLinear];
        scrollView.frame = CGRectMake(scrollView.originX-width, scrollView.originY, width, scrollView.height);
        [UIView commitAnimations];
        scrollView.scrollEnabled = YES;
        [self updateNavigation];
    }
    if (index > [self numberOfPhotos] - 1)
        index = [self numberOfPhotos] - 1;
	//NSUInteger previousCurrentPage = _currentPageIndex;
	_currentPageIndex = index;
    
    NSString *title;
    
        title = (_itemsArray.count ? [NSString stringWithFormat: @"%d из %d", index + 1, _itemsArray.count] : @"");
    
    [self.navigationItem setTitle:title];
    
    if (scrollView.contentOffset.x < (-0.15*scrollView.width)) {
        scrollView.scrollEnabled = NO;
        float width = scrollView.width;
        [scrollView setContentOffset:CGPointMake(scrollView.width*([self numberOfPhotos]-1), scrollView.contentOffset.y) animated:NO];
        scrollView.frame = CGRectMake(scrollView.originX-width, scrollView.originY, width, scrollView.height);
        [UIView beginAnimations:@"move scroll" context:nil];
        [UIView setAnimationDuration:0.3f];
        [UIView setAnimationCurve:UIViewAnimationCurveLinear];
        scrollView.frame = CGRectMake(scrollView.originX+width, scrollView.originY, width, scrollView.height);
        [UIView commitAnimations];
        scrollView.scrollEnabled = YES;
        [self updateNavigation];
    }
}

- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView {
	// Hide controls when dragging begins
	//[self setControlsHidden:YES animated:YES permanent:NO];
}

- (void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView {
	// Update nav when page changes
	[self updateNavigation];
}

#pragma mark - Navigation

- (void)updateNavigation {
//    galleryObject.caption
    if ([self numberOfPhotos] > 0){
        id objectPhoto = [_itemsArray objectAtIndex:_currentPageIndex];
        NewsDB *newsObj = [CoreDataManager object:@"NewsDB" withIdentifier:_trId inMainContext:YES];
// favorite button configuring

        FileImageUrlDB *galleryPr =  (FileImageUrlDB *)[_itemsArray objectAtIndex:_currentPageIndex];
//        if ([[galleryPr is_favorite] isEqualToNumber:[NSNumber numberWithInt:1]])
//        {
//            [favor_button setImage:[UIImage imageNamed:@"icn_fav_"] forState:UIControlStateNormal];
//        }
//        else{
//            [favor_button setImage:[UIImage imageNamed:@"icn_fav"] forState:UIControlStateNormal];
//        }

        
// end confg
        NSString *title = @"";
        NSString *description = @"";
      
        if ([objectPhoto isKindOfClass:[NewsDB class]]) {
            title = galleryPr.name;
            description = newsObj.text;
//            description = (((PreviewGalleryDB*)objectPhoto).strDate.length > 0) ? ((PreviewGalleryDB*)objectPhoto).strDate : galleryObject.strDate;
           
            _textToPost = title;

        }else if(_otherSource){
            
            title = _typeForGallery;
            description = descriptionInf;
            
           

        }else if([_itemsArray.lastObject isKindOfClass:[NSString class]]){
            description = _titleGallery;
        }

        UIView *labelView = [_panelGallery viewWithTag:1];//title
        UILabel *titleLabel = nil;
        if (labelView) {
            if ([labelView isKindOfClass:[UILabel class]]) {
                titleLabel = (UILabel*)labelView;
            }
        }
        if (!titleLabel) {
            titleLabel = [[[UILabel alloc] initWithFrame:CGRectMake(8, 5, _panelGallery.width-16, 20)] autorelease];
            titleLabel.autoresizingMask = UIViewAutoresizingFlexibleTopMargin | UIViewAutoresizingFlexibleWidth|UIViewAutoresizingFlexibleBottomMargin;
            titleLabel.backgroundColor = [UIColor clearColor];
            titleLabel.textColor = [UIColor whiteColor];
            titleLabel.tag = 1;
            titleLabel.numberOfLines = 0;
            titleLabel.font = [UIFont fontWithName:@"Helvetica" size:14];
            [_panelGallery addSubview:titleLabel];
        }
        
        titleLabel.text = title;
        _textToPost = title;
        CGSize sizeDescription = [titleLabel.text sizeWithFont:titleLabel.font constrainedToSize:CGSizeMake(self.view.width-26, self.view.height - 26)];

        float panelHeight = sizeDescription.height;
        panelHeight = panelHeight + [description sizeWithFont:[UIFont systemFontOfSize:12] constrainedToSize:CGSizeMake(self.view.width-16, self.view.height - 26 - panelHeight)].height;
        
        _panelGallery.frame = CGRectMake(0, self.view.height - panelHeight - 10, self.view.width, panelHeight + 10);
        sizeDescription = [titleLabel.text sizeWithFont:titleLabel.font constrainedToSize:CGSizeMake(_panelGallery.width-16, _panelGallery.height - 15)];
        titleLabel.frame = CGRectMake(8, 5, _panelGallery.width-16, sizeDescription.height);
        
        labelView = [_panelGallery viewWithTag:2];//description
        UILabel *descriptionLabel = nil;
        if (labelView) {
            if ([labelView isKindOfClass:[UILabel class]]) {
                descriptionLabel = (UILabel*)labelView;
            }
        }
        if (!descriptionLabel) {
            descriptionLabel = [[[UILabel alloc] initWithFrame:CGRectMake(8, 25.0, _panelGallery.width-16, _panelGallery.height)] autorelease];
            descriptionLabel.autoresizingMask = UIViewAutoresizingFlexibleWidth|UIViewAutoresizingFlexibleHeight|UIViewAutoresizingFlexibleBottomMargin | UIViewAutoresizingFlexibleTopMargin;
            descriptionLabel.numberOfLines = 0;
            descriptionLabel.backgroundColor = [UIColor clearColor];
            descriptionLabel.textColor = [UIColor whiteColor];
            descriptionLabel.tag = 2;
            descriptionLabel.font = [UIFont fontWithName:@"Helvetica" size:12];
            [_panelGallery addSubview:descriptionLabel];
        }
        
        descriptionLabel.text = description;
        
        sizeDescription = [descriptionLabel.text sizeWithFont:descriptionLabel.font constrainedToSize:CGSizeMake(_panelGallery.width, _panelGallery.height - y_offset(titleLabel))];
        descriptionLabel.frame = CGRectMake(8, y_offset(titleLabel), _panelGallery.width-16, sizeDescription.height);
        
        
//        UILabel *dateLabel = nil;
//        if (labelView) {
//            if ([labelView isKindOfClass:[UILabel class]]) {
//                dateLabel = (UILabel*)labelView;
//            }
//        }
//        if (!dateLabel) {
//            dateLabel = [[[UILabel alloc] initWithFrame:CGRectMake(8, _panelGallery.height-16, _panelGallery.width-16, 15)] autorelease];
//            dateLabel.autoresizingMask = UIViewAutoresizingFlexibleTopMargin | UIViewAutoresizingFlexibleWidth;
//            dateLabel.backgroundColor = [UIColor clearColor];
//            dateLabel.textColor = [UIColor whiteColor];
//            dateLabel.tag = kLabelTag;
//            dateLabel.font = [UIFont systemFontOfSize:12];
//            [_panelGallery addSubview:dateLabel];
//            
//            UITapGestureRecognizer *tapGR = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(sourceLinkButtonTapped:)];
//            tapGR.delegate = (id <UIGestureRecognizerDelegate>)self;
//            [self.view addGestureRecognizer:tapGR];
//        }
//        
//        dateLabel.text = date;
    }
    
    for (ZoomingPhotoScrollView *page in _visiblePages) {
        NSUInteger index = PAGE_INDEX(page);
		page.frame = [self frameForPageAtIndex:index];
		[page setMaxMinZoomScalesForCurrentBounds];
       	}
}

#pragma mark SOURCE LINK BUTTON TAPPER DETECTION

- (void)sourceLinkButtonTapped:(UITapGestureRecognizer*)tap
{
    BOOL captureTheTarget = NO;
    if(_panelGallery.alpha)
    {
        CGPoint tapLocation = [tap locationInView:_panelGallery];
        CGRect frame = CGRectMake(8, _panelGallery.height-16, _panelGallery.width-16, 15);
        
        if((tapLocation.x > frame.origin.x && tapLocation.x < frame.size.width + frame.origin.x) &&
           (tapLocation.y > frame.origin.y && tapLocation.y < frame.size.height + frame.origin.y))
        {
            captureTheTarget = YES;
        }
    }
    else
    {
        [self toggleControls];
    }
    
//    if((captureTheTarget && [_itemsArray.lastObject isKindOfClass:[PreviewGalleryDB class]]) || _sourceUrl.length)
//    {
//        UIActionSheet *menu = [[[UIActionSheet alloc] initWithTitle:@"Переход по внешней ссылке" delegate:self cancelButtonTitle:@"Отмена" destructiveButtonTitle:@"OK" otherButtonTitles: nil] autorelease];
//        [menu showInView:self.view];
//    }
}

- (BOOL)gestureRecognizer:(UIGestureRecognizer *)gesture shouldReceiveTouch:(UITouch *)touch
{    
    if ([gesture isKindOfClass:[UITapGestureRecognizer class]])
    {
        CGPoint tapLocation = [touch locationInView:_panelGallery];//[gesture locationInView:_panelGallery];
        CGRect frame = CGRectMake(8, _panelGallery.height-16, _panelGallery.width-16, 15);
        
        if((tapLocation.x > frame.origin.x && tapLocation.x < frame.size.width + frame.origin.x) &&
           (tapLocation.y > frame.origin.y && tapLocation.y < frame.size.height + frame.origin.y))
        {
            return YES;
        }
    }
    return NO;
}



- (void)jumpToPageAtIndex:(NSUInteger)index {
	
	// Change page
//    FileImageUrlDB *galleryPr =  (FileImageUrlDB *)[_itemsArray objectAtIndex:_currentPageIndex];
//    if ([[galleryPr is_favorite] isEqualToNumber:[NSNumber numberWithInt:1]])
//    {
//        [favor_button setImage:[UIImage imageNamed:@"icn_fav_"] forState:UIControlStateNormal];
//    }
//    else{
//        [favor_button setImage:[UIImage imageNamed:@"icn_fav"] forState:UIControlStateNormal];
//    }

	if (index < [self numberOfPhotos]) {
		CGRect pageFrame = [self frameForPageAtIndex:index];
		_pagingScrollView.contentOffset = CGPointMake(pageFrame.origin.x - PADDING, 0);
		[self updateNavigation];
	}
	
}

- (void)gotoPreviousPage 
{
    [self jumpToPageAtIndex:_currentPageIndex-1];
}

- (void)gotoNextPage 
{
    [self jumpToPageAtIndex:_currentPageIndex+1]; 
}

#pragma mark - Control Hiding / Showing

- (void)setControlsHidden:(BOOL)hidden animated:(BOOL)animated permanent:(BOOL)permanent {
    if (backTapped) {
        return;
    }
    // Cancel any timers
    [self cancelControlHiding];
	
	// Status bar and nav bar positioning
    if (self.wantsFullScreenLayout) {
        
        // Get status bar height if visible
        CGFloat statusBarHeight = 0;
        if (![UIApplication sharedApplication].statusBarHidden) {
            CGRect statusBarFrame = [[UIApplication sharedApplication] statusBarFrame];
            statusBarHeight = MIN(statusBarFrame.size.height, statusBarFrame.size.width);
        }
        
        // Status Bar
        [[UIApplication sharedApplication] setStatusBarHidden:hidden withAnimation:animated];
        // Get status bar height if visible
        if (![UIApplication sharedApplication].statusBarHidden) {
            CGRect statusBarFrame = [[UIApplication sharedApplication] statusBarFrame];
            statusBarHeight = MIN(statusBarFrame.size.height, statusBarFrame.size.width);
        }
        
        // Set navigation bar frame
        CGRect navBarFrame = self.navigationController.navigationBar.frame;
        navBarFrame.origin.y = statusBarHeight;
        self.navigationController.navigationBar.frame = navBarFrame;
        
    }
	
	// Animate
    if (animated) {
        [UIView beginAnimations:nil context:nil];
        [UIView setAnimationDuration:0.35];
    }
    CGFloat alpha = hidden ? 0 : 1;
    [self.navigationController.navigationBar setAlpha:alpha];

    [_panelGallery setAlpha: alpha];
    
	if (animated) 
        [UIView commitAnimations];
	
    //Navbar
//    [navigationBar setHidden:hidden];
	
}

- (void)cancelControlHiding {
	// If a timer exists then cancel and release
	if (_controlVisibilityTimer) {
		[_controlVisibilityTimer invalidate];
		[_controlVisibilityTimer release];
		_controlVisibilityTimer = nil;
	}
}

- (void)hideControls 
{ 
    
    [self setControlsHidden:YES animated:YES permanent:NO]; 
}

- (void)toggleControls{
    [self setControlsHidden:(self.navigationController.navigationBar.alpha > 0) animated:YES permanent:NO];
}
#pragma mark - Properties

- (void)setInitialPageIndex:(NSUInteger)index {
    // Validate
    if (index >= [self numberOfPhotos])
        index = [self numberOfPhotos]-1;
    _currentPageIndex = index;
	if ([self isViewLoaded]) {
        [self jumpToPageAtIndex:index];
        if (!_viewIsActive)
            [self tilePages]; // Force tiling if view is not visible
    }
    [self updateNavigation];
}

#pragma mark - Misc

- (void)doneButtonPressed:(id)sender {
    //[self dismissModalViewControllerAnimated:YES];
    /* tsv */[self dismissViewControllerAnimated:YES completion:nil];
}

- (int)getCurrentPageNumber{
    return _currentPageIndex;
}

- (id)getCurrentObject
{
    if (_itemsArray) {
        if (_itemsArray.count > _currentPageIndex) {
            return [_itemsArray objectAtIndex:_currentPageIndex];
        }
    }
    return nil;
}

#pragma mark navigation delegate
- (void)BackButtonTapped:(UIButton *)backbutton {
    
    backTapped = YES;
    BOOL isAnim = TRUE;
    if (self.interfaceOrientation!=UIInterfaceOrientationPortrait)
        isAnim = FALSE;

    [self.navigationController popViewControllerAnimated:isAnim];
}

//change content
-(void)setObjects:(id)newObject{
    
    _currentPageIndex = 0;
    
    
    
    _otherSource = NO;
//    if ([newObject isKindOfClass: [GalleryDB class]]) {
//        NSArray *sortedObjects = [((GalleryDB*)newObject).galleryPreview.allObjects sortedArrayUsingComparator:^NSComparisonResult(id obj1, id obj2) {
//            
//            if ( [[obj1 identifier] intValue] > [[obj2 identifier] intValue] )
//                return NSOrderedAscending;
//            if ([[obj1 identifier] intValue] < [[obj2 identifier] intValue])
//                return NSOrderedDescending;
//            return NSOrderedSame;
//        }];
//        
//        _itemsArray = [[NSArray alloc] initWithArray: sortedObjects];
//    }
//    else
    if (fetchedResultsController.fetchedObjects.count)
    {
        SAFE_KILL(_itemsArray);
        _itemsArray = [[NSArray alloc] initWithArray: fetchedResultsController.fetchedObjects];
    }
    

    [_visiblePages removeAllObjects];
    [_recycledPages removeAllObjects];
    for(UIView *oneView in [_pagingScrollView subviews])
        [oneView removeFromSuperview];
    
    [self.view setBackgroundColor:[UIColor blackColor]];
    [self reloadData];
    [self updateNavigation];
    [self setControlsHidden:NO animated:NO permanent:NO];

    if (_itemsArray.count == 0) {
        NSArray *subviewsLabel = [_panelGallery subviews];
        for (UIView *oneView in subviewsLabel) {
            [oneView removeFromSuperview];
        }
    }

//    }
}

#pragma fecthresult delegat

- (NSFetchedResultsController *)fetchedResultsController {
    
    if (fetchedResultsController != nil) {
        return fetchedResultsController;
    }
    
    /*
	 Set up the fetched results controller.
     */
	// Create the fetch request for the entity.
	NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
	// Edit the entity name as appropriate.
	NSEntityDescription *entity = [NSEntityDescription entityForName:@"PreviewGalleryDB" inManagedObjectContext:CoreDataManager.shared.managedObjectContext];
	[fetchRequest setEntity:entity];
	
	// Set the batch size to a suitable number.
	[fetchRequest setFetchBatchSize:20];
    [fetchRequest setFetchLimit:0];
    
	// Sort using the timeStamp property..
    NSSortDescriptor *sortDescriptor = [[NSSortDescriptor alloc] initWithKey:@"date" ascending:NO];
    
    NSArray *sortDescriptors = [[NSArray alloc] initWithObjects:sortDescriptor, nil];
    
	[fetchRequest setSortDescriptors:sortDescriptors];
    
//    NSArray *ar = [[[CoreDataManager shared].managedObjectContext executeFetchRequest:fetchRequest error:nil] valueForKey:@"gallery"];
//    NSLog(@"%@", ar);
    [fetchRequest setPredicate : [NSPredicate predicateWithFormat:@"ANY gallery.identifier == %@",_trId]];
    
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
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller
{
    if (shouldUpdate)
    {
        [self reloadData];
        [self setObjects:nil];
    }
    else
    {
        shouldUpdate=YES;
    }
    
}
-(void)favoriteButtonTaped: (UIButton *)tapButton
{
//    NSLog(@"Favorite Button taped");
//    NSLog(@"--------------------------%d",_currentPageIndex);
//    if ([[_itemsArray objectAtIndex:_currentPageIndex] isKindOfClass:[PreviewGalleryDB class]])
    {
//    UIAlertView *favorAV;
//    BOOL is_favor=FALSE;
//    NSDate *dateFavor=nil;
//    FileImageUrlDB *galleryPr =  (FileImageUrlDB *)[_itemsArray objectAtIndex:_currentPageIndex];
//    if ([[galleryPr is_favorite] isEqualToNumber:[NSNumber numberWithInt:0]])
//    {
//        favorAV = [[UIAlertView alloc] initWithTitle:@"Избранное" message:@"Фотография добавлена" delegate:self cancelButtonTitle:@"Продолжить" otherButtonTitles:nil, nil];
//        is_favor = TRUE;
//        [favor_button setImage:[UIImage imageNamed:@"icn_fav_"] forState:UIControlStateNormal];
//
//        dateFavor = [NSDate date];
//    }
//    else{
//        favorAV = [[UIAlertView alloc] initWithTitle:@"Избранное" message:@"Фотография удалена" delegate:self cancelButtonTitle:@"Продолжить" otherButtonTitles:nil, nil];
//         [favor_button setImage:[UIImage imageNamed:@"icn_fav"] forState:UIControlStateNormal];
//        is_favor = FALSE;
//    }
//    [favorAV show];
//    [favorAV release];
//    
//    shouldUpdate = NO;
//
//    checkAndSet(galleryPr, @"is_favorite", [NSNumber numberWithBool:is_favor]);
//    checkAndSet(galleryPr, @"date_favor", [NSNumber numberWithDouble:[dateFavor timeIntervalSince1970]]);
//    [CoreDataManager saveParsingContext];
//    [CoreDataManager saveMainContext];

        }
//    _currentPageIndex = index;
//    [self jumpToPageAtIndex:_currentPageIndex];
}
//-(void)shareButtonTaped: (UIButton *)tapButton
//{
//    NSLog(@"Share Button taped");
//    PreviewGalleryDB *galleryPr =  (PreviewGalleryDB *)[_itemsArray objectAtIndex:_currentPageIndex];
//    _imageAdress = [[NSString stringWithFormat:SIEURLNewsImage,galleryPr.image] retain];
//    if ([[[UIDevice currentDevice] systemVersion] floatValue] >= 6) {
//        SIEActivityVK *vkActivity = [[[SIEActivityVK alloc] init] autorelease];
//        vkActivity.rootNC = self.navigationController;
//        RDActivityViewController *viewController1 =  [[[RDActivityViewController alloc] initWithDelegate:self maximumNumberOfItems:10 applicationActivities:@[vkActivity] placeholderItem:@"123"] autorelease];
//        viewController1.excludedActivityTypes = @[UIActivityTypePostToWeibo ,UIActivityTypeMail, UIActivityTypePrint, UIActivityTypeSaveToCameraRoll, UIActivityTypeMessage, UIActivityTypeCopyToPasteboard];
//        [self presentViewController:viewController1 animated:YES completion:^{}];
//    }
//    else {
//        UIActionSheet *styleAlert = [[UIActionSheet alloc] initWithTitle:@"Поделиться ссылкой"
//                                                                delegate:self cancelButtonTitle:@"Отмена"
//                                                  destructiveButtonTitle:nil
//                                                       otherButtonTitles:	@"ВКонтакте",
//                                     @"Facebook",
//                                     @"Twitter",
//                                     nil,
//                                     nil];
//        [styleAlert showInView:self.view];
//        [styleAlert release];
//        NSLog(@"Версия не соответствует");
//    }
//
//}
//#pragma - mark Share component delegate
//- (NSArray *)activityViewController:(NSArray *)activityViewController itemsForActivityType:(NSString *)activityType {
//    NSArray *activityItems;
//    
//    NSString *myText = _textToPost;
//    NSLog(@"my text -> %@",myText);
//    NSString *path = _imageAdress;
//    UIImage *image;
//    if ([FileSystem pathExisted:path])
//    {
//        image = [FileSystem imageWithPath:path];
//    }
//    else
//    {
//        image = [UIImage imageNamed:path];
//    }
//    NSLog(@"image length %d",path.length);
//    activityItems = @[myText,image];
//    
//    if ([activityType isEqualToString:UIActivityTypePostToTwitter]) {
//        NSRange newLineRange = [myText rangeOfString: @"\n\n"];
//        if (!newLineRange.length)
//        {
//            newLineRange = [myText rangeOfString: @"."];
//            
//        }
//        if (!newLineRange.length || newLineRange.location>35)
//            newLineRange = NSMakeRange(35, 0);
//        NSRange stringBeforeNewLineRange = NSMakeRange(0, newLineRange.location);
//
//        
//        NSString *resultString = [myText substringWithRange:stringBeforeNewLineRange];
//        resultString = [resultString stringByAppendingString:@"..."];
//        resultString = [resultString stringByAppendingString:[NSString stringWithFormat:@"\n%@",kUrlApplication]];
//        
//        
//        activityItems = @[resultString, image];
//        
//        return activityItems;
//    } else  {
//        myText = [myText stringByAppendingString:[NSString stringWithFormat:@"\nСкачай приложение «Сименс» и узнай больше! %@",kUrlApplication]];
//        activityItems = @[myText, image];
//        return activityItems;
//    }
//}
//
//- (void)postToFacebooksWall
//{
//    NSString *myText = _textToPost;
//    myText = [myText stringByAppendingString:[NSString stringWithFormat:@"\nСкачай приложение «Сименс» и узнай больше! %@",kUrlApplication]];
//    NSString *path = _imageAdress;
//    
//    
//    NSLog(@"path   %@", path);
//    NSData *imageForShareNSData = [NSData dataWithContentsOfFile:path];
//    
//    NSMutableDictionary *params = [NSMutableDictionary dictionaryWithObjectsAndKeys:
//                                   kAppId, @"app_id",
//                                   imageForShareNSData, @"source",
//                                   myText, @"message",
//                                   nil];
//    [_facebook requestWithGraphPath:@"/me/photos" andParams:params andHttpMethod:@"POST" andDelegate:self];
//    UIAlertView *alert = [[UIAlertView alloc] initWithTitle:nil message:@"Запись успешно опубликована" delegate: nil cancelButtonTitle:@"OK" otherButtonTitles:nil];
//    [alert show];
//    [alert release];
//}
//
//- (void)actionSheet:(UIActionSheet *)modalView clickedButtonAtIndex:(NSInteger)buttonIndex
//{
//    
//    // Change the navigation bar style, also make the status bar match with it
//	switch (buttonIndex)
//	{
//		case 0:
//		{
//            if (![SIELoader reachable])
//            {
//                UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Внимание" message:@"Проверьте соединения с интернетом." delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil];
//                [alertView show];
//                [alertView release];
//            } else {
//                NSString *myText = _textToPost;
//                myText = [myText stringByAppendingString:[NSString stringWithFormat:@"\nСкачай приложение «Сименс» и узнай больше! %@",kUrlApplication]];
//                NSLog(@"my text -> %@",myText);
//                NSString *path = _imageAdress;
//                
//                
//                UIImage *image;
//                if ([FileSystem pathExisted:path])
//                {
//                    image = [FileSystem imageWithPath:path];
//                }
//                else
//                {
//                    image = [UIImage imageNamed:path];
//                }
//                SIEVKViewController *vk = [[[SIEVKViewController alloc] initWithData:myText andImage:image] autorelease];
//                [self presentModalViewController:vk animated:YES];
//            }
//			break;
//		}
//		case 1:
//		{
//            if (![SIELoader reachable])
//            {
//                UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Внимание" message:@"Проверьте соединения с интернетом." delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil];
//                [alertView show];
//                [alertView release];
//            } else {
//                
//                if([_facebook isSessionValid]){
//                    [self postToFacebooksWall];
//                }
//                else
//                    [_facebook authorize:_permissions localAppId:kAppId];
//            }
//			break;
//		}
//		case 2:
//		{
//
//			if ([[[UIDevice currentDevice] systemVersion] floatValue] >= 5)
//            {
//                NSString *myText = _textToPost;
//                NSString *path = _imageAdress;
//                UIImage *image;
//                if ([FileSystem pathExisted:path])
//                {
//                    image = [FileSystem imageWithPath:path];
//                }
//                else
//                {
//                    image = [UIImage imageNamed:path];
//                }
//
//                NSRange newLineRange = [myText rangeOfString: @"."];
//                if (newLineRange.location<130)
//                    newLineRange = NSMakeRange(124-[path length], 0);
//                else
//                    newLineRange = NSMakeRange(100, 0);
//                
//                NSRange stringBeforeNewLineRange = NSMakeRange(0, newLineRange.location);
//                
//                NSString *resultString = [myText substringWithRange:stringBeforeNewLineRange];
//                resultString = [resultString stringByAppendingString:@"..."];
//                resultString = [resultString stringByAppendingString:[NSString stringWithFormat:@"\n%@",kUrlApplication]];
//                
//                
//                
//                                
//                
//                TWTweetComposeViewController *tweetComposer = [[[TWTweetComposeViewController alloc] init] autorelease];
//                [tweetComposer setInitialText:resultString];
//                [tweetComposer addImage:image];
//                
//                [self presentViewController:tweetComposer animated:YES completion:nil];
//            } else {
//                UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Внимание" message:@"Пожалуйста обновите версию iOS" delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil];
//                [alertView show];
//                [alertView release];
//            }
//			break;
//		}
//	}
//}
//
//
//- (void)request:(FBRequest *)request didReceiveResponse:(NSURLResponse *)response
//{
//    
//}
//
//- (void)request:(FBRequest *)request didReceiveData:(NSData *)data
//{
//    [_responseText appendData:data];
//    NSLog(@"_responseText - - - %@",_responseText);
//}
//
//- (void)request:(FBRequest *)request didLoad:(id)result
//{
//    NSLog(@"result 333 - - - %@",[[[result objectForKey:@"data"] objectAtIndex:0] objectForKey:@"name"]);
//    NSLog(@"result 444 - - - %@",[[[result objectForKey:@"data"] objectAtIndex:0] objectForKey:@"picture"]);
//    if(_data)
//        [_data release], _data = nil;
//    
//    _data = [result retain];
//    
//    NSLog(@"_data - - - %@",_data);
//};
//
//- (void)request:(FBRequest *)request didFailWithError:(NSError *)error
//{
//    NSLog(@"error: \n = = = = = \n %@ \n = = = = = \n",error);
//};
//
//- (void)fbDidLogin
//{
//    [defaults setObject:[_facebook accessToken] forKey:@"FBAccessTokenKey"];
//    [defaults setObject:[_facebook expirationDate] forKey:@"FBExpirationDateKey"];
//    [defaults synchronize];
//    [self postToFacebooksWall];
//}
//
//-(void)fbDidNotLogin:(BOOL)cancelled
//{
//    NSLog(@"did not login");
//}

@end
