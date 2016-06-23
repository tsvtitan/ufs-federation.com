//
//  HTMLDataDB.h
//  UFS
//
//  Created by mihail on 16.12.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"


@interface HTMLDataDB : ManadgeObjectDB

@property (nonatomic, retain) NSNumber * categoryID;
@property (nonatomic, retain) NSNumber * subcategoryID;
@property (nonatomic, retain) NSString * text;

@end
