package ru.ideast.ufs.utils;

import java.io.Closeable;
import java.io.IOException;
import java.util.Collection;

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
}
