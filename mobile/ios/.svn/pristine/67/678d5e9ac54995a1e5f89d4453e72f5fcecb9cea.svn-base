//
//	ReaderViewController.m
//	Reader v2.6.0
//
//	Created by Julius Oklamcak on 2011-07-01.
//	Copyright © 2011-2012 Julius Oklamcak. All rights reserved.
//  Modified by Moskovchenko M. iD-East 2013
//
//	Permission is hereby granted, free of charge, to any person obtaining a copy
//	of this software and associated documentation files (the "Software"), to deal
//	in the Software without restriction, including without limitation the rights to
//	use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
//	of the Software, and to permit persons to whom the Software is furnished to
//	do so, subject to the following conditions:
//
//	The above copyright notice and this permission notice shall be included in all
//	copies or substantial portions of the Software.
//
//	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
//	OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
//	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
//	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
//	WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
//	CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
//


#import "ReaderViewController.h"
#import "ReaderMainPagebar.h"
#import "ReaderContentView.h"
#import "ReaderThumbCache.h"
#import "ReaderThumbQueue.h"
#import "ThumbsViewController.h"
#import <MessageUI/MessageUI.h>

@interface ReaderViewController () <UIScrollViewDelegate, UIGestureRecognizerDelegate, ReaderMainPagebarDelegate, ReaderContentViewDelegate, ThumbsViewControllerDelegate>
@end

@implementation ReaderViewController
{
	ReaderDocument *document;

	UIScrollView *theScrollView;

	ReaderMainPagebar *mainPagebar;

	NSMutableDictionary *contentViews;

	NSInteger currentPage;

	CGSize lastAppearSize;

	NSDate *lastHideTime;

	BOOL isVisible;
    
    UIAlertView *alert;
    
    AFHTTPRequestOperation *downloadPDFRequest;
    UIView *bgIndicator;
}

#pragma mark Constants

#define PAGING_VIEWS 3

#define PAGEBAR_HEIGHT 48.0f

#define TAP_AREA_SIZE 48.0f

#pragma mark Properties

@synthesize delegate;

#pragma mark Support methods

- (void)BackButtonTapped:(UIButton *)backbutton {
    BOOL isAnimated = YES;
    if (!UIInterfaceOrientationIsPortrait([UIApplication sharedApplication].statusBarOrientation))
        isAnimated = FALSE;
    
    
    [self.navigationController popViewControllerAnimated:isAnimated];
}


- (void)updateScrollViewContentSize
{
	NSInteger count = [document.pageCount integerValue];

	if (count > PAGING_VIEWS) count = PAGING_VIEWS; // Limit

	CGFloat contentHeight = theScrollView.bounds.size.height;

	CGFloat contentWidth = (theScrollView.bounds.size.width * count);

	theScrollView.contentSize = CGSizeMake(contentWidth, contentHeight);
}

- (void)updateScrollViewContentViews
{
	[self updateScrollViewContentSize]; // Update the content size

	NSMutableIndexSet *pageSet = [NSMutableIndexSet indexSet]; // Page set

	[contentViews enumerateKeysAndObjectsUsingBlock: // Enumerate content views
		^(id key, id object, BOOL *stop)
		{
			ReaderContentView *contentView = object; [pageSet addIndex:contentView.tag];
		}
	];

	__block CGRect viewRect = CGRectZero;
    viewRect.size = theScrollView.bounds.size;

	__block CGPoint contentOffset = CGPointZero;
    NSInteger page = [document.pageNumber integerValue];

	[pageSet enumerateIndexesUsingBlock: // Enumerate page number set
		^(NSUInteger number, BOOL *stop)
		{
			NSNumber *key = [NSNumber numberWithInteger:number]; // # key

			ReaderContentView *contentView = [contentViews objectForKey:key];

			contentView.frame = viewRect;
            if (page == number) contentOffset = viewRect.origin;

			viewRect.origin.x += viewRect.size.width; // Next view frame position
		}
	];

	if (CGPointEqualToPoint(theScrollView.contentOffset, contentOffset) == false)
	{
		theScrollView.contentOffset = contentOffset; // Update content offset
	}
}

