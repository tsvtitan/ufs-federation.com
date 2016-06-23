
package com.ufsic.core.counters;

/* tsv */

import java.util.ArrayList;
import java.util.HashSet;


import com.distimo.sdk.DistimoSDK;
import com.google.android.gms.analytics.GoogleAnalytics;
import com.google.android.gms.analytics.HitBuilders;

import com.google.android.gms.analytics.Tracker;

import android.app.Activity;
import android.app.Application;
import android.content.Context;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageManager;
import android.content.pm.PackageManager.NameNotFoundException;


final public class AnalyticsCounter {

	private static final String googleKey = "UA-52688652-1";
	private static final String yandexKey = "25969";
	private static final String distimoKey = "H6uGlqea9nhSn2No";
	
	public static final String actionTap = "tap";
	
	public enum Type {
		DISTIMO, YANDEX, GOOGLE;	
	}
	
	abstract private class System {
		
		private Application application;
		private String key;  
		
		System(Application application, String key) {
			super();
			this.application = application;
			this.key = key;
		}
		
		final Application getApplication() {
			return application;
		}
		
		final String getKey() {
			return key;
		}
		
		final Context getContext() {
			return getApplication().getApplicationContext();
		}
		
		void forceSend() {
			//
		}
		
		void start(Activity activity) {
			//
		}
		
		void stop(Activity activity) {
			//
		}
		
		void event(String text) {
			//
		}
		
		void event(String screen, String category, String action, String value) {
			
			action = (action!=null)?String.format("(%s)",action):"";
			category = (screen!=null)?String.format("%s/%s",screen,category):category;
			category = String.format("[%s] %s",category,action).trim();
			value = (value!=null)?String.format(": %s",value):"";
			event(String.format("%s%s",category,value).trim());
		}
		
		void event(String screen, String[] categories, String action, String value) {
			
			StringBuilder category = new StringBuilder();
			for (String s: categories) {
				category.append((category.length()!=0)?"/":"").append(s);
			}
			if (category.length()>0) {
				event(screen,category.toString(),action,value);
			}
		}
		
		void event(String[] screens, String category, String action, String value) {
			
			StringBuilder screen = new StringBuilder();
			for (String s: screens) {
				screen.append((screen.length()!=0)?"/":"").append(s);
			}
			if (screen.length()>0) {
				event(screen.toString(),category,action,value);
			}
		}
		
	}
	
	private class Distimo extends System {
		
		Distimo (Application application, String key) {
			super(application,key);
			DistimoSDK.onCreate(getContext(),key);
		}
		
		@Override
		void event(String text) {
			DistimoSDK.onBannerClick(text);
		}

	}
	
	private class Yandex extends System {

		private com.yandex.metrica.Counter counter = null;
		
		Yandex(Application application, String key) {
			super(application,key);
			
			try {
				com.yandex.metrica.Counter.initialize(getContext());
				
				counter = com.yandex.metrica.Counter.sharedInstance();
				counter.setApiKey(key);
				counter.setReportsEnabled(true);
				counter.setReportCrashesEnabled(false);
				
			} catch (Exception e) {
				counter = null;
			}
			
		}

		private com.yandex.metrica.Counter getCounter() {
			return counter;
		}
		
		private boolean isEnabled() {
			return getCounter()!=null;
		}
		
		@Override 
		void forceSend() {
			if (isEnabled()) getCounter().sendEventsBuffer();
		}
		
		@Override
		void start(Activity activity) {
			if (isEnabled()) getCounter().onResumeActivity(activity);
		}
		
		@Override
		void stop(Activity activity) {
			if (isEnabled()) getCounter().onPauseActivity(activity);
		}
		
		@Override
		void event(String text) {
			if (isEnabled()) {
				getCounter().reportEvent(text);
				forceSend();
			}
		}
	}
	
	private class Google extends System {

		private Tracker tracker = null;
		
		Google(Application application, String key) {
			super(application,key);
			
			GoogleAnalytics analytics = GoogleAnalytics.getInstance(getApplication());
			analytics.setAppOptOut(false);
			analytics.setDryRun(false);
			analytics.enableAutoActivityReports(application);
			
			tracker = analytics.newTracker(key);
			tracker.enableAutoActivityTracking(true);
			
		}

		private Tracker getTracker() {
			return tracker;
		}
		private boolean isEnabled() {
			return getTracker()!=null;
		}
		
