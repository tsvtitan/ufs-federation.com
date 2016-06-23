//
//  Yandex.m
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import "YMMCounter.h"
#import "Yandex.h"

@implementation Yandex

- (id)initWithKey:(NSString*)key options:(NSDictionary *)options
{
    self = [super initWithKey:key options:options];
    if (self) {
        [YMMCounter startWithAPIKey:[key intValue]];
        [YMMCounter setReportsEnabled:YES];
        [YMMCounter setReportCrashesEnabled:NO];
    }
    return self;
}

- (void)forceSend
{
    [YMMCounter sendEvents];
}

- (void)event:(NSString *)text;
{
    NSError * __autoreleasing error = nil;
    [YMMCounter reportEvent:text failure:&error];
}

@end
