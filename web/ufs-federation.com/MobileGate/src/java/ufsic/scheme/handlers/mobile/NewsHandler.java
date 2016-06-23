package ufsic.scheme.handlers.mobile;

import java.sql.Timestamp;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Date;
import java.util.HashMap;
import java.util.HashSet;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.TreeSet;
import org.apache.commons.lang3.StringEscapeUtils;
import org.apache.commons.lang3.StringUtils;
import org.htmlcleaner.CleanerProperties;
import org.htmlcleaner.HtmlCleaner;
import org.htmlcleaner.HtmlNode;
import org.htmlcleaner.TagNode;
import org.htmlcleaner.TagNodeVisitor;
import ufsic.providers.Dataset;
import ufsic.providers.Provider;
import ufsic.providers.Record;
import ufsic.providers.Value;
import ufsic.providers.Filter;
import ufsic.scheme.Device;

import ufsic.scheme.mobile.MobileFile;
import ufsic.scheme.mobile.MobileMenu;
import ufsic.scheme.mobile.MobileMenus;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;
import ufsic.scheme.Token;
import ufsic.utils.Utils;

public class NewsHandler extends MobileMenuHandler {

  public NewsHandler(Path path) {
    super(path);
  }
  
  private class Replacement {

    private String company = null;
    private String from = null;
    private String to = null;
    
    public Replacement(String ident) {
      
      String[] tmp = ident.split(":");
      if (tmp.length>0) {
        
        String s = null;
        if (tmp.length>1) {
          this.company = tmp[0].trim();
          s = tmp[1];  
        } else {
          this.company = ident;
        }
        
        if (isNotNull(s)) {
          
          String[] nt = s.split("=");
          if (nt.length>1) {
            
            this.from = nt[0].trim();
            this.to = nt[1].trim();
            
          } else {
            this.to = s;
          }
        }
      }
    }
    
    public String getCompany() {
      return company;
    }
    
    public String getFrom() {
      return from;
    }
    
    public String getTo() {
      return to;
    }
  }
  
  private class Replacements extends ArrayList<Replacement> {

    public Replacements() {
      super();
    }
    
    public Replacements(Value replacements) {

      this();

      if (replacements.isNotNull()) {

        String[] reps = replacements.asString().split(Utils.getLineSeparator());
        if (reps.length>0) {

          for (String s: reps) {
            this.add(new Replacement(s.trim()));
          }
        }
      }
    }
    
    public Replacements getList(String company) {
      
      Replacements ret = new Replacements();
      for (Replacement t: this) {
        
        String n = t.getCompany();
        if (isNotNull(n) && n.equals(company)) {

          ret.add(t);
        }
      }
      return ret;
    }
  }

  protected class NewsResponse extends BaseResponse {
   
    private ArrayList<News> result = null;
            
    protected class News {
    
      private String id = "";
      private String actual = "";
      private String title = "";
      private String text = "";
      private String date = "";
      private String categoryID = "";
      private String subcategoryID = "";
      private String expired = "";
      
      private boolean needMatches = false;
      
      private ArrayList<Url> imageUrls = new ArrayList<>();
      private ArrayList<Url> fileUrls = new ArrayList<>();
      private ArrayList<Link> relatedLinks = new ArrayList<>();
      private ArrayList<String> keywords = new ArrayList<>();
      private TreeSet<String> matches = new TreeSet<>();
      
      public String getId() {
        return id;
      }

      public void setId(String id) {
        this.id = isNotNull(id)?id:"";
      }
      
      public String getActual() {
        return actual;
      }

      public void setActual(String actual) {
        this.actual = isNotNull(actual)?actual:"";
      }

      public String getTitle() {
        return title;
      }

      public void setTitle(String title) {
        this.title = isNotNull(title)?title:"";
      }
      
      public String getText() {
        return text;
      }

      public void setText(String text) {
        this.text = isNotNull(text)?text:"";
      }
      
      public String getDate() {
        return date;
      }

      public void setDate(Timestamp date) {
        
        Long temp = date.getTime() / 1000L;
        this.date = temp.toString();
      }
      
      public String getCategoryID() {
        return categoryID;
      }

      public void setCategoryID(String categoryID) {
        this.categoryID = isNotNull(categoryID)?categoryID:"";
      }
      
      public String getSubcategoryID() {
        return subcategoryID;
      }

