//
//  PTRArrowView.m
//  Copyright (c) 2012 iD EAST. All rights reserved.
//

#import "PTRArrowView.h"


#define TAG_ARROW 1001
#define TAG_LABEL 1002
#define TAG_LABEL_UPDATE 2002

#define CONTROL_HEIGHT 55
#define ACTION_OFFSET 10
#define C255(color) (color / 255.0f)


@implementation PTRArrowView

@synthesize tableView;
@synthesize stage;
@synthesize value, dateLastUpdate;
@synthesize actionDelegate;

- (id)initWithFrame:(CGRect)frame
{
	self = [super initWithFrame:frame];
	if (self)
	{
		self.backgroundColor = [UIColor clearColor];
		self.tag = TAG_ARROW;
		
		self.layer.shadowColor = [UIColor blackColor].CGColor;
		self.layer.shadowOffset = CGSizeMake(0, 2);
		self.layer.shadowOpacity = 0.5;
		self.layer.shadowRadius = 3;
		
		label = [[UILabel alloc] initWithFrame:CGRectZero];
		label.backgroundColor = [UIColor clearColor];
		label.font = [UIFont boldSystemFontOfSize:13];
		label.textColor = [UIColor BMGrayColor];
		//label.textAlignment = UITextAlignmentCenter;
        /* tsv */label.textAlignment = NSTextAlignmentCenter;
		label.numberOfLines = 1;
		label.tag = TAG_LABEL;
		
        labelUpdate = [[UILabel alloc] initWithFrame:CGRectZero];
		labelUpdate.backgroundColor = [UIColor clearColor];
		labelUpdate.font = [UIFont systemFontOfSize:11];
		labelUpdate.textColor = [UIColor BMGrayColor];
		//labelUpdate.textAlignment = UITextAlignmentCenter;
        /* tsv */labelUpdate.textAlignment = NSTextAlignmentCenter;
		labelUpdate.numberOfLines = 2;
		labelUpdate.tag = TAG_LABEL_UPDATE;
        
        noActionText = @"Нет соединения: обновление невозможно";
		text = @"Потяните вниз для обновления";
		advancedText = @"Отпустите для обновления";
        actionText = @"Обновление списка";
		
        indicator = [[UIActivityIndicatorView alloc] initWithFrame:CGRectZero] ;
        indicator.activityIndicatorViewStyle = UIActivityIndicatorViewStyleGray;
        indicator.tag = 121;


        
		self.stage = ReloadStageNone;
	}
	return self;
}

- (void)dealloc
{
	self.tableView = nil;
    SAFE_KILL(dateLastUpdate);
	[label release];
	[labelUpdate release];
	[indicator release];
    indicator = nil;
    [super dealloc];
}

- (void)setFrame:(CGRect)frame
{
	[super setFrame:frame];

	self.layer.shadowPath = nil;
	[self setNeedsDisplay];
}

- (void)drawRect:(CGRect)rect
{
	static CGFloat margin = 2;
	CGRect rectToDraw = {
        
		{ margin, margin },
		{ rect.size.width - margin * 3, rect.size.height - margin * 3}
	};
	
	CGContextRef context = UIGraphicsGetCurrentContext();
	CGContextTranslateCTM(context, 0.3, 0.5);
	
	static CGFloat arrowHeight = 0.38;
	static CGFloat arrowWidth = 0.20;
	CGFloat arrowHBase = rectToDraw.size.width * arrowWidth / 2.0;
	CGFloat arrowVBase = rectToDraw.origin.y + arrowHeight * rectToDraw.size.height;
	CGMutablePathRef path = CGPathCreateMutable();
	
	CGPathMoveToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw)), rintf(CGRectGetMinY(rectToDraw)));
	CGPathAddLineToPoint(path, nil, rintf(CGRectGetMinX(rectToDraw) + 13.0), rintf(arrowVBase));
	CGPathAddLineToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) - arrowHBase), rintf(arrowVBase));
	CGPathAddLineToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) - arrowHBase), rintf(CGRectGetMaxY(rectToDraw)/2));
	CGPathAddLineToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) + arrowHBase), rintf(CGRectGetMaxY(rectToDraw)/2));
	CGPathAddLineToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) + arrowHBase), rintf(arrowVBase));
	CGPathAddLineToPoint(path, nil, rintf(CGRectGetMaxX(rectToDraw) - 13.0), rintf(arrowVBase));
	CGPathCloseSubpath(path);
    
    for (NSInteger i = 0; i < 18; i+=6){
    
    CGPathMoveToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) - arrowHBase), rintf(CGRectGetMaxY(rectToDraw)/2) + 6 + i);
    CGPathAddLineToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) - arrowHBase), rintf(CGRectGetMaxY(rectToDraw)/2)+2 + i);
	CGPathAddLineToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) + arrowHBase), rintf(CGRectGetMaxY(rectToDraw)/2)+2 + i);
    CGPathAddLineToPoint(path, nil, rintf(CGRectGetMidX(rectToDraw) + arrowHBase), rintf(CGRectGetMaxY(rectToDraw)/2)+6 + i);
    CGPathCloseSubpath(path);
    
    }
	
