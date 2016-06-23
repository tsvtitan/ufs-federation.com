//
//  TableViewDB.h
//  UFS
//
//  Created by Sergei Tomilov on 21/04/14.
//  Copyright (c) 2014 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"

@class TVRows;

@interface TableViewDB : ManadgeObjectDB

@property (nonatomic, retain) NSString * about;
@property (nonatomic, retain) NSString * descriptionText;
@property (nonatomic, retain) NSNumber * expired;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSNumber * subcatID;
@property (nonatomic, retain) NSSet *aligment;
@property (nonatomic, retain) NSSet *buyurls;
@property (nonatomic, retain) NSSet *columns;
@property (nonatomic, retain) NSSet *values;
@end

@interface TableViewDB (CoreDataGeneratedAccessors)

- (void)addAligmentObject:(TVRows *)value;
- (void)removeAligmentObject:(TVRows *)value;
- (void)addAligment:(NSSet *)values;
- (void)removeAligment:(NSSet *)values;

- (void)addBuyurlsObject:(TVRows *)value;
- (void)removeBuyurlsObject:(TVRows *)value;
- (void)addBuyurls:(NSSet *)values;
- (void)removeBuyurls:(NSSet *)values;

- (void)addColumnsObject:(TVRows *)value;
- (void)removeColumnsObject:(TVRows *)value;
- (void)addColumns:(NSSet *)values;
- (void)removeColumns:(NSSet *)values;

- (void)addValuesObject:(TVRows *)value;
- (void)removeValuesObject:(TVRows *)value;
- (void)addValues:(NSSet *)values;
- (void)removeValues:(NSSet *)values;

@end