		@Override
		void forceSend() {
			GoogleAnalytics.getInstance(getContext()).dispatchLocalHits();
		};
		
		@Override
		void start(Activity activity) {
			
			GoogleAnalytics.getInstance(getContext()).reportActivityStart(activity);
		}
		
		@Override
		void stop(Activity activity) {
			GoogleAnalytics.getInstance(getContext()).reportActivityStop(activity);
		}
		
		@Override
		void event(String screen, String category, String action, String value) {

			if (isEnabled()) {
				getTracker().setScreenName(screen);
				try {
					getTracker().send(new HitBuilders.EventBuilder().
							                          setCategory(category).
							                          setAction(action).
							                          setLabel(value).build());
				} finally {
					getTracker().setScreenName(null);
				}
			}
		}
		
	}
	
	@SuppressWarnings("serial")
	private class Systems extends ArrayList<System> {
		
		
		void forceSend() {
			for (System system: this) {
				system.forceSend();
			}
		}
		
		void start(Activity activity) {
			for (System system: this) {
				system.start(activity);
			}
		}
		
		void stop(Activity activity) {
			for (System system: this) {
				system.stop(activity);
			}
		}
		
		void event(String screen, String category, String action, String value) {
			for (System system: this) {
				system.event(screen,category,action,value);
			}
		}
		
		void event(String screen, String[] categories, String action, String value) {
			for (System system: this) {
				system.event(screen,categories,action,value);
			}
		}
		
		void event(String[] screens, String category, String action, String value) {
			for (System system: this) {
				system.event(screens,category,action,value);
			}
		}
	}
	
	private static AnalyticsCounter counter = null;
	private Systems systems = null;
	
	private AnalyticsCounter() {
		super();
		this.systems =  new Systems();
	}
	
	private AnalyticsCounter(Application application, HashSet<Type> types) {
		
		this();
		
		String dKey = distimoKey;
		String yKey = yandexKey;
		String gKey = googleKey;
		
		Context context = application.getApplicationContext();
		if (context!=null) {
			
			try {
				
				ApplicationInfo ai = context.getPackageManager().getApplicationInfo(context.getPackageName(), PackageManager.GET_META_DATA);
				if (ai!=null) {
					dKey = ai.metaData.getString("counterDistimoKey",dKey);
					yKey = ai.metaData.getString("counterYandexKey",yKey);
					gKey = ai.metaData.getString("counterGoogleKey",gKey);
				}
				
			} catch (NameNotFoundException e) {
				//
			}
		}
		
		if (types.contains(Type.DISTIMO)) this.systems.add(new Distimo(application,dKey));
		if (types.contains(Type.YANDEX)) this.systems.add(new Yandex(application,yKey));
		if (types.contains(Type.GOOGLE)) this.systems.add(new Google(application,gKey));
		
	}
	
	private Systems getSystems() {
	  return systems;	
	}
	
	private static AnalyticsCounter getInstance(Application application, HashSet<Type> types) {
		
		if (counter==null) {
			counter = new AnalyticsCounter(application,types);
		}
		return counter;
	}
	
	private static AnalyticsCounter getInstance(Application application) {
		
		HashSet<Type> types = new HashSet<Type>();
		types.add(Type.DISTIMO);
		types.add(Type.YANDEX);
		types.add(Type.GOOGLE);
		return getInstance(application,types);
	}
	
	public static void initialize(Activity activity) {
		getInstance(activity.getApplication());
	}
	
	public static synchronized void forceSend() {
		if (counter!=null) {
			counter.getSystems().forceSend();
		}
	}
	
	public static synchronized void start(Activity activity) {
		if (counter!=null) {
			counter.getSystems().start(activity);
		}
	}
	
	public static synchronized void stop(Activity activity) {
		if (counter!=null) {
			counter.getSystems().stop(activity);
		}
	}
	
	public static synchronized void eventScreen(String screen, String category, String action, String value) {
		if (counter!=null) {
			counter.getSystems().event(screen,category,action,value);
		}
	}
	
	public static synchronized void eventScreen(String screen, String[] categories, String action, String value) {
		if (counter!=null) {
			counter.getSystems().event(screen,categories,action,value);
		}
	}
	
	public static synchronized void eventScreens(String[] screens, String category, String action, String value) {
		if (counter!=null) {
			counter.getSystems().event(screens,category,action,value);
		}
	}
	
}
