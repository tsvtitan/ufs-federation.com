package com.ufsic.core.activities;

/* tsv */

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.TimeZone;
import java.util.concurrent.TimeUnit;

import org.apache.http.conn.ssl.SSLSocketFactory;

import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.async.http.AsyncSSLSocketMiddleware;
import com.koushikdutta.ion.Ion;
import com.ufsic.core.activities.MainActivity;
import com.ufsic.core.beans.PromotionBean;
import com.ufsic.core.beans.QRCodeBean.Promotion;
import com.ufsic.core.beans.QRCodeBean.Promotion.Product;
import com.ufsic.core.beans.QRCodeBean.Promotion.Product.StatusType;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.managers.HttpManager;
import com.ufsic.core.managers.LoadingState;
import com.ufsic.core.managers.TrustAllSSLSocketFactory;
import com.ufsic.core.managers.URLManager;
import com.ufsic.core.utils.SharedPreferencesWrap;
import com.ufsic.core.utils.ToolBox;
import com.ufsic.core.widgets.ActionBarUfs;


import com.ufsic.core.R;


import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.graphics.Color;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;

import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentActivity;
import android.support.v4.app.NavUtils;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

public class PromotionActivity extends FragmentActivity {

	public static final String PROMOTION = "promotion";
	public static final String PRODUCTS = "products";
	public static final String TITLE = "title";
	public static final String TITLE_PARENT = "titleParent";
	public static final String REG_NAME = "regName";
	public static final String REG_PHONE = "regPhone";
	public static final String REG_EMAIL = "regEmail";
	public static final String REG_BROKERAGE = "regBrokerage";
	public static final String REG_YIELD = "regYield";
	public static final String ACCEPTED = "accepted";
	
	private String title;
	private String titleParent;
	
	private ArrayList<Product> products;
	private Spinner productNames;
	private ImageView productImage;
	private ProgressBar progressBar;
	private TextView productDesc;
	private CheckBox productCheck;
	private TextView productCountdown;
	private Button productAccept;
	private Button productReject;
	private SimpleDateFormat countdownFormat;
	
	private String regName;
	private String regPhone;
	private String regEmail;
	private Boolean regBrokerage;
	private Boolean regYield;
	
	private Boolean acceptOrReject;
	private boolean accepted = false;
	private StatusType[] statusesAR = new StatusType[] {StatusType.ACCEPTED,StatusType.REJECTED};
	
	final private int redColor = Color.parseColor("#cc0000");
	final private int greenColor = Color.parseColor("#006e2e");
	
	private Handler messageHandler = null;
	
	final private TrustAllSSLSocketFactory factory = (TrustAllSSLSocketFactory)TrustAllSSLSocketFactory.createTrustAllSSLSocketFactory();
	final private HashMap<Product,CountDownTimer> timers = new HashMap<Product,CountDownTimer>();

	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		setContentView(R.layout.promotion_activity);
		
		Bundle bundle = getIntent().getExtras();
		
		if (bundle == null || !(bundle.containsKey(PROMOTION) || bundle.containsKey(PRODUCTS)) || 
			!bundle.containsKey(TITLE_PARENT)) {
			
			finish();
			return;
		}
		
		messageHandler = new Handler(Looper.getMainLooper()) {
        	
        	@Override
            public void handleMessage(Message inputMessage) {
        		PromotionActivity.this.onResultReceive(inputMessage);
    		}
        };
		
