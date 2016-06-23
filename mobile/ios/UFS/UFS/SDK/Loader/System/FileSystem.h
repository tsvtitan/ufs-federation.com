//
//  FileSystem.h
//  Copyright 2010 iD EAST. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>


@interface FileSystem : NSObject

// Store
+ (void)storeData:(NSData *)data withPath:(NSString *)pathString;

// Get
+ (BOOL)pathExisted:(NSString *)pathString;
+ (NSData *)dataWithPath:(NSString *)pathString;
+ (UIImage *)imageWithPath:(NSString *)pathString;
+ (UIImage *)uncachedImageWithPath:(NSString *)pathString;
+ (NSString *)stringWithPath:(NSString *)pathString;

// Delete
+ (void)removeFilesOlderThanDate:(NSDate *)date;
+ (void)removeAtPath:(NSString *)pathString;
+ (void)removeAll;
+ (BOOL)removePdf;

// Advanced
+ (NSString *)filePathForUrlPath:(NSString *)pathString;

// Temp files
+ (BOOL)tempPathExisted:(NSString *)pathString;
+ (void)tempStoreData:(NSData *)data withPath:(NSString *)pathString;
+ (NSString *)tempStringWithPath:(NSString *)pathString;
+ (void)removeAllTemporary;

@end


@interface NSString (FileSystem)

- (NSString *)standarizedString;

@end