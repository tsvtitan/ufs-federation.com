package com.ufsic.core.managers;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.List;
import java.util.Set;

import android.app.Activity;
import android.content.Context;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageManager;
import android.content.pm.PackageManager.NameNotFoundException;
import android.content.res.Resources;

import org.OpenUDID.*;

import com.ufsic.core.utils.ToolBox;

import com.ufsic.core.R;

public class URLManager {

	/* tsv */private static final String WEB_URL = "http://ru.ufs-federation.com";
	
	/* tsv */
	//private static final String ROOT_URL = "http://ru.dev1.ufs-federation.com:8080"; // test
	private static final String ROOT_URL = "https://ru.ufs-federation.com"; // work
	
	private static final String BASE_PATH = ROOT_URL.concat("/MobileGate"); 
	private static final String BASE_URL = BASE_PATH.concat("/"); 
	/* tsv */
	
	/* tsv */private static final String AUTHORIZATION = BASE_URL.concat("auth?madeBy=%s&deviceModel=%s&screenSize=%s&os=%s&id=%s&version=%s&company=%s");
	private static final String GET_CATEGORIES = BASE_URL.concat("getCategories?token=%s");
	private static final String GET_NEWS = BASE_URL.concat("getNews?token=%s&categoryID=%s&subcategoryID=%s&newsID=%s&timestamp=%d&offset=%d");
	private static final String GET_GROUPS = BASE_URL.concat("getGroups?token=%s&subcategoryID=%s");
	private static final String GET_GROUPS_DETAIL = BASE_URL.concat("getNews?token=%s&categoryID=%s&subcategoryID=%s&newsID=%s");
	private static final String GET_BRANCHES = BASE_URL.concat("getBranches?token=%s");
	private static final String GET_DATES_OF_NEWS = BASE_URL.concat("getDatesOfNews?token=%s&categoryID=%s&subcategoryID=%s");
	private static final String GET_NEWS_FILTER = BASE_URL.concat("getNews?token=%s&categoryID=%s&subcategoryID=%s&newsID=%s&timestamp=%d&limitDateTime=%d");
	private static final String GET_TABLES = BASE_URL.concat("getTableView?token=%s&subcategoryID=%s");
	private static final String GET_ACTIVITIES = BASE_URL.concat("getActivities?token=%s&categoryID=%s");
	private static final String GET_HTML = BASE_URL.concat("getHtml?token=%s&categoryID=%s&subcategoryID=%s");
	/* tsv */
	private static final String QRCODE = BASE_URL.concat("qrcode?token=%s&text=%s");
	private static final String PROMOTION = BASE_URL.concat("promotion?token=%s&promotionID=%s&accepted=%s&name=%s&phone=%s&email=%s&brokerage=%s&yield=%s");
	private static final String KEYWORDS = BASE_URL.concat("keywords?token=%s");
	private static final String KEYWORDS_NEW = BASE_URL.concat("keywords?token=%s");
	private static final String KEYWORDS_FINISH = BASE_URL.concat("keywords?token=%s&finish=on");
	private static final String VALIDATION = BASE_URL.concat("validation?token=%s&text=%s");
	
	private static final String URL_ENCODE_CHARSET = "UTF-8";
	
	public static String getWebUrl() {
		return WEB_URL;
	}
	
	public static String getRootUrl() {
		return ROOT_URL;
	}
	
	public static String getBaseUrl() {
		return BASE_URL;
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
		String company = "Unknown company";
				
		Context context = activity.getApplicationContext();
		if (context!=null) {
			
			String packageName = context.getPackageName();
			System.out.println(packageName);
			
			try {
				version = context.getPackageManager().getPackageInfo(packageName,0).versionName;
			} catch (NameNotFoundException e) {
				//
			}
			
			try {
				
				ApplicationInfo ai = context.getPackageManager().getApplicationInfo(packageName, PackageManager.GET_META_DATA);
				if (ai!=null) {
					company = ai.metaData.getString("company");
				}
				
			} catch (NameNotFoundException e) {
				//
			}
			
			//Resources res = context.getResources();
			//company = res.getString(R.string.company);
		}
		
		return String.format(AUTHORIZATION, madeBy, deviceModel, screenSize, os, id, version, company);
		
		/* tsv */
		
		//return String.format(AUTHORIZATION, madeBy, deviceModel, screenSize, os, id, version);
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
		return BASE_PATH.concat(postfix);
	}
	
	/* tsv */
	
	public static String getQRCode(String token, String text) {
		return String.format(QRCODE, token, text);
	}
	
	public static String getPromotion(String token, String promotionID, Boolean accepted, 
									  String name, String phone, String email, Boolean brokerage, Boolean yield) {
		return String.format(PROMOTION, token, promotionID, (accepted!=null)?accepted.toString():"",
				             name, phone, email, (brokerage!=null)?brokerage.toString():"", (yield!=null)?yield.toString():"");
	}
	
	public static String getKeywords(String token) {
		return String.format(KEYWORDS, token);
	}
	
	private static String getKeywords(String token, String format, List<String> keywords) {
		
		StringBuilder sb = new StringBuilder();
		sb.append(String.format(format,token));
		for (String s: keywords) {
			try {
			  s = URLEncoder.encode(s,URL_ENCODE_CHARSET);	
			  sb.append("&keyword=").append(s);
			} catch (UnsupportedEncodingException e) {
			  //
			}
		}
		return sb.toString();
	}
	
	public static String getKeywordsFinish(String token, List<String> keywords) {
		
		return getKeywords(token,KEYWORDS_FINISH,keywords);
	}
	
    public static String getKeywordsNew(String token, List<String> keywords, Set<String> kinds, String email) {
		
    	StringBuilder sb = new StringBuilder();
    	sb.append(getKeywords(token,KEYWORDS_NEW,keywords));
    	for (String s: kinds) {
    		sb.append("&kind=").append(s);
    	}
    	if (!ToolBox.isEmpty(email)) {
    		sb.append("&email=").append(email);
    	}
    	sb.append("&app=on");
		return sb.toString();
	}
	
	public static String getValidation(String token, String text) {
		return String.format(VALIDATION, token, text);
	}
	
	/* tsv */
}
