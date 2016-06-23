//
//  UFSLoader.m
//  UFS
//
//  Created by iD-EAST on 16.07.13.
//  Copyright (c) 2013 iD EAST. All rights reserved.
//

#import "UFSLoader.h"
#import <Foundation/Foundation.h>
#import "DataTransformer.h"
#import "OpenUDID.h"

//#import "CoreDataManager.h"

static UFSLoader *kSharedLoader = nil;

@implementation UFSLoader

@synthesize reach, client;

+ (UFSLoader *)shared
{
	if (kSharedLoader == nil)
	{
		kSharedLoader = [[UFSLoader alloc] init];
	}
	return kSharedLoader;
}

+ (BOOL)reachable
{
    
//	NetworkStatus netStatus = [[UFSLoader shared].reach currentReachabilityStatus];
//        switch (netStatus)
//    {
//        case ReachableViaWWAN:
//        {
//            
//			return YES;
//		}
//        case ReachableViaWiFi:
//        {
//           
//			return YES;
//		}
//		default:
//		{
//			break;
//		}
//    }
	return [UFSLoader shared].reachabilityStatus;
}

- (UFSLoader *)init
{
	self = [super initWithBaseURL:[NSURL URLWithString:kServerBaseURL]];
	if (self)
	{
//		[[NSNotificationCenter defaultCenter] addObserver:self
//												 selector:@selector(reachabilityChanged:)
//													 name:kReachabilityChangedNotification
//												   object:nil];
//		
//		reach = [[Reachability reachabilityWithHostName:kServerBaseURL] retain];
//		[reach startNotifier];
       
        [self registerHTTPOperationClass:[AFJSONRequestOperation class]];
         [self setDefaultHeader:@"Accept" value:@"application/json"];
       [AFJSONRequestOperation addAcceptableContentTypes:[NSSet setWithObject:@"text/html"]];
        
        [self setParameterEncoding:AFFormURLParameterEncoding];
//        [self setParameterEncoding:AFJSONParameterEncoding];
        [self setAllowsInvalidSSLCertificate:YES];
        [self setReachabilityStatusChangeBlock:^(AFNetworkReachabilityStatus status) {
            if (status == AFNetworkReachabilityStatusReachableViaWiFi)
            {
                NSLog(@"reach WIFI");
                 self.reachabilityStatus=YES;
                [[NSNotificationCenter defaultCenter] postNotificationName:kReachableOk object:nil];
               
            }
            if (status == AFNetworkReachabilityStatusReachableViaWWAN)
            {
                NSLog(@"reach WAN");
                self.reachabilityStatus=YES;
                 [[NSNotificationCenter defaultCenter] postNotificationName:kReachableOk object:nil];
                 
            }
            if (status == AFNetworkReachabilityStatusNotReachable)
            {
                NSLog(@"reach no");
                self.reachabilityStatus=NO;
                 [[NSNotificationCenter defaultCenter] postNotificationName:kNotReachable object:nil];
                 
            }
        }];
//		self.networkQueue = [ASINetworkQueue queue];
//		[self.networkQueue setDelegate:self];
//		[self.networkQueue setRequestDidFinishSelector:@selector(requestFinished:)];
//		[self.networkQueue setRequestDidFailSelector:@selector(requestFailed:)];
//		[self.networkQueue setQueueDidFinishSelector:@selector(queueFinished:)];
//        [self.networkQueue setShowAccurateProgress:YES];
////      [self.networkQueue setDownloadProgressDelegate:self];
//
//		[self.networkQueue go];
	}
	return self;
}

- (void)dealloc
{
	kSharedLoader = nil;
	
//    SAFE_KILL(networkQueue);
//	[self.networkQueue cancelAllOperations];
//	
	[[NSNotificationCenter defaultCenter] removeObserver:self];
	[reach release];
	
	[super dealloc];
}

