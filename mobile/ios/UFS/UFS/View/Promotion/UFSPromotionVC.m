//
//  UFSPromotionVC.m
//  UFS
//
//  Created by Sergei Tomilov on 5/12/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import "AnalyticsCounter.h"
#import "UFSPromotionVC.h"
#import "AnimatedGIFImageSerialization.h"

@implementation UFSPromotionVC

NSString * const redColor = @"#cc0000";
NSString * const greenColor = @"#006e2e";


- (id)initWithProductsEx:(NSMutableArray*)products
                  timers:(NSMutableDictionary*)timers
                    title:(NSString *)title
              titleParent:(NSString *)titleParent
                  regName:(NSString*)regName regPhone:(NSString*)regPhone regEmail:(NSString*)regEmail
             regBrokerage:(BOOL)regBrokerage regYield:(BOOL)regYield
{
	self = [super init];
	if (self) {
        
        self.titleParent = titleParent;
        self.titleNavBar = title;
        self.timers = timers;
        self.products = products;
        
        self.regName = regName;
        self.regPhone = regPhone;
        self.regEmail = regEmail;
        self.regBrokerage = regBrokerage;
        self.regYield = regYield;
        
        self.accepted = NO;
        
	}
	return self;
}

- (id)initWithProducts:(NSMutableArray*)products
                 title:(NSString *)title
           titleParent:(NSString *)titleParent {
    
    return [self initWithProductsEx:products timers:nil title:title titleParent:titleParent regName:@"" regPhone:@"" regEmail:@"" regBrokerage:false regYield:false];
}

- (id)initWithPromotion:(NSDictionary*)promotion titleParent:(NSString *)titleParent {
    
    NSMutableArray* products = [[NSMutableArray alloc] init];
    NSArray *arr = ((NSArray*)[promotion objectForKey:@"products"]);
     
    if (arr.count>0) {
        for (int i=0; i<arr.count;i++) {
     
            NSMutableDictionary *product = [(NSDictionary*)[arr objectAtIndex:i] mutableCopy];
            
            NSNumber *countdown = ((NSNumber*)[product objectForKey:@"countdown"]);
            if ([countdown isKindOfClass:[NSNumber class]] && countdown!=nil) {
                
                int secondsLeft = [countdown integerValue];
                if (secondsLeft>0) {
                    NSDate *expired = [[NSDate date] dateByAddingTimeInterval:secondsLeft];
                    [product setObject:expired forKey:@"expired"];
                }
            }
            
            [products addObject:product];
        }
    }
    
    NSString* title = ((NSString*)[promotion objectForKey:@"title"]);
    
    return [self initWithProductsEx:products timers:nil title:title titleParent:titleParent regName:@"" regPhone:@"" regEmail:@"" regBrokerage:false regYield:false];
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(afterImageLoad:) name:kNotificationImageLoaded object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(afterImageLoad:) name:kNotificationImageLoadFailed object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(afterPromotion:) name:kNotificationPromotion object:nil];

    
    [self.view setBackgroundColor:RGBA(238, 241, 243, 1.0f)];
    
    UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
    UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
    [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
    [backbutton addTarget:self action:@selector(backButtonTouch:) forControlEvents:UIControlEventTouchUpInside];
    [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
    self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
    [backbutton release];
    
    self.productNamePicker = [[UIPickerActionSheet alloc] initForView:self.view];
    self.productNamePicker.delegate = self;
    
    productImage.clipsToBounds = YES;
    productImage.contentMode = UIViewContentModeScaleAspectFit;
    
    for (int i=0; i<self.products.count;i++) {
        
            NSMutableDictionary *product = ((NSMutableDictionary *)[self.products objectAtIndex:i]);
            if (product) {
            
                NSString *promotionID = ((NSString*)[product objectForKey:@"promotionID"]);
                NSNumber *countdown = ((NSNumber*)[product objectForKey:@"countdown"]);
                
                NSTimer *timer = nil;
                
                if (self.timers!=nil) {
                    
                    timer = (NSTimer*)[self.timers objectForKey:promotionID];
                    if (timer!=nil) {
                        [timer invalidate];
                        [self.timers removeObjectForKey:promotionID];
                    }
                }
            
                if ([countdown isKindOfClass:[NSNumber class]] && countdown!=nil && self.timers!=nil) {
                
                    int secondsLeft = [countdown integerValue];
                    if (secondsLeft>0) {
                        NSAutoreleasePool *pool = [[NSAutoreleasePool alloc] init];
                        timer = [NSTimer scheduledTimerWithTimeInterval:1.0f target:self selector:@selector(updateTimer:) userInfo:product repeats:YES];
                        [self.timers setObject:timer forKey:promotionID];
                        [pool release];
                    } else {
                   //
                    }
                }
            }
    }
    
    [scrollView addConstraint:[NSLayoutConstraint constraintWithItem:productAccept attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:productDesc attribute:NSLayoutAttributeBottom multiplier:1.0 constant:productCountdown.frame.size.height*3]];
    
    self.productChecked = NO;
    self.productIndex = -1;
    [self setFirstProduct];
    
    if (self.products.count==1) {

        productNameButton.hidden = YES;
        productName.hidden = YES;
        productImageViewTop.constant = productImageViewTop.constant - (productName.frame.origin.y + productName.frame.size.height);
    }
    
    
    
    self.titleText = _titleNavBar;
}

- (void)viewDidLayoutSubviews {
    
    scrollView.contentSize = CGSizeMake(self.view.frame.size.width,productReject.frame.origin.y+productReject.frame.size.height + 100);

}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
}

