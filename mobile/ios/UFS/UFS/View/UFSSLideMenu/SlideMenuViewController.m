/*

 Copyright (c) 2013 Joan Lluch <joan.lluch@sweetwilliamsl.com>
 
 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is furnished
 to do so, subject to the following conditions:
 
 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.

*/

#import "SlideMenuViewController.h"

#import "SWRevealViewController.h"
#import "UFSMainNewsVC.h"
#import "UFSMenuCell.h"
#import "UFSActionsVC.h"

/* tsv */
#import "AnalyticsCounter.h"
/* tsv */


@interface SlideMenuViewController()

@end

@implementation SlideMenuViewController

@synthesize fetchedResultsController;

/*
 * The following lines are crucial to understanding how the SWRevealController works.
 *
 * In this example, we show how a SWRevealController can be contained in another instance 
 * of the same class. We have three scenarios of hierarchies as follows
 *
 * In the first scenario a FrontViewController is contained inside of a UINavigationController.
 * And the UINavigationController is contained inside of a SWRevealController. Thus the
 * following hierarchy is created:
 *
 * - SWRevealController is parent of:
 * - 1 UINavigationController is parent of:
 * - - 1.1 RearViewController
 * - 2 UINavigationController is parent of:
 * - - 2.1 FrontViewController
 *
 * In the second scenario a MapViewController is contained inside of a UINavigationController.
 * And the UINavigationController is contained inside of a SWRevealController. Thus the
 * following hierarchy is created:
 *
 * - SWRevealController is parent of:
 * - 1 UINavigationController is parent of:
 * - - 1.1 RearViewController
 * - 2 UINavigationController is parent of:
 * - - 1.2 MapViewController
 *
 * In the third scenario a SWRevealViewController is contained directly inside of another.
 * SWRevealController. Thus the following hierarchy is created:
 *
 * - SWRevealController is parent of:
 * - 1 UINavigationController is parent of:
 * - - 1.1 RearViewController
 * - 2 SWRevealController
 *
 * The second SWRevealController on the third scenario can in turn contain anything. 
 * On this example it may recursively contain any of the above, including again the third one
 */