+ (BOOL)reachableWiFi
{
	NetworkStatus netStatus = [[UFSLoader shared].reach currentReachabilityStatus];
	return (netStatus == ReachableViaWiFi);
}

- (void)reachabilityChanged:(NSNotification* )note
{
    NSLog(@"reachabilityChanged:");
}

+ (NSString *)paramsFromDictionary:(NSDictionary *)params {
    
    NSMutableString *result = [NSMutableString string];
    
    for (NSString *key in params.allKeys) {
        
        if (result.length) {
            
            [result appendString:@"&"];
        }
        
        [result appendFormat:@"%@=%@", key, [params objectForKey:key]];
    }
    
    return [[result copy] autorelease];
    
}
+(BOOL)requestPostAuth: (NSString *)screenHeigth andWidth:(NSString *)screenWidth
{
    
    NSLog(@"device name %@",[[UIDevice currentDevice] name]);
    NSString *currentVersion = [NSString stringWithFormat:@"%@ %@",[[UIDevice currentDevice] systemName],[[UIDevice currentDevice] systemVersion]];
    NSString *currentResolution = [NSString stringWithFormat:@"%@x%@",screenWidth,screenHeigth];
    if ([[UIDevice currentDevice] userInterfaceIdiom]==UIUserInterfaceIdiomPhone)
    {
        if (RETINA)
        {
            currentResolution = @"960×640";
        }
        else
        {
             currentResolution = @"480×320";
        }
    }
    else
    {
        if (RETINA)
        {
            currentResolution = @"2048×1536";
        }
        else
        {
            currentResolution = @"1024×768";
        }

    }
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    
    /* tsv */
    NSString *openUDID = [OpenUDID value];
    NSString *version = [[[NSBundle mainBundle] infoDictionary] objectForKey:@"CFBundleVersion"];
    BOOL newAuth = NO;
    /* tsv */
   
    NSNumber *expiredOld = [userDef valueForKey:kTokenExpiredTime];
    // Ask is old token expired, if it TRUE delete all download files
    if (newAuth || expiredOld==nil || [expiredOld isEqualToNumber:[NSNumber numberWithInt:0]] || [@([[NSDate date] timeIntervalSince1970]) compare:expiredOld]==NSOrderedDescending || [@([[NSDate date] timeIntervalSince1970]) compare:expiredOld]==NSOrderedSame)
    {
        
    //  Removing all data
        NSLog(@"remove all data");
       
        NSArray *news = [CoreDataManager objects:@"NewsDB" withPredicate:[NSPredicate predicateWithFormat:@"category == nil || subcategory == nil"] inMainContext:YES];
        if (news.count) {
            
            NSInteger countObjects = news.count;
            while (countObjects > 0) {
                countObjects --;
                NewsDB *new = [news objectAtIndex:countObjects];
                [CoreDataManager.shared.managedObjectContext deleteObject:new];
            }
        }
        
        /* tsv */
        NSString *company = @"";
        #if COMPANY_UFS
            company = @"UFS";
        #elif COMPANY_PREMIER
            company = @"PREMIER";
        #endif
        /* tsv */

        [[UFSLoader shared] postPath:[NSString stringWithFormat:UFSURLAuth] parameters:[NSDictionary dictionaryWithObjects:@[@"Apple",[[UIDevice currentDevice] name],currentResolution,currentVersion,openUDID,version,company] forKeys:@[@"madeBy",@"deviceModel",@"screenSize",@"os",@"id",@"version",@"company"]] success:^(AFHTTPRequestOperation *operation, id responseObject)
        {
             NSLog(@"successs................");
            if ([responseObject isKindOfClass:[NSDictionary class]])
            {
                NSString *tokenStr = [[responseObject objectForKey:@"result"] objectForKey:@"token"];
                NSNumber *expired = @([[[responseObject objectForKey:@"result"] objectForKey:@"expired"] integerValue]);
                /* tsv */
                NSString *email = [[responseObject objectForKey:@"result"] objectForKey:@"email"];
                NSString *phone = [[responseObject objectForKey:@"result"] objectForKey:@"phone"];
                NSString *categoryId = [[responseObject objectForKey:@"result"] objectForKey:@"categoryId"];
                NSString *categoryDelay = [[responseObject objectForKey:@"result"] objectForKey:@"categoryDelay"];
                /* tsv */

                if (tokenStr.length)
                {
                    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
                
                    [userDef setValue:tokenStr forKey:kTokenForSession];
                    [userDef setValue:expired forKey:kTokenExpiredTime];
                    
                    /* tsv */
                    [userDef setBool:YES forKey:kFirstMenuShow];
                    [userDef setValue:email forKey:kAuthEmail];
                    [userDef setValue:phone forKey:kAuthPhone];
                    
                    NSInteger val = [userDef integerForKey:kAuthCategoryId];
                    NSScanner* scan = [NSScanner scannerWithString:categoryId];
                    if ([scan scanInteger:&val] && [scan isAtEnd]) {
                        [userDef setInteger:val forKey:kAuthCategoryId];
                    }
                    
                    val = [userDef integerForKey:kAuthCategoryDelay];
                    scan = [NSScanner scannerWithString:categoryDelay];
                    if ([scan scanInteger:&val] && [scan isAtEnd]) {
                        [userDef setInteger:val forKey:kAuthCategoryDelay];
                    }
                    /* tsv */
                    
                    [userDef synchronize];
                    
                    NSLog(@"token=%@, expired=%@, email=%@",tokenStr,expired,email);
                    [[CoreDataManager shared] removeDataWithEntityName:@"SubCatDB"];
                    [[CoreDataManager shared] removeDataWithEntityName:@"CategoriesDB"];
                    
                    /* tsv */
                    [UFSLoader requestKeywords];
                    /* tsv */
                }
                
            }
        } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
            [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];

             NSLog(@"Fail................auth%@",error.localizedFailureReason);
        }];
        return false;
    }
    else
    {
        if ([screenHeigth isEqualToString:@"first"])
        {
           /* tsv */[UFSLoader requestKeywords];
           [UFSLoader requestPostRubrics];
        }
        NSLog(@"token is same %@",[userDef valueForKey:kTokenForSession]);
        return true;
    }
}