- (void)backButtonTouch: (UIButton *)sender
{
    if (self.accepted) {
        NSArray *viewControllers = [self.navigationController viewControllers];
        if (viewControllers.count>1) {
            UIViewController *controller = [viewControllers objectAtIndex:1];
            [self.navigationController popToViewController:controller animated:YES];
        } else {
            [self.navigationController popViewControllerAnimated:YES];
        }
    } else {
        [self.navigationController popViewControllerAnimated:YES];
    }
}

- (void)dealloc {
    [productNameButton release];
    [productName release];
    [productImageIndicator release];
    [productImage release];
    [productDesc release];
    [productAccept release];
    [productReject release];
    [productCheckCaption release];
    [productCountdown release];
    [productImageView release];
    [scrollView release];
    [productImageViewTop release];
    [super dealloc];
}
- (void)viewDidUnload {
    [productNameButton release];
    productNameButton = nil;
    [productName release];
    productName = nil;
    [productImageIndicator release];
    productImageIndicator = nil;
    [productImage release];
    productImage = nil;
    [productDesc release];
    productDesc = nil;
    [super viewDidUnload];
}

- (UIColor *)colorFromHexString:(NSString *)hexString
{
    unsigned rgbValue = 0;
    NSScanner *scanner = [NSScanner scannerWithString:hexString];
    [scanner setScanLocation:1]; // bypass '#' character
    [scanner scanHexInt:&rgbValue];
    return [UIColor colorWithRed:((rgbValue & 0xFF0000) >> 16)/255.0 green:((rgbValue & 0xFF00) >> 8)/255.0 blue:(rgbValue & 0xFF)/255.0 alpha:1.0];
}

- (void) updateProductCheckTimer
{
    UIImage *normal = [UIImage imageNamed:@"checkbox"];
    UIImage *selected = [UIImage imageNamed:@"checkbox-checked"];
    
    if (!productCheck.enabled) {
        productCheck.enabled = YES;
        if (self.productChecked) {
            [productCheck setImage:selected forState:UIControlStateDisabled];
        } else {
            [productCheck setImage:normal forState:UIControlStateDisabled];
        }
        productCheck.enabled = NO;
    }
}

- (void) updateProductChecked:(BOOL)productChecked
{
    self.productChecked = productChecked;
    [self performSelectorOnMainThread:@selector(updateProductCheckTimer) withObject:nil waitUntilDone:YES];
    //[self updateProductCheckTimer];
}

- (NSMutableDictionary *)getCurrentProduct
{
    NSMutableDictionary *product = nil;
    
    if (self.productIndex>=0 && self.productIndex<self.products.count) {
        product = ((NSMutableDictionary *)[self.products objectAtIndex:self.productIndex]);
    }
    
    return product;
}


