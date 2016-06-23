//
//  ContactDetailVC.h
//  UFS
//
//  Created by mihail on 08.11.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//
#define kTwitterAlert 100
#define kPhoneAlert 150
#define kEmailAlert 200
#define kSaveContactAlert 500

#import <UIKit/UIKit.h>
#import <MapKit/MapKit.h>
#import <CoreLocation/CoreLocation.h>
#import <AddressBook/AddressBook.h>
#import <MessageUI/MessageUI.h>
#import <AddressBookUI/AddressBookUI.h>
#import "UFSAnnotation.h"


@interface ContactDetailVC : UFSRootVC <UITableViewDataSource, UITableViewDelegate, MKMapViewDelegate>
{
    NSInteger height;
    UILabel *addresLable;
    UIButton *mapButton;
    NSString *nameStr;
    NSString *emailStr;
    UIScrollView *bgScroll;
    BOOL isOpen;
    UIActivityIndicatorView *indicator;
    UIImageView *imageForNotReach;
}

@property (strong, nonatomic) UITableView *contactDetailTV;
@property (strong, nonatomic) MKMapView *mapView;
@property (strong, nonatomic) ContactsDB *contact;
@property (strong, nonatomic) NSString *reqStr;

- (id)initWithContact:(ContactsDB *) contactObj;
- (void)createMapView;
@end