      public void setSubcategoryID(String subcategoryID) {
        this.subcategoryID = isNotNull(subcategoryID)?subcategoryID:"";
      }
      
      public String getExpired() {
        return expired;
      }

      public void setExpired(Timestamp expired) {
        
        Long temp = expired.getTime() / 1000L;
        this.expired = temp.toString();
      }
      
      protected class Url {
        
        private String name = "";
        private String url = "";
        private String extension = "";
        private String size = "";
        
        public String getName() {
          return name;
        }

        public void setName(String name) {
          this.name = isNotNull(name)?name:"";
        }
        
        public String getUrl() {
          return url;
        }

        public void setUrl(String url) {
          this.url = isNotNull(url)?url:"";
        }
        
        public String getExtension() {
          return extension;
        }

        public void setExtension(String extension) {
          this.extension = isNotNull(extension)?extension:"";
        }
        
        public String getSize() {
          return size;
        }

        public void setSize(String size) {
          this.size = isNotNull(size)?size:"";
        }
        
      }
      
      public ArrayList<Url> getImageUrls() {
      
        return imageUrls;
      }

      public void setImageUrls(ArrayList<Url> imageUrls) {

        this.imageUrls = imageUrls; 
      }
      
      public ArrayList<Url> getFileUrls() {
      
        return fileUrls;
      }

      public void setFileUrls(ArrayList<Url> fileUrls) {

        this.fileUrls = fileUrls; 
      }
      
      protected class Link {
        
        private String id = "";
        private String name = "";
        private String date = "";
        private String categoryID = "";
        private String subcategoryID = "";
        
        public String getId() {
          return id;
        }

        public void setId(String id) {
          this.id = isNotNull(id)?id:"";
        }
        
        public String getName() {
          return name;
        }

        public void setName(String name) {
          this.name = isNotNull(name)?name:"";
        }
        
        public String getDate() {
          return date;
        }

        public void setDate(Timestamp date) {

          Long temp = date.getTime() / 1000L;
          this.date = temp.toString();
        }

        public String getCategoryID() {
          return categoryID;
        }

        public void setCategoryID(String categoryID) {
          this.categoryID = isNotNull(categoryID)?categoryID:"";
        }

        public String getSubcategoryID() {
          return subcategoryID;
        }

        public void setSubcategoryID(String subcategoryID) {
          this.subcategoryID = isNotNull(subcategoryID)?subcategoryID:"";
        }        
      }
     
      public ArrayList<Link> getRelatedLinks() {
      
        return relatedLinks;
      }

      public void setRelatedLinks(ArrayList<Link> relatedLinks) {

        this.relatedLinks = relatedLinks; 
      }
      
      public ArrayList<String> getKeywords() {
      
        return keywords;
      }

      public void setKeywords(ArrayList<String> keywords) {

        this.keywords = keywords; 
      }
      
      public TreeSet<String> getMatches() {
      
        return matches;
      }

      public void setMatches(TreeSet<String> matches) {

        this.matches = matches; 
      }
      
    }
    
    public ArrayList<News> getResult() {
      
      if (isNull(result)) {
        result = new ArrayList<>();
      }
      return result;
    }
  
    public void setResult(ArrayList<News> result) {
      
      this.result = result; 
    }   
  }
  
  @Override
  protected void setTestHtml(StringBuilder builder) {
  
    super.setTestHtml(builder);
    builder.append(String.format("<input name=\"newsID\" placeholder=\"newsID\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"timestamp\" placeholder=\"timestamp\" value=\"%d\"/><br>",new Date().getTime()/1000L));
    builder.append(String.format("<input name=\"limitDateTime\" placeholder=\"limitDateTime\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"offset\" placeholder=\"offset\" value=\"%s\"/><br>",""));
  }
  
  private String removeWrongChars(String s) {
    
    String ret = s;
    if (!isEmpty(ret)) {
      ret = ret.replaceAll("\\\\\"","");
    }
    return ret;
  }
  