-(void) setInitialContent
{
    
	assert(document != nil); // Must have a valid ReaderDocument
      
	
    
	CGRect viewRect = self.view.bounds; // View controller's view bounds
    
	theScrollView = [[UIScrollView alloc] initWithFrame:viewRect]; // All
    
	theScrollView.scrollsToTop = NO;
	theScrollView.pagingEnabled = YES;
	theScrollView.delaysContentTouches = NO;
	theScrollView.showsVerticalScrollIndicator = NO;
	theScrollView.showsHorizontalScrollIndicator = NO;
	theScrollView.contentMode = UIViewContentModeRedraw;
	theScrollView.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight;
	theScrollView.backgroundColor = [UIColor clearColor];
	theScrollView.userInteractionEnabled = YES;
	theScrollView.autoresizesSubviews = NO;
	theScrollView.delegate = self;
    
	[self.view addSubview:theScrollView];
    [theScrollView release];
    
	CGRect pagebarRect = viewRect;
	pagebarRect.size.height = PAGEBAR_HEIGHT;
	pagebarRect.origin.y = (viewRect.size.height - PAGEBAR_HEIGHT);
    
	mainPagebar = [[ReaderMainPagebar alloc] initWithFrame:pagebarRect document:document]; // At bottom
	mainPagebar.delegate = self;
	[self.view addSubview:mainPagebar];
    [mainPagebar release];
    
    
	UITapGestureRecognizer *singleTapOne = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(handleSingleTap:)];
	singleTapOne.numberOfTouchesRequired = 1; singleTapOne.numberOfTapsRequired = 1; singleTapOne.delegate = self;
	[self.view addGestureRecognizer:singleTapOne];
   
    
	UITapGestureRecognizer *doubleTapOne = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(handleDoubleTap:)];
	doubleTapOne.numberOfTouchesRequired = 1; doubleTapOne.numberOfTapsRequired = 2; doubleTapOne.delegate = self;
	[self.view addGestureRecognizer:doubleTapOne];
   
    
	UITapGestureRecognizer *doubleTapTwo = [[UITapGestureRecognizer alloc] initWithTarget:self action:@selector(handleDoubleTap:)];
	doubleTapTwo.numberOfTouchesRequired = 2; doubleTapTwo.numberOfTapsRequired = 2; doubleTapTwo.delegate = self;
	[self.view addGestureRecognizer:doubleTapTwo];
    
    
	[singleTapOne requireGestureRecognizerToFail:doubleTapOne]; // Single tap requires double tap to fail
    
	contentViews = [NSMutableDictionary new];
//    lastHideTime = [NSDate date];
    lastHideTime = [[NSDate alloc] initWithTimeIntervalSinceNow:0];
    [singleTapOne release];
    [doubleTapOne release];
    [doubleTapTwo release];
   
    
}

