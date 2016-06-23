//
//  Helper.m
//  Copyright 2012 iD EAST. All rights reserved.
//

#import "Helper.h"
#import <objc/runtime.h>
#import <QuartzCore/QuartzCore.h>

static char *alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";

@implementation Helper
static char kAFImageRequestOperationObjectKey;
+ (void)printAvailableFonts
{
	for (NSString *family in [UIFont familyNames])
	{
		NSLog(@"%@", [UIFont fontNamesForFamilyName:family]);
	}
}

+ (NSInteger)getTagForBaseObjects:(NSArray*)objects{
    
    NSInteger tag = 0;
    NSInteger i = 0;
    for (id objectNews in objects) {
        i++;
        tag = tag + [objectNews identifier].integerValue * i;
    }
    return tag;
}

+ (UIImage *)getGradientImageWithStartColor:(UIColor*)startColor withEndColor:(UIColor*)endColor withSize:(CGSize)size{
    
    UIGraphicsBeginImageContext(size);
    CGContextRef currentContext = UIGraphicsGetCurrentContext();
    
    CGGradientRef gradient;
    CGColorSpaceRef rgbColorspace;
    size_t num_locations = 2;
    CGFloat locations[2] = { 0.0, 1.0 };
    
    CGFloat red1, green1, blue1, alpha1;
    
    //Create a sample color
    
    //Call getRed:green:blue:alpha: and pass in pointers to floats to take the answer.
    if ([startColor respondsToSelector:@selector(startColor:green:blue:alpha:)])
        {
             [startColor getRed:&red1 green:&green1 blue:&blue1 alpha:&alpha1];
            } else {
                 const CGFloat *components = CGColorGetComponents(startColor.CGColor);
                 red1 = components[0];
                 green1 = components[1];
                 blue1 = components[2];
                 alpha1 = components[3];
                }
    
    CGFloat red2, green2, blue2, alpha2;
    
    if ([endColor respondsToSelector:@selector(startColor:green:blue:alpha:)])
        {
             [endColor getRed:&red2 green:&green2 blue:&blue2 alpha:&alpha2];
            } else {
                 const CGFloat *components = CGColorGetComponents(endColor.CGColor);
                 red2 = components[0];
                 green2 = components[1];
                 blue2 = components[2];
                 alpha2 = components[3];
                }
    
    CGFloat components[8] = { red1, green1, blue1, alpha1,// Start color
         red2, green2, blue2, alpha2 }; // End color
    
    rgbColorspace = CGColorSpaceCreateDeviceRGB();
    gradient = CGGradientCreateWithColorComponents(rgbColorspace, components, locations, num_locations);
    
    CGContextDrawLinearGradient(currentContext, gradient, CGPointMake(0, 0), CGPointMake(0, size.height), 0);
    
    CGGradientRelease(gradient);
    CGColorSpaceRelease(rgbColorspace);
    UIImage *i = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return i;
}

#pragma mark - Values

+ (NSString *)getStatisticPath {
    
    NSString *plistPath = [NSSearchPathForDirectoriesInDomains(NSDocumentDirectory, NSUserDomainMask, YES) objectAtIndex:0];
    plistPath = [plistPath stringByAppendingPathComponent:@"AllNewsByRubrics.plist"];
    return plistPath;
}

+ (NSString *)spacedIntegerValue:(NSNumber *)value
{
	NSString *valueString = [Helper value:value plusNeeded:NO];
	NSRange range = [valueString rangeOfString:@","];
	return [valueString substringToIndex:range.location];
}

+ (NSString *)value:(NSNumber *)value plusNeeded:(BOOL)plus
{
	NSString *valueString = [NSString stringWithFormat:@"%.02f", fabs(value.doubleValue)];
	
	NSMutableString *valueStringWithSpaces = [[[NSMutableString alloc] init] autorelease];
	
	if (plus && value.doubleValue >= 0.0f)
	{
		[valueStringWithSpaces appendString:@"+ "];
	}
	else if (value.doubleValue < 0.0f)
	{
		[valueStringWithSpaces appendString:@"- "];
	}

	NSRange range = NSMakeRange(0, 0);
	for (NSInteger i = ([valueString length] - 3) % 3; i <= [valueString length] - 3; i += 3)
	{
		if (i > 3)
		{
			[valueStringWithSpaces appendString:@" "];
		}
		range = NSMakeRange(range.location + range.length, i - range.location - range.length);
		[valueStringWithSpaces appendString:[valueString substringWithRange:range]];
	}
	range = NSMakeRange([valueString length] - 2, 2);
	[valueStringWithSpaces appendFormat:@",%@", [valueString substringWithRange:range]];
	
	return [[valueStringWithSpaces copy] autorelease];
}
//data

