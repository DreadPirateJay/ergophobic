'use strict';

/**
* ergophobic.routes Module
*/
angular.module('ergophobic')

.config(function ($stateProvider, $urlRouterProvider) {

  $stateProvider

  .state('login', {
    url: '/login',
    templateUrl: 'js/templates/login.html',
    controller: 'LoginCtrl as ctrl'
  });

  $urlRouterProvider.otherwise('/login');
});