- (void)showDocumentPage:(NSInteger)page
{
	if (page != currentPage) // Only if different
	{
		NSInteger minValue; NSInteger maxValue;
		NSInteger maxPage = [document.pageCount integerValue];
		NSInteger minPage = 1;

		if ((page < minPage) || (page > maxPage)) return;

		if (maxPage <= PAGING_VIEWS) // Few pages
		{
			minValue = minPage;
			maxValue = maxPage;
		}
		else // Handle more pages
		{
			minValue = (page - 1);
			maxValue = (page + 1);

			if (minValue < minPage)
				{minValue++; maxValue++;}
			else
				if (maxValue > maxPage)
					{minValue--; maxValue--;}
		}

		NSMutableIndexSet *newPageSet = [NSMutableIndexSet new];

		NSMutableDictionary *unusedViews = [contentViews mutableCopy];

		CGRect viewRect = CGRectZero; viewRect.size = theScrollView.bounds.size;

		for (NSInteger number = minValue; number <= maxValue; number++)
		{
			NSNumber *key = [NSNumber numberWithInteger:number]; // # key

			ReaderContentView *contentView = [contentViews objectForKey:key];

			if (contentView == nil) // Create a brand new document content view
			{
				NSURL *fileURL = document.fileURL;
                NSString *phrase = document.password; // Document properties

				contentView = [[ReaderContentView alloc] initWithFrame:viewRect fileURL:fileURL page:number password:phrase];

				[theScrollView addSubview:contentView]; [contentViews setObject:contentView forKey:key];

				contentView.message = self;
                [newPageSet addIndex:number];
                [contentView release];
			}
			else // Reposition the existing content view
			{
				contentView.frame = viewRect; [contentView zoomReset];

				[unusedViews removeObjectForKey:key];
			}

			viewRect.origin.x += viewRect.size.width;
		}

		[unusedViews enumerateKeysAndObjectsUsingBlock: // Remove unused views
			^(id key, id object, BOOL *stop)
			{
				[contentViews removeObjectForKey:key];

				ReaderContentView *contentView = object;

				[contentView removeFromSuperview];
			}
		];

        [unusedViews removeAllObjects];
		SAFE_KILL(unusedViews);
         

		CGFloat viewWidthX1 = viewRect.size.width;
		CGFloat viewWidthX2 = (viewWidthX1 * 2.0f);

		CGPoint contentOffset = CGPointZero;

		if (maxPage >= PAGING_VIEWS)
		{
			if (page == maxPage)
				contentOffset.x = viewWidthX2;
			else
				if (page != minPage)
					contentOffset.x = viewWidthX1;
		}
		else
			if (page == (PAGING_VIEWS - 1))
				contentOffset.x = viewWidthX1;

		if (CGPointEqualToPoint(theScrollView.contentOffset, contentOffset) == false)
		{
			theScrollView.contentOffset = contentOffset; // Update content offset
		}

		if ([document.pageNumber integerValue] != page) // Only if different
		{
			document.pageNumber = [NSNumber numberWithInteger:page]; // Update page number
		}

		NSURL *fileURL = document.fileURL;
        
        NSString *phrase = document.password;
        
        NSString *guid = document.guid;

		if ([newPageSet containsIndex:page] == YES) // Preview visible page first
		{
			NSNumber *key = [NSNumber numberWithInteger:page]; // # key

			ReaderContentView *targetView = [contentViews objectForKey:key];

			[targetView showPageThumb:fileURL page:page password:phrase guid:guid];

			[newPageSet removeIndex:page]; // Remove visible page from set
		}

		[newPageSet enumerateIndexesWithOptions:NSEnumerationReverse usingBlock: // Show previews
			^(NSUInteger number, BOOL *stop)
			{
				NSNumber *key = [NSNumber numberWithInteger:number]; // # key

				ReaderContentView *targetView = [contentViews objectForKey:key];

				[targetView showPageThumb:fileURL page:number password:phrase guid:guid];
			}
		];

		SAFE_KILL(newPageSet); // Release new page set

		[mainPagebar updatePagebar]; // Update the pagebar display

		currentPage = page; // Track current page number
	}
}

- (void)showDocument:(id)object
{
	[self updateScrollViewContentSize]; // Set content size

	[self showDocumentPage:[document.pageNumber integerValue]];

	document.lastOpen = [NSDate date]; // Update last opened date

	isVisible = YES; // iOS present modal bodge
}
//-(void) setPdfUrl:(NSString *)newURL
//{
//    if (_pdfUrl!=nil)
//    {
//        SAFE_KILL(_pdfUrl);
//    }
//    _pdfUrl = [newURL copy];
//    
//}
#pragma mark UIViewController methods

