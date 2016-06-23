package ru.ideast.ufs.adapters;

import java.util.ArrayList;
import java.util.List;

import ru.ideast.ufs.R;
import ru.ideast.ufs.beans.GroupBean;
import ru.ideast.ufs.utils.ToolBox;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

public class GroupFullListAdapter extends BaseAdapter {
	
	private LayoutInflater inflater;
	private List<GroupBean.Item> beans;
	
	public GroupFullListAdapter(Context context) {
		inflater = LayoutInflater.from(context);
	}
	
	public void setData(ArrayList<GroupBean.Item> beans) {
		if(ToolBox.isEmpty(beans))
			return;
		
		if(this.beans == null)
			this.beans = new ArrayList<GroupBean.Item>();
		
		this.beans.addAll(beans);
		notifyDataSetChanged();
	}
	
	@Override
	public int getCount() {
		return beans == null ? 0 : beans.size();
	}

	@Override
	public GroupBean.Item getItem(int position) {
		return beans.get(position);
	}

	@Override
	public long getItemId(int position) {
		return position;
	}

	@Override
	public View getView(int position, View convertView, ViewGroup parent) {
		if(convertView == null) {
			convertView = inflater.inflate(R.layout.group_full_list_item, null);
			convertView.setTag(new ViewHolder(convertView));
		}
		
		ViewHolder holder = (ViewHolder) convertView.getTag();
		GroupBean.Item bean = getItem(position);
		
		if(getCount() == 1)
			holder.container.setBackgroundResource(R.drawable.table_center_single);
		else if(position == 0)
			holder.container.setBackgroundResource(R.drawable.table_header_without_shadow);
		else if(position == getCount() - 1)
			holder.container.setBackgroundResource(R.drawable.table_footer_with_shadow);
		else
			holder.container.setBackgroundResource(R.drawable.table_center);
		
		if(bean.getNew() == 1)
			holder.actual.setVisibility(View.VISIBLE);
		else
			holder.actual.setVisibility(View.INVISIBLE);
		
		holder.date.setText(bean.getDateStr());
		holder.name.setText(bean.getName());
		
		return convertView;
	}
	
	private static class ViewHolder {
		public LinearLayout container;
		public ImageView actual;
		public TextView date;
		public TextView name;
		
		public ViewHolder(View v) {
			container = (LinearLayout) v.findViewById(R.id.gbli_container);
			actual = (ImageView) v.findViewById(R.id.gbli_actual);
			date = (TextView) v.findViewById(R.id.gbli_date);
			name = (TextView) v.findViewById(R.id.gbli_name);
		}
	}
}
