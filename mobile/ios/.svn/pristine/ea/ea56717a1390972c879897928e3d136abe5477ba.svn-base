//
//  DistimoSDK.h
//  DistimoSDK
//
//  Created by Arne de Vries on 4/6/12.
//  Copyright (c) 2012 Distimo. All rights reserved.
//
//   Licensed under the Apache License, Version 2.0 (the "License");
//   you may not use this file except in compliance with the License.
//   You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
//   Unless required by applicable law or agreed to in writing, software
//   distributed under the License is distributed on an "AS IS" BASIS,
//   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//   See the License for the specific language governing permissions and
//   limitations under the License.
//

#import <Foundation/Foundation.h>
#import <UIKit/UIKit.h>

#define kDistimoSDKOpenAppLinkSuccessNotification @"kDistimoSDKOpenAppLinkSuccessNotification"
#define kDistimoSDKOpenAppLinkFailedNotification @"kDistimoSDKOpenAppLinkFailedNotification"


@protocol SKStoreProductViewControllerDelegate;


@interface DistimoSDK : NSObject

/**
 * Retrieve the version of the SDK
 *
 * @return the version of the SDK
 */
+ (NSString *)version;

/**
 * Start the SDK. Call this method at the top of your application:didFinishLaunchingWithOptions:
 *  method in your AppDelegate class
 * 
 * @param launchOptions The launchOptions provided by your application:didFinishLaunchingWithOptions: method
 * @param sdkKey Your Distimo SDK Key, go to https://analytics.distimo.com/settings/sdk to generate an SDK Key.
 **/
+ (BOOL)handleLaunchWithOptions:(NSDictionary *)launchOptions
						 sdkKey:(NSString *)sdkKey;

#pragma mark User Value

/**
 * Mark this user as newly registered
 **/
+ (void)logUserRegistered;

/**
 * Log an in-app purchase that this user completed, specified with a price-locale
 *
 * @param productID The productID of the in-app purchase
 * @param priceLocale The locale of the price for the purchase
 * @param price The price of the product
 * @param quantity Number of purchased products
 **/
+ (void)logInAppPurchaseWithProductID:(NSString *)productID
						  priceLocale:(NSLocale *)priceLocale
								price:(double)price
							 quantity:(int)quantity;

/**
 * Log an in-app purchase that this user completed, specified with an ISO 4217 international
 * currency symbol
 *
 * @param productID The productID of the in-app purchase
 * @param currencyCode The ISO 4217 currency code for the currency used for this purchase
 * @param price The price of the product
 * @param quantity Number of purchased products
 **/
+ (void)logInAppPurchaseWithProductID:(NSString *)productID
						 currencyCode:(NSString *)currencyCode
								price:(double)price
							 quantity:(int)quantity;

/**
 * Log an external purchase that this user completed, e.g. consumer goods or a booking,
 * specified with a price-locale
 *
 * @param productID The productID of the external purchase
 * @param priceLocale The locale of the price for the purchase
 * @param price The price of the product
 * @param quantity Number of purchased products
 **/
+ (void)logExternalPurchaseWithProductID:(NSString *)productID
							 priceLocale:(NSLocale *)priceLocale
								   price:(double)price
								quantity:(int)quantity;

/**
 * Log an external purchase that this user completed, e.g. consumer goods or a booking,
 *  specified with an ISO 4217 international currency symbol
 *
 * @param productID The productID of the external purchase
 * @param currencyCode The ISO 4217 currency code for the currency used for this purchase
 * @param price The price of the product
 * @param quantity Number of purchased products
 **/
+ (void)logExternalPurchaseWithProductID:(NSString *)productID
							currencyCode:(NSString *)currencyCode
								   price:(double)price
								quantity:(int)quantity;

/**
 * Log a banner click
 *
 * @param publisher The publisher of the banner (optional)
 **/
+ (void)logBannerClickWithPublisher:(NSString *)publisher;

#pragma mark User Properties

/**
 * Set a self-defined userID for this user. This userID is used to provide you with detailed
 *  source information that this user originated from.
 *
 * @param userID Your self-defined userID of this user
 **/
+ (void)setUserID:(NSString *)userID;

#pragma mark AppLink

/**
 * Redirects directly to the AppStore by routing through your AppLink. Use this for tracking
 *  conversion from within your own apps, e.g. for upselling to your Pro apps.
 *
 * Note: The redirect will happen in the background, this can take a couple of seconds.
 * Note: Tracking conversion using this method will only work for apps of the same publisher.
 *
 * - Upon success a kDistimoSDKOpenAppLinkSuccessNotification is posted
 * - Upon failure a kDistimoSDKOpenAppLinkFailedNotification is posted
 *
 * @param applinkHandle The handle of the AppLink you want to open, e.g. @"A00"
 * @param campaignHandle The handle of the campaign you want to use, e.g. @"a" (optional)
 * @param viewController The viewController that should present the SKStoreProductViewController instance (optional)
 **/
+ (void)openAppLink:(NSString *)applinkHandle
		   campaign:(NSString *)campaignHandle
   inViewController:(UIViewController<SKStoreProductViewControllerDelegate> *)viewController;

@end