-(id)init
{
    if (self=[super init])
    {
        _selectedPosition = [[[NSIndexPath alloc] init] autorelease];
        _selectedPosition = nil;
        NSLog(@"init viz slidemenu");
        NSError *error = nil;
        
        if (![[self fetchedResultsController] performFetch:&error]) {
            NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
        }
        
        if (!fetchedResultsController.fetchedObjects.count)
        {
            //            if ([((UINavigationController *)self.revealViewController.frontViewController).topViewController isKindOfClass:[UFSMainNewsVC class]])
            {
//                
//                ((UFSMainNewsVC *)((UINavigationController *)self.revealViewController.frontViewController).topViewController).menu.enabled = NO;
                NSLog(@"init viz ");
            }
            
        }
        [[NSNotificationCenter defaultCenter] removeObserver:self];
        [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reloadMenuData:) name:kNotificationDataRemoved object:nil];
        
        /* tsv */
        [self showDefaultCategory:NO];
        /* tsv */
    }
    return self;
}
- (void)viewDidLoad
{
	[super viewDidLoad];
    // We determine whether we have a grand parent SWRevealViewController, this means we are at least one level behind the hierarchy
        
    expandet = [[NSMutableDictionary alloc] init];
    
    _menuTableView = [[UITableView alloc] initWithFrame:CGRectMake(0, 0, 260, self.view.height)];
    [_menuTableView setBackgroundColor:RGBA(39, 43, 56, 1.0f)];
    [_menuTableView setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    [_menuTableView setShowsVerticalScrollIndicator:NO];
    _menuTableView.delegate= self;
    _menuTableView.dataSource = self;
    [self.view addSubview:_menuTableView];
    
    [_menuTableView release];
    
    if (!fetchedResultsController.fetchedObjects.count)
    {
        loadingLable = [[UILabel alloc] initWithFrame:CGRectMake(0, self.view.height/2-30, 260, 60)];
        loadingLable.backgroundColor = [UIColor clearColor];
        loadingLable.text = @"Идет загрузка";
        loadingLable.textAlignment = NSTextAlignmentCenter;
        [loadingLable setFont:[UIFont fontWithName:@"Helvetica-Bold" size:15]];
        [loadingLable setTextColor:RGBA(122, 132, 165, 1.0f)];
        [_menuTableView addSubview:loadingLable];
        [loadingLable release];
        
        spinner = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhite];
        spinner.color = RGBA(122, 132, 165, 1.0f);
        spinner.center = CGPointMake(loadingLable.center.x, loadingLable.center.y+30);
        [_menuTableView addSubview:spinner];
        [spinner startAnimating];
        [spinner release];
    }

    
    SWRevealViewController *parentRevealController = self.revealViewController;
    SWRevealViewController *grandParentRevealController = parentRevealController.revealViewController;
    menuButton = [[UIButton alloc] initWithFrame:CGRectMake(0, (self.view.frame.size.height - 44)/2.0f, 44, 44.0f)];
    [menuButton setImage:[UIImage imageNamed:@"icn_nav_menu"] forState:UIControlStateNormal];
   
    
    UIBarButtonItem *revealButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:menuButton] autorelease];
    [menuButton release];
    {
         [menuButton addTarget:parentRevealController action:@selector(revealToggle:) forControlEvents:UIControlEventTouchUpInside];
    }

    // if we have a reveal controller as a grand parent, this means we are are being added as a
    // child of a detail (child) reveal controller, so we add a gesture recognizer provided by our grand parent to our
    // navigation bar as well as a "reveal" button
    if (grandParentRevealController)
    {
        // to present a title, we count the number of ancestor reveal controllers we have, this is of course
        // only a hack for demonstration purposes, on a real project you would have a model telling this.
        NSInteger level=0;
        UIViewController *controller = grandParentRevealController;
        while( nil != (controller = [controller revealViewController]) )
            level++;
        
        NSString *title = [NSString stringWithFormat:@"Detail Level %d", level];
            
        [self.navigationController.navigationBar addGestureRecognizer:grandParentRevealController.panGestureRecognizer];
        self.navigationItem.leftBarButtonItem = revealButtonItem;
        self.navigationItem.title = title;
        [revealButtonItem release];
    }
    
    // otherwise, we are in the top reveal controller, so we just add a title
    else
    {
        self.navigationItem.title = @"";
    }
}

-(void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    
//     [fetchedResultsController setDelegate:self];
    
}
-(void)viewWillDisappear:(BOOL)animated
{
    [super viewWillDisappear:animated];
//    [fetchedResultsController setDelegate:nil];
    
}
-(void)showAlert
{
   
}

#pragma -mark  UITableView Data Source

