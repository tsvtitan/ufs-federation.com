
//
//  ContactDetailVC.m
//  UFS
//
//  Created by mihail on 08.11.13.
//  Copyright (c) 2013 Moskovchenko M. All rights reserved.
//

#import "ContactDetailVC.h"

@interface ContactDetailVC ()

@end

@implementation ContactDetailVC
- (id)initWithContact:(ContactsDB *) contactObj
{
    self = [super initWithNibName:nil bundle:nil];
    if (self)
    {
        [self setContact:contactObj];
      
        height = 0;
        isOpen = true;
    }
    return self;
}
//-(void)setContact:(ContactsDB *)contact
//{
//    SAFE_KILL(_contact);
//    self.contact = contact;
//}
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil
{
    self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil];
    if (self) {
            // Custom initialization
    }
    return self;
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    [[NSNotificationCenter defaultCenter] removeObserver:self];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOn:) name:kReachableOk object:nil];
    [[NSNotificationCenter defaultCenter] addObserver:self selector:@selector(reachOff:) name:kNotReachable object:nil];

    [self.view setBackgroundColor:RGBA(238, 241, 243, 1.0f)];
    self.titleText = _contact.city;
    UIImage *imgBtn = [UIImage imageNamed:@"icn_nav_back"];
    //    imgBtn = [imgBtn stretchableImageWithLeftCapWidth:12 topCapHeight:0];
    UIButton *backbutton = [[UIButton alloc] initWithFrame:CGRectMake(5.0f, (self.view.frame.size.height - 30)/2.0f, 30.0f, 30.0f)];
    [backbutton setBackgroundImage:imgBtn forState:UIControlStateNormal];
    [backbutton addTarget:self action:@selector(BackButtonTapped:) forControlEvents:UIControlEventTouchUpInside];
    [backbutton.titleLabel setTextAlignment:NSTextAlignmentCenter];
    self.navigationItem.leftBarButtonItem = [[[UIBarButtonItem alloc] initWithCustomView:backbutton] autorelease];
    [backbutton release];
    
    bgScroll = [[UIScrollView alloc] initWithFrame:self.view.bounds];
    bgScroll.backgroundColor = [UIColor clearColor];
    bgScroll.autoresizingMask = UIViewAutoresizingFlexibleHeight;
    [self.view addSubview:bgScroll];
    [bgScroll release];
    
    addresLable = [[UILabel alloc] initWithFrame:CGRectMake(10, 10, self.view.width-20, 100)];
    addresLable.lineBreakMode = NSLineBreakByWordWrapping;
    addresLable.numberOfLines=0;
    addresLable.text = self.contact.address;
    [addresLable setFont:[UIFont fontWithName:@"Helvetica-Bold" size:15]];
    [addresLable setBackgroundColor:[UIColor clearColor]];
    addresLable.textColor = RGBA(104, 104, 104, 1.0f);
    height = [addresLable.text sizeWithFont:addresLable.font constrainedToSize:CGSizeMake(self.view.width-20, MAXFLOAT)].height;
    addresLable.frame = CGRectMake(10, 10, addresLable.width, height);
    [bgScroll addSubview:addresLable];
    [addresLable release];
        _contactDetailTV = [[UITableView alloc] initWithFrame:CGRectMake(0, addresLable.height+220,self.view.width,[_contact.data allObjects].count*50.0f+50.0f) style:UITableViewStylePlain];
    _contactDetailTV.autoresizingMask = UIViewAutoresizingFlexibleHeight;
    _contactDetailTV.backgroundColor = [UIColor clearColor];
    _contactDetailTV.scrollEnabled = false;
    [_contactDetailTV setSeparatorStyle:UITableViewCellSeparatorStyleNone];
    _contactDetailTV.delegate = self;
    _contactDetailTV.dataSource = self;
    [bgScroll addSubview:_contactDetailTV];
    [_contactDetailTV release];
    bgScroll.contentSize = CGSizeMake(self.view.width, addresLable.height+220+[_contact.data allObjects].count*50.0f);
    indicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
    indicator.center = CGPointMake(self.view.width/2, addresLable.height+110);
    [indicator setColor:RGBA(0, 62, 112, 1.0f)];
    [bgScroll addSubview:indicator];
    [indicator startAnimating];
    [indicator release];
	// Do any additional setup after loading the view.
}
- (void)viewDidAppear:(BOOL)animated
{
    [super viewDidAppear:animated];
    if ([UFSLoader reachable])
    {
        if (isOpen)
        {
            [self createMapView];
        }
    }
    else
    {
        [indicator stopAnimating];
        imageForNotReach = [[UIImageView alloc] initWithFrame:CGRectMake(60,addresLable.height+50, self.view.width-120, 120)];
        imageForNotReach.image = [UIImage imageNamed:@"icn_logo"];
        [bgScroll addSubview:imageForNotReach];
        [imageForNotReach release];
    }
}
- (void)dealloc
{
     [[NSNotificationCenter defaultCenter] removeObserver:self];
    _mapView.delegate = nil;
    [self.mapView setMapType:MKMapTypeSatellite];
    [self.mapView setMapType:MKMapTypeStandard];
    [_mapView removeAnnotation:[_mapView.annotations objectAtIndex:0]];
//    self.mapView = nil;
    _contactDetailTV.delegate = nil;
    _contactDetailTV.dataSource = nil;
    [super dealloc];
    
}
- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}
-(void)BackButtonTapped: (UIButton *)sender
{
    
   [self.navigationController popViewControllerAnimated:YES];
}
-(void)createMapView
{
    _mapView = [[MKMapView alloc] initWithFrame:CGRectMake(10,addresLable.height+20, self.view.width-20, 200)];
    _mapView.delegate = self;
    [_mapView setMapType:MKMapTypeStandard];
    [_mapView.layer setBorderColor:RGBA(145, 175, 190, 1.0f).CGColor];
    [_mapView.layer setBorderWidth:1.0f];
    [_mapView.layer setCornerRadius:5.0f];
    [_mapView setZoomEnabled:NO];
    [_mapView setScrollEnabled:NO];
    //        [_mapView setHidden:YES];
    [bgScroll addSubview:_mapView];
    [_mapView release];
    mapButton = [[UIButton alloc] initWithFrame:_mapView.bounds];
    [mapButton setBackgroundColor:[UIColor clearColor]];
    [mapButton addTarget:self action:@selector(ZoomMapToFullScreen:) forControlEvents:UIControlEventTouchUpInside];
    [self.mapView addSubview:mapButton];
    [mapButton release];
    CLLocationCoordinate2D coordinate = CLLocationCoordinate2DMake([_contact.latitude doubleValue],[_contact.longitude doubleValue]);
    [_mapView setRegion:MKCoordinateRegionMakeWithDistance(coordinate, 250, 200) animated:NO];
    
    NSString *adress = [_contact.address substringFromIndex:[_contact.address rangeOfString:_contact.city].location+_contact.city.length+2];
    UFSAnnotation *annotation = [[UFSAnnotation alloc] init];
    annotation.title = adress;
    annotation.subtitle = _contact.city;
    annotation.coordinate = CLLocationCoordinate2DMake([_contact.latitude doubleValue],[_contact.longitude doubleValue]);
    [_mapView addAnnotation:annotation];
    [annotation release];
    isOpen = false;
//    _mapView.hidden = YES;
}
-(void)ZoomMapToFullScreen: (UIButton *)sender
{
    _contactDetailTV.hidden = YES;
    [UIView animateWithDuration:0.5f animations:^{
        [self.navigationController setNavigationBarHidden:YES];
        [_mapView setFrame:CGRectMake(0, 0, self.view.width, self.view.height)];
    } completion:^(BOOL finished) {
        UIButton *zoomB = [[UIButton alloc] initWithFrame:CGRectMake(self.view.width-40, self.view.height-40, 40, 40)];
        [zoomB setImage:[UIImage imageNamed:@"btn_nav_expand_"] forState:UIControlStateNormal];
//	[zoomB setImage:[UIImage imageNamed:@"btn_nav_expand_"] forState:UIControlStateHighlighted];
        [zoomB addTarget:self action:@selector(UnZoomMapFromFullScreen:) forControlEvents:UIControlEventTouchUpInside];
        zoomB.tag = 1024;
        [_mapView addSubview:zoomB];
        [zoomB release];
        _mapView.scrollEnabled = YES;
        _mapView.zoomEnabled = YES;
	mapButton.hidden = YES;
//        NSLog(@"Complete");
    }];
}
-(void)UnZoomMapFromFullScreen:(UIButton *)sender
{
    [_mapView deselectAnnotation:[_mapView.annotations objectAtIndex:0] animated:YES];
    [UIView animateWithDuration:0.5f animations:^{
        [_mapView setFrame:CGRectMake(10, addresLable.height+20, self.view.width-20, 200)];
        
	[self.navigationController setNavigationBarHidden:NO];
    } completion:^(BOOL finished) {
	CLLocationCoordinate2D coordinate = CLLocationCoordinate2DMake([_contact.latitude doubleValue],[_contact.longitude doubleValue]);
    	[_mapView setRegion:MKCoordinateRegionMakeWithDistance(coordinate, 250, 200) animated:YES];
	[_mapView setZoomEnabled:NO];
    	[_mapView setScrollEnabled:NO];
	mapButton.hidden = NO;
        _contactDetailTV.hidden = NO;
        [sender removeFromSuperview];
    }];
}
#pragma -mark TableView Delegate & DataSource
- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath
{
    return 50.0f;
}
- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section
{
    return [_contact.data allObjects].count;
}
- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView
{
    return 1.0f;
}
- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath
{
    
    NSString *cellId = [NSString stringWithFormat:@"%d %d", indexPath.row, indexPath.section];
    
    UITableViewCell *cell = [tableView dequeueReusableCellWithIdentifier:cellId];
    if ([_contact.data allObjects].count)
    {
        NSArray *contactDataArray = [_contact.data allObjects];
        contactDataArray = [contactDataArray sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];
        DataForContacts *dataObj = [contactDataArray objectAtIndex:indexPath.row];
            if (cell==nil)
            {
                cell = [[[UITableViewCell alloc] initWithStyle:UITableViewCellStyleDefault reuseIdentifier:cellId] autorelease];
                // Configuring cell
                [cell setSelectionStyle:UITableViewCellSelectionStyleNone];
                UIButton *dataButton = [[UIButton alloc] initWithFrame:CGRectMake(120, 15, self.view.width-140, 25)];
                dataButton.tag = indexPath.row;
                if ([dataObj.title rangeOfString:@"Факс"].location==NSNotFound)
                {
                    [dataButton addTarget:self action:@selector(dataButtonTap:) forControlEvents:UIControlEventTouchUpInside];
                }
                
                UILabel *nameLable = [[UILabel alloc] initWithFrame:CGRectMake(10, 15, 100, 25)];
                nameLable.text = dataObj.title;
                nameLable.textColor = RGBA(103, 103, 103, 1.0f);
                nameLable.font = [UIFont fontWithName:@"Helvetica-Bold" size:12.0f];
                nameLable.backgroundColor = [UIColor clearColor];
                [nameLable setTextAlignment:NSTextAlignmentRight];
                [cell.contentView addSubview:nameLable];
                [nameLable release];

                [dataButton setTitle:dataObj.value forState:UIControlStateNormal];
                [dataButton.titleLabel setFont:[UIFont fontWithName:@"Helvetica" size:14]];
                [dataButton setBackgroundImage:[[UIImage imageNamed:@"bg_calendar_title"] stretchableImageWithLeftCapWidth:10 topCapHeight:10] forState:UIControlStateNormal];
                [dataButton setTitleShadowColor:RGBA(0, 49, 87, 1.0f) forState:UIControlStateNormal];
                
                [dataButton setContentHorizontalAlignment:UIControlContentHorizontalAlignmentLeft];
                [dataButton setTitleEdgeInsets:UIEdgeInsetsMake(0, 10, 0, 0)];
                [dataButton setClipsToBounds:YES];
                [dataButton.layer setCornerRadius:13.0f];
                [dataButton setTitleColor:[UIColor whiteColor] forState:UIControlStateNormal];
                [cell.contentView addSubview:dataButton];
                [dataButton release];
                
                
//                UILabel *valueLable = [[UILabel alloc] initWithFrame:CGRectMake(10, 25, self.view.width, 20)];
//                valueLable.text = dataObj.value;
//                valueLable.textColor = RGBA(0, 24, 114, 1.0f);
//                valueLable.font = [UIFont fontWithName:@"Helvetica" size:14.0f];
//                valueLable.backgroundColor = [UIColor clearColor];
//                [cell.contentView addSubview:valueLable];
//                [valueLable release];
            }
    }
    return cell;
}
-(void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath
{
}
#pragma -mark MapView Delegate

-(void)mapViewDidFinishLoadingMap:(MKMapView *)mapView
{
    if ([indicator isAnimating])
        [indicator stopAnimating];
    if ([_mapView isHidden])
    {
        if (imageForNotReach)
        {
            [imageForNotReach removeFromSuperview];
            imageForNotReach = nil;
        }
        [_mapView setHidden:NO];
    }
    
}
-(void)mapViewWillStartLoadingMap:(MKMapView *)mapView
{
//   [_mapView setHidden:YES];
}
-(void)mapViewDidFailLoadingMap:(MKMapView *)mapView withError:(NSError *)error
{
    NSLog(@"Error loading map %@",error);
   
    if (indicator)
    {
        [indicator stopAnimating];
        if (_mapView.hidden)
        {
            if (!imageForNotReach)
            {
                imageForNotReach = [[UIImageView alloc] initWithFrame:CGRectMake(10,addresLable.height+20, self.view.width-20, 200)];
                imageForNotReach.image = [UIImage imageNamed:@"icn_logo"];
                [bgScroll addSubview:imageForNotReach];
                [imageForNotReach release];
            }
        }
    }

    
}
-(UFSAnnotationView *)mapView:(MKMapView *)mapView viewForAnnotation:(id<MKAnnotation>)annotation
{
    UFSAnnotationView *pinAnn = (UFSAnnotationView*)[_mapView dequeueReusableAnnotationViewWithIdentifier:@"annotationInd"];
    
    if(!pinAnn)
    {
        pinAnn = [[[UFSAnnotationView alloc] initWithAnnotation:annotation reuseIdentifier:nil] autorelease];
       

        pinAnn.pinColor = MKPinAnnotationColorRed;
        pinAnn.animatesDrop = YES;
        pinAnn.canShowCallout = YES;
        

    }
    return pinAnn;
}


-(IBAction)phoneButtDidPress:(id)sender
{
   
        if(![[[UIDevice currentDevice]model] isEqualToString:@"iPod touch"])
        {
            if ([[UIApplication sharedApplication] canOpenURL:[NSURL URLWithString:@"tel:+74957371737"]])
            {
                self.reqStr = ((NSString *)sender);
                nameStr = _contact.name;
                UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Позвонить?" message:_reqStr delegate:self cancelButtonTitle:@"Отмена" otherButtonTitles:@"Вызов", nil];
                alert.tag = kPhoneAlert;
                [alert show];
                [alert release];
            }
            else
            {
                self.reqStr = ((NSString *)sender);
                nameStr = _contact.name;
                UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Не возможно сделать вызов" message:@"Cохранить контакт в справочнике?" delegate:self cancelButtonTitle:@"Отмена" otherButtonTitles:@"Ок", nil];
                alert.tag = kSaveContactAlert;
                [alert show];
                [alert release];
                
            }
        }
    
}
-(IBAction)emailBtnDidPress:(id)sender
{
    
        nameStr = _contact.name;
        self.reqStr = ((NSString *)sender);
        emailStr=@"";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Написать письмо" message:_reqStr delegate:self cancelButtonTitle:@"Отмена" otherButtonTitles:@"Ок", nil];
        alert.tag = kEmailAlert;
        [alert show];
        [alert release];
    
}
-(IBAction)twitterBtnDidPress:(id)sender
{
    
        self.reqStr = ((NSString *)sender);
        emailStr=@"";
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Перейти в твиттер" message:_reqStr delegate:self cancelButtonTitle:@"Отмена" otherButtonTitles:@"Ок", nil];
        alert.tag = kTwitterAlert;
        [alert show];
        [alert release];
        
    
}
-(void) addContact:(NSString *)phoneNumber
{
    CFErrorRef *error=nil;
    
    NSString * addressString1 = @"";
    
    NSString * addressString2 = @"";
    
    NSString * cityName = @"Москва";
    
    NSString * stateName = @"РФ";
    
    NSString * postal = @"";
    
    NSString * emailString = emailStr.length?emailStr:@"info@ufs-federation.com";
    //    _emailData.titleLabel.text.length?_emailData.titleLabel.text:
    NSString * prefName = [NSString stringWithFormat: @"UFS %@",nameStr];
    //    [NSString stringWithFormat: @"Siemens %@",_nameData.text]
    ABAddressBookRef libroDirec = ABAddressBookCreateWithOptions(NULL, nil);
    
    ABRecordRef persona = ABPersonCreate();
    
    NSData *dataRef = UIImagePNGRepresentation([UIImage imageNamed:@"logo_phone.png"]);
    ABPersonSetImageData(persona, (CFDataRef)dataRef, nil);
    
    ABRecordSetValue(persona, kABPersonFirstNameProperty, prefName, nil);
    
    ABMutableMultiValueRef multiHome = ABMultiValueCreateMutable(kABMultiDictionaryPropertyType);
    
    //    NSMutableDictionary *addressDictionary = [[NSMutableDictionary alloc] init];
    
    NSString *homeStreetAddress=[addressString1 stringByAppendingString:addressString2];
    
    //    [addressDictionary setObject:homeStreetAddress forKey:(NSString *) kABPersonAddressStreetKey];
    //
    //    [addressDictionary setObject:cityName forKey:(NSString *)kABPersonAddressCityKey];
    //
    //    [addressDictionary setObject:stateName forKey:(NSString *)kABPersonAddressStateKey];
    //
    //    [addressDictionary setObject:postal forKey:(NSString *)kABPersonAddressZIPKey];
    NSDictionary * addressDictionary = [NSDictionary dictionaryWithObjects:@[homeStreetAddress,cityName,stateName,postal] forKeys:@[(NSString *) kABPersonAddressStreetKey,(NSString *)kABPersonAddressCityKey,(NSString *)kABPersonAddressStateKey,(NSString *)kABPersonAddressZIPKey]];
    bool didAddHome = ABMultiValueAddValueAndLabel(multiHome, addressDictionary, kABHomeLabel, NULL);
    
    if(didAddHome)
    {
        ABRecordSetValue(persona, kABPersonAddressProperty, multiHome, NULL);
        
        NSLog(@"Address saved.....");
    }
    
    //    [addressDictionary release];
    
    //##############################################################################
    
    ABMutableMultiValueRef multiPhone = ABMultiValueCreateMutable(kABMultiStringPropertyType);
    
    bool didAddPhone = ABMultiValueAddValueAndLabel(multiPhone, phoneNumber, kABPersonPhoneMobileLabel, NULL);
    
    if(didAddPhone){
        
        if (ABRecordSetValue(persona, kABPersonPhoneProperty, multiPhone,nil))
        {
            NSLog(@"Phone Number saved......");
        }
        
    }
    
    CFRelease(multiPhone);
    
    //##############################################################################
    
    ABMutableMultiValueRef emailMultiValue = ABMultiValueCreateMutable(kABPersonEmailProperty);
    
    bool didAddEmail = ABMultiValueAddValueAndLabel(emailMultiValue, emailString, kABOtherLabel, NULL);
    
    if(didAddEmail){
        if (ABRecordSetValue(persona, kABPersonEmailProperty, emailMultiValue, nil))
            NSLog(@"Email saved......");
    }
    
    CFRelease(emailMultiValue);
    
    //##############################################################################
    
    if (ABAddressBookAddRecord(libroDirec, persona, error))
    {
        NSLog(@"Person saved......");
    }
    
    CFRelease(persona);
    
    BOOL x = ABAddressBookSave(libroDirec, nil);
    
    CFRelease(libroDirec);
    NSString * errorString=@"";
    if (x)
    {
        errorString = [NSString stringWithFormat:@"Контакт добавлен в телефонный справочник"];
    }
    else
    {
        errorString = [NSString stringWithFormat:@"Ошибка сохранения контакта"];
    }
    
    UIAlertView * errorAlert = [[UIAlertView alloc] initWithTitle:@"Информация" message:errorString delegate:self cancelButtonTitle:@"OK" otherButtonTitles:nil];
    
    [errorAlert show];
    
    [errorAlert release];
}
-(void)sendEmailMessage:(NSString *)emailString
{
    if ([emailString length]) {
        
        if([MFMailComposeViewController canSendMail])
        {
            MFMailComposeViewController *controller = [[MFMailComposeViewController alloc] init];
            [controller setMailComposeDelegate:self];
            [controller.navigationBar setTintColor:[UIColor colorWithRed:33./255 green:93./255 blue:155./255 alpha:1]];
            //                            [controller setToRecipients:@[]];
            [controller setToRecipients:@[emailString]];
            [controller setSubject:nameStr];
            //               [controller setMessageBody:[NSString stringWithFormat:@"<a href = \"http://news.mail.ru/\">Новости@Mail.RU</a><br/><b>%@</b><br/>%@<a href=\"%@\">Читать полностью...<a/>", title, text, [_mainObject url] ? [_mainObject url] : @""] isHTML:YES];
            controller.modalPresentationStyle = UIModalPresentationFormSheet;
            //[self.navigationController.topViewController presentModalViewController:controller animated:YES];
            /* tsv */[self.navigationController.topViewController presentViewController:controller animated:YES completion:nil];
            [controller release];
        }
        else
        {
            UIAlertView *alertV = [[UIAlertView alloc] initWithTitle:@"Ошибка!" message:@"укажите почтовый аккаунт в настройках устройства!" delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil];
            [alertV show];
            [alertV release];
        }
    }
}
#pragma -mark AlertView

- (void)alertView:(UIAlertView *)alertView clickedButtonAtIndex:(NSInteger)buttonIndex
{
    if (buttonIndex==1)
        switch (alertView.tag) {
            case kTwitterAlert:
            {
            //   NSString *strUrl = [NSString stringWithFormat:@«twitter://%@»,_reqStr];
             //   NSLog(@"%@",strUrl);
if ( [[UIApplication sharedApplication] canOpenURL:[NSURL URLWithString:@"twitter:///user?id=240162732"]] ) {
            [[UIApplication sharedApplication] openURL: [NSURL URLWithString:@"twitter:///user?id=240162732"]];
//            id=1039278950
                
        } else {
            
             [[UIApplication sharedApplication] openURL:[NSURL URLWithString: _reqStr]];
        }

              
            }
                break;
            case kSaveContactAlert:
            {
                NSString *phoneNumber = [NSString stringWithFormat:@"+%@",[Helper removeAllButDigits:_reqStr]];
                NSLog(@"number %@",phoneNumber);
                
                [self addContact:phoneNumber];
            }
                break;
            case kPhoneAlert:
            {
                NSString *phoneNumber = [NSString stringWithFormat:@"tel:+%@",[Helper removeAllButDigits:_reqStr]];
                NSLog(@"number %@",phoneNumber);
                [[UIApplication sharedApplication] openURL:[NSURL URLWithString:phoneNumber]];
            }
                break;
            case kEmailAlert:
            {
                [self sendEmailMessage:_reqStr];
            }
                break;
            default:
                break;
        }
}

- (void)mailComposeController:(MFMailComposeViewController*)controller didFinishWithResult:(MFMailComposeResult)result error:(NSError*)error
{
    [controller dismissViewControllerAnimated:YES completion:^(void){
        NSString *errorStr = @"";
        if (result==MFMailComposeResultSent)
        {
            errorStr = @"Успешно отправлено";
        }
        else if (result==MFMailComposeResultFailed)
        {
            errorStr = @"Не удалось отправить";
        }
        else if (result==MFMailComposeResultSaved)
        {
            errorStr = @"Сохраненно";
        }
        else
        {
            return;
        }
        UIAlertView *alertV = [[UIAlertView alloc] initWithTitle:@"Email" message:errorStr delegate:self cancelButtonTitle:@"Ok" otherButtonTitles:nil];
        [alertV show];
        [alertV release];
        
    }];
}
-(void)dataButtonTap: (UIButton *)sender
{
    NSLog(@"button tap %d",sender.tag);
    NSInteger index = sender.tag;
    NSArray *contactDataArray = [_contact.data allObjects];
    contactDataArray = [contactDataArray sortedArrayUsingDescriptors:@[[NSSortDescriptor sortDescriptorWithKey:@"identifier" ascending:YES]]];
    DataForContacts *dataObj = [contactDataArray objectAtIndex:index];
//    [_contactDetailTV deselectRowAtIndexPath:indexPath animated:NO];
    if ([dataObj.title rangeOfString:@"mail"].location!=NSNotFound)
    {
        [self performSelector:@selector(emailBtnDidPress:) withObject:dataObj.value];
    }
    else if ([dataObj.title rangeOfString:@"Твиттер"].location!=NSNotFound)
    {
        [self performSelector:@selector(twitterBtnDidPress:) withObject:dataObj.value];
    }
    else if ([dataObj.title rangeOfString:@"Факс"].location==NSNotFound)
    {
        
        [self performSelector:@selector(phoneButtDidPress:) withObject:dataObj.value];
    }

}
#pragma -mark Reachable Methods
- (void) reachOn: (NSNotification *)notif
{
    NSLog(@"reachhhhh");
      if (self.mapView.hidden || !_mapView)
      {
          if (imageForNotReach)
          {
              [imageForNotReach removeFromSuperview];
              imageForNotReach = nil;
          }
//         
          if (!_mapView)
          {
              [self createMapView];
              [indicator startAnimating];
          }
          else
          {
              _mapView.hidden=FALSE;
          }

      }
}
- (void) reachOff: (NSNotification *)notif
{
    if (indicator)
    {
        [indicator stopAnimating];
    }
    if (!imageForNotReach && _mapView.hidden)
    {
        imageForNotReach = [[UIImageView alloc] initWithFrame:CGRectMake(30,addresLable.height+40, self.view.width-60, 120)];
        imageForNotReach.image = [UIImage imageNamed:@"icn_logo"];
        [bgScroll addSubview:imageForNotReach];
        [imageForNotReach release];
    }

    
}

@end
