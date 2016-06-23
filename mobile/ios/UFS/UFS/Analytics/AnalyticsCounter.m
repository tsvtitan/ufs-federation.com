//
//  AnalyticsCounter.m
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import "AnalyticsCounter.h"
#import "Distimo.h"
#import "Yandex.h"
#import "Google.h"

@implementation AnalyticsCounter

static AnalyticsCounter *counter = nil;

- (id)initWithOptions:(NSDictionary *)options
{
    self = [super init];
    if (self) {
        systems = [[AnalyticsSystems alloc] init];
        [systems addSystem:[[Distimo alloc] initWithKey:kDistimoKey options:options]];
        [systems addSystem:[[Yandex alloc] initWithKey:kYandexKey options:options]];
        [systems addSystem:[[Google alloc] initWithKey:kGoogleKey options:options]];
    }
    return self;
}

- (void)dealloc
{
    //[systems clear];
    [super dealloc];
}

- (AnalyticsSystems *)getSystems;
{
    return systems;
}

+ (void)initialize:(NSDictionary *)options
{
   if (!counter) {
       counter = [[AnalyticsCounter alloc] initWithOptions:options];
    }
}

+ (void)forceSend
{
    if (counter) {
        [counter.getSystems forceSend];
    }
}

+ (void)eventScreen:(NSString *)screen category:(NSString *)category action:(NSString *)action value:(NSString *)value
{
    if (counter) {
        [counter.getSystems eventScreen:screen category:category action:action value:value];
    }
}

+ (void)eventScreen:(NSString *)screen categories:(NSArray *)categories action:(NSString *)action value:(NSString *)value
{
    if (counter) {
        [counter.getSystems eventScreen:screen categories:categories action:action value:value];
    }
}

+ (void)eventScreens:(NSArray *)screens category:(NSString *)category action:(NSString *)action value:(NSString *)value
{
    if (counter) {
        [counter.getSystems eventScreens:screens category:category action:action value:value];
    }
}

@end	
