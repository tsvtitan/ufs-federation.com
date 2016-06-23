//
//  KeywordDB.h
//  UFS
//
//  Created by Sergei Tomilov on 11/14/14.
//  Copyright (c) 2014 UFS Investment Company. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"


@interface KeywordDB : ManadgeObjectDB

@property (nonatomic, retain) NSString * keyword;
@property (nonatomic, retain) NSNumber * email;
@property (nonatomic, retain) NSNumber * app;
@property (nonatomic, retain) NSNumber * sms;

@end