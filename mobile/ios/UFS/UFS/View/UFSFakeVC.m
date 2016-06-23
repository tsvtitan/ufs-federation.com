//
//  UFSFakeVC.m
//  UFS
//
//  Created by mihail on 01.10.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSFakeVC.h"

@interface UFSFakeVC ()

@end

@implementation UFSFakeVC

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    [self.navigationController popViewControllerAnimated:NO];
	// Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
#pragma -mark Supported Inteface Orientation
-(BOOL)shouldAutorotate
{
    return YES;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
   	return  (toInterfaceOrientation == UIInterfaceOrientationLandscapeLeft|| toInterfaceOrientation == UIInterfaceOrientationLandscapeRight);
}
-(NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskLandscape;
}

@end
