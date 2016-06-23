package ru.ideast.ufs.db;

import java.sql.SQLException;

import ru.ideast.ufs.beans.ActivityBean;
import ru.ideast.ufs.beans.BranchBean;
import ru.ideast.ufs.beans.CategoryBean;
import ru.ideast.ufs.beans.GroupBean;
import ru.ideast.ufs.beans.GroupDetailBean;
import ru.ideast.ufs.beans.HtmlBean;
import ru.ideast.ufs.beans.NewsBean;
import ru.ideast.ufs.beans.TableBean;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;

import com.j256.ormlite.android.apptools.OrmLiteSqliteOpenHelper;
import com.j256.ormlite.support.ConnectionSource;
import com.j256.ormlite.table.TableUtils;

public class InnerDatabaseHelper extends OrmLiteSqliteOpenHelper {
	
	private static final int DATABASE_VERSION = 1;
	
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
