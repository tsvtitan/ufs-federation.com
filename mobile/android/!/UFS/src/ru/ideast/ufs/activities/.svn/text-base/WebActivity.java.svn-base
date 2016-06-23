package ru.ideast.ufs.activities;

import ru.ideast.ufs.R;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.webkit.WebSettings;
import android.webkit.WebView;

public class WebActivity extends Activity {
	
	public static final String NAME = "name";
	public static final String TEXT = "text";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.web_activity);
		
		Bundle extras = getIntent().getExtras();
		if(extras == null || !extras.containsKey(NAME) || !extras.containsKey(TEXT)) {
			finish();
			return;
		}
		
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.wa_action_bar);
		actionBar.setSingleText(extras.getString(NAME));
		actionBar.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				finish();
			}
		});
		
		WebView web = (WebView) findViewById(R.id.wa_web);
		WebSettings settings = web.getSettings();
		settings.setDefaultTextEncodingName("utf-8");
		web.loadDataWithBaseURL(null, extras.getString(TEXT), "text/html", "utf-8", null);
	}
}
