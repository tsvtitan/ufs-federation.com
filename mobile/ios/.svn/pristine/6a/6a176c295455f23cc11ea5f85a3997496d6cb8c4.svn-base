//
//  UFSRootVC.m
//  UFS
//
//  Created by mihail on 10.07.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSRootVC.h"

@interface UFSRootVC ()

@end

@implementation UFSRootVC

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}
-(void)dealloc
{
//    [_titleText release];

//    [navbarScroll removeFromSuperview];
   [super dealloc];
}
- (void)viewDidLoad
{
    [super viewDidLoad];
    [self.navigationController.navigationBar setTintColor:[UIColor colorWithRed:0.0f/255.0f green:71.0f/255.0f blue:130.0f/255.0f alpha:1.0f]];
    [self.navigationController.navigationBar setBackgroundImage:[UIImage imageNamed:@"navbar"] forBarMetrics:UIBarMetricsDefault];

    [self.navigationController.navigationBar setTitleTextAttributes:[NSDictionary dictionaryWithObjectsAndKeys:[UIColor colorWithRed:255.0/255.0 green:255.0/255.0 blue:255.0/255.0 alpha:1.0],
                                                                     UITextAttributeTextColor,
                                                                     [UIColor colorWithRed:0.0 green:0.0 blue:0.0 alpha:1.0],
                                                                     UITextAttributeTextShadowColor,
                                                                     [NSValue valueWithUIOffset:UIOffsetMake(0, 0)],
                                                                     UITextAttributeTextShadowOffset,
                                                                     [UIFont fontWithName:@"Arial-BoldItalicMT" size:18.0],
                                                                     UITextAttributeFont,
                                                                     nil]];
        self.titleText = @"";
    [self createNavBarScroll];
}
-(void)viewWillAppear:(BOOL)animated
{
    [super viewWillAppear:animated];
    
    
}
-(void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
//    if (!navBarView)
//    {
//        [self createNavBarScroll];
//    }
}
-(void)viewWillDisappear:(BOOL)animated
{
    [super viewWillDisappear:animated];
//    [navBarView removeFromSuperview];
//    navBarView = nil;
}
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
-(void)setTitleText:(NSString *)titleText
{
    [self.navigationController.navigationBar setAutoresizesSubviews:NO];
    titleText = [titleText stringByReplacingOccurrencesOfString:@"\n" withString:@" "];
    
    titleText = [NSString stringWithFormat:@" %@ ",titleText];
//    if ([self.titleText isEqualToString:titleText])
//    {
//        return;
//    }
    _titleText = [titleText copy];
    
   
    [navBarView.layer removeAllAnimations];
    if (isScrollAnimation)
    {
        
        contentOffsetPoint = [navbarScroll contentOffset];
        [navBarView.layer removeAllAnimations];
        for (UIView *view in navBarView.subviews)
        {
            [view.layer removeAllAnimations];
        }
        for (UIView *view in navbarScroll.subviews)
        {
            [view.layer removeAllAnimations];
        }
        [navbarScroll.layer removeAllAnimations];
//        [navBarView removeFromSuperview];
//
//        [self createNavBarScroll];
        [navBarView layoutSubviews];
//        [navbarScroll removeFromSuperview];
//        navbarScroll  = [[UIScrollView alloc] initWithFrame:CGRectZero];
//        [navBarView addSubview:navbarScroll];
//        [navbarScroll release];
//        navBarLable = [[UILabel alloc] initWithFrame:CGRectZero];
//        [navbarScroll addSubview:navBarLable];
//        [navBarLable release];
        isScrollAnimation = FALSE;
    }
    else
    {
        contentOffsetPoint = CGPointMake(0, 0);
        contentOffsetPointAfter = CGPointMake(0, 0);
    }
    CATransition *animation = [CATransition animation];
    [animation setDuration:0.3];
    //animation.fromValue = [NSValue valueWithCGPoint:startPoint];
    //animation.toValue = [NSValue valueWithCGPoint:CGPointMake(200, 200)];
    [animation setFillMode:kCAFillModeForwards];
    [animation setRemovedOnCompletion:NO];
    [animation setTimingFunction:[CAMediaTimingFunction functionWithName:kCAMediaTimingFunctionEaseOut]];
//    [animation setType:@"rippleEffect"];
     [animation setType:kCATransitionFade];
    rightShadow.frame = CGRectMake(self.view.width-(44.0f+7.0f), 0, 7.0f, 40.0f);
    
    float leftOffset=0.0f, rightOffset=0.0f;
    if ([self.navigationItem.leftBarButtonItem.customView isKindOfClass:[UIButton class]])
    {
        leftOffset += self.navigationItem.leftBarButtonItem.customView.width;
       
        leftOffset = 44.0f;
        
    }
    else
        [leftShadow setImage:[UIImage imageNamed:@""]];
    
    if (self.navigationItem.rightBarButtonItem.customView)
    {
         
        rightOffset += self.navigationItem.rightBarButtonItem.customView.width;
      
        rightOffset = 44.0f;
    }
    else
        [rightShadow setImage:[UIImage imageNamed:@""]];

   
   
    self.navigationItem.titleView.frame = CGRectMake(leftOffset, 0, self.view.width-(leftOffset+rightOffset), self.navigationController.navigationBar.height);
    float widthForCaption = [self.titleText sizeWithFont:[UIFont fontWithName:@"Arial-BoldItalicMT" size:18] constrainedToSize:CGSizeMake(MAXFLOAT, self.navigationController.navigationBar.height)].width;
   
    [navBarView setFrame:CGRectMake(leftOffset, 0, self.view.width-(leftOffset+rightOffset+25.0f), self.navigationController.navigationBar.height)];
    
//    -(leftOffset+rightOffset)
    [navbarScroll setFrame:CGRectMake(0, 0, self.navigationItem.titleView.width, self.navigationController.navigationBar.height)];
//    [navBarView setBackgroundColor:[UIColor purpleColor]];
//    [navbarScroll setBackgroundColor:[UIColor greenColor]];
//    navBarLable.backgroundColor = [UIColor whiteColor];
//    navBarLable
    [navbarScroll setContentSize:CGSizeMake(widthForCaption, self.navigationController.navigationBar.height)];
    [navBarView.layer addAnimation:animation forKey:nil];
    
    leftShadow.frame = CGRectMake(0.0f, 0, 7.0f, 40.0f);
    
    rightShadow.frame = CGRectMake(navBarView.width-(7.0f), 0, 7.0f, 40.0f);
 
    
    if (widthForCaption<=navbarScroll.width)
    {
        navBarLable.frame = CGRectMake(0, 0, navbarScroll.width, self.navigationController.navigationBar.height);
        if (!UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
            [leftShadow setImage:[UIImage imageNamed:@"navbar_separator_left"]];
        else
            [leftShadow setImage:[UIImage imageNamed:@""]];
       

        
        if (rightOffset>16)
        {
            if (!UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
                [rightShadow setImage:[UIImage imageNamed:@"navbar_separator_rite"]];
            else
                 [rightShadow setImage:[UIImage imageNamed:@""]];
            
        }
    }
    else
    {
        NSLog(@"navbar height %f",navbarScroll.height);
        float y_position = !UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation)?0:10.0f;
        if (navbarScroll.height<35)
            y_position = 0;
        navBarLable.frame = CGRectMake(0, y_position, widthForCaption, self.navigationController.navigationBar.height);
        [leftShadow setImage:[UIImage imageNamed:@"navbar_fade_left"]];
        [rightShadow setImage:[UIImage imageNamed:@"navbar_fade_rite"]];
        if (UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
        {
            [leftShadow setImage:[UIImage imageNamed:@"navbar_fade_left_"]];
            [rightShadow setImage:[UIImage imageNamed:@"navbar_fade_rite_"]];

            leftShadow.frame = CGRectMake(0.0f, 0, 7.0f, navbarScroll.height+4);
            rightShadow.frame = CGRectMake(navBarView.width-(7.0f), 0, 7.0f, navbarScroll.height+4);
        }
               
    }
    
       float offset = (widthForCaption-navBarView.width);
    
     navBarLable.text = self.titleText;
    [leftShadow.layer addAnimation:animation forKey:nil];
    [rightShadow.layer addAnimation:animation forKey:nil];
     [navBarLable.layer addAnimation:animation forKey:nil];  
    if (navbarScroll.contentSize.width>navbarScroll.width)
    {
        float deselerate = (offset>100.0f)?((offset>120.0f)?10.0f:8.0f):2.0f;
        if (UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation))
        {
            deselerate = 10.0f;
        }
        if (contentOffsetPoint.x>0)
        {
             
            if (contentOffsetPointAfter.x<contentOffsetPoint.x)
            {

//                [navbarScroll setContentOffset:CGPointMake(offset, 0) animated:YES];
               
                [UIView animateWithDuration:deselerate delay:0.0f options:UIViewAnimationOptionCurveLinear animations:^{
                    isScrollAnimation = TRUE;
                    [navbarScroll setContentOffset:CGPointMake(offset, 0)];
                } completion:^(BOOL finished) {
                    [UIView animateWithDuration:deselerate delay:0.0f options:UIViewAnimationOptionRepeat|UIViewAnimationOptionAutoreverse|UIViewAnimationOptionCurveLinear  animations:^{
                        
                        isScrollAnimation = TRUE;
                        [navbarScroll setContentOffset:CGPointMake(0, 0)];
                    } completion:^(BOOL finished) {
                        //            contentOffsetPoint = CGPointMake(0, 0);
                      
                    }];
                    
                }];

            }
            else
            {
               
                [UIView animateWithDuration:deselerate delay:0.0f options:UIViewAnimationOptionCurveLinear animations:^{
                    isScrollAnimation = TRUE;
                    [navbarScroll setContentOffset:CGPointMake(0, 0)];
                } completion:^(BOOL finished) {
                    [UIView animateWithDuration:deselerate delay:0.0f options:UIViewAnimationOptionRepeat|UIViewAnimationOptionAutoreverse|UIViewAnimationOptionCurveLinear  animations:^{
                       
                        [navbarScroll setContentOffset:CGPointMake(offset, 0)];
                    } completion:^(BOOL finished) {
                       
                    }];

                }];
            }
        }
        else
        {
            isScrollAnimation = YES;

        [UIView animateWithDuration:deselerate delay:0.0f options:UIViewAnimationOptionRepeat|UIViewAnimationOptionAutoreverse|UIViewAnimationOptionCurveLinear  animations:^{
            
            isScrollAnimation = TRUE;
            [navbarScroll setContentOffset:CGPointMake(offset, 0)];
        } completion:^(BOOL finished) {
           

            contentOffsetPointAfter = [navbarScroll contentOffset];
        }];
        }
    }
    else
    {
        
        [navbarScroll.layer removeAllAnimations];
        [navbarScroll setContentOffset:CGPointMake(0, 0)];
        if ((!self.navigationItem.rightBarButtonItem.customView && widthForCaption<200) || UIDeviceOrientationIsLandscape([UIApplication sharedApplication].statusBarOrientation)){
            
            [navBarLable setCenter:CGPointMake(navBarLable.center.x-22.0f, navBarLable.center.y)];}
    }
   
     
}
//-(void)scrollViewDidEndDecelerating:(UIScrollView *)scrollView
//{
//    if ([scrollView isEqual:navbarScroll])
//    {
//        if (scrollView.contentOffset.x)
//        {
//            [UIView animateWithDuration:3.0f delay:1.0f options:UIViewAnimationOptionRepeat|ui animations:^{
//                [navbarScroll setContentOffset:CGPointMake(0, 0)];
//            } completion:^(BOOL finished) {
//                
//            }];
//        }
//        else if (scrollView.contentOffset.x==0)
//        {
//            [UIView animateWithDuration:3.0f delay:1.0f options:UIViewAnimationOptionAllowAnimatedContent animations:^{
//                 [navbarScroll setContentOffset:CGPointMake(navBarLable.width-navbarScroll.width, 0)];
//            } completion:^(BOOL finished) {
//                
//            }];
//           
//        }
//    }
//}
//-(void)scrollViewDidEndScrollingAnimation:(UIScrollView *)scrollView
//{
//    
//}
-(void)createNavBarScroll
{

    isScrollAnimation = FALSE;
    navBarView = [[UIView alloc] initWithFrame:CGRectZero];
    navBarView.backgroundColor = [UIColor clearColor];
    navBarView.clipsToBounds = YES;
//        CAGradientLayer *gradient = [[CAGradientLayer alloc] init];
//        [gradient setColors:[NSArray arrayWithObjects:(id)[[UIColor clearColor] CGColor],(id)[RGBA(30, 53, 99, 1.0) CGColor], nil]];
//        gradient.frame = navbarScroll.bounds;
//        [gradient setStartPoint:CGPointMake(0.01, 0.0)];
//        [gradient setEndPoint:CGPointMake(0.0, 0.0)];
//        [gradient setDrawsAsynchronously:YES];
//
//        //    [gradient setCornerRadius:8.0f];
//        [navBarView.layer addSublayer:gradient];
//        [gradient release];

    navbarScroll = [[UIScrollView alloc] initWithFrame:CGRectZero];
    navbarScroll.backgroundColor = [UIColor clearColor];
//    if (SYSTEM_VERSION_LESS_THAN(@"7"))
        [navbarScroll setAutoresizingMask:UIViewAutoresizingFlexibleWidth];
    
    [navBarView addSubview:navbarScroll];
    [navbarScroll release];
    navBarLable = [[UILabel alloc] initWithFrame:CGRectZero];
    navBarLable.backgroundColor = [UIColor clearColor];
    navBarLable.textColor = [UIColor whiteColor];
    navBarLable.font = [UIFont fontWithName:@"Arial-BoldItalicMT" size:18.0f];
    navBarLable.textAlignment = NSTextAlignmentCenter;
    navBarLable.text = @"";
//    NSLog(@"title   %@",_titleText);
//    float widthForCaption = [navBarLable.text sizeWithFont:[UIFont fontWithName:@"Arial-BoldItalicMT" size:18] constrainedToSize:CGSizeMake(MAXFLOAT, navBarLable.height)].width;
 //   navBarLable.frame = CGRectMake(0, 0, widthForCaption, navBarLable.height);
    [navbarScroll addSubview:navBarLable];
    [navBarLable release];
    [navbarScroll setContentSize:CGSizeMake(navBarLable.width, navBarLable.height)];
//    [self.navigationController.navigationBar addSubview:navBarView];
    [self.navigationItem setTitleView:navBarView];
    [navBarView release];
    
    leftShadow = [[UIImageView alloc] initWithFrame:CGRectZero];
    [navBarView addSubview:leftShadow];
    [leftShadow release];
    //    navbar_fade_rite
    rightShadow = [[UIImageView alloc] initWithFrame:CGRectZero];
    [navBarView addSubview:rightShadow];
    [rightShadow release];
 
  
}
-(float)navBarScrollWidht
{
    return navbarScroll.width;
}
-(void)removeAnimationsFromScrollView
{
    [navbarScroll removeFromSuperview];
    [navBarView removeFromSuperview];
    navbarScroll = nil;
    navBarView = nil;
    self.navigationItem.titleView = nil;
    [self createNavBarScroll];
//    [self.navigationItem.titleView.layer removeAllAnimations];
        NSLog(@"clear");
//    [navbarScroll.layer removeAllAnimations];
//    [navBarView removeFromSuperview];
//    [self createNavBarScroll];
}
@end
