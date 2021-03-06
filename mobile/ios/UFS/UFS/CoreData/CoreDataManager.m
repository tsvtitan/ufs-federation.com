//
//  CoreDataManager.m
//  Copyright 2012 iD EAST. All rights reserved.
//

#import "CoreDataManager.h"
#import "DataTransformer.h"


static CoreDataManager *kCoreDataManager = nil;

void checkAndSetEx(id object, NSString *propertyKey, id newValue, BOOL forced)
{
    // Если newValue является значением [NSNull null]
	if (newValue == [NSNull null])
		newValue = nil;
	
#ifdef DEBUG_MODE
	NSError *error = nil;
	if (![object validateValue:&newValue forKey:propertyKey error:&error])
	{
		NSLog(@"(!!!) [checkAndSet:] [%@.%@] [ERROR: %@]", NSStringFromClass([object class]), propertyKey, error);
	}
#endif
	
	id oldValue = [object valueForKey:propertyKey];
	
	if ((oldValue && newValue && ![oldValue isEqual:newValue]) ||
		((!!oldValue) != (!!newValue) /* либо old == nil, либо new == nil */) || forced)
	{
		@try
		{
			[object setValue:newValue forKey:propertyKey];
		}
		@catch(NSException *exception1)
		{
			NSLog(@"(!!!) [checkAndSet:] Exception \"%@\", reason: \"%@\"", [exception1 name], [exception1 reason]);
		}
		@catch(NSException *exception2)
		{
			NSLog(@"(!!!) [checkAndSet:] Exception (2) \"%@\", reason: \"%@\"", [exception2 name], [exception2 reason]);
		}
	}
}

/* tsv */
void checkAndSet(id object, NSString *propertyKey, id newValue)
{
    checkAndSetEx(object,propertyKey,newValue,NO);
}
/* tsv */

@interface CoreDataManager()

// Метод для изменения времени последнего обновления обьектов в контексте
- (void)changeModifyTimeInObjectsFromContext:(NSManagedObjectContext *)aContext;

@end

@implementation CoreDataManager

@synthesize managedObjectContext = __managedObjectContext;
@synthesize managedObjectContextForParsing = __managedObjectContextForParsing;
@synthesize managedObjectModel = __managedObjectModel;
@synthesize persistentStoreCoordinator = __persistentStoreCoordinator;


+ (CoreDataManager *)shared
{
	if (kCoreDataManager == nil)
	{
		kCoreDataManager = [[CoreDataManager alloc] init];
	}
	return kCoreDataManager;
}

- (id)init
{
    self = [super init];
    if (self)
	{
		[self persistentStoreCoordinator];
    }
    
    return self;
}

- (void)dealloc
{
	[[NSNotificationCenter defaultCenter] removeObserver:self];

	kCoreDataManager = nil;
    
	[__managedObjectContext release], __managedObjectContext = nil;
	[__managedObjectContextForParsing release], __managedObjectContextForParsing = nil;
	[__persistentStoreCoordinator release], __persistentStoreCoordinator = nil;
	[__managedObjectModel release], __managedObjectModel = nil;
	
	[super dealloc];
}

#pragma mark - Accessors

+ (id)createObject:(NSString *)entityName inMainContext:(BOOL)mainContext
{
	NSManagedObjectContext *context = mainContext ? [CoreDataManager shared].managedObjectContext : [CoreDataManager shared].managedObjectContextForParsing;
    
	Class EntityClass = NSClassFromString(entityName);
	
	NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:context];
    NSManagedObject *object = [[EntityClass alloc] initWithEntity:entity insertIntoManagedObjectContext:context];
	
	return [(id)object autorelease];
}

+ (id)anyObject:(NSString *)entityName inMainContext:(BOOL)mainContext
{
	NSManagedObjectContext *context = mainContext ? [CoreDataManager shared].managedObjectContext : [CoreDataManager shared].managedObjectContextForParsing;

	NSArray *result = nil;
	
	NSFetchRequest *request = [[NSFetchRequest alloc] init];
	request.entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:context];
	request.predicate = nil;
	request.fetchLimit = 1;
	@try
	{
		result = [context executeFetchRequest:request error:nil];
	}
	@catch(NSException *exception)
	{
//		DLog(@"(!!!) Exception \"%@\", reason: \"%@\"", [exception name], [exception reason]);
	}
	[request release];
	
	return [result objectAtIndex:0];
}

