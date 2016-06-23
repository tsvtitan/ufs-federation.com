package com.ufsic.core.fragments;

/* tsv */

import java.util.ArrayList;

import java.util.Collections;
import java.util.Comparator;
import java.util.List;
import java.util.Vector;


import com.nhaarman.listviewanimations.ArrayAdapter;
import com.nhaarman.listviewanimations.appearance.simple.AlphaInAnimationAdapter;
import com.nhaarman.listviewanimations.itemmanipulation.DynamicListView;
import com.nhaarman.listviewanimations.itemmanipulation.swipedismiss.OnDismissCallback;
import com.nhaarman.listviewanimations.itemmanipulation.swipedismiss.undo.SimpleSwipeUndoAdapter;


import com.nhaarman.listviewanimations.itemmanipulation.swipedismiss.undo.UndoAdapter;
import com.ufsic.core.activities.MainActivity;
import com.ufsic.core.activities.SplashActivity;
import com.ufsic.core.beans.KeywordsBean;
import com.ufsic.core.db.DatabaseManager;
import com.ufsic.core.exceptions.CorruptedDataException;
import com.ufsic.core.exceptions.NoNetworkException;
import com.ufsic.core.managers.HttpManager;
import com.ufsic.core.managers.LoadingState;
import com.ufsic.core.managers.URLManager;
import com.ufsic.core.utils.SharedPreferencesWrap;

import com.ufsic.core.R;



import android.app.ProgressDialog;
import android.content.Context;

import android.content.res.Resources;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;
import android.support.annotation.NonNull;

import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;

import android.widget.Toast;
import android.widget.TextView;

public class AllKeywordsFragment extends Fragment  {

	private static final int ANIMATION_DELAY = 250;
	private static final int SWIPE_DELAY = 2500;
	
	private View root = null;
	private ArrayList<KeywordsBean.Result> keywords = null;
	private DynamicListView listView = null;
	private ListViewAdapter adapter = null;
	private ListViewCountDownTimer swipeTimer = null;
	private ListViewSwipeUndoAdapter swipeUndoAdapter = null;
	private Vector<String> candidates = new Vector<String>();
	private Handler messageHandler = null;
	private TextView emptyText = null;
	private ProgressDialog progress = null;
	
	public static AllKeywordsFragment newInstance() {
		
		AllKeywordsFragment f = new AllKeywordsFragment();
		
		return f;
	}
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		
		super.onCreate(savedInstanceState);
		
        swipeTimer = new ListViewCountDownTimer(SWIPE_DELAY,1000);
        
