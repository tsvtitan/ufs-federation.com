//
//  UFSMainNewsVC.h
//  UFS
//
//  Created by mihail on 10.07.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//
#define ReloadAnimationNone 1111011
#define ReloadAnimationPush 2220022
#define ReloadAnimationPop 33300333



#import <UIKit/UIKit.h>
#import "UFSRootVC.h"
#import "SWRevealViewController.h"
#import "UFSCalendarView.h"

#define kMainScreen @"Главный экран"

@interface UFSMainNewsVC:UFSRootVC <UITableViewDataSource, UITableViewDelegate, NSFetchedResultsControllerDelegate, SWRevealViewControllerDelegate, CKCalendarDelegate, UIAlertViewDelegate>
{
    NSFetchedResultsController *fetchedResultsController;
    NSString * predicateFormat;
    NSInteger lastID;
    NSInteger reloadAnimation;
    UIView *scrennForFilter;
    NSArray *dateArray;
    NSString *predicateFilter;
    UIActivityIndicatorView *indicator;
    UIImageView *imageForNotReachble;
    NSInteger fetchLimit;
    BOOL filterTap;
    UIAlertView *exitAlert;
    BOOL isPreviosLoadFailed;
    UIPanGestureRecognizer *customRecognizer;
    double viewHeight;
}



@property (strong, nonatomic) UIButton *menu;
@property (nonatomic, retain) NSFetchedResultsController *fetchedResultsController;
@property (assign, nonatomic) NSInteger subCatId;
@property (assign, nonatomic) NSInteger allNewsCount;
@property (assign, nonatomic) NSInteger catId;
@property (nonatomic, copy) NSString *titleNavBar;
@property (strong, nonatomic) UITableView *newsTableView;
@property (nonatomic, assign) NSInteger typeOfNews;
@property (nonatomic, assign) NSInteger type;
/* tsv */@property (nonatomic, assign) NSMutableArray *titles;

-(void)reloadVC;
-(void) setMenuEnabled:(NSNotification *)notify;

@end
