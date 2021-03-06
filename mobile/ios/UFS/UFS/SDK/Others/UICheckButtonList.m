//
//  UICheckButtonList.m
//  based on DWTagList.m
//
//  Created by Dominic Wroblewski on 07/07/2012.
//  Copyright (c) 2012 Terracoding LTD. All rights reserved.
//
//  Created by Sergei Tomilov on 11/24/14.
//

#import "UICheckButtonList.h"
#import <QuartzCore/QuartzCore.h>

#define CORNER_RADIUS 10.0f
#define LABEL_MARGIN_DEFAULT 5.0f
#define BOTTOM_MARGIN_DEFAULT 5.0f
#define FONT_SIZE_DEFAULT 14.0f
#define HORIZONTAL_PADDING_DEFAULT 7.0f
#define VERTICAL_PADDING_DEFAULT 3.0f
#define BACKGROUND_COLOR [UIColor colorWithRed:0.93 green:0.93 blue:0.93 alpha:1.00]
#define TEXT_COLOR [UIColor blackColor]
#define TEXT_SHADOW_COLOR [UIColor whiteColor]
#define TEXT_DISABLED_COLOR [UIColor lightGrayColor]
#define TEXT_SHADOW_OFFSET CGSizeMake(0.0f, 1.0f)
#define BORDER_COLOR [UIColor lightGrayColor].CGColor
#define BORDER_WIDTH 1.0f
#define HIGHLIGHTED_BACKGROUND_COLOR [UIColor colorWithRed:0.40 green:0.80 blue:1.00 alpha:0.5]
#define DEFAULT_AUTOMATIC_RESIZE NO
#define DEFAULT_STRETCH_INSETS UIEdgeInsetsMake(0,0,0,0);

#define DEFAULT_LEFT_OFFSET 26.0f
#define DEFAULT_CHECKED YES

@implementation UICheckButton
@end

@interface UICheckButtonList () <UICheckButtonViewDelegate>

@end

@implementation UICheckButtonList

@synthesize view, textArray, automaticResize;

- (id)initWithFrame:(CGRect)frame
{
    self = [super initWithFrame:frame];
    if (self) {
        [self addSubview:view];
        [self setClipsToBounds:YES];
        self.automaticResize = DEFAULT_AUTOMATIC_RESIZE;
        self.highlightedBackgroundColor = HIGHLIGHTED_BACKGROUND_COLOR;
        self.font = [UIFont systemFontOfSize:FONT_SIZE_DEFAULT];
        self.labelMargin = LABEL_MARGIN_DEFAULT;
        self.bottomMargin = BOTTOM_MARGIN_DEFAULT;
        self.horizontalPadding = HORIZONTAL_PADDING_DEFAULT;
        self.verticalPadding = VERTICAL_PADDING_DEFAULT;
        self.cornerRadius = CORNER_RADIUS;
        self.borderColor = BORDER_COLOR;
        self.borderWidth = BORDER_WIDTH;
        self.textColor = TEXT_COLOR;
        self.textShadowColor = TEXT_SHADOW_COLOR;
        self.textShadowOffset = TEXT_SHADOW_OFFSET;
        self.textDisabledColor = TEXT_DISABLED_COLOR;
        self.leftOffset = DEFAULT_LEFT_OFFSET;
        self.stretchInsets = DEFAULT_STRETCH_INSETS;
    }
    return self;
}

- (id)initWithCoder:(NSCoder *)aDecoder {
    self = [super initWithCoder:aDecoder];
    if (self) {
        [self addSubview:view];
        [self setClipsToBounds:YES];
        self.highlightedBackgroundColor = HIGHLIGHTED_BACKGROUND_COLOR;
        self.font = [UIFont systemFontOfSize:FONT_SIZE_DEFAULT];
        self.labelMargin = LABEL_MARGIN_DEFAULT;
        self.bottomMargin = BOTTOM_MARGIN_DEFAULT;
        self.horizontalPadding = HORIZONTAL_PADDING_DEFAULT;
        self.verticalPadding = VERTICAL_PADDING_DEFAULT;
        self.cornerRadius = CORNER_RADIUS;
        self.borderColor = BORDER_COLOR;
        self.borderWidth = BORDER_WIDTH;
        self.textColor = TEXT_COLOR;
        self.textShadowColor = TEXT_SHADOW_COLOR;
        self.textShadowOffset = TEXT_SHADOW_OFFSET;
        self.textDisabledColor = TEXT_DISABLED_COLOR;
        self.leftOffset = DEFAULT_LEFT_OFFSET;
        self.stretchInsets = DEFAULT_STRETCH_INSETS;
    }
    return self;
}

