<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <meta charset="UTF-8" />
  <meta name="description" content="<?php if (is_archive()) { echo Strip_tags(term_description()); } else { if (get_field('meta_description')) { echo get_field('meta_description'); } } ?>"/>
  <meta name="keywords" content="<?php if (!is_archive()) { if (get_field('keywords')) { echo get_field('keywords'); } } ?>"/>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
  <header class="">
    <div class="main-navigation sticker">
      <nav class="navbar navbar-expand-lg sticky-top gradient-bg-2" id="mainNav">
        <div class="container logo-box text-right">
          <a class="navbar-brand" href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/MonnoSchool-logo.png"></a>
            <form class="search" action="" method="POST">
              <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="SEARCH" aria-label="Username" aria-describedby="basic-addon1">
                <div class="input-group-append">
                  <button class="pl-2 pr-2" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
          </div>
      </nav>
    </div>
    <nav class="navbar navbar-expand-lg sticky-top mb-2" id="primary">
      <div class="container-fluid logo-box text-right">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="lnr lnr-menu"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center align-items-center" id="navbarSupportedContent">
          <?php 
            wp_nav_menu( array(
                'menu' => '',
                'container' => false,
                'menu_class' => 'navbar-nav',
                'theme_location' => 'primary'
            ) );
          ?>
        </div>
      </div>
    </nav>
  </header>