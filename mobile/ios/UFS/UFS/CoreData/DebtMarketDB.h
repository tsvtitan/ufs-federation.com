//
//  DebtMarketDB.h
//  UFS
//
//  Created by mihail on 16.12.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"

@class GroupDB;

@interface DebtMarketDB : ManadgeObjectDB

@property (nonatomic, retain) NSNumber * actual;
@property (nonatomic, retain) NSNumber * date;
@property (nonatomic, retain) NSNumber * index;
@property (nonatomic, retain) NSNumber * isNew;
@property (nonatomic, retain) NSNumber * linkID;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSString * strDate;
@property (nonatomic, retain) NSNumber * type;
@property (nonatomic, retain) GroupDB *debtGroup;

@end