+(void)requestPostRubrics
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    NSString *token=[userDef valueForKey:kTokenForSession];
    
    if (token.length)
    {
    [[UFSLoader shared] postPath:[NSString stringWithFormat:@"%@",UFSURLCategories] parameters:[NSDictionary dictionaryWithObject:token forKey:@"token"] success:^(AFHTTPRequestOperation *operation, id responseObject)
     {
//         NSNumber *err = [NSNumber numberWithInt:100];
         NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
//         errorCode = [NSString stringWithFormat:@"%@",err];
         NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
         NSLog(@"error = %@ message %@",errorCode,errorMessage);
         if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
         {
             NSLog(@"reauth categ................");
             if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
             {
//                 [[CoreDataManager shared] removeDataWithEntityName:@"CategoriesDB"];
//                 [[CoreDataManager shared] removeDataWithEntityName:@"SubCatDB"];
                  NSLog(@"reauth categ begins...............");
                 [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
             }
             
         }
         else
         {
             NSLog(@"successs categ................");
             
             [DataTransformer parseData:responseObject userInfo:@{@"type":@(DTOperationTypeRubrics)}];
             [UFSLoader requestPostMainNews:@"" CategoryId:@"16" andSubCategoryId:@"" andNewsID:@""];
             /* tsv */[[NSNotificationCenter defaultCenter] postNotificationName:kNotificationCategories object:nil];
         }
        
     } failure:^(AFHTTPRequestOperation *operation, NSError *error)
     {
         [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
         NSLog(@"Fail................categ%@",error.localizedFailureReason);
     }];
    }
    else
    {
//        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }
}

+(void)requestPostMainNews:(NSString *)dateFrom CategoryId:(NSString *)catId andSubCategoryId:(NSString *)subCatId andNewsID:(NSString *)newsId
{
    NSString *dateLimit = @"";
    if ([dateFrom rangeOfString:@" "].location!=NSNotFound)
    {
        dateLimit = [dateFrom substringFromIndex:[dateFrom rangeOfString:@" "].location];
        dateFrom = [dateFrom substringToIndex:[dateFrom rangeOfString:@" "].location];
    }
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    
    NSString *date = dateFrom.length?dateFrom:[NSString stringWithFormat:@"%@",[NSNumber numberWithInt:[[NSDate date] timeIntervalSince1970]]];
    NSString *token=[userDef valueForKey:kTokenForSession];
//    token=@"11111sssded2ed";
    NSDictionary *params = nil;
        
        //params = [NSDictionary dictionaryWithObjects:@[catId,subCatId,newsId, date,dateLimit,@"20",token]
        /* tsv */params = [NSDictionary dictionaryWithObjects:@[catId,subCatId,newsId, date,dateLimit,@"10",token]
        forKeys:@[@"categoryID",@"subcategoryID",@"newsID",@"timestamp",@"limitDateTime",@"offset",@"token"]];
//    NSLog(@"params = %@",params);
        if (token.length)
    {
        [[UFSLoader shared] postPath:UFSURLNews parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSLog(@"successs news main................");
             NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
             //         errorCode = [NSString stringWithFormat:@"%@",err];
             NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
             NSLog(@"error = %@ message = %@",errorCode,errorMessage);
             if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
             {
                 NSLog(@"reauth categ................");
                 if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [errorCode isEqualToString:@"100"])
                 {
                     [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                 }

             }
//             [[CoreDataManager shared] removeDataOlderThanDate:[NSDate date]];
             //         NSDictionary *resp = [JSONDecoder.decoder objectWithData:responseObject];
             [DataTransformer parseData:responseObject userInfo:@{@"type":@(DTOperationTypeNews)}];
             
         } failure:^(AFHTTPRequestOperation *operation, NSError *error)
         {
              [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
             NSLog(@"Fail................%@",error.localizedFailureReason);
         }];
    }
    else
    {
//        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }
}
+(void)requestPostTableNewsWithSubCategoryId:(NSString *)subCatId
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    
    NSString *token=[userDef valueForKey:kTokenForSession];
    NSDictionary *params = nil;
    if (subCatId.length)
    {
        params = [NSDictionary dictionaryWithObjects:@[subCatId,token] forKeys:@[@"subcategoryID", @"token"]];
    }
    //NSLog(@"%@",params);
    if (token.length)
    {
        [[UFSLoader shared] postPath:UFSURLTableNews parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
             //         errorCode = [NSString stringWithFormat:@"%@",err];
             NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
             NSLog(@"error = %@ message %@",errorCode,errorMessage);
             if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
             {
                 NSLog(@"reauth categ................");
                 if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
                 {
                     //                 [[CoreDataManager shared] removeDataWithEntityName:@"CategoriesDB"];
                     //                 [[CoreDataManager shared] removeDataWithEntityName:@"SubCatDB"];
                     NSLog(@"reauth categ begins...............");
                     [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                 }
                 
             }
             else
             {

             NSLog(@"successs tables...............");
             //         NSDictionary *resp = [JSONDecoder.decoder objectWithData:responseObject];
             [DataTransformer parseData:responseObject userInfo:@{@"type":@(DTOperationTypeTables)}];
             }
         } failure:^(AFHTTPRequestOperation *operation, NSError *error)
         {
             NSLog(@"Fail................%@",error.localizedFailureReason);
         }];
    }
    else
    {
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }
}

+(void)requestPostDebtMarketWithSubCategoryId:(NSString *)subCatId
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    
    NSString *token=[userDef valueForKey:kTokenForSession];
    NSDictionary *params = nil;
    if (subCatId.length)
    {
        params = [NSDictionary dictionaryWithObjects:@[subCatId,token] forKeys:@[@"subcategoryID", @"token"]];
    }
    if (token.length)
    {
        [[UFSLoader shared] postPath:UFSURLDebtMarket parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
             //         errorCode = [NSString stringWithFormat:@"%@",err];
             NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
             NSLog(@"error = %@ message %@",errorCode,errorMessage);
             if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
             {
                 NSLog(@"reauth categ................");
                 if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
                 {
                     //                 [[CoreDataManager shared] removeDataWithEntityName:@"CategoriesDB"];
                     //                 [[CoreDataManager shared] removeDataWithEntityName:@"SubCatDB"];
                     NSLog(@"reauth categ begins...............");
                     [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                 }
                 
             }
             else
             {

             NSLog(@"successs groups...............");
             //         NSDictionary *resp = [JSONDecoder.decoder objectWithData:responseObject];
             [DataTransformer parseData:responseObject userInfo:@{@"type":@(DTOperationTypeGroups)}];
             }
         } failure:^(AFHTTPRequestOperation *operation, NSError *error)
         {
             NSLog(@"Fail................%@",error.localizedFailureReason);
         }];
    }
    else
    {
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }
}

+(void)requestPostContacts
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    
    NSString *token=[userDef valueForKey:kTokenForSession];
    NSDictionary *params = [NSDictionary dictionaryWithObjects:@[token] forKeys:@[@"token"]];
    if (token.length)
    {
        [[UFSLoader shared] postPath:UFSURLBranches parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
             //         errorCode = [NSString stringWithFormat:@"%@",err];
             NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
             NSLog(@"error = %@ message %@",errorCode,errorMessage);
             if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
             {
                 NSLog(@"reauth categ................");
                 if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
                 {
                     //                 [[CoreDataManager shared] removeDataWithEntityName:@"CategoriesDB"];
                     //                 [[CoreDataManager shared] removeDataWithEntityName:@"SubCatDB"];
                     NSLog(@"reauth categ begins...............");
                     [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                 }
                 
             }
             else
             {

             NSLog(@"successs contacts...............");
             //         NSDictionary *resp = [JSONDecoder.decoder objectWithData:responseObject];
             [DataTransformer parseData:responseObject userInfo:@{@"type":@(DTOperationTypeContacts)}];
             }
         } failure:^(AFHTTPRequestOperation *operation, NSError *error)
         {
             NSLog(@"Fail................%@",error.localizedFailureReason);
         }];
    }
    else
    {
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }

}

