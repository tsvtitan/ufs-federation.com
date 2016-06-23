//
//  NewsDB.h
//  UFS
//
//  Created by mihail on 16.12.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreData/CoreData.h>
#import "ManadgeObjectDB.h"

@class CategoriesDB, FileImageUrlDB, NewsKeywordDB, NewsDB, SubCatDB;

@interface NewsDB : ManadgeObjectDB

@property (nonatomic, retain) NSNumber * actual;
@property (nonatomic, retain) NSNumber * categoryID;
@property (nonatomic, retain) NSNumber * date;
@property (nonatomic, retain) NSNumber * expired;
@property (nonatomic, retain) NSNumber * isMain;
@property (nonatomic, retain) NSString * strDate;
@property (nonatomic, retain) NSNumber * subcategoryID;
@property (nonatomic, retain) NSString * text;
@property (nonatomic, retain) NSString * title;
@property (nonatomic, retain) CategoriesDB *category;
@property (nonatomic, retain) NSSet *files;
@property (nonatomic, retain) NSSet *relatedLinks;
@property (nonatomic, retain) SubCatDB *subcategory;
/* tsv */
@property (nonatomic, retain) NSSet *keywords;
//@property (nonatomic, retain) StringList *matches;
/* tsv */
@end

@interface NewsDB (CoreDataGeneratedAccessors)

- (void)addFilesObject:(FileImageUrlDB *)value;
- (void)removeFilesObject:(FileImageUrlDB *)value;
- (void)addFiles:(NSSet *)values;
- (void)removeFiles:(NSSet *)values;

- (void)addRelatedLinksObject:(NewsDB *)value;
- (void)removeRelatedLinksObject:(NewsDB *)value;
- (void)addRelatedLinks:(NSSet *)values;
- (void)removeRelatedLinks:(NSSet *)values;

/* tsv */
- (void)addKeywordsObject:(NewsKeywordDB *)value;
- (void)removeKeywordsObject:(NewsKeywordDB *)value;
- (void)addKeywords:(NSSet *)values;
- (void)removeKeywords:(NSSet *)values;
/* tsv */

@end
