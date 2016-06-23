package ru.ideast.ufs.activities;

/* tsv */

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.TimeZone;


import org.apache.http.conn.ssl.SSLSocketFactory;

import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.async.http.AsyncSSLSocketMiddleware;
import com.koushikdutta.ion.Ion;

import ru.ideast.ufs.R;

import ru.ideast.ufs.beans.PromotionBean;

import ru.ideast.ufs.beans.QRCodeBean.Promotion;

import ru.ideast.ufs.beans.QRCodeBean.Promotion.Product;
import ru.ideast.ufs.beans.QRCodeBean.Promotion.Product.StatusType;
import ru.ideast.ufs.loaders.FragmentLoader;
import ru.ideast.ufs.loaders.FragmentLoaderManager;
import ru.ideast.ufs.loaders.FragmentLoaderManager.Flag;
import ru.ideast.ufs.managers.HttpManager;
import ru.ideast.ufs.managers.LoadingState;
import ru.ideast.ufs.managers.TrustAllSSLSocketFactory;
import ru.ideast.ufs.managers.URLManager;
import ru.ideast.ufs.utils.SharedPreferencesWrap;
import ru.ideast.ufs.utils.ToolBox;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;

import android.content.res.Resources;
import android.graphics.Color;

import android.graphics.drawable.Drawable;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Message;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
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

import com.distimo.sdk.DistimoSDK;


public class PromotionActivity extends FragmentActivity implements FragmentLoaderManager.Callback<Message> {

	public static final String PROMOTION = "promotion";
	
	private Promotion promotion;
	private ArrayList<Product> products;
	private Spinner productNames;
	private ImageView productImage;
	private ProgressBar progressBar;
	private TextView productDesc;
	private CheckBox productCheck;
	private TextView productCountdown;
	private Button productAccept;
	//private Drawable defaultDrawableAccept;
	private Button productReject;
	//private Drawable defaultDrawableReject;
	private SimpleDateFormat countdownFormat;
	
	private Boolean acceptOrReject;
	private StatusType[] statusesAR = new StatusType[] {StatusType.ACCEPTED,StatusType.REJECTED};
	
	final private int redColor = Color.parseColor("#cc0000");
	final private int greenColor = Color.parseColor("#006e2e");
	
	private FragmentLoaderManager<Message> loaderManager;
	
	final private TrustAllSSLSocketFactory factory = (TrustAllSSLSocketFactory)TrustAllSSLSocketFactory.createTrustAllSSLSocketFactory();
	final private HashMap<Product,CountDownTimer> timers = new HashMap<Product,CountDownTimer>();

	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		setContentView(R.layout.promotion_activity);
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		
		Bundle bundle = getIntent().getExtras();
		if (bundle == null || !bundle.containsKey(PROMOTION)) {
			finish();
			return;
		}
		
		setWidgets(bundle);
	}

	private void setWidgets(Bundle bundle) {
		
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
		//defaultDrawableAccept = productAccept.getBackground();
		productReject = (Button)findViewById(R.id.pr_reject);		
		//defaultDrawableReject = productReject.getBackground();
		countdownFormat = new SimpleDateFormat("HH:mm:ss");
		countdownFormat.setTimeZone(TimeZone.getTimeZone("UTC"));
		
		promotion = (Promotion)bundle.getSerializable(PROMOTION);
		if (promotion!=null) {
			
			ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.pr_action_bar);
			actionBar.setSingleText(promotion.getTitle());
			actionBar.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					finish();
				}
			});
		
			List<String> list = new ArrayList<String>();
			
			products = promotion.getProducts();
			if (products!=null) {
				
				for (final Product product: products) {
					list.add(product.getName());
					
					Integer countdown = product.getCountdown();
					if (countdown!=null && countdown>0) {
						
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
									
									Integer total = product.getCountdown();
									if (total>0) {
										Integer ct = total - 1;
										product.setCountdown(ct);
									  
										if (product==getCurrentProduct()) {
										  
											updateCountdown(product,overall);
										}
									} else {
										this.cancel();
									}
								}
							}
							
						}.start();
						timers.put(product,timer);
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
			//productAccept.setBackground(defaultDrawableAccept);
			productAccept.setTextColor((enabled)?Color.BLACK:Color.GRAY);
		}
		
		productReject.setEnabled(enabled);
		if (status==StatusType.REJECTED) {
			
			productReject.setBackgroundResource(R.drawable.btn_red);
			productReject.setTextColor((enabled)?Color.BLACK:Color.LTGRAY);
		} else {
			//productReject.setBackground(defaultDrawableReject);
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
				loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
			}
		});
		
		productReject.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View view) {
				
				productNames.setEnabled(false);
				setStateForAll(product,false);
				acceptOrReject = false;
				loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
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
		loaderManager.onPause();
        super.onPause();
    }
	
	@Override
	public void onResume() {
		super.onResume();
		loaderManager.onResume(this);
	}

	@Override
	public void onDestroy() {
		loaderManager.onDestroy();
		super.onDestroy();
	}
	
	@Override
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
		  				product.setStatus(bean.getResult().getStatus());
		  				cancelTimer(product);
		  				product.setCountdown(0);
						updateCountdown(product,0);
					    setStateForAll(product,false);
					    
					    
					    String publisher = bean.getResult().getPublisher();
					    if (publisher!=null && !publisher.trim().equals("")) {
					    	DistimoSDK.onBannerClick(publisher);
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
  	public FragmentLoader<Message> onCreateLoader(Bundle params) {
  		return new PromotionLoader(this);
  	}
      
    private class PromotionLoader extends FragmentLoader<Message> {

  		public PromotionLoader(Context context) {
  			super(context);
  		}

  		@Override
  		public void runInBackground(boolean firstRun) {
  			
  			PromotionBean bean = null;
  			boolean fromNetwork = false;
  			
  			try {
  				  			
  				Product product = getCurrentProduct();
  				if (acceptOrReject!=null && product!=null) {
  					
  					String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
  					
  					bean = HttpManager.INSTANCE.getData(URLManager.getPromotion(token,product.getPromotionID(),acceptOrReject.toString()),PromotionBean.class);
  					fromNetwork = bean!=null;
  				}
  				
  			} catch (Exception e) {
  				//
  			}
  			
  			if (bean==null) {
  				publishProgress(Message.obtain(null, LoadingState.NO_NETWORK_AND_DATA));
  			} else {
  				if (fromNetwork)
  					publishProgress(Message.obtain(null, LoadingState.SUCCESS, bean));
  				else
  					publishProgress(Message.obtain(null, LoadingState.NO_NETWORK, bean));
  			}
  		}
      }
}
