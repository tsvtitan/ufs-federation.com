//
//  UFSDetailVC.h
//  UFS
//
//  Created by mihail on 27.08.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <UIKit/UIKit.h>
#import "PdfTableViewController.h"
#import "PhotoGalleryVC.h"
#import "SmartImageView.h"
#import "UICheckButtonList.h"

@interface UFSDetailVC : UFSRootVC<NSFetchedResultsControllerDelegate, UIWebViewDelegate, UITableViewDataSource, UITableViewDelegate, UICheckButtonListDelegate>

{
    NSFetchedResultsController *fetchedResultsController;
    UIWebView *newsWebView;
    NSInteger pdfCount;
    NSInteger newsId;
    NSInteger subCatId;
    NSInteger catID;
    UIScrollView *bgScroll;
    UITableView *relatedTV;
    UICheckButtonList *keywordButtons;
    UIButton *subscribeButton;
    UILabel *notifyLabel;
    UIView *labelFoeLoading;
    BOOL isLoading;
    
}

@property (retain, nonatomic) NewsDB *newsObj;
@property (nonatomic, retain) NSFetchedResultsController *fetchedResultsController;
@property (nonatomic, assign) NSInteger type;
/* tsv */
@property (nonatomic, assign) NSMutableArray *titles;
@property (copy,nonatomic) NSString *title;
/* tsv */

-(void) setContentForWebView;
-(id)initWithNewsId:(NSInteger)newsID CategoryID:(NSInteger)cID andSubcategoryID:(NSInteger)subID;
- (void) setElementPosition;

@end
