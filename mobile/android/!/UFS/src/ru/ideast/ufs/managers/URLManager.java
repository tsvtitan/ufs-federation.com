package ru.ideast.ufs.managers;

import android.app.Activity;
import android.content.Context;
import android.content.pm.PackageManager.NameNotFoundException;

import org.OpenUDID.*;

public class URLManager {

	/* tsv *///private static final String ROOT_URL = "http://ru.new.ufs-federation.com:8080";
	/* tsv */private static final String ROOT_URL = "https://ru.ufs-federation.com";
	/* tsv */private static final String BASE_URL = ROOT_URL.concat("/mobile");
	
	//private static final String BASE_URL = "https://ru.ufs-federation.com/mobile/";
	//private static final String AUTHORIZATION = BASE_URL.concat("auth?madeBy=%s&deviceModel=%s&screenSize=%s&os=%s");
	/* tsv */private static final String AUTHORIZATION = BASE_URL.concat("/auth?madeBy=%s&deviceModel=%s&screenSize=%s&os=%s&id=%s&version=%s");
	private static final String GET_CATEGORIES = BASE_URL.concat("/getCategories?token=%s");
	private static final String GET_NEWS = BASE_URL.concat("/getNews?token=%s&categoryID=%s&subcategoryID=%s&newsID=%s&timestamp=%d&offset=%d");
	private static final String GET_GROUPS = BASE_URL.concat("/getGroups?token=%s&subcategoryID=%s");
	private static final String GET_GROUPS_DETAIL = BASE_URL.concat("/getNews?token=%s&categoryID=%s&subcategoryID=%s&newsID=%s");
	private static final String GET_BRANCHES = BASE_URL.concat("/getBranches?token=%s");
	private static final String GET_DATES_OF_NEWS = BASE_URL.concat("/getDatesOfNews?token=%s&categoryID=%s&subcategoryID=%s");
	private static final String GET_NEWS_FILTER = BASE_URL.concat("/getNews?token=%s&categoryID=%s&subcategoryID=%s&newsID=%s&timestamp=%d&limitDateTime=%d");
	private static final String GET_TABLES = BASE_URL.concat("/getTableView?token=%s&subcategoryID=%s");
	private static final String GET_ACTIVITIES = BASE_URL.concat("/getActivities?token=%s&categoryID=%s");
	private static final String GET_HTML = BASE_URL.concat("/getHtml?token=%s&categoryID=%s&subcategoryID=%s");
	/* tsv */
	private static final String QRCODE = BASE_URL.concat("/qrcode?token=%s&text=%s");
	private static final String PROMOTION = BASE_URL.concat("/promotion?token=%s&promotionID=%s&accepted=%s");
	/* tsv */
	
	/* tsv */
	public static String getRootUrl() {
		return ROOT_URL;
	}
	/* tsv */
	
	public static String authorization(Activity activity) {
		String madeBy = android.os.Build.BRAND;
		madeBy = madeBy.replace(" ", "_");
		String deviceModel = android.os.Build.MODEL;
		deviceModel = deviceModel.replace(" ", "_");
		
		String screenSize = activity.getWindowManager().getDefaultDisplay().getHeight() + "x" + activity.getWindowManager().getDefaultDisplay().getWidth();
		String os = "android_" + android.os.Build.VERSION.RELEASE;
		
		/* tsv  */
		String id = "Unknown id";
		if (OpenUDID_manager.isInitialized()) {
			id = OpenUDID_manager.getOpenUDID();
		}
		
		String version = "Unknown version";
		Context context = activity.getApplicationContext();
		if (context!=null) {
			try {
				version = context.getPackageManager().getPackageInfo(context.getPackageName(),0).versionName;
			} catch (NameNotFoundException e) {
				//
			}
		}
		/* tsv */
		
		return String.format(AUTHORIZATION, madeBy, deviceModel, screenSize, os, id, version);
	}
	
	public static String getCategories(String token) {
		return String.format(GET_CATEGORIES, token);
	}
	
	public static String getNews(String token, String categoryId, String subcategoryId, String newsId, long timestamp, int offset) {
		return String.format(GET_NEWS, token, categoryId, subcategoryId, newsId, timestamp, offset);
	}
	
	public static String getGroups(String token, String subcategoryId) {
		return String.format(GET_GROUPS, token, subcategoryId);
	}
	
	public static String getGroupsDetail(String token, String categoryId, String subcategoryId, String newsId) {
		return String.format(GET_GROUPS_DETAIL, token, categoryId, subcategoryId, newsId);
	}
	
	public static String getBranches(String token) {
		return String.format(GET_BRANCHES, token);
	}
	
	public static String getDatesOfNews(String token, String categoryId, String subcategoryId) {
		return String.format(GET_DATES_OF_NEWS, token, categoryId, subcategoryId);
	}
	
	public static String getNewsFilter(String token, String categoryId, String subcategoryId, String newsId, long timestamp, long limitDateTime) {
		return String.format(GET_NEWS_FILTER, token, categoryId, subcategoryId, newsId, timestamp, limitDateTime);
	}
	
	public static String getTables(String token, String subcategoryId) {
		return String.format(GET_TABLES, token, subcategoryId);
	}
	
	public static String getActivities(String token, String categoryId) {
		return String.format(GET_ACTIVITIES, token, categoryId);
	}
	
	public static String getHtml(String token, String categoryId, String subcategoryId) {
		return String.format(GET_HTML, token, categoryId, subcategoryId);
	}
	
	public static String getFile(String postfix) {
		return BASE_URL.concat(postfix);
	}
	
	/* tsv */
	
	public static String getQRCode(String token, String text) {
		return String.format(QRCODE, token, text);
	}
	
	public static String getPromotion(String token, String promotionID, String accepted) {
		return String.format(PROMOTION, token, promotionID, accepted);
	}
	
	/* tsv */
}