- (id)initWithReaderDocumentURL:(NSString *)pdfUrlToLoad AndName:(NSString *)namePDF
{
    self = [super initWithNibName:nil bundle:nil];
    
            
    document = nil;
//    _pdfId = [pdfIdToLoad retain];
    self.pdfUrl = pdfUrlToLoad;
    if ([FileSystem pathExisted:_pdfUrl])
    {
        NSString *pathToPdf = [FileSystem filePathForUrlPath:_pdfUrl];
        document = [[ReaderDocument withDocumentFilePath:pathToPdf password:nil] retain];
    }
    else if ([UFSLoader reachable])
    {
        alert = [[UIAlertView alloc] initWithTitle:@"Идет загрузка данных,\nпожалуйста, подождите" message:@"0% \n " delegate:self cancelButtonTitle:@"Отмена" otherButtonTitles: nil];
        [alert setFrame:CGRectMake(50, 100, alert.getMaxX, (alert.getMaxY+150))];
        [alert show];

        downloadPDFRequest = [UFSLoader requestGetFile:_pdfUrl AndName:_pdfUrl];
        [downloadPDFRequest setDownloadProgressBlock:^(NSUInteger bytesRead, long long totalBytesRead, long long totalBytesExpectedToRead) {
            float progress = (float)((totalBytesRead*1.0f)/totalBytesExpectedToRead);
//            NSLog(@"progress %f",progress);
            [alert setMessage:[NSString stringWithFormat:@"%d%% \n ",(int)(progress*100)]];
            [self setProgressToPV:progress];
        }];
        [[NSNotificationCenter defaultCenter] removeObserver:self];
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(loadPdftoFS:) name:kNotificationFileLoaded object:nil];
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(loadPdftoFS:) name:kNotificationFileLoadFailed object:nil];
        
    }
    else
    {
        UIAlertView *alertNO = [[UIAlertView alloc] initWithTitle:@"Ошибка" message:@"Нет интернет соединения,\nпопробуйте позже" delegate:self cancelButtonTitle:@"Ок" otherButtonTitles: nil];
        [alertNO show];
        [alertNO release];
    }
  
return self;
	
}

