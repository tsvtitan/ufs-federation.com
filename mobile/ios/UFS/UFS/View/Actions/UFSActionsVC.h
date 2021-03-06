//
//  UFSActionsVC.h
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#define kImageTag 83331

#import <UIKit/UIKit.h>
#import "UFSRootVC.h"
#import "UFSLoader.h"
#import "UFSActionsDetailVC.h"

@interface UFSActionsVC : UFSRootVC <UITableViewDataSource, UITableViewDelegate, NSFetchedResultsControllerDelegate, SWRevealViewControllerDelegate>
{
	NSFetchedResultsController *fetchedResultsController;
	UIActivityIndicatorView *activityIndicatorView;
    UIImageView *imageForNotReachble;
}
@property (nonatomic, copy) NSString *categoryId;
@property (nonatomic, retain) NSFetchedResultsController *fetchedResultsController;
@property (strong, nonatomic) UITableView *actionTableView;
@property (copy, nonatomic) NSString *titleNavBar;
/* tsv */@property (nonatomic, assign) NSArray *titles;

- (id)initWithCategoryIdentifier:(NSString *)categoryId;

@end
