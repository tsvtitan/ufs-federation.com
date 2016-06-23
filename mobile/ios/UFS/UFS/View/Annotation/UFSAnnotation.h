//
//  UFSAnnotation.h
//  UFS
//
//  Created by mihail on 08.11.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <MapKit/MapKit.h>

@interface UFSAnnotation : NSObject <MKAnnotation>
@property (copy,nonatomic) NSString *title;
@property (copy,nonatomic) NSString *subtitle;
@property (assign, nonatomic) CLLocationCoordinate2D coordinate;
@end

@interface UFSAnnotationView : MKPinAnnotationView

@end