+ (id)object:(NSString *)entityName withIdentifier:(id)identifier inMainContext:(BOOL)mainContext
{
	NSManagedObjectContext *context = mainContext ? [CoreDataManager shared].managedObjectContext : [CoreDataManager shared].managedObjectContextForParsing;

	Class EntityClass = NSClassFromString(entityName);
	
	NSManagedObject *object = [[CoreDataManager object:entityName predicate:[NSPredicate predicateWithFormat:@"identifier == %@", identifier] inMainContext:mainContext] retain];
	if (object == nil)
	{
		NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:context];
		object = [[EntityClass alloc] initWithEntity:entity insertIntoManagedObjectContext:context];
		checkAndSet(object, @"identifier", identifier);
	}
	
	return [object autorelease];
}

+ (id)object:(NSString *)entityName predicate:(NSPredicate *)predicate inMainContext:(BOOL)mainContext
{
	NSArray *result = [CoreDataManager objects:entityName withPredicate:predicate inMainContext:mainContext];
	
	if ([result count] > 0)
	{
		return [result objectAtIndex:0];
	}
	return nil;
}

+ (NSArray *)objects:(NSString *)entityName withPredicate:(NSPredicate *)predicate inMainContext:(BOOL)mainContext
{
	NSManagedObjectContext *context = mainContext ? [CoreDataManager shared].managedObjectContext : [CoreDataManager shared].managedObjectContextForParsing;

	NSArray *result = nil;
	
	NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:context];
	NSFetchRequest *request = [[NSFetchRequest alloc] init];
	[request setEntity:entity];
	[request setPredicate:predicate];
	@try
	{
		result = [context executeFetchRequest:request error:nil];
	}
	@catch(NSException *exception)
	{
//		DLog(@"(!!!) Exception \"%@\", reason: \"%@\"", [exception name], [exception reason]);
	}
	[request release];
	
	return ([result count] == 0 ? nil : result);
}


+ (NSArray *)objectsSort:(NSString *)entityName withPredicate:(NSPredicate *)predicate WithSortKey:(NSString*)sortKey WithAscending:(BOOL)ascending inMainContext:(BOOL)mainContext
{
	NSArray *result = nil;
	
	NSManagedObjectContext *context = mainContext ? CoreDataManager.shared.managedObjectContext : CoreDataManager.shared.managedObjectContextForParsing;
	NSEntityDescription *entity = [NSEntityDescription entityForName:entityName inManagedObjectContext:context];
	NSFetchRequest *req = [[NSFetchRequest alloc] init];
	[req setEntity:entity];
	[req setPredicate:predicate];
    [req setSortDescriptors:[NSArray arrayWithObjects:[[[NSSortDescriptor alloc] initWithKey:sortKey ascending:ascending] autorelease],nil]];
    
	@try
	{
		result = [context executeFetchRequest:req error:nil];
	}
	@catch(NSException *exception)
	{
        //		DLog(@"(!!!) Exception \"%@\", reason: \"%@\"", [exception name], [exception reason]);
	}
	[req release];
	
	return ([result count] == 0 ? nil : result);
}

#pragma mark - MainContext

+ (void)lockMainContext
{
	[[CoreDataManager shared].managedObjectContext lock];
}

+ (void)unlockMainContext
{
	[[CoreDataManager shared].managedObjectContext unlock];
}

+ (void)saveMainContext
{
	NSManagedObjectContext *context = [CoreDataManager shared].managedObjectContext;

    NSError *error = nil;
    if (context != nil)
    {
        // Проверяем были ли внесены изменения в контекст
        if ([context hasChanges])
        {
            // Изменяем время последнего обновления у обьектов в контексте
            [[CoreDataManager shared] changeModifyTimeInObjectsFromContext:context];
            
            // Сохраняем контекст
            if (![context save:&error]) {
                /*
                 Replace this implementation with code to handle the error appropriately.
                 
                 abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development. If it is not possible to recover from the error, display an alert panel that instructs the user to quit the application by pressing the Home button.
                 */
                NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
                abort();
            }
        }
    }
}

- (void)mainContextDidChanged:(NSNotification *)notification
{
	@synchronized(__managedObjectContextForParsing)
	{
		[__managedObjectContextForParsing lock];
		[__managedObjectContextForParsing mergeChangesFromContextDidSaveNotification:notification];
		[__managedObjectContextForParsing unlock];
	}
}

#pragma mark - ParsingContext

+ (void)lockParsingContext
{
	[[CoreDataManager shared].managedObjectContextForParsing lock];
}

