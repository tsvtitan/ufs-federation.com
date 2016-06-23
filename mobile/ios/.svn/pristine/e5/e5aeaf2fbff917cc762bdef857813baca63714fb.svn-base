//
//  DataTransformOperation.m
//  UFS
//
//  Created by id-East on 2013.
//  Copyright (c) 2013 iD EAST. All rights reserved.
//

#import "DataTransformOperation.h"


@interface DataTransformOperation()

@end

@implementation DataTransformOperation

@synthesize rawData, userInfo;

- (void)main {
    
	@synchronized([CoreDataManager shared].managedObjectContextForParsing) {
		
        DTOperationType operationType = [[userInfo objectForKey:@"type"] integerValue];
        
        if (rawData && operationType) {
            
            parsedData = rawData;
            contactGroup = @"";
            indexOf = 0;
            if (!parsedData) {
//                NSString *strData = [[NSString alloc] initWithData:rawData encoding:NSUTF8StringEncoding];
//                NSLog(@"%@", strData);
//                [strData release];
                NSLog(@"(!!!) Incorrect data");
                return;
            }
            
            SEL action = [self actionForOperation:operationType];
            [self transformData:action];
        }
	}
}

- (void) dealloc{
    SAFE_KILL(currentTime);
    SAFE_KILL(userInfo);
    [super dealloc];
}
- (SEL)actionForOperation:(DTOperationType)operationType {
    
    priority = 0;
    countMain = 0;
    SEL action = nil;
    SAFE_KILL(currentTime);
    NSDate *today = [NSDate date];
    
    currentTime = [[NSNumber alloc] initWithDouble: [today timeIntervalSince1970]];
    
    
    switch (operationType) {
            
        case DTOperationTypeNews:
            
            action = @selector(transformNews:);
            break;
        case DTOperationTypeRubrics:
            
            action = @selector(transformRubrics:);
            break;
        case DTOperationTypeGroups:
            
            action = @selector(transformGroups:);
            break;
        case DTOperationTypeTables:
            
            action = @selector(transformTables:);
            break;
        case DTOperationTypeContacts:
            
            action = @selector(transformContacts:);
            break;
			
		case DTOperationTypeStock:
			
			action = @selector(transformActions:);
			break;
        case DTOperationTypeHTML:
			
			action = @selector(transformHTMLData:);
			break;
            
        /* tsv */
        case DTOperationTypeKeywords:
            
            action = @selector(transformKeywords:);
            break;
        /* tsv */
            
        default:{
            action = nil;
            break;
        }
    }
    
    return action;
}
//новости экономики
- (void)transformNews:(NSDictionary *)entryDict{
    
    if ([entryDict isKindOfClass:[NSArray class]]) {
        
        for (NSDictionary *newsDic in (NSArray *)entryDict) {
            
            [self setNews:newsDic];
        }
    } else if ([entryDict isKindOfClass:[NSDictionary class]]){
        if ([entryDict objectForKey:@"result"]) {
            
            NSArray *entryArray = [entryDict objectForKey:@"result"];
            if ([entryArray isKindOfClass:[NSArray class]]) {
                
                for (NSDictionary *newsDic in entryArray) {
                    
                    [self setNews:newsDic];
                }
                
            }else{
                [self setNews:entryDict];
            }
        }
    }
}
- (void)transformRubrics:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSArray class]]) {
        
        for (NSDictionary *newsDic in (NSArray *)entryDict) {
            
            [self setCategories:newsDic];
        }
    }else if ([entryDict isKindOfClass:[NSDictionary class]]){
        if ([entryDict objectForKey:@"result"]) {
            
            NSArray *entryArray = [entryDict objectForKey:@"result"];
            if ([entryArray isKindOfClass:[NSArray class]]) {
                
                for (NSDictionary *newsDic in entryArray) {
                    
                    [self setCategories:newsDic];
                }
                
            }else{
                [self setCategories:entryDict];
            }
        }
    }
 
}

- (void)transformGroups:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSArray class]]) {
        
        for (NSDictionary *newsDic in (NSArray *)entryDict) {
            
             [self setGroups:newsDic];
        }
    }else if ([entryDict isKindOfClass:[NSDictionary class]]){
        if ([entryDict objectForKey:@"result"]) {
            
            NSArray *entryArray = [entryDict objectForKey:@"result"];
            if ([entryArray isKindOfClass:[NSArray class]]) {
                
                for (NSDictionary *newsDic in entryArray) {
                    
                    [self setGroups:newsDic];
                }
                
            }else{
                 [self setGroups:entryDict];
            }
        }
    }

}

- (void)transformTables:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSArray class]]) {
        
        for (NSDictionary *newsDic in (NSArray *)entryDict) {
            
            [self setTables:newsDic];
        }
    } else if ([entryDict isKindOfClass:[NSDictionary class]]){
        if ([entryDict objectForKey:@"result"]) {
            id entryArray = nil;
            if ([[entryDict objectForKey:@"result"] isKindOfClass:[NSDictionary class]])
            {
                entryArray = (NSDictionary *)[entryDict objectForKey:@"result"];
            }
            else
            {
                entryArray = (NSArray *)[entryDict objectForKey:@"result"];
            }
            if ([entryArray isKindOfClass:[NSArray class]]) {
                
                for (NSDictionary *newsDic in entryArray) {
                    
                    [self setTables:newsDic];
                }
                
            }else{
                [self setTables:entryDict];
            }
        }
        else
        {
            [self setTables:entryDict];
        }
    }
 
}

