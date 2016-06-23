
app.directive('checkField',function() {
  
  return {
    restrict: 'A',
    require:  '^form',
    link: function (scope, el, attrs, formCtrl) {
      
      var element = angular.element(el[0].querySelector("[name]"));
      
      var model = element.attr('data-ng-model');
      //var required = element.attr('required');

      if (model) {
        var fmEl = formCtrl[element.attr('name')];
        
        scope.$watch(model,function() {
          el.toggleClass('has-error',(fmEl.$invalid && fmEl.$dirty));
        });
        
        scope.$on('show-errors',function() {
          el.toggleClass('has-error',(fmEl.$invalid));
        });
        
        if (fmEl.setRequired===undefined) {
          fmEl.setRequired = function(required) {
            fmEl.$setValidity(fmEl.$name,!required);
          }
        }
      }
      
      if (formCtrl.checkFields===undefined) {
        formCtrl.checkFields = function() {

          if (this.$invalid) {
            scope.$broadcast('show-errors');
          }
          return !this.$invalid;
        }
      }
    }
  }
});