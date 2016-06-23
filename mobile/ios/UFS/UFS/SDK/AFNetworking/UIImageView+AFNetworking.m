// UIImageView+AFNetworking.m
//
// Copyright (c) 2011 Gowalla (http://gowalla.com/)
//
// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:
//
// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
// THE SOFTWARE.

#import <Foundation/Foundation.h>
#import <objc/runtime.h>

#if defined(__IPHONE_OS_VERSION_MIN_REQUIRED)
#import "UIImageView+AFNetworking.h"

@interface AFImageCache : NSCache
- (UIImage *)cachedImageForRequest:(NSURLRequest *)request;
- (void)cacheImage:(UIImage *)image
        forRequest:(NSURLRequest *)request;
@end

#pragma mark - 

static char kAFImageRequestOperationObjectKey;

@interface UIImageView (_AFNetworking)
@property (readwrite, nonatomic, strong, setter = af_setImageRequestOperation:) AFImageRequestOperation *af_imageRequestOperation;
@end

@implementation UIImageView (_AFNetworking)
@dynamic af_imageRequestOperation;
@end

#pragma mark -

@implementation UIImageView (AFNetworking)

- (AFHTTPRequestOperation *)af_imageRequestOperation {
    return (AFHTTPRequestOperation *)objc_getAssociatedObject(self, &kAFImageRequestOperationObjectKey);
}

- (void)af_setImageRequestOperation:(AFImageRequestOperation *)imageRequestOperation {
    objc_setAssociatedObject(self, &kAFImageRequestOperationObjectKey, imageRequestOperation, OBJC_ASSOCIATION_RETAIN_NONATOMIC);
}

+ (NSOperationQueue *)af_sharedImageRequestOperationQueue {
    static NSOperationQueue *_af_imageRequestOperationQueue = nil;
    static dispatch_once_t onceToken;
    dispatch_once(&onceToken, ^{
        _af_imageRequestOperationQueue = [[NSOperationQueue alloc] init];
        [_af_imageRequestOperationQueue setMaxConcurrentOperationCount:NSOperationQueueDefaultMaxConcurrentOperationCount];
    });

    return _af_imageRequestOperationQueue;
}

+ (AFImageCache *)af_sharedImageCache {
    static AFImageCache *_af_imageCache = nil;
    static dispatch_once_t oncePredicate;
    dispatch_once(&oncePredicate, ^{
        _af_imageCache = [[AFImageCache alloc] init];
    });

    return _af_imageCache;
}

#pragma mark -

- (void)setImageWithURL:(NSURL *)url {
    [self setImageWithURL:url placeholderImage:nil];
}

- (void)setImageWithURL:(NSURL *)url
       placeholderImage:(UIImage *)placeholderImage
{
    NSMutableURLRequest *request = [NSMutableURLRequest requestWithURL:url];
    [request addValue:@"image/*" forHTTPHeaderField:@"Accept"];
    
    [self setImageWithURLRequest:request placeholderImage:placeholderImage success:nil failure:nil];
}

- (void)setImageWithURLRequest:(NSURLRequest *)urlRequest
              placeholderImage:(UIImage *)placeholderImage
                       success:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, UIImage *image))success
                       failure:(void (^)(NSURLRequest *request, NSHTTPURLResponse *response, NSError *error))failure
{
    [self cancelImageRequestOperation];
//     [self setAllowsInvalidSSLCertificate:YES];
    
    self.image = placeholderImage;
    
//    NSData *imageData = [FileSystem dataWithPath:urlRequest.URL.absoluteString];
//    if (imageData)
//    {
//        NSLog(@"image complete");
//        UIImage *image = [UIImage imageWithData:imageData];
//        self.image = image;
//        if (success)
//            success(nil, nil, image);
//        
//        if ([self respondsToSelector:@selector(af_setImageRequestOperation:)])
//            [self performSelector:@selector(af_setImageRequestOperation:) withObject:nil];
//    }
    UIImage *imageData = [FileSystem uncachedImageWithPath:urlRequest.URL.absoluteString];
    if (imageData)
    {
        NSLog(@"image complete");
//        UIImage *image = [UIImage imageWithData:imageData];
        self.image = imageData;
        if (success)
            success(nil, nil, imageData);
        
        if ([self respondsToSelector:@selector(af_setImageRequestOperation:)])
            [self performSelector:@selector(af_setImageRequestOperation:) withObject:nil];
    }

    AFImageRequestOperation *requestOperation = [[AFImageRequestOperation alloc] initWithRequest:urlRequest];
    [requestOperation setAllowsInvalidSSLCertificate:YES];

    [requestOperation setCompletionBlockWithSuccess:^(AFHTTPRequestOperation *operation, id responseObject)
     {
         NSLog(@"Image Download %@",urlRequest.URL.absoluteString);
         if (responseObject)
         {
             NSLog(@"response obj");
             [FileSystem  storeData:UIImagePNGRepresentation(responseObject)  withPath:urlRequest.URL.absoluteString];
             if ([[urlRequest URL] isEqual:[[self.af_imageRequestOperation request] URL]])
             {
                 self.image = responseObject;
                 /*if (success)
                     success(operation.request, operation.response, responseObject);*/
                 
                 if ([self respondsToSelector:@selector(af_setImageRequestOperation:)])
                     [self performSelector:@selector(af_setImageRequestOperation:) withObject:nil];
             }
         }
         //            [[[self class] af_sharedImageCache] cacheImage:responseObject forRequest:urlRequest];
     } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
         NSLog(@"Image Download Faild %@",urlRequest.URL.absoluteString);
         NSLog(@"%@",error);

         if ([[urlRequest URL] isEqual:[[self.af_imageRequestOperation request] URL]]) {
             if (failure)
                 failure(operation.request, operation.response, error);
             if ([self respondsToSelector:@selector(af_setImageRequestOperation:)])
                 [self performSelector:@selector(af_setImageRequestOperation:) withObject:nil];
         }
     }];


        if ([self respondsToSelector:@selector(af_setImageRequestOperation:)]) {
            [self performSelector:@selector(af_setImageRequestOperation:) withObject:requestOperation];
        }

        [[[self class] af_sharedImageRequestOperationQueue] addOperation:self.af_imageRequestOperation];
}

- (void)cancelImageRequestOperation {
    [self.af_imageRequestOperation cancel];
    self.af_imageRequestOperation = nil;
}

@end

#pragma mark -

static inline NSString * AFImageCacheKeyFromURLRequest(NSURLRequest *request) {
    return [[request URL] absoluteString];
}

@implementation AFImageCache

- (UIImage *)cachedImageForRequest:(NSURLRequest *)request {
    switch ([request cachePolicy]) {
        case NSURLRequestReloadIgnoringCacheData:
        case NSURLRequestReloadIgnoringLocalAndRemoteCacheData:
            return nil;
        default:
            break;
    }

	return [self objectForKey:AFImageCacheKeyFromURLRequest(request)];
}

- (void)cacheImage:(UIImage *)image
        forRequest:(NSURLRequest *)request
{
    if (image && request) {
        [self setObject:image forKey:AFImageCacheKeyFromURLRequest(request)];
    }
}

@end

#endif
