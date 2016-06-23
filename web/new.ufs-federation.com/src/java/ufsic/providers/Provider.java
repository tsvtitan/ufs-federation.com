package ufsic.providers;

import java.lang.reflect.Constructor;
import java.sql.CallableStatement;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.Date;
import java.sql.ParameterMetaData;
import java.sql.Time;
import java.sql.Timestamp;

import java.util.ArrayList;
import java.util.Arrays;

import javax.naming.InitialContext;
import javax.sql.DataSource;

import ufsic.core.CoreObject;
import ufsic.out.Echo;
import ufsic.out.Logger;
import ufsic.providers.Param.Direction;


public class Provider extends CoreObject implements IProvider {
  
  private enum QueryType {
    SELECT, UPDATE, EXECUTE
  }
  
  private class SqlException extends Exception {
    
    private Exception parent = null;
    private String sql = null;
    
    SqlException(Exception parent, String sql) {
      
      this.parent = parent;
      this.sql = sql;
    }
    
    @Override
    public String getMessage() {
      
      String ret = null;
      if (isNotNull(parent)) {
        ret = String.format("%s, sql = %s",parent.getMessage(),sql);
      }
      return ret;
    }
  }
  
  protected final String jndiName;
  protected final int maxRows = 100*1000;
  protected final int maxBlobSize = 10*1024*1024;
  protected final ArrayList<Class> quoteClasses = new ArrayList<>();
  
  protected String recNumFieldName = "RECN";
  
  private DataSource dataSource;
  private Connection connection;
  private double lastDuration = 0;
  private String lastError = "";
  private Exception lastException = null;
  private ArrayList<String> sqlStack = new ArrayList<>();

  public Provider(Echo echo, Logger logger, String jndiName) {

    super(logger,echo);
    this.jndiName = jndiName;
    
    this.quoteClasses.add(String.class);
    this.quoteClasses.add(Date.class);
    this.quoteClasses.add(Time.class);
    this.quoteClasses.add(Timestamp.class);
  }
  
  public Provider(String jndiName) {
    
    this(null,null,jndiName);
  }

  private void setException(Exception e) {
    
    lastException = e;
    if (isNotNull(e)) {
      lastError = e.getMessage();
      logException(e);
    } else {
      lastError = "";
    }
  }
  
  public Exception getLastException() {
    
    return this.lastException;
  }
  
  public Connection getConnection() {
    return connection;
  }
  
  public boolean isConnected() {

    boolean ret = false;
    try {
      if (isNotNull(connection)) {
        ret = !connection.isClosed();
      }
    } catch (Exception e) {
      setException(e);
    }
    return ret;
  }
  
  public void connect() {
    
    disconnect();
    if (!isConnected()) {
      
      try {
        InitialContext initContext = new InitialContext();
        dataSource = (DataSource)initContext.lookup(jndiName);
        if (isNotNull(dataSource)) {
          connection = dataSource.getConnection();
        }
      } catch (Exception e) {
        setException(e);
      }
    }
  }
  
  public void disconnect() {
    
    if (isConnected()) {
      try {
        connection.close();
        connection = null;
      } catch (Exception e) {
        setException(e);
      }        
    }
  }
  
  public boolean checkConnected() {
    
    boolean ret = isConnected();
    if (!ret) {
      connect();
      ret = isConnected();
    }
    return ret;
  }

  public void commit() {
    
    if (isConnected()) {
      try {
        connection.commit();
      } catch (Exception e) {
        setException(e);
      }
    }
  }
  
  public void rollback() {
    
    if (isConnected()) {
      try {
        connection.rollback();
      } catch (Exception e) {
        setException(e);
      }
    }
  }

  protected Class getValueClass(Object object, int type) {
    return Value.class;
  }
  
  protected Object getParameterValue(Object value) {
    
    Object ret = value;
    return ret;
  } 
  
  private Object getParameterValue(Value value) {
    
    Object obj = null;
    if (isNotNull(value) && value.isNotNull()) {
      obj = getParameterValue(value.asObject());
    }
    return obj;
  }