+ (NSString *)getTime:(NSDate *)date
{
	if (date == nil)
	{
		return @"";
	}
//#warning location ???
	NSDateFormatter *dateFormatter = [[[NSDateFormatter alloc] init] autorelease];
    
    dateFormatter.dateFormat = @"HH:mm";
	
	return [dateFormatter stringFromDate:date];
}


+ (NSString *)getDate:(NSDate *)date
{
	if (date == nil)
	{
		return @"";
	}
	NSDateFormatter *dateFormatter = [[[NSDateFormatter alloc] init] autorelease];
    
    dateFormatter.dateFormat = @"HH:mm | dd MM";
	
	return [dateFormatter stringFromDate:date];
}

+ (NSString *)getDate:(NSDate *)date withFormat:(NSString *)format
{
	if (date == nil)
	{
		return @"";
	}
	NSDateFormatter *dateFormatter = [[[NSDateFormatter alloc] init] autorelease];
    
    dateFormatter.dateFormat = format;
	
	return [dateFormatter stringFromDate:date];
}

+ (NSString *)getDateAnotherFormat:(NSDate *)date
{
	if (date == nil)
	{
		return @"";
	}
	NSDateFormatter *dateFormatter = [[[NSDateFormatter alloc] init] autorelease];
    
    dateFormatter.dateFormat = @"dd/MM";
    
	return [dateFormatter stringFromDate:date];
}

+ (NSString *)getDateDdMmmmY:(NSDate *)date
{
	if (date == nil)
	{
		return @"";
	}
	NSDateFormatter *dateFormatter = [[[NSDateFormatter alloc] init] autorelease];
    
    dateFormatter.dateFormat = @"dd MMMM Y";
    
	return [dateFormatter stringFromDate:date];
}

+ (NSString *)getDateTodayFormat:(NSDate *)dateOld
{
    
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    // записываем в строку в нужном формате
    [formatter setDateFormat:@"d MM Y HH:mm"];
    NSString *formattedDateString = [formatter stringFromDate:dateOld];
    // получаем номер дня для полученной даты и для текущей даты
    NSCalendar *greg = [[NSCalendar alloc] initWithCalendarIdentifier:NSGregorianCalendar];
//    NSInteger dayOfYear = [greg ordinalityOfUnit:NSDayCalendarUnit inUnit:NSYearCalendarUnit forDate:date];
//    NSInteger dayOfYearToday = [greg ordinalityOfUnit:NSDayCalendarUnit inUnit:NSYearCalendarUnit forDate:[NSDate date]];
    [greg release];
    // считаем разницу в секундах
//    NSInteger interval = [date timeIntervalSinceNow];
//    if (interval < 86400) {
//
//        formattedDateString = [NSString stringWithFormat:@"%@ %@", @"За последние сутки", formattedDateString];
//    }
    
    // количество дней с 1 января 1970
    NSInteger dayCount = [dateOld timeIntervalSince1970] / 86400;
    NSInteger dayCountToday =  [[NSDate date] timeIntervalSince1970] / 86400;
    // и раздницу в днях между датами
    if (abs(dayCount - dayCountToday) == 0) {
        [formatter setDateFormat:@"d"];
        NSString *dateOldDay = [formatter stringFromDate:dateOld];
        NSString *dateDay = [formatter stringFromDate:[NSDate date]];
        
        [formatter setDateFormat:@"HH:mm"];
        formattedDateString = [formatter stringFromDate:dateOld];
        if ([dateDay isEqualToString:dateOldDay]) {
            
            formattedDateString = [NSString stringWithFormat:@"сегодня, %@", formattedDateString];
        }else {
            
            formattedDateString = [NSString stringWithFormat:@"вчера, %@", formattedDateString];
        }
    } else if (abs(dayCount - dayCountToday) == 1) {

        [formatter setDateFormat:@"HH:mm"];
        formattedDateString = [formatter stringFromDate:dateOld];
        formattedDateString = [NSString stringWithFormat:@"вчера, %@", formattedDateString];
    }
    [formatter release];
    return formattedDateString;
}


+ (id)getObjectWithTag:(NSInteger)tagNum withClassname:(NSString*)className withParentView:(UIView*)parentView{
    UIView *view = [parentView viewWithTag:tagNum];
    if ([view isKindOfClass:[NSClassFromString(className) class]]) {
        return view;
    }else {
        return nil;
    }
    
}

