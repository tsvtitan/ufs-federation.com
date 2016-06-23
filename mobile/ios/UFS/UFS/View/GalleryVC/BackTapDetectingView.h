//
//  BackTapDetectingView.h
//
//  Copyright 2012 id-East. All rights reserved.
//
//background view

@protocol BackTapDetectingViewDelegate;


@interface BackTapDetectingView : UIView {
	id <BackTapDetectingViewDelegate> tapDelegate;
}

@property (nonatomic, assign) id <BackTapDetectingViewDelegate> tapDelegate;
- (void)handleSingleTap:(UITouch *)touch;
- (void)handleDoubleTap:(UITouch *)touch;
- (void)handleTripleTap:(UITouch *)touch;
@end



@protocol BackTapDetectingViewDelegate <NSObject>
@optional
- (void)view:(UIView *)view singleTapDetected:(UITouch *)touch;
- (void)view:(UIView *)view doubleTapDetected:(UITouch *)touch;
- (void)view:(UIView *)view tripleTapDetected:(UITouch *)touch;
@end