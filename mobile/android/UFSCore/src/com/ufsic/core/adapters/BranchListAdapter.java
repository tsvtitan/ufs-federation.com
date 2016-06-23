package com.ufsic.core.adapters;

import java.util.ArrayList;

import com.ufsic.core.beans.BranchBean;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class BranchListAdapter extends BaseAdapter {
	
	public static final int ROW_HEADER = 0;
	public static final int ROW_CENTER = 1;
	public static final int ROW_FOOTER = 2;
	
	private LayoutInflater inflater;
	
	private ArrayList<BranchBean.Result> beans;
	private ArrayList<Integer> regionHeaders;
	
	public BranchListAdapter(Context context) {
		inflater = LayoutInflater.from(context);
	}
	
	public void setData(ArrayList<BranchBean.Result> beans) {
		if(ToolBox.isEmpty(beans))
			return;
		
		this.beans = beans;
		regionHeaders = new ArrayList<Integer>();
		
		String prevRegion = new String();
		
		int size = beans.size();
		for (int i = 0; i < size; i++) {
			String curRegion = beans.get(i).getRegion();
			
			if(!curRegion.equals(prevRegion)) {
				int headerNum = i + regionHeaders.size();
				regionHeaders.add(headerNum);
			}
			
			prevRegion = curRegion;
		}
		
		notifyDataSetChanged();
	}
	
	@Override
	public int getCount() {
		int size = beans == null ? 0 : beans.size();
		size += regionHeaders == null ? 0 : regionHeaders.size();
		
		return size;
	}

	@Override
	public BranchBean.Result getItem(int position) {
		int viewType = getItemViewType(position);
		
		if(viewType == ROW_CENTER || viewType == ROW_FOOTER) {
			int offset = 0;
			
			int size = regionHeaders.size();
			for (int i = 0; i < size; i++) {
				if(position > regionHeaders.get(i))
					offset++;
			}
			
			return beans.get(position - offset);
		}
		else
			return null;
	}

	@Override
	public long getItemId(int position) {
		return 0;
	}
	
	@Override
	public int getViewTypeCount() {
		return 3;
	}
	
	@Override
	public int getItemViewType(int position) {
		if(position == 0)
			return ROW_HEADER;
		else if(position == getCount()-1)
			return ROW_FOOTER;
		else {
			if(!regionHeaders.contains(position)) {
				if(regionHeaders.contains(position + 1))
					return ROW_FOOTER;
				else
					return ROW_CENTER;
			}
			else
				return ROW_HEADER;
		}
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		int viewType = getItemViewType(position);
		
		if(convertView == null) {
			switch (viewType) {
			case ROW_HEADER:
				convertView = inflater.inflate(R.layout.branch_header_list_item, null);
				convertView.setTag(new HeaderHolder(convertView));
				break;
			case ROW_CENTER:
			case ROW_FOOTER:
				convertView = inflater.inflate(R.layout.group_full_list_item, null);
				convertView.setTag(new SimpleHolder(convertView));
				break;
			}
		}
		
		if(viewType == ROW_CENTER || viewType == ROW_FOOTER) {
			BranchBean.Result bean = getItem(position);
			SimpleHolder holder = (SimpleHolder) convertView.getTag();
			
			holder.actual.setVisibility(View.INVISIBLE);
			holder.city.setText(bean.getCity());
			holder.address.setText(bean.getAddress());
			
			if(viewType == ROW_CENTER)
				convertView.setBackgroundResource(R.drawable.table_center);
			else
				convertView.setBackgroundResource(R.drawable.table_footer_with_shadow);
		}
		else {
			BranchBean.Result bean = getItem(position + 1);
			HeaderHolder holder = (HeaderHolder) convertView.getTag();
			
			holder.region.setText(bean.getRegion());
		}
		
		return convertView;
	}
	
	private static class HeaderHolder {
		public TextView region;
		
		public HeaderHolder(View v) {
			region = (TextView) v.findViewById(R.id.bhli_text);
		}
	}
	
	private static class SimpleHolder {
		public ImageView actual;
		public TextView city;
		public TextView address;
		
		public SimpleHolder(View v) {
			actual = (ImageView) v.findViewById(R.id.gbli_actual);
			city = (TextView) v.findViewById(R.id.gbli_date);
			address = (TextView) v.findViewById(R.id.gbli_name);
		}
	}
}