  private Record newRecord(IProviderSelector selector) {
    
    Record ret;
    if (isNotNull(selector)) {
      ret = selector.newRecord();
    } else {
      ret = new Record();
    }
    return ret;
  }
          
  private Object querySelect(String sql, boolean autoCommit, ArrayList<Object> args, IProviderSelector selector) {
    
    Object ret = null;
    setException(null);
    if (checkConnected()) {
      try {
        sqlStack.add(0,sql);
        
        PreparedStatement stmt = null;
        try {
          stmt = connection.prepareStatement(sql);
          if (isNotNull(stmt)) {

            /*if (isNotNull(args)) {
              int index = 1;
              for (Object obj: args) {
                stmt.setObject(index,obj);
                index++;
              }
            } else {*/
            if (isNotNull(args)) {
              ParameterMetaData meta = stmt.getParameterMetaData(); 
              if (isNotNull(meta)) {
                
                int count = meta.getParameterCount();
                for (int i=1;i<(count+1);i++) {
                  Object v = (args.size()<=count)?args.get(i-1):null;
                  v = getParameterValue(v);
                  stmt.setObject(i,v);
                }
              }
            }
          
            ResultSet rs = stmt.executeQuery();
            if (isNotNull(rs)) {

              ResultSetMetaData rsmd = rs.getMetaData();
              if (isNotNull(rsmd)) {

                int count = rsmd.getColumnCount();
                DataSet ds = new DataSet();

                while(rs.next()){

                  Record rec = newRecord(selector);
                  if (isNotNull(rec)) {
                    for (int i=1; i<(count+1); i++) {

                      String name = rsmd.getColumnName(i);
                      Object object = rs.getObject(i);
                      Value value;
                      Class cls = getValueClass(object,rsmd.getColumnType(i));
                      if (isNotNull(cls)) {

                        Constructor con = cls.getConstructor(Object.class);
                        if (isNotNull(con)) {
                          value = (Value)con.newInstance(object);
                        } else {
                          value = new Value(object);
                        }
                      } else {
                        value = new Value(object);
                      }

                      Field f = rec.add(name,value);
                      if (f.getName().equals(recNumFieldName)) {
                        f.setNeedInsert(false);
                        f.setNeedUpdate(false);
                      }
                    }
                    ds.add(rec);
                  }
                }
                ret = (DataSet)ds;
              }
            }
            if (autoCommit) {
              commit();
            }
          }
        } finally {
          if (isNotNull(stmt)) {
            stmt.close();
          }
        }
      } catch (Exception e) {
        setException(new SqlException(e,sql));
      }
    }
    return ret;
  }

  private Object querySelect(String sql, boolean autoCommit, Object... args) {

    ArrayList<Object> list = new ArrayList<>();
    list.addAll(Arrays.asList(args));
    return querySelect(sql,autoCommit,list);
  }
  
  public DataSet querySelect(String sql, ArrayList<Object> args, IProviderSelector selector) {
    
    DataSet ret = null;
    Object r = querySelect(sql,false,args,selector);
    if (isNotNull(r)) {
      if (r.getClass()==DataSet.class) {
        ret = (DataSet)r;
      }
    }
    return ret;
  }

  public DataSet querySelect(String sql, Params params, IProviderSelector selector) {
    
    ArrayList<Object> list = new ArrayList<>();
    if (isNotNull(params)) {
      for (Param p: params) {
        list.add(getParameterValue(p.getValue()));
      }
    }
    return querySelect(sql,list,selector);
  }
  
  public DataSet querySelect(String sql) {
    
    ArrayList<Object> args = null;
    return querySelect(sql,args,null);
  }
  
