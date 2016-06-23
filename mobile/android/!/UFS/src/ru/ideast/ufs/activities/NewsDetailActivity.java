package ru.ideast.ufs.activities;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.NewsBean;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.TextView;

public class NewsDetailActivity extends Activity implements OnClickListener {
	
	public static final String NEWS_BEAN = "newsBean";
	
	private NewsBean.Result news;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.news_detail_activity);
		
		Bundle bundle = getIntent().getExtras();
		if(bundle == null || !bundle.containsKey(NEWS_BEAN)) {
			finish();
			return;
		}
		
		news = (NewsBean.Result) bundle.getSerializable(NEWS_BEAN);
		setWidgets();
	}
	
	private void setWidgets() {
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.nda_action_bar);
		actionBar.setSingleText(news.getTitle());
		actionBar.setOnClickListener(this);
		
		TextView titleTv = (TextView) findViewById(R.id.nda_title);
		titleTv.setText(news.getTitle());
		
		if(news.getFileUrls().size() > 0) {
			ImageView pdfImage = (ImageView) findViewById(R.id.nda_pdf);
			pdfImage.setVisibility(View.VISIBLE);
			pdfImage.setOnClickListener(this);
		}
		
		TextView dateTv = (TextView) findViewById(R.id.nda_date);
		dateTv.setText(news.getDateStr());
		
		TextView textTv = (TextView) findViewById(R.id.nda_text);
		textTv.setText(news.getText());
	}

	@Override
	public void onClick(View v) {
		
		switch (v.getId()) {
		case R.id.abu_left_btn:
			finish();
			break;
		case R.id.nda_pdf:
			Intent intent = new Intent(this, FileSelectActivity.class);
			intent.putExtra(FileSelectActivity.URLS, news.getFileUrls());
			intent.putExtra(FileSelectActivity.TITLE, news.getTitle());
			startActivity(intent);
			break;
		}
	}
}