  private String htmlToText(final Token token, final NewsResponse.News news, HtmlCleaner cleaner, 
                            String s, Replacements replacements) {
    
    TagNode node = cleaner.clean(s);
    
    node.traverse(new TagNodeVisitor() {

      @Override
      public boolean visit(TagNode tagNode, HtmlNode htmlNode) {
       
        if (htmlNode instanceof TagNode) {
          
          TagNode node = (TagNode)htmlNode;
          String nodeName = node.getName().toLowerCase();
          
          if (!isEmpty(nodeName) && nodeName.equals("img")) {
            
            String location = node.getAttributeByName("src");
            if (!isEmpty(location)) {
              
              location = removeWrongChars(location).trim();
              if (!isEmpty(location)) {
              
                String name = node.getAttributeByName("alt");
                if (!isEmpty(name)) {
                  name = removeWrongChars(name).trim();
                  if ("".equals(name)) {
                    name = null;
                  }
                }

                String extension = Utils.getFileExtension(location);

                MobileFile f = getFile(token,location,name,extension,null,false);
                if (isNotNull(f)) {

                  NewsResponse.News.Url url = news.new Url();

                  url.setName(name);
                  url.setExtension(extension);
                  url.setSize(f.getFileSize().asString());
                  url.setUrl(getFileUrl(f));

                  news.getImageUrls().add(url);
                }
              }
            }
          }
        }
        return true;
      }
      
    });
    
    s = node.getText().toString();
    s = StringEscapeUtils.unescapeHtml4(s);
    
    if (isNotNull(replacements)) {
      
      for (Replacement r: replacements) {
        s = s.replace(r.getFrom(),r.getTo());
      }
    }
    
    return s.trim();
  }
  
  private void setNewsFileUrls(Token token, NewsResponse.News news, Dataset files) {

    if (isNotNull(files)) {
      
      Dataset<Record> ds = new Dataset<>();
      if (files.getList("outer_id",news.getId(),ds,false)) {

        for (Record r: ds) {

          String name = r.getValue("name").asString();
          String file = r.getValue("file").asString();
          String location = r.getValue("location").asString();
          String extension = Utils.getFileExtension(file);

          MobileFile f = getFile(token,location,name,extension,null,false);
          if (isNotNull(f)) {

            NewsResponse.News.Url url = news.new Url();

            url.setName(name);
            url.setExtension(extension);
            url.setSize(f.getFileSize().asString());
            url.setUrl(getFileUrl(f));

            news.getFileUrls().add(url);
          }
        }
      }
    }
  }
  
  private void setNewsRelatedLinks(NewsResponse.News news, Dataset links) {

    if (isNotNull(links)) {
      
      Dataset<Record> ds = new Dataset();
      if (links.getList("outer_id",news.getId(),ds,false)) {

        for (Record r: ds) {

          NewsResponse.News.Link link = news.new Link();

          link.setId(r.getValue("id").asString());
          link.setName(r.getValue("name").asString());
          link.setDate(r.getValue("date").asTimestamp());
          
          String parentId = r.getValue("parent_id").asString().trim();
          String mobileMenuId = r.getValue("mobile_menu_id").asString();
          
          if (isEmpty(parentId)) {
            link.setCategoryID(mobileMenuId);
            link.setSubcategoryID("");
          } else {
            link.setCategoryID(parentId);
            link.setSubcategoryID(mobileMenuId);
          }

          news.getRelatedLinks().add(link);
        }
      }
    }
  }
  
  private void setNewsKeywords(NewsResponse.News news, Dataset keywords) {

    if (isNotNull(keywords)) {
      
      Dataset<Record> ds = new Dataset<>();
      if (keywords.getList("outer_id",news.getId(),ds,false)) {

        for (Record r: ds) {

          news.getKeywords().add(r.getValue("name").asString());
        }
      }
    }
  }
  
  private void setNewsMatches(NewsResponse.News news, Set<String> keywords) {
    
    if (news.needMatches) {
      String text = news.getText();
      ArrayList<String> kwords = news.getKeywords();
      for (String s: keywords) {
        if (kwords.contains(s)) {
          news.getMatches().add(s);
        } else if (!isEmpty(text)) {
          if (text.contains(s)) {
            news.getMatches().add(s);
          }
        }
      }
    }
  }
  
