package ru.ideast.ufs.activities;

import com.jeremyfeinstein.slidingmenu.lib.SlidingMenu;

import ru.ideast.ufs.R;
import ru.ideast.ufs.fragments.LeftMenuFragment;
import ru.ideast.ufs.fragments.NewsListFragment;
import ru.ideast.ufs.widgets.ActionBarUfs;
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
		
		actionBar = (ActionBarUfs) findViewById(R.id.ma_action_bar);
		actionBar.setOnClickListener(actionBarClickListener);

		getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.left_menu_container, LeftMenuFragment.newInstance())
		.commit();
		
		//новости компании
		getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.content_container, NewsListFragment.newInstance("16", ""))
		.commit();
		
		setActionBarText("Новости компании");
		setActionBarRightBtn(true);
		
		
	}
	
	/* tsv */
	public void showMenu() {
		
		if (!menu.isMenuShowing()) {
			menu.showMenu();
		}
	}
	
	public boolean getFirstTime() {
		return firstTime;
	}
	
	public void setFirstTime(boolean firstTime){
		this.firstTime = firstTime;
	}
	/* tsv */
	
	private OnClickListener actionBarClickListener = new OnClickListener() {
		
		@Override
		public void onClick(View v) {
			
			switch (v.getId()) {
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
			}
		}
	};
	
	public void switchContent(Fragment fragment) {
		getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.content_container, fragment)
		.commit();
		
		menu.showContent();
	}
	
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
