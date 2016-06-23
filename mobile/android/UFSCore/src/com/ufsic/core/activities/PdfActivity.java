package com.ufsic.core.activities;

import java.io.File;

import com.joanzapata.pdfview.*;
import com.ufsic.core.counters.AnalyticsCounter;

import com.ufsic.core.R;
import android.app.Activity;
import android.os.Bundle;

public class PdfActivity extends Activity {
	
	public static final String FILE = "file";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.pdf_activity);
		
		Bundle extras = getIntent().getExtras();
		if(extras == null || !extras.containsKey(FILE)) {
			finish();
			return;
		}
		
		File file = (File) extras.getSerializable(FILE);
		
		PDFView pdfView = (PDFView) findViewById(R.id.pa_pdf);
		pdfView.fromFile(file)
			.showMinimap(true)
			.load();
	}
}