+ (void)requestPostActionsWithCategoryIdentifier:(NSString *)categoryId
{
	NSUserDefaults *userDefaults = [NSUserDefaults standardUserDefaults];
	NSString *token = [userDefaults valueForKey:kTokenForSession];
	NSDictionary *params = [NSDictionary dictionaryWithObjects:@[token, categoryId] forKeys:@[@"token", @"categoryID"]];
	
	if (token.length)
	{
		[[UFSLoader shared] postPath:UFSURLActivities parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
		{
            NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
            //         errorCode = [NSString stringWithFormat:@"%@",err];
            NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
            NSLog(@"error = %@ message %@",errorCode,errorMessage);
            if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
            {
                NSLog(@"reauth categ................");
                if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
                {
                    //                 [[CoreDataManager shared] removeDataWithEntityName:@"CategoriesDB"];
                    //                 [[CoreDataManager shared] removeDataWithEntityName:@"SubCatDB"];
                    NSLog(@"reauth categ begins...............");
                    [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                } else {
                    [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationDataNotFound object:errorMessage];
                }
                
            }
            else
            {

                NSLog(@"Success actions.....");
//              NSLog(@"Actions response: %@", responseObject);
                [DataTransformer parseData:responseObject userInfo:@{ @"type" : @(DTOperationTypeStock) }];
            }
		}
		failure:^(AFHTTPRequestOperation *operation, NSError *error)
		{
			NSLog(@"Fail actions.....%@", error.localizedFailureReason);
		}];
	}
	else
	{
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
		[UFSLoader requestPostAuth:@"" andWidth:@""];
	}
}

+ (void)requestPostHTMLDataWithCategoryIdentifier:(NSString *)categoryId andSubCategoryId:(NSString *)subCatId
{
    NSUserDefaults *userDefaults = [NSUserDefaults standardUserDefaults];
	NSString *token = [userDefaults valueForKey:kTokenForSession];
	NSDictionary *params = [NSDictionary dictionaryWithObjects:@[token, categoryId, subCatId] forKeys:@[@"token", @"categoryID", @"subcategoryID"]];
	
	if (token.length)
	{
		[[UFSLoader shared] postPath:UFSURLHTMLData parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSLog(@"Success html data.....");
             //			NSLog(@"Actions response: %@", responseObject);
             [DataTransformer parseData:responseObject userInfo:@{ @"type" : @(DTOperationTypeHTML) }];
         }
                             failure:^(AFHTTPRequestOperation *operation, NSError *error)
         {
             NSLog(@"Fail html data.....%@", error.localizedFailureReason);
         }];
	}
	else
	{
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
		[UFSLoader requestPostAuth:@"" andWidth:@""];
	}

}
+(AFHTTPRequestOperation *)requestGetFile:(NSString *)url AndName:(NSString *)fileName
{

    AFHTTPRequestOperation *operationGetFile = [[AFHTTPRequestOperation alloc] initWithRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:[NSString stringWithFormat:kServerBasePath,url]]]];

    [[UFSLoader shared] enqueueHTTPRequestOperation:operationGetFile];
    [operationGetFile setCompletionBlockWithSuccess:^(AFHTTPRequestOperation *operation, id responseObject) {
        [FileSystem storeData:responseObject withPath:fileName];
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationFileLoaded object:nil];
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        if ([error code]!=-999)
            [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationFileLoadFailed object:nil];
    }];
    return operationGetFile;
}

