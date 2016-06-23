package com.ufsic.core.adapters;

import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.TreeSet;

import com.ufsic.core.beans.KeywordsBean;
import com.ufsic.core.beans.NewsBean;
import com.ufsic.core.layouts.FlowLayoutEx;
import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;
import android.app.Activity;
import android.content.Context;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

public class NewsListAdapter extends BaseAdapter {
	
	private LayoutInflater inflater;
	private List<NewsBean.Result> beans;
    /* tsv */private boolean busy = false;
	
	public NewsListAdapter(Context context) {
		inflater = LayoutInflater.from(context);
	}
	
	public void setData(ArrayList<NewsBean.Result> beans) {
		if(ToolBox.isEmpty(beans))
			return;
		
		this.beans = beans;
		notifyDataSetChanged();
	}
	
	public void clearData() {
		beans.clear();
		notifyDataSetChanged();
	}
	
	/* tsv */
	public void setBusy(boolean busy) {
		this.busy = busy;
	}
	/* tsv */
	
	@Override
	public int getCount() {
		return beans == null ? 0 : beans.size();
	}

	@Override
	public NewsBean.Result getItem(int position) {
		return beans.get(position);
	}

	@Override
	public long getItemId(int position) {
		return position;
	}

	/*@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		
		if(convertView == null) {
			convertView = inflater.inflate(R.layout.news_list_item, null);
			convertView.setTag(new ViewHolder(convertView));
		}
		
		ViewHolder holder = (ViewHolder) convertView.getTag();
		NewsBean.Result bean = getItem(position);
		
		if(bean.getActual() == 1)
			holder.actual.setVisibility(View.VISIBLE);
		else
			holder.actual.setVisibility(View.GONE);
		
		holder.title.setText(bean.getTitle());
		holder.date.setText(bean.getDateStr());
		holder.text.setText(bean.getText());
		
		if(bean.getFileUrls().size() > 0)
			holder.pdf.setVisibility(View.VISIBLE);
		else
			holder.pdf.setVisibility(View.GONE);
		
		return convertView;
	}*/
	
	/* tsv */
	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		
		final NewsBean.Result bean = getItem(position);
		ViewHolder holder = null;
		
		if (convertView==null) {
			
			convertView = inflater.inflate(R.layout.news_list_item, parent, false);
			
			holder =  new ViewHolder(convertView);
			holder.setProperties(bean,convertView);
			
			convertView.setTag(holder);

		} else {

			holder = (ViewHolder) convertView.getTag();
			if (!busy) {
				holder.setProperties(bean,convertView);
			}
		}
		
		return convertView;
	}
	
	/*private static class ViewHolder {
		public ImageView actual;
		public TextView title;
		public TextView date;
		public TextView text;
		public ImageView pdf;
		
		public ViewHolder(View v) {
			actual = (ImageView) v.findViewById(R.id.nli_actual);
			title = (TextView) v.findViewById(R.id.nli_title);
			date = (TextView) v.findViewById(R.id.nli_date);
			text = (TextView) v.findViewById(R.id.nli_text);
			pdf = (ImageView) v.findViewById(R.id.nli_pdf);
		}

	}*/
	
	private static class ViewHolder {
		
		public ImageView actual;
		public TextView title;
		public TextView date;
		public TextView text;
		public ImageView pdf;
		public RelativeLayout layout = null;
		public FlowLayoutEx matches = null;
		public FlowLayoutEx.LayoutParams params;
		
		public ViewHolder(View v) {
			actual = (ImageView) v.findViewById(R.id.nli_actual);
			title = (TextView) v.findViewById(R.id.nli_title);
			date = (TextView) v.findViewById(R.id.nli_date);
			text = (TextView) v.findViewById(R.id.nli_text);
			pdf = (ImageView) v.findViewById(R.id.nli_pdf);
			layout = (RelativeLayout) v.findViewById(R.id.nli_matches_layout);
			matches = (FlowLayoutEx) v.findViewById(R.id.nli_matches);
			
			params = new FlowLayoutEx.LayoutParams(LayoutParams.WRAP_CONTENT,LayoutParams.WRAP_CONTENT);
			params.height = 36;
			params.leftMargin = 0;
			params.bottomMargin = 0;
			params.rightMargin = 5;
			params.topMargin = 0;
			params.gravity = Gravity.CENTER;
		}
		
		public synchronized void setProperties(NewsBean.Result bean, View convertView) {
			
			if (bean!=null) {
				
				if(bean.getActual() == 1)
					actual.setVisibility(View.VISIBLE);
				else
					actual.setVisibility(View.GONE);
				
				title.setText(bean.getTitle());
				date.setText(bean.getDateStr());
				text.setText(bean.getText());
				
				if(bean.getFileUrls().size() > 0)
					pdf.setVisibility(View.VISIBLE);
				else
					pdf.setVisibility(View.GONE);
				
				if (matches!=null) {
					TreeSet<String> mList = bean.getMatches();
					boolean b = mList!=null && mList.size()>0;
					if (b) {
						
						matches.removeAllViews();
						
						for (String s: mList) {
							
							TextView txt = new TextView(convertView.getContext());
							txt.setLayoutParams(params);
							txt.setTextSize(12);
							txt.setText(s);
							txt.setGravity(Gravity.LEFT | Gravity.CENTER_VERTICAL);
							txt.setCompoundDrawablesWithIntrinsicBounds(R.drawable.icn_label,0,0,0);
							matches.addView(txt);
						}
					}
					layout.setVisibility(b?View.VISIBLE:View.GONE);
				}
			}
		}
	}
	/* tsv */
}