+ (NSString *)getDateBMFormat:(NSDate *)dateOld
{
    
    NSDateFormatter *formatter = [[NSDateFormatter alloc] init];
    // записываем в строку в нужном формате
    [formatter setDateFormat:@"d MM Y HH:mm"];
    NSString *formattedDateString = nil;
    // получаем номер дня для полученной даты и для текущей даты
    NSCalendar *greg = [[NSCalendar alloc] initWithCalendarIdentifier:NSGregorianCalendar];
    [greg release];
    
    
    // количество дней с 1 января 1970
    NSInteger dayCount = [dateOld timeIntervalSince1970] / 86400;
    NSInteger dayCountToday =  [[NSDate date] timeIntervalSince1970] / 86400;
    // и раздницу в днях между датами
    if (abs(dayCount - dayCountToday) == 0) {
        [formatter setDateFormat:@"d"];
        NSString *dateOldDay = [formatter stringFromDate:dateOld];
        NSString *dateDay = [formatter stringFromDate:[NSDate date]];
        
        [formatter setDateFormat:@"HH:mm"];
        formattedDateString = [formatter stringFromDate:dateOld];
        if (![dateDay isEqualToString:dateOldDay]) {
            
            [formatter setDateFormat:@"d MMMM HH:mm"];
            formattedDateString = [formatter stringFromDate:dateOld];
        }
    }
    else{
        
        [formatter setDateFormat:@"yyy"];
        NSString *yearOld = [formatter stringFromDate:dateOld];
        NSString *yearCurrent = [formatter stringFromDate:[NSDate date]];
        if (yearOld.integerValue != yearCurrent.integerValue) {
            
            [formatter setDateFormat:@"d MMMM yyy"];
            formattedDateString = [formatter stringFromDate:dateOld];
        }else{
            
            [formatter setDateFormat:@"d MMMM HH:mm"];
            formattedDateString = [formatter stringFromDate:dateOld];
        }
    }
    [formatter release];
    return formattedDateString;
}
+ (NSString *) removeAllButDigits:(NSString *)nativeString
{
    NSString *originalString = nativeString;
    NSMutableString *strippedString = [NSMutableString
                                       stringWithCapacity:originalString.length];
    
    NSScanner *scanner = [NSScanner scannerWithString:originalString];
    NSCharacterSet *numbers = [NSCharacterSet
                               characterSetWithCharactersInString:@"0123456789"];
    
    while ([scanner isAtEnd] == NO) {
        NSString *buffer;
        if ([scanner scanCharactersFromSet:numbers intoString:&buffer]) {
            [strippedString appendString:buffer];
            
        } else {
            [scanner setScanLocation:([scanner scanLocation] + 1)];
        }
    }
    
    NSLog(@"%@", strippedString); // "123123123"
    return strippedString;
}
+ (void) hideTabBar:(UITabBarController *) tabbarcontroller
{
    CGRect screenRect = [[UIScreen mainScreen] bounds];
    
//    [UIView beginAnimations:nil context:NULL];
//    [UIView setAnimationDuration:0.5f];
    float fHeight = screenRect.size.height;
    if(  UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation) )
    {
        fHeight = screenRect.size.width;
    }
    
    for(UIView *view in tabbarcontroller.view.subviews)
    {
        if([view isKindOfClass:[UITabBar class]])
        {
            [view setFrame:CGRectMake(view.frame.origin.x, fHeight, view.frame.size.width, view.frame.size.height)];
        }
        else
        {
            [view setFrame:CGRectMake(view.frame.origin.x, view.frame.origin.y, view.frame.size.width, fHeight)];
            view.backgroundColor = [UIColor blackColor];
        }
    }
//    [UIView commitAnimations];
}



+ (void) showTabBar:(UITabBarController *) tabbarcontroller
{
    CGRect screenRect = [[UIScreen mainScreen] bounds];
    float fHeight = screenRect.size.height - 49.0;
    
    if(  UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation) )
    {
        fHeight = screenRect.size.width - 49.0;
    }
    
//    [UIView beginAnimations:nil context:NULL];
//    [UIView setAnimationDuration:0.5];
    for(UIView *view in tabbarcontroller.view.subviews)
    {
        if([view isKindOfClass:[UITabBar class]])
        {
            [view setFrame:CGRectMake(view.frame.origin.x, fHeight, view.frame.size.width, view.frame.size.height)];
        }
        else
        {
            [view setFrame:CGRectMake(view.frame.origin.x, view.frame.origin.y, view.frame.size.width, fHeight)];
        }
    }
//    [UIView commitAnimations];
}

