package ru.ideast.ufs.db;

import java.util.ArrayList;
import java.util.concurrent.Callable;

import ru.ideast.ufs.beans.GroupBean;
import ru.ideast.ufs.beans.HtmlBean;
import ru.ideast.ufs.beans.NewsBean;
import ru.ideast.ufs.beans.TableBean;
import ru.ideast.ufs.utils.ToolBox;
import android.content.Context;

import com.j256.ormlite.dao.Dao;
import com.j256.ormlite.stmt.DeleteBuilder;
import com.j256.ormlite.stmt.QueryBuilder;
import com.j256.ormlite.table.TableUtils;

public enum DatabaseManager {
	
	INSTANCE;
	
	private InnerDatabaseHelper innerDBHelper;
	
	public void init(Context context) {
		innerDBHelper = new InnerDatabaseHelper(context);
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////
////
////TODO	ФУНКЦИИ ДОБАВЛЕНИЯ
////
////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public <T> void addObject(final T dataObject, Class<T> clazz) {
		if(dataObject == null)
			return;
		
		try {
			Dao<T, Integer> dao = innerDBHelper.getDao(clazz);
			dao.createIfNotExists(dataObject);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public <T> void AddList(final ArrayList<T> dataList, Class<T> clazz) {
		if(ToolBox.isEmpty(dataList))
			return;
		
		try {
			final Dao<T, Integer> dao = innerDBHelper.getDao(clazz);
			dao.callBatchTasks(new Callable<Void>() {
				@Override
				public Void call() throws Exception {
					
					int size = dataList.size();
					for (int i = 0; i < size; i++)
						dao.createIfNotExists(dataList.get(i));
					
					return null;
				}
			});
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public void addGroupBeanList(final ArrayList<GroupBean.Result> groupBeanList) {
		if(ToolBox.isEmpty(groupBeanList))
			return;
		
		try {
			final Dao<GroupBean.Result, Integer> dao = innerDBHelper.getDao(GroupBean.Result.class);
			dao.callBatchTasks(new Callable<Void>() {
				@Override
				public Void call() throws Exception {
					
					int parentSize = groupBeanList.size();
					for (int i = 0; i < parentSize; i++) {
						GroupBean.Result parent = groupBeanList.get(i);
						
						int childSize = parent.getItems().size();
						if(childSize > 1) {
							for (int j = 0; j < childSize; j++) {
								GroupBean.Item child = parent.getItems().get(j);
								
								//складываем актуальные группы в отдельный лист
								if(child.getActual() == 1)
									parent.getItemsActual().add(child);
							}
						}
						
						dao.createIfNotExists(parent);
					}
					
					return null;
				}
			});
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////
////
////TODO	ФУНКЦИИ ИЗВЛЕЧЕНИЯ
////
////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public <T> T getObject(int id, Class<T> clazz) {
		try {
			Dao<T, Integer> dao = innerDBHelper.getDao(clazz);
			return dao.queryForId(id);
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
	}
	
	public <T> ArrayList<T> getList(Class<T> clazz) {
		try {
			Dao<T, Integer> dao = innerDBHelper.getDao(clazz);
			return (ArrayList<T>) dao.queryForAll();
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
	}
	
	public ArrayList<NewsBean.Result> getNewsBean(String categoryId, String subcategoryId) {
		try {
			Dao<NewsBean.Result, Integer> dao = innerDBHelper.getDao(NewsBean.Result.class);
			QueryBuilder<NewsBean.Result, Integer> queryBuilder = dao.queryBuilder();
			queryBuilder.where().eq(NewsBean.Result.DB_CATEGORY_ID, categoryId).and().eq(NewsBean.Result.DB_SUBCATEGORY_ID, subcategoryId);
			return (ArrayList<NewsBean.Result>) queryBuilder.query();
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
	}
	
	public ArrayList<TableBean.Table> getTableBean(int subcategoryId) {
		try {
			Dao<TableBean.Table, Integer> dao = innerDBHelper.getDao(TableBean.Table.class);
			QueryBuilder<TableBean.Table, Integer> queryBuilder = dao.queryBuilder();
			queryBuilder.where().eq(TableBean.Table.DB_SUBCATEGORY_ID, subcategoryId);
			return (ArrayList<TableBean.Table>) queryBuilder.query();
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
	}
	
	public HtmlBean.Result getHtmlBean(String categoryId, String subcategoryId) {
		try {
			Dao<HtmlBean.Result, Integer> dao = innerDBHelper.getDao(HtmlBean.Result.class);
			QueryBuilder<HtmlBean.Result, Integer> queryBuilder = dao.queryBuilder();
			queryBuilder.where().eq(HtmlBean.Result.DB_CATEGORY_ID, categoryId).and().eq(HtmlBean.Result.DB_SUBCATEGORY_ID, subcategoryId);
			return queryBuilder.queryForFirst();
		} catch (Exception e) {
			throw new RuntimeException(e);
		}
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////
////
////TODO	ФУНКЦИИ ОЧИСТКИ
////
////////////////////////////////////////////////////////////////////////////////////////////////////
	
	public <T> void deleteForId(int id, Class<T> clazz) {
		try {
			Dao<T, Integer> dao = innerDBHelper.getDao(clazz);
			dao.deleteById(id);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public <T> void clearTable(Class<T> clazz) {
		try {
			TableUtils.clearTable(innerDBHelper.getConnectionSource(), clazz);
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public void deleteNewsBean(String categoryId, String subcategoryId) {
		try {
			Dao<NewsBean.Result, Integer> dao = innerDBHelper.getDao(NewsBean.Result.class);
			DeleteBuilder<NewsBean.Result, Integer> deleteBuilder = dao.deleteBuilder();
			deleteBuilder.where().eq(NewsBean.Result.DB_CATEGORY_ID, categoryId).and().eq(NewsBean.Result.DB_SUBCATEGORY_ID, subcategoryId);
			deleteBuilder.delete();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public void deleteTableBean(int subcategoryId) {
		try {
			Dao<TableBean.Table, Integer> dao = innerDBHelper.getDao(TableBean.Table.class);
			DeleteBuilder<TableBean.Table, Integer> deleteBuilder = dao.deleteBuilder();
			deleteBuilder.where().eq(TableBean.Table.DB_SUBCATEGORY_ID, subcategoryId);
			deleteBuilder.delete();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
	
	public void deleteHtmlBean(String categoryId, String subcategoryId) {
		try {
			Dao<HtmlBean.Result, Integer> dao = innerDBHelper.getDao(HtmlBean.Result.class);
			DeleteBuilder<HtmlBean.Result, Integer> deleteBuilder = dao.deleteBuilder();
			deleteBuilder.where().eq(HtmlBean.Result.DB_CATEGORY_ID, categoryId).and().eq(HtmlBean.Result.DB_SUBCATEGORY_ID, subcategoryId);
			deleteBuilder.delete();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}
