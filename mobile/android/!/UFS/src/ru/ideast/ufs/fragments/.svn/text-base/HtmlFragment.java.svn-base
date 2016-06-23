package ru.ideast.ufs.fragments;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.HtmlBean;
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

import android.content.Context;
import android.content.res.Resources;
import android.os.Bundle;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.ProgressBar;
import android.widget.Toast;

public class HtmlFragment extends Fragment implements FragmentLoaderManager.Callback<Message> {
	
	private static final String CATEGORY_ID = "category_id";
	private static final String SUBCATEGORY_ID = "subcategory_id";
	
	private String categoryId;
	private String subcategoryId;
	
	private FragmentLoaderManager<Message> loaderManager;
	
	private WebView webView;
	private ProgressBar progress;
	
	public static HtmlFragment newInstance(String categoryId, String subcategoryId) {
		HtmlFragment fragment = new HtmlFragment();
		
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
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.html_fragment, container, false);
		
		webView = (WebView) root.findViewById(R.id.hf_web);
		
		WebSettings settings = webView.getSettings();
		settings.setDefaultTextEncodingName("utf-8");
		
		progress = (ProgressBar) root.findViewById(R.id.hf_progress);
		
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
	public void onResultReceive(Message data) {
		progress.setVisibility(View.GONE);
		
		Resources res = getResources();
		
		switch (data.what) {
		case LoadingState.NO_NETWORK_AND_DATA:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			break;
		case LoadingState.SUCCESS:
			HtmlBean.Result success = DatabaseManager.INSTANCE.getHtmlBean(categoryId, subcategoryId);
			webView.loadDataWithBaseURL(null, success.getHtml(), "text/html", "utf-8", null);
			break;
		case LoadingState.NO_NETWORK:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			HtmlBean.Result noNetwork = DatabaseManager.INSTANCE.getHtmlBean(categoryId, subcategoryId);
			webView.loadDataWithBaseURL(null, noNetwork.getHtml(), "text/html", "utf-8", null);
			break;
		}
	}

	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		return new HtmlLoader(getActivity());
	}
	
	private class HtmlLoader extends FragmentLoader<Message> {

		public HtmlLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			HtmlBean.Result bean = null;
			boolean fromNetwork = false;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				HtmlBean wrappedBean = HttpManager.INSTANCE.getData(URLManager.getHtml(token, categoryId, subcategoryId), HtmlBean.class);
				bean = wrappedBean.getResult().get(0);
				
				DatabaseManager.INSTANCE.deleteHtmlBean(categoryId, subcategoryId);
				DatabaseManager.INSTANCE.addObject(bean, HtmlBean.Result.class);
				fromNetwork = true;
			} catch (NoNetworkException e) {
				bean = DatabaseManager.INSTANCE.getHtmlBean(categoryId, subcategoryId);
			} catch (CorruptedDataException e) {
			}
			
			if(bean == null) {
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