  private Object queryUpdate(String sql, boolean autoCommit, ArrayList<Object> args) {
    
    Object ret = null;
    setException(null);
    if (checkConnected()) {
      try {
        sqlStack.add(0,sql);
        PreparedStatement stmt = null;
        try {
          stmt = connection.prepareStatement(sql);
          if (isNotNull(stmt)) {

            if (isNotNull(args)) {
              int index = 1;
              for (Object obj: args) {
                stmt.setObject(index,obj);
                index++;
              }
            }
          
            stmt.executeUpdate();
            ret = true;
            
            if (autoCommit) {
              commit();
            }
          }
        } finally {
          if (isNotNull(stmt)) {
            stmt.close();
          }
        }
      } catch (Exception e) {
        setException(new SqlException(e,sql));
      }
    }
    return ret;
  }
  
  private Object queryUpdate(String sql, boolean autoCommit, Object... args) {

    ArrayList<Object> list = new ArrayList<>();
    list.addAll(Arrays.asList(args));
    return queryUpdate(sql,autoCommit,list);
  }
  
  public boolean queryUpdate(String sql, ArrayList<Object> args) {
    
    boolean ret = false;
    Object r = queryUpdate(sql,true,args);
    if (isNotNull(r) && (boolean)r) {
      ret = true;
    }
    return ret;
  }

  private boolean queryUpdate(String sql, Object... args) {

    ArrayList<Object> list = new ArrayList<>();
    list.addAll(Arrays.asList(args));
    return queryUpdate(sql,list);
  }
  
  protected Params getProcedureParams(String name) {

    return null;
  }
  
  private boolean queryExecute(String sql, boolean autoCommit, Params params) {
    
    boolean ret = false;
    setException(null);
    
    if (checkConnected()) {
      try {
        sqlStack.add(0,sql);
        CallableStatement stmt = null;
        try {
          stmt = connection.prepareCall(sql);
          if (isNotNull(stmt)) {

            Direction[] drIn = {Direction.IN,Direction.IN_OUT}; 
            Direction[] drOut = {Direction.IN_OUT,Direction.OUT}; 
            
            if (isNotNull(params)) {
              
              for (Param p: params) {
                if (Arrays.asList(drIn).contains(p.getDirection())) {
                  stmt.setObject(p.getName(),p.getValue().getObject());
                }
                if (Arrays.asList(drOut).contains(p.getDirection())) {
                  stmt.registerOutParameter(p.getName(),p.getSqlType());
                }
              }
            }
            
            stmt.executeUpdate();
            
            if (isNotNull(params)) {

              for (Param p: params) {
                if (Arrays.asList(drOut).contains(p.getDirection())) {
                  p.setValue(stmt.getObject(p.getName()));
                }
              }
            }
            ret = true;
            
            if (autoCommit) {
              commit();
            }
          }
        } finally {
          if (isNotNull(stmt)) {
            stmt.close();
          }
        }
      } catch (Exception e) {
        setException(new SqlException(e,sql));
      }
    }
    return ret;
  }
  
  
  public String getExecuteSql(String name, Params params) {
    
    String ps = "";
    if (isNotNull(params)) {
      boolean first = false;
      StringBuilder sb = new StringBuilder();
      for(Param p: params) {
        String s = "?";
        if (!first) {
          sb.insert(0,s);
          first = true;
        } else {
          sb.append(",").append(s);
        }
      }
      ps = sb.toString();
    }
    return String.format("{ CALL %s(%s) }",name,ps); 
  }
  
  public boolean execute(String name, Params params) {
    
    boolean ret = false;
    Params procParams = getProcedureParams(name);
    if (isNotNull(procParams)) {
      String sql = getExecuteSql(name,procParams);
      if (!sql.trim().equals("")) {  
        if (isNotNull(params)) {
          for (Param p: params) {
            Param pp = procParams.find(p.getName());
            if (isNotNull(pp)) {
              Value v = p.getValue();
              v.setObject(getParameterValue(v.getObject()));
              pp.setValue(v);
              if (isNotNull(p.getSqlType())) {
                pp.setSqlType(p.getSqlType());
              }
            }
          }
        }
        ret = queryExecute(sql,true,procParams);
        if (ret) {
          if (isNotNull(params)) {
            for (Param pp: procParams) {
              Param p = params.find(pp.getName());
              if (isNotNull(p)) {
                p.setValue(pp.getValue());
                p.setDirection(pp.getDirection());
                p.setSqlType(pp.getSqlType());
              }
            }
          }
        }
      }
    }
    return ret;
  }
  
