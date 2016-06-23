package com.ufsic.core.beans;

import java.io.Serializable;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Locale;

import com.j256.ormlite.field.DataType;
import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.GroupBean.Result;


public class GroupBean extends BaseBean<ArrayList<Result>> {
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result extends BaseGroupPreviewBean {
		
		public static final String TABLE_NAME = "group_bean";
		
		public static final String DB_ID = "id";
		public static final String DB_NAME = "name";
		public static final String DB_ITEMS = "items";
		public static final String DB_ITEMS_ACTUAL = "items_actual";
		
		@DatabaseField(columnName = DB_ID, id = true)
		private String id;
		@DatabaseField(columnName = DB_NAME)
		private String name;
		@DatabaseField(columnName = DB_ITEMS, dataType = DataType.SERIALIZABLE)
		private ArrayList<Item> items;
		@DatabaseField(columnName = DB_ITEMS_ACTUAL, dataType = DataType.SERIALIZABLE)
		private ArrayList<Item> itemsActual = new ArrayList<Item>();
		
		public String getId() {
			return id;
		}
		
		public void setId(String id) {
			this.id = id;
		}

		public String getName() {
			return name;
		}

		public void setName(String name) {
			this.name = name;
		}

		public ArrayList<Item> getItems() {
			return items;
		}

		public void setItems(ArrayList<Item> items) {
			this.items = items;
		}
		
		public ArrayList<Item> getItemsActual() {
			return itemsActual;
		}

		public void setItemsActual(ArrayList<Item> itemsActual) {
			this.itemsActual = itemsActual;
		}

		@Override
		public int getItemsCount() {
			return items.size();
		}
		
		@Override
		public int getItemsActualCount() {
			return itemsActual.size();
		}

		@Override
		public int getActual() {
			return 0;
		}

		@Override
		public String getDateStr() {
			return null;
		}
	}
	
	public static class Item extends BaseGroupPreviewBean implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = -5892938399340029894L;

		private static SimpleDateFormat dateFormat = new SimpleDateFormat("d MMMM yyyy", Locale.getDefault());
		
		private String id;
		private String name;
		private long date;
		private String dateStr;
		private int neww;
		private String type;
		private String linkID;
		private int actual;
		
		public String getId() {
			return id;
		}
		
		public void setId(String id) {
			this.id = id;
		}

		public String getName() {
			return name;
		}

		public void setName(String name) {
			this.name = name;
		}

		public long getDate() {
			return date;
		}

		public void setDate(long date) {
			this.date = date;
			setDateStr(date);
		}
		
		public String getDateStr() {
			return dateStr;
		}

		public void setDateStr(long dateStr) {
			this.dateStr = dateFormat.format(new Date(dateStr * 1000));
		}

		public int getNew() {
			return neww;
		}

		public void setNew(String neww) {
			this.neww = Integer.valueOf(neww);
		}

		public String getType() {
			return type;
		}

		public void setType(String type) {
			this.type = type;
		}

		public String getLinkID() {
			return linkID;
		}

		public void setLinkID(String linkID) {
			this.linkID = linkID;
		}

		public int getActual() {
			return actual;
		}

		public void setActual(String actual) {
			this.actual = Integer.valueOf(actual);
		}

		@Override
		public int getItemsCount() {
			return -1;
		}
		
		@Override
		public int getItemsActualCount() {
			return -1;
		}
	}
}
