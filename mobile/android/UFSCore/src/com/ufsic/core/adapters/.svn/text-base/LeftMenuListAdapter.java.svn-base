package com.ufsic.core.adapters;

import java.util.ArrayList;
import java.util.HashMap;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.listener.SimpleImageLoadingListener;
import com.ufsic.core.beans.CategoryBean;
import com.ufsic.core.managers.URLManager;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;
import android.content.Context;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.StateListDrawable;
import android.util.StateSet;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class LeftMenuListAdapter extends BaseAdapter {
	
	private LayoutInflater inflater;
	private ArrayList<CategoryBean.Result> beans;
	/* tsv */private HashMap<String,Integer> backgrounds = new HashMap<>();
	
	private int paddingLeft;
	
	public LeftMenuListAdapter(Context context) {
		inflater = LayoutInflater.from(context);
		paddingLeft = ToolBox.dp2pix(context, 10);
	}
	
	public void setData(ArrayList<CategoryBean.Result> beans) {
		if(ToolBox.isEmpty(beans))
			return;
		
		this.beans = beans;
		notifyDataSetChanged();
	}
	
	
	@Override
	public int getCount() {
		int count = 0;
		if (beans!=null) {
			count = beans.size();
			for (CategoryBean.Result catBean : beans) {
				if(!ToolBox.isEmpty(catBean.getSubcategories())) {
					count += catBean.getSubcategories().size();
				}
			}
		}
		return count;
	}

	@Override
	public CategoryBean.Result getItem(int position) {
		int count = position;
		
		int catCount = 0;
		if (beans!=null) { 
			
			catCount = beans.size();
			for (int i = 0; i < catCount; i++) {
				if(count == 0) {
					return beans.get(i);
				}
				
				count--;
				
				if(!ToolBox.isEmpty(beans.get(i).getSubcategories())) {
					
					int subCount = beans.get(i).getSubcategories().size();
					for (int j = 0; j < subCount; j++) {
						if(count == 0) {
							return beans.get(i).getSubcategories().get(j);
						}
						
						count--;
					}
				}
			}
		}
		return null;
	}

	@Override
	public long getItemId(int position) {
		return position;
	}
	
	public String getCategoryId(int position) {
		int count = position;
		
		int catCount = 0;
		if (beans!=null) {
			catCount = beans.size();
			for (int i = 0; i < catCount; i++) {
				String categoryId = beans.get(i).getId();
				if(count == 0) {
					return categoryId;
				}
				
				count--;
				
				if(!ToolBox.isEmpty(beans.get(i).getSubcategories())) {
					
					int subCount = beans.get(i).getSubcategories().size();
					for (int j = 0; j < subCount; j++) {
						if(count == 0) {
							return categoryId;
						}
						
						count--;
					}
				}
			}
		}
		return null;
	}

	/* tsv */
	public int getBackground(CategoryBean.Result bean) {
		
		int d = R.drawable.left_menu_header_selector;
		Integer b = backgrounds.get(bean.getId());
		if (b==null) {
			Resources res = inflater.getContext().getResources();
			String name = inflater.getContext().getPackageName();
			int drawableResourceId = res.getIdentifier(String.format("left_menu_header_selector_%s",bean.getId()),"drawable",name);
			b = (drawableResourceId!=0)?drawableResourceId:R.drawable.left_menu_header_selector;
			backgrounds.put(bean.getId(),b);
		}
		return (b!=null)?b:d;
	}
	/* tsv */
	
	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		
		if(convertView == null) {
			convertView = inflater.inflate(R.layout.left_menu_list_item, null);
			convertView.setTag(new ViewHolder(convertView));
		}
		
		final ViewHolder holder = (ViewHolder) convertView.getTag();
		final CategoryBean.Result bean = getItem(position);
		
		/* tsv */boolean needText = true;
		
		if(bean.getImgURL() == null) {
			holder.image.setVisibility(View.GONE);
			holder.text.setTextColor(Color.rgb(76, 115, 144));
			holder.text.setPadding(paddingLeft, 0, paddingLeft, 0);
			
			/* tsv */
			//convertView.setBackgroundResource(R.drawable.left_menu_header_selector);
			int back = getBackground(bean);
			needText = back==R.drawable.left_menu_header_selector;
			convertView.setBackgroundResource(getBackground(bean));
			/* tsv */
		}
		else {
			holder.image.setVisibility(View.VISIBLE);
			
			final ImageLoader imageLoader = ImageLoader.getInstance();
			imageLoader.loadImage(URLManager.getFile(bean.getImgURL()), new SimpleImageLoadingListener() {
			    @Override
			    public void onLoadingComplete(String imageUri, View view, final Bitmap loadedImage) {
			    	
					imageLoader.loadImage(URLManager.getFile(bean.getH_imgURL()), new SimpleImageLoadingListener() {
					    @Override
					    public void onLoadingComplete(String imageUri, View view, Bitmap loadedHImage) {

					    	  Resources res = inflater.getContext().getResources();
					    	  BitmapDrawable drawableHImage = new BitmapDrawable(res, loadedHImage);
					    	  
					    	  StateListDrawable stateList = new StateListDrawable();
					    	  stateList.addState(new int[] { android.R.attr.state_pressed }, drawableHImage);
					    	  stateList.addState(new int[] { android.R.attr.state_selected }, drawableHImage);
					    	  stateList.addState(StateSet.WILD_CARD, new BitmapDrawable(res, loadedImage));
					    	  
					    	  holder.image.setImageDrawable(stateList);
					    }
					});
			    }
			});
			
			holder.text.setTextColor(Color.rgb(187, 219, 239));
			holder.text.setPadding(0, 0, paddingLeft, 0);
			convertView.setBackgroundResource(R.drawable.left_menu_item_selector);
		}
		
		//holder.text.setText(bean.getTitle());        
		/* tsv */holder.text.setText(needText?bean.getTitle():"");
		
		return convertView;
	}
	
	private static class ViewHolder {
		public ImageView image;
		public TextView text;
		
		public ViewHolder(View v) {
			image = (ImageView) v.findViewById(R.id.lmli_image);
			text = (TextView) v.findViewById(R.id.lmli_text);
		}
		
		
	}
}
