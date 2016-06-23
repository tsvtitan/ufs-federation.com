//
//  FileSystem.m
//  Copyright 2010 iD EAST. All rights reserved.
//

#import "FileSystem.h"


static NSString *kCachePath = nil;
static NSString *kCachePathPdf = nil;
static NSString *kTempCachePath = nil;


@implementation NSString (FileSystem)

- (NSString *)standarizedString
{
	if ([self length] == 0)
	{
		return self;
	}
	
    NSString *retString = [[self copy] autorelease];
    
    NSRange range;
    range.location = [retString length] - 1;
    range.length = 1;
    if([[retString substringWithRange:range] isEqualToString:@"/"])
    {
        range.location = 0;
        range.length = [retString length] - 1;
        retString = [retString substringWithRange:range];
    }
    
    range = [retString rangeOfString:@"https://"];
    if(range.location != NSNotFound)
    {
        range.location = range.length;
        range.length = [retString length] - range.length;
        retString = [retString substringWithRange:range];
    }
    retString = [retString stringByReplacingOccurrencesOfString:@"/" withString:@"__"];
//    retString = [retString stringByReplacingOccurrencesOfString:@"?" withString:@"%3F"];


    // removing parameters
//    range = [retString rangeOfString:@"?"];
//    retString = [retString substringToIndex:range.location];

    return retString;
}

@end


@implementation FileSystem

+ (void)initialize
{
	NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
	if (!paths)
	{
		return;
	}

	// Create cache path
	kCachePath = [[[paths objectAtIndex:0] stringByAppendingPathComponent:@"iDEastCache"] retain];
    kCachePathPdf = [[[paths objectAtIndex:0] stringByAppendingPathComponent:@"iDEastCachePdf"] retain];
	NSLog(@"kCachePath = %@", kCachePath);
    NSLog(@"kCachePathPdf = %@", kCachePathPdf);

	if (![[NSFileManager defaultManager] fileExistsAtPath:kCachePath])
	{
		NSError *error = nil;
		[[NSFileManager defaultManager] createDirectoryAtPath:kCachePath
								  withIntermediateDirectories:NO
												   attributes:nil
														error:&error];
		if(error)
		{
			NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
		}
	}
    if (![[NSFileManager defaultManager] fileExistsAtPath:kCachePathPdf])
	{
		NSError *error = nil;
		[[NSFileManager defaultManager] createDirectoryAtPath:kCachePathPdf
								  withIntermediateDirectories:NO
												   attributes:nil
														error:&error];
		if(error)
		{
			NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
		}
	}
	
	// Create temp cache path
	kTempCachePath = [[[paths objectAtIndex:0] stringByAppendingPathComponent:@"iDEastCacheTemporary"] retain];
	NSLog(@"kTempCachePath = %@", kTempCachePath);
	
	if (![[NSFileManager defaultManager] fileExistsAtPath:kTempCachePath])
	{
		NSError *error = nil;
		[[NSFileManager defaultManager] createDirectoryAtPath:kTempCachePath
								  withIntermediateDirectories:NO
												   attributes:nil
														error:&error];
		if(error)
		{
			NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
		}
	}
}

#pragma mark - Store

+ (void)storeData:(NSData *)data withPath:(NSString *)pathString
{
	if(!data)
	{
		NSLog(@"(!) Error storing data: data is nil");
		return;
	}
    
    pathString = [pathString standarizedString];
	
	NSString *path = kCachePath;
    if ([pathString rangeOfString:@"files"].location!=NSNotFound)
    {
        path = kCachePathPdf;
    }
	for(int i = 0; i < [[pathString pathComponents] count] - 1; i += 1)
	{
		path = [path stringByAppendingPathComponent:[[pathString pathComponents] objectAtIndex:i]];
		if(![[NSFileManager defaultManager] fileExistsAtPath:path])
		{
			NSError *error = nil;
			[[NSFileManager defaultManager] createDirectoryAtPath:path
									  withIntermediateDirectories:NO
													   attributes:nil
															error:&error];
			if(error)
			{
				NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
			}
		}
	}
	
	path = [NSString stringWithFormat:@"%@/%@", kCachePath, pathString];
    
    if ([pathString rangeOfString:@"file"].location!=NSNotFound)
    {
        path =[NSString stringWithFormat:@"%@/%@", kCachePathPdf, pathString];
    }

	
    NSDictionary *fileAttributes = [NSDictionary dictionaryWithObject:[NSDate date] forKey:NSFileModificationDate];
	if(![[NSFileManager defaultManager] createFileAtPath:path contents:data attributes:fileAttributes])
	{
		NSLog(@"Error storing data on path: %@", path);
	}
}

