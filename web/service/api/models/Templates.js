// Template

module.exports = {

  migrate: 'safe',
  autoPK: false,
  autoCreatedAt: false,
  autoUpdatedAt: false,
  
  attributes: {
    id: {
      type: 'string',
      unique: true,
      primaryKey: true,
      required: true
    },
    name: {
      type: 'string',
      required: true
    },
    template: {
      type: 'string',
      required: true
    },
    
    toJSON: function() {
      
      return Utils.extend({},this);
    }
  },
  
  getByUser: function (user,result) {

    this.find(function(err,templates){

      if (err) {

        result(err,[]);

      } else if (templates) {

        function access(tpl) {

          if (typeof(tpl.id)==='string') {

            if (typeof(user.templates)==='object') {

              for (var t1 in user.templates) {
                if (tpl.id.search(t1)!==-1) {
                  return user.templates[t1];
                }
              }
            } else {
              
              if (typeof(user.templates)==='string') {
                var r = user.templates==='*';
                if (!r) {
                  r = tpl.id.search(user.templates)!==-1;
                }
                return r;
              }
            }
          }
          return false;
        }

        var tpls = {};
        for (var i in templates) {
          var t = templates[i];
          var a = access(t);
          if (a) {
            tpls[t.id] = {
              name: t.name,
              template: t.template,
              url: t.url,
              menu: (t.menu)?true:false
            }
          }
        }
        result(null,tpls);

      } else result(null,[]);

    });
  }
  
}
