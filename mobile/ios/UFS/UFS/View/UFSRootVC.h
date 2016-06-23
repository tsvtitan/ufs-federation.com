//
//  UFSRootVC.h
//  UFS
//
//  Created by mihail on 10.07.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UFSRootVC : UIViewController <UIScrollViewDelegate>
{
    UILabel *navBarLable;
    UIScrollView *navbarScroll;
    UIView *navBarView;
    UIImageView *leftShadow;
    UIImageView *rightShadow;
    BOOL isScrollAnimation;
    CGPoint contentOffsetPoint;
    CGPoint contentOffsetPointAfter;
}

@property (copy, nonatomic) NSString *titleText;

-(void)createNavBarScroll;
-(void)removeAnimationsFromScrollView;
-(float)navBarScrollWidht;
@end
