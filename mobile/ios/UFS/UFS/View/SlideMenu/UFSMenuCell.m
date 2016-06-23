//
//  UFSMenuCell.m
//  UFS
//
//  Created by mihail on 26.08.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "UFSMenuCell.h"

@implementation UFSMenuCell

- (id)initWithStyle:(UITableViewCellStyle)style reuseIdentifier:(NSString *)reuseIdentifier
                                                           type:(NSNumber *)type
{
    self = [super initWithStyle:style reuseIdentifier:reuseIdentifier];
    if (self) {
        // Initialization code
        self.accessoryType = UITableViewCellAccessoryDisclosureIndicator;
        self.selectionStyle = UITableViewCellSelectionStyleNone;
        UILabel *stripe = [[UILabel alloc] initWithFrame:CGRectMake(0, 0, self.contentView.width, 2)];
        [stripe setBackgroundColor:RGBA(29, 71, 102, 1)];
        [self.contentView addSubview:stripe];
        [stripe release];
        _bgImageView = [[UIImageView alloc] initWithFrame:CGRectMake(0, 0, self.contentView.width, 42)];
        [_bgImageView setImage:[UIImage imageNamed:@"bg_table_normal"]];
        
        [self.contentView addSubview:_bgImageView];
        [_bgImageView release];
        self.caption = [[UILabel alloc] initWithFrame:CGRectMake(35, -3, self.contentView.width-114, self.contentView.height)];
        [self.caption setFont:[UIFont fontWithName:@"Helvetica-Bold" size:15]];
        self.caption.textColor = RGBA(188, 226, 248, 1.0f);
         self.caption.backgroundColor = [UIColor clearColor];
        //        contentView.backgroundColor = RGBA(0, 43, 79, 1.0f);
        [self.contentView addSubview:_caption];
        [self.caption release];
        UIImageView *acces = [[UIImageView alloc] initWithImage:[UIImage imageNamed:@"icn_arrow"]];
        self.accessoryView = acces;
        [acces release];
        _imageViewSC = [[SmartImageView alloc] initWithFrame:CGRectMake(0, 3, 35, 35)];
        _imageViewSC.backgroundColor = [UIColor clearColor];
        if ([_imageOfSubcategory length])
        {
            [_imageViewSC setImageWithUrlString:_imageOfSubcategory AndName:[_imageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
            [UFSLoader requestGetFile:_selectedImageOfSubcategory AndName:[_imageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
            [_imageViewSC setIndicatorHide:YES];
        }
        [self.contentView addSubview:_imageViewSC];
        [_imageViewSC release];
    }
    _type = type;
    return self;
}

- (void)setSelected:(BOOL)selected animated:(BOOL)animated
{
    [super setSelected:selected animated:animated];
    if (selected)
    {
        [_imageViewSC setImageWithUrlString:_selectedImageOfSubcategory AndName:[_selectedImageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
        [_bgImageView setImage:[UIImage imageNamed:@"bg_table_selected"]];
    }
    else
    {
        [_imageViewSC setImageWithUrlString:_imageOfSubcategory AndName:[_imageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
        [_bgImageView setImage:[UIImage imageNamed:@"bg_table_normal"]];
    }
}
-(void)setHighlighted:(BOOL)highlighted animated:(BOOL)animated
{
    [super setHighlighted:highlighted animated:animated];
    if (highlighted)
    {
        [_imageViewSC setImageWithUrlString:_selectedImageOfSubcategory AndName:[_selectedImageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
        [_bgImageView setImage:[UIImage imageNamed:@"bg_table_selected"]];    }
    else
    {
        [_imageViewSC setImageWithUrlString:_imageOfSubcategory AndName:[_imageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
        [_bgImageView setImage:[UIImage imageNamed:@"bg_table_normal"]];
    }

}
-(void) layoutSubviews
{
    [super layoutSubviews];
    if (self.selected)
    {
         [_imageViewSC setImageWithUrlString:_selectedImageOfSubcategory AndName:[_selectedImageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
        [_bgImageView setImage:[UIImage imageNamed:@"bg_table_selected"]];
    }
    else
    {
       [_imageViewSC setImageWithUrlString:_imageOfSubcategory AndName:[_imageOfSubcategory stringByReplacingOccurrencesOfString:@"files" withString:@"images"]];
        [_bgImageView setImage:[UIImage imageNamed:@"bg_table_normal"]];
    }

}
@end
