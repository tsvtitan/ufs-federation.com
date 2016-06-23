package com.ufsic.core.fragments;

import java.util.ArrayList;

import com.ufsic.core.activities.BranchDetailActivity;
import com.ufsic.core.adapters.BranchListAdapter;
import com.ufsic.core.beans.BranchBean;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.db.DatabaseManager;
import com.ufsic.core.exceptions.CorruptedDataException;
import com.ufsic.core.exceptions.NoNetworkException;
import com.ufsic.core.loaders.FragmentLoader;
import com.ufsic.core.loaders.FragmentLoaderManager;
import com.ufsic.core.loaders.FragmentLoaderManager.Flag;
import com.ufsic.core.managers.HttpManager;
import com.ufsic.core.managers.LoadingState;
import com.ufsic.core.managers.URLManager;
import com.ufsic.core.utils.SharedPreferencesWrap;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;
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

public class BranchListFragment extends Fragment implements FragmentLoaderManager.Callback<Message>, OnItemClickListener {
	
	/* tsv */private static final String TITLES = "titles";
	/* tsv */private String[] titles;
	
	private FragmentLoaderManager<Message> loaderManager;
	
	private BranchListAdapter adapter;
	
	/*public static BranchListFragment newInstance() {
	    return new BranchListFragment(); 
	 }*/
	
	/* tsv */
	public static BranchListFragment newInstance(String[] titles) {
		
		BranchListFragment fragment = new BranchListFragment();
		
		Bundle args = new Bundle();
		args.putStringArray(TITLES, titles);
		fragment.setArguments(args);
		
		return fragment;
	}
	/* tsv */
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		/* tsv */
		Bundle args = getArguments();
		if(args != null && args.containsKey(TITLES)) {
			titles = args.getStringArray(TITLES);
		}
		/* tsv */
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
		
		adapter = new BranchListAdapter(getActivity());
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
		int viewType = adapter.getItemViewType(pos);
		
		if(viewType == BranchListAdapter.ROW_CENTER || viewType == BranchListAdapter.ROW_FOOTER) {
			
			BranchBean.Result bean = adapter.getItem(pos);
			/* tsv */AnalyticsCounter.eventScreens(titles,bean.getAddress(),null,null);
			
			Intent intent = new Intent(getActivity(), BranchDetailActivity.class);
			intent.putExtra(BranchDetailActivity.BRANCH_BEAN, bean);
			startActivity(intent);
		}
	}

	@Override
	public void onResultReceive(Message data) {
		
		Resources res = getResources();
		
		switch (data.what) {
		case LoadingState.NO_NETWORK_AND_DATA:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			break;
		case LoadingState.SUCCESS:
			adapter.setData(DatabaseManager.INSTANCE.getList(BranchBean.Result.class));
			break;
		case LoadingState.NO_NETWORK:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			adapter.setData(DatabaseManager.INSTANCE.getList(BranchBean.Result.class));
			break;
		}
	}

	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		return new BranchLoader(getActivity());
	}
	
	private class BranchLoader extends FragmentLoader<Message> {

		public BranchLoader(Context context) {
			super(context);
		}
		
		@Override
		public void runInBackground(boolean firstRun) {
			ArrayList<BranchBean.Result> listBeans = null;
			boolean fromNetwork = false;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				BranchBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getBranches(token), BranchBean.class);
				listBeans = wrappedBeans.getResult();
				
				DatabaseManager.INSTANCE.clearTable(BranchBean.Result.class);
				DatabaseManager.INSTANCE.AddList(listBeans, BranchBean.Result.class);
				fromNetwork = true;
			}
			catch (NoNetworkException e) {
				listBeans = DatabaseManager.INSTANCE.getList(BranchBean.Result.class);
			}
			catch (CorruptedDataException e) {
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