+ (UIImage *)getImageWithColor:(UIColor *)color withSize:(CGSize)size{
    
    UIGraphicsBeginImageContext(size);
    CGContextRef context = UIGraphicsGetCurrentContext();
    CGContextSetFillColor(context, CGColorGetComponents(color.CGColor));
    CGContextFillRect(context, CGRectMake(0, 0, size.width, size.height));
    
    UIImage *i = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return i;
}
#pragma mark Animations for view
//+ (void)animationSuckEffect:(UIView *)view
//{
//    
//    CATransition *animation = [CATransition animation];
//    [animation setDuration:0.75];
//    [animation setFillMode:kCAFillModeForwards];
//    [animation setTimingFunction:[CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseOut]];
//    [animation setType:@"suckEffect"];
//    [view.layer addAnimation:animation forKey:nil];
//}

+ (void)animationRippleEffect:(UIView *)view
{
    
    CATransition *animation = [CATransition animation];
    [animation setDuration:1.75];
    //animation.fromValue = [NSValue valueWithCGPoint:startPoint];
    //animation.toValue = [NSValue valueWithCGPoint:CGPointMake(200, 200)];
    [animation setFillMode:kCAFillModeForwards];
    [animation setTimingFunction:[CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseOut]];
    [animation setType:@"rippleEffect"];
    
    [view.layer addAnimation:animation forKey:nil];
}

@end

#pragma mark - Расширения стандартных классов

#pragma mark - NSString

@implementation NSString (BM)

- (NSDate *)dateFromFormat:(NSString *)dateFormat
{
	NSDateFormatter *df = [[NSDateFormatter new] autorelease];
	[df setDateFormat:dateFormat];
	return [df dateFromString:self];
}

- (NSInteger *)count
{
	
	return 0;
}

- (NSString *)replaceHTMLTags{
    
    NSString *newText = @"";
    if ([self length]>0) {
        
        newText = [NSString replaceHtmlCharactersWithUnicode: self];
        newText = [newText stringByReplacingOccurrencesOfString:@"\n" withString:@""];
        NSError *error = NULL;
        NSRegularExpression *tagRegEx = [NSRegularExpression regularExpressionWithPattern:@"<[^<^>]*>" options:NSRegularExpressionCaseInsensitive error:&error];
        if(tagRegEx){
            newText = [tagRegEx stringByReplacingMatchesInString:newText
                                                              options:0
                                                                range:NSMakeRange(0, [newText length])
                                                         withTemplate:@""];
        }
    }
    
    return newText;
}
@end

@implementation NSDictionary (BM)

- (NSInteger *)lenght
{
	
	return 0;
}

@end

@implementation NSNumber (BM)

- (NSInteger *)count
{
	
	return 0;
}

- (NSInteger *)lenght
{
	
	return 0;
}

@end
@interface UIImageView (_Helper)
@property (readwrite, nonatomic, strong, setter = af_setImageRequestOperation:) AFImageRequestOperation *af_imageRequestOperation;
@end
@implementation UIImageView (Helper)

- (AFHTTPRequestOperation *)af_imageRequestOperation {
    return (AFHTTPRequestOperation *)objc_getAssociatedObject(self, &kAFImageRequestOperationObjectKey);
}

- (void)af_setImageRequestOperation:(AFImageRequestOperation *)imageRequestOperation {
    objc_setAssociatedObject(self, &kAFImageRequestOperationObjectKey, imageRequestOperation, OBJC_ASSOCIATION_RETAIN_NONATOMIC);
}

+ (NSOperationQueue *)af_sharedImageRequestOperationQueue {
    static NSOperationQueue *_af_imageRequestOperationQueue = nil;
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        _af_imageRequestOperationQueue = [[NSOperationQueue alloc] init];
        [_af_imageRequestOperationQueue setMaxConcurrentOperationCount:NSOperationQueueDefaultMaxConcurrentOperationCount];
    });
    
    return _af_imageRequestOperationQueue;
}

#pragma mark -


- (void)setImageWithNSURL:(NSURL *)url {
    [self setImageWithNSURL:url placeholderImage:nil];
}


- (void)setImageWithNSURL:(NSURL *)url
         placeholderImage:(UIImage *)placeholderImage
{
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:url];
    [request setHTTPShouldHandleCookies:NO];
    [request addValue:@"image/*" forHTTPHeaderField:@"Accept"];
    //    if (IS_PAD)
    //    {
    //        NSData *imageData = [[FileManager shared] dataFromPath:url.absoluteString];
    //        [self setImageWithNSURLRequest:request placeholderImage:imageData ? [UIImage imageWithData:imageData] : placeholderImage success:nil failure:nil];
    //    }
    //    else
    [self setImageWithNSURLRequest:request placeholderImage:placeholderImage success:nil failure:nil];
}