  @Override
  public String quote(String s) {
    
    return String.format("'%s'",s);
  }
  
  @Override
  public String quoteName(String name) {
    
    return name;
  }
  
  public String getFieldsSql(FieldNames fields) {
    
    String s = isNull(fields)?"*":fields.getString(this,",","");
    return s;
  }
  
  public String getWhereSql(Filter filter) {
    
    String s = "";
    s = isNull(filter)?s:filter.getString(this);
    s = (s.equals(""))?s:String.format(" WHERE %s",s);
    return s;
  }
  
  public String getOrderSql(Orders orders) { 
    
    String s = "";
    s = isNull(orders)?s:orders.getString(this,",","");
    s = (s.equals(""))?s: String.format(" ORDER BY %s",s);
    return s;
  }
  
  public String getLimitSql(int from, int count) {
    
    String s = "";
    return s;
  }
  
  public String getSelectSql(String name, FieldNames fields, Filter filter, Orders orders, int from, int count, Params params) {
    
    String f = getFieldsSql(fields);
    String w = getWhereSql(filter);
    String o = getOrderSql(orders);
    String l = getLimitSql(from,count);

    return String.format("SELECT %s FROM %s%s%s%s",f,name,w,o,l);
  }
  
  public String getSelectSql(String name, FieldNames fields, Filter filter, Orders orders, int from, Params params) {
    
    return getSelectSql(name,fields,filter,orders,from,-1,params);
  }

  public String getSelectSql(String name, FieldNames fields, Filter filter, Orders orders, Params params) {
    
    return getSelectSql(name,fields,filter,orders,-1,params);
  }
  
  public String getWrappedSelectSql(String sql, FieldNames fields, Filter filter, Orders orders, int from, int count, Params params) {
    
    String f = getFieldsSql(fields);
    String w = getWhereSql(filter);
    String o = getOrderSql(orders);
    String l = getLimitSql(from,count);

    return String.format("SELECT %s FROM (%s)%s%s%s",f,sql,w,o,l);
  }
  
  public String getWrappedSelectSql(String sql, FieldNames fields, Filter filter, Orders orders, int from, Params params) {
    
    return getWrappedSelectSql(sql,fields,filter,orders,from,-1,params);
  }

  public String getWrappedSelectSql(String sql, FieldNames fields, Filter filter, Orders orders, Params params) {
    
    return getWrappedSelectSql(sql,fields,filter,orders,-1,params);
  }
  
  public String getWrappedSelectSql(String sql, FieldNames fields, Params params) {
    
    return getWrappedSelectSql(sql,fields,null,null,params);
  }

  public String getWrappedSelectSql(String sql, FieldNames fields) {
    
    return getWrappedSelectSql(sql,fields,null,null,null);
  }

  public String getWrappedSelectSql(String sql) {
    
    return getWrappedSelectSql(sql,null);
  }
  
  public DataSet select(String name, FieldNames fields, Filter filter, Orders orders, int from, int count, Params params) {

    ArrayList<Object> list = new ArrayList<>();
    String sql = getSelectSql(name,fields,filter,orders,from,count,params); 
    if (isNotNull(params)) {
      for (Param p: params) {
        list.add(getParameterValue(p.getValue()));
      }
    }
    return querySelect(sql,list,null);
  }
  
  public DataSet select(String name, FieldNames fields, Filter filter, Orders orders, int from, Params params) {

    return select(name,fields,filter,orders,from,-1,params);
  }

  public DataSet select(String name, FieldNames fields, Filter filter, Orders orders, Params params) {

    return select(name,fields,filter,orders,-1,params);
  }

  public DataSet select(String name, FieldNames fields, Filter filter, Orders orders) {

    return select(name,fields,filter,orders,null);
  }

  public DataSet select(String name, FieldNames fields, Filter filter, Params params) {

    return select(name,fields,filter,null,params);
  }

