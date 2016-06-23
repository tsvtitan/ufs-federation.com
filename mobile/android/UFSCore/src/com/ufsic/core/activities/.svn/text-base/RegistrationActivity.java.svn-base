package com.ufsic.core.activities;

import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;

import com.ufsic.core.beans.QRCodeBean.Promotion;
import com.ufsic.core.beans.QRCodeBean.Promotion.Product;
import com.ufsic.core.beans.QRCodeBean.Promotion.Registration;
import com.ufsic.core.loaders.FragmentLoaderManager;
import com.ufsic.core.utils.ToolBox;
import com.ufsic.core.widgets.ActionBarUfs;

import com.ufsic.core.R;
import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Message;
import android.support.v4.app.FragmentActivity;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnFocusChangeListener;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.Toast;

public class RegistrationActivity extends FragmentActivity {

	public static final String PROMOTION = "promotion";
	public static final String TITLE = "title";
	
	private ArrayList<Product> products;
	private Promotion promotion;
	private String title;
	private LinearLayout layout;
	private EditText name;
	private EditText phone;
	private EditText email;
	private CheckBox brokerage;
	private CheckBox yield;
	private Button next;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		setContentView(R.layout.registration_activity);
		
		Bundle bundle = getIntent().getExtras();
		if (bundle == null || !bundle.containsKey(PROMOTION) || !bundle.containsKey(TITLE)) {
			finish();
			return;
		}
		
		setWidgets(bundle);
	}

	private void setWidgets(Bundle bundle) {

		promotion = (Promotion)bundle.getSerializable(PROMOTION);
		products = promotion.getProducts();
		if (products!=null) {
			for (Product product: products) {
				
				Integer countdown = product.getCountdown();
				if (countdown!=null && countdown>0) {
					
					Date expired = product.getExpired();
					if (expired==null) {
						Calendar calendar = Calendar.getInstance();
						calendar.setTime(new Date());
						calendar.add(Calendar.SECOND,countdown);
						product.setExpired(calendar.getTime());
					}
				}
			}
		}
		
		title = bundle.getString(TITLE);
		
		layout = (LinearLayout)findViewById(R.id.rg_layout);
		layout.setOnTouchListener(new View.OnTouchListener() {
			
			@Override
			public boolean onTouch(View v, MotionEvent event) {
				
				if (v==layout) {
					hideKeyboard(RegistrationActivity.this.getCurrentFocus());
				}
				return false;
			}
		});
		
		name = (EditText)findViewById(R.id.rg_name);
		phone = (EditText)findViewById(R.id.rg_phone);
		email = (EditText)findViewById(R.id.rg_email);
		brokerage = (CheckBox)findViewById(R.id.rg_brokerage);
		yield = (CheckBox)findViewById(R.id.rg_yield);

		next = (Button)findViewById(R.id.rg_next);
		next.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				
				if (checkFields()) {
					Intent intent = new Intent(RegistrationActivity.this,PromotionActivity.class);
					intent.putExtra(PromotionActivity.PRODUCTS,products);
					intent.putExtra(PromotionActivity.TITLE_PARENT,title);
					intent.putExtra(PromotionActivity.TITLE,promotion.getTitle());
					intent.putExtra(PromotionActivity.REG_NAME,name.getText().toString());
					intent.putExtra(PromotionActivity.REG_PHONE,phone.getText().toString());
					intent.putExtra(PromotionActivity.REG_EMAIL,email.getText().toString());
					intent.putExtra(PromotionActivity.REG_BROKERAGE,brokerage.isChecked());
					intent.putExtra(PromotionActivity.REG_YIELD,yield.isChecked());
					startActivityForResult(intent,0);
				}
			}
			
		});
		
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.rg_action_bar);
		actionBar.setSingleText(getResources().getString(R.string.registration));
		actionBar.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
			}
		});
		
		if (promotion!=null) {
			
			Registration registration = promotion.getRegistration();
			if (registration!=null) {
				
				name.setText(registration.getName());
				phone.setText(registration.getPhone());
				email.setText(registration.getEmail());
				brokerage.setChecked(registration.getBrokerage());
				yield.setChecked(registration.getYield());
			}
		}
	}
	
	private void hideKeyboard(View view) {
		if (view!=null) view.clearFocus();
        InputMethodManager inputMethodManager = (InputMethodManager)getSystemService(Activity.INPUT_METHOD_SERVICE);
        if (view!=null) inputMethodManager.hideSoftInputFromWindow(view.getWindowToken(), 0);
    }
	
	private void showMessage(int id) {
		
		Toast.makeText(this,getResources().getString(id),Toast.LENGTH_SHORT).show();
	}
	
	private boolean checkFields() {
		
		if (ToolBox.isEmpty(name.getText().toString())) {
			showMessage(R.string.input_name);
			return false;
		}
		
		if (ToolBox.isEmpty(phone.getText().toString())) {
			showMessage(R.string.input_phone);
			return false;
		}
		
		if (!ToolBox.isEmail(email.getText().toString())) {
			showMessage(R.string.input_email);
			return false;
		}
		
		return true;
	}

	@Override
	@SuppressWarnings("unchecked")
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		if (requestCode==0 && resultCode==RESULT_OK) {
			if (data!=null) {
				
				Bundle bundle = data.getExtras();
				if (bundle.getBoolean(PromotionActivity.ACCEPTED)) {
					finish();
				}
				
				ArrayList<Product> list = (ArrayList<Product>)bundle.getSerializable(PromotionActivity.PRODUCTS);
				if (list!=null) {
					products = list;
				}
			}
		}
	}
}
