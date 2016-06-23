
var body = document.getElementsByTagName('body')[0];
if (body) {
  
  angular.element(body).ready(function() {
    body.setAttribute('data-ng-app',app.name);
    angular.bootstrap(body,[app.name]);
  });
}



