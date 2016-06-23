package ru.ideast.ufs.fragments;

import java.util.ArrayList;

import ru.ideast.ufs.R;
import ru.ideast.ufs.activities.CalendarActivity;
import ru.ideast.ufs.activities.MainActivity;
import ru.ideast.ufs.activities.NewsDetailActivity;
import ru.ideast.ufs.adapters.NewsListAdapter;
import ru.ideast.ufs.beans.NewsBean;
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
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AbsListView.OnScrollListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;

public class NewsListFragment extends Fragment implements FragmentLoaderManager.Callback<Message>, OnItemClickListener, OnScrollListener {

	private static final String CATEGORY_ID = "categoryId";
	private static final String SUBCATEGORY_ID = "subcategoryId";
	
	private String categoryId;
	private String subcategoryId;
	private long timestamp;
	private long resultDate;
	
	private FragmentLoaderManager<Message> loaderManager;
	private NewsListAdapter adapter;
	private LinearLayout loadingFooter;
	private TextView emptyText;
	/* tsv */private View progressView;
	
	private boolean networkEnabled = true;

	public static NewsListFragment newInstance(String categoryId, String subcategoryId) {
		NewsListFragment fragment = new NewsListFragment();

		Bundle args = new Bundle();
		args.putString(CATEGORY_ID, categoryId);
		args.putString(SUBCATEGORY_ID, subcategoryId);
		fragment.setArguments(args);

		return fragment;
	}

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		Bundle args = getArguments();
		if(args != null && args.containsKey(CATEGORY_ID) && args.containsKey(SUBCATEGORY_ID)) {
			categoryId = args.getString(CATEGORY_ID);
			subcategoryId = args.getString(SUBCATEGORY_ID);
		}
		else {
			//новости компании
			categoryId = "16";
			subcategoryId = "";
		}
		
		timestamp = System.currentTimeMillis() / 1000;
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
		
