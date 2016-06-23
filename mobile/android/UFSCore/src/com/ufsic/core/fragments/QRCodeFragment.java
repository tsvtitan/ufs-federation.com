package com.ufsic.core.fragments;

/* tsv */

import java.util.ArrayList;

import com.ufsic.core.activities.PromotionActivity;
import com.ufsic.core.activities.RegistrationActivity;
import com.ufsic.core.beans.QRCodeBean;
import com.ufsic.core.beans.QRCodeBean.Promotion;
import com.ufsic.core.beans.QRCodeBean.Redirection;
import com.ufsic.core.beans.QRCodeBean.Promotion.Product;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.interfaces.IMenuFragment;
import com.ufsic.core.managers.HttpManager;
import com.ufsic.core.managers.LoadingState;
import com.ufsic.core.managers.URLManager;
import com.ufsic.core.utils.SharedPreferencesWrap;
import com.ufsic.core.views.CameraPreview;

import com.ufsic.core.R;
import android.support.v4.app.Fragment;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Vibrator;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.ProgressBar;
import android.widget.Toast;
import android.hardware.Camera;
import android.hardware.Camera.PreviewCallback;
import android.hardware.Camera.AutoFocusCallback;

import android.hardware.Camera.Size;

import net.sourceforge.zbar.ImageScanner;
import net.sourceforge.zbar.Image;
import net.sourceforge.zbar.Symbol;
import net.sourceforge.zbar.SymbolSet;
import net.sourceforge.zbar.Config;
import android.os.Message;
import android.content.res.Resources;
import android.content.Context;


public class QRCodeFragment extends Fragment implements IMenuFragment {

	public static final String TITLE = "title";
	
	private Camera mCamera;
    private CameraPreview mPreview;
    private Handler autoFocusHandler;
    private ProgressBar progressBar;

    private ImageScanner scanner;

    private String lastBarcode = "";
    private String title;
    
    private boolean previewing = false;
    private boolean hidden = false;
    
    private Handler messageHandler = null;

    static {
        System.loadLibrary("iconv");
    } 
	
	public static Fragment newInstance(String title) {
		
		QRCodeFragment fragment = new QRCodeFragment();
		
		Bundle args = new Bundle();
		args.putString(TITLE,title);
		fragment.setArguments(args);
		
		return fragment;
	}
	
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		messageHandler = new Handler(Looper.getMainLooper()) {
        	
        	@Override
            public void handleMessage(Message inputMessage) {
        		QRCodeFragment.this.onResultReceive(inputMessage);
    		}
        };
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		
		View root = inflater.inflate(R.layout.qrcode_fragment, container, false);
		
		setWidgets(root,getArguments());
		
