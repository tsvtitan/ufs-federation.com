//
//  UFSContactsVC.h
//  UFS
//
//  Created by mihail on 07.11.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UFSRootVC.h"
#import "ContactDetailVC.h"

@interface UFSContactsVC : UFSRootVC <UITableViewDataSource, UITableViewDelegate, NSFetchedResultsControllerDelegate, SWRevealViewControllerDelegate>
{
    NSFetchedResultsController *fetchedResultsController;
    UIActivityIndicatorView *indicator;
    UIImageView *imageForNotReachble;
}
@property (nonatomic, retain) NSFetchedResultsController *fetchedResultsController;
@property (strong, nonatomic) UITableView *contactsTableView;
/* tsv */@property (nonatomic, assign) NSArray *titles;

@end
