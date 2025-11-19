<?php include '../includes/header.php'; ?>

<main class="max-w-7xl mx-auto px-4 py-8">
    <!-- Hero (Glide slider) -->
    <section class="mb-8">
        <div class="glide" id="heroGlide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <li class="glide__slide">
                        <div class="bg-white rounded-lg overflow-hidden shadow md:flex">
                            <div class="md:w-1/2 p-8 flex flex-col justify-center">
                                <h1 class="text-4xl font-bold text-blue-800 mb-4">Bienvenido a MiTienda</h1>
                                <p class="text-gray-600 mb-6">Encuentra las mejores consolas y videojuegos al mejor precio.</p>
                                <div>
                                    <a href="#" class="inline-block bg-blue-800 text-white px-6 py-3 rounded-lg mr-3">Comprar ahora</a>
                                    <a href="#" class="inline-block bg-gray-100 text-gray-800 px-6 py-3 rounded-lg">Explorar</a>
                                </div>
                            </div>
                            <div class="md:w-1/2">
                                <picture>
                                    <source srcset="assets/products/hero.webp" type="image/webp">
                                    <img src="assets/products/hero.jpg" alt="Hero" class="w-full h-64 object-cover" loading="lazy">
                                </picture>
                            </div>
                        </div>
                    </li>
                    <!-- Additional hero slides could be added here -->
                </ul>
            </div>
            <div class="glide__bullets" data-glide-el="controls[nav]"></div>
        </div>
    </section>

    <!-- Mejores ventas -->
    <section class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Mejores ventas</h2>
        <?php
            $products = [
                ['img'=>'placeholder1.jpg','name'=>'PlayStation 5 Console','price'=>'499.99€'],
                ['img'=>'placeholder2.jpg','name'=>'Xbox Series X','price'=>'499.99€'],
                ['img'=>'placeholder3.jpg','name'=>'Nintendo Switch OLED','price'=>'349.99€'],
                ['img'=>'placeholder4.jpg','name'=>'The Last of Us Part I (PS5)','price'=>'69.99€'],
                ['img'=>'placeholder5.jpg','name'=>'FIFA 24 (PS5)','price'=>'59.99€'],
                ['img'=>'placeholder6.jpg','name'=>'The Legend of Zelda: Tears of the Kingdom (Switch)','price'=>'69.99€']
            ];
        ?>
        <div class="glide" id="productsGlide">
            <div class="glide__track" data-glide-el="track">
                <ul class="glide__slides">
                    <?php foreach($products as $p): ?>
                        <li class="glide__slide">
                            <div class="w-[220px] product-card relative p-4 bg-white rounded-lg">
                                <div class="relative">
                                     <?php $imgBase = pathinfo($p['img'], PATHINFO_FILENAME); ?>
                                     <picture>
                                         <source srcset="assets/products/<?php echo $imgBase; ?>_thumb.webp" type="image/webp">
                                         <img src="assets/products/<?php echo $p['img']; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>" class="w-full h-40 object-cover rounded-md" loading="lazy">
                                     </picture>
                                     <div class="badge absolute top-3 right-3">★</div>
                                </div>
                                <h3 class="mt-3 text-sm font-semibold"><?php echo htmlspecialchars($p['name']); ?></h3>
                                <p class="text-blue-800 font-bold mt-1"><?php echo $p['price']; ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="glide__arrows" data-glide-el="controls">
                <button class="glide__arrow glide__arrow--left" data-glide-dir="<">«</button>
                <button class="glide__arrow glide__arrow--right" data-glide-dir=">">»</button>
            </div>
        </div>
    </section>

    <!-- Categorías -->
    <section class="mb-8">
        <h2 class="text-xl font-semibold mb-4">Categorías</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative rounded-lg overflow-hidden h-40 bg-gray-200">
                 <img src="assets/products/category1.jpg" alt="Consoles" class="w-full h-full object-cover">
                 <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-4">
                        <span class="text-white font-bold text-lg">Consoles</span>
                 </div>
            </div>
            <div class="relative rounded-lg overflow-hidden h-40 bg-gray-200">
                 <img src="assets/products/category2.jpg" alt="Occasion" class="w-full h-full object-cover">
                 <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-4">
                        <span class="text-white font-bold text-lg">Occasion</span>
                 </div>
            </div>
            <div class="relative rounded-lg overflow-hidden h-40 bg-gray-200">
                 <img src="assets/products/category3.jpg" alt="Figurines" class="w-full h-full object-cover">
                 <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-4">
                        <span class="text-white font-bold text-lg">Figurines</span>
                 </div>
            </div>
        </div>
    </section>

</main>

<?php include '../includes/footer.php'; ?>
