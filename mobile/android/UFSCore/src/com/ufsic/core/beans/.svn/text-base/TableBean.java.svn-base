package com.ufsic.core.beans;

import java.io.Serializable;
import java.util.ArrayList;

import com.j256.ormlite.field.DataType;
import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.TableBean.Result;


public class TableBean extends BaseBean<Result> {
	
	public static class Result {
		
		private int subcategoryID;
		private About about;
		private ArrayList<Table> tables;
		
		public int getSubcategoryID() {
			return subcategoryID;
		}
		
		public void setSubcategoryID(int subcategoryID) {
			this.subcategoryID = subcategoryID;
		}
		
		public About getAbout() {
			return about;
		}

		public void setAbout(About about) {
			this.about = about;
		}

		public ArrayList<Table> getTables() {
			return tables;
		}

		public void setTables(ArrayList<Table> tables) {
			this.tables = tables;
			
			for (Table table : tables) {
				table.setSubcategoryID(getSubcategoryID());
				table.setDescription(getAbout().getDescription());
			}
		}
	}
	
	public static class About {
		
		private String description;

		public String getDescription() {
			return description;
		}

		public void setDescription(String description) {
			this.description = description;
		}
	}
	
	@DatabaseTable(tableName = Table.TABLE_NAME)
	public static class Table implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = -3492827407222195358L;

		public final static String TABLE_NAME = "table_bean";
		
		public final static String DB_ID = "id";
		public final static String DB_SUBCATEGORY_ID = "subcategory_id";
		public final static String DB_DESCRIPTION = "description";
		public final static String DB_NAME = "name";
		public final static String DB_ABOUT = "about";
		public final static String DB_EXPIRED = "expired";
		public final static String DB_COLUMNS = "columns";
		public final static String DB_ALIGNMENTS = "alignments";
		public final static String DB_VALUES = "values";
		/* tsv */public final static String DB_BUY_URLS = "buyUrls";
		
		@DatabaseField(columnName = DB_ID, generatedId = true)
		private int id;
		@DatabaseField(columnName = DB_SUBCATEGORY_ID)
		private int subcategoryID;
		@DatabaseField(columnName = DB_DESCRIPTION)
		private String description;
		@DatabaseField(columnName = DB_NAME)
		private String name;
		@DatabaseField(columnName = DB_ABOUT)
		private String about;
		@DatabaseField(columnName = DB_EXPIRED)
		private long expired;
		@DatabaseField(columnName = DB_COLUMNS, dataType = DataType.SERIALIZABLE)
		private String[] columns;
		@DatabaseField(columnName = DB_ALIGNMENTS, dataType = DataType.SERIALIZABLE)
		private String[] alignments;
		@DatabaseField(columnName = DB_VALUES, dataType = DataType.SERIALIZABLE)
		private ArrayList<String[]> values;
		/* tsv */
		@DatabaseField(columnName = DB_BUY_URLS, dataType = DataType.SERIALIZABLE)
		private String[] buyUrls;
		/* tsv */
		
		public int getId() {
			return id;
		}
		
		public int getSubcategoryID() {
			return subcategoryID;
		}

		public void setSubcategoryID(int subcategoryID) {
			this.subcategoryID = subcategoryID;
		}
		
		public String getDescription() {
			return description;
		}

		public void setDescription(String description) {
			this.description = description;
		}

		public String getName() {
			return name;
		}

		public void setName(String name) {
			this.name = name;
		}

		public String getAbout() {
			return about;
		}

		public void setAbout(String about) {
			this.about = about;
		}

		public long getExpired() {
			return expired;
		}

		public void setExpired(String expired) {
			this.expired = Long.valueOf(expired);
		}

		public String[] getColumns() {
			return columns;
		}

		public void setColumns(String[] columns) {
			this.columns = columns;
		}
		
		public String[] getAlignments() {
			return alignments;
		}

		public void setAlignments(String[] alignments) {
			this.alignments = alignments;
		}

		public ArrayList<String[]> getValues() {
			return values;
		}

		public void setValues(ArrayList<String[]> values) {
			this.values = values;
		}
		
		/* tsv */
		public String[] getBuyUrls() {
			return buyUrls;
		}

		public void setBuyUrls(String[] buyUrls) {
			this.buyUrls = buyUrls;
		}
		/* tsv */
	}
}
