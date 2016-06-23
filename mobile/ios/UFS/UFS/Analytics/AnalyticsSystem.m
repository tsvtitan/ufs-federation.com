//
//  AnalyticsSystem.m
//  UFS
//
//  Created by Sergei Tomilov on 7/17/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import "AnalyticsSystem.h"

@implementation AnalyticsSystem


- (id)initWithKey:(NSString*)key options:(NSDictionary *)options
{
    self = [super init];
    if (self) {
        [self setKey:key];
        [self setOptions:options];
    }
    return self;
}

- (NSString *)getKey
{
    return _key;
}

- (void)setKey:(NSString *)key
{
    _key = key;
}

- (NSDictionary *)getOptions
{
    return _options;
}

- (void)setOptions:(NSDictionary *)options
{
    _options = options;
}

- (void)forceSend
{
    //
}

- (void)event:(NSString *)text;
{
    //
}

- (void)eventScreen:(NSString *)screen category:(NSString *)category action:(NSString *)action value:(NSString *)value
{
    screen = (screen)?screen:@"";
    screen = [screen stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    action = (action)?[NSString stringWithFormat:@"(%@)",action]:@"";
    action = [action stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    category = (screen)?[NSString stringWithFormat:@"%@/%@",screen,category]:category;
    category = [category stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    category = [NSString stringWithFormat:@"[%@] %@",category,action];
    category = [category stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    value = (value)?[NSString stringWithFormat:@": %@",value]:@"";
    value = [value stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
    
    NSString *ret = [NSString stringWithFormat:@"%@%@",category,value];
    
    [self event:[ret stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]]];
}

- (void)eventScreen:(NSString *)screen categories:(NSArray *)categories action:(NSString *)action value:(NSString *)value
{
    NSString *category = @"";
    if (categories) {
        for (int i=0; i<categories.count; i++) {
            NSString *s = (NSString *)[categories objectAtIndex:i];
            s = [s stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
            category = (i==0)?s:[NSString stringWithFormat:@"%@/%@",category,s];
        }
    }
    [self eventScreen:screen category:category action:action value:value];
}

- (void)eventScreens:(NSArray *)screens category:(NSString *)category action:(NSString *)action value:(NSString *)value
{
    NSString *screen = @"";
    if (screens) {
        for (int i=0; i<screens.count; i++) {
            NSString *s = (NSString *)[screens objectAtIndex:i];
            s = [s stringByTrimmingCharactersInSet:[NSCharacterSet whitespaceCharacterSet]];
            screen = (i==0)?s:[NSString stringWithFormat:@"%@/%@",screen,s];
        }
    }
    [self eventScreen:screen category:category action:action value:value];
}

@end