		adapter = new NewsListAdapter(getActivity());
	}

	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.list_only_fragment, container, false);
		
		ListView list = (ListView) root.findViewById(R.id.lof_list);
		
		View emptyView = root.findViewById(R.id.lof_progress);
		list.setEmptyView(emptyView);
		
		/* tsv */progressView = emptyView;
		
		loadingFooter = (LinearLayout) inflater.inflate(R.layout.list_progress_bar, null, false);
		loadingFooter.setTag(loadingFooter.findViewById(R.id.progress_row));
		list.addFooterView(loadingFooter, null, false);
		
		list.setOnScrollListener(this);
		list.setOnItemClickListener(this);
		list.setAdapter(adapter);
		
		emptyText = (TextView) root.findViewById(R.id.lof_empty);
		
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
	public void onActivityResult(int requestCode, int resultCode, Intent data) {
		if(resultCode == Activity.RESULT_OK) {
			resultDate = data.getExtras().getLong(CalendarActivity.DATE);
			
			loaderManager.onResume(this);
			loaderManager.run(false, Flag.RUN_IF_NOT_EXIST);
			
			emptyText.setVisibility(View.GONE);
		}
	}
	
	public String[] getIds() {
		String[] ids = { categoryId, subcategoryId };
		return ids;
	}
	
	private void showLoadingFooter() {
		((View) loadingFooter.getTag()).setVisibility(View.VISIBLE);
	}
	
	private void hideLoadingFooter() {
		((View) loadingFooter.getTag()).setVisibility(View.GONE);
	}
	
	@Override
	public void onItemClick(AdapterView<?> arg0, View arg1, int pos, long id) {
		Intent intent = new Intent(getActivity(), NewsDetailActivity.class);
		intent.putExtra(NewsDetailActivity.NEWS_BEAN, adapter.getItem(pos));
		startActivity(intent);
	}
	
	@Override
	public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {
		
		if(firstVisibleItem + visibleItemCount + 1 >= totalItemCount && adapter.getCount() > 2 && networkEnabled) {
			showLoadingFooter();
			//вычетаем 1 из даты, чтобы верхняя граница нового запроса не пересекалась с нижней границей предыдущего
			timestamp = adapter.getItem(adapter.getCount() - 1).getDate() - 1;
			loaderManager.run(false, Flag.RUN_IF_NOT_EXIST);
		}
	}

	@Override
	public void onScrollStateChanged(AbsListView view, int scrollState) {}

	@Override
	public void onResultReceive(Message data) {
		
		if(resultDate != 0) {
			
			switch (data.what) {
			case LoadingState.NO_NETWORK_AND_DATA:
				resultDate = 0;
				
				adapter.clearData();
				emptyText.setVisibility(View.VISIBLE);
				break;
			case LoadingState.SUCCESS:
				resultDate = 0;
				
				adapter.setData((ArrayList<NewsBean.Result>) data.obj);
				
				
				break;
			}
		}
		else {
			Resources res = getResources();
			
			switch (data.what) {
			case LoadingState.NO_NETWORK_AND_DATA:
				Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
				
				if(adapter.getCount() == 0)
					emptyText.setVisibility(View.VISIBLE);
				break;
			case LoadingState.SUCCESS:
				adapter.setData(DatabaseManager.INSTANCE.getNewsBean(categoryId, subcategoryId));
				break;
			case LoadingState.NO_NETWORK:
				Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
				adapter.setData(DatabaseManager.INSTANCE.getNewsBean(categoryId, subcategoryId));
				break;
			}
			
			hideLoadingFooter();
		}
		
		/* tsv */progressView.setVisibility(View.INVISIBLE);
		
		/* tsv */
		Activity activity = getActivity();
		if (activity!=null && activity instanceof MainActivity) {
			
			final MainActivity ma = (MainActivity)activity;
			if (!ma.getFirstTime()) {
				
				ma.setFirstTime(true);
				
				new CountDownTimer(500,500) {

					@Override
					public void onFinish() {
						ma.showMenu();
					}

					@Override
					public void onTick(long arg0) {
						this.cancel();
					}
				}.start();
			}
		}
		/* tsv */
	}

	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		if(resultDate != 0)
			return new FilterLoader(getActivity());
		else
			return new NewsLoader(getActivity());
	}
	
	private class NewsLoader extends FragmentLoader<Message> {

		public NewsLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			ArrayList<NewsBean.Result> listBeans = null;
			boolean fromNetwork = false;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				
				NewsBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getNews(token, categoryId, subcategoryId, "", timestamp, 10), NewsBean.class);
				listBeans = wrappedBeans.getResult();
				
				if(firstRun)
					DatabaseManager.INSTANCE.deleteNewsBean(categoryId, subcategoryId);
				
				DatabaseManager.INSTANCE.AddList(listBeans, NewsBean.Result.class);
				fromNetwork = true;
			} catch (NoNetworkException e) {
				listBeans = DatabaseManager.INSTANCE.getNewsBean(categoryId, subcategoryId);
				
				networkEnabled = false;
			} catch (CorruptedDataException e) {
			}
			
			//if(ToolBox.isEmpty(listBeans)) {
			/* tsv */if (listBeans==null) {
				publishProgress(Message.obtain(null, LoadingState.NO_NETWORK_AND_DATA));
			}
			else {
				if(fromNetwork) {
					//на самом деле successLast = true
					/* tsv *///if(listBeans.size() < 10) networkEnabled = false;
					publishProgress(Message.obtain(null, LoadingState.SUCCESS));
				}
				else
					publishProgress(Message.obtain(null, LoadingState.NO_NETWORK));
			}
		}
	}
	
	private class FilterLoader extends FragmentLoader<Message> {

		public FilterLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			ArrayList<NewsBean.Result> listBeans = null;
			
			String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
			
			try {
				long dateEnd = resultDate + 24 * 60 * 60;
				NewsBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getNewsFilter(token, categoryId, subcategoryId, "", dateEnd, resultDate), NewsBean.class);
				listBeans = wrappedBeans.getResult();
			} catch (NoNetworkException e) {
			} catch (CorruptedDataException e) {
			}
			
			if(ToolBox.isEmpty(listBeans))
				//на самом деле NO_NETWORK_OR_DATA
				publishProgress(Message.obtain(null, LoadingState.NO_NETWORK_AND_DATA));
			else
				publishProgress(Message.obtain(null, LoadingState.SUCCESS, listBeans));
		}
	}
}
