//
//  CategoriesDB.h
//  UFS
//
//  Created by mihail on 16.12.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"

@class NewsDB, SubCatDB;

@interface CategoriesDB : ManadgeObjectDB

@property (nonatomic, retain) NSNumber * actualNewsCount;
@property (nonatomic, retain) NSNumber * allActivityCount;
@property (nonatomic, retain) NSNumber * allNewsCount;
@property (nonatomic, retain) NSNumber * index;
@property (nonatomic, retain) NSString * title;
@property (nonatomic, retain) NSNumber * type;
@property (nonatomic, retain) NSSet *news;
@property (nonatomic, retain) NSSet *subcategories;
@end

@interface CategoriesDB (CoreDataGeneratedAccessors)

- (void)addNewsObject:(NewsDB *)value;
- (void)removeNewsObject:(NewsDB *)value;
- (void)addNews:(NSSet *)values;
- (void)removeNews:(NSSet *)values;

- (void)addSubcategoriesObject:(SubCatDB *)value;
- (void)removeSubcategoriesObject:(SubCatDB *)value;
- (void)addSubcategories:(NSSet *)values;
- (void)removeSubcategories:(NSSet *)values;

@end
