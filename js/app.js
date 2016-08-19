'use strict';

// App Module: the name AngularStore matches the ng-app attribute in the main <html> tag
// the route provides parses the URL and injects the appropriate partial page
var storeApp = angular.module('AngularStore', ['ngRoute', 'ui.bootstrap']).config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/store', {
        templateUrl: 'templates/store.html',
        controller: storeController

    }).when('/products/:productId', {
        templateUrl: 'templates/product.html',
        controller: storeController

    }).when('/cart', {
        templateUrl: 'templates/shoppingCart.html',
        controller: storeController

    }).otherwise({
        redirectTo: '/store'
    });
}]);

// create a data service that provides a store and a shopping cart that
// will be shared by all views (instead of creating fresh ones for each view).
storeApp.factory("DataService", function () {

    // create store
    var myStore = new store();

    // create shopping cart
    var myCart = new shoppingCart("AngularStore");

    // enable PayPal checkout
    // note: the second parameter identifies the merchant; in order to use the 
    // shopping cart with PayPal, you have to create a merchant account with 
    // PayPal. You can do that here:
    // https://www.paypal.com/webapps/mpp/merchant
    myCart.addCheckoutParameters("PayPal", "paypaluser@youremail.com");

    // enable Google Wallet checkout
    // note: the second parameter identifies the merchant; in order to use the 
    // shopping cart with Google Wallet, you have to create a merchant account with 
    // Google. You can do that here:
    // https://developers.google.com/commerce/wallet/digital/training/getting-started/merchant-setup
    myCart.addCheckoutParameters("PagSeguro", "pagsegurouser@pagseguro.com.br");

    // return data object with store and cart
    return {
        store: myStore,
        cart: myCart
    };
});