- (void)setButtons:(NSArray *)array
{
    textArray = [[NSArray alloc] initWithArray:array];
    sizeFit = CGSizeZero;
    if (automaticResize) {
        [self display];
        self.frame = CGRectMake(self.frame.origin.x, self.frame.origin.y, sizeFit.width, sizeFit.height);
    }
    else {
        [self display];
    }
}

- (void)setButtonBackgroundColor:(UIColor *)color
{
    lblBackgroundColor = color;
    [self display];
}

- (void)setButtonHighlightColor:(UIColor *)color
{
    self.highlightedBackgroundColor = color;
    [self display];
}

- (void)setViewOnly:(BOOL)viewOnly
{
    if (_viewOnly != viewOnly) {
        _viewOnly = viewOnly;
        [self display];
    }
}

- (void)setStrechInsets:(UIEdgeInsets)stretchInsets
{
    self.stretchInsets = stretchInsets;
    [self display];
}

- (void)layoutSubviews
{
    [super layoutSubviews];
}

- (UIImage *) stretchImage:(UIImage *)image frame:(CGRect)frame;
{
    if (image!=nil) {
        
        /*
        CGRect imageRect = CGRectMake(0.0, 0.0, image.size.width, image.size.height);
        CGFloat scale = [UIScreen mainScreen].scale;
        
        UIGraphicsBeginImageContextWithOptions(image.size, NO, scale);
        [image drawInRect:imageRect];
    
        CGContextRef context = UIGraphicsGetCurrentContext();
    
        CGContextClearRect(context, CGRectMake(0, 0, image.size.width, 1));
        CGContextClearRect(context, CGRectMake(0, 0, 1, image.size.height));
        
        image = UIGraphicsGetImageFromCurrentImageContext();
        UIGraphicsEndImageContext(); */
    
        UIEdgeInsets insets = self.stretchInsets;
        
        UIImage *nineImage = [image resizableImageWithCapInsets:insets
                                                   resizingMode:UIImageResizingModeStretch];
    
        return nineImage;
    } else {
        return nil;
    }
}

- (void)setButtonImage:(UICheckButton *)button;
{
    UIImage *image = (button.isChecked)?_imageChecked:_imageUnchecked;
    
    image = [self stretchImage:image frame:button.frame];
    [button setBackgroundImage:image forState:UIControlStateNormal];
    [button setBackgroundImage:image forState:UIControlStateHighlighted];
    
    if (image!=_imageUnchecked) {
        image = [self stretchImage:_imageUnchecked frame:button.frame];
    }
    [button setBackgroundImage:image forState:UIControlStateDisabled];
}