#pragma mark - Get

+ (BOOL)pathExisted:(NSString *)pathString
{
    pathString = [pathString standarizedString];

	NSString *path = [NSString stringWithFormat:@"%@/%@", kCachePath, pathString];
    if ([pathString rangeOfString:@"files"].location!=NSNotFound)
    {
        path =[NSString stringWithFormat:@"%@/%@", kCachePathPdf, pathString];
    }

	return [[NSFileManager defaultManager] fileExistsAtPath:path];
}

+ (NSData *)dataWithPath:(NSString *)pathString
{
    pathString = [pathString standarizedString];

	NSString *path = [NSString stringWithFormat:@"%@/%@", kCachePath, pathString];
    
    if ([pathString rangeOfString:@"files"].location!=NSNotFound)
    {
        path =[NSString stringWithFormat:@"%@/%@", kCachePathPdf, pathString];
    }


	return [[NSFileManager defaultManager] contentsAtPath:path];
}

+ (UIImage *)imageWithPath:(NSString *)pathString
{
	pathString = [pathString standarizedString];

	NSString *path = [NSString stringWithFormat:@"%@/%@", kCachePath, pathString];
	if ([pathString rangeOfString:@"files"].location!=NSNotFound)
    {
        path = [NSString stringWithFormat:@"%@/%@", kCachePathPdf, pathString];
    }
	return [UIImage imageWithContentsOfFile:path];
}

+ (UIImage *)uncachedImageWithPath:(NSString *)pathString
{
	pathString = [pathString standarizedString];
	NSString *path = [NSString stringWithFormat:@"%@/%@", kCachePath, pathString];
    if ([pathString rangeOfString:@"files"].location!=NSNotFound)
    {
        path =[NSString stringWithFormat:@"%@/%@", kCachePathPdf, pathString];
    }
	NSData *data = [NSData dataWithContentsOfFile:path options:NSDataReadingUncached error:nil];
//	NSFileHandle *file = [NSFileHandle fileHandleForReadingAtPath:path];
//	NSData *data = [file readDataToEndOfFile];
	
    return [UIImage imageWithData:data];
}

