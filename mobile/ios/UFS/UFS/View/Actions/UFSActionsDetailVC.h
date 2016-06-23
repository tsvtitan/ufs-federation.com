//
//  UFSActionsDetailVC.h
//  UFS
//
//  Created by mihail on 15.11.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "UFSRootVC.h"

@interface UFSActionsDetailVC : UFSRootVC <NSFetchedResultsControllerDelegate, UIWebViewDelegate, UIAlertViewDelegate, SWRevealViewControllerDelegate>
{
    NSFetchedResultsController *fetchedResultsController;
    UIImageView *imageForStock;
    UIWebView *textForStock;
    UIScrollView *bgScroll;
    UIButton *detailLoadButton;
    NSURL *requestUri;
    UIActivityIndicatorView *indicator;
    BOOL oldHtml;
}
@property (nonatomic, retain) NSFetchedResultsController *fetchedResultsController;
@property (strong, nonatomic) ActionsDB *stockObj;
@property (copy, nonatomic) NSString * titleNavBar;
@property (assign,nonatomic) NSInteger catID;
@property (assign,nonatomic) NSInteger subCatID;
@property (assign,nonatomic) NSInteger dataID;
@property (assign, nonatomic) NSInteger type;

@end