- (void)display
{
    NSMutableArray *buttonViews = [NSMutableArray array];
    
    for (UIView *subview in [self subviews]) {
        if ([subview isKindOfClass:[UICheckButtonView class]]) {
            UICheckButtonView *buttonView = (UICheckButtonView*)subview;
            for (UIGestureRecognizer *gesture in [subview gestureRecognizers]) {
                [subview removeGestureRecognizer:gesture];
            }
            
            [buttonView.button removeTarget:nil action:nil forControlEvents:UIControlEventAllEvents];
            
            [buttonViews addObject:subview];
        }
        [subview removeFromSuperview];
    }
    
    CGRect previousFrame = CGRectZero;
    BOOL gotPreviousFrame = NO;
    
    NSInteger tag = 0;
    for (id text in textArray) {
        UICheckButtonView *buttonView;
        if (buttonViews.count > 0) {
            buttonView = [buttonViews lastObject];
            [buttonViews removeLastObject];
        }
        else {
            buttonView = [[UICheckButtonView alloc] init];
        }
        
        CGFloat maximumHeight = 0;
        
        if (_imageChecked!=nil) {
            maximumHeight = _imageChecked.size.height;
        }
        
        [buttonView updateWithString:text
                                font:self.font
                  constrainedToWidth:self.frame.size.width - (self.horizontalPadding * 2)
                             padding:CGSizeMake(self.horizontalPadding, self.verticalPadding)
                        minimumWidth:self.minimumWidth
                          leftOffset:self.leftOffset
                       maximumHeight:maximumHeight
         ];
        
        if (gotPreviousFrame) {
            CGRect newRect = CGRectZero;
            if (previousFrame.origin.x + previousFrame.size.width + buttonView.frame.size.width + self.labelMargin > self.frame.size.width) {
                newRect.origin = CGPointMake(0, previousFrame.origin.y + buttonView.frame.size.height + self.bottomMargin);
            } else {
                newRect.origin = CGPointMake(previousFrame.origin.x + previousFrame.size.width + self.labelMargin, previousFrame.origin.y);
            }
            newRect.size = buttonView.frame.size;
            [buttonView setFrame:newRect];
        }
        
        previousFrame = buttonView.frame;
        gotPreviousFrame = YES;
        
        [buttonView setBackgroundColor:[self getBackgroundColor]];
        [buttonView setCornerRadius:self.cornerRadius];
        [buttonView setBorderColor:self.borderColor];
        [buttonView setBorderWidth:self.borderWidth];
        [buttonView setTextColor:self.textColor];
        [buttonView setTextShadowColor:self.textShadowColor];
        [buttonView setTextDisabledColor:self.textDisabledColor];
        [buttonView setTag:tag];
        [buttonView setDelegate:self];
        
        [self setButtonImage:buttonView.button];
        
        tag++;
        
        [self addSubview:buttonView];
        
        if (!_viewOnly) {
            [buttonView.button addTarget:self action:@selector(touchDownInside:) forControlEvents:UIControlEventTouchDown];
            [buttonView.button addTarget:self action:@selector(touchUpInside:) forControlEvents:UIControlEventTouchUpInside];
        }
    }
    
    sizeFit = CGSizeMake(self.frame.size.width, previousFrame.origin.y + previousFrame.size.height + self.bottomMargin + 1.0f);
    self.contentSize = sizeFit;
}

- (CGSize)fittedSize
{
    return sizeFit;
}

- (void)scrollToBottomAnimated:(BOOL)animated
{
    [self setContentOffset: CGPointMake(0.0, self.contentSize.height - self.bounds.size.height + self.contentInset.bottom) animated: animated];
}

- (void)touchDownInside:(id)sender
{
    UICheckButton *button = (UICheckButton*)sender;
    button.isChecked = !button.isChecked;
    [self setButtonImage:button];
}

- (void)touchUpInside:(id)sender
{
    UICheckButton *button = (UICheckButton*)sender;
    UICheckButtonView *listView = (UICheckButtonView *)[button superview];

    if ([self.listDelegate respondsToSelector:@selector(selectedButton:buttonIndex:)]) {
        [self.listDelegate selectedButton:button buttonIndex:listView.tag];
    }
    
}

- (UIColor *)getBackgroundColor
{
    if (!lblBackgroundColor) {
        return BACKGROUND_COLOR;
    } else {
        return lblBackgroundColor;
    }
}

- (void)setCornerRadius:(CGFloat)cornerRadius
{
    _cornerRadius = cornerRadius;
    [self display];
}

- (void)setBorderColor:(CGColorRef)borderColor
{
    _borderColor = borderColor;
    [self display];
}

- (void)setBorderWidth:(CGFloat)borderWidth
{
    _borderWidth = borderWidth;
    [self display];
}

- (void)setTextColor:(UIColor *)textColor
{
    _textColor = textColor;
    [self display];
}

- (void)setTextShadowColor:(UIColor *)textShadowColor
{
    _textShadowColor = textShadowColor;
    [self display];
}

- (void)setTextShadowOffset:(CGSize)textShadowOffset
{
    _textShadowOffset = textShadowOffset;
    [self display];
}

- (void)setTextDisabledColor:(UIColor *)textDisabledColor
{
    _textDisabledColor = textDisabledColor;
    [self display];
}