- (void)transformContacts:(NSDictionary *)entryDict
{
    indexOf = -1;
    if ([entryDict isKindOfClass:[NSArray class]]) {
        
        for (NSDictionary *contactsDict in (NSArray *)entryDict) {
            
            [self setContacts:contactsDict];
        }
    }else if ([entryDict isKindOfClass:[NSDictionary class]]){
        if ([entryDict objectForKey:@"result"]) {
            
            NSArray *entryArray = [entryDict objectForKey:@"result"];
            if ([entryArray isKindOfClass:[NSArray class]]) {
                
                for (NSDictionary *contactsDict in entryArray) {
                    
                    [self setContacts:contactsDict];
                }
                
            }else{
                [self setContacts:entryDict];
            }
        }
    }

}

- (void)transformActions:(NSDictionary *)entryDict
{
	if ([entryDict isKindOfClass:[NSArray class]])
	{
		for (NSDictionary *actionsDict in (NSArray *)entryDict)
		{
			[self setActions:actionsDict];
		}
	}
	else if ([entryDict isKindOfClass:[NSDictionary class]])
	{
		if ([entryDict objectForKey:@"result"])
		{
			NSArray *entryArray = [entryDict objectForKey:@"result"];
//			NSLog(@"%@", entryArray);
			
			if ([entryArray isKindOfClass:[NSArray class]])
			{
				for (NSDictionary *actionsDict in entryArray)
				{
//					NSLog(@"%@", actionsDict);
					[self setActions:actionsDict];
				}
			}
		}
		else
		{
			[self setActions:entryDict];
		}
	}
}

- (void)transformHTMLData:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSArray class]]) {
        
        for (NSDictionary *contactsDict in (NSArray *)entryDict) {
            
            [self setHTMLData:contactsDict];
        }
    }else if ([entryDict isKindOfClass:[NSDictionary class]]){
        if ([entryDict objectForKey:@"result"]) {
            
            NSArray *entryArray = [entryDict objectForKey:@"result"];
            if ([entryArray isKindOfClass:[NSArray class]]) {
                
                for (NSDictionary *contactsDict in entryArray) {
                    
                    [self setHTMLData:contactsDict];
                }
                
            }else{
                [self setHTMLData:entryDict];
            }
        }
    }

}

/* tsv */

- (void)transformKeywords:(NSDictionary *)entryDict
{
    indexOf = -1;
    if ([entryDict isKindOfClass:[NSArray class]]) {
        
        int i=0;
        for (NSDictionary *keywords in (NSArray *)entryDict) {
            [self setKeywords:keywords identifier:@(i++)];
        }
        
    } else if ([entryDict isKindOfClass:[NSDictionary class]]){
        
        if ([entryDict objectForKey:@"result"]) {
            
            NSArray *entryArray = [entryDict objectForKey:@"result"];
            if ([entryArray isKindOfClass:[NSArray class]]) {
                
                int i=0;
                for (NSDictionary *keywords in entryArray) {
                    
                    [self setKeywords:keywords identifier:@(i++)];
                }
                
            } else {
                [self setKeywords:entryDict identifier:@(0)];
            }
        }
    }
    
}

/* tsv */


