package ru.ideast.ufs.beans;

import java.io.Serializable;
import java.util.ArrayList;

import com.j256.ormlite.field.DataType;
import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;

import ru.ideast.ufs.beans.CategoryBean.Result;

public class CategoryBean extends BaseBean<ArrayList<Result>> {
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = 1671040394366844773L;
		
		public final static String TABLE_NAME = "category_bean";
		
		public final static String DB_ID = "id";
		public final static String DB_TITLE = "title";
		public final static String DB_TYPE = "type";
		public final static String DB_EXPIRED = "expired";
		public final static String DB_IMG_URL = "imgURL";
		public final static String DB_H_IMG_URL = "h_imgURL";
		public final static String DB_ALL_ACTIVITY_COUNT = "allActivityCount";
		public final static String DB_ALL_NEWS_COUNT = "allNewsCount";
		public final static String DB_ACTUAL_NEWS_COUNT = "actualNewsCount";
		public final static String DB_SUBCATEGORIES = "subcategories";
		
		@DatabaseField(columnName = DB_ID, id = true)
		private String id;
		@DatabaseField(columnName = DB_TITLE)
		private String title;
		@DatabaseField(columnName = DB_TYPE)
		private int type;
		@DatabaseField(columnName = DB_EXPIRED)
		private long expired;
		@DatabaseField(columnName = DB_IMG_URL)
		private String imgURL;
		@DatabaseField(columnName = DB_H_IMG_URL)
		private String h_imgURL;
		@DatabaseField(columnName = DB_ALL_ACTIVITY_COUNT)
		private String allActivityCount;
		@DatabaseField(columnName = DB_ALL_NEWS_COUNT)
		private String allNewsCount;
		@DatabaseField(columnName = DB_ACTUAL_NEWS_COUNT)
		private String actualNewsCount;
		@DatabaseField(columnName = DB_SUBCATEGORIES, dataType = DataType.SERIALIZABLE)
		private ArrayList<Result> subcategories;
		
		public String getId() {
			return id;
		}
		
		public void setId(String id) {
			this.id = id;
		}

		public String getTitle() {
			return title;
		}

		public void setTitle(String title) {
			this.title = title;
		}

		public int getType() {
			return type;
		}

		public void setType(String type) {
			this.type = Integer.valueOf(type);
		}

		public long getExpired() {
			return expired;
		}

		public void setExpired(String expired) {
			this.expired = Long.valueOf(expired) * 1000;
		}
		
		public String getImgURL() {
			return imgURL;
		}

		public void setImgURL(String imgURL) {
			this.imgURL = imgURL;
		}

		public String getH_imgURL() {
			return h_imgURL;
		}

		public void setH_imgURL(String h_imgURL) {
			this.h_imgURL = h_imgURL;
		}

		public String getAllActivityCount() {
			return allActivityCount;
		}

		public void setAllActivityCount(String allActivityCount) {
			this.allActivityCount = allActivityCount;
		}

		public String getAllNewsCount() {
			return allNewsCount;
		}

		public void setAllNewsCount(String allNewsCount) {
			this.allNewsCount = allNewsCount;
		}

		public String getActualNewsCount() {
			return actualNewsCount;
		}

		public void setActualNewsCount(String actualNewsCount) {
			this.actualNewsCount = actualNewsCount;
		}

		public ArrayList<Result> getSubcategories() {
			return subcategories;
		}

		public void setSubcategories(ArrayList<Result> subcategories) {
			this.subcategories = subcategories;
		}
	}
}
