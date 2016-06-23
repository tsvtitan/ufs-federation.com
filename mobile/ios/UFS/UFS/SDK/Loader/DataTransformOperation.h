//
//  DataTransformOperation.h
//  UFS
//
//  Created by id-East on 2013.
//  Copyright (c) 2013 iD EAST. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "NewsDB.h"
#import "CategoriesDB.h"
#import "SubCatDB.h"
#import "FileImageUrlDB.h"
#import "GroupDB.h"
#import "DebtMarketDB.h"
#import "TableViewDB.h"
#import "TVRows.h"
#import "ContactsDB.h"
#import "DataForContacts.h"
#import "ActionsDB.h"
/* tsv */
#import "KeywordDB.h"
#import "NewsKeywordDB.h"
/* tsv */


#define checkArray(array,action) \
if (![array isKindOfClass:[NSArray class]]) \
{ \
    NSLog(@"(!!) Not array"); \
    action; \
} else if ([(NSArray *)array count] == 0) \
{ \
    NSLog(@"(!!) Array count = 0"); \
    action; \
}

#define checkDictionary(dict,action) \
if (![dict isKindOfClass:[NSDictionary class]]) \
{ \
    NSLog(@"(!!) Not dictionary"); \
    action; \
}
@class NewsDB;
@class CategoriesDB;
@class SubCatDB;
@class GroupDB;
@class TableViewDB;
@class ContactsDB;
@class ActionsDB;
@class HTMLDataDB;
/* tsv */
@class KeywordDB;
@class NewsKeywordDB;
/* tsv */

typedef enum {
    
    DTOperationTypeNone = 0,
    DTOperationTypeRubrics,// Категории
    DTOperationTypeNews, // Новости
    DTOperationTypeGroups, // Группы
    DTOperationTypeTables, // Табличный вид
    DTOperationTypeContacts, // Контакты
    DTOperationTypeStock, // Акции
    DTOperationTypeHTML //ХТМЛ данные

    /* tsv */,DTOperationTypeKeywords
    
} DTOperationType;


@interface DataTransformOperation : NSOperation {
    
    NSData                  *rawData;
    id                      parsedData;
    NSDictionary            *userInfo;
    NSInteger               priority;
    NSNumber                *currentTime;
    NSInteger               countMain;
    NSInteger               rubricUID;
    NSInteger               indexOf;
    NSString                *contactGroup;
}


@property (nonatomic, retain, readwrite) NSData *rawData;
@property (nonatomic, retain, readwrite) NSDictionary *userInfo;

- (SEL)actionForOperation:(DTOperationType)operationType;

- (void)transformNews:(NSDictionary *)entryDict;
- (void)transformRubrics:(NSDictionary *)entryDict;
- (void)transformGroups:(NSDictionary *)entryDict;
- (void)transformTables:(NSDictionary *)entryDict;
- (void)transformContacts:(NSDictionary *)entryDict;
- (void)transformActions:(NSDictionary *)entryDict;
- (void)transformHTMLData:(NSDictionary *)entryDict;
/*tsv*/- (void)transformKeywords:(NSDictionary *)entryDict;

- (NewsDB *)setNews:(NSDictionary *)entryDict;
- (CategoriesDB *)setCategories:(NSDictionary *)entryDict;
- (GroupDB *)setGroups:(NSDictionary *)entryDict;
- (TableViewDB *)setTables:(NSDictionary *)entryDict;
- (ContactsDB *)setContacts:(NSDictionary *)entryDict;
- (ActionsDB *)setActions:(NSDictionary *)entryDict;
- (HTMLDataDB *)setHTMLData:(NSDictionary *)entryDict;
/* tsv */- (KeywordDB *)setKeywords:(NSDictionary *)keyword identifier:(NSNumber*)identifier;

- (void)transformData:(SEL)action;

@end
