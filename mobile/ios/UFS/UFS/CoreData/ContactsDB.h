//
//  ContactsDB.h
//  UFS
//
//  Created by mihail on 16.12.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"

@class DataForContacts;

@interface ContactsDB : ManadgeObjectDB

@property (nonatomic, retain) NSString * address;
@property (nonatomic, retain) NSString * city;
@property (nonatomic, retain) NSNumber * expired;
@property (nonatomic, retain) NSNumber * index;
@property (nonatomic, retain) NSNumber * latitude;
@property (nonatomic, retain) NSNumber * longitude;
@property (nonatomic, retain) NSString * name;
@property (nonatomic, retain) NSString * region;
@property (nonatomic, retain) NSSet *data;
@end

@interface ContactsDB (CoreDataGeneratedAccessors)

- (void)addDataObject:(DataForContacts *)value;
- (void)removeDataObject:(DataForContacts *)value;
- (void)addData:(NSSet *)values;
- (void)removeData:(NSSet *)values;

@end
