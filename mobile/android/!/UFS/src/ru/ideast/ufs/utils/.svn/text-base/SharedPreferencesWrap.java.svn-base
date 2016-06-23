package ru.ideast.ufs.utils;

import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

public enum SharedPreferencesWrap {

	INSTANCE;

	private final static String SP_NAME = "sp";
	
	public final static String TOKEN = "token";
	public final static String TOKEN_TIME = "tokenTime";

	private SharedPreferences sp;

	public void init(Context context) {
		sp = context.getApplicationContext().getSharedPreferences(SP_NAME, Context.MODE_PRIVATE);
	}

	public boolean contains(String key) {
		return sp.contains(key);
	}

	public boolean containsAll(String... keys) {
		for(String key: keys)
			if(!sp.contains(key))
				return false;
		return true;
	}

	public void putInt(String key, int value) {
		Editor editor = sp.edit();
		editor.putInt(key, value);
		editor.commit();
	}

	public void putLong(String key, long value) {
		Editor editor = sp.edit();
		editor.putLong(key, value);
		editor.commit();
	}

	public void putString(String key, String value) {
		Editor editor = sp.edit();
		editor.putString(key, value);
		editor.commit();
	}

	/**
	 *
	 * @param key - имя ключа
	 * @return значение по ключу или 0, если ключ не существует
	 */
	public int getInt(String key) {
		return sp.getInt(key, 0);
	}

	/**
	 *
	 * @param key - имя ключа
	 * @param defValue - значение по-умолчанию
	 * @return значение по ключу или значение по-умолчанию, если ключ не существует
	 */
	public int getInt(String key, int defValue) {
		return sp.getInt(key, defValue);
	}

	/**
	 *
	 * @param key - имя ключа
	 * @return значение по ключу или 0, если ключ не существует
	 */
	public long getLong(String key) {
		return sp.getLong(key, 0);
	}

	/**
	 *
	 * @param key - имя ключа
	 * @param defValue - значение по-умолчанию
	 * @return значение по ключу или значение по-умолчанию, если ключ не существует
	 */
	public long getLong(String key, long defValue) {
		return sp.getLong(key, defValue);
	}

	/**
	 *
	 * @param key - имя ключа
	 * @return значение по ключу или пустая строка, если ключ не существует
	 */
	public String getString(String key) {
		return sp.getString(key, "");
	}

	/**
	 *
	 * @param key - имя ключа
	 * @param defValue - значение по-умолчанию
	 * @return значение по ключу или значение по-умолчанию, если ключ не существует
	 */
	public String getString(String key, String defValue) {
		return sp.getString(key, defValue);
	}
}