- (NewsDB *)setNews:(NSDictionary *)entryDict{
    
    NSNumber *identifier = @([[entryDict objectForKey:@"id"] integerValue]);
    
    NewsDB *newsDB = [CoreDataManager object:@"NewsDB" withIdentifier:identifier inMainContext:NO];
    
    checkAndSet(newsDB, @"title", ([[entryDict objectForKey:@"title"] length] > 0) ? [entryDict objectForKey:@"title"] : newsDB.title);
    checkAndSet(newsDB, @"text", ([[entryDict objectForKey:@"text"] length] > 0) ? [entryDict objectForKey:@"text"] : newsDB.text);
    newsDB.date = @([[entryDict objectForKey:@"date"] integerValue]);

    NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
    [dateFormatter setDateStyle:NSDateFormatterLongStyle]; // 2013-01-24 15:46:53
    newsDB.strDate = [dateFormatter stringFromDate:[NSDate dateWithTimeIntervalSince1970:[newsDB.date intValue]]];
    [dateFormatter release];
    
    checkAndSet(newsDB, @"categoryID", @([[entryDict objectForKey:@"categoryID"] integerValue]));

    checkAndSet(newsDB, @"subcategoryID", @([[entryDict objectForKey:@"subcategoryID"] integerValue]));
    
    checkAndSet(newsDB, @"expired",@([[entryDict objectForKey:@"expired"] integerValue]));
    checkAndSet(newsDB, @"actual", @([[entryDict objectForKey:@"actual"] integerValue]));
    checkAndSet(newsDB, @"lastModify", @([[NSNumber numberWithInt:[[NSDate date] timeIntervalSince1970]] integerValue]));
    
    [[newsDB mutableSetValueForKey:@"files"] removeAllObjects];
    
    if ([[entryDict objectForKey:@"imageUrls"] isKindOfClass:[NSArray class]])
    {
        NSArray *arr = [NSArray arrayWithObject:[entryDict objectForKey:@"imageUrls"]];
        for (int i=0; i<arr.count; i++)
        {
            if ([[arr objectAtIndex:i] isKindOfClass:[NSArray class]])
            {
                NSArray *dictArr = ((NSArray *)[arr objectAtIndex:i]);
                for (int j=0;j<dictArr.count;j++)
                {
                    NSDictionary *imgDict = [NSDictionary dictionaryWithDictionary:((NSDictionary *)[dictArr objectAtIndex:j])];
                    FileImageUrlDB * imageUrl = [CoreDataManager createObject:@"FileImageUrlDB" inMainContext:NO];
                    checkAndSet(imageUrl, @"name", [imgDict objectForKey:@"name"]);
                    checkAndSet(imageUrl, @"url", [imgDict objectForKey:@"url"]);
                    checkAndSet(imageUrl, @"extension", [imgDict objectForKey:@"extension"]);
                    // is Image
                    checkAndSet(imageUrl, @"type", @(1));
                    [imageUrl setNews:newsDB];
                    [newsDB addFilesObject:imageUrl];
                }
            }
        }
    }
    if ([[entryDict objectForKey:@"fileUrls"] isKindOfClass:[NSArray class]])
    {
        NSArray *arr = [NSArray arrayWithObject:[entryDict objectForKey:@"fileUrls"]];

        for (int i=0; i<arr.count; i++)
        {
            if ([[arr objectAtIndex:i] isKindOfClass:[NSArray class]])
            {
                NSArray *dictArr = ((NSArray *)[arr objectAtIndex:i]);
                for (int j=0;j<dictArr.count;j++)
                {
                    NSDictionary *fDict = [NSDictionary dictionaryWithDictionary:((NSDictionary *)[dictArr objectAtIndex:j])];
                    FileImageUrlDB * fileUrl = [CoreDataManager createObject:@"FileImageUrlDB" inMainContext:NO];
                    checkAndSet(fileUrl, @"name", [fDict objectForKey:@"name"]);
                    checkAndSet(fileUrl, @"url", [fDict objectForKey:@"url"]);
                    checkAndSet(fileUrl, @"extension", [fDict objectForKey:@"extension"]);
                    if ([fileUrl.extension isEqualToString:@"pdf"])
                    // is File
                        checkAndSet(fileUrl, @"type", @(3));
                    else
                        checkAndSet(fileUrl, @"type", @(2));
                    [fileUrl setNews:newsDB];
                    [newsDB addFilesObject:fileUrl];
                }
            }
        }
    }
    if ([[entryDict objectForKey:@"relatedLinks"] isKindOfClass:[NSArray class]])
    {
        NSArray *arr = [NSArray arrayWithObject:[entryDict objectForKey:@"relatedLinks"]];
        for (int i=0; i<arr.count; i++)
        {
            if ([[arr objectAtIndex:i] isKindOfClass:[NSArray class]])
            {
                NSArray *dictArr = ((NSArray *)[arr objectAtIndex:i]);
                for (int j=0;j<dictArr.count;j++)
                {
                    NSDictionary *fDict = [NSDictionary dictionaryWithDictionary:((NSDictionary *)[dictArr objectAtIndex:j])];
                    NSNumber *ident = @([[fDict objectForKey:@"id"] integerValue]);
                    NewsDB * relatedLinks = [CoreDataManager object:@"NewsDB" withIdentifier:ident inMainContext:NO];
                    checkAndSet(relatedLinks, @"title", [fDict objectForKey:@"name"]);
                    relatedLinks.title = [relatedLinks.title replaceHTMLTags];
                    checkAndSet(relatedLinks, @"date", @([[fDict objectForKey:@"date"] intValue]));
                    NSString *cat = [fDict objectForKey:@"categoryID"];
                    NSString *sub = @"";
                    sub = [fDict objectForKey:@"subcategoryID"];
                    if (!cat)
                        cat = @"";
                    if ([fDict validateValue:NULL forKey:@"subcategoryID" error:nil])
                       sub = @"";
                    checkAndSet(relatedLinks, @"categoryID", [cat length]?@([cat intValue]):@(0));
                    checkAndSet(relatedLinks, @"subcategoryID",[sub length]?@([sub intValue]):@(0));
                    [newsDB addRelatedLinksObject:relatedLinks];
                }
            }
        }
    }
    
    /* tsv */
    [[newsDB mutableSetValueForKey:@"keywords"] removeAllObjects];
    
    if ([[entryDict objectForKey:@"keywords"] isKindOfClass:[NSArray class]])
    {
        NSArray *arr = [NSArray arrayWithObject:[entryDict objectForKey:@"keywords"]];
        for (int i=0; i<arr.count; i++)
        {
            if ([[arr objectAtIndex:i] isKindOfClass:[NSArray class]])
            {
                NSArray *dictArr = ((NSArray *)[arr objectAtIndex:i]);
                if (dictArr.count>0) {
                
                    for (int j=0;j<dictArr.count;j++)
                    {
                        NSNumber *index = [NSNumber numberWithInt:j];
                        NSString *word = (NSString *)[dictArr objectAtIndex:j];
                        NewsKeywordDB *keyword = [CoreDataManager createObject:@"NewsKeywordDB" inMainContext:NO];
                        checkAndSet(keyword, @"index", index);
                        checkAndSet(keyword, @"keyword", word);
                        [keyword setNews:newsDB];
                        [newsDB addKeywordsObject:keyword];
                    }
                }
            }
        }
    }
    /* tsv */

    SubCatDB *subcategory = [CoreDataManager object:@"SubCatDB" withIdentifier: @([[entryDict objectForKey:@"subcategoryID"] integerValue]) inMainContext:NO];
    [subcategory addNewsObject:newsDB];
    newsDB.subcategory = subcategory;
    CategoriesDB *catDB = [CoreDataManager object:@"CategoriesDB" withIdentifier: @([[entryDict objectForKey:@"categoryID"] integerValue]) inMainContext:NO];
    [catDB addNewsObject:newsDB];
    [newsDB setCategory:catDB];
    
    return newsDB;
}

