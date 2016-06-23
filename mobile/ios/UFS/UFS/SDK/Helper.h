//
//  Helper.h
//  Copyright 2012 iD EAST. All rights reserved.
//

#import <Foundation/Foundation.h>

#pragma mark NSText additions

typedef NS_ENUM(NSInteger, NSTextVerticalAlignment) {
    NSTextVerticalAlignmentCenter = 0,
    NSTextVerticalAlignmentTop = 1,
    NSTextVerticalAlignmentBottom = 2,
};
#if NS_BLOCKS_AVAILABLE
typedef void (^AFHTTPRequestSuccessBlock)(AFHTTPRequestOperation *operation, id responseObject);
typedef void (^AFHTTPRequestFailureBlock)(AFHTTPRequestOperation *operation, NSError *error);
typedef BOOL (^AFURLConnectionOperationResponseBlock)(NSURLConnection *connection, NSHTTPURLResponse *response);
#endif
static NSString *const kUserInfoKey = @"UserInfoKey";
static NSString *const kLastStatisticSendDateKey = @"LastStatisticSendDateKey";
#define C255(color) (color / 255.0f)

@interface Helper : NSObject

+ (void)printAvailableFonts;

// Values
+ (NSString *)getStatisticPath;
+ (NSInteger)getTagForBaseObjects:(NSArray*)objects;
+ (NSString *)spacedIntegerValue:(NSNumber *)number;
+ (NSString *)value:(NSNumber *)value plusNeeded:(BOOL)plus;
+ (NSString *)getTime:(NSDate *)date;
+ (NSString *)getDate:(NSDate *)date;
+ (NSString *)getDate:(NSDate *)date withFormat:(NSString *)format;
+ (NSString *)getDateAnotherFormat:(NSDate *)date;
+ (NSString *)getDateDdMmmmY:(NSDate *)date;
+ (NSString *)getDateTodayFormat:(NSDate *)dateOld;
+ (NSString *)getDateBMFormat:(NSDate *)dateOld;
+ (UIImage *)getImageWithColor:(UIColor *)color withSize:(CGSize)size;
+ (NSString *) removeAllButDigits:(NSString *)nativeString;
+ (void) hideTabBar:(UITabBarController *) tabbarcontroller;
+ (void) showTabBar:(UITabBarController *) tabbarcontroller;

+ (id)getObjectWithTag:(NSInteger)tagNum withClassname:(NSString*)className withParentView:(UIView*)parentView;
//Animations
//+ (void)animationRippleEffect:(UIView *)view;
@end


#pragma mark - Расширения стандартных классов


@interface NSString (BM)

- (NSDate *)dateFromFormat:(NSString *)dateFormat;
- (NSInteger *)count;
- (NSString *)replaceHTMLTags;

@end

@interface NSDictionary (BM)

- (NSInteger *)lenght;
@end

@interface NSNumber (BM)

- (NSInteger *)count;
- (NSInteger *)lenght;
@end

@interface NSDate (BM)

- (NSString *)stringWithFormat:(NSString *)dateFormat;

@end


@interface UIFont (BM)

+ (UIFont *)BMFontWithSize:(CGFloat)size;
+ (UIFont *)BMBoldFontWithSize:(CGFloat)size;

@end
@interface UIImage (Helper)

+ (UIImage *)getGradientImageWithStartColor:(UIColor*)startColor withEndColor:(UIColor*)endColor withSize:(CGSize)size;
+ (UIImage *)getGradientImageWithStartColor:(UIColor*)startColor withMidleColor:(UIColor*)midleColor withMidle2Color:(UIColor*)midle2Color withEndColor:(UIColor*)endColor withSize:(CGSize)size;
+ (UIImage *)imageWithColor:(UIColor *)imgColor size:(CGSize)imgSize;
- (UIImage *)scaleImageToSize:(CGSize)newSize;
@end
@interface UIImageView (Helper)

- (void)setImageWithNSURL:(NSURL *)url;

- (void)setImageWithNSURL:(NSURL *)url
         placeholderImage:(UIImage *)placeholderImage;

- (void)setImageWithNSURLRequest:(NSURLRequest *)urlRequest
                placeholderImage:(UIImage *)placeholderImage
                         success:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, UIImage *image))success
                         failure:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, NSError *error))failure;

- (void)setImageWithNSURLString:(NSString *)urlString
               placeholderImage:(UIImage *)placeholderImage
                        success:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, UIImage *image))success
                        failure:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, NSError *error))failure;