- (void)viewDidLoad
{
	[super viewDidLoad];
    self.view.backgroundColor = [UIColor scrollViewTexturedBackgroundColor];
    self.navigationController.navigationBar.tintColor = nil;
    self.navigationController.navigationBar.barStyle = UIBarStyleBlackTranslucent;
   
    

    
    UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
    UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
    [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
    [backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
    [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
    self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
    [backbutton release];
    [self removeAnimationsFromScrollView];
    self.titleText = _pdfName;

    if (document!=nil)
    {
        [self setInitialContent];
    }
        
}

- (void)viewWillAppear:(BOOL)animated
{
	[super viewWillAppear:animated];
   for (UIPanGestureRecognizer *gesture in [self.navigationController.navigationBar subviews])
   {
       if ([gesture isKindOfClass:[UIPanGestureRecognizer class]])
           [self.navigationController.navigationBar removeGestureRecognizer:gesture];
   }
    if (document!=nil)
    {
	if (CGSizeEqualToSize(lastAppearSize, CGSizeZero) == false)
	{
		if (CGSizeEqualToSize(lastAppearSize, self.view.bounds.size) == false)
		{
			[self updateScrollViewContentViews]; // Update content views
		}

		lastAppearSize = CGSizeZero; // Reset view size tracking
	}
    }
}

- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
    if (document != nil)
    {
        if (CGSizeEqualToSize(theScrollView.contentSize, CGSizeZero)) // First time
        {
            [self performSelector:@selector(showDocument:) withObject:nil afterDelay:0.02];
        }

        #if (READER_DISABLE_IDLE == TRUE) // Option

        [UIApplication sharedApplication].idleTimerDisabled = YES;

        #endif // end of READER_DISABLE_IDLE Option
    
//    [self.navigationController.navigationBar setTranslucent:YES];
//    [self.navigationController.navigationBar setBackgroundColor:[UIColor blackColor]];
//    [self.navigationController.navigationBar setAlpha:0.83f];
       
    }

}

- (void)viewWillDisappear:(BOOL)animated
{
	[super viewWillDisappear:animated];

	lastAppearSize = self.view.bounds.size; // Track view size

#if (READER_DISABLE_IDLE == TRUE) // Option

	[UIApplication sharedApplication].idleTimerDisabled = NO;

#endif // end of READER_DISABLE_IDLE Option
    [self.navigationController.navigationBar setTintColor:[UIColor colorWithRed:(110.0f)/(255.0f) green:(131.0f)/(255.0f) blue:(142.0f)/(255.0f) alpha:1]];
    [self.navigationController.navigationBar setAlpha:1.0f];
    [self.navigationController.navigationBar setTranslucent:NO];
    

}

- (void)viewDidDisappear:(BOOL)animated
{
	[super viewDidDisappear:animated];
}

- (void)viewDidUnload
{
    [super viewDidUnload];
#ifdef DEBUG
	NSLog(@"%s", __FUNCTION__);
#endif

	

	lastAppearSize = CGSizeZero; currentPage = 0;

	
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation
{
	return YES;
}

- (void)willRotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation duration:(NSTimeInterval)duration
{
    [self removeAnimationsFromScrollView];
   
	if (isVisible == NO) return; // iOS present modal bodge
}
-(void)didRotateFromInterfaceOrientation:(UIInterfaceOrientation)fromInterfaceOrientation
{
     self.titleText = _pdfName;
}
- (void)willAnimateRotationToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation duration:(NSTimeInterval)duration
{
    if (isVisible == NO) return; // iOS present modal bodge

	[self updateScrollViewContentViews]; // Update content views

	lastAppearSize = CGSizeZero; // Reset view size tracking
}

/*
- (void)didRotateFromInterfaceOrientation:(UIInterfaceOrientation)fromInterfaceOrientation
{
	//if (isVisible == NO) return; // iOS present modal bodge

	//if (fromInterfaceOrientation == self.interfaceOrientation) return;
}
*/

- (void)didReceiveMemoryWarning
{
#ifdef DEBUG
	NSLog(@"%s", __FUNCTION__);
#endif

	[super didReceiveMemoryWarning];
}

- (void)dealloc
{   [_pdfName release];
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [FileSystem removeAllTemporary];
//    SAFE_KILL(alert);
//    SAFE_KILL(downloadPDFRequest);
    SAFE_KILL(lastHideTime);
    SAFE_KILL(contentViews);
    SAFE_KILL(delegate);
    SAFE_KILL(document);
    
    [super dealloc];
}

#pragma mark UIScrollViewDelegate methods

- (void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView
{
	__block NSInteger page = 0;

	CGFloat contentOffsetX = scrollView.contentOffset.x;

	[contentViews enumerateKeysAndObjectsUsingBlock: // Enumerate content views
		^(id key, id object, BOOL *stop)
		{
			ReaderContentView *contentView = object;

			if (contentView.frame.origin.x == contentOffsetX)
			{
				page = contentView.tag; *stop = YES;
			}
		}
	];

	if (page != 0) [self showDocumentPage:page]; // Show the page
}

- (void)scrollViewDidEndScrollingAnimation:(UIScrollView *)scrollView
{
	[self showDocumentPage:theScrollView.tag]; // Show page

	theScrollView.tag = 0; // Clear page number tag
}

#pragma mark UIGestureRecognizerDelegate methods

- (BOOL)gestureRecognizer:(UIGestureRecognizer *)recognizer shouldReceiveTouch:(UITouch *)touch
{
	if ([touch.view isKindOfClass:[UIScrollView class]])
        
        return YES;

	return NO;
}

#pragma mark UIGestureRecognizer action methods

- (void)decrementPageNumber
{
	if (theScrollView.tag == 0) // Scroll view did end
	{
		NSInteger page = [document.pageNumber integerValue];
		NSInteger maxPage = [document.pageCount integerValue];
		NSInteger minPage = 1; // Minimum

		if ((maxPage > minPage) && (page != minPage))
		{
			CGPoint contentOffset = theScrollView.contentOffset;

			contentOffset.x -= theScrollView.bounds.size.width; // -= 1

			[theScrollView setContentOffset:contentOffset animated:YES];

			theScrollView.tag = (page - 1); // Decrement page number
		}
	}
}

- (void)incrementPageNumber
{
	if (theScrollView.tag == 0) // Scroll view did end
	{
		NSInteger page = [document.pageNumber integerValue];
		NSInteger maxPage = [document.pageCount integerValue];
		NSInteger minPage = 1; // Minimum

		if ((maxPage > minPage) && (page != maxPage))
		{
			CGPoint contentOffset = theScrollView.contentOffset;

			contentOffset.x += theScrollView.bounds.size.width; // += 1

			[theScrollView setContentOffset:contentOffset animated:YES];

			theScrollView.tag = (page + 1); // Increment page number
		}
	}
}

- (void)handleSingleTap:(UITapGestureRecognizer *)recognizer
{
	if (recognizer.state == UIGestureRecognizerStateRecognized)
	{
		CGRect viewRect = recognizer.view.bounds; // View bounds

		CGPoint point = [recognizer locationInView:recognizer.view];

		CGRect areaRect = CGRectInset(viewRect, TAP_AREA_SIZE, 0.0f); // Area

		if (CGRectContainsPoint(areaRect, point)) // Single tap is inside the area
		{
			NSInteger page = [document.pageNumber integerValue]; // Current page #

			NSNumber *key = [NSNumber numberWithInteger:page]; // Page number key

			ReaderContentView *targetView = [contentViews objectForKey:key];

			id target = [targetView processSingleTap:recognizer]; // Target

			if (target != nil) // Handle the returned target object
			{
				if ([target isKindOfClass:[NSURL class]]) // Open a URL
				{
					NSURL *url = (NSURL *)target; // Cast to a NSURL object

					if (url.scheme == nil) // Handle a missing URL scheme
					{
						NSString *www = url.absoluteString; // Get URL string

						if ([www hasPrefix:@"www"] == YES) // Check for 'www' prefix
						{
							NSString *http = [NSString stringWithFormat:@"http://%@", www];

							url = [NSURL URLWithString:http]; // Proper http-based URL
						}
					}

					if ([[UIApplication sharedApplication] openURL:url] == NO)
					{
						#ifdef DEBUG
							NSLog(@"%s '%@'", __FUNCTION__, url); // Bad or unknown URL
						#endif
					}
				}
				else // Not a URL, so check for other possible object type
				{
					if ([target isKindOfClass:[NSNumber class]]) // Goto page
					{
						NSInteger value = [target integerValue]; // Number

						[self showDocumentPage:value]; // Show the page
					}
				}
			}
			else // Nothing active tapped in the target content view
			{
				if ([lastHideTime timeIntervalSinceNow] < -0.75) // Delay since hide
				{
					if ((mainPagebar.hidden == YES))
					{
						[mainPagebar showPagebar]; // Show
                        [UIView animateWithDuration:0.25 delay:0.0
                                            options:UIViewAnimationOptionCurveLinear | UIViewAnimationOptionAllowUserInteraction
                                         animations:^(void)
                         {
                             [self.navigationController.navigationBar setHidden:NO];
                             [self.navigationController.navigationBar setAlpha:1.0f];
//                             
                         }
                                         completion:^(BOOL finished)
                         {
                            
                         }
                         ];

                        
					}
				}
			}

			return;
		}

		CGRect nextPageRect = viewRect;
		nextPageRect.size.width = TAP_AREA_SIZE;
		nextPageRect.origin.x = (viewRect.size.width - TAP_AREA_SIZE);

		if (CGRectContainsPoint(nextPageRect, point)) // page++ area
		{
			[self incrementPageNumber]; return;
		}

		CGRect prevPageRect = viewRect;
		prevPageRect.size.width = TAP_AREA_SIZE;

		if (CGRectContainsPoint(prevPageRect, point)) // page-- area
		{
			[self decrementPageNumber]; return;
		}
	}
}

- (void)handleDoubleTap:(UITapGestureRecognizer *)recognizer
{
	if (recognizer.state == UIGestureRecognizerStateRecognized)
	{
		CGRect viewRect = recognizer.view.bounds; // View bounds

		CGPoint point = [recognizer locationInView:recognizer.view];

		CGRect zoomArea = CGRectInset(viewRect, TAP_AREA_SIZE, TAP_AREA_SIZE);

		if (CGRectContainsPoint(zoomArea, point)) // Double tap is in the zoom area
		{
			NSInteger page = [document.pageNumber integerValue]; // Current page #

			NSNumber *key = [NSNumber numberWithInteger:page]; // Page number key

			ReaderContentView *targetView = [contentViews objectForKey:key];

			switch (recognizer.numberOfTouchesRequired) // Touches count
			{
				case 1: // One finger double tap: zoom ++
				{
					[targetView zoomIncrement]; break;
				}

				case 2: // Two finger double tap: zoom --
				{
					[targetView zoomDecrement]; break;
				}
			}

			return;
		}

		CGRect nextPageRect = viewRect;
		nextPageRect.size.width = TAP_AREA_SIZE;
		nextPageRect.origin.x = (viewRect.size.width - TAP_AREA_SIZE);

		if (CGRectContainsPoint(nextPageRect, point)) // page++ area
		{
			[self incrementPageNumber]; return;
		}

		CGRect prevPageRect = viewRect;
		prevPageRect.size.width = TAP_AREA_SIZE;

		if (CGRectContainsPoint(prevPageRect, point)) // page-- area
		{
			[self decrementPageNumber]; return;
		}
	}
}

#pragma mark ReaderContentViewDelegate methods

- (void)contentView:(ReaderContentView *)contentView touchesBegan:(NSSet *)touches
{
	if ((mainPagebar.hidden == NO))
	{
		if (touches.count == 1) // Single touches only
		{
			UITouch *touch = [touches anyObject]; // Touch info

			CGPoint point = [touch locationInView:self.view]; // Touch location

			CGRect areaRect = CGRectInset(self.view.bounds, TAP_AREA_SIZE, TAP_AREA_SIZE);

			if (CGRectContainsPoint(areaRect, point) == false) return;
		}

		[mainPagebar hidePagebar]; // Hide
        [UIView animateWithDuration:0.25 delay:0.0
                            options:UIViewAnimationOptionCurveLinear | UIViewAnimationOptionAllowUserInteraction
                         animations:^(void)
         {
             [self.navigationController.navigationBar setAlpha:0.0f];
         }
                         completion:^(BOOL finished)
         {
             [self.navigationController.navigationBar setHidden:YES];
         }
         ];

        SAFE_KILL(lastHideTime);
		lastHideTime = [[NSDate alloc] initWithTimeIntervalSinceNow:0];
	}
}



#pragma mark ThumbsViewControllerDelegate methods

- (void)dismissThumbsViewController:(ThumbsViewController *)viewController
{
	 // Update bookmark icon

	//[self dismissModalViewControllerAnimated:NO]; // Dismiss
    /* tsv */[self dismissViewControllerAnimated:NO completion:nil]; // Dismiss
}

- (void)thumbsViewController:(ThumbsViewController *)viewController gotoPage:(NSInteger)page
{
	[self showDocumentPage:page]; // Show the page
}

#pragma mark ReaderMainPagebarDelegate methods

- (void)pagebar:(ReaderMainPagebar *)pagebar gotoPage:(NSInteger)page
{
	[self showDocumentPage:page]; // Show the page
}

#pragma mark UIApplication notification methods


- (void)loadPdftoFS:(NSNotification *)notification
{
    if ([notification.name isEqualToString:kNotificationFileLoaded])
    {
//         _pdfUrl = [NSString stringWithFormat:SIEURLGetFile,_pdfId];
        if ([FileSystem pathExisted:[_pdfUrl copy]])
        {
            NSString *pathToPdf = [FileSystem filePathForUrlPath:_pdfUrl];
            document = [[ReaderDocument withDocumentFilePath:pathToPdf password:nil] retain];
            if (document != nil)
            {
                [alert dismissWithClickedButtonIndex:1 animated:YES];
//                [alert release];
                [self setInitialContent];
                [self viewWillAppear:YES];
                [self viewDidAppear:YES];
                
            }

        }
    }
    else if ([notification.name isEqualToString:kNotificationFileLoadFailed])
    {
        
        [alert dismissWithClickedButtonIndex:1 animated:NO];
        [alert release];
        UIAlertView *alertNO = [[UIAlertView alloc] initWithTitle:@"Ошибка" message:@"Загрузка не удалась,\nпопробуйте позже" delegate:self cancelButtonTitle:@"Ок" otherButtonTitles: nil];
        [alertNO show];
        [alertNO release];
    }
}

- (void)setProgress:(float)newProgress
{
    NSLog(@"progress %f",newProgress);
    [alert setMessage:[NSString stringWithFormat:@"%d%% \n ",(int)(newProgress*100)]];
    [self setProgressToPV:newProgress];
}
#pragma -mark delegate AlertView
- (void)alertView:(UIAlertView *)alertView willDismissWithButtonIndex:(NSInteger)buttonIndex
{
    if (alertView==alert)
    {
        [UFSLoader stopAndClear];
    }
    if (buttonIndex==0)
    {
        [self.navigationController popViewControllerAnimated:YES];
    }
//   
    }
- (void)willPresentAlertView:(UIAlertView *)alertView
{
    // setup background for progressView
    if (alertView==alert)
    {
    bgIndicator = [[UIView alloc] initWithFrame:CGRectMake(40, alert.bounds.size.height/2, alert.bounds.size.width-80, 15)];
    [bgIndicator setBackgroundColor:[UIColor clearColor]];
    [bgIndicator.layer setBorderColor:[RGBA(10, 21, 50, 0.7) CGColor]];
    [bgIndicator.layer setBorderWidth:1.3f];
    [bgIndicator.layer setCornerRadius:7.0f];
    [bgIndicator setTag:510];
    [bgIndicator setClipsToBounds:YES];
    [alert addSubview:bgIndicator];
    [bgIndicator release];
    
    CAGradientLayer *gradient = [[CAGradientLayer alloc] init];
    [gradient setColors:[NSArray arrayWithObjects:(id)[[UIColor clearColor] CGColor],(id)[RGBA(30, 53, 99, 1.0) CGColor], nil]];
    gradient.frame = bgIndicator.bounds;
    [gradient setStartPoint:CGPointMake(0.0, 0.0)];
    [gradient setEndPoint:CGPointMake(0.0, 1.0)];
    [gradient setCornerRadius:8.0f];
    [bgIndicator.layer addSublayer:gradient];
    [gradient release];

// setup progress bar
     _indicator = [[UIView alloc] initWithFrame:CGRectMake(0, 0, 0, 15)];
    [_indicator setBackgroundColor:[UIColor clearColor]];
    [_indicator setClipsToBounds:YES];
    [bgIndicator addSubview:_indicator];
     [_indicator release];
    
    CAGradientLayer *gradientProgress = [[CAGradientLayer alloc] init];
    [gradientProgress setColors:[NSArray arrayWithObjects:(id)[RGBA(216, 218, 225, 1.0) CGColor],(id)[RGBA(65, 77, 110, 1.0) CGColor], nil]];
    gradientProgress.frame = bgIndicator.bounds;
    [gradientProgress setStartPoint:CGPointMake(0, 0)];
    [gradientProgress setEndPoint:CGPointMake(0.0, 0.7)];
    [_indicator.layer addSublayer:gradientProgress];
    [gradientProgress release];
   
    
    }
}
#pragma -mark GradientProgressView
-(void)setProgressToPV:(float)progress
{
    [_indicator setFrame:CGRectMake(0, 0,  (bgIndicator.bounds.size.width)*progress,15)];
    if ([[[_indicator.layer sublayers] objectAtIndex:0] isKindOfClass:[CAGradientLayer class]])
    {
        [[[_indicator.layer sublayers] objectAtIndex:0] setFrame:_indicator.bounds];
    }
 
}
@end