- (CategoriesDB *)setCategories:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSDictionary class]]){
        
        
        NSNumber *identifier = @([[entryDict objectForKey:@"id"] integerValue]);
        
        CategoriesDB *catDB = [CoreDataManager object:@"CategoriesDB" withIdentifier:identifier inMainContext:NO];
        [[catDB mutableSetValueForKey:@"subcategories"] removeAllObjects];
         [[catDB mutableSetValueForKey:@"news"] removeAllObjects];
        checkAndSet(catDB, @"title", ([[entryDict objectForKey:@"title"] length] > 0) ? [entryDict objectForKey:@"title"] : catDB.title);
        checkAndSet(catDB, @"type", ([[entryDict objectForKey:@"type"] length] > 0) ? @([[entryDict objectForKey:@"type"] integerValue]) : catDB.type);
        checkAndSet(catDB, @"allActivityCount",[[entryDict objectForKey:@"allActivityCount"] length]?@([[entryDict objectForKey:@"allActivityCount"] integerValue]):@(-1));
        checkAndSet(catDB, @"allNewsCount",[[entryDict objectForKey:@"allNewsCount"] length]?@([[entryDict objectForKey:@"allNewsCount"] integerValue]):@(0));
        checkAndSet(catDB, @"actualNewsCount",[[entryDict objectForKey:@"actualNewsCount"] length]?@([[entryDict objectForKey:@"actualNewsCount"] integerValue]):@(0));
        checkAndSet(catDB, @"lastModify", @([[NSNumber numberWithInt:[[NSDate date] timeIntervalSince1970]] integerValue]));
        //тэги приходят
        
        catDB.title = [catDB.title replaceHTMLTags];
        catDB.index = @(indexOf);
        indexOf++;
        if ([[entryDict objectForKey:@"subcategories"] isKindOfClass:[NSArray class]])
        {
            NSArray *array = [NSArray arrayWithObject:[entryDict objectForKey:@"subcategories"]];
            for (int i =0; i< array.count;i++)
            {
                if ([[array objectAtIndex:i] isKindOfClass:[NSArray class]])
                {
                    NSArray *dictArr = ((NSArray *)[array objectAtIndex:i]);
                    for (int j=0;j<dictArr.count;j++)
                    {
                        NSDictionary *subDict = (NSDictionary *)[dictArr objectAtIndex:j];
                        SubCatDB *subcategory = [CoreDataManager object:@"SubCatDB" withIdentifier:@([[subDict objectForKey:@"id"] integerValue]) inMainContext:NO];
//                        [[subcategory mutableSetValueForKey:@"news"] removeAllObjects];
                        checkAndSet(subcategory, @"title", [subDict objectForKey:@"title"]);
                        checkAndSet(subcategory, @"type", @([[subDict objectForKey:@"type"] integerValue]));
                        checkAndSet(subcategory, @"imgURL", [subDict objectForKey:@"imgURL"]);
                        checkAndSet(subcategory, @"h_imgURL", [subDict objectForKey:@"h_imgURL"]);
                        checkAndSet(subcategory, @"allNewsCount", @([[subDict objectForKey:@"allNewsCount"] integerValue]));
                        checkAndSet(subcategory, @"actualNewsCount", @([[subDict objectForKey:@"actualNewsCount"] integerValue]));
                        subcategory.lastModify = [NSNumber numberWithDouble:[[NSDate date] timeIntervalSince1970]];
                        subcategory.index = @(j);
                        subcategory.title = [subcategory.title replaceHTMLTags];
                        subcategory.imgURL = [subcategory.imgURL replaceHTMLTags];
                         subcategory.h_imgURL = [subcategory.h_imgURL replaceHTMLTags];
                        if (![FileSystem pathExisted:[subcategory.imgURL stringByReplacingOccurrencesOfString:@"files" withString:@"images"]])
                        {
                            [UFSLoader getImage:subcategory.imgURL AndName:[subcategory.imgURL stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
                        }
                        if (![FileSystem pathExisted:[subcategory.h_imgURL stringByReplacingOccurrencesOfString:@"files" withString:@"images"]])
                        {
                            [UFSLoader getImage:subcategory.h_imgURL AndName:[subcategory.h_imgURL stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
                        }
                        subcategory.categories = catDB;
                        [catDB addSubcategoriesObject:subcategory];
                                           }
                }
            }
            
        }
//        checkAndSet(newsDB, @"textPreview", ([[entryDict objectForKey:@"textPreview"] length] > 0) ? [entryDict objectForKey:@"textPreview"] : newsDB.textPreview);
        //тэги приходят
        //            newsDB.textPreview = [newsDB.textPreview replaceHTMLTags];
        //
        //            checkAndSet(newsDB, @"source", [entryDict objectForKey:@"source"]);
        //            checkAndSet(newsDB, @"date", [entryDict objectForKey:@"date"]);
        //            checkAndSet(newsDB, @"preview", [entryDict objectForKey:@"image_B"]);
        //            if (newsDB.date) {
        //
        //                newsDB.strData = [Helper getDateAnotherFormat:[NSDate dateWithTimeIntervalSince1970:newsDB.date.doubleValue]];
        //            }else{
        //
        //                newsDB.strData = @"";
        //            }
        return catDB;
    }
    return nil;
}

- (GroupDB *)setGroups:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSDictionary class]]){
        
        
        NSNumber *identifier = @([[entryDict objectForKey:@"id"] integerValue]);
        
        GroupDB *groupDB = [CoreDataManager object:@"GroupDB" withIdentifier:identifier inMainContext:NO];
        
        checkAndSet(groupDB, @"name", ([[entryDict objectForKey:@"name"] length] > 0) ? [entryDict objectForKey:@"name"] : groupDB.name);
        groupDB.name = [groupDB.name replaceHTMLTags];
        if ([[entryDict objectForKey:@"items"] isKindOfClass:[NSArray class]])
        {
            NSArray *array = [NSArray arrayWithObject:[entryDict objectForKey:@"items"]];
            for (int i =0; i< array.count;i++)
            {
                if ([[array objectAtIndex:i] isKindOfClass:[NSArray class]])
                {
                    NSArray *dictArr = ((NSArray *)[array objectAtIndex:i]);
                    for (int j=0;j<dictArr.count;j++)
                    {
                        NSDictionary *subDict = (NSDictionary *)[dictArr objectAtIndex:j];
                        DebtMarketDB *subcategory = [CoreDataManager object:@"DebtMarketDB" withIdentifier:@([[subDict objectForKey:@"id"] integerValue]) inMainContext:NO];
                        checkAndSet(subcategory, @"name", [subDict objectForKey:@"name"]);
                        subcategory.name = [subcategory.name replaceHTMLTags];
                        checkAndSet(subcategory, @"type", @([[subDict objectForKey:@"type"] integerValue]));
                        
                        checkAndSet(subcategory, @"actual", @([[subDict objectForKey:@"actual"] integerValue]));
                        checkAndSet(subcategory, @"date", @([[subDict objectForKey:@"date"] integerValue]));
                        
                        NSDateFormatter *dateFormatter = [[NSDateFormatter alloc] init];
                        
                        //     [dateFormatter setDateFormat:@"dd.LL.yyyy"];
                        [dateFormatter setDateStyle:NSDateFormatterLongStyle]; // 2013-01-24 15:46:53
                        //    [dateFormatter setDateStyle:NSDateFormatterNoStyle];
                        //    [dateFormatter setFormatterBehavior:NSDateFormatterBehaviorDefault];
                        //    newsDB.date = @([[dateFormatter dateFromString:newsDB.strDate] timeIntervalSince1970]);
                        subcategory.strDate = [dateFormatter stringFromDate:[NSDate dateWithTimeIntervalSince1970:[subcategory.date intValue]]];
                        [dateFormatter release];

                        checkAndSet(subcategory, @"isNew", @([[subDict objectForKey:@"new"] integerValue]));
//                        checkAndSet(subcategory, @"isNew", @(1));
                        checkAndSet(subcategory, @"type", @([[subDict objectForKey:@"type"] integerValue]));
                        checkAndSet(subcategory, @"linkID", @([[subDict objectForKey:@"linkID"] integerValue]));
                        subcategory.lastModify = [NSNumber numberWithDouble:[[NSDate date] timeIntervalSince1970]];
                        subcategory.index = @(j);
                        subcategory.name = [subcategory.name replaceHTMLTags];
                        subcategory.debtGroup = groupDB;
                        [groupDB addItemsObject:subcategory];
                    }
                }
            }
            
        }
        return groupDB;
    }
    return nil;

}
- (TableViewDB *)setTables:(NSDictionary *)entryDict
{
//    NSNumber *identifier = @([[entryDict objectForKey:@"id"] integerValue]);
    
//    TableViewDB *tableVC = [CoreDataManager object:@"TableViewDB" withIdentifier:identifier inMainContext:NO];
    NSAutoreleasePool *autoRelease = [[NSAutoreleasePool alloc] init];
    TableViewDB *tableObj = nil;
    NSArray *tablesDict;
    NSString *valueString = @"";
    if ([entryDict objectForKey:@"tables"])
    {
        tablesDict = ((NSArray *)[entryDict objectForKey:@"tables"]);
        NSLog(@"tables count = %d",[tablesDict count]);
        if ([tablesDict isKindOfClass:[NSArray class]])
        {
            
            for (int j=0; j<[tablesDict count]; j++) {
                NSNumber *identifier = @(j+[[entryDict objectForKey:@"subcategoryID"] integerValue]*1035);
                tableObj = [CoreDataManager object:@"TableViewDB" withIdentifier:identifier inMainContext:NO];
            
                NSDictionary *tablesArray = [tablesDict objectAtIndex:j];
            
                checkAndSet(tableObj, @"expired", @([[tablesArray objectForKey:@"expired"] integerValue]));
                checkAndSet(tableObj, @"subcatID", @([[entryDict objectForKey:@"subcategoryID"] integerValue]));
                checkAndSet(tableObj, @"name", [tablesArray objectForKey:@"name"]);
                checkAndSet(tableObj, @"about", [tablesArray objectForKey:@"about"]);
                checkAndSet(tableObj, @"descriptionText", [[entryDict objectForKey:@"about"] objectForKey:@"description"]);
// Tegs removing
                tableObj.name = [tableObj.name replaceHTMLTags];
                tableObj.about = [tableObj.about replaceHTMLTags];
                tableObj.descriptionText = [tableObj.descriptionText replaceHTMLTags];
// Columns names
                valueString = @"";
                if ([[tablesArray objectForKey:@"columns"] isKindOfClass:[NSArray class]])
                {
                    NSArray *columns = [tablesArray objectForKey:@"columns"];
                    
                    for (int i=0;i<columns.count;i++)
                    {
                        valueString = [valueString stringByAppendingString:columns[i]];
                        if (i<columns.count-1)
                            valueString = [valueString stringByAppendingString:@" | "];
                    }
//                    NSLog(@"val %@",valueString);
                    TVRows *columnsDB = [CoreDataManager object:@"TVRows" withIdentifier:@([identifier intValue]*500) inMainContext:NO];
                    columnsDB.column = valueString;
                    columnsDB.index = @(0);
                    [tableObj addColumnsObject:columnsDB];
                    columnsDB.col = tableObj;
                    columnsDB.column = [columnsDB.column replaceHTMLTags];

                }
                if ([[tablesArray objectForKey:@"alignments"] isKindOfClass:[NSArray class]])
                {
                    NSArray *columns = [tablesArray objectForKey:@"alignments"];
                    valueString = @"";
                    for (int i=0;i<columns.count;i++)
                    {
                        valueString = [valueString stringByAppendingString:columns[i]];
                        if (i<columns.count-1)
                            valueString = [valueString stringByAppendingString:@" | "];
                    }
//                    NSLog(@"alig %@",valueString);
                    TVRows *columnsDB = [CoreDataManager object:@"TVRows" withIdentifier:@([identifier intValue]*501) inMainContext:NO];
                    columnsDB.column = valueString;
                    columnsDB.index = @(1);
                    [tableObj addAligmentObject:columnsDB];
                    columnsDB.aligm = tableObj;
                    columnsDB.column = [columnsDB.column replaceHTMLTags];
                }
                /* tsv */
                if ([[tablesArray objectForKey:@"buyUrls"] isKindOfClass:[NSArray class]])
                {
                    NSArray *columns = [tablesArray objectForKey:@"buyUrls"];
                    valueString = @"";
                    for (int i=0;i<columns.count;i++)
                    {
                        valueString = [valueString stringByAppendingString:columns[i]];
                        if (i<columns.count-1)
                            valueString = [valueString stringByAppendingString:@" | "];
                    }
                    NSLog(@"url %@",valueString);
                    TVRows *columnsDB = [CoreDataManager object:@"TVRows" withIdentifier:@([identifier intValue]*601) inMainContext:NO];
                    columnsDB.column = valueString;
                    columnsDB.index = @(2);
                    [tableObj addBuyurlsObject:columnsDB];
                    columnsDB.buyurls = tableObj;
                    columnsDB.column = [columnsDB.column replaceHTMLTags];
                }
                /* tsv */
                if ([[tablesArray objectForKey:@"values"] isKindOfClass:[NSArray class]])
                {
                    
                    NSArray *values = [tablesArray objectForKey:@"values"];
                    if ( [values isKindOfClass:[NSArray class]])
                    {
                        NSNumber *idNumber =@([identifier intValue]*842);
                        for (int i=0;i<values.count;i++)
                        {
                            if ([[values objectAtIndex:i] isKindOfClass:[NSArray class]])
                            {
                                 
                                NSArray *columns = [values objectAtIndex:i];
                                valueString = @"";
                                for (int j=0;j<columns.count;j++)
                                {
                                    valueString = [valueString stringByAppendingString:columns[j]];
                                    if (j<columns.count-1)
                                        valueString = [valueString stringByAppendingString:@" | "];
                                }

                                TVRows *columnsDB = [CoreDataManager object:@"TVRows" withIdentifier:idNumber inMainContext:NO];
                                //                                    NSLog(@"table = %@ row = %d column = %d id = %@", identifier,i,j,idNumber);
                                columnsDB.column = valueString;
                                /* tsv */ columnsDB.index = @(i+3);
                                //columnsDB.index = @(i+2);
                                [tableObj addValuesObject:columnsDB];
                                columnsDB.values = tableObj;
                                columnsDB.column = [columnsDB.column replaceHTMLTags];
                                idNumber = @([idNumber intValue]+1);

                            }

                        }
                    }
                    
                }
                

                NSLog(@"table %d",j);
               
            }
             [CoreDataManager saveParsingContext];
            }
            
        }
    
    NSLog(@"Complete table ");
    [autoRelease drain];
    return tableObj;
 
}

