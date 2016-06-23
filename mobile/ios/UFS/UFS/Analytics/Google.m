//
//  Google.m
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import "GAI.h"
#import "GAIFields.h"
#import "GAIDictionaryBuilder.h"
#import "Google.h"

@implementation Google

- (id)initWithKey:(NSString*)key options:(NSDictionary *)options
{
    self = [super initWithKey:key options:options];
    if (self) {
        [GAI sharedInstance].optOut = NO;
        [GAI sharedInstance].dryRun = NO;
        [GAI sharedInstance].trackUncaughtExceptions = YES;
        [[GAI sharedInstance] trackerWithTrackingId:key];
        
    }
    return self;
}

- (id<GAITracker>) getTracker
{
    return [[GAI sharedInstance] defaultTracker];
}

- (void)forceSend
{
    [[GAI sharedInstance] dispatch];
}

- (void)eventScreen:(NSString *)screen category:(NSString *)category action:(NSString *)action value:(NSString *)value
{
    id<GAITracker> tracker = [self getTracker];
    
    [tracker set:kGAIScreenName value:screen];
    @try {
        
        [tracker send:[[GAIDictionaryBuilder createEventWithCategory:category
                                                              action:action
                                                               label:value
                                                               value:nil] build]];
    }
    @finally {
        [tracker set:kGAIScreenName value:nil];
    }
}

@end
