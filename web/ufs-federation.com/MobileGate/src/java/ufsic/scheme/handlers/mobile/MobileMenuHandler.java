package ufsic.scheme.handlers.mobile;

import ufsic.providers.Filter;
import ufsic.providers.GroupFilter;
import ufsic.providers.Orders;
import ufsic.scheme.mobile.MobileMenu;
import ufsic.scheme.mobile.MobileMenus;
import ufsic.scheme.Path;
import ufsic.scheme.Scheme;

public abstract class MobileMenuHandler extends TokenHandler {

  private MobileMenus mobileMenus = null;
  
  public MobileMenuHandler(Path path) {
    super(path);
  }
  
  @Override
  protected void setTestHtml(StringBuilder builder) {
    
    super.setTestHtml(builder);
    builder.append(String.format("<input name=\"categoryID\" placeholder=\"categoryID\" value=\"%s\"/><br>",""));
    builder.append(String.format("<input name=\"subcategoryID\" placeholder=\"subcategoryID\" value=\"%s\"/><br>",""));
  }
  
  final protected MobileMenus getMobileMenus() {
  
    if (isNull(mobileMenus)) {
      mobileMenus = new MobileMenus(getScheme());
    }
    return mobileMenus;
  }
  
  private MobileMenus getMobileMenus(Integer mobileMenuId, Integer parentId, Filter filter) {
    
    
    MobileMenus ret = null;
    
    Scheme scheme = getScheme();
    
    MobileMenus menus = getMobileMenus();

    GroupFilter gf = new GroupFilter();
    gf.And(MobileMenus.Locked).IsNull();
    gf.And(MobileMenus.LangId,scheme.getLangId());

    if (isNotNull(mobileMenuId)) {

      gf.And(MobileMenus.MobileMenuId,mobileMenuId);
      if (isNotNull(parentId)) {
        gf.And(MobileMenus.ParentId,parentId);
      }

    } else {

      Filter f = new Filter();
      if (isNotNull(parentId)) {
        f.Or(MobileMenus.MobileMenuId,parentId);
        f.Or(MobileMenus.ParentId,parentId);
      } else {
        f.Or(MobileMenus.MobileMenuId).IsNull();
        f.Or(MobileMenus.ParentId).IsNull();
      }
      gf.And(f);
    }
    
    if (isNotNull(filter)) {
      gf.And(filter);
    }

    if (menus.open(gf,new Orders(MobileMenus.Level,MobileMenus.Priority))) {
      ret = menus;
    }
    
    return ret; 
  }
  
  protected MobileMenus getMobileMenus(BaseResponse response, Filter filter) throws BaseResponseException {
    
    Path path = getPath();
    
    Integer mobileMenuId = path.getParameterValue("subcategoryID",(Integer)null);
    if (isNotNull(mobileMenuId) && mobileMenuId==0) {
      mobileMenuId = null;
    }
    Integer parentId = path.getParameterValue("categoryID",(Integer)null);
    if (isNotNull(parentId) && parentId==0) {
      parentId = null;
    }
    
    if (isNull(mobileMenuId) && isNull(parentId)) {
      throw new BaseResponseException(response,ErrorCodeLackOfParameters);
    }
    
    return getMobileMenus(mobileMenuId,parentId,filter);  
  }
  
  protected MobileMenu getMobileMenu(BaseResponse response, Filter filter) throws BaseResponseException {
    
    MobileMenu ret = null;
    MobileMenus menus = getMobileMenus(response,filter);
    if (isNotNull(menus) && menus.size()>0) {
      ret = (MobileMenu)menus.get(0);
    }
    return ret;
  }
  
}
