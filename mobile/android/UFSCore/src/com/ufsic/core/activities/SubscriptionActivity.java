package com.ufsic.core.activities;

/* tsv */

import java.util.ArrayList;

import com.astuetz.PagerSlidingTabStrip;
import com.ufsic.core.fragments.AllKeywordsFragment;
import com.ufsic.core.fragments.NewKeywordsFragment;
import com.ufsic.core.interfaces.ISubscriptionChanger;
import com.ufsic.core.loaders.FragmentLoader;
import com.ufsic.core.loaders.FragmentLoaderManager;
import com.ufsic.core.widgets.ActionBarUfs;

import com.ufsic.core.R;

import android.content.res.Resources;
import android.os.Bundle;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.view.View;
import android.view.View.OnClickListener;

public class SubscriptionActivity extends FragmentActivity implements ISubscriptionChanger  {

	public static final String TITLE = "title";
	public static final String KEYWORDS = "keywords";
	private String title;
	private String[] keywords;
	
	private PagerSlidingTabStrip tabs;
	private PagerAdapter adapter;
	private ViewPager pager;
	
	private AllKeywordsFragment allKeywords = null;
	private NewKeywordsFragment newskeywords = null;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		setContentView(R.layout.subscription_activity);
		
		Bundle bundle = getIntent().getExtras();
		if (bundle == null || !bundle.containsKey(KEYWORDS) || !bundle.containsKey(TITLE)) {
			finish();
			return;
		}
		
		setWidgets(bundle);
	}
	
	private void setWidgets(Bundle bundle) {
			
		title = bundle.getString(TITLE);
		keywords = bundle.getStringArray(KEYWORDS);
		
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.sa_action_bar);
		actionBar.setSingleText(title);
		actionBar.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
			}
		});
		
		allKeywords = AllKeywordsFragment.newInstance();
		newskeywords = NewKeywordsFragment.newInstance(keywords,this);
		
		tabs = (PagerSlidingTabStrip) findViewById(R.id.sa_tabs);
		adapter = new PagerAdapter(getSupportFragmentManager());
		
		pager = (ViewPager) findViewById(R.id.sa_pager);
		pager.setAdapter(adapter);
		
		tabs.setShouldExpand(true);
		tabs.setIndicatorHeight(10);
		tabs.setTextSize(30);
		
		tabs.setViewPager(pager);
		
	}
	
	public class PagerAdapter extends FragmentPagerAdapter {

		private final ArrayList<String> titles = new ArrayList<String>();
		

		public PagerAdapter(FragmentManager fm) {
			
			super(fm);
			
			Resources res = getResources();
			titles.add(res.getString(R.string.subscription_new_keywords));
			titles.add(res.getString(R.string.subscription_all_keywords));
		}

		@Override
		public CharSequence getPageTitle(int position) {
			return titles.get(position);
		}

		@Override
		public int getCount() {
			return titles.size();
		}

		@Override
		public Fragment getItem(int position) {
			
			Fragment ret = null;
			switch (position) {
				case 0: {
					ret = newskeywords;
					break;
				}
				case 1: {
					ret = allKeywords;
					break;
				}
				default: ret = newskeywords;
			}
			return ret;
		}

	}

	@Override
	public void reload() {
		
		if (allKeywords!=null) {
			allKeywords.reloadListView();
		}
		int pos = pager.getCurrentItem();
		if (pos!=1) {
			pager.setCurrentItem(1,true);
		}
		
	}
	
}
