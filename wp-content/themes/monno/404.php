<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <meta charset="UTF-8" />
    <title><?php  wp_title( '|', true ); ?></title>

    <?php wp_head(); ?>
</head>

<body>
  <?php 
    get_header(); 

    include TEMPLATEPATH.'/inc/layouts/404.php'; #load layouts

    get_footer(); 
    
    wp_footer();
  ?>
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
