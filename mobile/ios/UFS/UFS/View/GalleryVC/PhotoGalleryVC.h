//
//  PhotoGalleryVC.h
//
//  Copyright 2012 id-East. All rights reserved.
//

#import <UIKit/UIKit.h>


// PhotoGalleryVC
@interface PhotoGalleryVC :UIViewController <UIScrollViewDelegate, NSFetchedResultsControllerDelegate>
{
    
    NSString       *descriptionInf;
    BOOL shouldUpdate;
    BOOL           backTapped;
    NSArray *photoArray;
    NSInteger currentIndexOfFoto;
    NSFetchedResultsController *fetchedResultsController;
    NSArray  *_permissions;
    NSMutableData *_responseText;
    NSMutableDictionary *_data;
    NSUserDefaults *defaults;
    UIButton *favor_button;
}

// Properties
@property (nonatomic) BOOL displayActionButton;
@property (nonatomic, retain) NSString *titleGallery;

@property (nonatomic, retain) NSFetchedResultsController *fetchedResultsController;
@property (strong, nonatomic) NSNumber *trId;
@property (strong, nonatomic) NSNumber *previewId;
@property (strong, nonatomic) NSString *imageAdress;
@property (strong, nonatomic) NSString *textToPost;




// Init
- (id)initWithItems:(NSArray *)objectsArray;
- (id)initWithObject;

// Reloads the photoScroll and refetches data
- (void)reloadData;

// Set page
- (void)setInitialPageIndex:(NSUInteger)index;
// Get page Number;
- (int)getCurrentPageNumber;

- (id)getCurrentObject;

- (void)setObjects:(id)newObject;
@end