-(UIView *) tableView:(UITableView *)tableView viewForHeaderInSection:(NSInteger)section
{
    
    UIView *bgView = [[[UIView alloc] initWithFrame:CGRectMake(0, 0, tableView.width, 42)] autorelease];

    //NSString *str = ((CategoriesDB *)[categoryArray objectAtIndex:section]).title;
    /* tsv */
    CategoriesDB *catDB = (CategoriesDB *)[categoryArray objectAtIndex:section];
    UIImage *back = [UIImage imageNamed:[NSString stringWithFormat:@"bg_table_header_%d",[catDB.identifier integerValue]]];
    /* tsv */
    
    UIImageView *imageBg = [[UIImageView alloc] initWithFrame:CGRectMake(0, 0, tableView.width, 42)];
    //[imageBg setImage:[UIImage imageNamed:@"bg_table_header"]];
    /* tsv */
    if (back==nil) {
        [imageBg setImage:[UIImage imageNamed:@"bg_table_header"]];
    } else {
        [imageBg setImage:back];
    }
    /* tsv */
    [bgView addSubview:imageBg];
    [imageBg release];
    UILabel *titleLable = [[UILabel alloc] initWithFrame:CGRectMake(40, 5, tableView.width-40, bgView.height-10)];
    [titleLable setBackgroundColor:[UIColor clearColor]];
    [titleLable setFont:[UIFont fontWithName:@"Helvetica-Bold" size:15]];
    [titleLable setTextColor:RGBA(122, 132, 165, 1.0f)];
    //[titleLable setText:str];
    /* tsv */
    if (back==nil) {
        [titleLable setText:catDB.title];
    }
    /* tsv */
    [bgView addSubview:titleLable];
    [titleLable release];
    float offset = 0;
    if (section<categoryArray.count-1)
    {
        int count = [((CategoriesDB *)[categoryArray objectAtIndex:section]).subcategories allObjects].count;
        if (count)
        {
            offset=45;
            UIButton *buttonExpand = [[UIButton alloc] initWithFrame:CGRectMake(0, 0, 44, 44)];
            [buttonExpand setBackgroundColor:[UIColor clearColor]];
            [buttonExpand setTag:section];
            [buttonExpand addTarget:self action:@selector(buttonExpand:) forControlEvents:UIControlEventTouchUpInside];
            BOOL isExp = ((BOOL)[[[expandet objectForKey:[NSString stringWithFormat:@"%d",section]] objectForKey:@"exp"] intValue]);
            if (isExp)
            {
                [buttonExpand setBackgroundImage:[UIImage imageNamed:@"icn_list_drop_up"] forState:UIControlStateNormal];
            }
            else
            {
                [buttonExpand setBackgroundImage:[UIImage imageNamed:@"icn_list_drop_down"] forState:UIControlStateNormal];
            }

    //        [buttonExpand setImage:[UIImage imageNamed:@"icn_arr_menu_down"] forState:UIControlStateNormal];
            [bgView addSubview:buttonExpand];
            [buttonExpand release];
        }
    }
    
    
    UIButton *buttonCategory = [[UIButton alloc] initWithFrame:CGRectMake(offset, 0, bgView.width-(offset+20), bgView.height)];
    [buttonCategory setBackgroundColor:[UIColor clearColor]];
    [buttonCategory addTarget:self action:@selector(buttonCategory:) forControlEvents:UIControlEventTouchUpInside];
    [buttonCategory setTag:section];
    [bgView addSubview:buttonCategory];
    [buttonCategory release];
    return bgView;
}


- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 42;
}
- (CGFloat)tableView:(UITableView *)tableView heightForHeaderInSection:(NSInteger)section
{
    return 42;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    //    return [self.childViewControllers count];
    if (section<categoryArray.count)
    {
        int count = [((CategoriesDB *)[categoryArray objectAtIndex:section]).subcategories allObjects].count;
        if (![expandet allKeys].count)
        {
            BOOL need = (count)?YES:NO;
            NSDictionary *dict = [NSDictionary dictionaryWithObject:[NSNumber numberWithBool:need] forKey:@"exp"];
            [expandet setObject:dict forKey:[NSString stringWithFormat:@"%d",section]];
        }
        BOOL isExp = ((BOOL)[[[expandet objectForKey:[NSString stringWithFormat:@"%d",section]] objectForKey:@"exp"] intValue]);
        if (isExp)
            return count;
        else
            return 0;
    }
    return 0;
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    if (fetchedResultsController.fetchedObjects.count)
    {
    NSArray *actionsValue = [fetchedResultsController.fetchedObjects filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"type == 2"]];
    int x=0;
    if (actionsValue.count)
    {
        for (int i=0;i<actionsValue.count;i++)
        {
            if (!((CategoriesDB *)[actionsValue objectAtIndex:i]).allActivityCount)
            {
                x++;
            }
        }
    }
    
    NSArray *array = [fetchedResultsController.fetchedObjects filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"allActivityCount!=0"]];
    array = [array sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"index" ascending:YES]]];

    categoryArray = [[NSArray alloc] initWithArray:array];

    return (categoryArray.count);
    }
    return 0;
}


- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    NSString *cellIdentifier = [NSString stringWithFormat:@"Cell%d%d",indexPath.section,indexPath.row];
	UFSMenuCell *cell = [tableView dequeueReusableCellWithIdentifier:cellIdentifier];
	
    
	if(cell == nil)
    {
        cell = [[[UFSMenuCell alloc] initWithStyle:UITableViewCellStyleValue1
                                   reuseIdentifier:cellIdentifier
                                              type:0] autorelease];
        // Do something here......................
    }
    if (fetchedResultsController.fetchedObjects.count)
    {
        NSArray *arr = [NSArray arrayWithArray:[((CategoriesDB *)[categoryArray objectAtIndex:indexPath.section]).subcategories allObjects]];
        NSSortDescriptor *srt = [NSSortDescriptor sortDescriptorWithKey:@"index" ascending:YES];
        arr = [arr sortedArrayUsingDescriptors:[NSArray arrayWithObjects:srt, nil]];
        SubCatDB *subCat = ((SubCatDB *)[arr objectAtIndex:indexPath.row]);
        cell.caption.text = subCat.title;
        cell.imageOfSubcategory = subCat.imgURL;
        cell.selectedImageOfSubcategory = subCat.h_imgURL;
        cell.nameForImage = [NSString stringWithFormat:@"Image_%d_%d",indexPath.section,indexPath.row];
    }
    
	return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
    SWRevealViewController *revealController = [self revealViewController];
    UIViewController *frontViewController = revealController.frontViewController;
    UINavigationController *frontNavigationController =nil;
    
    if ( [frontViewController isKindOfClass:[UINavigationController class]] )
        frontNavigationController = (id)frontViewController;
    
    NSArray *arr = [NSArray arrayWithArray:[((CategoriesDB *)[categoryArray objectAtIndex:indexPath.section]).subcategories allObjects]];
    NSSortDescriptor *srt = [NSSortDescriptor sortDescriptorWithKey:@"index" ascending:YES];
    arr = [arr sortedArrayUsingDescriptors:[NSArray arrayWithObjects:srt, nil]];
    SubCatDB *subCat = ((SubCatDB *)[arr objectAtIndex:indexPath.row]);

	// Here you'd implement some of your own logic... I simply take for granted that the first row (=0) corresponds to the "FrontViewController".
    // Now let's see if we're not attempting to swap the current frontViewController for a new instance of ITSELF, which'd be highly redundant.

    /* tsv */
    NSMutableArray *titles = [[NSMutableArray alloc] init];
    CategoriesDB *catDB = [categoryArray objectAtIndex:indexPath.section];
    [titles addObject:catDB.title];
    [titles addObject:[subCat title]];
    [AnalyticsCounter eventScreen:kMainScreen categories:[NSArray arrayWithArray:titles] action:nil value:nil];
    /* tsv */
    
    if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:4]])
    {
        frontViewController = [[[UFSDebtMarketVC alloc] init] autorelease];
        /* tsv */((UFSDebtMarketVC *)frontViewController).titles = titles;
        [((UFSDebtMarketVC *)frontViewController) setSubCatId:[subCat.identifier integerValue]];
        [((UFSDebtMarketVC *)frontViewController) setTitleNavBar:[subCat title]];
        [((UFSDebtMarketVC *)frontViewController) setType:0];
        
//        [((UFSMainNewsVC *)frontViewController) reloadVC];
        UINavigationController *navigationController = [[UINavigationController alloc] initWithRootViewController:frontViewController];
        [revealController setFrontViewController:navigationController animated:YES];
    }
    else if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:3]])
    {
                
        id ViewController = [[[UFSEmitentsVC alloc] init] autorelease];
        /* tsv */((UFSEmitentsVC *)ViewController).titles = titles;
        [((UFSEmitentsVC *)ViewController) setSubcatID:[subCat.identifier integerValue]];
        [((UFSEmitentsVC *)ViewController) setTitleNavBar:[subCat title]];
        [((UFSEmitentsVC *)ViewController) setType:0];
        [frontNavigationController pushViewController:((UFSEmitentsVC *)ViewController) animated:NO];
        
        [revealController revealToggleAnimated:YES];
    }
    else if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:5]])
    {
        id ViewController = [[[UFSActionsDetailVC alloc] initWithNibName:nil bundle:nil] autorelease];
        [((UFSActionsDetailVC *)ViewController) setSubCatID:[subCat.identifier integerValue]];
        [((UFSActionsDetailVC *)ViewController) setCatID:0];
        [((UFSActionsDetailVC *)ViewController) setTitleNavBar:[subCat title]];
        [((UFSActionsDetailVC *)ViewController) setType:5];
        [frontNavigationController pushViewController:((UFSActionsDetailVC *)ViewController) animated:NO];
        [revealController revealToggleAnimated:YES];
    }
    else if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:6]])
    {
        
        id ViewController = [[[UFSContactsVC alloc] initWithNibName:nil bundle:nil] autorelease];
        /* tsv */((UFSContactsVC *)ViewController).titles = titles;
        [frontNavigationController pushViewController:((UFSContactsVC *)ViewController) animated:NO];
        [revealController revealToggleAnimated:YES];

    }
    /* tsv */
    else if ([subCat.type isEqualToNumber:[NSNumber numberWithInt:99]])
    {
   
                
    }
    /* tsv */
    else
    {
		frontViewController = [[[UFSMainNewsVC alloc] init] autorelease];
        /* tsv */((UFSMainNewsVC *)frontViewController).titles = titles;
        [((UFSMainNewsVC *)frontViewController) setCatId:[[((CategoriesDB *)subCat.categories) identifier] intValue]];
        [((UFSMainNewsVC *)frontViewController) setSubCatId:[subCat.identifier integerValue]];
        [((UFSMainNewsVC *)frontViewController) setAllNewsCount:[subCat.allNewsCount intValue]];
        [((UFSMainNewsVC *)frontViewController) setTitleNavBar:[subCat title]];
        [((UFSMainNewsVC *)frontViewController) setTypeOfNews:[[subCat type] intValue]];
        //[((UFSMainNewsVC *)frontViewController) reloadVC];
		UINavigationController *navigationController = [[UINavigationController alloc] initWithRootViewController:frontViewController];
		[revealController setFrontViewController:navigationController animated:YES];
    }
    SAFE_KILL(_selectedPosition);
    _selectedPosition = [indexPath copy];
}

