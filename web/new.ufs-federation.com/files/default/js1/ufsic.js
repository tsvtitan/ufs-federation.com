var format = function (str, col) {
  col = typeof col === 'object' ? col : Array.prototype.slice.call(arguments, 1);

  return str.replace(/\{\{|\}\}|\{(\w+)\}/g, function (m, n) {
    if (m === "{{") { return "{"; }
    if (m === "}}") { return "}"; }
    return col[n];
  });
};  

String.prototype.toMMSS = function () {
  sec_numb    = parseInt(this);
  var hours = Math.floor(sec_numb / 3600);
  var minutes = Math.floor((sec_numb - (hours * 3600)) / 60);
  var seconds = sec_numb - (hours * 3600) - (minutes * 60);

  if (minutes < 10) {minutes = "0"+minutes;}
  if (seconds < 10) {seconds = "0"+seconds;}
  var time    = minutes+':'+seconds;
  return time;
}

function objectToString(obj) {

  return JSON.stringify(obj);
}

function asBoolean(value) {
  
  var ret = false;
  if (value!==null) {
    ret = JSON.parse(value);
  }
  return ret;
}

function getValue(params,name) {

  var ret = null;
  if (params!==null) {
    for (i=0;i<params.length;i++) {
      if (params[i].name===name) {
        ret = params[i].value;
        break;
      }
    }
  }
  return ret;
}

function valueAsBoolean(params,name) {
  
  return asBoolean(getValue(params,name));
}

function makePostParams(props,params) {
  
  var ret = [];
  if (params!==null) {
    for (var p in params) {
      ret[ret.length] = {name:params[p].name,value:params[p].value};
    }
  }
  if (props!==null) {
    for (var p in props) {
      ret[ret.length] = {name:p,value:props[p]};
    }
  }
  return ret;
}

function mergePostParams(params1,params2) {
  
  var ret = params1;
  if (params2!==null) {
    for (i=0;i<params2.length;i++) {
      ret[ret.length] = {name:params2[i].name,value:params2[i].value};
    }
  }
  return ret;
}

function getObject(objOrId) {
  
  var obj = (objOrId instanceof Object)?objOrId:$('#'+objOrId);
  return (obj.length>0)?obj:false;
}

function child(parent,ident) {
  
  var ret = null;
  if (parent!==null) {
    var pid = (parent instanceof Object)?parent.attr('id'):parent;
    ret = $(format('#{0} {1}',pid,ident));
  }
  return ret;
}

function getTemplate(template,params,func) {

  var url = format('/new/template/{0}',template);
  $.post(url,params).done(function(data) {
    if (func!==null) {
      func(data);
    }
  });
}

function getTemplateFor(element,template,add,params,func) {

  if (element!==null) {
    var e = (element instanceof Object)?element:$(element);
    if (e.length) {
      getTemplate(template,params,function(data){
        if ((add===null) || (add===false)) {
          e.html(data);
        } else {
          e.append(data);
        }
        if (func!==null) { func(); }
      });
    } else {
      alert('Element is not found.');
    }
  }
}

function redirect(url) {

  // IE8 and lower fix
  if (navigator.userAgent.match(/MSIE\s(?!9.0)/)) {

    var rl = document.createElement("a");
    rl.href = url;
    document.body.appendChild(rl);
    rl.click();
  } else { 
    window.location.replace(url); 
  }
}

/*function refreshFor(element,template,params,func) {

  getTemplateFor(element,template,false,params,func);
}

function appendFor(element,template,params,func) {
  
  getTemplateFor(element,template,true,params,func);
}

function appendTemplateTo(element,template,params,func) {
  
  getTemplateFor(element,template,true,params,func);
}*/

function templateRefreshFor(element,template,params,func) {

  getTemplateFor(element,template,false,params,func);
}

// tables ===========================================

function tableGetFrom(objOrId) {
  
  var ret = 0;
  var parent = getObject(objOrId);
  if (parent) {
    ret = parseInt(child(parent,'#from').attr('value')); 
  }
  return ret;
}

function tableSetFrom(objOrId,from) {
  
  var parent = getObject(objOrId);
  if (parent) {
    child(parent,'#from').attr('value',from);
  }
}

function tableGetRecordsCount(objOrId) {

  var ret = -1;
  var parent = getObject(objOrId);
  if (parent) {
    var r = child(parent,'#recordsCount').val();
    ret = (r===null)?-1:parseInt(r);
  }
  return ret;
}

function tableGetAllCount(objOrId) {

  var ret = 0;
  var parent = getObject(objOrId);
  if (parent) {
    ret = parseInt(child(parent,'#allCount').attr('value'));
  }
  return ret;
}

function tableGetMaxCount(objOrId) {
  
  var ret = 0;
  var parent = getObject(objOrId);
  if (parent) {
    ret = parseInt(child(parent,'#maxCount').attr('value')); 
  }
  return ret;
}

function tablePagerUpdate(objOrId) {

  var parent = getObject(objOrId);
  if (parent) {

    var f = tableGetFrom(parent);
    var c = child(parent,'table tbody tr').size();
    var all = tableGetAllCount(parent);
    
    //alert('f='+f+' c='+c+' all='+all);

    child(parent,'#first').prop('disabled',f===1);
    child(parent,'#prev').prop('disabled',f===1);
    child(parent,'#next').prop('disabled',((f+c)>all));
    child(parent,'#last').prop('disabled',((f+c)>all));

    var pa = 0;
    var p = 0 ;
    var rc = tableGetRecordsCount(parent);
    if (rc>0) {
      pa = Math.floor(all/rc);
      pa = ((all%rc)===0)?pa:pa+1;
      p = Math.floor(f/rc);
      p = ((f%rc)===0)?p:p+1;
    } else {
      pa = 1; p = 1;
    }
    child(parent,'#pages').attr('value',format('{0}/{1}',p,pa));
  }
}

