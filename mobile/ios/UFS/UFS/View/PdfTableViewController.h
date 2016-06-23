//
//  PdfTableViewController.h
//  UFS
//
//  Created by mihail on 05.09.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface PdfTableViewController : UIViewController<UITableViewDataSource,UITableViewDelegate>
{
    FileImageUrlDB *pdfFile;
    UITableView *pdfTV;
}
@property (strong,nonatomic) NSArray *pdfArray;
@property (nonatomic, copy) NSString *nameOfNews;

@end