- (void)setImageChecked:(UIImage*)imageChecked
{
    _imageChecked = imageChecked;
    [self display];
}

- (void)setImageUnchecked:(UIImage*)imageUnchecked
{
    _imageUnchecked = imageUnchecked;
    [self display];
}

- (void)setLeftOffset:(CGFloat)leftOffset
{
    _leftOffset = leftOffset;
    [self display];
}

- (void)dealloc
{
    view = nil;
    textArray = nil;
    lblBackgroundColor = nil;
    [super dealloc];
}

@end


@implementation UICheckButtonView

- (id)init
{
    self = [super init];
    if (self) {

        _button = [UICheckButton buttonWithType:UIButtonTypeCustom];
        _button.isChecked = DEFAULT_CHECKED;
        _button.autoresizingMask = UIViewAutoresizingFlexibleHeight | UIViewAutoresizingFlexibleWidth;
        
        [_button setFrame:self.frame];
        [self addSubview:_button];
        
        [self.layer setMasksToBounds:YES];
        [self.layer setCornerRadius:CORNER_RADIUS];
        [self.layer setBorderColor:BORDER_COLOR];
        [self.layer setBorderWidth:BORDER_WIDTH];
    }
    return self;
}

- (void)updateWithString:(id)text font:(UIFont*)font constrainedToWidth:(CGFloat)maxWidth padding:(CGSize)padding
            minimumWidth:(CGFloat)minimumWidth leftOffset:(CGFloat)leftOffset maximumHeight:(CGFloat)maximumHeight
{
    CGSize textSize = CGSizeZero;
    
    BOOL isTextAttributedString = [text isKindOfClass:[NSAttributedString class]];
    
    if (isTextAttributedString) {
        NSMutableAttributedString *attributedString = [[NSMutableAttributedString alloc] initWithAttributedString:text];
        [attributedString addAttributes:@{NSFontAttributeName: font} range:NSMakeRange(0, ((NSAttributedString *)text).string.length)];
        
        textSize = [attributedString boundingRectWithSize:CGSizeMake(maxWidth-leftOffset, 0) options:NSStringDrawingUsesLineFragmentOrigin context:nil].size;
        //_label.attributedText = [attributedString copy];
        
    } else {
        textSize = [text sizeWithFont:font forWidth:maxWidth-leftOffset lineBreakMode:NSLineBreakByTruncatingTail];
        [_button setTitle:text forState:UIControlStateNormal];
    }
    
    float h = textSize.height;
    
    textSize.width = MAX(textSize.width, minimumWidth) + padding.width + leftOffset;
    textSize.height = MAX(h + padding.height*2,maximumHeight);
    
    self.frame = CGRectMake(0, 0, textSize.width, textSize.height);
    
    _button.frame = self.frame;
    _button.titleLabel.font = font;
    
    [_button setContentHorizontalAlignment:UIControlContentHorizontalAlignmentLeft];
    [_button setContentVerticalAlignment:UIControlContentVerticalAlignmentTop];
    [_button setTitleEdgeInsets:UIEdgeInsetsMake((textSize.height - h)/2, leftOffset, 0.0f, 0.0f)];
}

- (void)setCornerRadius:(CGFloat)cornerRadius
{
    [self.layer setCornerRadius:cornerRadius];
}

- (void)setBorderColor:(CGColorRef)borderColor
{
    [self.layer setBorderColor:borderColor];
}

- (void)setBorderWidth:(CGFloat)borderWidth
{
    [self.layer setBorderWidth:borderWidth];
}

- (void)setLabelText:(NSString*)text
{
    [_button setTitle:text forState:UIControlStateNormal];
}

- (void)setTextColor:(UIColor *)textColor
{
    [_button setTitleColor:textColor forState:UIControlStateNormal];
}

- (void)setTextDisabledColor:(UIColor *)textDisabledColor
{
    [_button setTitleColor:textDisabledColor forState:UIControlStateDisabled];
}

- (void)setTextShadowColor:(UIColor*)textShadowColor
{
    [_button setTitleShadowColor:textShadowColor forState:UIControlStateNormal];
}

- (void)dealloc
{
    [_button release];
    _button = nil;
    [super dealloc];
}

@end