+ (void)unlockParsingContext
{
	[[CoreDataManager shared].managedObjectContextForParsing unlock];
}

+ (void)saveParsingContext
{
	NSManagedObjectContext *context = [CoreDataManager shared].managedObjectContextForParsing;
	
    NSError *error = nil;
    if (context != nil)
    {
        NSLog(@"HasChanges: %d", context.hasChanges);
        
        // Проверяем были ли внесены изменения в контекст
        if ([context hasChanges]) {
            
            // Изменяем время последнего обновления у обьектов в контексте
            [[CoreDataManager shared] changeModifyTimeInObjectsFromContext:context];
            
            // Пытаемся сохранить контекст
            @try {
                [context save:&error];
            }
            @catch (NSException *exception) {
                error = nil;
                NSLog(@"[context save:&error]");
            }
            @finally {
                
            }
            
            if (error) {
                
                NSLog(@"Unresolved error %@, %@", error, [error userInfo]);
                abort();
            }
        }
    }
}

//- (void)mergeOnMainThread:(NSNotification *)notification
//{
//	[__managedObjectContext lock];
//	[__managedObjectContext mergeChangesFromContextDidSaveNotification:notification];
//	[__managedObjectContext unlock];
//    NSLog(@"Merged: %@\n", __managedObjectContext);
////    [TabBarController.shared refresh];
//}

- (void)mergeOnMainThread:(NSNotification *)notification
{
    // Достаем все обьекты, которые были либо изменены, либо добавлены в контекст
    
    // Костыль для работы ФРК
    [[notification.userInfo objectForKey:NSUpdatedObjectsKey] enumerateObjectsUsingBlock:^(id obj, NSUInteger idx, BOOL *stop) {
        
        //if ([obj class] == [GalleriesDB class]) {
            
            [(id)[__managedObjectContext objectWithID:[obj objectID]] identifier];
        //}
    }];
    
	[__managedObjectContext mergeChangesFromContextDidSaveNotification:notification];
}

- (void)parsingContextDidChanged:(NSNotification *)notification
{
	[self performSelectorOnMainThread:@selector(mergeOnMainThread:) withObject:notification waitUntilDone:NO];
}

#pragma mark - Actions
- (void)changeModifyTimeInObjectsFromContext:(NSManagedObjectContext *)aContext {
    
    // Перебираем все обьекты, которые были зарегестрированы за контекстом
    for (NSManagedObject *managedObject in aContext.registeredObjects) {
        
        // Проверяем был ли обьект изменен или только добавлен и если да, то назначаем ему lastModify, если такого св-ва нет, то пиздец
        if (managedObject.isUpdated || managedObject.isInserted) {
            
            [managedObject setValue:[NSNumber numberWithDouble:[[NSDate date] timeIntervalSince1970]] forKey:@"lastModify"];
        }
    }
}

- (void)clean
{
	[[NSNotificationCenter defaultCenter] removeObserver:self];
	
	[__managedObjectContext release], __managedObjectContext = nil;
	[__managedObjectContextForParsing release], __managedObjectContextForParsing = nil;
	[__persistentStoreCoordinator release], __persistentStoreCoordinator = nil;
}