+(void)stopAndClear
{
    [[[UFSLoader shared] operationQueue] cancelAllOperations];
}

+(AFHTTPRequestOperation *)getImage:(NSString *)image AndName:(NSString *)name
{
    AFHTTPRequestOperation *operationGetImage = [[AFHTTPRequestOperation alloc] initWithRequest:[NSURLRequest requestWithURL:[NSURL URLWithString:[NSString stringWithFormat:kServerBasePath,image]]]];
    
    [[UFSLoader shared] enqueueHTTPRequestOperation:operationGetImage];
    [operationGetImage setCompletionBlockWithSuccess:^(AFHTTPRequestOperation *operation, id responseObject)
    {
        [FileSystem storeData:responseObject withPath:name];
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationImageLoaded object:name];
    } failure:^(AFHTTPRequestOperation *operation, NSError *error)
    {
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationImageLoadFailed object:nil];
    }];
    return operationGetImage;
}

+(AFJSONRequestOperation *)requestGetDateForNewsWithCategoryId:(NSString *)catId AndSubCatId:(NSString *)subCatId
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    
    NSString *token=[userDef valueForKey:kTokenForSession];
    NSDictionary *params = [NSDictionary dictionaryWithObjects:@[catId, subCatId, token] forKeys:@[@"categoryID",@"subcategoryID", @"token"]];
    
        params = [NSDictionary dictionaryWithObjects:@[catId, subCatId, token] forKeys:@[@"categoryID",@"subcategoryID", @"token"]];
   
    AFJSONRequestOperation *operationGetDate = [[[AFJSONRequestOperation alloc] initWithRequest:[[UFSLoader shared] requestWithMethod:@"POST" path:UFSURLDatesOfNews parameters:params]] autorelease];

                                                
    return operationGetDate;

}

