package com.ufsic.core.fragments;

/* tsv */

import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.HashSet;
import java.util.List;
import java.util.Set;

import com.nhaarman.listviewanimations.ArrayAdapter;
import com.nhaarman.listviewanimations.appearance.simple.AlphaInAnimationAdapter;
import com.nhaarman.listviewanimations.itemmanipulation.DynamicListView;
import com.nhaarman.listviewanimations.itemmanipulation.swipedismiss.OnDismissCallback;

import com.nhaarman.listviewanimations.itemmanipulation.swipedismiss.undo.TimedUndoAdapter;
import com.nhaarman.listviewanimations.itemmanipulation.swipedismiss.undo.UndoAdapter;
import com.ufsic.core.beans.KeywordsBean;
import com.ufsic.core.beans.ValidationBean;
import com.ufsic.core.db.DatabaseManager;
import com.ufsic.core.exceptions.CorruptedDataException;
import com.ufsic.core.exceptions.NoNetworkException;
import com.ufsic.core.interfaces.ISubscriptionChanger;
import com.ufsic.core.managers.HttpManager;
import com.ufsic.core.managers.LoadingState;
import com.ufsic.core.managers.URLManager;
import com.ufsic.core.utils.SharedPreferencesWrap;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.res.Resources;

import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;

import android.support.annotation.NonNull;
import android.support.v4.app.Fragment;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;

import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.view.inputmethod.InputMethodManager;


import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.LinearLayout.LayoutParams;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

public class NewKeywordsFragment extends Fragment {

    private static final int ANIMATION_DELAY = 250;
    private static final int SWIPE_DELAY = 2500;
    public static final String KEYWORDS = "keywords";
    
	private String[] keywords = null;
	private View root = null;
	private LinearLayout parentLayout = null;
	private DynamicListView listView = null;
	private ListViewAdapter adapter = null;
	private ListViewSwipeUndoAdapter swipeUndoAdapter = null;
	private LinearLayout subscribeLayout = null;
	private LinearLayout emailLayout = null;
	private OnCheckedChangeListener switchChangeListener = null;
	private RadioButton onlyApp = null;
	private RadioButton appAndEmail = null;
	private EditText email = null;
	private Button emailValidate = null;
	private ProgressBar emailProgress = null;
	private Button subscribe = null;
	private boolean emailValidated = false;
	private Handler messageHandler = null;
	private String sharedEmail = null;
	private TextView emptyText = null;
	private ISubscriptionChanger changer = null;
	private ProgressDialog progress = null;
	
	
	private enum MessageType {
		VALIDATION, SUBSCRIPTION;
	}
	
	private class MessageObject {
		
		private MessageType type;
		private Object object;
		
		MessageObject(MessageType type, Object object) {
			this.type = type;
			this.object = object;
		}
		
		private MessageType getType() {
			return type;
		}
		
		private Object getObject() {
			return object;
		}
	}
	
	public static NewKeywordsFragment newInstance(String[] keywords, ISubscriptionChanger changer) {
		
		NewKeywordsFragment f = new NewKeywordsFragment();
		f.changer = changer;
		
		Bundle b = new Bundle();
		b.putStringArray(KEYWORDS,keywords);
		f.setArguments(b);
		
		return f;
	}
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		
		Bundle args = getArguments();
		if (args != null && args.containsKey(KEYWORDS) ) {
			keywords = args.getStringArray(KEYWORDS);
		}	
        
