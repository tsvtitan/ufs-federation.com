package ru.ideast.ufs.activities;

import java.util.ArrayList;

import com.polites.android.GestureViewPager;

import ru.ideast.ufs.R;
import ru.ideast.ufs.adapters.GalleryAdapter;
import ru.ideast.ufs.beans.NewsBean;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.app.Activity;
import android.os.Bundle;
import android.support.v4.view.ViewPager.OnPageChangeListener;
import android.view.View;
import android.view.View.OnClickListener;

public class GalleryActivity extends Activity implements OnPageChangeListener, OnClickListener {
	
	public static final String URLS = "urls";
	
	private GalleryAdapter adapter;
	private ActionBarUfs actionBar;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.gallery_activity);
		
		Bundle bundle = getIntent().getExtras();
		if(bundle == null || !bundle.containsKey(URLS)) {
			finish();
			return;
		}
		
		ArrayList<NewsBean.Url> urls = (ArrayList<NewsBean.Url>) bundle.getSerializable(URLS);
		adapter = new GalleryAdapter(this, urls, this);
		
		setWidgets();
	}
	
	private void setWidgets() {
		actionBar = (ActionBarUfs) findViewById(R.id.ga_action_bar);
		actionBar.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
				return;
			}
		});
		
		GestureViewPager pager = (GestureViewPager) findViewById(R.id.ga_pager);
		pager.setAdapter(adapter);
		pager.setCurrentItem(0, false);
		pager.setOffscreenPageLimit(2);
		pager.setOnPageChangeListener(this);
		
		onPageSelected(0);
	}
	
	@Override
	public void onPageSelected(int pos) {
		adapter.setCurrentPosition(pos);
		
		String counter = String.format("%d из %d", pos + 1, adapter.getCount());
		actionBar.setSingleText(counter);
	}

	@Override
	public void onPageScrollStateChanged(int arg0) {}

	@Override
	public void onPageScrolled(int arg0, float arg1, int arg2) {}

	@Override
	public void onClick(View v) {
		if(actionBar.getVisibility() == View.VISIBLE)
			actionBar.setVisibility(View.GONE);
		else
			actionBar.setVisibility(View.VISIBLE);
	}
}
