//
//  DataTransformer.h
//  BM
//
//  Created by id-East on 2/5/12.
//  Copyright (c) 2012 iD EAST. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface DataTransformer : NSObject

+ (void)parseData:(NSData *)data userInfo:(NSDictionary *)userInfo;

+ (NSOperationQueue *)queue;

@end