- (ContactsDB *)setContacts:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSDictionary class]]){
        
        
        NSNumber *identifier = @([[entryDict objectForKey:@"id"] integerValue]);
        
        ContactsDB *contactsObj = [CoreDataManager object:@"ContactsDB" withIdentifier:identifier inMainContext:NO];
        
        checkAndSet(contactsObj, @"name", ([[entryDict objectForKey:@"name"] length] > 0) ? [entryDict objectForKey:@"name"] : contactsObj.name);
        checkAndSet(contactsObj, @"region", ([[entryDict objectForKey:@"region"] length] > 0) ? [entryDict objectForKey:@"region"] : contactsObj.region);
        
        checkAndSet(contactsObj, @"city", ([[entryDict objectForKey:@"city"] length] > 0) ? [entryDict objectForKey:@"city"] : contactsObj.city);
        checkAndSet(contactsObj, @"address", ([[entryDict objectForKey:@"address"] length] > 0) ? [entryDict objectForKey:@"address"] : contactsObj.address);
        checkAndSet(contactsObj, @"latitude", ([[entryDict objectForKey:@"latitude"] length] > 0) ? @([[entryDict objectForKey:@"latitude"] doubleValue]) : contactsObj.latitude);
        checkAndSet(contactsObj, @"longitude", ([[entryDict objectForKey:@"longitude"] length] > 0) ? @([[entryDict objectForKey:@"longitude"] doubleValue]) : contactsObj.longitude);
        checkAndSet(contactsObj, @"expired", ([[entryDict objectForKey:@"expired"] length] > 0) ? @([[entryDict objectForKey:@"expired"] intValue]) : contactsObj.expired);
         
        
                //тэги приходят
        
        contactsObj.name = [contactsObj.name replaceHTMLTags];
        contactsObj.region = [contactsObj.region replaceHTMLTags];
        contactsObj.city = [contactsObj.city replaceHTMLTags];
        contactsObj.address = [contactsObj.address replaceHTMLTags];
        if (![contactsObj.region isEqualToString:contactGroup])
        {
           indexOf++;
            contactGroup = contactsObj.region;
        }
        contactsObj.index = @(indexOf);
        
        if ([[entryDict objectForKey:@"contacts"] isKindOfClass:[NSArray class]])
        {
            NSArray *array = [NSArray arrayWithObject:[entryDict objectForKey:@"contacts"]];
            for (int i =0; i< array.count;i++)
            {
                if ([[array objectAtIndex:i] isKindOfClass:[NSArray class]])
                {
                    NSArray *dictArr = ((NSArray *)[array objectAtIndex:i]);
                    for (int j=0;j<dictArr.count;j++)
                    {
                        NSDictionary *subDict = (NSDictionary *)[dictArr objectAtIndex:j];
                        DataForContacts *data = [CoreDataManager object:@"DataForContacts" withIdentifier:@([contactsObj.identifier intValue]*(indexOf+124) + j) inMainContext:NO];
                        checkAndSet(data, @"title", [subDict objectForKey:@"title"]);
                        checkAndSet(data, @"contactID", contactsObj.identifier);
                        checkAndSet(data, @"value", [subDict objectForKey:@"value"]);
                        
                        data.lastModify = [NSNumber numberWithDouble:[[NSDate date] timeIntervalSince1970]];
                        data.title = [data.title replaceHTMLTags];
                        data.value = [data.value replaceHTMLTags];
                        data.contactObj = contactsObj;
                        [contactsObj addDataObject:data];
                    }
                }
            }
            
        }
          return contactsObj;
    }
    return nil;

}

