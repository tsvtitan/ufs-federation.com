package com.ufsic.core.beans;

import java.io.Serializable;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashSet;
import java.util.Locale;
import java.util.TreeSet;

import com.j256.ormlite.field.DataType;
import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.NewsBean.Result;


public class NewsBean extends BaseBean<ArrayList<Result>> {
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = 4935386733313924884L;

		public final static String TABLE_NAME = "news_bean";
		
		public final static String DB_ID = "id";
		public final static String DB_ACTUAL = "actual";
		public final static String DB_TITLE = "title";
		public final static String DB_TEXT = "text";
		public final static String DB_DATE = "date";
		public final static String DB_DATE_STR = "date_str";
		public final static String DB_CATEGORY_ID = "category_id";
		public final static String DB_SUBCATEGORY_ID = "subcategory_id";
		public final static String DB_EXPIRED = "expired";
		public final static String DB_IMAGE_URLS = "image_urls";
		public final static String DB_FILE_URLS = "file_urls";
		public final static String DB_RELATED_LINKS = "related_links";
		/* tsv */
		public final static String DB_KEYWORDS = "keywords";
		public final static String DB_MATCHES = "matches";
		/* tsv */
		
		private static SimpleDateFormat dateFormat = new SimpleDateFormat("d MMMM yyyy", Locale.getDefault());
		
		@DatabaseField(columnName = DB_ID, id = true)
		private String id;
		@DatabaseField(columnName = DB_ACTUAL)
		private int actual;
		@DatabaseField(columnName = DB_TITLE)
		private String title;
		@DatabaseField(columnName = DB_TEXT)
		private String text;
		@DatabaseField(columnName = DB_DATE)
		private long date;
		@DatabaseField(columnName = DB_DATE_STR)
		private String dateStr;
		@DatabaseField(columnName = DB_CATEGORY_ID)
		private String categoryID;
		@DatabaseField(columnName = DB_SUBCATEGORY_ID)
		private String subcategoryID;
		@DatabaseField(columnName = DB_EXPIRED)
		private long expired;
		@DatabaseField(columnName = DB_IMAGE_URLS, dataType = DataType.SERIALIZABLE)
		private ArrayList<Url> imageUrls;
		@DatabaseField(columnName = DB_FILE_URLS, dataType = DataType.SERIALIZABLE)
		private ArrayList<Url> fileUrls;
		@DatabaseField(columnName = DB_RELATED_LINKS, dataType = DataType.SERIALIZABLE)
		private ArrayList<Link> relatedLinks;
		/* tsv */
		@DatabaseField(columnName = DB_KEYWORDS, dataType = DataType.SERIALIZABLE)
		private ArrayList<String> keywords;
		@DatabaseField(columnName = DB_MATCHES, dataType = DataType.SERIALIZABLE)
		private TreeSet<String> matches;
		/* tsv */
		
		public String getId() {
			return id;
		}
		
		public void setId(String id) {
			this.id = id;
		}

		public int getActual() {
			return actual;
		}

		public void setActual(String actual) {
			this.actual = Integer.valueOf(actual);
		}

		public String getTitle() {
			return title;
		}

		public void setTitle(String title) {
			this.title = title;
		}

		public String getText() {
			return text;
		}

		public void setText(String text) {
			this.text = text;
		}

		public long getDate() {
			return date;
		}

		public void setDate(String date) {
			this.date = Long.valueOf(date);
			setDateStr(this.date);
		}
		
		public String getDateStr() {
			return dateStr;
		}

		public void setDateStr(long dateStr) {
			this.dateStr = dateFormat.format(new Date(dateStr * 1000));
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

		public long getExpired() {
			return expired;
		}

		public void setExpired(String expired) {
			this.expired = Long.valueOf(expired) * 1000;
		}
		
		public ArrayList<Url> getImageUrls() {
			return imageUrls;
		}

		public void setImageUrls(ArrayList<Url> imageUrls) {
			this.imageUrls = imageUrls;
		}

		public ArrayList<Url> getFileUrls() {
			return fileUrls;
		}

		public void setFileUrls(ArrayList<Url> fileUrls) {
			this.fileUrls = fileUrls;
		}

		public ArrayList<Link> getRelatedLinks() {
			return relatedLinks;
		}

		public void setRelatedLinks(ArrayList<Link> relatedLinks) {
			this.relatedLinks = relatedLinks;
		}
		
		/* tsv */
		public ArrayList<String> getKeywords() {
			return keywords;
		}

		public void setKeywords(ArrayList<String> keywords) {
			this.keywords = keywords;
		}
		
		public TreeSet<String> getMatches() {
			return matches;
		}

		public void setMatches(TreeSet<String> matches) {
			this.matches = matches;
		}
		/* tsv */
	}
	
	public static class Url implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = -3878573661591877426L;
		
		private String name;
		private String url;
		private String extension;
		//private long size;
		/* tsv */private Long size;
		
		public String getName() {
			return name;
		}
		
		public void setName(String name) {
			this.name = name;
		}

		public String getUrl() {
			return url;
		}

		public void setUrl(String url) {
			this.url = url;
		}

		public String getExtension() {
			return extension;
		}

		public void setExtension(String extension) {
			this.extension = extension;
		}

		/*
		public long getSize() {
			return size;
		}
		*/
		/* tsv */
		public Long getSize() {
			return size;
		}
		/* tsv */

		public void setSize(String size) {
			
			//this.size = Long.valueOf(size);
			/* tsv */
			try {
				this.size = Long.valueOf(size);
			} catch (Exception e) {
				this.size = null;
			}
			/* tsv */
		}
	}
	
	public static class Link implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = -8565192165725638000L;

		private static SimpleDateFormat dateFormat = new SimpleDateFormat("d MMMM yyyy", Locale.getDefault());
		
		private String id;
		private String name;
		private String date;
		private String categoryID;
		private String subcategoryID;
		
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

		public String getDate() {
			return date;
		}

		public void setDate(long date) {
			this.date = dateFormat.format(new Date(date * 1000));
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
	}
}
