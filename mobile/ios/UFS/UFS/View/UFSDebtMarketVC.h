//
//  UFSDebtMarketVC.h
//  UFS
//
//  Created by mihail on 16.09.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UFSRootVC.h"

@interface UFSDebtMarketVC : UFSRootVC<UITableViewDataSource, UITableViewDelegate, NSFetchedResultsControllerDelegate, SWRevealViewControllerDelegate>
{
    NSFetchedResultsController *fetchedResultsController;
    UIActivityIndicatorView *indicator;
    NSInteger selectedPosition;
    NSInteger reloadAnimation;
}

@property (nonatomic, retain) NSFetchedResultsController *fetchedResultsController;
@property (assign, nonatomic) NSInteger subCatId;
@property (assign, nonatomic) NSInteger catId;
// type for VC 0-std, 1- was pushed from category list, 2-all emitents for VC
@property (assign, nonatomic) NSInteger type;
@property (nonatomic, copy) NSString *titleNavBar;
@property (strong, nonatomic) UITableView *groupsTableView;
/* tsv */@property (nonatomic, assign) NSMutableArray *titles;

@end
