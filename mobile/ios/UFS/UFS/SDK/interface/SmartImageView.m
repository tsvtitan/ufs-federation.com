//
//  SmartImageView.m
//  Copyright 2011 iD EAST. All rights reserved.
//

#import "SmartImageView.h"



@interface SmartImageView()

@property (nonatomic, copy) NSString *urlString;

@end

@implementation SmartImageView

@synthesize image;
@synthesize urlString;

@synthesize tapDelegate;

- (SmartImageView *)initWithFrame:(CGRect)frame
{
	self = [super initWithFrame:frame];
	if(self)
	{
		self.backgroundColor = [UIColor clearColor];
		self.contentMode = UIViewContentModeScaleAspectFit;
		self.userInteractionEnabled = YES;
		super.userInteractionEnabled = YES;
        image = nil;
		urlString = nil;
		scale = 1.0f;
		indicator = nil;
        self.animtionHide = NO;
	}
	return self;
}

- (void)dealloc
{
	[[NSNotificationCenter defaultCenter] removeObserver:self];
	
//	request.downloadProgressDelegate = nil;
//	[request release], request = nil;
    self.urlString = nil;
    SAFE_KILL(image);
	[super dealloc];
}

- (void)setFrame:(CGRect)frame
{
	[super setFrame:frame];
	
	[indicator setFrame:CGRectMake(frame.size.width / 2.0f - 10.0f, frame.size.height / 2.0f - 10.0f, 20.0f, 20.0f)];
	[self setNeedsDisplay];
}

- (void)setImage:(UIImage *)theImage
{
	[image autorelease];
	image = [theImage retain];
	
	[self setNeedsDisplay];
}

#pragma mark - Actions

- (void)setImageWithUrlString:(NSString *)imageUrlString AndName:(NSString *)imageName {
    
    SAFE_KILL(imageNameForSave);
    imageNameForSave = [imageName copy];
    if ([imageUrlString isEqualToString:@""] || imageUrlString==nil) {
        
        return;
    }
    
	if (![urlString isEqualToString:imageUrlString])
	{
		[[NSNotificationCenter defaultCenter] removeObserver:self];
        // Stop previous loading
		      
		[NSObject cancelPreviousPerformRequestsWithTarget:self];
		
		self.urlString = imageUrlString;
		
		// Set up new image
		if ([FileSystem pathExisted:imageName])
		{
			self.image = [FileSystem uncachedImageWithPath:imageName];
            
            if (indicator) {
                
                [indicator removeFromSuperview], indicator = nil;
            }
		}
		else
		{
			self.image = nil;
            if ([UFSLoader reachable])
            {
                if (!indicator)
                {
//                    indicator = [[[UIActivityIndicatorView alloc] initWithFrame:CGRectMake(self.width / 2.0f - 10.0f, self.height / 2.0f - 10.0f, 20.0f, 20.0f)] autorelease];
//                    indicator.activityIndicatorViewStyle = UIActivityIndicatorViewStyleWhite;
//                    [indicator setColor:[UIColor grayColor]];
//                    [indicator setBackgroundColor:[UIColor clearColor]];
//                    indicator.tag = 121;
//                    [self addSubview:indicator];
                }
//                [indicator startAnimating];
                
                [self performSelector:@selector(beginLoadingImageWithUrlString:) withObject:urlString afterDelay:0];
            }
		}
	}
}

