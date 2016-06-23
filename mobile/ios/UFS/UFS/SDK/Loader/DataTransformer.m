//
//  DataTransformer.m
//  BM
//
//  Created by id-East on 2/5/12.
//  Copyright (c) 2012 iD EAST. All rights reserved.
//

#import "DataTransformer.h"

static NSOperationQueue *kDataTransformerQueue = nil;

@implementation DataTransformer

+ (void)parseData:(NSData *)data userInfo:(NSDictionary *)userInfo {
    
    DataTransformOperation *operation = [[DataTransformOperation alloc] init];
    
    operation.rawData = data;
    operation.userInfo = userInfo;
    
    [DataTransformer.queue addOperation:[operation autorelease]];
}

+ (NSOperationQueue *)queue {
    
    if (!kDataTransformerQueue) {
        
        kDataTransformerQueue = [[NSOperationQueue alloc] init];
		kDataTransformerQueue.maxConcurrentOperationCount = 1;
    }
    
    return kDataTransformerQueue;
}

@end