//    if (CGAffineTransformIsIdentity(self.layer.affineTransform)){
//        
//        CGContextSetRGBFillColor(context, 0.85, 0.85, 0.85, 1);
//    }else {
        
        CGContextSetRGBFillColor(context, 0.6, 0.6, 0.6, 0.90);
//    }
	CGContextAddPath(context, path);
	CGContextFillPath(context);
	
	CGContextSaveGState(context);
	{
		CGContextAddPath(context, path);
		CGContextClip(context);

		static const CGFloat Locations[2] = { 0, 1 };
		CGColorSpaceRef colorSpace = CGColorSpaceCreateDeviceRGB();
		CGGradientRef gradient;

		CGFloat yFrom = 0;
		CGFloat yTo = 0;
		if (CGAffineTransformIsIdentity(self.layer.affineTransform))
		{
			yFrom = rectToDraw.origin.y + (rectToDraw.size.height * (1 - value));
			yTo = CGRectGetMaxY(rectToDraw);
			
            static CGFloat Components[8] = {
				0.8, 0.8, 0.8, 1,
                0.7, 0.7, 0.7, 1
			};
			Components[3] = value;
			gradient = CGGradientCreateWithColorComponents(colorSpace, Components, Locations, 2);
		}
		else
		{
			yFrom = rectToDraw.origin.y + (rectToDraw.size.height * value);
			yTo = CGRectGetMaxY(rectToDraw);
			
			static CGFloat Components[8] = {
				0.8, 0.8, 0.8, 1,
                0.7, 0.7, 0.7, 1
			};
			Components[3] = 1 - value;
			gradient = CGGradientCreateWithColorComponents(colorSpace, Components, Locations, 2);
		}
		CGColorSpaceRelease(colorSpace);
		
		CGContextDrawLinearGradient(context,
									gradient,
									CGPointMake(0, yFrom),
									CGPointMake(0, yTo),
									0);
        if (gradient) {
            CFRelease(gradient);
        }
    }
	CGContextRestoreGState(context);
	
	CGContextSetShadowWithColor(context,
								CGSizeMake(0, 1),
								0.75,
								[UIColor colorWithWhite:1 alpha:0.1].CGColor);
	CGContextSetRGBStrokeColor(context, 0.5, 0.5, 0.5, 0.1);

	CGContextAddPath(context, path);
	CGContextStrokePath(context);
	
	if (self.layer.shadowPath == nil)
	{
		self.layer.shadowPath = path;
	}
	CGPathRelease(path);
	
	CGContextStrokePath(context);
}

- (void)observeValueForKeyPath:(NSString *)keyPath ofObject:(id)object change:(NSDictionary *)change context:(void *)context
{
	if (refreshing) {
        return;
    }
    if (tableView.contentOffset.y < 0)
	{	
		CGFloat tableInsets = fmaxf(tableView.contentInset.top, 10);
		
		switch (stage)
		{
			case ReloadStageNone:
			{
				if (tableView.tracking)
				{
					self.stage = ReloadStageUp;
				}
				break;
			}
			case ReloadStageUp:
			{
				if (tableView.tracking)
				{
					if (tableView.contentOffset.y < - CONTROL_HEIGHT - ACTION_OFFSET - tableInsets)
					{
						self.stage = ReloadStageDown;
					}
					else
					{
						self.value = fabs(fminf(0, (tableView.contentOffset.y + ACTION_OFFSET + tableInsets)) / CONTROL_HEIGHT);
					}
				}
				else
				{
					self.stage = ReloadStageNone;
				}
				break;
			}
			case ReloadStageDown:
			{
				if (tableView.tracking)
				{
					if (tableView.contentOffset.y > - ACTION_OFFSET - tableInsets)
					{
						self.stage = ReloadStageUp;
					}
					else
					{
						self.value = fabs(fminf(0, (tableView.contentOffset.y + ACTION_OFFSET + tableInsets)) / CONTROL_HEIGHT);
					}
				}
				else
				{
					self.stage = ReloadStageNone;
					if ([actionDelegate respondsToSelector:@selector(pullToRefreshAction)])
					{
						[actionDelegate performSelector:@selector(pullToRefreshAction)];
                        refreshing = YES;
                        [UIView animateWithDuration:0.2 animations:^{[self.tableView setContentInset:UIEdgeInsetsMake(CONTROL_HEIGHT, 0, 0, 0)];}];
                        double delayInSeconds = 1.0f;
                        dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, delayInSeconds * NSEC_PER_SEC);
                        dispatch_after(popTime, dispatch_get_main_queue(), ^{
                            refreshing = NO;
                            [UIView animateWithDuration:0.2 animations:^{[self.tableView setContentInset:UIEdgeInsetsZero];}];
                        });

                        
					}
				}
				break;
			}
            case LoadingState:
			{
                break;
			}

		}
	}
}

