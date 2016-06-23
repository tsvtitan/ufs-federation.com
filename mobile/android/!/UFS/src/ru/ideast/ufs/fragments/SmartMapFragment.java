package ru.ideast.ufs.fragments;

import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.SupportMapFragment;

public class SmartMapFragment extends SupportMapFragment {
	
	private OnMapInitCompletedCallback callback;
	
	public void setOnMapInitCompletedCallback(OnMapInitCompletedCallback callback) {
		this.callback = callback;
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		View root = super.onCreateView(inflater, container, savedInstanceState);
		
		if(callback != null && getMap() != null)
			callback.onMapInitCompleted(getMap());
		
		return root;
	}
	
	public interface OnMapInitCompletedCallback {
		public void onMapInitCompleted(GoogleMap map);
	}
}
