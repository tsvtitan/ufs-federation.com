package com.ufsic.core.adapters;

import java.util.ArrayList;

import com.ufsic.core.R;
import android.content.Context;
import android.content.Intent;
import android.graphics.Paint;
import android.net.Uri;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.inqbarna.tablefixheaders.adapters.BaseTableAdapter;
import com.ufsic.core.beans.TableBean;
import com.ufsic.core.counters.AnalyticsCounter;
import com.ufsic.core.managers.URLManager;

public class TableAdapter extends BaseTableAdapter {
	
	private String[] headers;
	private DataType[] familys;
	
	private int[] widths;
	private String[] alignments;
	/* tsv */private String[] buyUrls;
	/* tsv */private String[] titles;
	
	private final float density;
	private LayoutInflater inflater;
	
	private int rowCount;
	private int columnCount;
	
	public TableAdapter(Context context) {
		density = context.getResources().getDisplayMetrics().density;
		inflater = LayoutInflater.from(context);
	}
	
	/* tsv */
	private boolean buyUrlsExist() {
		
		boolean ret = false;
		if (buyUrls!=null && buyUrls.length>0) {
			for (String s: buyUrls) {
				if (!s.trim().equals("")) {
					ret = true;
					break;
				}
			}
		}
		return ret;
	}
	/* tsv */
	
	//public void setData(TableBean.Table bean) {
	/* tsv */public void setData(TableBean.Table bean, String[] titles) {
		headers = bean.getColumns();
		
		ArrayList<String[]> listStrings = bean.getValues();
		
		rowCount = listStrings.size();
		columnCount = headers.length;
		
		Paint paint = new Paint();
		paint.setTextSize(12 * density);
		
		/* tsv */
		buyUrls = bean.getBuyUrls();
		boolean extendFirst = buyUrlsExist();
		this.titles = titles;
		/* tsv */
		
		widths = new int[columnCount];
		for (int i = 0; i < columnCount; i++) {
			float headerWidth = paint.measureText(headers[i]);
			float columnWidth = paint.measureText(listStrings.get(1)[i]);
			
			if(headerWidth > columnWidth) {
				if(headerWidth > 150 && headerWidth < 150 * 6)
					widths[i] = (int) headerWidth;
				else if(headerWidth > 150 * 6)
					widths[i] = 150 * 6;
				else
					widths[i] = 150;
			}
			else {
				if(columnWidth > 150 && columnWidth < 150 * 6)
					widths[i] = (int) columnWidth;
				else if(columnWidth > 150 * 6)
					widths[i] = 150 * 6;
				else
					widths[i] = 150;
			}
			
			/* tsv */
			if (i==0 && extendFirst) {
				widths[i] = widths[i] + 50;
			}
			/* tsv */
		}
		
		alignments = bean.getAlignments();
		
		int familysCount = 0;
		for (String[] strings : listStrings) {
			if(strings[0].equals(strings[1]))
				familysCount++;
		}
		
		if(familysCount > 0) {
			familys = new DataType[familysCount];
			
			int familysNumber = 0;
			for (String[] strings : listStrings) {
				if(strings[0].equals(strings[1])) {
					familys[familysNumber] = new DataType();
					familys[familysNumber].name = strings[0];
					familysNumber++;
				}
				else {
					Data data = new Data();
					data.data = strings;
					
					familys[familysNumber - 1].list.add(data);
				}
			}
		}
		else {
			familys = new DataType[1];
			
			familys[0] = new DataType();
			familys[0].name = "";
			
			for (String[] strings : listStrings) {
				Data data = new Data();
				data.data = strings;
				
				familys[0].list.add(data);
			}
		}
	}
	
	@Override
	public int getRowCount() {
		return rowCount;
	}

	@Override
	public int getColumnCount() {
		return columnCount - 1;
	}

	@Override
	public View getView(int row, int column, View convertView, ViewGroup parent) {
		final View view;
		switch (getItemViewType(row, column)) {
			case 0:
				view = getFirstHeader(row, column, convertView, parent);
			break;
			case 1:
				view = getHeader(row, column, convertView, parent);
			break;
			case 2:
				view = getFirstBody(row, column, convertView, parent);
			break;
			case 3:
				view = getBody(row, column, convertView, parent);
			break;
			case 4:
				view = getFamilyView(row, column, convertView, parent);
			break;
			default:
				throw new RuntimeException("wtf?");
		}
		return view;
	}
	