- (ActionsDB *)setActions:(NSDictionary *)entryDict
{
	if ([entryDict isKindOfClass:[NSDictionary class]])
	{
		NSNumber *identifier = @([entryDict[@"id"] integerValue]);
		
		ActionsDB *actionsObj = [CoreDataManager object:@"ActionsDB" withIdentifier:identifier inMainContext:NO];
		
		checkAndSet(actionsObj, @"actionID", ([entryDict[@"id"] length] > 0) ? @([entryDict[@"id"] integerValue]) : actionsObj.actionID);
        
        checkAndSet(actionsObj, @"categoryID", ([entryDict[@"categoryID"] length] > 0) ? @([entryDict[@"categoryID"] integerValue]) : @(0));
        checkAndSet(actionsObj, @"subcategoryID", ([entryDict[@"subcategoryID"] length] > 0) ? @([entryDict[@"subcategoryID"] integerValue]) : @(0));
        
		checkAndSet(actionsObj, @"type", ([entryDict[@"type"] length] > 0) ? @([entryDict[@"type"] integerValue]) : actionsObj.type);
		checkAndSet(actionsObj, @"expired", ([entryDict[@"expired"] length] > 0) ? @([entryDict[@"expired"] integerValue]) : actionsObj.expired);
		checkAndSet(actionsObj, @"mainImg", ([entryDict[@"mainImg"] length] > 0) ? entryDict[@"mainImg"] : actionsObj.mainImg);
		checkAndSet(actionsObj, @"url", ([entryDict[@"url"] length] > 0) ? entryDict[@"url"] : actionsObj.url);
		checkAndSet(actionsObj, @"text", ([entryDict[@"text"] length] > 0) ? entryDict[@"text"] : ([entryDict[@"html"] length] > 0) ? entryDict[@"html"] : @"");
		/* tsv */checkAndSet(actionsObj, @"name", ([entryDict[@"name"] length] > 0) ? entryDict[@"name"] : actionsObj.name);
		return actionsObj;
	}
	
	return nil;
}