- (void)setImageWithNSURLString:(NSString *)urlString
               placeholderImage:(UIImage *)placeholderImage
                        success:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, UIImage *image))success
                        failure:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, NSError *error))failure
{
	NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:[NSURL URLWithString:urlString]];
    [request setHTTPShouldHandleCookies:NO];
    [request addValue:@"image/*" forHTTPHeaderField:@"Accept"];
    
//    if (IS_PAD)
//    {
//        NSData *imageData = [FileSystem dataWithPath:urlString];
//        [self setImageWithNSURLRequest:request placeholderImage:imageData ? [UIImage imageWithData:imageData] : placeholderImage success:success failure:failure];
//    }
//    else
        [self setImageWithNSURLRequest:request placeholderImage:placeholderImage success:success failure:failure];
}


- (void)setImageWithNSURLRequest:(NSURLRequest *)urlRequest
                placeholderImage:(UIImage *)placeholderImage
                         success:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, UIImage *image))success
                         failure:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, NSError *error))failure
{
//    [self cancelImageRequestOperation];
//    
//    AFURLConnectionOperationResponseBlock responseBlock =  ^BOOL(NSURLConnection *connection, NSHTTPURLResponse *response)
//    {
//        return YES;
//    };
//    
//    self.image = placeholderImage;
//    
//    NSData *imageData = [FileSystem dataWithPath:urlRequest.URL.absoluteString];
//    if (imageData)
//    {
//        UIImage *image = [UIImage imageWithData:imageData];
//        self.image = image;
//        if (success)
//            success(nil, nil, image);
//        
//        if ([self respondsToSelector:@selector(af_setImageRequestOperation:)])
//            [self performSelector:@selector(af_setImageRequestOperation:) withObject:nil];
//    }
//    else
//    {
//        AFImageRequestOperation *requestOperation = [[AFImageRequestOperation alloc] initWithRequest:urlRequest];
//        [requestOperation setResponseBlock:responseBlock];
//        [requestOperation setCompletionBlockWithSuccess:^(AFHTTPRequestOperation *operation, id responseObject)
//         {
//             if ([[urlRequest URL] isEqual:[[self.af_imageRequestOperation request] URL]])
//             {
//                 self.image = [UIImage imageWithData:operation.responseData];
//                 if (success)
//                     success(operation.request, operation.response, [UIImage imageWithData:operation.responseData]);
//                 
//                 if ([self respondsToSelector:@selector(af_setImageRequestOperation:)])
//                     [self performSelector:@selector(af_setImageRequestOperation:) withObject:nil];
//             }
//             
//             [FileSystem  storeData:operation.responseData withPath:urlRequest.URL.absoluteString];
//             
//             //            [[[self class] af_sharedImageCache] cacheImage:responseObject forRequest:urlRequest];
//         } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
//             if ([[urlRequest URL] isEqual:[[self.af_imageRequestOperation request] URL]]) {
//                 if (failure)
//                     failure(operation.request, operation.response, error);
//                 
//                 if ([self respondsToSelector:@selector(af_setImageRequestOperation:)])
//                     [self performSelector:@selector(af_setImageRequestOperation:) withObject:nil];
//             }
//         }];
//        
//        //        self.af_imageRequestOperation = requestOperation;
//        if ([self respondsToSelector:@selector(af_setImageRequestOperation:)]) {
//            [self performSelector:@selector(af_setImageRequestOperation:) withObject:requestOperation];
//        }
//        
//        [[[self class] af_sharedImageRequestOperationQueue] addOperation:self.af_imageRequestOperation];
//    }
}


- (void)cancelImageRequestOperation {
    [self.af_imageRequestOperation cancel];
    self.af_imageRequestOperation = nil;
}

@end

@implementation UIImage (Helper)

