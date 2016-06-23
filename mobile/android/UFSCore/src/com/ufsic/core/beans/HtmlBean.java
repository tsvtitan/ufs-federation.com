package com.ufsic.core.beans;

import java.util.ArrayList;

import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.HtmlBean.Result;


public class HtmlBean extends BaseBean<ArrayList<Result>> {
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result {
		
		public static final String TABLE_NAME = "html_bean";
		
		public static final String DB_ID = "id";
		public static final String DB_CATEGORY_ID = "category_id";
		public static final String DB_SUBCATEGORY_ID = "subcategory_id";
		public static final String DB_HTML = "html";
		
		@DatabaseField(columnName = DB_ID, generatedId = true)
		private int id;
		@DatabaseField(columnName = DB_CATEGORY_ID)
		private String categoryID;
		@DatabaseField(columnName = DB_SUBCATEGORY_ID)
		private String subcategoryID;
		@DatabaseField(columnName = DB_HTML)
		private String html;
		
		public int getId() {
			return id;
		}
		
		public void setId(int id) {
			this.id = id;
		}

		public String getCategoryID() {
			return categoryID;
		}

		public void setCategoryID(String categoryID) {
			this.categoryID = categoryID;
		}

		public String getSubcategoryID() {
			return subcategoryID;
		}

		public void setSubcategoryID(String subcategoryID) {
			this.subcategoryID = subcategoryID;
		}

		public String getHtml() {
			return html;
		}

		public void setHtml(String html) {
			this.html = html;
		}
	}
}