/*- (HTMLDataDB *)setHTMLData:(NSDictionary *)entryDict
{
    if ([entryDict isKindOfClass:[NSDictionary class]])
	{
		NSNumber *identifier = @([entryDict[@"categoryID"] integerValue]+[entryDict[@"subcategoryID"] intValue]);
		
		HTMLDataDB *actionsObj = [CoreDataManager object:@"HTMLDataDB" withIdentifier:identifier inMainContext:NO];
		
        checkAndSet(actionsObj, @"categoryID", ([entryDict[@"categoryID"] length] > 0) ? @([entryDict[@"categoryID"] integerValue]) : @(0));
        checkAndSet(actionsObj, @"subcategoryID", ([entryDict[@"subcategoryID"] length] > 0) ? @([entryDict[@"subcategoryID"] integerValue]) : @(0));
        
        checkAndSet(actionsObj, @"text", ([entryDict[@"html"] length] > 0) ? entryDict[@"html"] : ([entryDict[@"html"] length] > 0) ? entryDict[@"html"] : @"");
		
		return actionsObj;
	}
	
	return nil;

}*/

/* tsv */
void setParamEx(id object, NSDictionary *dictionary, NSString *propertyFrom, NSString *propertyTo, id defaultValue, BOOL forced)
{
    id val = nil;
    
    NSObject *v = [dictionary objectForKey:propertyFrom];
    if (v!=nil) {
        if ([defaultValue isKindOfClass:[NSString class]]) {
            val = v;
        } else if ([defaultValue isKindOfClass:[NSNumber class]]) {
            val = @([(NSNumber*)v integerValue]);
        }
    } else {
        val = defaultValue;
    }
    
    checkAndSetEx(object, propertyTo, val, forced);
}