- (void)removeDataOlderThanDate:(NSDate *)date {
    
    // Проверяем есть ли дата вообще
    if (date) {
       
        [[DataTransformer queue] addOperationWithBlock:^{
            
            // Проверяем существует ли NSPersistentStoreCoordinator и если да, то продолжаем
            if (self.persistentStoreCoordinator) {
                
                // Перебираем все NSEntityDescription в базе
                for (NSEntityDescription *entityDescription in self.managedObjectModel.entities) {
                    
                    // Получаем все обьекты для данного NSEntityDescription
                    NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] initWithEntityName:entityDescription.name];
                    NSArray *entityManagedObjects = [self.managedObjectContextForParsing executeFetchRequest:fetchRequest error:nil];
                    [fetchRequest release];
                    
                    // Перебираем все обьекты с данным NSEntityDescription
                    for (NSManagedObject *managedObject in entityManagedObjects) {
                        if ([entityDescription.name isEqualToString:@"NewsDB"] || [entityDescription.name isEqualToString:@"ActionsDB"] || [entityDescription.name isEqualToString:@"ContactsDB"] || [entityDescription.name isEqualToString:@"TableViewDB"])
                        {
//                            if ([entityDescription.name isEqualToString:@"NewsDB"])
//                            {
//                                for (NSManagedObject *object in ((NewsDB *)managedObject).files)
//                                {
//                                    [__managedObjectContextForParsing deleteObject:object];
//                                }
//                                for (NSManagedObject *object in ((NewsDB *)managedObject).relatedLinks)
//                                {
//                                    [__managedObjectContextForParsing deleteObject:object];
//                                }
//                            }
                        
                        // Проверяем дату последнего изменения обьекта
                        
                        NSDate *expired = [NSDate dateWithTimeIntervalSince1970:[[managedObject valueForKey:@"expired"] doubleValue]];
                        if ([expired compare:date] == NSOrderedAscending) {
                            // Если обьект давно не использовался и он не является избранным, то удаляем его из базы
                                [__managedObjectContextForParsing deleteObject:managedObject];
                            }
                            
                        }
                    }
                    
                }
                
                NSError *savingError = nil;
                // Проверяем были ли внесены изменения в контекст и если да, то пытаемся сохранить контекст
                if ([__managedObjectContextForParsing hasChanges] && ![__managedObjectContextForParsing save:&savingError]) {
                    
                    /*
                     Replace this implementation with code to handle the error appropriately.
                     
                     abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development. If it is not possible to recover from the error, display an alert panel that instructs the user to quit the application by pressing the Home button.
                     */
                    NSLog(@"Unresolved error %@, %@", savingError, [savingError userInfo]);
                    abort();
                }
            }
        }];
    }
}
-(BOOL) removeDataWithEntityName:(NSString *)entityName
{

    @synchronized([CoreDataManager shared].managedObjectContextForParsing)
    {
        
    }
    [[DataTransformer queue] addOperationWithBlock:^{
    if (self.persistentStoreCoordinator) {
        
        NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] initWithEntityName:entityName];
        NSArray *entityManagedObjects = [__managedObjectContextForParsing executeFetchRequest:fetchRequest error:nil];
        [fetchRequest release];
       
        NSLog(@"Removing from %@",entityName);
        // Перебираем все обьекты с данным NSEntityDescription
        if ([entityManagedObjects count])
        {
            for (NSManagedObject *managedObject in entityManagedObjects) {
                
                // Если обьект давно не использовался и он не является избранным, то удаляем его из базы
            [__managedObjectContextForParsing deleteObject:managedObject];
            }
             NSError *savingError = nil;
            if ([__managedObjectContextForParsing hasChanges] && ![__managedObjectContextForParsing save:&savingError]) {
                
                /*
                 Replace this implementation with code to handle the error appropriately.
                 
                 abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development. If it is not possible to recover from the error, display an alert panel that instructs the user to quit the application by pressing the Home button.
                 */
                NSLog(@"Unresolved error %@, %@", savingError, [savingError userInfo]);
                //
                //        [self performSelectorOnMainThread:@selector(complete:) withObject:kNotificationDataNotRemoved waitUntilDone:NO];
                
                
                abort();
            }

        }

    }
            // Проверяем были ли внесены изменения в контекст и если да, то пытаемся сохранить контекст
        if ([entityName isEqualToString:@"CategoriesDB"])
        {
            NSLog(@"Complete Removing from %@",entityName);
            [[NSNotificationCenter defaultCenter] postNotificationName:kNotificationDataRemoved object:nil];
        }
    }];
//    [CoreDataManager unlockParsingContext];
return true;
}
-(BOOL) removeLinkData
{
    
    @synchronized([CoreDataManager shared].managedObjectContextForParsing)
    {
        
    }
    [[DataTransformer queue] addOperationWithBlock:^{
        if (self.persistentStoreCoordinator) {
            
            NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] initWithEntityName:@"FileImageUrlDB"];
            [fetchRequest setPredicate:[NSPredicate predicateWithFormat:@"news == nil"]];
            NSArray *entityManagedObjects = [__managedObjectContextForParsing executeFetchRequest:fetchRequest error:nil];
            [fetchRequest release];
            
            NSLog(@"Removing from %@",@"FileImageUrlDB");
            // Перебираем все обьекты с данным NSEntityDescription
            if ([entityManagedObjects count])
            {
                for (NSManagedObject *managedObject in entityManagedObjects) {
                    
                    // Если обьект давно не использовался и он не является избранным, то удаляем его из базы
                    [__managedObjectContextForParsing deleteObject:managedObject];
                }
                NSError *savingError = nil;
                if ([__managedObjectContextForParsing hasChanges] && ![__managedObjectContextForParsing save:&savingError]) {
                    
                    /*
                     Replace this implementation with code to handle the error appropriately.
                     
                     abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development. If it is not possible to recover from the error, display an alert panel that instructs the user to quit the application by pressing the Home button.
                     */
                    NSLog(@"Unresolved error %@, %@", savingError, [savingError userInfo]);
                    //
                    //        [self performSelectorOnMainThread:@selector(complete:) withObject:kNotificationDataNotRemoved waitUntilDone:NO];
                    
                    
                    abort();
                }
                
            }
            
        }
        
        // Проверяем были ли внесены изменения в контекст и если да, то пытаемся сохранить контекст
        [UFSLoader requestPostAuth:@"first" andWidth:@""];
    }];
    //    [CoreDataManager unlockParsingContext];
    return true;
}