- (void)setStatusForAll:(NSMutableDictionary *)product didEnabled:(BOOL)enabled
{
   
    NSString *status = ((NSString*)[product objectForKey:@"status"]);
    BOOL newState = enabled;
    NSNumber *countdown = ((NSNumber*)[product objectForKey:@"countdown"]);
    if ([countdown isKindOfClass:[NSNumber class]] && countdown!=nil) {
        int secondsLeft = [countdown integerValue];
        newState = newState && secondsLeft>0;
    }
    
    productDesc.enabled = newState;
    productCheck.enabled = newState;
    [self updateProductChecked:self.productChecked];
    productCheckCaption.enabled = newState;
    
    BOOL flag = newState;
    if (!productCheck.hidden) {
        flag = flag && self.productChecked;
    }
    
    productAccept.enabled = flag;
    if ([status isEqualToString:@"accepted"]) {
        
        UIImage *image = [UIImage imageNamed:@"btn_green"];
        [productAccept setBackgroundImage:image forState:UIControlStateDisabled];
        [productAccept setTitleColor:[UIColor lightGrayColor] forState:(UIControlStateDisabled)];
        
    } else {
        
        UIImage *image = [UIImage imageNamed:@"btn_default"];
        [productAccept setBackgroundImage:image forState:UIControlStateDisabled];
        [productAccept setTitleColor:[UIColor blackColor] forState:(UIControlStateNormal)];
        [productAccept setTitleColor:[UIColor darkGrayColor] forState:(UIControlStateDisabled)];
    }
    
    productReject.enabled = flag;
    if ([status isEqualToString:@"rejected"]) {
        
        UIImage *image = [UIImage imageNamed:@"btn_red"];
        [productReject setBackgroundImage:image forState:UIControlStateDisabled];
        [productReject setTitleColor:[UIColor lightGrayColor] forState:(UIControlStateDisabled)];
        
    } else {
        
        UIImage *image = [UIImage imageNamed:@"btn_default"];
        [productReject setBackgroundImage:image forState:UIControlStateDisabled];
        [productReject setTitleColor:[UIColor blackColor] forState:(UIControlStateNormal)];
        [productReject setTitleColor:[UIColor darkGrayColor] forState:(UIControlStateDisabled)];
    }
    
    productAccept.hidden = !self.accepted && ![status isEqualToString:@"started"];
    if (productAccept.hidden) {
        
        productDesc.text = @"Извините, в акции можно участвовать только один раз.";
        
        productCheck.hidden = true;
        productCheckCaption.hidden = true;
        productCountdown.hidden = true;
        productReject.hidden = true;
    }
}

-(void)updateCountdown:(NSMutableDictionary *)product didOverall:(NSInteger)overall
{
    if (!productCountdown.hidden) {
        NSString *status = ((NSString*)[product objectForKey:@"status"]);
        BOOL show = !([status isEqualToString:@"accepted"] || [status isEqualToString:@"rejected"]);
        NSString *time = nil;
        if (overall>=0) {
            int hours, minutes, seconds;
            hours = overall / 3600;
            minutes = (overall % 3600) / 60;
            seconds = (overall %3600) % 60;
            time = [NSString stringWithFormat:@"%02d:%02d:%02d", hours, minutes, seconds];
        }
        productCountdown.text = (show)?time:nil;
        if (overall>0) {
            [productCountdown setTextColor:[self colorFromHexString:greenColor]];
        } else {
            [productCountdown setTextColor:[self colorFromHexString:redColor]];
        }
    }
}

- (void)cancelTimer:(NSMutableDictionary *)product {
    
    if (self.timers!=nil) {
        NSString *promotionID = ((NSString*)[product objectForKey:@"promotionID"]);
        NSTimer *timer = (NSTimer*)[self.timers objectForKey:promotionID];
        if (timer!=nil) {
            [timer invalidate];
            [self.timers removeObjectForKey:promotionID];
        }
    }
}

- (void)updateTimer:(NSTimer *)timer {
    
    
    NSMutableDictionary *product = ((NSMutableDictionary *)timer.userInfo);
    
    NSDate *expired = ((NSDate*)[product objectForKey:@"expired"]);
    NSNumber *countdown = ((NSNumber*)[product objectForKey:@"countdown"]);
    if (expired!=nil && [countdown isKindOfClass:[NSNumber class]] && countdown!=nil) {
        
        NSTimeInterval left = [[NSDate date] timeIntervalSinceDate:expired];
        int secondsLeft = lroundf(left);
        
        if (secondsLeft<0) {
            
            countdown = [NSNumber numberWithInt:-secondsLeft];
            [product setObject:countdown forKey:@"countdown"];
            
            if (product==[self getCurrentProduct]) {
                [self updateCountdown:product didOverall:-secondsLeft];
            }
            
        } else {
            
            [timer invalidate];
            countdown = [NSNumber numberWithInt:0];
            [product setObject:countdown forKey:@"countdown"];
            [self updateCountdown:product didOverall:0];
            [self setStatusForAll:product didEnabled:NO];
        }
    }
}

