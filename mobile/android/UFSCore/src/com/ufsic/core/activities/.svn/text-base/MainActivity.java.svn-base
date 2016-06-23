package com.ufsic.core.activities;

import java.util.ArrayList;

import com.jeremyfeinstein.slidingmenu.lib.SlidingMenu;
import com.ufsic.core.beans.CategoryBean;
import com.ufsic.core.db.DatabaseManager;
import com.ufsic.core.fragments.LeftMenuFragment;
import com.ufsic.core.fragments.NewsListFragment;
import com.ufsic.core.interfaces.IMenuFragment;
import com.ufsic.core.utils.SharedPreferencesWrap;
import com.ufsic.core.utils.ToolBox;
import com.ufsic.core.widgets.ActionBarUfs;

import com.ufsic.core.R;
import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.view.View.OnClickListener;

public class MainActivity extends FragmentActivity {

	private SlidingMenu menu;
	private ActionBarUfs actionBar;
	private boolean firstTime = false;
	/* tsv */
	final public static String ScreenName = "Главный экран";
	private long defaultDelay = 1000;
	/* tsv */
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.main_activity);
		
		menu = new SlidingMenu(this);
		menu.setMode(SlidingMenu.LEFT);
		menu.setTouchModeAbove(SlidingMenu.TOUCHMODE_FULLSCREEN);
		menu.setBehindOffsetRes(R.dimen.action_bar_height);
		menu.setBehindScrollScale(0.0f);
		menu.setShadowDrawable(null);
		menu.setFadeEnabled(false);
		menu.setMenu(R.layout.left_menu);
		menu.attachToActivity(this, SlidingMenu.SLIDING_CONTENT);
		menu.post(new Runnable() {

	        @Override
	        public void run() {

	           menu.showMenu();

	        }
	    });
		menu.setOnOpenedListener(new SlidingMenu.OnOpenedListener() {
			
			@Override
			public void onOpened() {
				
				Fragment fragment = getSupportFragmentManager().findFragmentById(R.id.content_container);
				if (fragment!=null && fragment instanceof IMenuFragment) {
					((IMenuFragment)fragment).hide();
				}
				
			}
		});
		
		menu.setOnClosedListener(new SlidingMenu.OnClosedListener() {
			
			@Override
			public void onClosed() {
				
				Fragment fragment = getSupportFragmentManager().findFragmentById(R.id.content_container);
				if (fragment!=null && fragment instanceof IMenuFragment) {
					((IMenuFragment)fragment).show();
				}
			}
		});
		
		actionBar = (ActionBarUfs) findViewById(R.id.ma_action_bar);
		actionBar.setOnClickListener(actionBarClickListener);

		/*getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.left_menu_container, LeftMenuFragment.newInstance())
		.commit();
		
		//новости компании
		/*getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.content_container, NewsListFragment.newInstance("16", "", new String[]{"Новости компании"}))
		.commit();*/
		
		//setActionBarText("Новости компании");
		
		/* tsv */
		
		String categoryDelay = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.CATEGORY_DELAY);
		
		long delay = defaultDelay;
		if (!ToolBox.isEmpty(categoryDelay)) {
			try {
				delay = Long.parseLong(categoryDelay);
			} catch (NumberFormatException e) {
				delay = defaultDelay;
			}
		}
		
		
		
		String categoryId = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.CATEGORY_ID);
		
		getSupportFragmentManager().beginTransaction()
								   .replace(R.id.left_menu_container, LeftMenuFragment.newInstance(categoryId))
								   .commit();
		
		String title = "Новости компании";
		getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.content_container, NewsListFragment.newInstance("16", "", new String[]{title}))
		.commit();
		
		setActionBarText(title);
		
		delay = delay + 500;
		
		new CountDownTimer(delay,delay) {
	
			@Override
			public void onFinish() {
				menu.showContent();
			}

			@Override
			public void onTick(long arg0) {
				this.cancel();
			}
		}.start();
		
		menu.showMenu();
		
		/* tsv */
		
		setActionBarRightBtn(true);
	}
	
	private OnClickListener actionBarClickListener = new OnClickListener() {
		
		@Override
		public void onClick(View v) {
			
			
			/* tsv */
			int id = v.getId();
			if (id == R.id.abu_left_btn) {
				if (menu.isMenuShowing()) {
					menu.showContent();
				} else {
					menu.showMenu();
				}
			} else if (id == R.id.abu_right_btn) {
				try {
					String[] ids = ((NewsListFragment) getSupportFragmentManager().findFragmentById(R.id.content_container)).getIds();
					
					Intent intent = new Intent(MainActivity.this, CalendarActivity.class);
					intent.putExtra(CalendarActivity.CATEGORY_ID, ids[0]);
					intent.putExtra(CalendarActivity.SUBCATEGORY_ID, ids[1]);
					startActivityForResult(intent, 1);
				} catch (Exception e) {}
			}
			/* tsv */
			
			///??? doesn't work in library
			/*switch (id) {
			case R.id.abu_left_btn:
				
				if (menu.isMenuShowing()) {
					menu.showContent();
				} else {
					menu.showMenu();
				}
				break;
			case R.id.abu_right_btn:
				try {
					String[] ids = ((NewsListFragment) getSupportFragmentManager().findFragmentById(R.id.content_container)).getIds();
					
					Intent intent = new Intent(MainActivity.this, CalendarActivity.class);
					intent.putExtra(CalendarActivity.CATEGORY_ID, ids[0]);
					intent.putExtra(CalendarActivity.SUBCATEGORY_ID, ids[1]);
					startActivityForResult(intent, 1);
				} catch (Exception e) {}
				break;
			}*/
		}
	};
	
	public void switchContent(Fragment fragment, boolean showContent) {
		
		getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.content_container, fragment)
		.commit();
		
		/* tsv */
		if (showContent) {
			menu.showContent();
		} else {
			menu.showMenu();
		}
		/* tsv */
	}
	
	/* tsv */
	public void switchContent(Fragment fragment) {
		switchContent(fragment,true);
	}
	/* tsv */
	
	public void setActionBarText(String text) {
		actionBar.setSingleText(text);
	}
	
	public void setActionBarRightBtn(boolean visible) {
		if(visible)
			actionBar.setRightBtn(R.drawable.btn_calendar_selector);
		else
			actionBar.delRightBtn();
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		getSupportFragmentManager().findFragmentById(R.id.content_container).onActivityResult(requestCode, resultCode, data);
	}
	
	public void onBackPressed() {
		if (menu.isMenuShowing()) {
			menu.showContent();
		} else {
			super.onBackPressed();
		}
	};
}