		return root;
	}
	
	private void setWidgets(View root, Bundle bundle) {
		
		title = bundle.get(TITLE).toString();
		
		autoFocusHandler = new Handler();
        mCamera = getCameraInstance();
        progressBar = (ProgressBar)root.findViewById(R.id.qr_progress);
        
        scanner = new ImageScanner();
        scanner.setConfig(0, Config.X_DENSITY, 3);
        scanner.setConfig(0, Config.Y_DENSITY, 3);
        
        mPreview = new CameraPreview(getActivity(), mCamera, previewCb, autoFocusCB);
        FrameLayout preview = (FrameLayout)root.findViewById(R.id.qr_camera);
        preview.addView(mPreview);
 	}
	
	private void cameraStart() {
		
		if (!previewing) {
	        mCamera.startPreview();
	        mCamera.setPreviewCallback(previewCb);
	        previewing = true;
	        mCamera.autoFocus(autoFocusCB);
		}
	}
	
	private void cameraStop() {
		
		mCamera.setPreviewCallback(null);
		mCamera.stopPreview();
        previewing = false;
	}

	@Override
	public void onPause() {
        super.onPause();
        cameraStop();
    }
	
	@Override
	public void onResume() {
		super.onResume();
		if (hidden) cameraStop();
		else cameraStart();
	}

	@Override
	public void onDestroy() {
		releaseCamera();
		messageHandler = null;
		super.onDestroy();
	}
	
	public static Camera getCameraInstance(){
        Camera c = null;
        try {
            c = Camera.open();
        } catch (Exception e){
        }
        return c;
    }
    
    private void releaseCamera() {
        if (mCamera != null) {
            cameraStop();
            mCamera.release();
            mCamera = null;
        }
    }

    PreviewCallback previewCb = new PreviewCallback() {
        
    	public void onPreviewFrame(byte[] data, Camera camera) {
            
    		Camera.Parameters parameters = camera.getParameters();
            Size size = parameters.getPreviewSize();

            Image barcode = new Image(size.width, size.height, "Y800");
            barcode.setData(data);

            int result = scanner.scanImage(barcode);
            
            if (result != 0) {
            	
            	boolean exists = false;
                
                SymbolSet syms = scanner.getResults();
                for (Symbol sym : syms) {
                	
                	lastBarcode = sym.getData();
                	if (lastBarcode.length()>0) {
                		exists = true;
                	}
                	break;
                	
                }
                if (exists) {
                	AnalyticsCounter.eventScreen(title,lastBarcode,null,null);
                	progressBar.setVisibility(View.VISIBLE);
                	progressBar.bringToFront();
                	getQRCode();
                	((Vibrator)getActivity().getSystemService(Context.VIBRATOR_SERVICE)).vibrate(500);
                	cameraStop();
                }
            }
        }
    };
    
    private Runnable doAutoFocus = new Runnable() {
        public void run() {
            if (previewing)
                mCamera.autoFocus(autoFocusCB);
        }
    };
    
    AutoFocusCallback autoFocusCB = new AutoFocusCallback() {
	    public void onAutoFocus(boolean success, Camera camera) {
	        autoFocusHandler.postDelayed(doAutoFocus, 1000);
	    }
    };
    
    private void getQRCode() {
		
		new Thread() {
			
			private void publishProgress(int loadingState, QRCodeBean bean) {
				
				if (messageHandler!=null) {
				  Message.obtain(messageHandler,loadingState,bean).sendToTarget();
				}
			}
			
			@Override
			public void run() {
			   
				QRCodeBean bean = null;
	  			boolean fromNetwork = false;
	  			
	  			try {
	  				String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
	  				
	  				bean = HttpManager.INSTANCE.getData(URLManager.getQRCode(token,lastBarcode),QRCodeBean.class);
	  				fromNetwork = bean!=null;
	  				
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

    public void onResultReceive(Message data) {
  		
    	
    	progressBar.setVisibility(View.INVISIBLE);
    	
      	Intent intent = null;
  		
  		Resources res = getResources();
  		
  		switch (data.what) {
	  		case LoadingState.NO_NETWORK_AND_DATA:
	  			Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
	  			cameraStart();
	  			break;
	  		case LoadingState.SUCCESS: {
	  			
	  			if (data.obj!=null && data.obj instanceof QRCodeBean) {
	  				
	  				QRCodeBean bean = (QRCodeBean)data.obj;
	  				
	  				QRCodeBean.KindType kind = bean.getResult().getKindType();
	  				switch (kind) {
	  					case MESSAGE: {
	  						QRCodeBean.Message message = bean.getResult().getMessage();
	  						if (message!=null) {
	  							AnalyticsCounter.eventScreen(title,message.getText(),null,null);
	  						    Toast.makeText(getActivity(), message.getText(), Toast.LENGTH_SHORT).show();
	  						}
	  			  			cameraStart();
	  			  			break;
	  					}
		  				case REDIRECTION: {
		  					Redirection redirection = bean.getResult().getRedirection();
		  					if (redirection!=null) {
		  						AnalyticsCounter.eventScreen(title,redirection.getUrl(),null,null);
		  						intent = new Intent(Intent.ACTION_VIEW, Uri.parse(redirection.getUrl())); 
		  					} else {
		  						cameraStart();
		  					}
		  					break;
		  				}
		  				case PROMOTION: {
		  					Promotion promotion = bean.getResult().getPromotion();
		  					if (promotion!=null) {
		  						
		  						AnalyticsCounter.eventScreen(title,promotion.getTitle(),null,null);
		  						
		  						ArrayList<Product> products = promotion.getProducts();
		  						if (products!=null && !products.isEmpty()) {
		  							
		  							Boolean registered = promotion.getRegistered();
		  							if (registered) {
			  							intent = new Intent(getActivity(), PromotionActivity.class);
				  						intent.putExtra(PromotionActivity.PROMOTION,promotion);
				  						intent.putExtra(PromotionActivity.TITLE_PARENT,title);
		  							} else {
		  								intent = new Intent(getActivity(), RegistrationActivity.class);
				  						intent.putExtra(RegistrationActivity.PROMOTION,promotion);
				  						intent.putExtra(RegistrationActivity.TITLE,title);
		  							}
			  						
		  						} else {
		  							cameraStart();
		  						}
		  						
		  					} else {
		  						cameraStart();
		  					}
		  					break;
		  				}
		  				default: {
		  					cameraStart();
		  				}
	  				}
	  			}
	  			break;
	  		}
	  		case LoadingState.NO_NETWORK:
	  			Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
	  			cameraStart();
	  			break;
  		}
  		
  		if (intent != null) {
  			startActivity(intent);
  		}
  		
  		
  	}

	@Override
	public void show() {
		cameraStart();
		hidden = false;
	}

	@Override
	public void hide() {
		cameraStop();
		hidden = true;
	}
      
}
