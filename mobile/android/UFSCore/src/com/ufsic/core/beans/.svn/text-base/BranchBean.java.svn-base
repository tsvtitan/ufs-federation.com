package com.ufsic.core.beans;

import java.io.Serializable;
import java.util.ArrayList;

import com.j256.ormlite.field.DataType;
import com.j256.ormlite.field.DatabaseField;
import com.j256.ormlite.table.DatabaseTable;
import com.ufsic.core.beans.BranchBean.Result;


public class BranchBean extends BaseBean<ArrayList<Result>> {
	
	@DatabaseTable(tableName = Result.TABLE_NAME)
	public static class Result implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = -2391864127001445735L;

		public static final String TABLE_NAME = "branch_bean";
		
		public static final String DB_ID = "id";
		public static final String DB_NAME = "name";
		public static final String DB_REGION = "region";
		public static final String DB_CITY = "city";
		public static final String DB_ADDRESS = "address";
		public static final String DB_LATITUDE = "latitude";
		public static final String DB_LONGITUDE = "longitude";
		public static final String DB_EXPIRED = "expired";
		public static final String DB_CONTACTS = "contacts";
		
		@DatabaseField(columnName = DB_ID, id = true)
		private String id;
		@DatabaseField(columnName = DB_NAME)
		private String name;
		@DatabaseField(columnName = DB_REGION)
		private String region;
		@DatabaseField(columnName = DB_CITY)
		private String city;
		@DatabaseField(columnName = DB_ADDRESS)
		private String address;
		@DatabaseField(columnName = DB_LATITUDE)
		private double latitude;
		@DatabaseField(columnName = DB_LONGITUDE)
		private double longitude;
		@DatabaseField(columnName = DB_EXPIRED)
		private long expired;
		@DatabaseField(columnName = DB_CONTACTS, dataType = DataType.SERIALIZABLE)
		private ArrayList<Contact> contacts;
		
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

		public String getRegion() {
			return region;
		}

		public void setRegion(String region) {
			this.region = region;
		}

		public String getCity() {
			return city;
		}

		public void setCity(String city) {
			this.city = city;
		}

		public String getAddress() {
			return address;
		}

		public void setAddress(String address) {
			this.address = address;
		}

		public double getLatitude() {
			return latitude;
		}

		public void setLatitude(String latitude) {
			this.latitude = Double.valueOf(latitude);
		}

		public double getLongitude() {
			return longitude;
		}

		public void setLongitude(String longitude) {
			this.longitude = Double.valueOf(longitude);
		}

		public long getExpired() {
			return expired;
		}

		public void setExpired(String expired) {
			this.expired = Long.valueOf(expired);
		}

		public ArrayList<Contact> getContacts() {
			return contacts;
		}

		public void setContacts(ArrayList<Contact> contacts) {
			this.contacts = contacts;
		}
	}
	
	public static class Contact implements Serializable {
		
		/**
		 * 
		 */
		private static final long serialVersionUID = -7691040107319663725L;
		
		private String title;
		private String value;
		
		public String getTitle() {
			return title;
		}
		
		public void setTitle(String title) {
			this.title = title;
		}

		public String getValue() {
			return value;
		}

		public void setValue(String value) {
			this.value = value;
		}
	}
}