- (void)beginLoadingImageWithUrlString:(NSString *)imageUrlString
{
	
	[[NSNotificationCenter defaultCenter] removeObserver:self];
	[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(update:) name:kNotificationImageLoaded object:nil];
	[[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(update:) name:kNotificationImageLoadFailed object:nil];
	if ([UFSLoader reachable]){
        AFHTTPRequestOperation *requestOp = [[UFSLoader getImage:imageUrlString AndName:imageNameForSave] retain];
        [requestOp setDownloadProgressBlock:^(NSUInteger bytesRead, long long totalBytesRead, long long totalBytesExpectedToRead) {
            [self setProgress:(totalBytesRead/totalBytesExpectedToRead)];
        }];
        }
}

#pragma mark - Engine

- (void)update:(NSNotification *)notification
{
	if (self.image == nil)
	{
		NSString *pathString = [notification object];
		
		if ([pathString isKindOfClass:[NSString class]] &&
			[imageNameForSave isEqualToString:pathString])
		{
			if (indicator) {
                
                [indicator stopAnimating];
                [indicator removeFromSuperview], indicator = nil;
            }
			self.image = [FileSystem uncachedImageWithPath:imageNameForSave];
            self.urlString = nil;
		}
	}
}
-(void)setIndicatorHide:(BOOL)isHide
{
    if (indicator) {
        
        [indicator removeFromSuperview], indicator = nil;
    }

}
// ASIHTTPRequest
- (void)setProgress:(CGFloat)theProgress
{
	progress = theProgress;
//    NSLog(@"\nprogress\n%f", progress);
	[self setNeedsDisplay];
}

- (void)setScale:(float) newScale {
    
    scale = newScale;
}

- (void)drawRect:(CGRect)rect
{
    //if (scale <= 0.0) {
    
    scale = 1.0f;
    //}else {
    
    //    rect = CGRectMake(rect.origin.x, rect.origin.y, rect.size.width*scale, rect.size.height*scale);
    //}
    //CGRect fullRect = CGRectMake(self.bounds.origin.x, self.bounds.origin.y, self.bounds.size.width*scale, self.bounds.size.height*scale);//self.bounds;
    //    if (self.bounds.size.height != self.height) {
    //
    //        fullRect = CGRectMake(self.bounds.origin.x, self.bounds.origin.y, self.width, self.height);
    //    } else {
    //
    //        fullRect = self.bounds;
    //    }
	CGRect fullRect = self.bounds;
	CGContextRef context = UIGraphicsGetCurrentContext();
	CGContextClipToRect(context, rect);
	
	if(image)
	{
        //        NSLog(@"%@", NSStringFromCGSize(image.size));
		
//		[request release], request = nil;
		
		CGFloat width = 0.0f;
		CGFloat height = 0.0f;
		switch (self.contentMode)
		{
			case UIViewContentModeScaleAspectFit:
            case UIViewContentModeScaleAspectFit | UIViewContentModeTop:
			{
				CGFloat aspect = fmin(fullRect.size.width / image.size.width, fullRect.size.height / image.size.height);
				width = image.size.width * aspect;
				height = image.size.height * aspect;
				break;
			}
			case UIViewContentModeScaleAspectFill:
			{
				CGFloat aspect = fmax(fullRect.size.width / image.size.width, fullRect.size.height / image.size.height);
				width = image.size.width * aspect;
				height = image.size.height * aspect;
				break;
			}
			case UIViewContentModeScaleToFill:
			{
				width = rect.size.width;
				height = rect.size.height;
				break;
			}
			case UIViewContentModeCenter:
			{
                width = image.size.width;
				height = image.size.height;
				//self.frame = CGRectMake(0, 0, width, height);
                //fullRect = CGRectMake(0, 0, width, height);
				break;
			}
            default:
			{
				width = rect.size.width;
				height = rect.size.height;
				break;
			}
		}
        CGRect imageRect;
        if (self.contentMode == (UIViewContentModeScaleAspectFit | UIViewContentModeTop)) {
            
            imageRect = CGRectMake(roundf(roundf(fullRect.size.width - width) / (2.0f * scale)), 0, roundf(width), roundf(height));
        }else{
            
            imageRect = CGRectMake(roundf(roundf(fullRect.size.width - width) / (2.0f * scale)), roundf((fullRect.size.height - height) / (2.0f * scale)), roundf(width), roundf(height));
        }
        
        //        imageRect = {
        //			{roundf(roundf(fullRect.size.width - width) / (2.0f * scale)), roundf((fullRect.size.height - height) / (2.0f * scale))},
        //			{roundf(width), roundf(height)}
        //		};
        
        [image drawInRect:imageRect];
        
    }
	else
	{
		if (![UFSLoader reachable])
		{
			[indicator stopAnimating];
			
			[[UIColor colorWithWhite:0.2f alpha:1.0f] setFill];
			CGRect textRect = {
				{rect.origin.x, rect.origin.y + rintf((rect.size.height - 40.0f) / 2.0f)},
				{rect.size.width, 40.0f}
			};
            float fontSize = 15.0f;
			if (self.width < 150.0) {
                
                fontSize = 10.0;
            }
            
            if (self.width < 50.0) {
                
                fontSize = 12.0;
                //[@"\nø" drawInRect:textRect withFont:[UIFont boldSystemFontOfSize:fontSize] lineBreakMode:UILineBreakModeWordWrap alignment:UITextAlignmentCenter];
                /* tsv */[@"\nø" drawInRect:textRect withFont:[UIFont boldSystemFontOfSize:fontSize] lineBreakMode:NSLineBreakByWordWrapping alignment:NSTextAlignmentCenter];
            }else{
                
                //[@"" drawInRect:textRect withFont:[UIFont boldSystemFontOfSize:fontSize] lineBreakMode:UILineBreakModeWordWrap alignment:UITextAlignmentCenter];
                /* tsv */[@"" drawInRect:textRect withFont:[UIFont boldSystemFontOfSize:fontSize] lineBreakMode:NSLineBreakByWordWrapping alignment:NSTextAlignmentCenter];
            }
		}
		else
		{
//			[indicator startAnimating];
		}
	}
	
	if (fminf(fullRect.size.width, fullRect.size.height) >= 100.0f)
	{
		if(progress < 1.0f && progress > 0.0f)
		{
			CGFloat centerX = self.width / 2.0f;
			CGFloat centerY = self.height / 2.0f;
			
			CGFloat r = 18.0f;
			CGFloat finalAngle = M_PI * 2.0f * progress;
			
			CGContextSetRGBStrokeColor(context, 0.2f, 0.2f, 0.2f, 1.0f);	// Light
			CGContextSetLineWidth(context, 12.0f);
			
			CGContextMoveToPoint(context, centerX, centerY - r);
			CGContextAddArc(context, centerX, centerY, r, -M_PI_2, finalAngle - M_PI_2, 0);
			
			CGContextStrokePath(context);
		}
	}
}

- (UIImage *) getImage:(CGFloat)width withHeight:(CGFloat)height {
    //get ref from your source image
    CGImageRef myImage = image.CGImage;
    
    const size_t bitsPerComponent = CGImageGetBitsPerComponent(myImage);
    CGColorSpaceRef colorSpace = CGImageGetColorSpace(myImage);
    
    //CGContextRef context = CGBitmapContextCreate(nil, width, height, bitsPerComponent, 0, colorSpace, kCGImageAlphaPremultipliedFirst);
    /* tsv */CGContextRef context = CGBitmapContextCreate(nil, width, height, bitsPerComponent, 0, colorSpace, (CGBitmapInfo)kCGImageAlphaPremultipliedFirst);
    CGContextDrawImage(context, CGRectMake(0, 0, width, height), myImage);
    CGImageRef newImage = CGBitmapContextCreateImage (context);
    CGContextRelease(context);
    UIImage *myUIImage = [UIImage imageWithCGImage:newImage];
    CGImageRelease(newImage);
    
    return myUIImage;
}


- (void)touchesEnded:(NSSet *)touches withEvent:(UIEvent *)event {
	
    UITouch *touch = [touches anyObject];
	NSUInteger tapCount = touch.tapCount;
	switch (tapCount) {
		case 1:
			[self handleSingleTap:touch];
			break;
		case 2:
			[self handleDoubleTap:touch];
			break;
		case 3:
			[self handleTripleTap:touch];
			break;
		default:
			break;
	}
	[[self nextResponder] touchesEnded:touches withEvent:event];
}

- (void)handleSingleTap:(UITouch *)touch {
	if ([tapDelegate respondsToSelector:@selector(smartImageView:singleTapDetected:)])
		[tapDelegate smartImageView:self singleTapDetected:touch];
}

- (void)handleDoubleTap:(UITouch *)touch {
	if ([tapDelegate respondsToSelector:@selector(smartImageView:doubleTapDetected:)])
		[tapDelegate smartImageView:self doubleTapDetected:touch];
}

- (void)handleTripleTap:(UITouch *)touch {
	if ([tapDelegate respondsToSelector:@selector(smartImageView:tripleTapDetected:)])
		[tapDelegate smartImageView:self tripleTapDetected:touch];
}
#pragma mark - hide animation
- (void)setHidden:(BOOL)hidden{
    
    if (_animtionHide) {
        
        CATransition *animation = [CATransition animation];
        animation.timingFunction = [CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseOut];
        animation.type = kCATransitionFade;
        animation.duration = 0.5f;
        [self.layer addAnimation:animation forKey:nil];
    }
    [super setHidden:hidden];
}
@end