void setParam(id object, NSDictionary *dictionary, NSString *property, id defaultValue)
{
    setParamEx(object,dictionary,property,property,defaultValue,NO);
}

- (HTMLDataDB *)setHTMLData:(NSDictionary *)html
{
    if ([html isKindOfClass:[NSDictionary class]]) {
	
        NSString *s1 = html[@"categoryID"];
        if (s1.length==0) s1 = @"00";
        
        NSString *s2 = html[@"subcategoryID"];
        if (s2.length==0) s2 = @"00";
        
        NSString *s = [NSString stringWithFormat:@"%@%@",s1,s2];
        NSNumber *identifier = @([s integerValue]);
 
        HTMLDataDB *db = [CoreDataManager object:@"HTMLDataDB" withIdentifier:identifier inMainContext:NO];
 
        setParam(db,html,@"categoryID",@(0));
        setParam(db,html,@"subcategoryID",@(0));
        setParamEx(db,html,@"html",@"text",@"",YES);
        
        
        return db;
	}
	return nil;
 }

- (KeywordDB *)setKeywords:(NSDictionary *)keyword identifier:(NSNumber*)identifier
{
    if ([keyword isKindOfClass:[NSDictionary class]]) {
        
        KeywordDB *db = [CoreDataManager object:@"KeywordDB" withIdentifier:identifier inMainContext:NO];
        
        setParam(db,keyword,@"keyword",@"");
        setParam(db,keyword,@"email",@(0));
        setParam(db,keyword,@"app",@(0));
        setParam(db,keyword,@"sms",@(0));
        
        return db;
    }
    return nil;
}
/* tsv */

- (void)transformData:(SEL)action {
 
    NSAutoreleasePool *pool = [[NSAutoreleasePool alloc] init];
    NSArray *array = (NSArray *)parsedData;
    checkArray(array, array = [NSArray arrayWithObject:parsedData]);

    int count = array.count;

    for (NSDictionary *dict in array) {
        count ++;
        if (count > 50) {
                count = 0;
                [CoreDataManager saveParsingContext];
        }
        if ([[dict objectForKey:@"result"] isKindOfClass:[NSDictionary class]]) {
            dict = [dict objectForKey:@"result"];
        }
        
        if (action) {
                
            [self performSelector:action withObject:dict];
        }
    }
    if (count > 0) {
        [CoreDataManager saveParsingContext];
    }
    [pool drain];
}


@end
