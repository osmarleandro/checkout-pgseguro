'use strict';

// the storeController contains two objects:
// - store: contains the product list
// - cart: the shopping cart object
angular.module('AngularStore').controller('StoreController', function ($scope, $routeParams, $http) {

    $scope.products = [
        new product("G4", "Moto G4 Plus", "Motorola", "Smartphone Moto G4 Plus Dual Chip Android 6.0 Tela 5.5 32GB Câmera 16MP - Preto", 1499, "http://imagens.americanas.com.br/produtos/01/00/item/127115/1/127115104_1GG.jpg", 16, 2, "Octa-core Qualcomm Snapdragon 617 (MSM8952)", "6.0 Marshmallow", 16, 5),
        new product("K5", "Lenovo K5", "Lenovo", "Smartphone Lenovo Vibe K5 Dual Chip Android Tela 5 16GB 4G Câmera 13MP - Dourado", 829, "http://imagens.americanas.com.br/produtos/01/00/item/126000/0/126000090_1GG.png", 16, 2, "Octa-core Qualcomm Snapdragon 616", "	5.1.1, Lollipop", 13, 5),
        new product("K10", "LG K10", "LG", "Smartphone LG K10 Dual Chip Android 6 Tela 5.3 16GB 4G Câmera 13MP TV Digital - Índigo", 849.99, "http://imagens.americanas.com.br/produtos/01/00/item/125768/0/125768030_1GG.png", 16, 1, "Octa Core 1.14 Ghz - Mediatek MT6753", "6.0 Marshmallow", 13, 8),
        new product("J7", "Galaxy J7", "Samsung", "Smartphone Samsung Galaxy J7 Duos Dual Chip Desbloqueado Android 5.1 5.5 16GB 4G 13MP - Dourado", 1199.99, "http://imagens.americanas.com.br/produtos/01/00/item/124196/1/124196148_1GG.jpg", 16, 2, "Exynos 1.5 GHz 8 Core", "5.1 Lollipop", 13, 5)
    ];

    $scope.getProduct = function (idProduct) {
        for (var i = 0; i < $scope.products.length; i++) {
            if ($scope.products[i].id == idProduct)
                return $scope.products[i];
        }
        return null;
    }

    // use routing to pick the selected product
    if ($routeParams.productId != null) {
        $scope.product = $scope.getProduct($routeParams.productId);
    }

    var keyStorage = 'ngitens';
    $scope.clearCart = false;
    $scope.checkoutParameters = {};
    $scope.items = [];

    // save items to local storage
    function saveItems() {
        if (localStorage != null && JSON != null) {
            localStorage.setItem(keyStorage, JSON.stringify($scope.items));
        }
    }

    // load items from local storage
    function loadItems() {
        $scope.items = localStorage.getItem(keyStorage) != null ? JSON.parse(localStorage.getItem(keyStorage)) : [];
    }

    // adds an item to the cart
    $scope.addItem = function (idProduct, name, price, quantity) {
        quantity = toNumber(quantity);
        if (quantity != 0) {

            // update quantity for existing item
            var found = false;
            for (var i = 0; i < $scope.items.length && !found; i++) {
                var item = $scope.items[i];
                if (item.id == idProduct) {
                    found = true;
                    item.quantity = toNumber(item.quantity + quantity);
                    if (item.quantity <= 0) {
                        $scope.items.splice(i, 1);
                    }
                }
            }

            // new item, add now
            if (!found) {
                var item = new cartItem(idProduct, name, price, quantity);
                $scope.items.push(item);
            }

            // save changes
            saveItems();
        }
    }

    // get the total price for all items currently in the cart
    $scope.getTotalPrice = function (idProduct) {
        var total = 0;
        for (var i = 0; i < $scope.items.length; i++) {
            var item = $scope.items[i];
            if (idProduct == null || item.id == idProduct) {
                total += toNumber(item.quantity * item.price);
            }
        }
        return total;
    }

    // get the total price for all items currently in the cart
    $scope.getTotalCount = function (idProduct) {
        var count = 0;
        if ($scope.items == null) return count;
        for (var i = 0; i < $scope.items.length; i++) {
            var item = $scope.items[i];
            if (idProduct == null || item.id == idProduct)
                count += toNumber(item.quantity);
        }
        return count;
    }

    // clear the cart
    $scope.clearItems = function () {
        $scope.items = [];
        saveItems();
    }

    function checkoutParameters(serviceName, merchantID, options) {
        this.serviceName = serviceName;
        this.merchantID = merchantID;
        this.options = options;
    }

    // define checkout parameters
    function addCheckoutParameters(serviceName, merchantID, options) {

        // check parameters
        if (serviceName != "PayPal" && serviceName != "PagSeguro") {
            throw "O serviço precisa ser 'PayPal' or 'PagSeguro'";
        }
        if (merchantID == null) {
            throw "A merchantID is required in order to checkout.";
        }

        // save parameters
        $scope.checkoutParameters[serviceName] = new checkoutParameters(serviceName, merchantID, options);
    }

    // Check out with services
    $scope.checkout = function (serviceName, clearCart) {

        // select serviceName if we have to
        if (serviceName == null) {
            var p = $scope.checkoutParameters[Object.keys($scope.checkoutParameters)[0]];
            serviceName = p.serviceName;
        }

        // sanity
        if (serviceName == null) {
            throw "Use o método 'addCheckoutParameters' para definir pelo menos um serviço de checkout.";
        }

        var parms = $scope.checkoutParameters[serviceName];
        if (parms == null) {
            throw "Não foi possível verificar os parâmetros para '" + serviceName + "'.";
        }
        switch (parms.serviceName) {
            case "PagSeguro":
                checkoutPagSeguro(parms, clearCart);
                break;
            case "PayPal":
                checkoutPayPal(parms, clearCart);
                break;
            default:
                throw "Serviço desconhecido: " + parms.serviceName;
        }
    }

    // check out using PagSeguro service
    function checkoutPagSeguro(parms, clearCart) {
        // global data
        var data = {};

        // item data
        for (var i = 0; i < $scope.items.length; i++) {
            var item = $scope.items[i];
            var ctr = i + 1;
            data["item_name_" + ctr] = item.id;
            data["item_description_" + ctr] = item.name;
            data["item_price_" + ctr] = item.price.toFixed(2);
            data["item_quantity_" + ctr] = item.quantity;
            data["item_merchant_id_" + ctr] = parms.merchantID;
        }

    }

    // check out using PayPal
    // for details see:
    // www.paypal.com/cgi-bin/webscr?cmd=p/pdn/howto_checkout-outside
    function checkoutPayPal(parms, clearCart) {
        // global data
        var data = {
            cmd: "_cart",
            currency_code: "BRL",
            business: parms.merchantID,
            upload: "1",
            rm: "2",
            charset: "utf-8"
        };

        // item data
        for (var i = 0; i < $scope.items.length; i++) {
            var item = $scope.items[i];
            var ctr = i + 1;
            data["item_number_" + ctr] = item.id;
            data["item_name_" + ctr] = item.name;
            data["quantity_" + ctr] = item.quantity;
            data["amount_" + ctr] = item.price.toFixed(2);
        }

        // build form
        var form = $('<form/></form>');
        form.attr("action", "https://www.paypal.com/cgi-bin/webscr");
        form.attr("method", "POST");
        form.attr("style", "display:none;");
        addFormFields(form, data);
        addFormFields(form, parms.options);
        $("body").append(form);

        // submit form
        $scope.clearCart = clearCart == null || $scope.clearCart;
        form.submit();
        form.remove();
    }

    // utility methods
    function addFormFields(form, data) {
        if (data != null) {
            $.each(data, function (name, value) {
                if (value != null) {
                    var input = $("<input></input>").attr("type", "hidden").attr("name", name).val(value);
                    form.append(input);
                }
            });
        }
    }

    function toNumber(value) {
        value = value * 1;
        return isNaN(value) ? 0 : value;
    }


    // items in the cart
    function cartItem(idProduct, name, price, quantity) {
        this.id = idProduct;
        this.name = name;
        this.price = price * 1;
        this.quantity = quantity * 1;
    }

    function init() {
        // enable PayPal checkout
        // https://www.paypal.com/webapps/mpp/merchant
        addCheckoutParameters("PayPal", "paypaluser@youremail.com");

        // enable PagSeguro checkout
        addCheckoutParameters("PagSeguro", "pagsegurouser@pagseguro.com.br");

        // load items from local storage when initializing
        loadItems();

        // save items to local storage when unloading
        $(window).on('beforeunload', function () {
            if ($scope.clearCart) {
                $scope.clearItems();
            }
            saveItems();
            $scope.clearCart = false;
        });
    }

    // initializing the app config
    init();

});