package com.ufsic.core.beans;

import java.util.ArrayList;

import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.GroupDetailBean.Result;


public class GroupDetailBean extends BaseBean<ArrayList<Result>> {
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result extends NewsBean.Result {

		/**
		 * 
		 */
		private static final long serialVersionUID = -7419147115286317519L;
		
		public final static String TABLE_NAME = "group_detail_bean";
	}
}