- (void)cancelImageRequestOperation;

@end


@interface UIColor (BM)

+ (UIColor *)BMDarkTextColor;
+ (UIColor *)BMOrangeColor;
+ (UIColor *)BMBlueColor;
+ (UIColor *)BMGrayColor;
+ (UIColor *)BMLayerColor;
+ (UIColor *)BMLightBlueColor;

@end

// Данный класс создан с целью добавления в него св-в и методов, которые отсутствуют у стандартного лейбла. В идеальном случае, этот класс должен использоваться вместо UILabel
@interface UIAdditionLabel : UILabel

@property (nonatomic, assign) NSTextVerticalAlignment textVericalAlignment;

@end


// Для экономии места в коде, чтобы не писать большие выражения типа CGRectGetMaxX(aView.frame) завел эту категорию
@interface UIView (Geometry)

// origin
- (CGFloat)originX;
- (CGFloat)originY;

// size
- (CGFloat)width;
- (CGFloat)height;

// середина
- (CGFloat)getMidX;
- (CGFloat)getMidY;

// нижний край
- (CGFloat)getMaxX;
- (CGFloat)getMaxY;

//очистим всё
- (void)removeAllSubviews;
@end

@interface UIPageControl (BM)

- (void)setNumberOfPagesLimited:(NSUInteger)theNumberOfPagesLimited;
- (void)setCurrentPageLimited:(NSUInteger)theCurrentPageLimited;
- (void)setCurrentPageM:(NSUInteger)theCurrentPage withMax:(NSUInteger)max;
@end

@interface UIWebView (contentView)

- (UIScrollView *)webScrollView;

@end

@interface NSArray (BMArrays)

- (NSArray *)initWithLimit:(NSInteger) countElements;
@end

@interface UISegmentedControl (setFonts)

- (void)setTitleTextAttributesAll:(NSDictionary *)attributes forState:(UIControlState)state;

@end


typedef void (^SafeUpdatesBlock)();

@interface UITableView (SafeUpdates)

- (void)updatesWithBlock:(SafeUpdatesBlock)block;

@end

#pragma mark CoreGraphics additions

// Назначить rect новый origin.x
CG_INLINE CGRect CGRectOriginX(CGRect rect, CGFloat originX);

CG_INLINE CGRect
CGRectOriginX(CGRect rect, CGFloat originX)
{
    rect.origin.x = originX;
    return rect;
}

// Назначить rect новый origin.y
CG_INLINE CGRect CGRectOriginY(CGRect rect, CGFloat originY);

CG_INLINE CGRect
CGRectOriginY(CGRect rect, CGFloat originY)
{
    rect.origin.y = originY;
    return rect;
}

// Назначить rect новый origin
CG_INLINE CGRect CGRectOrigin(CGRect rect, CGFloat originX, CGFloat originY);

CG_INLINE CGRect
CGRectOrigin(CGRect rect, CGFloat originX, CGFloat originY)
{
    rect.origin.x = originX;
    rect.origin.y = originY;
    return rect;
}

// Назначить rect новый size
CG_INLINE CGRect CGRectSize(CGRect rect, CGFloat width, CGFloat height);

CG_INLINE CGRect
CGRectSize(CGRect rect, CGFloat width, CGFloat height)
{
    rect.size.width = width;
    rect.size.height = height;
    return rect;
}

// Назначить rect новый size.width
CG_INLINE CGRect CGRectSizeWidth(CGRect rect, CGFloat width);

CG_INLINE CGRect
CGRectSizeWidth(CGRect rect, CGFloat width)
{
    rect.size.width = width;
    return rect;
}

// Назначить rect новый size.height
CG_INLINE CGRect CGRectSizeHeight(CGRect rect, CGFloat height);

CG_INLINE CGRect
CGRectSizeHeight(CGRect rect, CGFloat height)
{
    rect.size.height = height;
    return rect;
}

// Увеличить size на dw по ширине и на dh по высоте
CG_INLINE CGSize CGSizeIncrease(CGSize size, CGFloat dw, CGFloat dh);

CG_INLINE CGSize
CGSizeIncrease(CGSize size, CGFloat dw, CGFloat dh)
{
    size.width += dw;
    size.height += dh;
    return size;
}
#pragma -mark Base64 interface

@interface Base64 : NSObject

+(NSString *)encode:(NSData *)plainText;

@end
