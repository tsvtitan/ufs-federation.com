package ru.ideast.ufs.activities;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.NewsBean;
import ru.ideast.ufs.exceptions.CorruptedDataException;
import ru.ideast.ufs.exceptions.NoNetworkException;
import ru.ideast.ufs.managers.HttpManager;
import ru.ideast.ufs.managers.URLManager;
import ru.ideast.ufs.utils.ToolBox;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.LinearLayout;
import android.widget.RelativeLayout.LayoutParams;
import android.widget.TextView;
import android.widget.Toast;

public class FileSelectActivity extends Activity implements OnClickListener {

	public static final String URLS = "urls";
	public static final String TITLE = "title";

	private ArrayList<NewsBean.Url> urls;
	
	private LinearLayout container;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.file_select_activity);

		Bundle bundle = getIntent().getExtras();
		if (bundle == null || !bundle.containsKey(URLS) || !bundle.containsKey(TITLE)) {
			finish();
			return;
		}

		urls = (ArrayList<NewsBean.Url>) bundle.getSerializable(URLS);
		String title = bundle.getString(TITLE);

		if(urls.size() > 1)
			setWidgets(title);
		else
			loadFile(urls.get(0).getUrl(), urls.get(0).getSize());
	}

	private void setWidgets(String title) {
		findViewById(R.id.fsa_progress).setVisibility(View.GONE);

		container = (LinearLayout) findViewById(R.id.fsa_container);
		container.setVisibility(View.VISIBLE);

		ActionBarUfs actionBar = new ActionBarUfs(this);
		
		int actionBarHeight = getResources().getDimensionPixelSize(R.dimen.action_bar_height);
		LayoutParams params = new LayoutParams(LayoutParams.MATCH_PARENT, actionBarHeight);
		
		actionBar.setLeftBtn(R.drawable.btn_back_selector);
		actionBar.setDualText(title, getResources().getString(R.string.file_sub_title));
		actionBar.setBackgroundResource(R.drawable.actionbar);
		actionBar.setOnClickListener(this);
		container.addView(actionBar, params);

		//из-за проблем с margin у RelativeLayout
		View marginView = new View(this);
		int marginViewHeight = ToolBox.dp2pix(this, 5);
		params = new LayoutParams(LayoutParams.MATCH_PARENT, marginViewHeight);
		container.addView(marginView, params);
		
		LayoutInflater inflater = LayoutInflater.from(this);

		for (int i = 0; i < urls.size(); i++) {
			View item = inflater.inflate(R.layout.file_item, null);

			TextView text = (TextView) item.findViewById(R.id.fi_text);
			text.setText(urls.get(i).getName());
			
			item.setTag(i);
			item.setOnClickListener(this);
			
			container.addView(item);
		}
	}
	
	private void clearWidgets() {
		container.setVisibility(View.GONE);
		findViewById(R.id.fsa_progress).setVisibility(View.VISIBLE);
	}
	
	//private void loadFile(final String url, long size) {
	/* tsv */ 
	private void loadFile(final String url, Long size) {
		
		//убираем /files/ из начала
		String fileName = url.substring(7);
		
		File file = new File(HttpManager.INSTANCE.getDiskCacheDir(), fileName + ".pdf");
		if(file.exists()) {
			finish();
			
			Intent intent = new Intent(this, PdfActivity.class);
			intent.putExtra(PdfActivity.FILE, file);
			startActivity(intent);
			
			return;
		}
		
		Resources res = getResources();
		
		AlertDialog.Builder adb = new AlertDialog.Builder(this);
		adb.setTitle(res.getString(R.string.file_dialog_title));
		
		//adb.setMessage(String.format(res.getString(R.string.file_dialog_message), humanReadableByteCount(size, true)));
		/* tsv */
		if (size!=null) {
			adb.setMessage(String.format(res.getString(R.string.file_dialog_message), humanReadableByteCount(size, true)));
		} else {
			adb.setMessage(res.getString(R.string.file_dialog_message_wo_size));
		}
		/* tsv */
		
		adb.setPositiveButton("ДА", new DialogInterface.OnClickListener() {
			
			@Override
			public void onClick(DialogInterface dialog, int which) {
				new FileLoader(url).execute();
			}
		});
		adb.setNegativeButton("НЕТ", new DialogInterface.OnClickListener() {
			
			@Override
			public void onClick(DialogInterface dialog, int which) {
				finish();
			}
		});
		adb.setCancelable(false);
		adb.show();
	}

	@Override
	public void onClick(View v) {

		switch (v.getId()) {
		case R.id.abu_left_btn:
			finish();
			break;
		case R.id.fi_container:
			int i = (Integer) v.getTag();
			loadFile(urls.get(i).getUrl(), urls.get(i).getSize());
			clearWidgets();
			break;
		}
	}
	
	private class FileLoader extends AsyncTask<Void, Void, File> {
		
		private String url;
		
		public FileLoader(String url) {
			this.url = url;
		}
		
		@Override
		protected File doInBackground(Void... params) {
			File file = null;
			
			try {
				//убираем /files/ из начала
				String fileName = url.substring(7);
				
				file = HttpManager.INSTANCE.getFile(URLManager.getFile(url), fileName + ".pdf");
			} catch (IOException e) {
				e.printStackTrace();
			} catch (NoNetworkException e) {
				e.printStackTrace();
			} catch (CorruptedDataException e) {
				e.printStackTrace();
			}
			
			return file;
		}
		
		@Override
		protected void onPostExecute(File result) {
			finish();
			
			if(result == null) {
				Toast.makeText(FileSelectActivity.this, getResources().getString(R.string.file_failed), Toast.LENGTH_SHORT).show();
			} else {
				Intent intent = new Intent(FileSelectActivity.this, PdfActivity.class);
				intent.putExtra(PdfActivity.FILE, result);
				startActivity(intent);
			}
		}
	}
	
	public String humanReadableByteCount(long bytes, boolean si) {
	    int unit = si ? 1000 : 1024;
	    if (bytes < unit) return bytes + " B";
	    int exp = (int) (Math.log(bytes) / Math.log(unit));
	    String pre = (si ? "kMGTPE" : "KMGTPE").charAt(exp-1) + (si ? "" : "i");
	    return String.format("%.1f %sB", bytes / Math.pow(unit, exp), pre);
	}
}