  public DataSet select(String name, FieldNames fields, Filter filter) {

    return select(name,fields,filter,null,null);
  }

  public DataSet select(String name, FieldNames fields, Params params) {

    return select(name,fields,null,params);
  }

  public DataSet select(String name, Filter filter, Orders orders) {

    return select(name,null,filter,orders,null);
  }
  
  public DataSet select(String name, Params params) {

    return select(name,null,params);
  }

  public DataSet select(String name, Filter filter) {

    return select(name,null,filter);
  }
  
  public DataSet select(String name) {

    return select(name,null,null,null,null);
  }

  private Record getFirstRecord(DataSet dataset) {
    
    Record ret = null;
    if (isNotNull(dataset) && dataset.size()>0) {
      ret = dataset.get(0);
    }
    return ret;
  }
  
  public Record first(String name, FieldNames fields, Filter filter, Orders orders, Params params) {

    DataSet ds = select(name,fields,filter,orders,1,1,params);
    return getFirstRecord(ds);
  }

  public Record first(String name, FieldNames fields, Filter filter, Params params) {

    return first(name,fields,filter,null,params);
  }
  
  public Record first(String name, FieldNames fields, Filter filter, Orders orders) {

    return first(name,fields,filter,orders,null);
  }

  public Record first(String name, FieldNames fields, Filter filter) {

    return first(name,fields,filter,null,null);
  }

  public Record first(String name, FieldNames fields) {

    return first(name,fields,null);
  }

  public Record first(String name, Params params) {

    return first(name,null,null,params);
  }

  public Record first(String name, Filter filter) {

    return first(name,null,filter);
  }
  
  public Record first(String name) {

    return first(name,null,null);
  }
  
  private Value getFirstValue(Record record) {

    Value ret;
    if (isNotNull(record) && record.size()>0) {
      ret = record.get(0).getValue();
    } else {
      ret = new Value(null);
    }
    return ret;
  }
  
  public Value firstValue(String name, String fieldName, Filter filter, Orders orders, Params params) {
    
    Record r = first(name,new FieldNames(fieldName),filter,orders,params);
    return getFirstValue(r);
  }

  public Value firstValue(String name, String fieldName, Filter filter, Params params) {
    
    return firstValue(name,fieldName,filter,null,params);
  }

  public Value firstValue(String name, String fieldName, Filter filter) {
    
    return firstValue(name,fieldName,filter,null,null);
  }

  public String getCountSql(String name, Filter filter, Params params) {
    
    String w = getWhereSql(filter);

    return String.format("SELECT COUNT(*) FROM (%s)%s",name,w);
  }
  
  public Value count(String name, Filter filter, Params params) {

    ArrayList<Object> list = new ArrayList<>();
    String sql = getCountSql(name,filter,params); 
    if (isNotNull(params)) {
      for (Param p: params) {
        list.add(getParameterValue(p.getValue()));
      }
    }
    DataSet ds = querySelect(sql,list,null);
    return getFirstValue(getFirstRecord(ds));
  }
  
  public Value count(String name, Filter filter) {
    
    return count(name,filter,null);
  }

  public Value count(String name, Params params) {
    
    return count(name,null,params);
  }
  
  public Value count(String name) {
    
    return count(name,null,null);
  }
  
  private boolean needQuote(Value value) {
    
    return quoteClasses.indexOf(value.asObject().getClass())!=-1;
  }
  
  @Override
  public String getValueString(Value value, String prefix, String suffix) {

    String ret = prefix+value.asString()+suffix;
    if (needQuote(value)) {
      ret = quote(ret);
    }
    return ret;
  }
  
  private String getSqlValueString(Value value) {
  
    String ret = "NULL";
    if (isNotNull(value) && value.isNotNull()) {
      ret = getValueString(value,"","");
    }
    return ret;
  }
  
