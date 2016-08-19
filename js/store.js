/**
 * Store (contains the products)
 * id, name, trademark, description, price, img, internalMemory, ram, processor, soVersion, mainCam, frontalCam
 * */
function store() {
    this.products = [
        new product("G4", "Moto G4 Plus", "Motorola", "Smartphone Moto G4 Plus Dual Chip Android 6.0 Tela 5.5 32GB Câmera 16MP - Preto", 1499, "http://imagens.americanas.com.br/produtos/01/00/item/127115/1/127115104_1GG.jpg", 16, 2, "Octa-core Qualcomm Snapdragon 617 (MSM8952)", "6.0 Marshmallow", 16, 5),
        new product("K5", "Lenovo K5", "Lenovo", "Smartphone Lenovo Vibe K5 Dual Chip Android Tela 5 16GB 4G Câmera 13MP - Dourado", 829, "http://imagens.americanas.com.br/produtos/01/00/item/126000/0/126000090_1GG.png", 16, 2, "Octa-core Qualcomm Snapdragon 616", "	5.1.1, Lollipop", 13, 5),
        new product("K10", "LG K10", "LG", "Smartphone LG K10 Dual Chip Android 6 Tela 5.3 16GB 4G Câmera 13MP TV Digital - Índigo", 849.99, "http://imagens.americanas.com.br/produtos/01/00/item/125768/0/125768030_1GG.png", 16, 1, "Octa Core 1.14 Ghz - Mediatek MT6753", "6.0 Marshmallow", 13, 8),
        new product("J7", "Galaxy J7", "Samsung", "Smartphone Samsung Galaxy J7 Duos Dual Chip Desbloqueado Android 5.1 5.5 16GB 4G 13MP - Dourado", 1199.99, "http://imagens.americanas.com.br/produtos/01/00/item/124196/1/124196148_1GG.jpg", 16, 2, "Exynos 1.5 GHz 8 Core", "5.1 Lollipop", 13, 5)
    ];
}

store.prototype.getProduct = function (idProduct) {
    for (var i = 0; i < this.products.length; i++) {
        if (this.products[i].id == idProduct)
            return this.products[i];
    }
    return null;
}
