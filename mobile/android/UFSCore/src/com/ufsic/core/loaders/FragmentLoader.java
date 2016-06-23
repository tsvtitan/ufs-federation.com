package com.ufsic.core.loaders;

import android.content.Context;
import android.os.AsyncTask;

public abstract class FragmentLoader<T> extends AsyncTask<Boolean, T, Void>{

	public boolean resultReceive = false;
	private Context appContext;

	interface ProgressHolder<T>{
		public void onPublishProgress(T data);
	}

	public FragmentLoader(Context context){
		this.appContext = context.getApplicationContext();
	}

	protected Context getAppContext(){
		return appContext;
	}

	private ProgressHolder<T> progressHolder;
	void setProgressHolder(ProgressHolder<T> progressHolder) {
		this.progressHolder = progressHolder;
	}

	public abstract void runInBackground(boolean firstRun);

	@Override
	protected final Void doInBackground(Boolean... params) {
		runInBackground(params[0]);
		return null;
	}

	@Override
	protected final void onProgressUpdate(T... values) {
		if(progressHolder != null)
			progressHolder.onPublishProgress(values[0]);
		resultReceive = true;
	};
}
