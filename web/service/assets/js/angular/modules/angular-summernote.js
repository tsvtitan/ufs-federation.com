/**
 * Copyright (c) 2013 JeongHoon Byun aka "Outsider", <http://blog.outsider.ne.kr/>
 * Licensed under the MIT license.
 * <http://outsider.mit-license.org/>
 */
/* global angular */
angular.module('summernote', [])

  .controller('SummernoteController', ['$scope', '$attrs', function($scope, $attrs) {
    'use strict';

    var currentElement,
        summernoteConfig = $scope.summernoteConfig || {};

    if (angular.isDefined($attrs.height)) { summernoteConfig.height = $attrs.height; }
    if (angular.isDefined($attrs.focus)) { summernoteConfig.focus = true; }
    if (angular.isDefined($attrs.airmode)) { summernoteConfig.airMode = true; }
    //if (angular.isDefined($attrs.lang)) {
    /* tsv */
    if (angular.isDefined($attrs.placeholder)) { summernoteConfig.placeholder = true; }
    if (angular.isDefined($attrs.lang) && $attrs.lang!=="" && $attrs.lang!=="en-EN") {
      /*if (!angular.isDefined($.summernote.lang[$attrs.lang])) {
        throw new Error('"' + $attrs.lang + '" lang file must be exist.');
      }*/
      summernoteConfig.lang = $attrs.lang;
    }

    summernoteConfig.oninit = $scope.init;
    summernoteConfig.onenter = function(evt) { $scope.enter({evt:evt}); };
    summernoteConfig.onfocus = function(evt) { $scope.focus({evt:evt}); };
    
    summernoteConfig.onblur = function(evt) { $scope.blur({evt:evt}); };
    summernoteConfig.onpaste = function(evt) { $scope.paste({evt:evt}); };
    summernoteConfig.onkeyup = function(evt) { $scope.keyup({evt:evt}); };
    summernoteConfig.onkeydown = function(evt) { $scope.keydown({evt:evt}); };
    if (angular.isDefined($attrs.onImageUpload)) {
      summernoteConfig.onImageUpload = function(files, editor) {
        $scope.imageUpload({files:files, editor:editor, editable: $scope.editable});
      };
    }

    this.activate = function(scope, element, ngModel, lang) {
      
      if (!angular.isDefined(currentElement) || summernoteConfig.lang!==lang) {
        var updateNgModel = function() {
          var newValue = element.code();
          if (ngModel && ngModel.$viewValue !== newValue) {
            ngModel.$setViewValue(newValue);
            if (scope.$$phase !== '$apply' && scope.$$phase !== '$digest' ) {
              scope.$apply();
            }
          }
        };


        summernoteConfig.onChange = function(contents) {
          updateNgModel();
          $scope.change({contents:contents, editable: $scope.editable});
        };

        // tsv
        summernoteConfig.lang = lang;

        element.summernote(summernoteConfig);

        var editor$ = element.next('.note-editor'),
            unwatchNgModel;

        editor$.find('.note-toolbar').click(function() {
          updateNgModel();

          // sync ngModel in codeview mode
          if (editor$.hasClass('codeview')) {
            editor$.on('keyup', updateNgModel);
            if (ngModel) {
              unwatchNgModel = scope.$watch(function () {
                return ngModel.$modelValue;
              }, function(newValue) {
                editor$.find('.note-codable').val(newValue);
              });
            }
          } else {
            editor$.off('keyup', updateNgModel);
            if (angular.isFunction(unwatchNgModel)) {
              unwatchNgModel();
            }
          }
        });

        if (ngModel) {
          ngModel.$render = function() {
            element.code(ngModel.$viewValue || '');
          };
        }

        // set editable to avoid error:isecdom since Angular v1.3
        if (angular.isDefined($attrs.editable)) {
          $scope.editable = editor$.find('.note-editable');
        }

        currentElement = element;
        // tsv
        currentElement.editable = editor$.find('.note-editable');
        currentElement.toolbar = editor$.find('.note-toolbar');
        
        // use jquery Event binding instead $on('$destroy') to preserve options data of DOM
        element.on('$destroy', function() {
          element.destroy();
          $scope.summernoteDestroyed = true;
        });
      }
      
      return currentElement;

    };

    $scope.$on('$destroy', function () {
      // when destroying scope directly
      if (!$scope.summernoteDestroyed) {
        currentElement.destroy();
      }
    });
  }])
  .directive('summernote', [function() {
    'use strict';

    return {
      restrict: 'EA',
      transclude: true,
      replace: true,
      require: ['summernote', '^?ngModel'],
      controller: 'SummernoteController',
      scope: {
        summernoteConfig: '=config',
        editable: '=',
        placeholder: '=',
        lang: '=',
        disabled: '=',
        init: '&onInit',
        enter: '&onEnter',
        focus: '&onFocus',
        blur: '&onBlur',
        paste: '&onPaste',
        keyup: '&onKeyup',
        keydown: '&onKeydown',
        change: '&onChange',
        imageUpload: '&onImageUpload'
      },
      template: '<div class="summernote"></div>',
      link: function(scope, element, attrs, ctrls) {
        var summernoteController = ctrls[0],
            ngModel = ctrls[1];

        // tsv    
        scope.$watch('lang', function(value) {
          
          element.destroy();
          summernoteController.activate(scope, element, ngModel, value);

        });
        
        scope.$watch('placeholder', function(value) {
          
          if (value) {
            var e = summernoteController.activate(scope, element, ngModel, value);
            e.editable.attr('data-placeholder',value);
          }
        });
        
        scope.$watch('disabled', function(newValue,oldValue) {
          
          var e = summernoteController.activate(scope, element, ngModel, scope.lang);
          if (e) {  
            e.editable.attr('contentEditable',!newValue);
            e.toolbar.find('button')
                     .toggleClass('disabled',newValue);
           }
        });
        
        summernoteController.activate(scope, element, ngModel, false);
      }
    };
  }]);
