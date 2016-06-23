package com.ufsic.core.fragments;

import java.util.ArrayList;

import com.ufsic.core.beans.HtmlBean;
import com.ufsic.core.beans.NewsBean;
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

import com.ufsic.core.R;
import android.content.Context;
import android.content.res.Resources;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ProgressBar;
import android.widget.Toast;
import android.webkit.SslErrorHandler;
import android.net.http.SslError;


public class HtmlFragment extends Fragment /*implements FragmentLoaderManager.Callback<Message>*/ {
	
	private static final String CATEGORY_ID = "category_id";
	private static final String SUBCATEGORY_ID = "subcategory_id";
	
	private String categoryId;
	private String subcategoryId;
	
	//private FragmentLoaderManager<Message> loaderManager;
	/* tsv */private Handler messageHandler = null;
	
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
		
		/*loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);*/
		/* tsv */
		messageHandler = new Handler(Looper.getMainLooper()) {
        	
        	@Override
            public void handleMessage(Message inputMessage) {
        		HtmlFragment.this.onResultReceive(inputMessage);
    		}
        };
        
        getHtml(true);
        /* tsv */
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = inflater.inflate(R.layout.html_fragment, container, false);
		
		webView = (WebView) root.findViewById(R.id.hf_web);
		/* tsv */
		webView.setWebViewClient(new WebViewClient() {
			@Override
            public void onReceivedSslError(WebView view, SslErrorHandler handler, SslError error) {
                handler.proceed(); // Ignore SSL certificate errors
            }
		});
		/* tsv */
		
		WebSettings settings = webView.getSettings();
		settings.setDefaultTextEncodingName("utf-8");
		
		progress = (ProgressBar) root.findViewById(R.id.hf_progress);
		
		return root;
	}
	
	/* tsv */
	public void getHtml(final boolean firstRun) {
		
		new Thread() {
			
			private void publishProgress(int loadingState, HtmlBean.Result bean) {
				
				if (messageHandler!=null) {
				  Message.obtain(messageHandler,loadingState,bean).sendToTarget();
				}
			}
			
			@Override
			public void run() {
				
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
					publishProgress(LoadingState.NO_NETWORK_AND_DATA,null);
				}
				else {
					if(fromNetwork)
						publishProgress(LoadingState.SUCCESS,bean);
					else
						publishProgress(LoadingState.NO_NETWORK,bean);
				}
			}
		}.start();
			
	}
	/* tsv */
	
	@Override
	public void onResume() {
		super.onResume();
		//loaderManager.onResume(this);
	}
	
	@Override
	public void onPause() {
		//loaderManager.onPause();
		super.onPause();
	}
	
	@Override
	public void onDestroy() {
		//loaderManager.onDestroy();
		messageHandler = null;
		super.onDestroy();
	}

	//@Override
	public void onResultReceive(Message data) {
		progress.setVisibility(View.GONE);
		
		Resources res = getResources();
		
		switch (data.what) {
		case LoadingState.NO_NETWORK_AND_DATA:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			break;
		case LoadingState.SUCCESS:
			HtmlBean.Result success = DatabaseManager.INSTANCE.getHtmlBean(categoryId, subcategoryId);
			//webView.loadDataWithBaseURL(null, success.getHtml(), "text/html", "utf-8", null);
			/* tsv */webView.loadDataWithBaseURL(URLManager.getBaseUrl(), success.getHtml(), "text/html", "utf-8", null);
			break;
		case LoadingState.NO_NETWORK:
			Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			HtmlBean.Result noNetwork = DatabaseManager.INSTANCE.getHtmlBean(categoryId, subcategoryId);
			//webView.loadDataWithBaseURL(null, noNetwork.getHtml(), "text/html", "utf-8", null);
			/* tsv */webView.loadDataWithBaseURL(URLManager.getBaseUrl(), noNetwork.getHtml(), "text/html", "utf-8", null);
			break;
		}
	}

	/*
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
	*/
}
