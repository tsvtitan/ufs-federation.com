package com.ufsic.core.beans;

import java.io.Serializable;
import java.util.ArrayList;

import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.ActivityBean.Result;


public class ActivityBean extends BaseBean<ArrayList<Result>> {
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = -1323649593596838042L;

		public static final String TABLE_NAME = "activity_bean";
		
		public static final String DB_ID = "id";
		public static final String DB_NAME = "name";
		public static final String DB_EXPIRED = "expired";
		public static final String DB_MAIN_IMG = "main_img";
		public static final String DB_URL = "url";
		public static final String DB_TEXT = "text";
		
		@DatabaseField(columnName = DB_ID, id = true)
		private String id;
		@DatabaseField(columnName = DB_NAME)
		private String name;
		@DatabaseField(columnName = DB_EXPIRED)
		private long expired;
		@DatabaseField(columnName = DB_MAIN_IMG)
		private String mainImg;
		@DatabaseField(columnName = DB_URL)
		private String url;
		@DatabaseField(columnName = DB_TEXT)
		private String text;
		
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

		public long getExpired() {
			return expired;
		}

		public void setExpired(String expired) {
			this.expired = Long.valueOf(expired);
		}

		public String getMainImg() {
			return mainImg;
		}

		public void setMainImg(String mainImg) {
			this.mainImg = mainImg;
		}

		public String getUrl() {
			return url;
		}

		public void setUrl(String url) {
			this.url = url;
		}

		public String getText() {
			return text;
		}

		public void setText(String text) {
			this.text = text;
		}
	}
}