        messageHandler = new Handler(Looper.getMainLooper()) {
        	
        	@Override
            public void handleMessage(Message inputMessage) {
    			AllKeywordsFragment.this.handleMessage(inputMessage);
    		}
        };
	}
	
	private ArrayList<KeywordsBean.Result> loadKeywords() {
		
		ArrayList<KeywordsBean.Result> ret;
		
		try {
			
			ret = DatabaseManager.INSTANCE.getList(KeywordsBean.Result.class);
			
			Collections.sort(ret, new Comparator<KeywordsBean.Result>() {

				@Override
				public int compare(KeywordsBean.Result lhs, KeywordsBean.Result rhs) {
					
					return lhs.getKeyword().compareTo(rhs.getKeyword());
				}
				
			});
			
		} catch (Exception e) {
			ret = null;
		}
		return ret;
	}
	
	@Override
	public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
		
		root = inflater.inflate(R.layout.all_keywords_fragment, container, false);
		
		keywords = loadKeywords();
		
		listView = (DynamicListView) root.findViewById(R.id.akf_listview);
		
		adapter = new ListViewAdapter(this);
		
		swipeUndoAdapter = new ListViewSwipeUndoAdapter(adapter, this.getActivity());
		
		AlphaInAnimationAdapter animAdapter = new AlphaInAnimationAdapter(swipeUndoAdapter);
		animAdapter.setAbsListView(listView);
		
		assert animAdapter.getViewAnimator() != null;
        animAdapter.getViewAnimator().setInitialDelayMillis(ANIMATION_DELAY);
        listView.setAdapter(animAdapter);
        
        emptyText = (TextView) root.findViewById(R.id.akf_empty);
        
        updateEmptyText();
        
		return root;
	}
	
	private void updateEmptyText() {
		
		if (emptyText!=null) {
			emptyText.setVisibility(adapter.getCount()>0?View.GONE:View.VISIBLE);
		}
	}
	
	public void reloadListView() {
		
		keywords = loadKeywords();
		adapter.reload(keywords);
		updateEmptyText();
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
				                       res.getString(R.string.elements_removing));
	}
	
	private KeywordsBean.Result findResult(String keyword) {
		
		KeywordsBean.Result ret = null;
		if (keywords!=null) {
			for (KeywordsBean.Result result: keywords) {
				if (keyword.equalsIgnoreCase(result.getKeyword())) {
					ret = result;
					break;
				}
			}
		}
		return ret;
	}
	
	private class ListViewSwipeUndoAdapter extends SimpleSwipeUndoAdapter {

		public ListViewSwipeUndoAdapter(BaseAdapter adapter, Context context) {
			
			super(adapter, context, new OnDismissCallback() {

				@Override
				public void onDismiss(@NonNull ViewGroup listView,	@NonNull int[] reverseSortedPositions) {
					// dumb
				}
				
			});
		}
		
		@Override
	    public void onUndo(@NonNull final View view, final int position) {
	        
			super.onUndo(view, position);
			swipeTimer.tryCancel();
			candidates.remove(adapter.getItem(position));
			swipeTimer.tryStart();
	    }
		
		@Override
	    public void onDismiss(@NonNull final ViewGroup viewGroup, @NonNull final int[] reverseSortedPositions) {
			
			super.onDismiss(listView, reverseSortedPositions);
			
			if (adapter!=null) {
				for (int i=0; i<reverseSortedPositions.length; i++) {
					int pos = reverseSortedPositions[i];
					String word = adapter.getItem(pos);
					candidates.remove(word);
					adapter.remove(word);
				}
				updateEmptyText();
			}
	    }
		
	}
	
	public void handleMessage(Message data) {
		
		Resources res = getResources();
		
		switch (data.what) {
			case LoadingState.NO_NETWORK_AND_DATA:
				
				Toast.makeText(getActivity(), res.getString(R.string.download_no_network_and_data), Toast.LENGTH_SHORT).show();
				break;
				
			case LoadingState.SUCCESS: {
				
				ArrayList<String> temp = new ArrayList<>();
				
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
						if (!exists) {
							temp.add(keyword);
						}
					}
				}
				
				listView.enableSimpleSwipeUndo();
				try {
	                for (int i=candidates.size()-1; i>=0; i--) {
						
						String keyword = candidates.get(i);
						Integer position = adapter.getPosition(keyword);
						if (position!=null) {
							if (temp.contains(keyword)) {
							    listView.fling(position);
							} else {
								View v = listView.getChildAt(position);
								listView.undo(v);
							}
						}
	                }
				} finally {
					listView.disableSwipeToDismiss();
				}
				
				for (int i=candidates.size()-1; i>=0; i--) {
					
					String keyword = candidates.get(i);
					Integer position = adapter.getPosition(keyword);
					if (position!=null) {
					    if (temp.contains(keyword)) {
							swipeUndoAdapter.dismiss(position);
						}
					}
					candidates.remove(i);
				}	
				
				updateEmptyText();
				
				break;
			}
			
			case LoadingState.NO_NETWORK: {

				Toast.makeText(getActivity(), res.getString(R.string.download_no_network), Toast.LENGTH_SHORT).show();
				
				listView.enableSimpleSwipeUndo();
				try {
					for (int i=candidates.size()-1; i>=0; i--) {
						
						String word = candidates.get(i);
						Integer position = adapter.getPosition(word);
						if (position!=null) {
							View v = listView.getChildAt(position);
							listView.undo(v);
						}
						candidates.remove(i);
	                }
				} finally {
					listView.disableSwipeToDismiss();
				}
				
				updateEmptyText();
				
				break;
			}
		}
		
		hideProgress();
	}
	
	private class ListViewCountDownTimer extends CountDownTimer {

		public ListViewCountDownTimer(long millisInFuture, long countDownInterval) {
	
			super(millisInFuture, countDownInterval);
		}

		@Override
		public void onFinish() {
			
			if (!candidates.isEmpty()) {
				
				Collections.sort(candidates, new Comparator<String>() {

					@Override
					public int compare(String lhs, String rhs) {
						
						return lhs.compareTo(rhs);
					}
					
				});
				
				new Thread() {
					
					private void publishProgress(int loadingState) {
						
						if (messageHandler!=null) {
						  Message.obtain(messageHandler,loadingState).sendToTarget();
						}
					}
					
					@Override
					public void run() {
					   
						ArrayList<KeywordsBean.Result> list = null;
						boolean fromNetwork = false;
						
						try {
							String token = SharedPreferencesWrap.INSTANCE.getString(SharedPreferencesWrap.TOKEN);
							
							String url = URLManager.getKeywordsFinish(token,candidates);
							KeywordsBean bean = HttpManager.INSTANCE.getData(url,KeywordsBean.class);
							list = bean.getResult();
							
							DatabaseManager.INSTANCE.clearTable(KeywordsBean.Result.class);
							DatabaseManager.INSTANCE.AddList(list,KeywordsBean.Result.class);
							
							fromNetwork = true;
							
						} catch (NoNetworkException e) {
							list = DatabaseManager.INSTANCE.getList(KeywordsBean.Result.class);
						} catch (CorruptedDataException e) {
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
				
				showProgress();
			}
		}

		@Override
		public void onTick(long millisUntilFinished) {
			//
		}
		
		public void tryCancel() {
			cancel();
		}
		
		public void tryStart() {
			cancel();
			if (!candidates.isEmpty()) 
			  super.start();
		}
		
	}

	private static class ListViewAdapter extends ArrayAdapter<String> implements UndoAdapter {

        private final AllKeywordsFragment fragment;

        ListViewAdapter(final AllKeywordsFragment fragment) {
        	this.fragment = fragment;
        	reload(fragment.keywords);
        }
        
        public final void reload(ArrayList<KeywordsBean.Result> keywords) {
        	
        	clear();
        	if (keywords!=null) {
	            for (KeywordsBean.Result result: keywords) {
	                add(result.getKeyword());
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
            KeywordsBean.Result result = fragment.findResult(text);
            
            ((TextView) view.findViewById(R.id.list_row_dynamiclistview_textview)).setText(text);
            
            ImageView email = (ImageView) view.findViewById(R.id.list_row_dynamiclistview_email);
            email.setImageResource(result.getEmail()?R.drawable.icn_email:R.drawable.icn_email_off);
            
            ImageView trash = (ImageView) view.findViewById(R.id.list_row_dynamiclistview_del);
            trash.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					
					fragment.listView.enableSimpleSwipeUndo();
					try {
						fragment.swipeTimer.tryCancel();
						fragment.candidates.add(text);
						fragment.swipeTimer.tryStart();
						fragment.listView.dismiss(position);
					} finally {
						fragment.listView.disableSwipeToDismiss();
					}
					
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
	public void onResume() {
		super.onResume();
	}
	
	@Override
	public void onPause() {
		super.onPause();
	}
	
	@Override
	public void onDestroy() {
		
		swipeTimer.cancel();
		hideProgress();
		messageHandler = null;
		adapter = null;
		super.onDestroy();
		
	}
	


}