#pragma  - mark NSFetchedResultControllerDelegate
- (void)controllerDidChangeContent:(NSFetchedResultsController *)controller {
    
    if (categoryArray)
    {
        [categoryArray release];
        categoryArray = nil;
    }
    if (loadingLable)
    {
        [loadingLable removeFromSuperview];
        loadingLable=nil;
    }
    if (spinner)
    {
        [spinner stopAnimating];
        spinner = nil;
    }
    [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationDisableMenu object:[NSNumber numberWithBool:NO]];
    [_menuTableView reloadData];
    fetchedResultsController.delegate = nil;
    
    /* tsv */[self showDefaultCategory:NO];
}



- (NSFetchedResultsController *)fetchedResultsController {
    
    if (fetchedResultsController != nil) {
        return fetchedResultsController;
    }
    
    /*
	 Set up the fetched results controller.
     */
	// Create the fetch request for the entity.
	NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] init];
	// Edit the entity name as appropriate.
	NSEntityDescription *entity = [NSEntityDescription entityForName:@"CategoriesDB" inManagedObjectContext:CoreDataManager.shared.managedObjectContext];
	[fetchRequest setEntity:entity];
	
	// Set the batch size to a suitable number.
	[fetchRequest setFetchBatchSize:20];
    [fetchRequest setFetchLimit:20];
    
	// Sort using the timeStamp property..
    NSSortDescriptor *sortDescriptor = [[NSSortDescriptor alloc] initWithKey:@"index" ascending:YES];
    
    NSArray *sortDescriptors = [[NSArray alloc] initWithObjects:sortDescriptor,nil];
    
    
    //    NSPredicate * predicatForResult = [NSPredicate predicateWithFormat:@"%K like %@",@"arcName",typeOfNews];
    [fetchRequest setPredicate : nil];
	[fetchRequest setSortDescriptors:sortDescriptors];
	
    // Use the sectionIdentifier property to group into sections.
	
    NSFetchedResultsController *localFetchedResultsController = [[NSFetchedResultsController alloc] initWithFetchRequest:fetchRequest managedObjectContext:CoreDataManager.shared.managedObjectContext sectionNameKeyPath:nil  cacheName:nil];
    localFetchedResultsController.delegate = self;
	self.fetchedResultsController = localFetchedResultsController;
	
	[localFetchedResultsController release];
	[fetchRequest release];
	[sortDescriptor release];
	[sortDescriptors release];
    
    return fetchedResultsController;
}