		setWidgets(bundle);
	}

	@SuppressWarnings("unchecked")
	private void setWidgets(Bundle bundle) {
		
		Promotion promotion = (Promotion)bundle.getSerializable(PROMOTION);
		if (promotion!=null) {
			products = promotion.getProducts();
			title = promotion.getTitle();
		} else {
			products = (ArrayList<Product>)bundle.getSerializable(PRODUCTS);
			title = bundle.getString(TITLE);
		}
		
		titleParent = bundle.getString(TITLE_PARENT);
		
		regName = bundle.getString(REG_NAME);
		regPhone = bundle.getString(REG_PHONE);
		regEmail = bundle.getString(REG_EMAIL);
		regBrokerage = bundle.getBoolean(REG_BROKERAGE);
		regYield = bundle.getBoolean(REG_YIELD);
		
		FrameLayout frame = (FrameLayout)findViewById(R.id.pr_frame_image);
		frame.setLayoutParams(new LinearLayout.LayoutParams(LayoutParams.MATCH_PARENT,ToolBox.dp2pix(this,200)));
		
		productImage = (ImageView)findViewById(R.id.pr_image);
		productImage.setImageDrawable(null); // may be need a default image
		
		progressBar = (ProgressBar)findViewById(R.id.pr_progress);
		productNames = (Spinner)findViewById(R.id.pr_names);
		productDesc = (TextView)findViewById(R.id.pr_desc);
		productCheck = (CheckBox)findViewById(R.id.pr_check);
		productCountdown = (TextView)findViewById(R.id.pr_countdown);
		productAccept = (Button)findViewById(R.id.pr_accept);
		productReject = (Button)findViewById(R.id.pr_reject);		
		countdownFormat = new SimpleDateFormat("HH:mm:ss");
		countdownFormat.setTimeZone(TimeZone.getTimeZone("UTC"));
		
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.pr_action_bar);
		actionBar.setSingleText(title);
		actionBar.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				
				onBackPressed();
			}
		});
		
		if (products!=null) {
			
			List<String> list = new ArrayList<String>();
			
			for (final Product product: products) {
				
				list.add(product.getName());
				
				Integer countdown = product.getCountdown();
				if (countdown!=null && countdown>0) {
					
					Date expired = product.getExpired();
					if (expired==null) {
						expired = new Date();
						Calendar calendar = Calendar.getInstance();
						calendar.setTime(expired);
						calendar.add(Calendar.SECOND,countdown);
						product.setExpired(calendar.getTime());
					}
					
					if (expired.getTime()>new Date().getTime()) {
						
						CountDownTimer timer = new CountDownTimer(countdown*1000,1000) {
	
							@Override
							public void onFinish() {
								
								synchronized (product) {
									
									product.setCountdown(0);
									updateCountdown(product,0);
								    setStateForAll(product,false);
								    
								}
							}
	
							@Override
							public void onTick(long overall) {
								
								synchronized (product) {
									
									Date expired = product.getExpired();
									if (expired!=null) {
										
										long diffMsec = expired.getTime() - new Date().getTime();
										Long secondsLeft = TimeUnit.MILLISECONDS.toSeconds(diffMsec);
										if (secondsLeft>0) {
											
											product.setCountdown(secondsLeft.intValue());
										  
											if (product==getCurrentProduct()) {
											  
												updateCountdown(product,diffMsec);
											}
											
										} else this.cancel();
									} else this.cancel();
								}
							}
							
						}.start();
						timers.put(product,timer);
						
					} else {
						product.setCountdown(0);
						updateCountdown(product,0);
					    setStateForAll(product,false);
					}
					
				} else {
					product.setCountdown(0);
					updateCountdown(product,0);
				    setStateForAll(product,false);
				}
			}
		
			ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_spinner_dropdown_item,list);
			productNames.setAdapter(adapter);
			
			if (products.size()>1) {
				
				productNames.setOnItemSelectedListener(new OnItemSelectedListener() {
	
					@Override
					public void onItemSelected(AdapterView<?> parent, View view, int pos, long id) {
						
						changeProduct(products.get(pos));
					}
	
					@Override
					public void onNothingSelected(AdapterView<?> view) {
						//
					}
					
				});
				
			} else {
				
				if (products.size()==1) {
					
					productNames.setVisibility(View.GONE);
					productNames.setSelection(0);
					changeProduct(products.get(0));
					
				}
			}
		}
	}
	
	private Product getCurrentProduct() {
		
		Product ret = null;
		int pos = productNames.getSelectedItemPosition();
		if (pos!=-1) {
			ret = products.get(pos);
		}
		return ret;
	}
	
	private void cancelTimer(Product product) {
		
		CountDownTimer timer = timers.get(product);
		if (timer!=null) {
			timer.cancel();
		}
	}
	
	private void updateCountdown(Product product, long overall) {
		
		if (productCountdown.getVisibility()==View.VISIBLE) {
			boolean show = !Arrays.asList(statusesAR).contains(product.getStatusType());
			String time = countdownFormat.format(new Date(overall));
			productCountdown.setText(show?time:null);
			if (overall>0) {
				productCountdown.setTextColor(greenColor);
			} else {
				productCountdown.setTextColor(redColor);
			}
		}
	}
	
	private void updateButtonsState(Product product, boolean enabled) {
		
		StatusType status = product.getStatusType();
		productAccept.setEnabled(enabled);
		if (status==StatusType.ACCEPTED) {
			
			productAccept.setBackgroundResource(R.drawable.btn_green);
			productAccept.setTextColor((enabled)?Color.BLACK:Color.LTGRAY);
		} else {
			productAccept.setTextColor((enabled)?Color.BLACK:Color.GRAY);
		}
		
		productReject.setEnabled(enabled);
		if (status==StatusType.REJECTED) {
			
			productReject.setBackgroundResource(R.drawable.btn_red);
			productReject.setTextColor((enabled)?Color.BLACK:Color.LTGRAY);
		} else {
			productReject.setTextColor((enabled)?Color.BLACK:Color.GRAY);
		}
	}
	
	private void setStateForAll(Product product, boolean enabled) {
		
		
		boolean newState = enabled;
		Integer countdown = product.getCountdown();
		if (countdown!=null) {
			newState = newState && countdown>0;
		}
		
		productImage.setEnabled(newState);
		productDesc.setEnabled(newState);
		productCheck.setEnabled(newState);
		
		boolean flag = newState;
		if (productCheck.getVisibility()==View.VISIBLE) {
			flag = flag && productCheck.isChecked();
		}
		
		updateButtonsState(product,flag);
		
		productAccept.setVisibility((!accepted && product.getStatusType()!=Product.StatusType.STARTED)?View.INVISIBLE:View.VISIBLE);
		if (productAccept.getVisibility()==View.INVISIBLE) {
			
			productDesc.setText(getResources().getString(R.string.promotion_sorry));
			
			productCheck.setVisibility(View.INVISIBLE);
			productCountdown.setVisibility(View.INVISIBLE);
			productReject.setVisibility(View.INVISIBLE);
		}
	}
	
	private void changeProduct(final Product product) {
		
		
		final StatusType status = product.getStatusType();
		final boolean enabled = status == StatusType.STARTED; 
		
		progressBar.setVisibility(View.INVISIBLE);
		productImage.setImageDrawable(null);
		
		productDesc.setVisibility(View.INVISIBLE);
		productDesc.setText(null);
		
		productCheck.setVisibility(View.INVISIBLE);
		productCheck.setOnCheckedChangeListener(null);
		productCheck.setChecked(Arrays.asList(statusesAR).contains(status));
		
		Integer countdown = product.getCountdown();
		productCountdown.setVisibility((countdown!=null)?View.VISIBLE:View.INVISIBLE);
		updateCountdown(product,0);
		
		acceptOrReject = null;
		
		productAccept.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View view) {
				
				productNames.setEnabled(false);
				setStateForAll(product,false);
				acceptOrReject = true;
				getPromotion();
			}
		});
		
		productReject.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View view) {
				
				productNames.setEnabled(false);
				setStateForAll(product,false);
				acceptOrReject = false;
				getPromotion();
			}
		});
		
		String url = product.getImageURL();
		if (url!=null) {
		  
			progressBar.setVisibility(View.VISIBLE);
        	progressBar.bringToFront();
        	
        	url = URLManager.getFile(url);
        	
        	AsyncSSLSocketMiddleware middle = Ion.getDefault(this).getHttpClient().getSSLSocketMiddleware();
        	middle.setConnectAllAddresses(true);
        	middle.setHostnameVerifier(SSLSocketFactory.ALLOW_ALL_HOSTNAME_VERIFIER);
        	middle.setTrustManagers(factory.getTrustManagers());
        	middle.setSSLContext(factory.getSSLContext());
        	
        	Ion.with(productImage).animateGif(status!=StatusType.STARTED).load(url).setCallback(new FutureCallback<ImageView>() {

				@Override
				public void onCompleted(Exception e, ImageView result) {
					
					progressBar.setVisibility(View.INVISIBLE);
					productImage.setEnabled(enabled);
				}
        		
        	});
        	
    	} else {
			//???
		}
		
		String desc = product.getDescription();
		if (desc!=null) {
			productDesc.setVisibility(View.VISIBLE);
			productDesc.setText(desc);
		}
		
		final String agreement = product.getAgreement();
		if (agreement!=null) {
			
			productCheck.setVisibility(View.VISIBLE);
			productCheck.setOnCheckedChangeListener(new OnCheckedChangeListener() {

				@Override
				public void onCheckedChanged(CompoundButton button, final boolean isChecked) {
					
					if (isChecked) {
						
						Resources res = getResources();
						
						AlertDialog.Builder adb = new AlertDialog.Builder(PromotionActivity.this);
						adb.setTitle(res.getString(R.string.promotion_agreement));
						adb.setMessage(agreement);
						adb.setPositiveButton(res.getString(R.string.promotion_agreement_yes), new DialogInterface.OnClickListener() {
							
							@Override
							public void onClick(DialogInterface dialog, int which) {
								
								updateButtonsState(product,isChecked);
							}
						});
						adb.setNegativeButton(res.getString(R.string.promotion_agreement_no), new DialogInterface.OnClickListener() {
							
							@Override
							public void onClick(DialogInterface dialog, int which) {
								
								updateButtonsState(product,false);
								productCheck.setChecked(false);
							}
						});
						adb.setCancelable(false);
						adb.show();
						
					} else {
						
						updateButtonsState(product,isChecked);
					}
					
				}
				
			});
		}
		setStateForAll(product,enabled);
	}
	
	@Override
	public void onPause() {
        super.onPause();
    }
	
	@Override
	public void onResume() {
		super.onResume();
	}

	@Override
	public void onDestroy() {
		messageHandler = null;
		super.onDestroy();
	}
	
	 private void getPromotion() {
			
			new Thread() {
				
				private void publishProgress(int loadingState, PromotionBean bean) {
					
					if (messageHandler!=null) {
					  Message.obtain(messageHandler,loadingState,bean).sendToTarget();
					}
				}
				
				@Override
				public void run() {
				   
					PromotionBean bean = null;
		  			boolean fromNetwork = false;
		  			
		  			try {
		  				  			
		  				Product product = getCurrentProduct();
		  				if (acceptOrReject!=null && product!=null) {
		  					
		  					String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
		  					String url = URLManager.getPromotion(token,product.getPromotionID(),acceptOrReject,regName,regPhone,regEmail,regBrokerage,regYield);
		  					
		  					bean = HttpManager.INSTANCE.getData(url,PromotionBean.class);
		  					fromNetwork = bean!=null;
		  				}
		  				
		  			} catch (Exception e) {
		  				//
		  			}
		  			
		  			if (bean==null) {
		  				publishProgress(LoadingState.NO_NETWORK_AND_DATA,null);
		  			} else {
		  				if (fromNetwork)
		  					publishProgress(LoadingState.SUCCESS,bean);
		  				else
		  					publishProgress(LoadingState.NO_NETWORK,bean);
		  			}
				}
				
			}.start();
		}
	 
	//@Override
    public void onResultReceive(Message data) {
  		
    	
		productNames.setEnabled(true);
		
      	Resources res = getResources();
  		
  		switch (data.what) {
	  		case LoadingState.NO_NETWORK_AND_DATA:
	  			Toast.makeText(this, res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
	  			break;
	  		case LoadingState.SUCCESS: {
	  			
	  			if (data.obj!=null && data.obj instanceof PromotionBean) {
	  				
	  				PromotionBean bean = (PromotionBean)data.obj;
	  				
	  				Product product = getCurrentProduct();
	  				if (bean!=null) {
	  					
	  					String status = bean.getResult().getStatus();
	  					
	  					AnalyticsCounter.eventScreen(titleParent,title,status,product.getName());
	  					
	  					product.setStatus(status);
		  				cancelTimer(product);
		  				product.setCountdown(0);
						updateCountdown(product,0);
						accepted = product.getStatusType()==Product.StatusType.ACCEPTED;
					    setStateForAll(product,false);
					    
					    String publisher = bean.getResult().getPublisher();
					    if (publisher!=null && !publisher.trim().equals("")) {
					    	
					    	//DistimoSDK.onBannerClick(publisher);
					    }
					    
	  				} else {
	  					setStateForAll(product,true);
	  				}
		  				
	  			}
	  			break;
	  		}
	  		case LoadingState.NO_NETWORK:
	  			Toast.makeText(this, res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
	  			break;
  		}
  	}

    @Override
    public void onBackPressed() {
    	
    	Bundle bundle = new Bundle();
		bundle.putBoolean(ACCEPTED,accepted);
		bundle.putSerializable(PRODUCTS,products);
		
		Intent intent = new Intent();
		intent.putExtras(bundle);
		setResult(RESULT_OK,intent);
		
		finish();
		
        return;
    }
}
