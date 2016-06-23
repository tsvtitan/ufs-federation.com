package ru.ideast.ufs.activities;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.UiSettings;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.BranchBean;
import ru.ideast.ufs.fragments.SmartMapFragment;
import ru.ideast.ufs.fragments.SmartMapFragment.OnMapInitCompletedCallback;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.view.View.OnClickListener;

public class MapActivity extends FragmentActivity {
	
	public static final String BRANCH_BEAN = "branch_bean";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.map_activity);
		
		Bundle bundle = getIntent().getExtras();
		if (bundle == null || !bundle.containsKey(BRANCH_BEAN)) {
			finish();
			return;
		}
		
		BranchBean.Result bean = (BranchBean.Result) bundle.getSerializable(BRANCH_BEAN);
		setWidgets(bean);
	}
	
	private void setWidgets(BranchBean.Result bean) {
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.ma_action_bar);
		actionBar.setSingleText(bean.getRegion());
		actionBar.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
			}
		});
		
		final LatLng latLng = new LatLng(bean.getLatitude(), bean.getLongitude());
		
		SmartMapFragment mapFragment = new SmartMapFragment();
		mapFragment.setOnMapInitCompletedCallback(new OnMapInitCompletedCallback() {
			
			@Override
			public void onMapInitCompleted(GoogleMap map) {
				map.addMarker(new MarkerOptions().position(latLng));
				map.moveCamera(CameraUpdateFactory.newLatLngZoom(latLng, 16));
			}
		});
		
		getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.ma_container_map, mapFragment)
		.commit();
	}
}