-(void)changeProduct: (NSMutableDictionary *)product
{
    
    NSString *status = ((NSString*)[product objectForKey:@"status"]);
    BOOL enabled = [status isEqualToString:@"started"];
    
    productName.text = ((NSString*)[product objectForKey:@"name"]);
    
    [productImage setImage:nil];
    [productImageIndicator stopAnimating];
    productImageIndicator.hidden = true;

    productDesc.hidden = YES;
    productDesc.text = nil;
    
    productCheck.hidden = YES;
    productCheckCaption.hidden = YES;
    [self updateProductChecked:[status isEqualToString:@"accepted"] || [status isEqualToString:@"rejected"]];
    
    NSNumber *countdown = ((NSNumber*)[product objectForKey:@"countdown"]);
    productCountdown.hidden = ![countdown isKindOfClass:[NSNumber class]];
    [self updateCountdown:product didOverall:0];
    
    self.acceptOrReject = nil;
    
    NSString *description = ((NSString*)[product objectForKey:@"description"]);
    if ([description isKindOfClass:[NSString class]] && description.length>0) {
        productDesc.hidden = NO;
        productDesc.text = description;
    }
    
    NSString *agreement = ((NSString*)[product objectForKey:@"agreement"]);
    if ([agreement isKindOfClass:[NSString class]] && agreement.length>0) {
        productCheck.hidden = NO;
        productCheckCaption.hidden = NO;
    }
    
    if ([UFSLoader reachable]){
        
        NSString *imageURL = ((NSString*)[product objectForKey:@"imageURL"]);
        if (imageURL.length>0) {
            
            productImageIndicator.hidden = false;
            [productImageIndicator startAnimating];
            
            NSString *imagePath = [imageURL stringByReplacingOccurrencesOfString:@"files" withString:@"image"];
            
            if ([FileSystem pathExisted:imagePath]) {
                
                [productImageIndicator stopAnimating];
                productImageIndicator.hidden = true;
                [productImage setImage:[FileSystem imageWithPath:imagePath]];
                
            } else {
                
                [UFSLoader getImage:imageURL AndName:imagePath];
            }
        }
    }
    [self setStatusForAll:product didEnabled:enabled];
    [productDesc sizeToFit];
    [self.view needsUpdateConstraints];
}

- (void)setProduct: (NSInteger)index
{
    if (self.productIndex!=index && index>=0 && index<self.products.count) {
        
        NSMutableDictionary *product = ((NSMutableDictionary *)[self.products objectAtIndex:index]);
        if (product) {
            
            self.productIndex = index;
            [self changeProduct:product];
        }
    }
}

- (void)setFirstProduct
{
    [self setProduct:0];
}

- (IBAction)productNameButtonTouch:(id)sender {
    
    if (self.productIndex!=-1) {
        
        NSMutableArray *values = [[NSMutableArray alloc] init];
        
        for (int i=0; i<self.products.count;i++) {
        
            NSMutableDictionary *product = ((NSMutableDictionary *)[self.products objectAtIndex:i]);
            if (product) {
                NSString *name = ((NSString*)[product objectForKey:@"name"]);
                [values addObject:name];
            }
        }
        [self.productNamePicker show:values rowIndex:self.productIndex];
    
        productNameButton.selected = true;
    }
}

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (alertView==self.alertViewAgreement) {
        
        if (buttonIndex==0) {
            productAccept.enabled = NO;
            productReject.enabled = NO;
            [self updateProductChecked:NO];
            productCheck.selected = NO;
        } else {
            productAccept.enabled = YES;
            productReject.enabled = YES;
        }
    }
}

