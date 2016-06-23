package com.ufsic.core.activities;

import java.util.ArrayList;

import com.inqbarna.tablefixheaders.TableFixHeaders;
import com.ufsic.core.adapters.TableAdapter;
import com.ufsic.core.beans.TableBean;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.db.DatabaseManager;
import com.ufsic.core.exceptions.CorruptedDataException;
import com.ufsic.core.exceptions.NoNetworkException;
import com.ufsic.core.fragments.TableFragment;
import com.ufsic.core.loaders.FragmentLoader;
import com.ufsic.core.loaders.FragmentLoaderManager;
import com.ufsic.core.loaders.FragmentLoaderManager.Flag;
import com.ufsic.core.managers.HttpManager;
import com.ufsic.core.managers.LoadingState;
import com.ufsic.core.managers.URLManager;
import com.ufsic.core.utils.SharedPreferencesWrap;
import com.ufsic.core.utils.ToolBox;
import com.ufsic.core.widgets.ActionBarUfs;
import com.ufsic.core.widgets.MyFragmentTabHost;

import com.ufsic.core.R;
import android.app.AlertDialog;
import android.content.Context;
import android.content.res.Resources;
import android.os.Bundle;
import android.os.Message;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Toast;

public class TablesActivity extends FragmentActivity implements FragmentLoaderManager.Callback<Message> {
	
	public static final String TITLE = "title";
	public static final String SUBCATEGORY_ID = "subcategoryId";
	/* tsv */public static final String TITLES = "titles";
	
	private String subcategoryId;
	
	private FragmentLoaderManager<Message> loaderManager;
	
	private ActionBarUfs actionBar;
	private String description;
	
	private MyFragmentTabHost tabHost;
	/* tsv */private String[] titles;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.tables_activity);
		
		Bundle extras = getIntent().getExtras();
		if(extras == null || !extras.containsKey(TITLE) || !extras.containsKey(SUBCATEGORY_ID) || !extras.containsKey(TITLES)) {
			finish();
			return;
		}
		
		subcategoryId = extras.getString(SUBCATEGORY_ID);
		
		/* tsv */titles = extras.getStringArray(TITLES);
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
		
		actionBar = (ActionBarUfs) findViewById(R.id.ta_action_bar);
		actionBar.setSingleText(extras.getString(TITLE));
		actionBar.setOnClickListener(actionBarClickListener);
		
		tabHost = (MyFragmentTabHost) findViewById(R.id.ta_tabhost);
		tabHost.setup(this, getSupportFragmentManager(), android.R.id.tabcontent);
		
		//�������������� ���� java.lang.IllegalStateException: No tab known for tag null
		tabHost.addTab(tabHost.newTabSpec("tab_-1").setIndicator(""), TableFragment.class, null);
	}
	
	@Override
	public void onResume() {
		super.onResume();
		loaderManager.onResume(this);
	}
	
	@Override
	public void onPause() {
		loaderManager.onPause();
		super.onPause();
	}
	
	@Override
	public void onDestroy() {
		loaderManager.onDestroy();
		super.onDestroy();
	}
	
	private OnClickListener actionBarClickListener = new OnClickListener() {
		
		@Override
		public void onClick(View v) {
			
			/* tsv */
			int id = v.getId();
			if (id == R.id.abu_left_btn) {
				finish();
			} else if (id == R.id.abu_right_btn) {
				AlertDialog.Builder adb = new AlertDialog.Builder(TablesActivity.this);
				adb.setTitle(getResources().getString(R.string.tables_dialog_title));
				adb.setMessage(description);
				adb.show();
			}
			/* tsv */
			
			/*switch (v.getId()) {
			case R.id.abu_left_btn:
				finish();
				break;
			case R.id.abu_right_btn:
				AlertDialog.Builder adb = new AlertDialog.Builder(TablesActivity.this);
				adb.setTitle(getResources().getString(R.string.tables_dialog_title));
				adb.setMessage(description);
				adb.show();
				break;
			}*/
		}
	};
	
	@Override
	public void onResultReceive(Message data) {
		
		findViewById(R.id.ta_progress).setVisibility(View.GONE);
		
		TableAdapter adapter = new TableAdapter(this);
		TableFixHeaders table = (TableFixHeaders) findViewById(R.id.ta_table);
		
		Resources res = getResources();
		
		switch (data.what) {
		case LoadingState.NO_NETWORK_AND_DATA:
			Toast.makeText(this, res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			finish();
			break;
		case LoadingState.SUCCESS:
		case LoadingState.NO_NETWORK:
			ArrayList<TableBean.Table> tablesList = DatabaseManager.INSTANCE.getTableBean(Integer.valueOf(subcategoryId));
			
			if(tablesList.size() > 1) {
				tabHost.clearAllTabs();
				tabHost.setVisibility(View.VISIBLE);
				
				for (int i = 0; i < tablesList.size(); i++) {
					TableBean.Table tableData = tablesList.get(i);
					
					Bundle args = new Bundle();
					args.putSerializable(TableFragment.TABLE, tableData);
					/* tsv */args.putStringArray(TableFragment.TITLES,titles);
					
			        tabHost.addTab(tabHost.newTabSpec("tab_" + i).setIndicator(tableData.getName()), TableFragment.class, args);
				}
			}
			else {
				TableBean.Table tableData = tablesList.get(0);
				//adapter.setData(tableData);
				/* tsv */adapter.setData(tableData,titles);
				table.setAdapter(adapter);
			}
			
			if(tablesList.get(0).getDescription() != null) {
				description = tablesList.get(0).getDescription();
				actionBar.setRightBtn(R.drawable.btn_calendar_selector);
			}
			break;
		}
		
		if(data.what == LoadingState.NO_NETWORK)
			Toast.makeText(this, res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			
	}

	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		return new TablesLoader(this);
	}
	
	private class TablesLoader extends FragmentLoader<Message> {
		
		public TablesLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			ArrayList<TableBean.Table> listTables = null;
			boolean fromNetwork = false;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				TableBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getTables(token, subcategoryId), TableBean.class);
				listTables = wrappedBeans.getResult().getTables();
				
				DatabaseManager.INSTANCE.deleteTableBean(Integer.valueOf(subcategoryId));
				DatabaseManager.INSTANCE.AddList(listTables, TableBean.Table.class);
				fromNetwork = true;
			} catch (NoNetworkException e) {
				listTables = DatabaseManager.INSTANCE.getTableBean(Integer.valueOf(subcategoryId));
			} catch (CorruptedDataException e) {
			}
			
			if(ToolBox.isEmpty(listTables)) {
				publishProgress(Message.obtain(null, LoadingState.NO_NETWORK_AND_DATA));
			}
			else {
				if(fromNetwork)
					publishProgress(Message.obtain(null, LoadingState.SUCCESS));
				else
					publishProgress(Message.obtain(null, LoadingState.NO_NETWORK));
			}
		}
	}
}