  public String getInsertSql(String name, Record record, boolean asAsk) {
    
    StringBuilder fn = new StringBuilder();
    StringBuilder vl = new StringBuilder();
    
    if (isNotNull(record)) {
    
      boolean first = false;
      for (Field f: record) {

        if (f.isNeedInsert()) {
          String n = quoteName(f.getName());
          String s1 = (asAsk)?"?":getSqlValueString(f.getValue());

          if (!first) {
            first = !n.equals("");
            if (first) {
              fn.insert(0,n);
              vl.insert(0,s1);
            }
          } else {
            fn.append(",").append(n);
            vl.append(",").append(s1);
          }
        }
      }
    }
    
    return String.format("INSERT INTO %s (%s) VALUES (%s)",name,fn.toString(),vl.toString());
  }
 
  public boolean insert(String name, Record record) {
    
    ArrayList<Object> list = new ArrayList<>();
    String sql = getInsertSql(name,record,true);
    if (isNotNull(record)) {
      for (Field f: record) {
        if (f.isNeedInsert()) {
          list.add(getParameterValue(f.getValue()));
        }
      }
    }
    return queryUpdate(sql,list);
  }

  public String getUpdateSql(String name, Record record, Filter filter, boolean asAsk) {
    
    StringBuilder fn = new StringBuilder();
    
    if (isNotNull(record)) {
    
      boolean first = false;
      for (Field f: record) {

        if (f.isNeedUpdate()) {
          String n = quoteName(f.getName());
          String s1 = (asAsk)?"?":getSqlValueString(f.getValue());

          if (!first) {
            first = !n.equals("");
            if (first) {
              fn.insert(0,n).append("=").append(s1);
            }
          } else {
            fn.append(", ").append(n).append("=").append(s1);
          }
        }
      }
    }
    
    return String.format("UPDATE %s SET %s%s",name,fn.toString(),getWhereSql(filter));
  }
   
  public boolean update(String name, Record record, Filter filter) {
    
    ArrayList<Object> list = new ArrayList<>();
    String sql = getUpdateSql(name,record,filter,true);
    if (isNotNull(record)) {
      for (Field f: record) {
        if (f.isNeedUpdate()) {
          list.add(getParameterValue(f.getValue()));
        }
      }
    }
    return queryUpdate(sql,list);
  }
  
  public String getDeleteSql(String name, Filter filter, boolean asAsk) {
    
    return String.format("DELETE FROM %s%s",name,getWhereSql(filter));
  }
  
  public boolean delete(String name, Filter filter) {
    
    String sql = getDeleteSql(name,filter,true);
    return queryUpdate(sql);
  }
  
  public Value getUniqueId() {
  
    return null;
  }
  
  public Value getNow() {
    
    return null;
  }
  
  public double diffTimestamp(Value v1, Value v2) {
    
    long firstTime = 0;
    long pow = Math.round(Math.pow(10,6));
    if (v1.isNotNull()) {
     // Timestamp t1 = (Timestamp)v1.asObject();
      Timestamp t1 = v1.asTimestamp();
      firstTime = (t1.getTime() - (t1.getNanos()/pow))*pow + t1.getNanos();
    }
    long secondTime = 0;
    if (v2.isNotNull()) {
     // Timestamp t2 = (Timestamp)v2.asObject();
      Timestamp t2 = v2.asTimestamp();
      secondTime = (t2.getTime() - (t2.getNanos()/pow))*pow + t2.getNanos();
    }
    return (firstTime-secondTime)/Math.pow(10,9);
  }
  
  public boolean isSelect(String query) {
    
    boolean ret = false;
    if (isNotNull(query) && !query.equals("")) {
      
      String s = query.toUpperCase();
      int sIndex = s.indexOf("SELECT");
      int fIndex = s.indexOf("FROM");
      ret = (sIndex>-1) && (fIndex>-1) && (sIndex<fIndex);
    }
    return ret;
  }
  
  public String getSql(Integer index) {
    
    String ret = null;
    int i = index.intValue();
    if (i>=0 && (i<sqlStack.size())) {
      ret = sqlStack.get(i);
    }
    return ret;
  }

  public String getSqlStack() {
    
    return sqlStack.toString();
  }
  
}