+ (UIImage *)getGradientImageWithStartColor:(UIColor*)startColor withEndColor:(UIColor*)endColor withSize:(CGSize)size{
    
    UIGraphicsBeginImageContext(size);
    CGContextRef currentContext = UIGraphicsGetCurrentContext();
    
    CGGradientRef gradient;
    CGColorSpaceRef rgbColorspace;
    size_t num_locations = 2;
    CGFloat locations[2] = { 0.0, 1.0 };
    
    CGFloat red1, green1, blue1, alpha1;
    
    //Create a sample color
    
    //Call getRed:green:blue:alpha: and pass in pointers to floats to take the answer.
    if ([startColor respondsToSelector:@selector(startColor:green:blue:alpha:)])
    {
        [startColor getRed:&red1 green:&green1 blue:&blue1 alpha:&alpha1];
    } else {
        const CGFloat *components = CGColorGetComponents(startColor.CGColor);
        red1 = components[0];
        green1 = components[1];
        blue1 = components[2];
        alpha1 = components[3];
    }
    
    CGFloat red2, green2, blue2, alpha2;
    
    if ([endColor respondsToSelector:@selector(startColor:green:blue:alpha:)])
    {
        [endColor getRed:&red2 green:&green2 blue:&blue2 alpha:&alpha2];
    } else {
        const CGFloat *components = CGColorGetComponents(endColor.CGColor);
        red2 = components[0];
        green2 = components[1];
        blue2 = components[2];
        alpha2 = components[3];
    }
    
    CGFloat components[8] = { red1, green1, blue1, alpha1,  // Start color
        red2, green2, blue2, alpha2 }; // End color
    
    rgbColorspace = CGColorSpaceCreateDeviceRGB();
    gradient = CGGradientCreateWithColorComponents(rgbColorspace, components, locations, num_locations);
    
    CGContextDrawLinearGradient(currentContext, gradient, CGPointMake(0, 0), CGPointMake(0, size.height), 0);
    
    CGGradientRelease(gradient);
    CGColorSpaceRelease(rgbColorspace);
    UIImage *i = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return i;
}

+ (UIImage *)getGradientImageWithStartColor:(UIColor*)startColor withMidleColor:(UIColor*)midleColor withMidle2Color:(UIColor*)midle2Color withEndColor:(UIColor*)endColor withSize:(CGSize)size{
    
    UIGraphicsBeginImageContext(size);
    [[UIImage getGradientImageWithStartColor:startColor withEndColor:midleColor withSize:CGSizeMake(size.width, size.height/2.0)] drawInRect:CGRectMake(0, 0, size.width, size.height/2.0 - 1.0)];
    [[UIImage getGradientImageWithStartColor:midle2Color withEndColor:endColor withSize:CGSizeMake(size.width, size.height/2.0)] drawInRect:CGRectMake(0, size.height/2.0 + 1.0, size.width, size.height/2.0)];
    
    UIImage *i = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return i;
}

+ (UIImage *)imageWithColor:(UIColor *)imgColor size:(CGSize)imgSize
{
    if ([[UIScreen mainScreen] respondsToSelector:@selector(scale)]) {
        UIGraphicsBeginImageContextWithOptions(imgSize, NO, [UIScreen mainScreen].scale);
    } else {
        UIGraphicsBeginImageContext(imgSize);
    }
    CGContextRef currentContext = UIGraphicsGetCurrentContext();
    CGContextSetFillColorWithColor(currentContext, [imgColor CGColor]);
    CGContextFillRect(currentContext,CGRectMake(0.0f, 0.0f, imgSize.width, imgSize.height));
    
    UIImage *img = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    
    return img;
}

- (UIImage *)scaleImageToSize:(CGSize)newSize {
	
    UIGraphicsBeginImageContextWithOptions(newSize, NO, 0.0);
    [self drawInRect:CGRectMake(0, 0, newSize.width, newSize.height)];
    UIImage *newImage = UIGraphicsGetImageFromCurrentImageContext();
    UIGraphicsEndImageContext();
    return newImage;
}
@end

#pragma mark - NSDate

@implementation NSDate (BM)

- (NSString *)stringWithFormat:(NSString *)dateFormat
{
	NSDateFormatter *df = [[NSDateFormatter new] autorelease];
	[df setDateFormat:dateFormat];
	return [df stringFromDate:self];
}

@end

#pragma mark - UIFont

@implementation UIFont (BM)

+ (UIFont *)BMFontWithSize:(CGFloat)size
{
	return [UIFont fontWithName:@"Helvetica" size:size];
}

+ (UIFont *)BMBoldFontWithSize:(CGFloat)size
{
	return [UIFont fontWithName:@"Helvetica-Bold" size:size];
}

@end

#pragma mark - UIColor

@implementation UIColor (BM)

+ (UIColor *)BMDarkTextColor
{
	return [UIColor colorWithWhite:0.1f alpha:1.0f];
}

+ (UIColor *)BMOrangeColor
{
    return [UIColor colorWithRed:86/255.0 green:86/255.0 blue:86/255.0 alpha:1];
//	return [UIColor colorWithRed:218/255.0 green:132/255.0 blue:56/255.0 alpha:1];
}

+ (UIColor *)BMBlueColor
{
	return RGBA(0.0f, 63.0f, 120.0f, 1.0f);
}

