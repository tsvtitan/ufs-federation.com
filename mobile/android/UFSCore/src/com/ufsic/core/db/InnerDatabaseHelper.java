package com.ufsic.core.db;

import java.sql.SQLException;


import android.content.Context;
import android.database.sqlite.SQLiteDatabase;

import com.j256.ormlite.android.apptools.OrmLiteSqliteOpenHelper;
import com.j256.ormlite.support.ConnectionSource;
import com.j256.ormlite.table.TableUtils;
import com.ufsic.core.beans.ActivityBean;
import com.ufsic.core.beans.BranchBean;
import com.ufsic.core.beans.CategoryBean;
import com.ufsic.core.beans.GroupBean;
import com.ufsic.core.beans.GroupDetailBean;
import com.ufsic.core.beans.HtmlBean;
import com.ufsic.core.beans.KeywordsBean;
import com.ufsic.core.beans.NewsBean;
import com.ufsic.core.beans.TableBean;

public class InnerDatabaseHelper extends OrmLiteSqliteOpenHelper {
	
	private static final int DATABASE_VERSION = 2;
	
	// все классы, добавляемые в базу
	private static final Class<?>[] CLASSES = new Class[] {
		CategoryBean.Result.class,
		NewsBean.Result.class,
		GroupBean.Result.class,
		GroupDetailBean.Result.class,
		BranchBean.Result.class,
		TableBean.Table.class,
		ActivityBean.Result.class,
		HtmlBean.Result.class
		/* tsv */
		,
		KeywordsBean.Result.class
		/* tsv */
	};
	
	public InnerDatabaseHelper(Context context) {
		super(context, "inner", null, DATABASE_VERSION);
	}

	@Override
	public void onCreate(SQLiteDatabase db, ConnectionSource connection) {
		try {
			for(Class<?> clazz: CLASSES) {
				// создаем таблицу для класса
				TableUtils.createTable(connection, clazz);
			}
		} 
		catch (SQLException e) {
			throw new RuntimeException(e);
		}
	}

	@Override
	public void onUpgrade(SQLiteDatabase db, ConnectionSource connection, int oldVersion, int newVersion) {
		try {
			for(Class<?> clazz: CLASSES) {
				// удаляем таблицу класса
				TableUtils.dropTable(connection, clazz, true);
			}
			onCreate(db, connection);
		} 
		catch (SQLException e) {
			throw new RuntimeException(e);
		}
	}
}
