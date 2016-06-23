package com.ufsic.core.adapters;

import java.util.ArrayList;
import java.util.List;

import com.ufsic.core.beans.BaseGroupPreviewBean;
import com.ufsic.core.beans.GroupBean;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class GroupPreviewListAdapter extends BaseAdapter {
	
	public static final int ROW_HEADER = 0;
	public static final int ROW_HEADER_SINGLE = 1;
	public static final int ROW_CENTER = 2;
	public static final int ROW_FOOTER = 3;
	
	private LayoutInflater inflater;
	private List<BaseGroupPreviewBean> beans;
	
	public GroupPreviewListAdapter(Context context) {
		inflater = LayoutInflater.from(context);
	}
	
	public void setData(ArrayList<GroupBean.Result> beans) {
		if(ToolBox.isEmpty(beans))
			return;
		
		if(this.beans == null)
			this.beans = new ArrayList<BaseGroupPreviewBean>();
		
		int parentCount = beans.size();
		for (int i = 0; i < parentCount; i++) {
			GroupBean.Result parentBean = beans.get(i);
			parentBean.setParentId(i);
			this.beans.add(parentBean);
			
			int childCount = parentBean.getItemsActual().size();
			if(childCount > 0) {
				
				for (int j = 0; j < childCount; j++) {
					GroupBean.Item childBean = parentBean.getItemsActual().get(j);
					childBean.setParentId(i);
					this.beans.add(childBean);
				}
			}
		}
		
		notifyDataSetChanged();
	}
	
	@Override
	public int getCount() {
		return beans == null ? 0 : beans.size();
	}

	@Override
	public BaseGroupPreviewBean getItem(int position) {
		return beans.get(position);
	}

	@Override
	public long getItemId(int position) {
		return position;
	}
	
	@Override
	public int getViewTypeCount() {
		return 4;
	}
	
	@Override
	public int getItemViewType(int position) {
		int childrenCount = getItem(position).getItemsActualCount();
		
		if(childrenCount == 0)
			return ROW_HEADER_SINGLE;
		else if(position == 0)
			return ROW_HEADER;
		else if(position == getCount()-1)
			return ROW_FOOTER;
		else {
			if(getItem(position).getParentId() < getItem(position + 1).getParentId())
				return ROW_FOOTER;
			else if(getItem(position).getParentId() > getItem(position - 1).getParentId())
				return ROW_HEADER;
			else
				return ROW_CENTER;
		}
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		int viewType = getItemViewType(position);
		
		if(convertView == null) {
			switch (viewType) {
			case ROW_HEADER:
			case ROW_HEADER_SINGLE:
				convertView = inflater.inflate(R.layout.group_preview_list_item, null);
				convertView.setTag(new HeaderHolder(convertView));
				break;
			case ROW_CENTER:
			case ROW_FOOTER:
				convertView = inflater.inflate(R.layout.group_full_list_item, null);
				convertView.setTag(new CenterHolder(convertView));
				break;
			}
		}
		
		BaseGroupPreviewBean bean = getItem(position);
		
		if(viewType == ROW_HEADER || viewType == ROW_HEADER_SINGLE) {
			HeaderHolder holder = (HeaderHolder) convertView.getTag();
			
			holder.text.setText(bean.getName());
			holder.count.setText(String.valueOf(bean.getItemsCount()));
			
			if(viewType == ROW_HEADER)
				convertView.setBackgroundResource(R.drawable.table_header_with_shadow);
			else
				convertView.setBackgroundResource(R.drawable.table_header_single_with_shadow);
		}
		else {
			CenterHolder holder = (CenterHolder) convertView.getTag();
			
			if(bean.getActual() == 1)
				holder.actual.setVisibility(View.VISIBLE);
			else
				holder.actual.setVisibility(View.INVISIBLE);
			
			holder.date.setText(bean.getDateStr());
			holder.name.setText(bean.getName());
			
			if(viewType == ROW_CENTER)
				convertView.setBackgroundResource(R.drawable.table_center);
			else
				convertView.setBackgroundResource(R.drawable.table_footer_with_shadow);
		}
		
		return convertView;
	}
	
	private static class HeaderHolder {
		public TextView text;
		public TextView count;
		
		public HeaderHolder(View v) {
			text = (TextView) v.findViewById(R.id.ghli_text);
			count = (TextView) v.findViewById(R.id.ghli_count);
		}
	}
	
	private static class CenterHolder {
		public ImageView actual;
		public TextView date;
		public TextView name;
		
		public CenterHolder(View v) {
			actual = (ImageView) v.findViewById(R.id.gbli_actual);
			date = (TextView) v.findViewById(R.id.gbli_date);
			name = (TextView) v.findViewById(R.id.gbli_name);
		}
	}
}
