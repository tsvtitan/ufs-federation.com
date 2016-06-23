//
//  SubCatDB.h
//  UFS
//
//  Created by mihail on 16.12.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"

@class CategoriesDB, NewsDB;

@interface SubCatDB : ManadgeObjectDB

@property (nonatomic, retain) NSNumber * actualNewsCount;
@property (nonatomic, retain) NSNumber * allNewsCount;
@property (nonatomic, retain) NSString * h_imgURL;
@property (nonatomic, retain) NSString * imgURL;
@property (nonatomic, retain) NSNumber * index;
@property (nonatomic, retain) NSString * title;
@property (nonatomic, retain) NSNumber * type;
@property (nonatomic, retain) CategoriesDB *categories;
@property (nonatomic, retain) NSSet *news;
@end

@interface SubCatDB (CoreDataGeneratedAccessors)

- (void)addNewsObject:(NewsDB *)value;
- (void)removeNewsObject:(NewsDB *)value;
- (void)addNews:(NSSet *)values;
- (void)removeNews:(NSSet *)values;

@end