+ (NSString *)stringWithPath:(NSString *)pathString
{
    pathString = [pathString standarizedString];

    NSString *path = [NSString stringWithFormat:@"%@/%@", kCachePath, pathString];
    if ([pathString rangeOfString:@"files"].location!=NSNotFound)
    {
        path =[NSString stringWithFormat:@"%@/%@", kCachePathPdf, pathString];
    }

	
	return [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
}

#pragma mark - Delete
+ (void)removeFilesOlderThanDate:(NSDate *)date {
    
    if (date) {
       
        // Удаляем файлы, старше указанной даты
        dispatch_async(dispatch_get_global_queue(DISPATCH_QUEUE_PRIORITY_HIGH, 0), ^{
            
            // Берем массив всех ссылок на обьекты в файловой системе
            NSArray *allPaths = [[NSFileManager defaultManager] contentsOfDirectoryAtPath:kCachePath error:nil];
            for (NSString *path in allPaths) {
                
                // Получаем полный путь до обьекта в файловой системе
                NSString *fullPath = [kCachePath stringByAppendingPathComponent:path];
                
                //Получаем дату последнего изменения обьекта
                NSDate *lastModifyDate = [[[NSFileManager defaultManager] attributesOfItemAtPath:fullPath error:nil] valueForKey:NSFileModificationDate];
                if ([date compare:lastModifyDate] == NSOrderedDescending) {

                    // Если обьект давно не использовался, то удаляем его из файловой системы
                    [FileSystem removeAtPath:path];
                }
            }
        });
    }
}

+ (void)removeAtPath:(NSString *)pathString
{
    pathString = [pathString standarizedString];

	NSString *path = [NSString stringWithFormat:@"%@/%@", kCachePath, pathString];
	
	NSLog(@"%@", path);
	
	NSError *error = nil;
	[[NSFileManager defaultManager] removeItemAtPath:path error:&error];
	if(error)
	{
		NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
	}
}

+ (void)removeAll
{
	
	NSError *error = nil;
	[[NSFileManager defaultManager] removeItemAtPath:kCachePath error:&error];
	if(error)
	{
		NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
	}

	// Create empty directory
	NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
	if (!paths)
	{
		return;
	}
	kCachePath = [[[paths objectAtIndex:0] stringByAppendingPathComponent:@"iDEastCache"] retain];
	NSLog(@"kCachePath = %@", kCachePath);
	
	if (![[NSFileManager defaultManager] fileExistsAtPath:kCachePath])
	{
		NSError *error = nil;
		[[NSFileManager defaultManager] createDirectoryAtPath:kCachePath
								  withIntermediateDirectories:NO
												   attributes:nil
														error:&error];
		if(error)
		{
			NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
		}
	}
}
+ (BOOL)removePdf
{
	
	NSError *error = nil;
    BOOL complete = false;
    [[NSFileManager defaultManager] removeItemAtPath:kCachePathPdf error:&error];
	if(error)
	{
		NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
//        [self performSelectorOnMainThread:@selector(complete:) withObject:kNotificationPdfNotRemoved waitUntilDone:NO];
        complete = false;
	}
    else
    {
        
        complete = true;
    }
    
	// Create empty directory
	NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
	if (!paths)
	{
		return false;
	}
	kCachePathPdf = [[[paths objectAtIndex:0] stringByAppendingPathComponent:@"iDEastCachePdf"] retain];
	NSLog(@"kCachePathPdf = %@", kCachePath);
	
	if (![[NSFileManager defaultManager] fileExistsAtPath:kCachePathPdf])
	{
		NSError *error = nil;
		[[NSFileManager defaultManager] createDirectoryAtPath:kCachePathPdf
								  withIntermediateDirectories:NO
												   attributes:nil
														error:&error];
		if(error)
		{
			NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
		}
	}
    return complete;
}


#pragma mark - Advanced


+ (NSString *)filePathForUrlPath:(NSString *)pathString
{
    if ([pathString rangeOfString:@"files"].location!=NSNotFound)
    {
        return [NSString stringWithFormat:@"%@/%@", kCachePathPdf, [pathString standarizedString]];
    }
	return [NSString stringWithFormat:@"%@/%@", kCachePath, [pathString standarizedString]];
}

#pragma mark - Temp files

+ (BOOL)tempPathExisted:(NSString *)pathString
{
    pathString = [pathString standarizedString];
	
	NSString *path = [NSString stringWithFormat:@"%@/%@", kTempCachePath, pathString];
	
	return [[NSFileManager defaultManager] fileExistsAtPath:path];
}

+ (void)tempStoreData:(NSData *)data withPath:(NSString *)pathString
{
	if(!data)
	{
		NSLog(@"(!) Error storing data: data is nil");
		return;
	}
    
    pathString = [pathString standarizedString];
	
	NSString *path = kTempCachePath;
	for(int i = 0; i < [[pathString pathComponents] count] - 1; i += 1)
	{
		path = [path stringByAppendingPathComponent:[[pathString pathComponents] objectAtIndex:i]];
		if(![[NSFileManager defaultManager] fileExistsAtPath:path])
		{
			NSError *error = nil;
			[[NSFileManager defaultManager] createDirectoryAtPath:path
									  withIntermediateDirectories:NO
													   attributes:nil
															error:&error];
			if(error)
			{
				NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
			}
		}
	}
	
	path = [NSString stringWithFormat:@"%@/%@", kTempCachePath, pathString];
	
	if(![[NSFileManager defaultManager] createFileAtPath:path contents:data attributes:nil])
	{
		NSLog(@"Error storing data on path: %@", path);
	}
}

+ (NSString *)tempStringWithPath:(NSString *)pathString
{
    pathString = [pathString standarizedString];
	
    NSString *path = [NSString stringWithFormat:@"%@/%@", kTempCachePath, pathString];
	
	return [NSString stringWithContentsOfFile:path encoding:NSUTF8StringEncoding error:nil];
}

+ (void)removeAllTemporary
{
	
	NSError *error = nil;
	[[NSFileManager defaultManager] removeItemAtPath:kTempCachePath error:&error];
	if(error)
	{
		NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
	}
	
	// Create empty directory
	NSArray *paths = NSSearchPathForDirectoriesInDomains(NSCachesDirectory, NSUserDomainMask, YES);
	if (!paths)
	{
		return;
	}
	kTempCachePath = [[[paths objectAtIndex:0] stringByAppendingPathComponent:@"iDEastCacheTemporary"] retain];
	NSLog(@"kTempCachePath = %@", kTempCachePath);
	
	if (![[NSFileManager defaultManager] fileExistsAtPath:kTempCachePath])
	{
		NSError *error = nil;
		[[NSFileManager defaultManager] createDirectoryAtPath:kTempCachePath
								  withIntermediateDirectories:NO
												   attributes:nil
														error:&error];
		if(error)
		{
			NSLog(@"(!) Error creating directory: %@", [error localizedDescription]);
		}
	}
}

@end
