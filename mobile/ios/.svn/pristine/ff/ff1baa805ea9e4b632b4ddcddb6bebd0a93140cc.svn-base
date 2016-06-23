//
//  SmartImageView.h
//  Copyright 2012 iD EAST. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>



@class ASIHTTPRequest;

@class SmartImageView;
@protocol TapDetectingSmartImageViewDelegate <NSObject>

@optional
- (void)smartImageView:(SmartImageView *)smartImageView singleTapDetected:(UITouch *)touch;
- (void)smartImageView:(SmartImageView *)smartImageView doubleTapDetected:(UITouch *)touch;
- (void)smartImageView:(SmartImageView *)smartImageView tripleTapDetected:(UITouch *)touch;
@end

@interface SmartImageView : UIView
{
    UIActivityIndicatorView *indicator;
	
	UIImage         *image;
	NSString        *urlString;
	
	CGFloat         progress;
    float           scale;
    NSString        *imageNameForSave;
    id <TapDetectingSmartImageViewDelegate> tapDelegate;
}

@property (nonatomic, retain) UIImage *image;
@property (nonatomic, copy, readonly) NSString *urlString;
@property (nonatomic) CGRect                    imageRect;

- (void)setImageWithUrlString:(NSString *)imageUrlString AndName:(NSString *)imageName;
-(void)setIndicatorHide:(BOOL)isHide;

- (void)setScale:(float) newScale;
- (UIImage *) getImage:(CGFloat)width withHeight:(CGFloat)height;

@property (nonatomic, assign) id <TapDetectingSmartImageViewDelegate> tapDelegate;
@property (nonatomic) BOOL animtionHide;

//touches
- (void)handleSingleTap:(UITouch *)touch;
- (void)handleDoubleTap:(UITouch *)touch;
- (void)handleTripleTap:(UITouch *)touch;
@end
