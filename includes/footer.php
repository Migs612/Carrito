<footer class="footer mt-auto py-6 bg-white">
    <div class="container mx-auto text-center text-sm text-gray-500">
        © <?php echo date("Y"); ?> Mi Tienda Online
    </div>
</footer>

</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/@glidejs/glide/dist/glide.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    try{
        if(document.getElementById('heroGlide')){
            new Glide('#heroGlide', {type:'carousel', autoplay:5000, perView:1}).mount();
        }

        if(document.getElementById('productsGlide')){
            new Glide('#productsGlide', {
                type: 'carousel',
                perView: 4,
                gap: 24,
                breakpoints: { 1024: { perView:3 }, 768: { perView:2 }, 480: { perView:1 } }
            }).mount();
        }
    }catch(e){console.error(e)}
});
</script>
