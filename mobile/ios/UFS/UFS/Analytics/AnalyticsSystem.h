//
//  AnalyticsSystem.h
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface AnalyticsSystem : NSObject

@property (nonatomic, retain) NSString *key;
@property (nonatomic, retain) NSDictionary *options;

- (id)initWithKey:(NSString*)key options:(NSDictionary *)options;

- (NSString *)getKey;
- (void)setKey:(NSString *)key;

- (NSDictionary *)getOptions;
- (void)setOptions:(NSDictionary *)options;

- (void)forceSend;
- (void)event:(NSString *)text;
- (void)eventScreen:(NSString *)screen category:(NSString *)category action:(NSString *)action value:(NSString *)value;
- (void)eventScreen:(NSString *)screen categories:(NSArray *)categories action:(NSString *)action value:(NSString *)value;
- (void)eventScreens:(NSArray *)screens category:(NSString *)category action:(NSString *)action value:(NSString *)value;

@end
