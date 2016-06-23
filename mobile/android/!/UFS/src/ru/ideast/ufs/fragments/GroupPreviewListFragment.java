package ru.ideast.ufs.fragments;

import java.util.ArrayList;

import ru.ideast.ufs.R;
import ru.ideast.ufs.activities.GroupDetailActivity;
import ru.ideast.ufs.activities.GroupFullListActivity;
import ru.ideast.ufs.adapters.GroupPreviewListAdapter;
import ru.ideast.ufs.beans.GroupBean;
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
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;

public class GroupPreviewListFragment extends Fragment implements FragmentLoaderManager.Callback<Message>, OnItemClickListener {
	
	private static final String SUBCATEGORY_ID = "subcategoryId";
	
	private String subcategoryId;
	
	private FragmentLoaderManager<Message> loaderManager;
	private GroupPreviewListAdapter adapter;
	private ListView list;
	
	public static GroupPreviewListFragment newInstance(String subcategoryId) {
		GroupPreviewListFragment fragment = new GroupPreviewListFragment();
		
		Bundle args = new Bundle();
		args.putString(SUBCATEGORY_ID, subcategoryId);
		fragment.setArguments(args);
		
		return fragment;
	}
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		Bundle args = getArguments();
		if(args != null && args.containsKey(SUBCATEGORY_ID))
			subcategoryId = args.getString(SUBCATEGORY_ID);
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
		
		adapter = new GroupPreviewListAdapter(getActivity());
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.list_only_fragment, container, false);
		
		list = (ListView) root.findViewById(R.id.lof_list);
		
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
		
		switch (viewType) {
		case GroupPreviewListAdapter.ROW_HEADER:
		case GroupPreviewListAdapter.ROW_HEADER_SINGLE:
			GroupBean.Result header = (GroupBean.Result) adapter.getItem(pos);
			
			Intent intentHeader = new Intent(getActivity(), GroupFullListActivity.class);
			intentHeader.putExtra(GroupFullListActivity.ITEMS, header.getItems());
			intentHeader.putExtra(GroupFullListActivity.TITLE, header.getName());
			startActivity(intentHeader);
			break;
		case GroupPreviewListAdapter.ROW_CENTER:
		case GroupPreviewListAdapter.ROW_FOOTER:
			GroupBean.Item center = (GroupBean.Item) adapter.getItem(pos);
			
			Intent intent = new Intent(getActivity(), GroupDetailActivity.class);
			intent.putExtra(GroupDetailActivity.NEWS_ID, center.getLinkID());
			startActivity(intent);
			break;
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
			adapter.setData(DatabaseManager.INSTANCE.getList(GroupBean.Result.class));
			break;
		case LoadingState.NO_NETWORK:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			adapter.setData(DatabaseManager.INSTANCE.getList(GroupBean.Result.class));
			break;
		}
	}

	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		return new GroupLoader(getActivity());
	}
	
	private class GroupLoader extends FragmentLoader<Message> {

		public GroupLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			ArrayList<GroupBean.Result> listBeans = null;
			boolean fromNetwork = false;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				GroupBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getGroups(token, subcategoryId), GroupBean.class);
				listBeans = wrappedBeans.getResult();
				
				DatabaseManager.INSTANCE.clearTable(GroupBean.Result.class);
				DatabaseManager.INSTANCE.addGroupBeanList(listBeans);
				fromNetwork = true;
			} catch (NoNetworkException e) {
				listBeans = DatabaseManager.INSTANCE.getList(GroupBean.Result.class);
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
