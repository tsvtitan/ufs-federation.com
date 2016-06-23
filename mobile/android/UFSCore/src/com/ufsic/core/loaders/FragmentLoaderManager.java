package com.ufsic.core.loaders;

import com.ufsic.core.loaders.FragmentLoader.ProgressHolder;

import android.os.AsyncTask;
import android.os.Bundle;


public class FragmentLoaderManager<T> implements ProgressHolder<T>{

	public interface Callback<T>{
		
		public void onResultReceive(T data);
		public FragmentLoader<T> onCreateLoader(Bundle params);
	}
	
	public enum Flag{
		RUN_IF_NOT_EXIST, RUN_IF_RESULT_RECEIVE, CANCEL_LAST_AND_RUN;
	}
	
	private Callback<T> callback;
	private FragmentLoader<T> loader;
	private T waitResult;
	private boolean paused;
	
	public FragmentLoaderManager(Callback<T> callback){
		this.callback = callback;
		paused = true;
	}
	
	public void onPause(){
		paused = true;
		callback = null;
	}
	
	public void onResume(Callback<T> callback){
		this.callback = callback;
		paused = false;
		if(waitResult != null){
			callback.onResultReceive(waitResult);
			waitResult = null;
		}
	}
	
	public void onDestroy(){
		if(loader != null)
			loader.cancel(false);
		callback = null;
		paused = true;
		waitResult = null;
	}
	
	public void run(boolean first, Flag flag){
		run(first, flag, null);
	}
	
	public void run(boolean first, Flag flag, Bundle params){
		if(flag == Flag.RUN_IF_NOT_EXIST){
			if(loader == null || loader.getStatus() == AsyncTask.Status.FINISHED){
				loader = callback.onCreateLoader(params);
				loader.setProgressHolder(this);
				loader.execute(first);
			}
		}
		else if(flag == Flag.RUN_IF_RESULT_RECEIVE){
			if(loader == null || loader.resultReceive){
				if(loader != null)
					loader.cancel(false);
				
				loader = callback.onCreateLoader(params);
				loader.setProgressHolder(this);
				loader.execute(first);
			}	
		}
		else if(flag == Flag.CANCEL_LAST_AND_RUN){
			if(loader != null){
				loader.setProgressHolder(null);
				loader.cancel(false);
			}
			
			loader = callback.onCreateLoader(params);
			loader.setProgressHolder(this);
			loader.execute(first);
		}
	}

	@Override
	public void onPublishProgress(T data) {
		if(data == null)
			return;
		
		if(!paused && callback != null)
			callback.onResultReceive(data);
		else
			waitResult = data;
	}
}
