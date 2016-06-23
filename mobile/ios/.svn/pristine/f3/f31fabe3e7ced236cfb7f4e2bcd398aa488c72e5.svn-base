//
//  UFSQRCodeVC.h
//  UFS
//
//  Created by Sergei Tomilov on 5/12/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import <UIKit/UIKit.h>

#import "ZBarReaderView.h"


@interface UFSQRCodeVC : UFSRootVC <ZBarReaderViewDelegate,UIAlertViewDelegate,SWRevealViewControllerDelegate>
{
    
    double viewHeight;
}

@property (nonatomic, copy) NSString *titleNavBar;
@property (nonatomic, retain) ZBarReaderView *readerView;
@property (nonatomic, retain) UIActivityIndicatorView *indicator;
@property (nonatomic, retain) UIAlertView *alertViewMessage;
@property (nonatomic, copy) NSString *lastBarcode;

@end