+ (UIColor *)BMLayerColor
{
	return [UIColor colorWithRed:100.0/255.0 green:150.0/255.0 blue:1.0 alpha:0.8];
}

+ (UIColor *)BMLightBlueColor
{
	return [UIColor colorWithRed:160.0/255.0 green:200.0/255.0 blue:246.0 alpha:1.0];
}

+ (UIColor *)BMGrayColor
{
	return [UIColor colorWithRed:86/255.0 green:86/255.0 blue:86/255.0 alpha:1];
}
@end

#pragma mark - UILabel
@implementation UIAdditionLabel

@synthesize textVericalAlignment = _textVericalAlignment;

#pragma mark text drawing
- (CGRect)textRectForBounds:(CGRect)bounds limitedToNumberOfLines:(NSInteger)numberOfLines {
    
    CGRect rect = [super textRectForBounds:bounds limitedToNumberOfLines:numberOfLines];
    CGRect resultRect = rect;
    switch (self.textVericalAlignment) {
    
        case NSTextVerticalAlignmentTop:
            
            resultRect = CGRectOriginY(resultRect, bounds.origin.y);
            break;
            
        case NSTextVerticalAlignmentCenter:
            
            resultRect = CGRectOriginY(resultRect, bounds.origin.y + (bounds.size.height - rect.size.height) / 2);
            break;
            
        case NSTextVerticalAlignmentBottom:
            
            resultRect = CGRectOriginY(resultRect, bounds.origin.y + (bounds.size.height - rect.size.height)); 
            break;
    }
    return resultRect;
}

- (void)drawTextInRect:(CGRect)rect {
    
    CGRect textRect = [self textRectForBounds:rect limitedToNumberOfLines:self.numberOfLines];
    [super drawTextInRect:textRect];
}

#pragma mark Setters
- (void)setTextVericalAlignment:(NSTextVerticalAlignment)textVericalAlignment {
    _textVericalAlignment = textVericalAlignment;
    [self setNeedsDisplay];
}

#pragma mark Getters
- (NSTextVerticalAlignment)textVericalAlignment {
    return _textVericalAlignment;
}

@end

@interface UITabBarController (Rotate)

@end

@implementation UITabBarController (Rotate)

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
    return [self.selectedViewController shouldAutorotateToInterfaceOrientation:toInterfaceOrientation];
}

@end

@interface UINavigationController (Rotate)

@end

@implementation UINavigationController (Rotate)

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation {
    return [self.topViewController shouldAutorotateToInterfaceOrientation:toInterfaceOrientation];
}

@end

#pragma mark - CALayer

@implementation CALayer (BM)

- (UIImage *)screenshot
{
	CGSize size = self.frame.size;

	if (RETINA)
	{
		size = CGSizeMake(size.width * 2.0f, size.height * 2.0f);
	}

	UIGraphicsBeginImageContext(size);
	
	CGContextScaleCTM(UIGraphicsGetCurrentContext(), (1.0f + RETINA), (1.0f + RETINA));
	
	[self renderInContext:UIGraphicsGetCurrentContext()];
	UIImage *screenshot = UIGraphicsGetImageFromCurrentImageContext();
		
	UIGraphicsEndImageContext();
	
	return screenshot;
}

@end

@implementation UIView (Geometry)

- (CGFloat)originX {
    return self.frame.origin.x;
}

- (CGFloat)originY {
    return self.frame.origin.y;
}

- (CGFloat)width {
    return self.frame.size.width;
}

- (CGFloat)height {
    return self.frame.size.height;
}

- (CGFloat)getMidX {
    return CGRectGetMidX(self.frame);
}

- (CGFloat)getMidY {
    return CGRectGetMidY(self.frame);
}

- (CGFloat)getMaxX {
    return CGRectGetMaxX(self.frame);
}

- (CGFloat)getMaxY {
    return CGRectGetMaxY(self.frame);
}
- (void)removeAllSubviews{
    
    NSArray *arraySubViews = [self subviews];
    for (UIView *oneView in arraySubViews) {
        [oneView removeFromSuperview];
    }
    NSArray * sublayers = [self.layer sublayers];
    int num = [sublayers count];
    while (num >0) {
        [[sublayers objectAtIndex:num-1] removeFromSuperlayer];
        num --;
    }
}
@end

#pragma mark - UIPageControl

static NSUInteger maxPagesCount = 19;
static NSUInteger numberOfPagesLimited = 0;

@implementation UIPageControl (BM)