#pragma -mark Expand Category Method
-(void) buttonExpand:(UIButton *)sender
{
    BOOL isExp = ((BOOL)[[[expandet objectForKey:[NSString stringWithFormat:@"%d",sender.tag]] objectForKey:@"exp"] intValue]);
   
    isExp = !isExp;
    NSDictionary *dict = [NSDictionary dictionaryWithObject:[NSNumber numberWithBool:isExp] forKey:@"exp"];
    [expandet setObject:dict forKey:[NSString stringWithFormat:@"%d",sender.tag]];
    //    int count = [((CategoriesDB *)[fetchedResultsController.fetchedObjects objectAtIndex:sender.tag]).subcategories allObjects].count;
    [_menuTableView reloadSections:[NSIndexSet indexSetWithIndex:sender.tag] withRowAnimation:UITableViewRowAnimationAutomatic];
    if (isExp)
        [_menuTableView scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:0 inSection:sender.tag] atScrollPosition:UITableViewScrollPositionTop animated:YES];
}

/* tsv */
- (UIViewController*)tapIndex:(NSInteger)index visible:(BOOL)visible
{
    [_menuTableView deselectRowAtIndexPath:_selectedPosition animated:YES];
    
    
    SWRevealViewController *revealController = [self revealViewController];
    UIViewController *frontViewController = revealController.frontViewController;
    
    /* tsv */
    UINavigationController *frontNavigationController = nil;
    if ( [frontViewController isKindOfClass:[UINavigationController class]] )
        frontNavigationController = (id)frontViewController;
    /* tsv */
    
    int iDButt =[((CategoriesDB *)[categoryArray objectAtIndex:index]).identifier intValue];
    CategoriesDB *catDB = [categoryArray objectAtIndex:index];
    NSString *title = catDB.title;
    
    /* tsv */
    NSMutableArray *titles = [[NSMutableArray alloc] init];
    [titles addObject:title];
    [AnalyticsCounter eventScreen:kMainScreen categories:titles action:nil value:nil];
    /* tsv */
    
    if ([catDB.type isEqual: @(2)])
    {
        NSString *tempId = [((CategoriesDB *)[categoryArray objectAtIndex:index]).identifier stringValue];
        frontViewController = [[[UFSActionsVC alloc] initWithCategoryIdentifier:tempId] autorelease];
        /* tsv */((UFSActionsVC *)frontViewController).titles = titles;
        ((UFSActionsVC *)frontViewController).titleNavBar = title;
    }
    else if ([catDB.type isEqual: @(3)])
    {
        NSString *tempId = [((CategoriesDB *)[categoryArray objectAtIndex:index]).identifier stringValue];
        
        frontViewController = [[[UFSEmitentsVC alloc] initWithNibName:nil bundle:nil] autorelease];
        /* tsv */((UFSEmitentsVC *)frontViewController).titles = titles;
        ((UFSEmitentsVC *)frontViewController).subcatID = [tempId intValue];
        ((UFSEmitentsVC *)frontViewController).titleNavBar = title;
    }
    else if ([catDB.type isEqual: @(5)])
    {
        frontViewController = [[[UFSActionsDetailVC alloc] initWithNibName:nil bundle:nil] autorelease];
        ((UFSActionsDetailVC *)frontViewController).titleNavBar = title;
        ((UFSActionsDetailVC *)frontViewController).catID = [catDB.identifier integerValue];
        ((UFSActionsDetailVC *)frontViewController).subCatID = 0;
        ((UFSActionsDetailVC *)frontViewController).type = 5;
    }
    else if ([catDB.type isEqual: @(6)])
    {
        frontViewController = [[[UFSContactsVC alloc] init] autorelease];
        /* tsv */((UFSContactsVC *)frontViewController).titles = titles;
    }
    /* tsv */
    else if ([catDB.type isEqual: @(99)])
    {
        UFSQRCodeVC *qrcode = [[UFSQRCodeVC alloc] initWithNibName:nil bundle:nil];
        qrcode.titleNavBar = title;
        
        [frontNavigationController pushViewController:qrcode animated:NO];
        
        if (visible)
          [revealController revealToggleAnimated:YES];
        
        [qrcode release];
    }
    /* tsv */
    else
    {
        frontViewController = [[[UFSMainNewsVC alloc] init] autorelease];
        
        /* tsv */((UFSMainNewsVC *)frontViewController).titles = titles;
        [((UFSMainNewsVC *)frontViewController) setCatId:iDButt];
        [((UFSMainNewsVC *)frontViewController) setSubCatId:0];
        [((UFSMainNewsVC *)frontViewController) setTitleNavBar:((CategoriesDB *)[categoryArray objectAtIndex:index]).title];
        [((UFSMainNewsVC *)frontViewController) setTypeOfNews:[((CategoriesDB *)[categoryArray objectAtIndex:index]).type intValue]];
        [((UFSMainNewsVC *)frontViewController) setAllNewsCount:[((CategoriesDB *)[categoryArray objectAtIndex:index]).allNewsCount intValue]];
        
        if ([((CategoriesDB *)[categoryArray objectAtIndex:index]).subcategories allObjects].count)
        {
            
            NSArray *subcatArr = [catDB.subcategories allObjects];
            // type 3 - tables
            subcatArr = [subcatArr filteredArrayUsingPredicate:[NSPredicate predicateWithFormat:@"type!=3"]];
            if (!subcatArr.count)
            {
                BOOL isExp = ((BOOL)[[[expandet objectForKey:[NSString stringWithFormat:@"%d",index]] objectForKey:@"exp"] intValue]);
                
                isExp = !isExp;
                NSDictionary *dict = [NSDictionary dictionaryWithObject:[NSNumber numberWithBool:isExp] forKey:@"exp"];
                [expandet setObject:dict forKey:[NSString stringWithFormat:@"%d",index]];
                [_menuTableView reloadSections:[NSIndexSet indexSetWithIndex:index] withRowAnimation:UITableViewRowAnimationAutomatic];
                if (isExp)
                    [_menuTableView scrollToRowAtIndexPath:[NSIndexPath indexPathForRow:0 inSection:index] atScrollPosition:UITableViewScrollPositionTop animated:YES];
                //return;
                /* tsv */return nil;
            }
            
            [((UFSMainNewsVC *)frontViewController) setType:1];
        }
        else
        {
            [((UFSMainNewsVC *)frontViewController) setType:0];
        }
    }
    
    /* tsv */
    if (![catDB.type isEqual: @(99)]) {
        UINavigationController *navigationController = [[UINavigationController alloc] initWithRootViewController:frontViewController];
        [revealController setFrontViewController:navigationController animated:NO];
        if (visible)
          [revealController revealToggleAnimated:YES];
    }
    /* tsv */
    
    return frontViewController;
}