  private void setResponseBySqlFromWWW(Token token, NewsResponse response, String newsSql, 
                                        Map<String,String> filesSql, Map<String,String> linksSql, 
                                        Map<String,String> keywordsSql, Map<String,Replacements> replacements, Value company,
                                        Timestamp begin, Timestamp end, Integer count, Integer searchId, Set<String> keywords) {
    
    if (!isEmpty(newsSql)) {
      
      Provider p = getWWWProvider();
      
      String bs = p.quote(formatForWWWDateTime(begin));
      String es = p.quote(formatForWWWDateTime(end));
              
      newsSql = newsSql.replace("$BEGIN",bs).replace("$END",es).trim();
      if (isNull(searchId)) {
        newsSql = String.format("select t.* from (%s) t "+
                                 "where t.date>=%s and t.date<=%s " +
                                 "order by t.date desc limit 0, %d",
                                newsSql,bs,es,count); 
      } else {
        newsSql = String.format("select t.* from (%s) t where t.id=%d",newsSql,searchId);
      }
      
      Dataset<Record> ds = p.querySelect(newsSql);
      if (isNotNull(ds)) {
        
        CleanerProperties props = new CleanerProperties();
        props.setTranslateSpecialEntities(true);
		    props.setTransSpecialEntitiesToNCR(true);
		    props.setAdvancedXmlEscape(true);
		    props.setRecognizeUnicodeChars(true);
        props.setOmitUnknownTags(false);
        props.setUseEmptyElementTags(false);
        props.setTreatUnknownTagsAsContent(false);
        props.setTransResCharsToNCR(true);
        
        HtmlCleaner cleaner = new HtmlCleaner(props);
        
        StringBuilder filesBuilder = new StringBuilder();
        StringBuilder linksBuilder = new StringBuilder();
        StringBuilder keywordsBuilder = new StringBuilder();
        
        for (Record r: ds) {
          
          NewsResponse.News news = response.new News();
          
          Value id = r.getValue("id");
          
          news.setId(id.asString());
          news.setActual(r.getValue("actual").asString());
          
          String parentId = r.getValue("parent_id").asString().trim();
          String mobileMenuId = r.getValue("mobile_menu_id").asString();
          
          Replacements reps = replacements.get(mobileMenuId);
          if (isNotNull(reps)) {
            reps = reps.getList(company.asString());
          }
          
          news.setTitle(htmlToText(token,news,cleaner,r.getValue("title").asString(),reps));
          news.setText(htmlToText(token,news,cleaner,r.getValue("text").asString(),reps)); 
          
          news.setDate(r.getValue("date").asTimestamp());
          news.setExpired(r.getValue("expired").asTimestamp());
          
          if (isEmpty(parentId)) {
            news.setCategoryID(mobileMenuId);
            news.setSubcategoryID("");
          } else {
            news.setCategoryID(parentId);
            news.setSubcategoryID(mobileMenuId);
          }
          
          Value needMatches = r.getValue("need_matches");
          news.needMatches = needMatches.isNotNull() && needMatches.asString().equalsIgnoreCase("1");
          
          String sql = filesSql.get(mobileMenuId);
          if (!isEmpty(sql)) {
            
            if (filesBuilder.length()>0) {
              filesBuilder.append(Utils.getLineSeparator()).append("union all").append(Utils.getLineSeparator());
            }
            filesBuilder.append(sql.replace("$ID",id.asString()));
          }
          
          sql = linksSql.get(mobileMenuId);
          if (!isEmpty(sql)) {
            
            if (linksBuilder.length()>0) {
              linksBuilder.append(Utils.getLineSeparator()).append("union all").append(Utils.getLineSeparator());
            }
            linksBuilder.append(sql.replace("$ID",id.asString()));
          }
          
          sql = keywordsSql.get(mobileMenuId);
          if (!isEmpty(sql)) {
            
            if (keywordsBuilder.length()>0) {
              keywordsBuilder.append(Utils.getLineSeparator()).append("union all").append(Utils.getLineSeparator());
            }
            keywordsBuilder.append(sql.replace("$ID",id.asString()));
          }
          
          response.getResult().add(news);
        }
        
        Dataset dsFiles = null;
        if (filesBuilder.length()>0) {
          dsFiles = p.querySelect(String.format("select t.* from (%s) t order by t.priority",filesBuilder.toString()));
        }
        
        Dataset dsLinks = null;
        if (linksBuilder.length()>0) {
          dsLinks = p.querySelect(String.format("select t.* from (%s) t order by t.priority desc",linksBuilder.toString()));
        }
        
        Dataset dsKeywords = null;
        if (keywordsBuilder.length()>0) {
          dsKeywords = p.querySelect(String.format("select t.* from (%s) t order by t.priority",keywordsBuilder.toString()));
        }
        
        if (isNotNull(dsFiles) || isNotNull(dsLinks) || isNotNull(dsKeywords) || keywords.size()>0) {
          
          for (NewsResponse.News news: response.getResult()) {
            
            setNewsFileUrls(token,news,dsFiles);
            setNewsRelatedLinks(news,dsLinks);
            setNewsKeywords(news,dsKeywords);
            setNewsMatches(news,keywords);
            
          }
        }
      }
    }
  }
  
