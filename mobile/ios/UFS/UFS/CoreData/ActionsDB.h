//
//  ActionsDB.h
//  UFS
//
//  Created by mihail on 16.12.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"


@interface ActionsDB : ManadgeObjectDB

@property (nonatomic, retain) NSNumber * actionID;
@property (nonatomic, retain) NSNumber * categoryID;
@property (nonatomic, retain) NSNumber * expired;
@property (nonatomic, retain) NSString * mainImg;
@property (nonatomic, retain) NSNumber * subcategoryID;
@property (nonatomic, retain) NSString * text;
@property (nonatomic, retain) NSNumber * type;
@property (nonatomic, retain) NSString * url;
/* tsv */@property (nonatomic, retain) NSString * name;

@end
