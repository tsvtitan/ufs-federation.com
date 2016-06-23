//
//  PdfTableViewController.m
//  UFS
//
//  Created by mihail on 05.09.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "PdfTableViewController.h"

@interface PdfTableViewController ()

@end

@implementation PdfTableViewController

- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
        // Custom initialization
    }
    return self;
}
-(void) dealloc
{
    [_nameOfNews release];
    [super dealloc];
}
- (void)viewDidLoad
{
    [super viewDidLoad];
    [self.view setBackgroundColor:[UIColor whiteColor]];
    self.title = _nameOfNews;
    pdfTV = [[UITableView alloc] initWithFrame:self.view.bounds style:UITableViewStylePlain];
    pdfTV.delegate = self;
    pdfTV.dataSource = self;
    pdfTV.backgroundColor = [UIColor clearColor];
    pdfTV.autoresizingMask = UIViewAutoresizingFlexibleHeight;
    [pdfTV setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    [self.view addSubview:pdfTV];
    [pdfTV release];
     UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
    UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
    [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
    [backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
    [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
    self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
    [backbutton release];
	// Do any additional setup after loading the view.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 60;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //    return [self.childViewControllers count];
    
    return _pdfArray.count;
    
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1;
    
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *cellIdentifier = [NSString stringWithFormat:@"cellId"];
    pdfFile = [_pdfArray objectAtIndex:indexPath.row];
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellIdentifier];
    if (cell==nil)
    {
        cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellIdentifier] autorelease];
    }
    [cell setSelectionStyle:UITableViewCellSelectionStyleNone];
    
    UIImage *image = [[UIImage imageNamed:@"btn_big_round"] resizableImageWithCapInsets:UIEdgeInsetsMake(0, 10, 0, 10)];
    UIButton *pdfButton = [[UIButton alloc] initWithFrame:CGRectMake(5, 10, 300, 50)];
    [pdfButton setBackgroundImage:image forState:UIControlStateNormal];
    [pdfButton setTag:indexPath.row];
    [pdfButton addTarget:self action:@selector(pdfFileTap:) forControlEvents:UIControlEventTouchUpInside];
    [cell.contentView addSubview:pdfButton];
    [pdfButton release];
    UILabel *pdfName = [[UILabel alloc] initWithFrame:CGRectMake(20, 15, 250, 40)];
    pdfName.backgroundColor = [UIColor clearColor];
    pdfName.text = pdfFile.name;
    [pdfName setFont:[UIFont fontWithName:@"Helvetica-Bold" size:13]];
    
    [pdfName setTextColor:RGBA(3, 68, 124, 1)];
    [cell.contentView addSubview:pdfName];
    [pdfName release];
    UIImageView *pdfImage = [[UIImageView alloc] initWithFrame:CGRectMake(265, 20, 25, 30)];
    [pdfImage setImage:[UIImage imageNamed:@"icn_pdf"]];
    [cell.contentView addSubview:pdfImage];
    [pdfImage release];
    
    return cell;
}
-(void)pdfFileTap:(UIButton *)sender
{
     pdfFile = [_pdfArray objectAtIndex:sender.tag];
    ReaderViewController *readerPDF = [[ReaderViewController alloc] initWithReaderDocumentURL:pdfFile.url AndName:pdfFile.url];
    [readerPDF setPdfName: pdfFile.name];
    [self.navigationController pushViewController:readerPDF animated:YES];
    [readerPDF release];

}
-(void)BackButtonTapped: (UIButton *)sender
{
    [self.navigationController popViewControllerAnimated:YES];
}
#pragma -mark Supported Inteface Orientation
-(BOOL)shouldAutorotate
{
    return NO;
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)toInterfaceOrientation
{
   	return  (toInterfaceOrientation == UIInterfaceOrientationPortrait);
}
-(NSUInteger)supportedInterfaceOrientations
{
    return UIInterfaceOrientationMaskPortrait;
}

@end
