//
//  ZoomingPhotoScrollView.m
//
//  Copyright 2012 id-East. All rights reserved.
//
//скролл / uiview подложка с обработкой тачей/ SmartImageView(ставит картинку в очередь на загрузку (если в кэше нет)) с обработкой тачей
#import "ZoomingPhotoScrollView.h"
#import "PhotoGalleryVC.h"
#import "BackTapDetectingView.h"
#import "SmartImageView.h"

#define max_zoom_scale 3.0

// Declare private methods of photoScroll
@interface PhotoGalleryVC ()
- (void)cancelControlHiding;
//- (void)hideControlsAfterDelay;
@end

// Private methods and properties
@interface ZoomingPhotoScrollView ()
@property (nonatomic, assign) PhotoGalleryVC *photoScroll;
- (void)handleSingleTap:(CGPoint)touchPoint;
- (void)handleDoubleTap:(CGPoint)touchPoint;
@end

@implementation ZoomingPhotoScrollView

@synthesize photoScroll = _photoScroll;
//init
- (id)initWithPhotoScroll:(PhotoGalleryVC *)parentScroll{
    
    if ((self = [super init])) {
        // Delegate
        self.photoScroll = parentScroll;
        
		// Tap view for background
		_backTapDetectingView = [[BackTapDetectingView alloc] initWithFrame:self.bounds];
		_backTapDetectingView.tapDelegate = self;
		//_backTapDetectingView.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight;
		_backTapDetectingView.backgroundColor = [UIColor clearColor];
		[self addSubview:_backTapDetectingView];
		//view for zooming
        _otherBackView = [[UIView alloc] initWithFrame:self.bounds];
        _otherBackView.backgroundColor = [UIColor clearColor];
        //_otherBackView.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight;
        [self addSubview:_otherBackView];
		
        // Image view
		_photoImageView = [[SmartImageView alloc] initWithFrame:self.bounds];
		_photoImageView.tapDelegate = self;
		_photoImageView.contentMode = UIViewContentModeScaleAspectFit;
        //_photoImageView.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight;
        _photoImageView.backgroundColor = [UIColor clearColor];
		[self addSubview:_photoImageView];
		
		// Setup
		self.backgroundColor = [UIColor clearColor];
		self.delegate = self;
		self.showsHorizontalScrollIndicator = NO;
		self.showsVerticalScrollIndicator = NO;
		self.decelerationRate = UIScrollViewDecelerationRateFast;
//		self.autoresizingMask = UIViewAutoresizingFlexibleWidth | UIViewAutoresizingFlexibleHeight;
        
        self.userInteractionEnabled = YES;
        super.userInteractionEnabled = YES;
    }
    return self;
}
//release
- (void)dealloc {
	SAFE_KILL(_backTapDetectingView);
	SAFE_KILL(_otherBackView);
    SAFE_KILL(_photoImageView);
	[super dealloc];
}

#pragma mark - Image

// Get and display image for object
- (void)displayImage:(id)objectSet {		

    // Reset
    self.maximumZoomScale = max_zoom_scale;
    self.minimumZoomScale = 1.0;
    self.zoomScale = 1;
	
    // Set image
    //get url
    NSString * urlString = nil;
    if (objectSet) {
        //проверка на тип данных isKindOfClass
        if ([objectSet isKindOfClass:[NSString class]]) {
            urlString = (NSString *)objectSet;
        }else if ([objectSet isKindOfClass:[FileImageUrlDB class]]) {
            urlString = ((FileImageUrlDB *)objectSet).url;
            NSLog(@"%@", urlString);
        }
        //NSLog(@"%d", self.photoScroll.getCurrentPageNumber);
    }
    
    if(urlString)
        [_photoImageView setImageWithUrlString: urlString AndName:[NSString stringWithFormat:kServerBasePath,urlString]];
    
    _photoImageView.hidden = NO;
    CGRect frame = self.bounds;//[[UIApplication sharedApplication]statusBarFrame];
	self.bounds = frame;
	_backTapDetectingView.frame = frame;
    _otherBackView.frame = frame;
    _photoImageView.frame = frame;
    self.contentSize = frame.size;
    self.contentOffset = CGPointZero;
    [self setNeedsLayout];
}


#pragma mark - Setup

- (void)setMaxMinZoomScalesForCurrentBounds {

    [UIView beginAnimations:@"chanfe frame" context:nil];
    [UIView setAnimationDuration:0.3f];
    [UIView setAnimationCurve:UIViewAnimationCurveLinear];
    
    //Min Scale
    CGFloat minScale = 1.0;
    
    //Max Scale
	CGFloat maxScale = max_zoom_scale;
	if ([UIScreen instancesRespondToSelector:@selector(scale)]) {
		maxScale = maxScale / [[UIScreen mainScreen] scale];
	}
	
	// Set zoom scale
	self.maximumZoomScale = maxScale;
	self.minimumZoomScale = minScale;
	
    self.zoomScale = minScale;
	
	// Reset position
    CGRect frame = self.bounds;

	self.contentSize = frame.size;
    self.contentOffset = CGPointZero;
    _backTapDetectingView.frame = frame;
	_otherBackView.frame = frame;
    _photoImageView.frame = frame;
    [self setNeedsLayout];
    [UIView commitAnimations];

}

#pragma mark - Layout