function tablePagerShow(objOrId) {

  var parent = getObject(objOrId);
  if (parent) {

    var max = tableGetMaxCount(parent);
    var pager = child(parent,'#pager');
    if (max===0) {
      pager.hide();
    } else {
      var rc = tableGetRecordsCount(parent);
      if (rc<0) {
        pager.hide();
      } else {
        var all = tableGetAllCount(parent);
        if (all>rc) {
          pager.show();
        } else {
          pager.hide();
        }
      }
    }
  }
}

var tableRefreshTimeout;

function tableStartRefreshTimeout(objOrId) {

  clearTimeout(tableRefreshTimeout);
  
  tableRefreshTimeout = setTimeout(function(){
    
    clearTimeout(tableRefreshTimeout);
    child(objOrId,'#pager').hide();
    child(objOrId,'#loader').show();
    
  },250);
}

function tableRefresh(objOrId,params,from,func) {

  var parent = getObject(objOrId);
  if (parent) {
    
    tableStartRefreshTimeout(objOrId);

    var ptid = child(parent,'#pageTableId').attr('value');
    var rc = tableGetRecordsCount(parent);
    var max = tableGetMaxCount(parent);
    if (max===0) {
      rc = -1;
    }
    var template = child(parent,'#template').attr('value');
    
    //alert(objectToString(params));
    
    var ps = makePostParams({pageTableId:ptid,from:from,count:rc},params);
    
    templateRefreshFor(child(objOrId,'#table'),template,ps,function(){
      
      clearTimeout(tableRefreshTimeout);
      tableSetFrom(objOrId,from);
      child(objOrId,'#loader').hide();
      tablePagerShow(objOrId);
      tableSetEvents(objOrId,null,params);
      if (func!==null) { func(); }
    });
  }
}

function tablesRefresh(tableIds,params) {
  
  if (tableIds!==null && (tableIds instanceof Array)) {
    for (var p in tableIds) {
      var table = getObject(tableIds[p]);
      if (table) {
        var from = tableGetFrom(table);
        tableRefresh(table,params,from,null);
      }
    }  
  }
}

function tableSetEvents(objOrId,formIds,params) {
  
  var ps = params;
  if (formIds) {
    ps = formsGetParams(formIds);
  }
  
  child(objOrId,'#first').click(function(e){
    e.preventDefault();
    tableRefresh(objOrId,ps,1);
  });
  
  child(objOrId,'#last').click(function(e){
    e.preventDefault();
    var rc = tableGetRecordsCount(objOrId);
    var all = tableGetAllCount(objOrId);
    var m = all%rc;
    var from = all-m;
    if (from===all) {
      from = all-rc+1;
    } else {
      from = from +1;
    }
    tableRefresh(objOrId,ps,from);
  });
  
  child(objOrId,'#prev').click(function(e){
    e.preventDefault();
    var f = tableGetFrom(objOrId);
    var rc = tableGetRecordsCount(objOrId);
    tableRefresh(objOrId,ps,f-rc);
  });

  child(objOrId,'#next').click(function(e){
    e.preventDefault();
    var f = tableGetFrom(objOrId);
    var rc = tableGetRecordsCount(objOrId);
    tableRefresh(objOrId,ps,f+rc);
  });
  
  child(objOrId,'#recordsCount').change(function(e){
    e.preventDefault();
    tableRefresh(objOrId,ps,tableGetFrom(objOrId));
  });
  
  tablePagerUpdate(objOrId);
  
  if (formIds!==null) {
    
    //alert('formIds='+formIds);
    
    var autoLoad = asBoolean(child(objOrId,'#autoLoad').attr('value'));
    var async = asBoolean(child(objOrId,'#async').attr('value'));
    
    if (autoLoad && async) {
      tableRefresh(objOrId,ps,tableGetFrom(objOrId));
    } else {
      child(objOrId,'#loader').hide();
      tablePagerShow(objOrId);
    } 

  }
}

// forms =============================================

function formsGetParams(formIds) {
  
  var ret = [];
  if (formIds!==null && (formIds instanceof Array)) {
    for (var p in formIds) {
      var form = getObject(formIds[p]);
      if (form) {
        var params = form.serializeArray();
        ret = mergePostParams(ret,params);
      }
    }
  }
  return ret;
}

function formSubmit(objOrId,tableIds,func) {

  var parent = getObject(objOrId);
  if (parent) {

    var template = child(parent,'#template').attr('value');
    var form = child(parent,'#form');
    
    templateRefreshFor(form,template,form.serializeArray(),function(){
      
      var params = child(objOrId,'#form').serializeArray();
      var success = valueAsBoolean(params,'success');
      if (success) {
        tablesRefresh(tableIds,params);
        if (func!==null) { func(); }
      }
      //formSetEvents(objOrId,tableIds);
    });
  }
}

function formSetEvents(objOrId,tableIds) {
  
  child(objOrId,'#form').submit(function(e){
    e.preventDefault();
    formSubmit(objOrId,tableIds);
  });
}