package com.ufsic.core.adapters;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import com.ufsic.core.beans.CategoryBean;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class SubcategoryListAdapter extends BaseAdapter {
	
	private LayoutInflater inflater;
	private ArrayList<CategoryBean.Result> beans;
	
	public SubcategoryListAdapter(Context context) {
		inflater = LayoutInflater.from(context);
	}
	
	public void setData(ArrayList<CategoryBean.Result> beans) {
		if(ToolBox.isEmpty(beans))
			return;
		
		//this.beans = beans; 
		
		/* tsv */
		List<Integer> list = Arrays.asList(new Integer[] {1});
		ArrayList<CategoryBean.Result> bns = new ArrayList<CategoryBean.Result>();
		for (CategoryBean.Result result: beans) {
			if (list.contains(result.getType())) {
				bns.add(result);
			}
		}
		this.beans = bns;
		/* tsv */
		
		notifyDataSetChanged();
	}
	
	@Override
	public int getCount() {
		return beans == null ? 0 : beans.size();
	}

	@Override
	public CategoryBean.Result getItem(int position) {
		return beans.get(position);
	}

	@Override
	public long getItemId(int position) {
		return position;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		if(convertView == null) {
			convertView = inflater.inflate(R.layout.subcategory_list_item, null);
			convertView.setTag(new ViewHolder(convertView));
		}
		
		ViewHolder holder = (ViewHolder) convertView.getTag();
		CategoryBean.Result bean = getItem(position);
		
		holder.title.setText(bean.getTitle());
		
		String allNews = bean.getAllNewsCount() == null ? "0" : bean.getAllNewsCount();
		holder.all.setText(allNews);
		
		String actualNews = bean.getActualNewsCount() == null ? "0" : bean.getActualNewsCount();
		holder.actual.setText(actualNews);
		
		return convertView;
	}
	
	private static class ViewHolder {
		public TextView title;
		public TextView actual;
		public TextView all;
		
		public ViewHolder(View v) {
			title = (TextView) v.findViewById(R.id.sli_title);
			actual = (TextView) v.findViewById(R.id.sli_actual);
			all = (TextView) v.findViewById(R.id.sli_all);
		}
	}
}
