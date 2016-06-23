package ru.ideast.ufs.adapters;

import java.util.ArrayList;

import com.nostra13.universalimageloader.core.ImageLoader;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.ActivityBean;
import ru.ideast.ufs.managers.URLManager;
import ru.ideast.ufs.utils.ToolBox;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;

public class ActivityListAdapter extends BaseAdapter {
	
	private LayoutInflater inflater;
	private ArrayList<ActivityBean.Result> beans;
	
	public ActivityListAdapter(Context context) {
		inflater = LayoutInflater.from(context);
	}
	
	public void setData(ArrayList<ActivityBean.Result> beans) {
		if(ToolBox.isEmpty(beans))
			return;
		
		this.beans = beans;
		notifyDataSetChanged();
	}
	
	@Override
	public int getCount() {
		return beans == null ? 0 : beans.size();
	}

	@Override
	public ActivityBean.Result getItem(int position) {
		return beans.get(position);
	}

	@Override
	public long getItemId(int position) {
		return position;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		if(convertView == null) {
			convertView = inflater.inflate(R.layout.activity_list_item, null);
			convertView.setTag(new ViewHolder(convertView));
		}
		
		ViewHolder holder = (ViewHolder) convertView.getTag();
		ActivityBean.Result bean = getItem(position);
		
		ImageLoader imageLoader = ImageLoader.getInstance();
		imageLoader.displayImage(URLManager.getFile(bean.getMainImg()), holder.image);
		
		return convertView;
	}
	
	private static class ViewHolder {
		public ImageView image;
		
		public ViewHolder(View v) {
			image = (ImageView) v.findViewById(R.id.ali_image);
		}
	}
}
