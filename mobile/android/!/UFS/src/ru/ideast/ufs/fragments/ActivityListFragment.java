package ru.ideast.ufs.fragments;

import java.util.ArrayList;

import ru.ideast.ufs.R;
import ru.ideast.ufs.activities.WebActivity;
import ru.ideast.ufs.adapters.ActivityListAdapter;
import ru.ideast.ufs.beans.ActivityBean;
import ru.ideast.ufs.db.DatabaseManager;
import ru.ideast.ufs.exceptions.CorruptedDataException;
import ru.ideast.ufs.exceptions.NoNetworkException;
import ru.ideast.ufs.loaders.FragmentLoader;
import ru.ideast.ufs.loaders.FragmentLoaderManager;
import ru.ideast.ufs.loaders.FragmentLoaderManager.Flag;
import ru.ideast.ufs.managers.HttpManager;
import ru.ideast.ufs.managers.LoadingState;
import ru.ideast.ufs.managers.URLManager;
import ru.ideast.ufs.utils.SharedPreferencesWrap;
import ru.ideast.ufs.utils.ToolBox;

import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;
import android.widget.Toast;

public class ActivityListFragment extends Fragment implements FragmentLoaderManager.Callback<Message>, OnItemClickListener {
	
	private static final String CATEGORY_ID = "categoryId";
	
	private String categoryId;
	
	private FragmentLoaderManager<Message> loaderManager;
	private ActivityListAdapter adapter;
	
	public static ActivityListFragment newInstance(String categoryId) {
		ActivityListFragment fragment = new ActivityListFragment();
		
		Bundle args = new Bundle();
		args.putString(CATEGORY_ID, categoryId);
		fragment.setArguments(args);
		
		return fragment;
	}
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		Bundle args = getArguments();
		if(args != null && args.containsKey(CATEGORY_ID))
			categoryId = args.getString(CATEGORY_ID);
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
		
		adapter = new ActivityListAdapter(getActivity());
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.list_only_fragment, container, false);
		
		ListView list = (ListView) root.findViewById(R.id.lof_list);
		
		View emptyView = root.findViewById(R.id.lof_progress);
		list.setEmptyView(emptyView);
		
		list.setOnItemClickListener(this);
		list.setAdapter(adapter);
		
		return root;
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
	
	@Override
	public void onItemClick(AdapterView<?> arg0, View arg1, int pos, long id) {
		ActivityBean.Result bean = adapter.getItem(pos);
		Intent intent = new Intent(getActivity(), WebActivity.class);
		intent.putExtra(WebActivity.NAME, bean.getName());
		intent.putExtra(WebActivity.TEXT, bean.getText());
		startActivity(intent);
	}

	@Override
	public void onResultReceive(Message data) {
		
		Resources res = getResources();
		
		switch (data.what) {
		case LoadingState.NO_NETWORK_AND_DATA:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			break;
		case LoadingState.SUCCESS:
			adapter.setData(DatabaseManager.INSTANCE.getList(ActivityBean.Result.class));
			break;
		case LoadingState.NO_NETWORK:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			adapter.setData(DatabaseManager.INSTANCE.getList(ActivityBean.Result.class));
			break;
		}
	}

	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		return new ActivityLoader(getActivity());
	}
	
	private class ActivityLoader extends FragmentLoader<Message> {

		public ActivityLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			ArrayList<ActivityBean.Result> listBeans = null;
			boolean fromNetwork = false;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				ActivityBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getActivities(token, categoryId), ActivityBean.class);
				listBeans = wrappedBeans.getResult();
				
				DatabaseManager.INSTANCE.clearTable(ActivityBean.Result.class);
				DatabaseManager.INSTANCE.AddList(listBeans, ActivityBean.Result.class);
				fromNetwork = true;
			} catch (NoNetworkException e) {
				listBeans = DatabaseManager.INSTANCE.getList(ActivityBean.Result.class);
			} catch (CorruptedDataException e) {
			}
			
			//if(ToolBox.isEmpty(listBeans)) {
			/* tsv */if (listBeans==null) {
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