	private View getFirstHeader(int row, int column, View convertView, ViewGroup parent) {
		if (convertView == null) {
			convertView = inflater.inflate(R.layout.item_table_header_first, parent, false);
		}
		((TextView) convertView.findViewById(android.R.id.text1)).setText(headers[0]);
		return convertView;
	}

	private View getHeader(int row, int column, View convertView, ViewGroup parent) {
		if (convertView == null) {
			convertView = inflater.inflate(R.layout.item_table_header, parent, false);
		}
		((TextView) convertView.findViewById(android.R.id.text1)).setText(headers[column + 1]);
		return convertView;
	}

	private View getFirstBody(int row, int column, View convertView, ViewGroup parent) {
		if (convertView == null) {
			convertView = inflater.inflate(R.layout.item_table_first, parent, false);
		}
		final String title = getDevice(row).data[column + 1];
		((TextView) convertView.findViewById(android.R.id.text1)).setText(title);
		
		/* tsv */
		if (convertView!=null) {
			ImageView iv = (ImageView)convertView.findViewById(R.id.buy_image);
			if ((column + 1)==0) {
				
				final String url = buyUrls[row];
				if (!url.trim().equals("")) {
					
					iv.setVisibility(View.VISIBLE);
					iv.setOnClickListener(new OnClickListener() {
						
						@Override
						public void onClick(View v) {
							
							String s = URLManager.getWebUrl().concat(url);
							
							AnalyticsCounter.eventScreens(titles,title,"open",s);
							
							Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(s));
							v.getContext().startActivity(intent);
						}
					});
				} else {
					iv.setVisibility(View.INVISIBLE);
				}
			} else {
				iv.setVisibility(View.INVISIBLE);
			}
		}
		/* tsv */
		
		return convertView;
	}

	private View getBody(int row, int column, View convertView, ViewGroup parent) {
		if (convertView == null) {
			convertView = inflater.inflate(R.layout.item_table, parent, false);
		}
		
		TextView tv = (TextView) convertView.findViewById(android.R.id.text1);
		tv.setText(getDevice(row).data[column + 1]);
		
		//могут прийти не все alignments
		if((column + 1) + 1 <= alignments.length) {
			
			if(alignments[column + 1].equals("left"))
				tv.setGravity(Gravity.CENTER_VERTICAL | Gravity.LEFT);
			else if(alignments[column + 1].equals("right"))
				tv.setGravity(Gravity.CENTER_VERTICAL | Gravity.RIGHT);
			else
				tv.setGravity(Gravity.CENTER);
		}
		else {
			tv.setGravity(Gravity.CENTER);
		}
		
		return convertView;
	}

	private View getFamilyView(int row, int column, View convertView, ViewGroup parent) {
		if (convertView == null) {
			convertView = inflater.inflate(R.layout.item_table_family, parent, false);
		}
		final String string;
		if (column == -1) {
			string = getFamily(row).name;
		} else {
			string = "";
		}
		((TextView) convertView.findViewById(android.R.id.text1)).setText(string);
		return convertView;
	}

	@Override
	public int getWidth(int column) {
		return Math.round(widths[column + 1] * density);
	}

	@Override
	public int getHeight(int row) {
		if(isFamily(row))
			if(familys[0].name.equals(""))
				return Math.round(1 * density);
		
		return Math.round(40 * density);
	}

	@Override
	public int getItemViewType(int row, int column) {
		final int itemViewType;
		if (row == -1 && column == -1) {
			itemViewType = 0;
		} else if (row == -1) {
			itemViewType = 1;
		} else if (isFamily(row)) {
			itemViewType = 4;
		} else if (column == -1) {
			itemViewType = 2;
		} else {
			itemViewType = 3;
		}
		return itemViewType;
	}
	
	private boolean isFamily(int row) {
		int family = 0;
		while (row > 0) {
			row -= familys[family].list.size() + 1;
			family++;
		}
		return row == 0;
	}

	private DataType getFamily(int row) {
		int family = 0;
		while (row >= 0) {
			row -= familys[family].list.size() + 1;
			family++;
		}
		return familys[family - 1];
	}

	private Data getDevice(int row) {
		int family = 0;
		while (row >= 0) {
			row -= familys[family].list.size() + 1;
			family++;
		}
		family--;
		return familys[family].list.get(row + familys[family].list.size());
	}

	@Override
	public int getViewTypeCount() {
		return 5;
	}
	
	private class DataType {
		
		public String name;
		public ArrayList<Data> list = new ArrayList<Data>();
	}
	
	private class Data {
		
		public String[] data;
	}
}
