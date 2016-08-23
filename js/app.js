'use strict';

// App Module: the name AngularStore matches the ng-app attribute in the main <html> tag
// the route provides parses the URL and injects the appropriate partial page
angular.module('AngularStore', ['ngRoute', 'ui.bootstrap'])

    .config(['$routeProvider', function ($routeProvider) {

        $routeProvider.when('/store', {
            templateUrl: 'templates/store.html',
            controller: 'StoreController'

        }).when('/products/:productId', {
            templateUrl: 'templates/product.html',
            controller: 'StoreController'

        }).when('/cart', {
            templateUrl: 'templates/shoppingCart.html',
            controller: 'StoreController'

        }).otherwise({
            redirectTo: '/store'
        });
    }]);