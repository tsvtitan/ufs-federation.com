package ru.ideast.ufs.activities;

import com.nostra13.universalimageloader.core.ImageLoader;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.GroupDetailBean;
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
import ru.ideast.ufs.widgets.ActionBarUfs;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.os.Message;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

public class GroupDetailActivity extends Activity implements FragmentLoaderManager.Callback<Message>, OnClickListener {
	
	public static final String NEWS_ID = "linkID";
	
	private String newsID;
	
	private GroupDetailBean.Result group;
	
	private FragmentLoaderManager<Message> loaderManager;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.group_detail_activity);
		
		Bundle bundle = getIntent().getExtras();
		if(bundle == null || !bundle.containsKey(NEWS_ID)) {
			finish();
			return;
		}
		
		newsID = bundle.getString(NEWS_ID);
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
	}
	
	private void setWidgets() {
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.gda_action_bar);
		actionBar.setSingleText(group.getTitle());
		actionBar.setOnClickListener(this);
		
		TextView titleTv = (TextView) findViewById(R.id.gda_title);
		titleTv.setText(group.getTitle());
		
		if(group.getFileUrls().size() > 0) {
			ImageView pdfImage = (ImageView) findViewById(R.id.gda_pdf);
			pdfImage.setVisibility(View.VISIBLE);
			pdfImage.setOnClickListener(this);
		}
		
		TextView dateTv = (TextView) findViewById(R.id.gda_date);
		dateTv.setText(group.getDateStr());
		
		TextView textTv = (TextView) findViewById(R.id.gda_text);
		textTv.setText(group.getText());
		
		ImageView image = (ImageView) findViewById(R.id.gda_image);
		image.setOnClickListener(this);
		
		ImageLoader imageLoader = ImageLoader.getInstance();
		imageLoader.displayImage(URLManager.getFile(group.getImageUrls().get(0).getUrl()), image);
		
		if(group.getRelatedLinks().size() > 0) {
			findViewById(R.id.gda_related).setVisibility(View.VISIBLE);
			
			LinearLayout container = (LinearLayout) findViewById(R.id.gda_container);
			LayoutInflater inflater = LayoutInflater.from(this);
			
			int linksSize = group.getRelatedLinks().size();
			for (int i = 0; i < linksSize; i++) {
				View item = inflater.inflate(R.layout.group_full_list_item, null);
				
				if(linksSize == 1)
					item.setBackgroundResource(R.drawable.table_center_single);
				else if(i == 0)
					item.setBackgroundResource(R.drawable.table_header_without_shadow);
				else if(i == linksSize - 1)
					item.setBackgroundResource(R.drawable.table_footer_with_shadow);
				else
					item.setBackgroundResource(R.drawable.table_center);
				
				item.findViewById(R.id.gbli_actual).setVisibility(View.INVISIBLE);
				
				TextView text = (TextView) item.findViewById(R.id.gbli_date);
				text.setText(group.getRelatedLinks().get(i).getDate());
				
				TextView name = (TextView) item.findViewById(R.id.gbli_name);
				name.setText(group.getRelatedLinks().get(i).getName());
				
				item.setTag(group.getRelatedLinks().get(i).getId());
				item.setOnClickListener(this);
				
				container.addView(item);
			}
		}
	}
	
	@Override
	public void onClick(View v) {
		
		switch (v.getId()) {
		case R.id.abu_left_btn:
			finish();
			break;
		case R.id.gda_pdf:
			Intent intent = new Intent(this, FileSelectActivity.class);
			intent.putExtra(FileSelectActivity.URLS, group.getFileUrls());
			intent.putExtra(FileSelectActivity.TITLE, group.getTitle());
			startActivity(intent);
			break;
		case R.id.gda_image:
			Intent intentGallery = new Intent(this, GalleryActivity.class);
			intentGallery.putExtra(GalleryActivity.URLS, group.getImageUrls());
			startActivity(intentGallery);
			break;
		case R.id.gbli_container:
			Intent intentRelated = new Intent(this, GroupDetailActivity.class);
			intentRelated.putExtra(GroupDetailActivity.NEWS_ID, (String) v.getTag());
			startActivity(intentRelated);
			break;
		}
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
		int id = Integer.valueOf(newsID);
		findViewById(R.id.gda_progress).setVisibility(View.GONE);
		
		Resources res = getResources();
		
		switch (data.what) {
		case LoadingState.NO_NETWORK_AND_DATA:
			Toast.makeText(this, res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			finish();
			break;
		case LoadingState.SUCCESS:
			group = DatabaseManager.INSTANCE.getObject(id, GroupDetailBean.Result.class);
			setWidgets();
			break;
		case LoadingState.NO_NETWORK:
			Toast.makeText(this, res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
			group = DatabaseManager.INSTANCE.getObject(id, GroupDetailBean.Result.class);
			setWidgets();
			break;
		}
	}

	@Override
	public FragmentLoader<Message> onCreateLoader(Bundle params) {
		return new GroupDetailLoader(this);
	}
	
	private class GroupDetailLoader extends FragmentLoader<Message> {

		public GroupDetailLoader(Context context) {
			super(context);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			int id = Integer.valueOf(newsID);
			GroupDetailBean.Result bean = null;
			boolean fromNetwork = false;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				GroupDetailBean wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getGroupsDetail(token, "3", "13", newsID), GroupDetailBean.class);
				bean = wrappedBeans.getResult().get(0);
				
				DatabaseManager.INSTANCE.deleteForId(id, GroupDetailBean.Result.class);
				DatabaseManager.INSTANCE.addObject(bean, GroupDetailBean.Result.class);
				fromNetwork = true;
			} catch (NoNetworkException e) {
				bean = DatabaseManager.INSTANCE.getObject(id, GroupDetailBean.Result.class);
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