- (void)setNumberOfPagesLimited:(NSUInteger)theNumberOfPagesLimited
{
	numberOfPagesLimited = theNumberOfPagesLimited;
	self.numberOfPages = MIN(maxPagesCount, numberOfPagesLimited);
}

- (void)setCurrentPageLimited:(NSUInteger)theCurrentPageLimited
{
	NSUInteger leftMargin = self.numberOfPages / 2;
	NSUInteger rightMargin = numberOfPagesLimited - leftMargin;
	
	if (theCurrentPageLimited > leftMargin && theCurrentPageLimited < rightMargin)
	{
		self.currentPage = maxPagesCount / 2;
	}
	else if (theCurrentPageLimited <= leftMargin)
	{
		self.currentPage = theCurrentPageLimited;
	}
	else
	{
		self.currentPage = self.numberOfPages - (numberOfPagesLimited - theCurrentPageLimited);
	}
}

- (void)setCurrentPageM:(NSUInteger)theCurrentPage withMax:(NSUInteger)max{
    if ((max > 20) && (theCurrentPage >= 10)) {
        float delta = max - 20;
        if ((theCurrentPage - delta)<=10 && (theCurrentPage + 10)<max) {
            theCurrentPage = 10;
        }else {
            theCurrentPage = theCurrentPage - delta;
        }
    }
    self.currentPage = theCurrentPage;
}

@end

@implementation UIWebView (contentView)

- (UIScrollView *)webScrollView {
    
    if ([self respondsToSelector:@selector(scrollView)]) {
        
        return self.scrollView;
    }
    
    UIScrollView *webScrollView = nil;
    
	for (UIView *subview in self.subviews) {

		if ([subview.class isSubclassOfClass:UIScrollView.class]) {

			webScrollView = (UIScrollView *)subview;
            break;
		}
	}
	return webScrollView;
}

@end

@implementation  NSArray (BMArrays)

- (NSArray *)initWithLimit:(NSInteger) countElements{
    if(!countElements){
        
        return nil;
    }
    if (!self.count) {
        
        return nil;
    }
    if (countElements >= self.count) {
        
        return [[NSArray alloc] initWithArray:self];
    }
    NSMutableArray *localArray = [[NSMutableArray alloc] init];

    for (NSInteger elementNum = 0; elementNum < countElements; elementNum ++) {
        
        [localArray addObject:[self objectAtIndex:elementNum]];
    }
    return localArray;
}

@end

@implementation UISegmentedControl (setFonts)

- (void)setTitleTextAttributesAll:(NSDictionary *)attributes forState:(UIControlState)state{
    if ([self respondsToSelector:@selector(setTitleTextAttributes:forState:)]) {
        [self setTitleTextAttributes:attributes forState:state];
        return;
    }
}

@end

//konstantin
@implementation UITableView (SafeUpdates)

- (void)updatesWithBlock:(SafeUpdatesBlock)block {
    
    @try {
        
        [self beginUpdates];
        
        block();
    }
    @catch (NSException *exception) {
        
        NSLog(@"Oops!");
    }
    @finally {
        
        [self endUpdates];
    }
}

@end


@implementation Base64 : NSObject 
+(NSString *)encode:(NSData *)plainText {
    int encodedLength = (4 * (([plainText length] / 3) + (1 - (3 - ([plainText length] % 3)) / 3))) + 1;
    unsigned char *outputBuffer = malloc(encodedLength);
    unsigned char *inputBuffer = (unsigned char *)[plainText bytes];
    
    NSInteger i;
    NSInteger j = 0;
    int remain;
    
    for(i = 0; i < [plainText length]; i += 3) {
        remain = [plainText length] - i;
        
        outputBuffer[j++] = alphabet[(inputBuffer[i] & 0xFC) >> 2];
        outputBuffer[j++] = alphabet[((inputBuffer[i] & 0x03) << 4) |
                                     ((remain > 1) ? ((inputBuffer[i + 1] & 0xF0) >> 4): 0)];
        
        if(remain > 1)
            outputBuffer[j++] = alphabet[((inputBuffer[i + 1] & 0x0F) << 2)
                                         | ((remain > 2) ? ((inputBuffer[i + 2] & 0xC0) >> 6) : 0)];
        else
            outputBuffer[j++] = '=';
        
        if(remain > 2)
            outputBuffer[j++] = alphabet[inputBuffer[i + 2] & 0x3F];
        else
            outputBuffer[j++] = '=';
    }
    
    outputBuffer[j] = 0;
    
    NSString *result = [NSString stringWithCString:outputBuffer length:strlen(outputBuffer)];
    free(outputBuffer);
    
    return result;
}
@end