- (void)layoutSubviews {
    
//    float newScale = self.zoomScale;
    // Update tap view frame
	_backTapDetectingView.frame = self.bounds;
	
	// Super
	[super layoutSubviews];
	if (didZooming) {
        didZooming = NO;
        return;
    }
    // Center the image as it becomes smaller than the size of the screen
    CGSize boundsSize = self.bounds.size;
    CGRect frameToCenter = _otherBackView.frame;
    
    // Horizontally
    if (frameToCenter.size.width < boundsSize.width) {
        frameToCenter.origin.x = floorf((boundsSize.width - frameToCenter.size.width) / 2.0);
	} else {
        frameToCenter.origin.x = 0;
	}
    
    // Vertically
    if (frameToCenter.size.height < boundsSize.height) {
        frameToCenter.origin.y = floorf((boundsSize.height - frameToCenter.size.height) / 2.0);
	} else {
        frameToCenter.origin.y = 0;
	}
    
	// Center
    if (!CGRectEqualToRect(frameToCenter, CGRectZero) && !CGRectEqualToRect(frameToCenter, _photoImageView.frame)) {
        _otherBackView.frame = frameToCenter;
        _photoImageView.frame = frameToCenter;
    }
}

#pragma mark - UIScrollViewDelegate

- (UIView *)viewForZoomingInScrollView:(UIScrollView *)scrollView {
    
    return _otherBackView;
}

- (void)scrollViewWillBeginDragging:(UIScrollView *)scrollView {
    
    [_photoScroll cancelControlHiding];
}

- (void)scrollViewWillBeginZooming:(UIScrollView *)scrollView withView:(UIView *)view {
    
    [_photoScroll cancelControlHiding];
}

- (void)scrollViewDidEndZooming:(UIScrollView *)scrollView withView:(UIView *)view atScale:(float)scale {

//    NSLog(@"%@", NSStringFromCGRect(view.frame));
}

- (void) scrollViewDidZoom:(UIScrollView *)scrollView {
    
    if (scrollView.zoomScale) {
        
        NSLog(@"scrollView.zoomScale = %f", scrollView.zoomScale);
        CGSize boundsSize = self.bounds.size;
        CGRect frameToCenter = _otherBackView.frame;
        
        // Horizontally
        if (frameToCenter.size.width < boundsSize.width) {
            
            frameToCenter.origin.x = floorf((boundsSize.width - frameToCenter.size.width) / 2.0)*scrollView.zoomScale;
        } else {
            
            frameToCenter.origin.x = 0;
            
            if (_photoImageView.imageRect.origin.x > 0) {
                
                frameToCenter = CGRectSizeWidth(frameToCenter, frameToCenter.size.width - 2*_photoImageView.imageRect.origin.x);
                float offsetX = self.contentOffset.x+_photoImageView.imageRect.origin.x;
                
                offsetX = MIN(MAX(0, offsetX), frameToCenter.size.width);
                [self setContentOffset:CGPointMake(offsetX, self.contentOffset.y) animated:NO];

                _otherBackView.frame = CGRectSizeWidth(_otherBackView.frame, frameToCenter.size.width);
            }
            self.contentSize = frameToCenter.size;

        }
        
        // Vertically
        if (frameToCenter.size.height < boundsSize.height) {
            
            frameToCenter.origin.y = floorf((boundsSize.height - frameToCenter.size.height) / 2.0)*scrollView.zoomScale;
        } else {
            
            frameToCenter.origin.y = 0;
            if (_photoImageView.imageRect.origin.y > 0) {
                
                frameToCenter = CGRectSizeHeight(frameToCenter, frameToCenter.size.height - 2*_photoImageView.imageRect.origin.y);
                float offsetY =  self.contentOffset.y+_photoImageView.imageRect.origin.y;
                offsetY = MIN(MAX(0, offsetY), frameToCenter.size.height);
                [self setContentOffset:CGPointMake(self.contentOffset.x,offsetY) animated:NO];
                _otherBackView.frame = CGRectSizeHeight(_otherBackView.frame, frameToCenter.size.height);
            }
            self.contentSize = frameToCenter.size;
        }
        
        _photoImageView.frame = frameToCenter;
        didZooming = YES;
    }
}
- (void)scrollViewDidEndDragging:(UIScrollView *)scrollView willDecelerate:(BOOL)decelerate {

    //[_photoScroll hideControlsAfterDelay];
}

#pragma mark - Tap Detection

- (void)handleSingleTap:(CGPoint)touchPoint {
	
    [_photoScroll performSelector:@selector(toggleControls) withObject:nil afterDelay:0.2];
}

- (void)handleDoubleTap:(CGPoint)touchPoint {
	
	// Cancel any single tap handling
	[NSObject cancelPreviousPerformRequestsWithTarget:_photoScroll];
	
	// Zoom
	if (self.zoomScale == self.maximumZoomScale) {
		
		// Zoom out
		[self setZoomScale:self.minimumZoomScale animated:YES];
		
	} else {
		
		// Zoom in
		[self zoomToRect:CGRectMake(touchPoint.x, touchPoint.y, 1, 1) animated:YES];
	}
    //переопределим смещения
    [self performSelector:@selector(scrollViewDidZoom:) withObject:self afterDelay:0.2];
//    [self scrollViewDidZoom:self];
}

// Image touches
- (void)smartImageView:(SmartImageView *)smartImageView singleTapDetected:(UITouch *)touch { 
    
    [self handleSingleTap:[touch locationInView:smartImageView]];
}
- (void)smartImageView:(SmartImageView *)smartImageView doubleTapDetected:(UITouch *)touch{
    
    [self handleDoubleTap:[touch locationInView:smartImageView]];
}

// Background View
- (void)view:(UIView *)view singleTapDetected:(UITouch *)touch {
    
    [self handleSingleTap:[touch locationInView:view]];
}
- (void)view:(UIView *)view doubleTapDetected:(UITouch *)touch {
    
    [self handleDoubleTap:[touch locationInView:view]];
}
//ParentScroll set/get
- (PhotoGalleryVC*)getParentScroll{
    
    return self.photoScroll;
}

- (void)setParentScroll:(PhotoGalleryVC*)newGallery{
    self.photoScroll = newGallery;
}
@end
