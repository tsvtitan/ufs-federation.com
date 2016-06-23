//
//  UFSAnnotation.m
//  UFS
//
//  Created by mihail on 08.11.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSAnnotation.h"

@implementation UFSAnnotation

-(void)dealloc
{
    [_title release];
    [_subtitle release];
    [super dealloc];
}
@end

@implementation UFSAnnotationView

- (id)initWithAnnotation:(id <MKAnnotation>)annotation reuseIdentifier:(NSString *)reuseIdentifier
{
    self = [super initWithAnnotation:annotation reuseIdentifier:reuseIdentifier];
    if (self)
    {
        // Set the frame size to the appropriate values.
        CGRect  myFrame = self.frame;
        myFrame.size.width = self.frame.size.width;
        myFrame.size.height = self.frame.size.height;
        self.frame = myFrame;
        
        // The opaque property is YES by default. Setting it to
        // NO allows map content to show through any unrendered
        // parts of your view.

        self.opaque = YES;
    }
    return self;
}
//- (CGSize)sizeThatFits:(CGSize)size
//{
//    return CGSizeMake(self.frame.size.width, 80);
//}

@end