/* tsv */
+(void)requestPostQRCode:(NSString *)text
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    NSString *token=[userDef valueForKey:kTokenForSession];
    
    if (token.length)
    {
        NSDictionary *params = [NSDictionary dictionaryWithObjects:@[text, token] forKeys:@[@"text", @"token"]];
        [[UFSLoader shared] postPath:UFSURLQRCode parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
             NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
             
             NSLog(@"error = %@ message %@",errorCode,errorMessage);
             
             if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
             {
                 NSLog(@"reauth qrcode................");
                 if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
                 {
              
                     NSLog(@"reauth qrcode begins...............");
                     [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                 }
                 
             }
             else
             {
                 NSLog(@"successs qrcode...............");
                 [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationQRCode object:responseObject];
                 //[DataTransformer parseData:responseObject userInfo:@{@"type":@(DTOperationTypeQRCode)}];
             }
         } failure:^(AFHTTPRequestOperation *operation, NSError *error)
         {
             NSLog(@"Fail................%@",error.localizedFailureReason);
         }];
    }
    else
    {
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }
    
}

+(void)requestPostPromotion:(NSString *)promotionID accepted:(NSString *)accepted
                       name:(NSString*)name phone:(NSString*)phone email:(NSString*)email
                  brokerage:(NSString*)brokerage yield:(NSString*)yield
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    NSString *token=[userDef valueForKey:kTokenForSession];
    
    if (token.length)
    {
        NSDictionary *params = [NSDictionary dictionaryWithObjects:@[promotionID,accepted,name,phone,email,brokerage,yield,token]
                                                           forKeys:@[@"promotionID",@"accepted",@"name",@"phone",@"email",@"brokerage",@"yield",@"token"]];

        [[UFSLoader shared] postPath:UFSURLPromotion parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
             NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
             
             NSLog(@"error = %@ message %@",errorCode,errorMessage);
             
             if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
             {
                 NSLog(@"reauth promotion................");
                 if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
                 {
                     
                     NSLog(@"reauth promotion begins...............");
                     [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                 }
                 
             } else {
                 
                 NSLog(@"successs promotion...............");
                 [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationPromotion object:responseObject];
            }
         } failure:^(AFHTTPRequestOperation *operation, NSError *error)
         {
             NSLog(@"Fail................%@",error.localizedFailureReason);
         }];
    }
    else
    {
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }
    
}

