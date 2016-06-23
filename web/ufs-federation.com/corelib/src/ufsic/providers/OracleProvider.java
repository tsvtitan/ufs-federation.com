package ufsic.providers;

import java.sql.ResultSet;
import java.sql.Timestamp;

import oracle.sql.BLOB;
import oracle.sql.NCLOB;

import ufsic.out.Echo;
import ufsic.out.Logger;
import ufsic.providers.Param.Direction;
import ufsic.utils.Utils;

public class OracleProvider extends Provider {

  public OracleProvider(Echo echo, Logger logger, String jndiName) {
    
    super(echo,logger,jndiName);
    
    this.quoteClasses.add(oracle.sql.TIMESTAMP.class);
  }

  public OracleProvider(String jndiName) {
    
    this(null,null,jndiName);
  }

  @Override
  public void connect() {
    super.connect(); 
    if (isConnected()) {
      try {
        getConnection().setAutoCommit(false);
      } catch (Exception e) {
        logException(e);
      }
    }
  }

  @Override
  public String quote(String s) {

    String ret = s.replaceAll("'","''");
    return super.quote(ret);
  }

  @Override
  public String quoteName(String name) {
    
    return String.format("\"%s\"",name);
  }

  @Override
  protected Params getProcedureParams(String name) {

    Params ret = null;
    if (isConnected()) {
      try {
        ResultSet rs = null;
        try {
          rs = getConnection().getMetaData().getProcedureColumns(null,null,name,null);
          if (isNotNull(rs)) {
            ret = new Params();
            /*ResultSetMetaData rsm = rs.getMetaData();
            int count = rsm.getColumnCount();*/
            while (rs.next()) {
              /*for (int i=1; i<=count; i++) {
                String v = rsm.getColumnName(i);
                if (!v.equals("")) { 
                  Object o = rs.getObject(v);
                  if (Utils.isNotNull(o) && o.toString().equals("")) {

                  }
                }
              }*/
              String n = rs.getString("COLUMN_NAME");
              Integer dt = rs.getInt("DATA_TYPE");
              String tn = rs.getString("TYPE_NAME");
              switch (tn) {
                case "NVARCHAR2": { dt = java.sql.Types.NVARCHAR; break; }
              }
              Direction d = Direction.IN;
              Integer ct = rs.getInt("COLUMN_TYPE");
              if (isNotNull(ct)) {
                switch (ct) {
                  case 1: { d = Direction.IN; break; }
                  case 2: { d = Direction.IN_OUT; break; }
                  case 4: { d = Direction.OUT; break; }
                  default: d = Direction.IN;
                }
              }
              ret.Add(n,null,d,dt);
            }
          }
        } finally {
          if (isNotNull(rs)) {
            rs.close();
            rs = null;
          }
        }  
      } catch (Exception e) {
        logException(e);
      }
    }
    return ret;
  }
  
  private String getSelectSqlFromCount(String sql, FieldNames fields, int from, int count) {

    String ret;
    
    String rnum = recNumFieldName;
    
    if ((from>0) || (count>=0)) {

      Filter fl = new Filter();
      int f = 0;
      if (from>0) {
        fl.And(rnum).GreaterOrEqual(from);
        f = from;
      } else {
        f = 1;
      }
      if (count>=0) {
        fl.And(rnum).LessOrEqual(f+(count-1));
      }
      ret = String.format("SELECT %s FROM (SELECT ROWNUM AS %s, T.* FROM (%s) T) T WHERE %s",
                          isNull(fields)?"T.*":fields.getString(this,",","T."),rnum,sql,fl.getString(this));
    } else {
      
      ret = String.format("SELECT %s FROM (SELECT ROWNUM AS %s, T.* FROM (%s) T) T",
                          isNull(fields)?"T.*":fields.getString(this,",","T."),rnum,sql);
    }
    
    return ret;
  }
  
  @Override
  public String getSelectSql(String name, FieldNames fields, Filter filter, Orders orders, int from, int count, Params params) {
    
    if (isNotNull(params) && !params.isEmpty()) {
      
      StringBuilder sb = new StringBuilder();
      boolean first = false;
      
      for (Param p: params) {
        String s = String.format("%s=>?",p.getName());
        if (!first) {
          sb.insert(0,s);
          first = true;
        } else {
          sb.append(",").append(s);
        } 
      }
      name = String.format("TABLE(%s(%s))",name,sb.toString());
    }

    String sql = super.getSelectSql(name,fields,filter,orders,from,count,params);

    return getSelectSqlFromCount(sql,fields,from,count);
  }

  @Override
  public String getWrappedSelectSql(String sql, FieldNames fields, Filter filter, Orders orders, int from, int count, Params params) {
    
    if (isNotNull(params) && !params.isEmpty()) {
      
      StringBuilder sb = new StringBuilder();
      boolean first = false;
      
      for (Param p: params) {
        String s = String.format("%s=>?",p.getName());
        if (!first) {
          sb.insert(0,s);
          first = true;
        } else {
          sb.append(",").append(s);
        } 
      }
    }
    
    String newSql = super.getWrappedSelectSql(sql,fields,filter,orders,from,count,params);
    
    return getSelectSqlFromCount(newSql,fields,from,count);
  }

  @Override
  protected Class getValueClass(Object object, int type) {
    
    Class ret = super.getValueClass(object,type);
    if (isNotNull(object)) {
      if (Utils.isClass(object,BLOB.class)) {
        ret = OracleBlobValue.class; 
      } else if (Utils.isClass(object,NCLOB.class)) {
        ret = OracleNClobValue.class; 
      }
    }
    return ret;
  }
 
  @Override
  public String getValueString(Value value, String prefix, String suffix) {
    
    String ret = super.getValueString(value,prefix,suffix);
    if (value.sameClass(Timestamp.class) ||
        value.sameClass(oracle.sql.TIMESTAMP.class)) {
      ret = String.format("TO_TIMESTAMP(%s,'RRRR-MM-DD HH24:MI:SS.FF')",ret);
    }
    return ret; 
  }
  
  @Override
  protected Object getParameterValue(Object value) {
    
    Object ret = super.getParameterValue(value);
    if (isNotNull(ret)) {
      if (ret instanceof Timestamp) {
        ret = new oracle.sql.TIMESTAMP((Timestamp)ret);
      }
    }
    return ret;
  }
  
  @Override
  public Value getUniqueId() {
 
    return firstValue("DUAL","GET_UNIQUE_ID",null);
  }
  
  @Override
  public Value getNow() {
 
    return firstValue("DUAL","GET_NOW",null);
  }
  
}