- (IBAction)productCheckTouch:(id)sender {
    
    [self updateProductChecked:!self.productChecked];
    
    productCheck.selected = !productCheck.selected;
    
    if (self.productChecked) {
        
        NSMutableDictionary *product = [self getCurrentProduct];
        if (product!=nil) {
        
            NSString *agreement = ((NSString*)[product objectForKey:@"agreement"]);
            
            self.alertViewAgreement = [[UIAlertView alloc] initWithTitle:productCheckCaption.text
                                                                 message:agreement
                                                                delegate:self
                                                       cancelButtonTitle:nil
                                                       otherButtonTitles:@"нет",@"ДА", nil];
            [self.alertViewAgreement show];
        }
    } else {
        productAccept.enabled = NO;
        productReject.enabled = NO;
    }
}


- (void)pickerActionSheetDidCancel:(UIPickerActionSheet*)aPickerActionSheet
{
    productNameButton.selected = false;
}

- (void)pickerActionSheet:(UIPickerActionSheet*)aPickerActionSheet didSelectItem:(id)aItem didSelectIndex:(NSInteger)aIndex
{
    productNameButton.selected = false;
    [self setProduct:aIndex];
}

-(void)afterImageLoad:(NSNotification *)notify
{
    
    if ([notify.object isKindOfClass:[NSString class]]) {
        
        if ([notify.name isEqualToString:kNotificationImageLoaded]) {
           
            NSString *imagePath = (NSString *)notify.object;
            [productImage setImage:[FileSystem imageWithPath:imagePath]];
            
        } else {
            
           [productImage setImage:nil];
        }
        [productImageIndicator stopAnimating];
        productImageIndicator.hidden = true;
    }
}

- (void) requestData:(BOOL)acceptOrReject
{
    NSMutableDictionary *product = [self getCurrentProduct];
    if (product!=nil) {
        
        NSString *promotionID = ((NSString*)[product objectForKey:@"promotionID"]);
        if ([UFSLoader reachable]) {
            
            productName.enabled = NO;
            productNameButton.enabled = NO;
            [self setStatusForAll:product didEnabled:NO];
            
            self.acceptOrReject = [NSNumber numberWithInt:(acceptOrReject)?1:0];
            NSString *accepted = (acceptOrReject)?@"true":@"false";
            
            [UFSLoader requestPostAuth:@"" andWidth:@""];
            [UFSLoader requestPostPromotion:promotionID
                                   accepted:accepted
                                       name:self.regName
                                      phone:self.regPhone
                                      email:self.regEmail
                                  brokerage:(self.regBrokerage)?@"true":@"false"
                                      yield:(self.regYield)?@"true":@"false"];
        }
    }
}

- (IBAction)productAcceptTouch:(id)sender
{
    [self requestData:YES];
}

- (IBAction)productRejectTouch:(id)sender
{
    [self requestData:NO];
}

-(void)syncAfterPromotion:(NSNotification*)notify {
    
    productName.enabled = YES;
    productNameButton.enabled = YES;
    
    if ([notify.object isKindOfClass:[NSDictionary class]]){
        
        NSDictionary *obj = ((NSDictionary *)notify.object);
        
        NSDictionary *result = ((NSDictionary*)[obj objectForKey:@"result"]);
        
        NSString *status = ((NSString*)[result objectForKey:@"status"]);
        
        NSMutableDictionary *product = [self getCurrentProduct];
        if (product!=nil) {
            
            [AnalyticsCounter eventScreen:self.titleParent category:self.titleNavBar action:status value:((NSString*)[product objectForKey:@"name"])];
            
            [product setObject:status forKey:@"status"];
            [self cancelTimer:product];
            NSNumber *countdown = [NSNumber numberWithInt:0];
            [product setObject:countdown forKey:@"countdown"];
            [self updateCountdown:product didOverall:0];
            self.accepted = [status isEqualToString:@"accepted"];
            [self setStatusForAll:product didEnabled:NO];
            
        }
        
        NSString *publisher = ((NSString*)[result objectForKey:@"publisher"]);
        if ([publisher isKindOfClass:[NSString class]]) {
            
            publisher = [publisher stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
            if (publisher.length>0) {
                
                //[DistimoSDK logBannerClickWithPublisher:publisher];
            }
        }
    }
}

-(void) afterPromotion: (NSNotification *) notify
{
    [self performSelectorOnMainThread:@selector(syncAfterPromotion:) withObject:notify waitUntilDone:NO];
}

@end
