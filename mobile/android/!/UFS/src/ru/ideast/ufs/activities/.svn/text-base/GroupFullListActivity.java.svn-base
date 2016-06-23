package ru.ideast.ufs.activities;

import java.util.ArrayList;

import ru.ideast.ufs.R;
import ru.ideast.ufs.adapters.GroupFullListAdapter;
import ru.ideast.ufs.beans.GroupBean;
import ru.ideast.ufs.widgets.ActionBarUfs;
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
	
	private GroupFullListAdapter adapter;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.list_only_activity);
		
		Bundle bundle = getIntent().getExtras();
		if(bundle == null || !bundle.containsKey(ITEMS) || !bundle.containsKey(TITLE)) {
			finish();
			return;
		}
		
		ArrayList<GroupBean.Item> beans = (ArrayList<GroupBean.Item>) bundle.getSerializable(ITEMS);
		String title = bundle.getString(TITLE);
		
		adapter = new GroupFullListAdapter(this);
		adapter.setData(beans);
		
		setWidgets(title);
	}
	
	private void setWidgets(String title) {
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
		Intent intent = new Intent(this, GroupDetailActivity.class);
		intent.putExtra(GroupDetailActivity.NEWS_ID, adapter.getItem(pos).getLinkID());
		startActivity(intent);
	}
}
