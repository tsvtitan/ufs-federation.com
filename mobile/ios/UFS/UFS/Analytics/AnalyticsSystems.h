//
//  AnalyticsSystems.h
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "AnalyticsSystem.h"

@interface AnalyticsSystems : NSObject
{
    @private NSMutableArray *systems;
}

- (id)init;
- (AnalyticsSystem *)getSystem:(NSUInteger)index;
- (void)addSystem:(AnalyticsSystem *)system;

- (void)forceSend;
- (void)eventScreen:(NSString *)screen category:(NSString *)category action:(NSString *)action value:(NSString *)value;
- (void)eventScreen:(NSString *)screen categories:(NSArray *)categories action:(NSString *)action value:(NSString *)value;
- (void)eventScreens:(NSArray *)screens category:(NSString *)category action:(NSString *)action value:(NSString *)value;

@end
