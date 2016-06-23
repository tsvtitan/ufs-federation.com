package com.ufsic.core.activities;

import java.util.ArrayList;

import org.OpenUDID.OpenUDID_manager;

import com.nostra13.universalimageloader.core.DisplayImageOptions;
import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.ImageLoaderConfiguration;
import com.nostra13.universalimageloader.core.download.HttpClientImageDownloader;
import com.ufsic.core.beans.AuthBean;
import com.ufsic.core.beans.CategoryBean;
import com.ufsic.core.beans.KeywordsBean;
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
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.os.Message;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Toast;

public class SplashActivity extends Activity implements FragmentLoaderManager.Callback<Message> {
	
	private FragmentLoaderManager<Message> loaderManager;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.splash_activity);
		
		/* tsv */
		
		OpenUDID_manager.sync(getApplicationContext());
		AnalyticsCounter.initialize(this);
		
        /* tsv */
        
		SharedPreferencesWrap.INSTANCE.init(this);
		DatabaseManager.INSTANCE.init(this);
		HttpManager.INSTANCE.init(this);
		
		DisplayImageOptions options = new DisplayImageOptions.Builder()
        .cacheInMemory(true)
        .cacheOnDisc(true)
        .build();
		
		ImageLoaderConfiguration config = new ImageLoaderConfiguration.Builder(this)
		.imageDownloader(new HttpClientImageDownloader(this))
		.defaultDisplayImageOptions(options)
		.build();
		
		ImageLoader imageLoader = ImageLoader.getInstance();
		imageLoader.init(config);

		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
	}
	
	@Override
	public void onResume() {
		super.onResume();
		loaderManager.onResume(this);
		/* tsv */AnalyticsCounter.start(this);
	}

	@Override
	public void onPause() {
		/* tsv */AnalyticsCounter.stop(this);
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
		Intent intent = null;
		
		Resources res = getResources();
		
		switch (data.what) {
		case LoadingState.NO_NETWORK_AND_DATA:
			Toast.makeText(this, res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			break;
		case LoadingState.SUCCESS:
			intent = new Intent(this, MainActivity.class);
			break;
		case LoadingState.NO_NETWORK:
			intent = new Intent(this, MainActivity.class);
			Toast.makeText(this, res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			break;
		}
		
		if(intent != null)
			startActivity(intent);
		
		finish();
	}
	
	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		return new SplashLoader(this);
	}

	private class SplashLoader extends FragmentLoader<Message> {

		public SplashLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			ArrayList<CategoryBean.Result> listBeans = null;
			boolean fromNetwork = false;
			
			//пытаемся достать token из SharedPreferences
			String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN, null);
			
			try {
				//если token отсутствует в SharedPreferences - делаем запрос на сервер и сохраняем полученный token
				if (ToolBox.isEmpty(token)) {
					AuthBean authBean = HttpManager.INSTANCE.getData(URLManager.authorization(SplashActivity.this), AuthBean.class);
					token = authBean.getResult().getToken();
					SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.TOKEN, token);
					SharedPreferencesWrap.INSTANCE.putLong(SharedPreferencesWrap.TOKEN_TIME, authBean.getResult().getExpired());
					/* tsv */
					SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.EMAIL, authBean.getResult().getEmail());
					SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.PHONE, authBean.getResult().getPhone());
					SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.CATEGORY_ID, authBean.getResult().getCategoryId());
					SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.CATEGORY_DELAY, authBean.getResult().getCategoryDelay());
					/* tsv */
				}
				else {
					//если token есть в SharedPreferences - проверяем не устарел ли он
					long currentTime = System.currentTimeMillis();
					long tokenTime = SharedPreferencesWrap.INSTANCE.getLong(SharedPreferencesWrap.TOKEN_TIME);
					
					//если token устарел, то делаем запрос на сервер и сохраняем новый полученный token
					if(currentTime > tokenTime) {
						AuthBean authBean = HttpManager.INSTANCE.getData(URLManager.authorization(SplashActivity.this), AuthBean.class);
						token = authBean.getResult().getToken();
						SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.TOKEN, token);
						SharedPreferencesWrap.INSTANCE.putLong(SharedPreferencesWrap.TOKEN_TIME, authBean.getResult().getExpired());
						/* tsv */
						SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.EMAIL, authBean.getResult().getEmail());
						SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.PHONE, authBean.getResult().getPhone());
						SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.CATEGORY_ID, authBean.getResult().getCategoryId());
						SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.CATEGORY_DELAY, authBean.getResult().getCategoryDelay());
						/* tsv */
					}
				}
				
				//пытаемся загрузить категории из интернета
				CategoryBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getCategories(token), CategoryBean.class);
				listBeans = wrappedBeans.getResult();
				//удаляем старые категории из БД и сохраняем новые
				DatabaseManager.INSTANCE.clearTable(CategoryBean.Result.class);
				DatabaseManager.INSTANCE.AddList(listBeans, CategoryBean.Result.class);
				
				/* tsv */
				try {
					KeywordsBean bean = HttpManager.INSTANCE.getData(URLManager.getKeywords(token), KeywordsBean.class);
					ArrayList<KeywordsBean.Result> keywords = bean.getResult();
					
					DatabaseManager.INSTANCE.clearTable(KeywordsBean.Result.class);
					DatabaseManager.INSTANCE.AddList(keywords,KeywordsBean.Result.class);
				} catch (Exception e) {
					//
				}
				/* tsv */
				
				fromNetwork = true;
			}
			catch (NoNetworkException e) {
				//если нет интернета, то пытаемся достать категории из БД
				listBeans = DatabaseManager.INSTANCE.getList(CategoryBean.Result.class);
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
