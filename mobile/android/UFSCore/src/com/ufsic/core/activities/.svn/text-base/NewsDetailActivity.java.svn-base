package com.ufsic.core.activities;

import java.util.ArrayList;
import java.util.HashSet;

import com.ufsic.core.beans.KeywordsBean;
import com.ufsic.core.beans.NewsBean;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.db.DatabaseManager;
import com.ufsic.core.layouts.FlowLayout;
import com.ufsic.core.layouts.FlowLayoutEx;
import com.ufsic.core.utils.ToolBox;
import com.ufsic.core.widgets.ActionBarUfs;

import com.ufsic.core.R;


import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.Gravity;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;

public class NewsDetailActivity extends Activity implements OnClickListener {
	
	public static final String NEWS_BEAN = "newsBean";
	/* tsv */public static final String TITLES = "titles";
	
	private NewsBean.Result news;
	/* tsv */
	private String[] titles;
	private FlowLayoutEx keywordsLayout;
	private Button subscribeButton;
	/* tsv */
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.news_detail_activity);
		
		Bundle bundle = getIntent().getExtras();
		if(bundle == null || !bundle.containsKey(NEWS_BEAN) || !bundle.containsKey(TITLES)) {
			finish();
			return;
		}
		
		news = (NewsBean.Result) bundle.getSerializable(NEWS_BEAN);
		setWidgets(bundle);
	}
	
	private void setWidgets(Bundle bundle) {
		
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
		
		/* tsv */
		titles = bundle.getStringArray(TITLES);
		keywordsLayout = (FlowLayoutEx)findViewById(R.id.nda_keywords);
		subscribeButton = (Button)findViewById(R.id.nda_subscribe);
		/* tsv */
	}

	/* tsv */
	@Override
	public void onResume() {
		super.onResume();
		updateKeywords();
	}
	
	private ArrayList<String> getSelectedKeywords() {
		
		ArrayList<String> ret = new ArrayList<String>();
		for (int i=0; i<keywordsLayout.getChildCount(); i++) {
			
			View view = keywordsLayout.getChildAt(i);
			if (view!=null && view instanceof Button) {
				
				Button button = (Button)view;
				if (button.isSelected() && button.isEnabled()) {
					ret.add(button.getText().toString());
				}
			}
		}
		return ret;
	}
	
	private void updateKeywords() {
		
		keywordsLayout.removeAllViews();
		
		if (news!=null) {
			
			ArrayList<String> keywords = news.getKeywords();
			if (keywords!=null && !keywords.isEmpty()) {
				
				FlowLayoutEx.LayoutParams params = new FlowLayoutEx.LayoutParams(LayoutParams.WRAP_CONTENT,LayoutParams.WRAP_CONTENT);
				params.height = 50;
				params.leftMargin = 6;
				params.bottomMargin = 6;
				params.rightMargin = 0;
				params.topMargin = 0;
				params.gravity = Gravity.CENTER;
				
				ArrayList<KeywordsBean.Result> list;
				try {
					list = DatabaseManager.INSTANCE.getList(KeywordsBean.Result.class);
				} catch (Exception e) {
					list = null;
				}
				
				for (String word: keywords) {
					
					if (!ToolBox.isEmpty(word)) {
						
						final Button btn = new Button(this);
						btn.setLayoutParams(params);

						btn.setTextSize(13);
			        	
						btn.setText(word);
						btn.setGravity(Gravity.LEFT | Gravity.CENTER_VERTICAL);
						
						boolean exists = false;
						if (list!=null && !list.isEmpty()) {
							KeywordsBean.Result result = KeywordsBean.findByKeyword(list,word);
							exists = result!=null;
						}
						btn.setBackgroundResource(exists?R.drawable.btn_keyword:R.drawable.btn_keyword_selected);
						btn.setSelected(!exists); 
						btn.setEnabled(!exists);
						btn.setPadding(46,0,15,3);
						
						btn.setOnTouchListener(new OnTouchListener() {
							
							@Override
							public boolean onTouch(View v, MotionEvent event) {
								
								boolean b = btn.isSelected();
								boolean ret = false;
								
								switch (event.getAction()) {
									case MotionEvent.ACTION_DOWN: {
									
										btn.setBackgroundResource(b?R.drawable.btn_keyword:R.drawable.btn_keyword_selected);
										ret = true;
										break;
									}
									case MotionEvent.ACTION_UP: {
										
										btn.setBackgroundResource(b?R.drawable.btn_keyword:R.drawable.btn_keyword_selected);
										btn.setSelected(!b);
										ret = true;
										break;
									}
									case MotionEvent.ACTION_CANCEL: {
										
										btn.setBackgroundResource(b?R.drawable.btn_keyword:R.drawable.btn_keyword_selected);
										ret = true;
										break;
									}
								}
								
								if (ret) {
									btn.setPadding(46,0,15,3);
									
									ArrayList<String> keywords = getSelectedKeywords();
									subscribeButton.setEnabled(keywords.size()>0);
									
								}
								
								return ret;
							}
						});
						
						keywordsLayout.addView(btn);
					}
				}
				
				if (keywordsLayout.getChildCount()>0) {
					
					keywordsLayout.setVisibility(View.VISIBLE);
					
					if (subscribeButton!=null) {
						
						final Activity activity = this;
						subscribeButton.setOnClickListener(new OnClickListener() {
							
							@Override
							public void onClick(View v) {
								
								AnalyticsCounter.eventScreens(titles,subscribeButton.getHint().toString(),null,null);
								
								ArrayList<String> keywords = getSelectedKeywords();
								
								Intent intent = new Intent(activity,SubscriptionActivity.class);
								intent.putExtra(SubscriptionActivity.TITLE,subscribeButton.getHint());
								intent.putExtra(SubscriptionActivity.KEYWORDS,keywords.toArray(new String[keywords.size()]));
								
								startActivity(intent);
							}
						});
						subscribeButton.setVisibility(View.VISIBLE);
						subscribeButton.setEnabled(getSelectedKeywords().size()>0);
					}
				}
			}
		}
	}
	
	/* tsv */
	
	@Override
	public void onClick(View v) {
		
		/* tsv */
		int id = v.getId();
		if (id == R.id.abu_left_btn) {
			finish();
		} else if (id == R.id.nda_pdf) {
			Intent intent = new Intent(this, FileSelectActivity.class);
			intent.putExtra(FileSelectActivity.URLS, news.getFileUrls());
			intent.putExtra(FileSelectActivity.TITLE, news.getTitle());
			startActivity(intent);
		}
		/* tsv */
		
		
		/*switch (v.getId()) {
		case R.id.abu_left_btn:
			finish();
			break;
		case R.id.nda_pdf:
			Intent intent = new Intent(this, FileSelectActivity.class);
			intent.putExtra(FileSelectActivity.URLS, news.getFileUrls());
			intent.putExtra(FileSelectActivity.TITLE, news.getTitle());
			startActivity(intent);
			break;
		}*/
	}
}
