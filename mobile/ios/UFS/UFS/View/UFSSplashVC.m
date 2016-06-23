//
//  UFSSplashVC.m
//  UFS
//
//  Created by mihail on 16.07.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSSplashVC.h"

@interface UFSSplashVC ()

@end

@implementation UFSSplashVC

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
	
    self.backgroundImage = [[UIImageView alloc] initWithFrame:self.view.frame];
    [_backgroundImage setBackgroundColor:[UIColor whiteColor]];
    [self.view addSubview:_backgroundImage];
    [UIImageView animateWithDuration:animationTime animations:
	 ^(void){
		 
		 // background scale
		 CGRect bgframe = self.backgroundImage.frame;
		 [self.backgroundImage setFrame:CGRectMake(bgframe.origin.x - backgroundScale, bgframe.origin.y - backgroundScale, bgframe.size.width + backgroundScale, bgframe.size.height + backgroundScale)];
		 
		 // background alfa -> 0
		 [self.backgroundImage setAlpha:.0f];
		 
		 // right image slides to right and fade out to alfa 0
				 
	 }
                          completion:
	 ^(BOOL finished){
		 // well, let's switch to menu
         
         
         [self removeFromParentViewController];
         
		 
	 }];
    
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