+(void)requestKeywords
{
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    NSString *token=[userDef valueForKey:kTokenForSession];
    
    if (token.length) {
        
        NSDictionary *params = [NSDictionary dictionaryWithObjects:@[token] forKeys:@[@"token"]];
        [[UFSLoader shared] postPath:UFSURLKeywords parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject)
         {
             NSString *errorCode = [NSString stringWithFormat:@"%@",[[responseObject objectForKey:@"error"] objectForKey:@"code"] ];
             NSString *errorMessage = ((NSString *)[[responseObject objectForKey:@"error"] objectForKey:@"message"]);
             NSLog(@"error = %@ message %@",errorCode,errorMessage);
             if (errorCode.length || ![[responseObject objectForKey:@"result"] allObjects].count)
             {
                 NSLog(@"reauth keywords................");
                 if ([[errorMessage lowercaseString] rangeOfString:@"invalid"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"locked"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"token"].location!=NSNotFound || [[errorMessage lowercaseString] rangeOfString:@"not found"].location!=NSNotFound ||[errorCode isEqualToString:@"100"])
                 {
                     NSLog(@"reauth keywords begins...............");
                     [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:errorMessage];
                 }
                 
             } else {
                 
                 NSLog(@"successs keywords...............");
                 [DataTransformer parseData:responseObject userInfo:@{@"type":@(DTOperationTypeKeywords)}];
             }
         } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
             NSLog(@"Fail................%@",error.localizedFailureReason);
         }];
        
    } else {
        [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationRequestFaild object:nil];
        [UFSLoader requestPostAuth:@"" andWidth:@""];
    }
}

/* tsv */

@end
