//
//  Distimo.m
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import <DistimoSDK/DistimoSDK.h>
#import "Distimo.h"

@implementation Distimo

- (id)initWithKey:(NSString*)key options:(NSDictionary *)options
{
    self = [super initWithKey:key options:options];
    if (self) {
        [DistimoSDK handleLaunchWithOptions:options sdkKey:key];
    }
    return self;
}

- (void)event:(NSString *)text;
{
    [DistimoSDK logBannerClickWithPublisher:text];
}

@end
