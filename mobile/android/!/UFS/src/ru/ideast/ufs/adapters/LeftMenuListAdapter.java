package ru.ideast.ufs.adapters;

import java.util.ArrayList;

import com.nostra13.universalimageloader.core.ImageLoader;
import com.nostra13.universalimageloader.core.listener.SimpleImageLoadingListener;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.CategoryBean;
import ru.ideast.ufs.managers.URLManager;
import ru.ideast.ufs.utils.ToolBox;

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
		int count = beans.size();
		for (CategoryBean.Result catBean : beans) {
			if(!ToolBox.isEmpty(catBean.getSubcategories())) {
				count += catBean.getSubcategories().size();
			}
		}
		return count;
	}

	@Override
	public CategoryBean.Result getItem(int position) {
		int count = position;
		
		int catCount = beans.size();
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
		return null;
	}

	@Override
	public long getItemId(int position) {
		return position;
	}
	
	public String getCategoryId(int position) {
		int count = position;
		
		int catCount = beans.size();
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
		return null;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		if(convertView == null) {
			convertView = inflater.inflate(R.layout.left_menu_list_item, null);
			convertView.setTag(new ViewHolder(convertView));
		}
		
		final ViewHolder holder = (ViewHolder) convertView.getTag();
		final CategoryBean.Result bean = getItem(position);
		
		if(bean.getImgURL() == null) {
			holder.image.setVisibility(View.GONE);
			holder.text.setTextColor(Color.rgb(76, 115, 144));
			holder.text.setPadding(paddingLeft, 0, paddingLeft, 0);
			convertView.setBackgroundResource(R.drawable.left_menu_header_selector);
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
		
		holder.text.setText(bean.getTitle());
		
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
