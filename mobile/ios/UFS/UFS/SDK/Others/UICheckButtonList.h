//
//  UICheckButtonList.h
//  based on DWTagList.h
//
//  Created by Dominic Wroblewski on 07/07/2012.
//  Copyright (c) 2012 Terracoding LTD. All rights reserved.
//
//  Created by Sergei Tomilov on 11/24/14.
//

#import <UIKit/UIKit.h>

@protocol UICheckButtonListDelegate, UICheckButtonViewDelegate;

@interface UICheckButtonList : UIScrollView
{
    UIView      *view;
    NSArray     *textArray;
    CGSize      sizeFit;
    UIColor     *lblBackgroundColor;
}

@property (nonatomic) BOOL viewOnly;
@property (nonatomic, strong) UIView *view;
@property (nonatomic, strong) NSArray *textArray;
@property (nonatomic, assign) id<UICheckButtonListDelegate> listDelegate;
@property (nonatomic, strong) UIColor *highlightedBackgroundColor;
@property (nonatomic) BOOL automaticResize;
@property (nonatomic, strong) UIFont *font;
@property (nonatomic, assign) CGFloat labelMargin;
@property (nonatomic, assign) CGFloat bottomMargin;
@property (nonatomic, assign) CGFloat horizontalPadding;
@property (nonatomic, assign) CGFloat verticalPadding;
@property (nonatomic, assign) CGFloat minimumWidth;
@property (nonatomic, assign) CGFloat cornerRadius;
@property (nonatomic, assign) CGColorRef borderColor;
@property (nonatomic, assign) CGFloat borderWidth;
@property (nonatomic, strong) UIColor *textColor;
@property (nonatomic, strong) UIColor *textShadowColor;
@property (nonatomic, assign) CGSize textShadowOffset;
@property (nonatomic, strong) UIColor *textDisabledColor;

@property (nonatomic, strong) UIImage *imageChecked;
@property (nonatomic, strong) UIImage *imageUnchecked;
@property (nonatomic, assign) UIEdgeInsets stretchInsets;
@property (nonatomic, assign) CGFloat leftOffset;


- (void)setButtonBackgroundColor:(UIColor *)color;
- (void)setButtonHighlightColor:(UIColor *)color;
- (void)setButtons:(NSArray *)array;
- (void)display;
- (CGSize)fittedSize;
- (void)scrollToBottomAnimated:(BOOL)animated;
- (void)setImageChecked:(UIImage*)imageChecked;
- (void)setImageUnchecked:(UIImage*)imageUnchecked;
- (void)setStrechInsets:(UIEdgeInsets)stretchInsets;

@end

@interface UICheckButton : UIButton
@property(nonatomic) BOOL isChecked;
@end

@interface UICheckButtonView : UIView

@property (nonatomic, strong) UICheckButton  *button;
@property (nonatomic, assign) id<UICheckButtonViewDelegate> delegate;

- (void)updateWithString:(NSString*)text
                    font:(UIFont*)font
      constrainedToWidth:(CGFloat)maxWidth
                 padding:(CGSize)padding
            minimumWidth:(CGFloat)minimumWidth
              leftOffset:(CGFloat)leftOffset
           maximumHeight:(CGFloat)maximumHeight;
- (void)setLabelText:(NSString*)text;
- (void)setCornerRadius:(CGFloat)cornerRadius;
- (void)setBorderColor:(CGColorRef)borderColor;
- (void)setBorderWidth:(CGFloat)borderWidth;
- (void)setTextColor:(UIColor*)textColor;
- (void)setTextShadowColor:(UIColor*)textShadowColor;
- (void)setTextDisabledColor:(UIColor*)textDisabledColor;

@end


@protocol UICheckButtonListDelegate <NSObject>

@optional

- (void)selectedButton:(UICheckButton *)button buttonIndex:(NSInteger)buttonIndex;

@end

@protocol UICheckButtonViewDelegate <NSObject>

@required

@end