-(BOOL) removeAllData
{
    
    // Проверяем есть ли дата вообще
    
   
    
    @synchronized([CoreDataManager shared].managedObjectContextForParsing)
    {
        
    }
    
    
    [[DataTransformer queue] addOperationWithBlock:^{
        
        // Проверяем существует ли NSPersistentStoreCoordinator и если да, то продолжаем
        if (self.persistentStoreCoordinator) {
            [FileSystem removeAll];
            [FileSystem removePdf];
            // Перебираем все NSEntityDescription в базе
            for (NSEntityDescription *entityDescription in self.managedObjectModel.entities) {
                
                // Получаем все обьекты для данного NSEntityDescription
                NSFetchRequest *fetchRequest = [[NSFetchRequest alloc] initWithEntityName:entityDescription.name];
                NSArray *entityManagedObjects = [__managedObjectContextForParsing executeFetchRequest:fetchRequest error:nil];
                [fetchRequest release];
                
                // Перебираем все обьекты с данным NSEntityDescription
                for (NSManagedObject *managedObject in entityManagedObjects) {
                    
                    // Если обьект давно не использовался и он не является избранным, то удаляем его из базы
                    if (![managedObject respondsToSelector:@selector(is_favorite)])
                    {
                        
                        [__managedObjectContextForParsing deleteObject:managedObject];
                    }
                    else
                    {
                        
                        if ([[managedObject valueForKey:@"is_favorite"] isEqual:[NSNumber numberWithBool:NO]])
                        {
                            // класс FileSystem - custom надстройка над NSFileManager
                            [FileSystem removeAll];
                            //-----------удаляем запись в бд
                            [__managedObjectContextForParsing deleteObject:managedObject];
                        }
                    }
                    
                    
                }
            }
            
            
            NSError *savingError = nil;
            // Проверяем были ли внесены изменения в контекст и если да, то пытаемся сохранить контекст
            if ([__managedObjectContextForParsing hasChanges] && ![__managedObjectContextForParsing save:&savingError]) {
                
                /*
                 Replace this implementation with code to handle the error appropriately.
                 
                 abort() causes the application to generate a crash log and terminate. You should not use this function in a shipping application, although it may be useful during development. If it is not possible to recover from the error, display an alert panel that instructs the user to quit the application by pressing the Home button.
                 */
                NSLog(@"Unresolved error %@, %@", savingError, [savingError userInfo]);
                //
                [self performSelectorOnMainThread:@selector(complete:) withObject:kNotificationDataNotRemoved waitUntilDone:NO];
                
                
                abort();
            }
            else
            {
                [self performSelectorOnMainThread:@selector(complete:) withObject:kNotificationDataRemoved waitUntilDone:NO];
                
            }
            
            
            
            
        }
    }];
    return true;
    
}

+ (void)fillWithPreviewData
{
//	[CoreDataFiller createSomething];
}

#pragma mark - Core Data Stack

- (NSManagedObjectContext *)managedObjectContext
{
    if (__managedObjectContext == nil)
	{
		if (self.persistentStoreCoordinator != nil)
		{
			__managedObjectContext = [[NSManagedObjectContext alloc] init];
//            [__managedObjectContext setRetainsRegisteredObjects:YES];
			[__managedObjectContext setPersistentStoreCoordinator:self.persistentStoreCoordinator];
			
			// Откатывать изменения в случае конфликта
			__managedObjectContext.mergePolicy = NSMergeByPropertyStoreTrumpMergePolicy;
			
			[[NSNotificationCenter defaultCenter] addObserver:self
													 selector:@selector(mainContextDidChanged:)
														 name:NSManagedObjectContextDidSaveNotification
													   object:__managedObjectContext];
		}
    }
    return __managedObjectContext;
}

