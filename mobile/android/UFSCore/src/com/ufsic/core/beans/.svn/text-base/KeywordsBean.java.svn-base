package com.ufsic.core.beans;

import java.io.Serializable;
import java.util.ArrayList;

import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.KeywordsBean.Result;
import com.ufsic.core.utils.ToolBox;


public class KeywordsBean extends BaseBean<ArrayList<Result>> {

	public final static String KIND_APP = "APP";
	public final static String KIND_EMAIL = "EMAIL";
	
	public static Result findByKeyword(ArrayList<Result> list, String keyword) {
		
		Result ret = null;
		if (list!=null) {
			for (Result result: list) {
				if (result!=null && !ToolBox.isEmpty(result.getKeyword()) && 
				    result.getKeyword().equalsIgnoreCase(keyword)) {
					ret = result;
					break;
				}
			}
		}
		return ret;
	}
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = 5797584045389875646L;
		
		public final static String TABLE_NAME = "keywords_bean";
		
		public final static String DB_KEYWORD = "keyword";
		public final static String DB_EMAIL = "email";
		public final static String DB_APP = "app";
		public final static String DB_SMS = "sms";
		
		@DatabaseField(columnName = DB_KEYWORD, id = true)
		private String keyword;
		
		@DatabaseField(columnName = DB_EMAIL) 
		private Boolean email;
		
		@DatabaseField(columnName = DB_APP) 
		private Boolean app;
		
		@DatabaseField(columnName = DB_SMS) 
		private Boolean sms;
		
		public String getKeyword() {
			return keyword;
		}

		public void setKeyword(String keyword) {
			this.keyword = keyword;
		}
		
		public Boolean getEmail() {
			return email;
		}
		
		public void setEmail(Boolean email) {
			this.email = email;
		}

		public Boolean getApp() {
			return app;
		}
		
		public void setApp(Boolean app) {
			this.app = app;
		}

		public Boolean getSms() {
			return sms;
		}
		
		public void setSms(Boolean sms) {
			this.sms = sms;
		}
		
		
	}
}
