//
//  UFSRegistrationVC.h
//  UFS
//
//  Created by Sergei Tomilov on 4/16/15.
//  Copyright (c) 2015 UFS Investment Company. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface UFSRegistrationVC : UFSRootVC<UITextFieldDelegate>
{
    IBOutlet UIScrollView *scrollView;
    
    IBOutlet UILabel *nameLabel;
    IBOutlet UITextField *nameEdit;
    
    IBOutlet UILabel *phoneLabel;
    IBOutlet UITextField *phoneEdit;
    
    IBOutlet UILabel *emailLabel;
    IBOutlet UITextField *emailEdit;
    
    IBOutlet UIButton *brokerageCheck;
    IBOutlet UILabel *brokerageLabel;
    
    IBOutlet UIButton *yieldCheck;
    IBOutlet UILabel *yieldLabel;
    
    IBOutlet UIButton *nextButton;
}

@property (nonatomic, copy) NSString *titleNavBar;
@property (nonatomic, copy) NSString *titleParent;
@property (nonatomic, copy) NSDictionary *promotion;
@property (nonatomic, assign) NSMutableDictionary *timers;
@property (nonatomic, assign) NSMutableArray *products;
@property (nonatomic, assign) BOOL brokerageChecked;
@property (nonatomic, assign) BOOL yieldChecked;

@property (nonatomic, assign) UITapGestureRecognizer *tapRecognizer;

- (id)initWithPromotion:(NSDictionary*) promotion titleParent:(NSString *)titleParent;
- (IBAction)nextButtonTouch:(id)sender;
- (IBAction)brokerageCheckTouch:(id)sender;
- (IBAction)yieldCheckTouch:(id)sender;

@end