#pragma mark - Properties

- (void)setTableView:(UITableView *)table
{
	[tableView removeObserver:self forKeyPath:@"contentOffset"];
	tableView = table;
	[tableView addObserver:self forKeyPath:@"contentOffset" options:NSKeyValueObservingOptionNew context:nil];
	
	CGFloat tableInsets = fmaxf(tableView.contentInset.top, 0);

	static CGFloat arrowWidth = 50;
	self.frame = CGRectMake(CGRectGetMidX(tableView.bounds) - 100 - arrowWidth, -CONTROL_HEIGHT + 5 - tableInsets, arrowWidth, CONTROL_HEIGHT - 10);
	/*[label setTextAlignment:UITextAlignmentLeft];
    [labelUpdate setTextAlignment:UITextAlignmentLeft];*/
	/* tsv */
    [label setTextAlignment:NSTextAlignmentLeft];
    [labelUpdate setTextAlignment:NSTextAlignmentLeft];
    /* tsv */
    label.frame = CGRectMake(CGRectGetMidX(tableView.bounds) - 100, -CONTROL_HEIGHT - tableInsets + 10.0, 250, CONTROL_HEIGHT/3.0);
    labelUpdate.frame = CGRectMake(CGRectGetMidX(tableView.bounds) - 100, -CONTROL_HEIGHT - tableInsets + 0.0 + CONTROL_HEIGHT/3.0, 250, 2*CONTROL_HEIGHT/3.0);
    
    indicator.frame =  CGRectMake(CGRectGetMidX(tableView.bounds) - 135, -CONTROL_HEIGHT - tableInsets + 0.0 + CONTROL_HEIGHT/3.0 , 20, 20);

	[tableView addSubview:self];
	[tableView addSubview:label];
    [tableView addSubview:labelUpdate];
    [tableView addSubview:indicator];
    [indicator startAnimating];
    [indicator setHidden:YES];

}

- (void)setStage:(ReloadStage)st
{
	[self setActionDate:dateLastUpdate withFlag:NO];
    if (![UFSLoader reachable]) {
        self.hidden = NO;
        label.text = noActionText;
        label.hidden = NO;
        self.hidden = YES;
        return;
    }
    stage = st;

    CATransition *animation = [CATransition animation];
    animation.type = kCATransitionFade;
    animation.duration = 0.2;
    
    switch (stage)
	{
		case ReloadStageNone:
		{
            [indicator setHidden:NO];
            [self.layer addAnimation:animation forKey:nil];
            [label.layer addAnimation:animation forKey:nil];
			
			self.hidden = YES;
//			label.hidden = YES;
			label.hidden = NO;
            label.text = actionText;
//            if (!indicator)
//			{
//				indicator = [[[UIActivityIndicatorView alloc] initWithFrame:CGRectMake(self.originX, self.originY, 20.0, 20.0)] autorelease];
//				indicator.activityIndicatorViewStyle = UIActivityIndicatorViewStyleGray;
//				indicator.tag = 121;
//                [self.tableView addSubview:indicator];
//			}
            [indicator setHidden:NO];
//			[indicator startAnimating];
            break;
		}
		case ReloadStageUp:
		{
            [indicator setHidden:YES];
			if (self.hidden)
			{
                [self.layer addAnimation:animation forKey:nil];
                [label.layer addAnimation:animation forKey:nil];
				
				self.layer.affineTransform = CGAffineTransformIdentity;
				self.hidden = NO;
				label.hidden = NO;
			}
			
			if (CGAffineTransformIsIdentity(self.layer.affineTransform))//!
			{
				CABasicAnimation * rotationAnimation = [CABasicAnimation animationWithKeyPath:@"transform.rotation"];
				rotationAnimation.timingFunction = [CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseInEaseOut];
				rotationAnimation.fromValue = [NSNumber numberWithFloat:0];
				rotationAnimation.toValue = [NSNumber numberWithFloat:M_PI];
				rotationAnimation.duration = 0.3;
				[self.layer addAnimation:rotationAnimation forKey:@"rotation"];
			}
			self.layer.affineTransform = CGAffineTransformMakeRotation(M_PI);
			label.text = text;
			break;
		}
		case ReloadStageDown:
		{
			label.text = advancedText;
            [indicator setHidden:YES];
			CABasicAnimation * rotationAnimation = [CABasicAnimation animationWithKeyPath:@"transform.rotation"];
			rotationAnimation.timingFunction = [CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseInEaseOut];
			rotationAnimation.fromValue = [NSNumber numberWithFloat:M_PI];
			rotationAnimation.toValue = [NSNumber numberWithFloat:0];
			rotationAnimation.duration = 0.3;
			[self.layer addAnimation:rotationAnimation forKey:@"rotation"];
			
			self.layer.affineTransform = CGAffineTransformMakeRotation(0);
			break;
		}
        case LoadingState:
        {
            [indicator setHidden:NO];
            break;
        }
	}
}