/* tsv */

#pragma -mark Category Tap
- (void)buttonCategory:(UIButton *)sender
{
    _selectedController = [self tapIndex:sender.tag visible:YES];
}

/* tsv */
-(void)showDefaultCategory:(BOOL)visible
{
    
    NSUserDefaults *userDef = [NSUserDefaults standardUserDefaults];
    if ([userDef boolForKey:kFirstMenuShow]) {
        
        BOOL exists = [self numberOfSectionsInTableView:_menuTableView]>0;
        if ([userDef integerForKey:kAuthCategoryId] && exists) {
            
            NSInteger categoryId = [userDef integerForKey:kAuthCategoryId];
            
            int index = -1;
            for (int i=0; i<categoryArray.count; i++) {
                
                CategoriesDB *category = [categoryArray objectAtIndex:i];
                if (categoryId==[category.identifier integerValue]) {
                    index = i;
                    break;
                }
            }
            
            if (index!=-1) {
                
                [userDef setBool:NO forKey:kFirstMenuShow];
                [userDef synchronize];
                
                _selectedController = [self tapIndex:index visible:NO];
                if (_selectedController!=nil) {
                
                    [userDef setBool:NO forKey:kFirstMenuShow];
                    [userDef synchronize];
                    
                    if (visible)
                        [self.revealViewController revealToggleAnimated:YES];
                    
                }
            }
        }
    }
}

-(void)reloadMenuData:(NSNotification *)notification
{
    SAFE_KILL(fetchedResultsController);
    NSError *error = nil;
    if (![[self fetchedResultsController] performFetch:&error]) {
        NSLog(@"Unresolved error %@, %@", error, [error userInfo]);}
    fetchedResultsController.delegate = self;
    [_menuTableView reloadData];
    [UFSLoader requestPostRubrics];
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