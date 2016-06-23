package com.ufsic.core.activities;

import java.util.ArrayList;
import java.util.Arrays;

import com.ufsic.core.adapters.GroupFullListAdapter;
import com.ufsic.core.beans.GroupBean;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.widgets.ActionBarUfs;

import com.ufsic.core.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;

public class GroupFullListActivity extends Activity implements OnItemClickListener {
	
	public static final String ITEMS = "items";
	public static final String TITLE = "title";
	/* tsv */public static final String TITLES = "titles";
	
	private GroupFullListAdapter adapter;
	/* tsv */
	private String title;
	private String[] titles;
	/* tsv */
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.list_only_activity);
		
		Bundle bundle = getIntent().getExtras();
		if(bundle == null || !bundle.containsKey(ITEMS) || !bundle.containsKey(TITLE) || !bundle.containsKey(TITLES)) {
			finish();
			return;
		}
		
		ArrayList<GroupBean.Item> beans = (ArrayList<GroupBean.Item>) bundle.getSerializable(ITEMS);
		title = bundle.getString(TITLE);
		/* tsv */titles = bundle.getStringArray(TITLES);
		
		adapter = new GroupFullListAdapter(this);
		adapter.setData(beans);
		
		setWidgets();
	}
	
	private void setWidgets() {
		
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.loa_action_bar);
		actionBar.setSingleText(title);
		actionBar.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
				return;
			}
		});
		
		ListView list = (ListView) findViewById(R.id.loa_list);
		
		View emptyView = findViewById(R.id.loa_progress);
		list.setEmptyView(emptyView);
		
		list.setOnItemClickListener(this);
		list.setAdapter(adapter);
	}

	@Override
	public void onItemClick(AdapterView<?> arg0, View arg1, int pos, long id) {
		
		GroupBean.Item item = adapter.getItem(pos);
		
		/* tsv */
		ArrayList<String> list = new ArrayList<String>(Arrays.asList(titles));
		list.add(title);
		AnalyticsCounter.eventScreens(list.toArray(new String[list.size()]),item.getName(),null,null);
		list.add(item.getName());
		/* tsv */
		
		Intent intent = new Intent(this, GroupDetailActivity.class);
		intent.putExtra(GroupDetailActivity.NEWS_ID, item.getLinkID());
		/* tsv */intent.putExtra(GroupDetailActivity.TITLES,list.toArray(new String[list.size()]));
		startActivity(intent);
	}
	
	
}
