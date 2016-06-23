package com.ufsic.core.activities;

import com.google.android.gms.maps.CameraUpdate;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.UiSettings;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.ufsic.core.beans.BranchBean;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.fragments.SmartMapFragment;
import com.ufsic.core.fragments.SmartMapFragment.OnMapInitCompletedCallback;
import com.ufsic.core.utils.ToolBox;
import com.ufsic.core.widgets.ActionBarUfs;

import com.ufsic.core.R;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.FragmentActivity;
import android.text.util.Linkify;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.LinearLayout;
import android.widget.LinearLayout.LayoutParams;
import android.widget.TextView;

public class BranchDetailActivity extends FragmentActivity implements OnClickListener {

	public static final String BRANCH_BEAN = "branch_bean";
	
	private BranchBean.Result bean;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.branch_detail_activity);

		Bundle bundle = getIntent().getExtras();
		if (bundle == null || !bundle.containsKey(BRANCH_BEAN)) {
			finish();
			return;
		}

		bean = (BranchBean.Result) bundle.getSerializable(BRANCH_BEAN);
		setWidgets(bean);
	}

	private void setWidgets(BranchBean.Result bean) {

		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.bda_action_bar);
		actionBar.setSingleText(bean.getRegion());
		actionBar.setOnClickListener(this);
		
		TextView title = (TextView) findViewById(R.id.bda_title);
		title.setText(bean.getAddress());
		
		findViewById(R.id.bda_invisible).setOnClickListener(this);
		
		initMap(new LatLng(bean.getLatitude(), bean.getLongitude()));
		
		if(bean.getContacts().size() > 0) {
			LinearLayout container = (LinearLayout) findViewById(R.id.bda_container);
			LayoutInflater inflater = LayoutInflater.from(this);
			
			LayoutParams params = new LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT);
			int marginBottom = ToolBox.dp2pix(this, 20);
			params.setMargins(0, 0, 0, marginBottom);
			
			int size = bean.getContacts().size();
			for (int i = 0; i < size; i++) {
				View item = inflater.inflate(R.layout.contact_item, null);
				
				TextView titl = (TextView) item.findViewById(R.id.ci_title);
				titl.setText(bean.getContacts().get(i).getTitle());
				
				TextView value = (TextView) item.findViewById(R.id.ci_value);
				setAutoLinkMask(value, bean.getContacts().get(i).getValue());
				value.setText(bean.getContacts().get(i).getValue());
				
				if(i == size-1)
					container.addView(item);
				else
					container.addView(item, params);
			}
		}
	}
	
	private void initMap(final LatLng latLng) {
		SmartMapFragment mapFragment = new SmartMapFragment();
		mapFragment.setOnMapInitCompletedCallback(new OnMapInitCompletedCallback() {
			
			@Override
			public void onMapInitCompleted(GoogleMap map) {
				UiSettings mapSettings = map.getUiSettings();
				mapSettings.setAllGesturesEnabled(false);
				mapSettings.setZoomControlsEnabled(false);
				
				map.addMarker(new MarkerOptions().position(latLng));
				
				CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(latLng, 16);
				map.moveCamera(cameraUpdate);
			}
		});
		
		getSupportFragmentManager()
		.beginTransaction()
		.replace(R.id.bda_container_map, mapFragment)
		.commit();
	}
	
	private void setAutoLinkMask(TextView tv, String key) {
		if(key.contains("@"))
			tv.setAutoLinkMask(Linkify.EMAIL_ADDRESSES);
		else if(key.contains("http"))
			tv.setAutoLinkMask(Linkify.WEB_URLS);
		else
			tv.setAutoLinkMask(Linkify.PHONE_NUMBERS);
	}

	@Override
	public void onClick(View v) {
		
		/* tsv */
		int id = v.getId();
		if (id == R.id.abu_left_btn) {
			finish();
		} else if (id == R.id.bda_invisible) {
			Intent intent = new Intent(this, MapActivity.class);
			intent.putExtra(MapActivity.BRANCH_BEAN, bean);
			startActivity(intent);
		}
		/* tsv */
		
		/*switch (v.getId()) {
		case R.id.abu_left_btn:
			finish();
			break;
		case R.id.bda_invisible:
			Intent intent = new Intent(this, MapActivity.class);
			intent.putExtra(MapActivity.BRANCH_BEAN, bean);
			startActivity(intent);
			break;
		}*/
	}
	
	
}
