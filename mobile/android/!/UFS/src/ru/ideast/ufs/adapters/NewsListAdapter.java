package ru.ideast.ufs.adapters;

import java.util.ArrayList;
import java.util.List;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.NewsBean;
import ru.ideast.ufs.utils.ToolBox;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class NewsListAdapter extends BaseAdapter {
	
	private LayoutInflater inflater;
	private List<NewsBean.Result> beans;
	
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

	@Override
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
	}
	
	private static class ViewHolder {
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
	}
}