- (NSManagedObjectContext *)managedObjectContextForParsing
{
    if (__managedObjectContextForParsing == nil)
	{
		if (self.persistentStoreCoordinator != nil)
		{
			__managedObjectContextForParsing = [[NSManagedObjectContext alloc] init];
//            [__managedObjectContextForParsing setRetainsRegisteredObjects:YES];

            [__managedObjectContextForParsing setPersistentStoreCoordinator:self.persistentStoreCoordinator];

			// Перезаписывать изменения в случае конфликта
			__managedObjectContextForParsing.mergePolicy = NSMergeByPropertyObjectTrumpMergePolicy;
			
			[[NSNotificationCenter defaultCenter] addObserver:self
													 selector:@selector(parsingContextDidChanged:)
														 name:NSManagedObjectContextDidSaveNotification
													   object:__managedObjectContextForParsing];
		}
    }
    return __managedObjectContextForParsing;
}

- (NSManagedObjectModel *)managedObjectModel
{
    if (__managedObjectModel == nil)
	{
		NSString *modelPath = [[[NSBundle mainBundle] bundlePath] stringByAppendingPathComponent:@"UFS.momd/UFS.mom"];
		NSURL *modelURL = [NSURL fileURLWithPath:modelPath];
		__managedObjectModel = [[NSManagedObjectModel alloc] initWithContentsOfURL:modelURL];
	}
    return __managedObjectModel;
}

- (NSPersistentStoreCoordinator *)persistentStoreCoordinator
{
    if (__persistentStoreCoordinator != nil) {
        return __persistentStoreCoordinator;
    }
    
    NSLog(@"%@", [self managedObjectModel]);
    
    // Создаем координатор
    NSError *error = nil;
    NSURL *storeURL = [NSURL fileURLWithPath: [[APP_DELEGATE applicationDocumentsDirectory] stringByAppendingPathComponent:@"UFS.sqlite"]];
    __persistentStoreCoordinator = [[NSPersistentStoreCoordinator alloc] initWithManagedObjectModel:[self managedObjectModel]];
    
    NSMutableDictionary *pragmaOptions = [NSMutableDictionary dictionary];
    [pragmaOptions setObject:@"OFF" forKey:@"synchronous"];//отключаем проверку данных
    
    NSDictionary *storeOptions = [NSDictionary dictionaryWithObject:pragmaOptions forKey:NSSQLitePragmasOption];
    
    if (![__persistentStoreCoordinator addPersistentStoreWithType:NSSQLiteStoreType configuration:nil URL:storeURL options:storeOptions error:&error]) {
        
        // Проверяем есть ли база вообще. Если она есть, значит скорее всего ошибка связана с изменениями в ее модели
        if ([[NSFileManager defaultManager] fileExistsAtPath:storeURL.path]) {
            
            UIAlertView *alertView = [[UIAlertView alloc] initWithTitle:@"Структура БД изменилась.\nВся закешированная информация была стерта."
                                                                message:nil
                                                               delegate:nil
                                                      cancelButtonTitle:@"OK"
                                                      otherButtonTitles:nil, nil].autorelease;
            [alertView performSelectorOnMainThread:@selector(show) withObject:nil waitUntilDone:NO];
            
            // Удаляем базу
            [[NSFileManager defaultManager] removeItemAtURL:storeURL error:nil];
            
            // Сбрасываем все стнандартные настройки(Это для тех приложений, которые не делают в initialize AppDelegate registerDefaults)
            for (NSString *key in [NSUserDefaults standardUserDefaults].dictionaryRepresentation) {
                
                // [NSUserDefaults resetStandardUserDefaults] у меня не удалял обьекты из NSUserDefaults(что-то я не понимаю в этой жизни, бывает).
                [[NSUserDefaults standardUserDefaults] removeObjectForKey:key];
            }
            [[NSUserDefaults standardUserDefaults] synchronize];
            
            // Пытаемся опять добавить DB в координатор
            if (![__persistentStoreCoordinator addPersistentStoreWithType:NSSQLiteStoreType configuration:nil URL:storeURL options:storeOptions error:&error]) {
                
                // Если уж и на этот раз ничего не получилось, то и незачем такому приложению работать. CR - MAXX
//                DLog(@"Unresolved error %@, %@", error, [error userInfo]);
                abort();
            }
        }
    }
    
    return __persistentStoreCoordinator;
}
@end
