package com.ufsic.core.utils;

import java.io.Closeable;
import java.io.IOException;
import java.util.Arrays;
import java.util.Collection;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import android.content.Context;
import android.os.Bundle;

public class ToolBox {

	public static boolean isEmpty(String str) {
		return str == null || str.length() == 0;
	}
	
	public static <E> boolean isEmpty(Collection<E> collection) {
		return collection == null || collection.isEmpty();
	}
	
	public static boolean contains(Bundle in, String key) {
		return in != null && in.containsKey(key);
	}
	
	public static boolean contains(Object[] array, Object key) {
		if(array == null)
			return false;
		
		for(Object cur: array)
			if(cur.equals(key))
				return true;
		
		return false;
	}
	
	public static void quietlyClose(Closeable in) {
		try {
			if(in != null)
				in.close();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
	
	public static int dp2pix(Context context, float dp) {
		return (int) (dp * context.getResources().getDisplayMetrics().density);
	}
	
	public static int pix2dp(Context context, float pix) {
		return (int) (pix / context.getResources().getDisplayMetrics().density);
	}
	
	/* tsv */
	public static <T> T[] concat(T[] first, T[] second) {
	    T[] result = Arrays.copyOf(first, first.length + second.length);
	    System.arraycopy(second, 0, result, first.length, second.length);
	    return result;
	}
	
	public static boolean isEmail(String s) {
		  
	    boolean ret = false;
	    if (!isEmpty(s)) {
	      Pattern ep = Pattern.compile("^[(a-zA-Z-0-9-\\_\\+\\.)]+@[(a-z-A-z)]+\\.[(a-zA-z)]{2,3}$");
	      Matcher matcher = ep.matcher(s);
	      ret = matcher.matches();
	    }
	    return ret;
	  }

	public static boolean isPhone(String s) {
	  
	    boolean ret = false;
	    if (!isEmpty(s)) {
	      //Pattern ep = Pattern.compile("^\\+[0-9]{2,3}+-[0-9]{10}$");
	      Pattern ep = Pattern.compile("^\\+[0-9]{11}$");
	      Matcher matcher = ep.matcher(s);
	      ret = matcher.matches();
	    }
	    return ret;
	  }
	/* tsv */
}