- (void)setValue:(CGFloat)theValue
{
	value = fmaxf(0, fminf(1, theValue));
	[self setNeedsDisplay];
}

- (void)setActionDate:(NSNumber *)newActionDate withFlag:(BOOL)flag{
    if (flag) {
        double delayInSeconds = 1.0;
        dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, delayInSeconds * NSEC_PER_SEC);
        dispatch_after(popTime, dispatch_get_main_queue(), ^{
            refreshing = NO;
            //        if (indicator) {
            //            [indicator stopAnimating];
            //            [indicator removeFromSuperview], indicator = nil;
            //        }
            [UIView animateWithDuration:0.2 animations:^{[self.tableView setContentInset:UIEdgeInsetsZero];}];
        });
    }
    
    if (newActionDate) {
        self.dateLastUpdate = newActionDate;
        labelUpdate.text = [NSString stringWithFormat:@"Последнее обновление: %@", newActionDate ? [Helper getDateTodayFormat:[NSDate dateWithTimeIntervalSince1970:[newActionDate doubleValue]]] : @""];
    }
}
- (void)setActionText:(NSString *)newActionText {
    
    actionText = newActionText;
}
- (void)setRefreshing:(BOOL)refrash{
//    if (indicator) {
//        [indicator stopAnimating];
//        [indicator removeFromSuperview], indicator = nil;
//    }
    refreshing = refrash;
}
@end


@implementation UITableView (PullToRefresh)

- (void)appendPullToRefresh:(id)delegate
{
	if ([self viewWithTag:TAG_ARROW] == nil)
	{
		PTRArrowView *arrowView = [[[PTRArrowView alloc] initWithFrame:CGRectZero] autorelease];
		arrowView.actionDelegate = delegate;
		arrowView.tableView = self;
        arrowView.tag = TAG_ARROW;
	}
}

- (void)removePullToRefresh
{
//    [self removeObserver:self forKeyPath:@"contentOffset"];
    
    PTRArrowView *arrowView = (PTRArrowView *)[self viewWithTag:TAG_ARROW];
    arrowView.tableView = nil;
    [[self viewWithTag:TAG_LABEL_UPDATE] removeFromSuperview];
	[[self viewWithTag:TAG_LABEL] removeFromSuperview];
	[[self viewWithTag:TAG_ARROW] removeFromSuperview];
    
}

- (void) setActionText:(NSString *)newActionText{
    UIView *arrowView = [self viewWithTag:TAG_ARROW];
    if ([arrowView isKindOfClass:[PTRArrowView class]]) {
        [(PTRArrowView *)arrowView setActionText: newActionText];
    }
}

- (void)setActionDate:(NSNumber *)newActionDate {
    double delayInSeconds = 0.0;
    dispatch_time_t popTime = dispatch_time(DISPATCH_TIME_NOW, delayInSeconds * NSEC_PER_SEC);
    dispatch_after(popTime, dispatch_get_main_queue(), ^{
        UIView *arrowView = [self viewWithTag:TAG_ARROW];
        if ([arrowView isKindOfClass:[PTRArrowView class]]) {
            [(PTRArrowView *)arrowView setRefreshing: NO];
            [(PTRArrowView *)arrowView setDateLastUpdate:newActionDate];

        }
        [UIView animateWithDuration:0.1 animations:^{[self setContentInset:UIEdgeInsetsZero];}];
    });

    if (newActionDate) {
        
        [(UILabel*)[Helper getObjectWithTag:TAG_LABEL_UPDATE withClassname:@"UILabel" withParentView:self] setText: [NSString stringWithFormat:@"Последнее обновление: %@", newActionDate ? [Helper getDateTodayFormat:[NSDate dateWithTimeIntervalSince1970:[newActionDate doubleValue]]] : @""]];
    }
}
@end
