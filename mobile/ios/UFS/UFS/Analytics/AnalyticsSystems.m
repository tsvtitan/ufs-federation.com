//
//  AnalyticsSystems.m
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import "AnalyticsSystems.h"

@implementation AnalyticsSystems


- (id)init
{
    self = [super init];
    if (self) {
        systems = [[NSMutableArray alloc] init];
    }
    return self;
}

- (AnalyticsSystem *)getSystem:(NSUInteger)index
{
    return (AnalyticsSystem *)[systems objectAtIndex:index];
}

- (void)addSystem:(AnalyticsSystem *)system
{
    [systems addObject:system];
}

- (void)forceSend
{
    for (int i=0; i<systems.count;i++) {
        AnalyticsSystem *system = [self getSystem:i];
        if (system) {
            [system forceSend];
        }
    }
}

- (void)eventScreen:(NSString *)screen category:(NSString *)category action:(NSString *)action value:(NSString *)value;
{
    for (int i=0; i<systems.count;i++) {
        AnalyticsSystem *system = [self getSystem:i];
        if (system) {
            [system eventScreen:screen category:category action:action value:value];
        }
    }
}

- (void)eventScreen:(NSString *)screen categories:(NSArray *)categories action:(NSString *)action value:(NSString *)value;
{
    for (int i=0; i<systems.count;i++) {
        AnalyticsSystem *system = [self getSystem:i];
        if (system) {
            [system eventScreen:screen categories:categories action:action value:value];
        }
    }
}

- (void)eventScreens:(NSArray *)screens category:(NSString *)category action:(NSString *)action value:(NSString *)value;
{
    for (int i=0; i<systems.count;i++) {
        AnalyticsSystem *system = [self getSystem:i];
        if (system) {
            [system eventScreens:screens category:category action:action value:value];
        }
    }
}


@end
