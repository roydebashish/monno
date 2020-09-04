<footer class="footer-area">
    <div class="container">
        <div class="footer-bottom row align-items-center">
            <div class="section-title-footer col-lg-12 col-md-12 pt-3 pb-3 text-white">
                &copy; 2020. Monno International School And College
            </div>
        </div>
    </div>
</footer>

<!-- <footer class="footer-area">
    <div class="container">
        <div class="footer-bottom row align-items-center">
        <div class="section-title-footer col-lg-8 col-md-4 mt-4">
            &copy; 2020. Monno
        </div>
        <div class="col-lg-4 col-md-8 col-sm-6 footer-social mt-4">
            <a href="#" target="blank"><i class="fa fa-facebook"></i></a>  
        </div>
        </div>
    </div>
</footer> -->

<?php wp_footer(); ?>

<script>
    $(document).ready(function () {
        $('body').on('click', function (e) {
            if ($('li.nav-item.dropdown .dropdown-menu a').is(e.target)) {
                if ($(this).parent().parent().toggleClass('open').length = 1) {
                    $('li.nav-item.dropdown').removeClass('open');
                }
            }
        })
        $('li.nav-item.dropdown a').on('click', function (event) {
            $(this).parent().toggleClass('open');
        });
    })
</script>
<script>
    AOS.init({
        disable: function() {
            var maxWidth = 800;
            return window.innerWidth < maxWidth;
        }
    });
</script>
</body>

</html>