  @Override
  protected Response prepareResponse() throws ResponseException {
    
    NewsResponse response = new NewsResponse();
 
    Token token = getToken(response);
    
    if (isNotNull(token)) {
      
      Scheme scheme = getScheme();
      Path path = getPath();
      
      Integer id = path.getParameterValue("newsID",(Integer)null);
      
      Integer v = path.getParameterValue("timestamp",(Integer)null);
      Timestamp end = isNotNull(v)?new Timestamp(v*1000L):scheme.getStamp().asTimestamp();
      
      v = path.getParameterValue("limitDateTime",(Integer)null);
      Timestamp begin = isNotNull(v)?new Timestamp(v*1000L):new Timestamp(scheme.getStamp().addYears(-1).getTime());
      
      Integer count = path.getParameterValue("offset",Integer.MAX_VALUE);
      
      Filter filter = new Filter();
      filter.Add(MobileMenus.NewsSql).IsNotNull();
      
      Value company = new Value(MobileMenus.Ufs);
      Device device = getDevice(token);
      if (isNotNull(device)) {
        company = device.getCompany();
        if (company.same(MobileMenus.Ufs)) {
          filter.And(MobileMenus.Ufs,1);
        }
        if (company.same(MobileMenus.Premier)) {
          filter.And(MobileMenus.Premier,1);
        }
      }
      
      MobileMenus menus = getMobileMenus(response,filter);

      if (isNotNull(menus)) {

        StringBuilder newsBuilder = new StringBuilder();
        Map<String,String> filesSql = new HashMap<>();
        Map<String,String> linksSql = new HashMap<>();
        Map<String,String> keywordsSql = new HashMap<>();
        Map<String,Replacements> replacements = new HashMap<>();
        
        Set<String> kwords = getKeywords(token);
        String keywords = StringUtils.join(getSet(kwords,"'%s'"),",");
        String likeKeywords = StringUtils.join(getSet(kwords,"text like '%","%s","%'")," or ");
        
        for (MobileMenu m: menus) {

          String lang = m.getLangId().asString().toLowerCase();
          String mmid = m.getMobileMenuId().asString();
          
          Value pid = m.getParentId();
          String pids = (pid.isNotNull() && !pid.same(""))?pid.asString():"null";
          
          String sql = m.getNewsSql().asString().replace("$LANG",lang)
                                                .replace("$MOBILE_MENU_ID",mmid)
                                                .replace("$PARENT_ID",pids)
                                                .replace("$KEYWORDS",keywords)
                                                .replace("$LIKE_KEYWORDS",likeKeywords)
                                                .replace("$COMPANY",company.asString());
          if (!isEmpty(sql)) {
            
            if (newsBuilder.length()>0) {
              newsBuilder.append(Utils.getLineSeparator()).append("union all").append(Utils.getLineSeparator());
            }
            newsBuilder.append(sql);
            
            sql = m.getFilesSql().asString().trim();
            if (!isEmpty(sql)) {
              filesSql.put(mmid,sql.replace("$LANG",lang));
            }
            
            sql = m.getLinksSql().asString().trim();
            if (!isEmpty(sql)) {
              linksSql.put(mmid,sql.replace("$LANG",lang)
                                   .replace("$COMPANY",company.asString()));
            }
            
            sql = m.getKeywordsSql().asString().trim();
            if (!isEmpty(sql)) {
              keywordsSql.put(mmid,sql.replace("$LANG",lang));
            }
          }
          
          Value reps = m.getReplacements();
          if (!reps.isEmpty()) {
            replacements.put(mmid,new Replacements(reps));
          }
        }
        
        setResponseBySqlFromWWW(token,response,newsBuilder.toString().trim(),
                                filesSql,linksSql,keywordsSql,replacements,company,
                                begin,end,count,id,kwords);
        
      } else {
        throw new BaseResponseException(response,ErrorCodeCategoryNotFound);
      }
    }

    return response;
  }
}
