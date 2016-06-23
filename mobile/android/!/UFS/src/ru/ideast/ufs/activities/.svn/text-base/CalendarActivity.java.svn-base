package ru.ideast.ufs.activities;

import java.util.Calendar;

import net.simonvt.calendarview.CalendarView;
import net.simonvt.calendarview.CalendarView.OnDateChangeListener;
import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.DateBean;
import ru.ideast.ufs.beans.ErrorBean;
import ru.ideast.ufs.exceptions.CorruptedDataException;
import ru.ideast.ufs.exceptions.NoNetworkException;
import ru.ideast.ufs.loaders.FragmentLoader;
import ru.ideast.ufs.loaders.FragmentLoaderManager;
import ru.ideast.ufs.loaders.FragmentLoaderManager.Flag;
import ru.ideast.ufs.managers.HttpManager;
import ru.ideast.ufs.managers.URLManager;
import ru.ideast.ufs.utils.SharedPreferencesWrap;
import ru.ideast.ufs.utils.ToolBox;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.LinearLayout;
import android.widget.LinearLayout.LayoutParams;
import android.widget.Toast;

public class CalendarActivity extends Activity implements FragmentLoaderManager.Callback<DateBean> {

	public static final String CATEGORY_ID = "categoryId";
	public static final String SUBCATEGORY_ID = "subcategoryId";
	
	public static final String DATE = "date";
	
	private FragmentLoaderManager<DateBean> loaderManager;
	
	private int nowDateCode;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.calendar_activity);
		
		Bundle extras = getIntent().getExtras();
		if(extras == null || !extras.containsKey(CATEGORY_ID) || !extras.containsKey(SUBCATEGORY_ID)) {
			finish();
			return;
		}
		
		loaderManager = new FragmentLoaderManager<DateBean>(this);
		loaderManager.run(true, Flag.RUN_IF_NOT_EXIST, extras);

		Calendar calendar = Calendar.getInstance();
		nowDateCode = calendar.get(Calendar.YEAR) + calendar.get(Calendar.MONTH) + calendar.get(Calendar.DAY_OF_MONTH);
		
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.ca_action_bar);
		actionBar.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				setResult(RESULT_CANCELED);
				finish();
			}
		});
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
	public void onResultReceive(DateBean data) {
		if(data.getError().getMessage().equals("NoNetwork")) {
			Toast.makeText(this, getResources().getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
			
			finish();
			return;
		}
		
		CalendarView calendarView = new CalendarView(this);
		calendarView.setEventDates(data.getResult());
		calendarView.setFirstDayOfWeek(2);
		calendarView.setShowWeekNumber(false);
		calendarView.setOnDateChangeListener(new OnDateChangeListener() {
			
			@Override
			public void onSelectedDayChange(CalendarView view, int year, int month, int day) {
				int selDateCode = year + month + day;
				
				//защита от вызова метода при смене месяца
				if(nowDateCode != selDateCode) {
					Calendar calendar = Calendar.getInstance();
					calendar.set(year, month, day);
					calendar.set(Calendar.HOUR_OF_DAY, 0);
					calendar.set(Calendar.MINUTE, 0);
					calendar.set(Calendar.SECOND, 0);
					
					setResult(RESULT_OK, new Intent().putExtra(DATE, calendar.getTimeInMillis() / 1000));
					finish();
				}
			}
		});
		
		int calendarHeight = ToolBox.dp2pix(this, 350);
		LayoutParams params = new LayoutParams(LayoutParams.MATCH_PARENT, calendarHeight);
		
		LinearLayout container = (LinearLayout) findViewById(R.id.ca_container);
		container.addView(calendarView, params);
	}

	@Override
	public FragmentLoader<DateBean> onCreateLoader(Bundle params) {
		return new DateLoader(this, params);
	}
	
	private class DateLoader extends FragmentLoader<DateBean> {

		private String categoryId;
		private String subcategoryId;
		
		public DateLoader(Context context, Bundle params) {
			super(context);
			
			categoryId = params.getString(CalendarActivity.CATEGORY_ID);
			subcategoryId = params.getString(CalendarActivity.SUBCATEGORY_ID);
		}

		@Override
		public void runInBackground(boolean firstRun) {
			DateBean wrappedBeans = null;
			
			try {
				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
				wrappedBeans = HttpManager.INSTANCE.getData(URLManager.getDatesOfNews(token, categoryId, subcategoryId), DateBean.class);
			} catch (NoNetworkException e) {
			} catch (CorruptedDataException e) {
			}
			
			if(wrappedBeans != null)
				publishProgress(wrappedBeans);
			else {
				ErrorBean errorBean = new ErrorBean();
				errorBean.setMessage("NoNetwork");
				
				DateBean dateBean = new DateBean();
				dateBean.setError(errorBean);
				
				publishProgress(dateBean);
			}
		}
	}
}
