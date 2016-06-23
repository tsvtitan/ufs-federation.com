//
//  PTRArrowView.h
//  Copyright (c) 2012 iD EAST. All rights reserved.
//

#import <UIKit/UIKit.h>


typedef enum {
	ReloadStageNone = 0,
	ReloadStageUp,
	ReloadStageDown,
    LoadingState
} ReloadStage;


@interface PTRArrowView : UIView
{
	UILabel  *label;
    UILabel  *labelUpdate;

    NSString *noActionText;
    NSString *actionText;
	NSString *text;
	NSString *advancedText;
    BOOL     refreshing;
    UIActivityIndicatorView *indicator;
}

@property (nonatomic, assign) UITableView *tableView;
@property (nonatomic, retain) NSNumber *dateLastUpdate;
// Float value from 0 to 1
@property (nonatomic, assign) ReloadStage stage;
@property (nonatomic, assign) CGFloat value;

@property (nonatomic, assign) id <NSObject> actionDelegate;
- (void)setActionDate:(NSNumber *)newActionDate withFlag:(BOOL)flag;
- (void)setRefreshing:(BOOL)refrash;
- (void) setActionText:(NSString *)newActionText;
@end


@interface UITableView (PullToRefresh)

- (void)appendPullToRefresh:(id)delegate;
- (void)removePullToRefresh;
- (void)setActionText:(NSString *)newActionText;
- (void)setActionDate:(NSNumber *)newActionDate;
@end