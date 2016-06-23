//
//  ZoomingPhotoScrollView.h
//
//  Copyright 2012 id-East. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "BackTapDetectingView.h"
#import "SmartImageView.h"


@class PhotoGalleryVC;

@interface ZoomingPhotoScrollView : UIScrollView <UIScrollViewDelegate, TapDetectingSmartImageViewDelegate, BackTapDetectingViewDelegate> {
	
	PhotoGalleryVC          *_photoGallery;
    
	BackTapDetectingView    *_backTapDetectingView; // for background taps
	SmartImageView          *_photoImageView;
    UIView                  *_otherBackView;
    BOOL                    didZooming;
	
}


- (id)initWithPhotoScroll:(PhotoGalleryVC *)parentScroll;
- (void)displayImage:(id)objectSet;
- (void)setMaxMinZoomScalesForCurrentBounds;

- (PhotoGalleryVC*)getParentScroll;
- (void)setParentScroll:(PhotoGalleryVC*) newGallery;

@end