		messageHandler = new Handler(Looper.getMainLooper()) {
        	
        	@Override
            public void handleMessage(Message inputMessage) {
        		NewKeywordsFragment.this.handleMessage(inputMessage);
    		}
        };
	}
	
	private void hideKeyboard(View view) {
		view.clearFocus();
        InputMethodManager inputMethodManager = (InputMethodManager) getActivity().getSystemService(Activity.INPUT_METHOD_SERVICE);
        inputMethodManager.hideSoftInputFromWindow(view.getWindowToken(), 0);
    }
	
    private ArrayList<KeywordsBean.Result> loadKeywords() {
		
		ArrayList<KeywordsBean.Result> ret;
		
		try {
			
			ret = DatabaseManager.INSTANCE.getList(KeywordsBean.Result.class);
			
		} catch (Exception e) {
			ret = null;
		}
		return ret;
	}
    
    private void hideProgress() {
		
		if (progress!=null) {
			progress.dismiss();
			progress = null;
		}
	}
	
	private void showProgress() {
		
		hideProgress();
		Resources res = getResources();
		progress = ProgressDialog.show(getActivity(),
				                       res.getString(R.string.please_wait),
				                       res.getString(R.string.subscribing));
	}
	
    public void handleMessage(Message data) {
		
		final Resources res = getResources();
		
		MessageObject obj = (MessageObject) data.obj;
		MessageType messageType = obj.getType();
		
		if (obj.getType()==MessageType.VALIDATION) { 
			
			subscribeLayout.setEnabled(true);
			emailProgress.setVisibility(View.INVISIBLE);
			emailValidate.setEnabled(true);
			
		} else if (obj.getType()==MessageType.SUBSCRIPTION) {
			
			parentLayout.setEnabled(true);
			subscribe.setEnabled(true);
		}
		
		switch (data.what) {
		
			case LoadingState.NO_NETWORK_AND_DATA: {
				Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
				break;
			}
			case LoadingState.SUCCESS: {
				
				Object messageObj =  obj.getObject();
				
				if (messageType==MessageType.VALIDATION && messageObj!=null && messageObj instanceof ValidationBean.Result) { 
					
					final ValidationBean.Result result = (ValidationBean.Result) messageObj;
					
					AlertDialog.Builder builder = new AlertDialog.Builder(this.getActivity());
					builder.setTitle(R.string.input_email_code);
	
					final EditText input = new EditText(this.getActivity());
					
					LayoutParams params = new LayoutParams(LayoutParams.WRAP_CONTENT,LayoutParams.WRAP_CONTENT);
					params.setMargins(10, 5, 10, 5);
					
					input.setLayoutParams(params);
					input.setGravity(Gravity.CENTER);
					input.setInputType(InputType.TYPE_CLASS_NUMBER);
					
					builder.setView(input);
	
					builder.setPositiveButton(res.getString(R.string.button_ok), new DialogInterface.OnClickListener() { 
					    @Override
					    public void onClick(DialogInterface dialog, int which) {
					    	// see below
					    }
					});
					
					builder.setNegativeButton(res.getString(R.string.button_cancel), new DialogInterface.OnClickListener() {
					    @Override
					    public void onClick(DialogInterface dialog, int which) {
					        dialog.cancel();
					    }
					});
					
					final AlertDialog dialog = builder.create();
					dialog.show();
					dialog.getButton(AlertDialog.BUTTON_POSITIVE).setOnClickListener(new OnClickListener() {
						
						private Integer retryCount = 3;
						
						@Override
						public void onClick(View v) {
							
							String s = input.getText().toString();
					        if (!ToolBox.isEmpty(s) && s.equals(result.getCode())) {
					        	
					        	sharedEmail = email.getText().toString();
					        	SharedPreferencesWrap.INSTANCE.putString(SharedPreferencesWrap.EMAIL,sharedEmail);
					        	emailValidated = true;
					        	emailValidate.setEnabled(!emailValidated);
					        	updateStates();
					        	dialog.dismiss();
					        	hideKeyboard(email);
					        	
					        } else {
					        	
					        	retryCount--;
					        	if (retryCount>0) {
					        		Toast.makeText(getActivity(), res.getString(R.string.input_email_code_wrong), Toast.LENGTH_SHORT).show();
					        	} else {
					        		dialog.dismiss();
					        		hideKeyboard(email);
					        	}
					        	
					        }
						}
					});
					
				} else if (messageType==MessageType.SUBSCRIPTION) {
					
					
					ArrayList<KeywordsBean.Result> list = loadKeywords();
					if (list!=null) {
						for (int i=adapter.getCount()-1; i>=0; i--) {
							
							String keyword = adapter.getItem(i);
							boolean exists = false;
							
							for (KeywordsBean.Result result: list) {
								
								if (keyword.equalsIgnoreCase(result.getKeyword())) {
									exists = true;
									break;
								}
							}
							if (exists) {
								adapter.remove(i);
							}
						}
					}
					
					if (changer!=null) {
						changer.reload();
					}
					updateStates();
					
				}
				
				break;
			}
			case LoadingState.NO_NETWORK: {
				break;
			}
		}
		
		hideProgress();
    }
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		
		root = inflater.inflate(R.layout.new_keywords_fragment, container, false);
		
		parentLayout = (LinearLayout) root.findViewById(R.id.nkf_layout_keywords);
		listView = (DynamicListView) root.findViewById(R.id.nkf_listview);
				
		adapter = new ListViewAdapter(this);
		
		swipeUndoAdapter = new ListViewSwipeUndoAdapter(this.getActivity());
		swipeUndoAdapter.setTimeoutMs(SWIPE_DELAY);
		
		AlphaInAnimationAdapter animAdapter = new AlphaInAnimationAdapter(swipeUndoAdapter);
		animAdapter.setAbsListView(listView);
		
		assert animAdapter.getViewAnimator() != null;
        animAdapter.getViewAnimator().setInitialDelayMillis(ANIMATION_DELAY);
        listView.setAdapter(animAdapter);
        
        subscribeLayout = (LinearLayout) root.findViewById(R.id.nkf_subscribe_layout);
        emailLayout = (LinearLayout) root.findViewById(R.id.nkf_email_layout);
        
        switchChangeListener = new OnCheckedChangeListener() {
        	
        	@Override
    		public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
    			
    			if (isChecked) {
    				showEmailLayout(buttonView==appAndEmail);
    			}
    		}
        };
        
        onlyApp = (RadioButton) root.findViewById(R.id.nkf_only_app);
        onlyApp.setOnCheckedChangeListener(switchChangeListener);
        
        appAndEmail = (RadioButton) root.findViewById(R.id.nkf_app_and_email);
        appAndEmail.setOnCheckedChangeListener(switchChangeListener);

        sharedEmail = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.EMAIL);
        emailValidated = !ToolBox.isEmpty(sharedEmail);
        
        email = (EditText) root.findViewById(R.id.nkf_email);
        email.setText(sharedEmail); 
        email.addTextChangedListener(new TextWatcher() {

			@Override
			public void beforeTextChanged(CharSequence s, int start, int count, int after) { }

			@Override
			public void onTextChanged(CharSequence s, int start, int before,int count) { }

			@Override
			public void afterTextChanged(Editable s) { 
				
				String email = s.toString();
				boolean enabled = false; 
				if (!ToolBox.isEmpty(sharedEmail)) {
					enabled = !sharedEmail.equalsIgnoreCase(email) && ToolBox.isEmail(email);
				} else {
					enabled = ToolBox.isEmail(email);
				}
				emailValidate.setEnabled(enabled);
				emailValidated = !ToolBox.isEmpty(email) && email.equalsIgnoreCase(sharedEmail);
				updateStates();
			}
        	
        });
        
        emailProgress = (ProgressBar) root.findViewById(R.id.nkf_email_progress);
        
        emailValidate = (Button) root.findViewById(R.id.nkf_email_validate);
        emailValidate.setEnabled(!emailValidated);
        emailValidate.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				
				new Thread() {
					
					private void publishProgress(int loadingState, ValidationBean.Result result) {
						
						if (messageHandler!=null) {
						  Message.obtain(messageHandler,loadingState,new MessageObject(MessageType.VALIDATION,result)).sendToTarget();
						}
					}
					
					@Override
					public void run() {
					   
						ValidationBean.Result result = null;
						boolean fromNetwork = false;
						
						try {
							String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
							
							String url = URLManager.getValidation(token,getEmail());
							ValidationBean bean = HttpManager.INSTANCE.getData(url,ValidationBean.class);
							result = bean.getResult();
							
							fromNetwork = true;
							
						} catch (NoNetworkException e) {
							//
						} catch (CorruptedDataException e) {
							//
						}
						
						if(result == null) {
							publishProgress(LoadingState.NO_NETWORK_AND_DATA,null);
						}
						else {
							if(fromNetwork)
								publishProgress(LoadingState.SUCCESS,result);
							else
								publishProgress(LoadingState.NO_NETWORK,result);
						}

					}
					
				}.start();
				
				emailProgress.setVisibility(View.VISIBLE);
				emailValidate.setEnabled(false);
				subscribeLayout.setEnabled(false);
				
			}
		});
        
        subscribe = (Button) root.findViewById(R.id.nkf_subscribe);
        subscribe.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				
				new Thread() {
					
					private void publishProgress(int loadingState) {
						
						if (messageHandler!=null) {
						  Message.obtain(messageHandler,loadingState,new MessageObject(MessageType.SUBSCRIPTION,null)).sendToTarget();
						}
					}
					
					@Override
					public void run() {
						
						ArrayList<KeywordsBean.Result> list = null;
						boolean fromNetwork = false;
						
						try {
							String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
							
							String url = URLManager.getKeywordsNew(token,getKeywords(),getKinds(),getEmail());
							KeywordsBean bean = HttpManager.INSTANCE.getData(url,KeywordsBean.class);
							list = bean.getResult();
							
							DatabaseManager.INSTANCE.clearTable(KeywordsBean.Result.class);
							DatabaseManager.INSTANCE.AddList(list,KeywordsBean.Result.class);
							
							fromNetwork = true;
							
						} catch (NoNetworkException e) {
							list = DatabaseManager.INSTANCE.getList(KeywordsBean.Result.class);
						} catch (CorruptedDataException e) {
							//
						}
						
						if(list == null) {
							publishProgress(LoadingState.NO_NETWORK_AND_DATA);
						}
						else {
							if(fromNetwork)
								publishProgress(LoadingState.SUCCESS);
							else
								publishProgress(LoadingState.NO_NETWORK);
						}
					}
					
					
				}.start();
				
				subscribe.setEnabled(false);
				parentLayout.setEnabled(false);
				showProgress();
			}
        });
        
        emptyText = (TextView) root.findViewById(R.id.nkf_empty);
        
        updateStates();
        
		return root;
	}
	
	private synchronized List<String> getKeywords() {
		
		List<String> list = new ArrayList<String>();
		if (adapter!=null) {
			for (int i=0; i<adapter.getCount(); i++) {
				list.add(adapter.getItem(i));
			}
		}
		return list;
	}
	
	private synchronized Set<String> getKinds() {
		
		Set<String> set = new HashSet<String>();
		if (onlyApp!=null && appAndEmail!=null) {
			if (onlyApp.isChecked()) { 
				set.add(KeywordsBean.KIND_APP);
			} else if (appAndEmail.isChecked()) {
				set.add(KeywordsBean.KIND_APP);
				set.add(KeywordsBean.KIND_EMAIL);
			}
		}
		return set;
	}
	
	private synchronized String getEmail() {
		
		String ret = null;
		if (email!=null && appAndEmail!=null) {
			if (appAndEmail.isChecked()) {
				ret = email.getText().toString();
			}
		}
		return ret;
	}
	
	private void updateStates() {
		
		Resources res = getResources();
		
		if (email!=null) {
			email.setTextColor(emailValidated?res.getColor(R.color.Green):res.getColor(R.color.Red));
		}
		
		boolean enabled = adapter.getCount()>0;
		
		if (subscribe!=null) {
			
			boolean flag = enabled;
			if (flag) {
				flag = onlyApp.isChecked()?true:emailValidated;
			}
			subscribe.setEnabled(flag);
		}
		
		parentLayout.setVisibility(enabled?View.VISIBLE:View.GONE);
		
		emptyText.setVisibility(enabled?View.GONE:View.VISIBLE);
		
	}
	
	private void showEmailLayout(boolean visible) {
		
		if (visible) {
			emailLayout.setVisibility(View.VISIBLE);
			appAndEmail.setChecked(true);
			onlyApp.setChecked(false);
		} else {
			emailLayout.setVisibility(View.GONE);
			appAndEmail.setChecked(false);
			onlyApp.setChecked(true);
		}
		updateStates();
	}
	
	private class ListViewSwipeUndoAdapter extends TimedUndoAdapter {

		public ListViewSwipeUndoAdapter(Context context) {
			
			super(adapter, context, new OnDismissCallback() {
				
				public void onDismiss(@NonNull ViewGroup listView, @NonNull int[] reverseSortedPositions) {
					
					if (adapter!=null) {
						for (int i=0; i<reverseSortedPositions.length; i++) {
							int pos = reverseSortedPositions[i];
							adapter.remove(pos);
						}
						updateStates();
					}
				}
			});
		}
	}
	
	private static class ListViewAdapter extends ArrayAdapter<String> implements UndoAdapter {

        private final NewKeywordsFragment fragment;

        ListViewAdapter(final NewKeywordsFragment fragment) {
        	this.fragment = fragment;
            if (fragment.keywords!=null) {
	            for (String s: fragment.keywords) {
	                add(s);
	            }
            }
        }

        @Override
        public long getItemId(final int position) {
            return getItem(position).hashCode();
        }

        @Override
        public boolean hasStableIds() {
            return true;
        }
        
        public Integer getPosition(String text) {
        	Integer ret = null;
        	List<String> list = getItems();
        	if (list!=null) {
        		ret = list.indexOf(text);
        	}
        	return ret;
        }

        @Override
        public View getView(final int position, final View convertView, final ViewGroup parent) {

        	View view = convertView;
            if (view == null) {
                view = LayoutInflater.from(fragment.getActivity()).inflate(R.layout.list_row_dynamiclistview, parent, false);
            }
            
            final String text = getItem(position);
            
            ((TextView) view.findViewById(R.id.list_row_dynamiclistview_textview)).setText(text);
            
            ImageView email = (ImageView) view.findViewById(R.id.list_row_dynamiclistview_email);
            email.setVisibility(View.GONE);
            
            ImageView trash = (ImageView) view.findViewById(R.id.list_row_dynamiclistview_del);
            trash.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					
					fragment.swipeUndoAdapter.dismiss(position);
				}
			});
            
            return view;
        }

        @NonNull
        @Override
        public View getUndoView(final int position, final View convertView, @NonNull final ViewGroup parent) {
            
        	View view = convertView;
            if (view == null) {
                view = LayoutInflater.from(fragment.getActivity()).inflate(R.layout.undo_row, parent, false);
            }
            return view;
        }

        @NonNull
        @Override
        public View getUndoClickView(@NonNull final View view) {
        	
        	return view.findViewById(R.id.undo_row_undobutton);
        }
    }
	
	@Override
	public void onDestroy() {
		
		hideProgress();
		messageHandler = null;
		adapter = null;
		onlyApp = null;
		appAndEmail = null;
		email = null;
		super.onDestroy();
		
	}
}
