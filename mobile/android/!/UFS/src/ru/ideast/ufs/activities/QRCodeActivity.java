package ru.ideast.ufs.activities;

/* tsv */

import java.util.ArrayList;

import ru.ideast.ufs.R;
import ru.ideast.ufs.views.CameraPreview;
import ru.ideast.ufs.widgets.ActionBarUfs;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Vibrator;
import android.support.v4.app.FragmentActivity;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.FrameLayout;
import android.widget.ProgressBar;
import android.widget.Toast;
import android.hardware.Camera;
import android.hardware.Camera.PreviewCallback;
import android.hardware.Camera.AutoFocusCallback;
import android.hardware.Camera.Parameters;
import android.hardware.Camera.Size;

/* Import ZBar Class files */
import net.sourceforge.zbar.ImageScanner;
import net.sourceforge.zbar.Image;
import net.sourceforge.zbar.Symbol;
import net.sourceforge.zbar.SymbolSet;
import net.sourceforge.zbar.Config;
import ru.ideast.ufs.loaders.FragmentLoaderManager;
import ru.ideast.ufs.loaders.FragmentLoader;
import ru.ideast.ufs.loaders.FragmentLoaderManager.Flag;
import ru.ideast.ufs.managers.LoadingState;
import android.os.Message;
import android.content.res.Resources;
import android.content.Context;
import ru.ideast.ufs.beans.BaseBean;
import ru.ideast.ufs.beans.QRCodeBean;
import ru.ideast.ufs.beans.QRCodeBean.Promotion;
import ru.ideast.ufs.beans.QRCodeBean.Promotion.Product;
import ru.ideast.ufs.beans.QRCodeBean.Redirection;
import ru.ideast.ufs.utils.SharedPreferencesWrap;
import ru.ideast.ufs.managers.HttpManager;
import ru.ideast.ufs.managers.URLManager;
import ru.ideast.ufs.exceptions.CorruptedDataException;
import ru.ideast.ufs.exceptions.NoNetworkException;

public class QRCodeActivity extends FragmentActivity implements FragmentLoaderManager.Callback<Message> {

	public static final String TITLE = "title";
	public static final String SUBCATEGORY_ID = "subcategoryId";
	
	private Camera mCamera;
    private CameraPreview mPreview;
    private Handler autoFocusHandler;
    private ProgressBar progressBar;

    private ImageScanner scanner;

    private String lastBarcode = "";
    
    private boolean previewing = true;
    
    private FragmentLoaderManager<Message> loaderManager;


    static {
        System.loadLibrary("iconv");
    } 
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.qrcode_activity);
		
		loaderManager = new FragmentLoaderManager<Message>(this);
		
		Bundle bundle = getIntent().getExtras();
		if (bundle == null || !bundle.containsKey(TITLE)) {
			finish();
			return;
		}
		
		setWidgets(bundle);
	}
	
	private void setWidgets(Bundle bundle) {
		
		ActionBarUfs actionBar = (ActionBarUfs) findViewById(R.id.qr_action_bar);
		actionBar.setSingleText(bundle.get(TITLE).toString());
		actionBar.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
			}
		});
		
		
		setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);

        autoFocusHandler = new Handler();
        mCamera = getCameraInstance();
        progressBar = (ProgressBar)findViewById(R.id.qr_progress);
        
        /* Instance barcode scanner */
        scanner = new ImageScanner();
        scanner.setConfig(0, Config.X_DENSITY, 3);
        scanner.setConfig(0, Config.Y_DENSITY, 3);
        
        mPreview = new CameraPreview(this, mCamera, previewCb, autoFocusCB);
        FrameLayout preview = (FrameLayout)findViewById(R.id.qr_camera);
        preview.addView(mPreview);
        
        cameraStart();
	}
	
	private void cameraStart() {
		
        mCamera.setPreviewCallback(previewCb);
        mCamera.startPreview();
        previewing = true;
        mCamera.autoFocus(autoFocusCB);
	}
	
	private void cameraStop() {
		
		previewing = false;
        mCamera.setPreviewCallback(null);
        mCamera.stopPreview();
	}

	@Override
	public void onPause() {
		loaderManager.onPause();
        super.onPause();
        releaseCamera();
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
	
	/** A safe way to get an instance of the Camera object. */
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
            /*previewing = false;
            mCamera.setPreviewCallback(null);*/
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
                	progressBar.setVisibility(View.VISIBLE);
                	progressBar.bringToFront();
                	loaderManager.run(true, Flag.RUN_IF_NOT_EXIST);
                	((Vibrator)getSystemService(VIBRATOR_SERVICE)).vibrate(500);
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
    
    // Mimic continuous auto-focusing
    AutoFocusCallback autoFocusCB = new AutoFocusCallback() {
	    public void onAutoFocus(boolean success, Camera camera) {
	        autoFocusHandler.postDelayed(doAutoFocus, 1000);
	    }
    };
    
    
    @Override
    public void onResultReceive(Message data) {
  		
    	
    	progressBar.setVisibility(View.INVISIBLE);
    	
      	Intent intent = null;
  		
  		Resources res = getResources();
  		
  		switch (data.what) {
	  		case LoadingState.NO_NETWORK_AND_DATA:
	  			Toast.makeText(this, res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
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
	  						  Toast.makeText(this, message.getText(), Toast.LENGTH_SHORT).show();
	  						}
	  			  			cameraStart();
	  			  			break;
	  					}
		  				case REDIRECTION: {
		  					Redirection redirection = bean.getResult().getRedirection();
		  					if (redirection!=null) {
		  						intent = new Intent(Intent.ACTION_VIEW, Uri.parse(redirection.getUrl())); 
		  					} else {
		  						cameraStart();
		  					}
		  					break;
		  				}
		  				case PROMOTION: {
		  					Promotion promotion = bean.getResult().getPromotion();
		  					if (promotion!=null) {
		  						
		  						ArrayList<Product> products = promotion.getProducts();
		  						if (products!=null && !products.isEmpty()) {
		  							
		  							intent = new Intent(this, PromotionActivity.class);
			  						intent.putExtra(PromotionActivity.PROMOTION,promotion);
			  						
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
	  			Toast.makeText(this, res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
	  			cameraStart();
	  			break;
  		}
  		
  		if (intent != null) {
  			startActivity(intent);
  			finish();
  		}
  		
  		
  	}
      
    @Override
  	public FragmentLoader<Message> onCreateLoader(Bundle params) {
  		return new QRCodeLoader(this);
  	}
      
    private class QRCodeLoader extends FragmentLoader<Message> {

  		public QRCodeLoader(Context context) {
  			super(context);
  		}

  		@Override
  		public void runInBackground(boolean firstRun) {
  			
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
