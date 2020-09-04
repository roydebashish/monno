<section class="<?php echo $layout['css_class'].' '.$layout['background']; ?>" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="500">
  <div class="container">
    <div class="row">
      <div class="col-md-8 offset-md-2 offset-lg-2 text-center">
        <h4 class="text-uppercase mb-4"><?php echo $layout['title']; ?></h4>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 text-center mb-3">
        <h6 class="mb-3">ADDRESS</h6>
        <span class="address-icon mb-3 bg_address"><i class="fa fa-map-marker text-white fa-2x"></i></span>
        <p><?php echo get_field('address', 'option') ?></p>
      </div>
      <div class="col-md-4 text-center mb-3">
        <h6 class="mb-3">PHONE NO.</h6>
        <span class="address-icon mb-3 bg_phone"><i class="fa fa-phone text-white fa-2x"></i></span>
        <p><?php echo get_field('phone', 'option') ?></p>
      </div>
      <div class="col-md-4 text-center mb-3">
        <h6 class="mb-3">EMAIL</h6>
        <span class="address-icon mb-3 bg_email"><i class="fa fa-envelope text-white fa-2x"></i></span>
        <p><?php echo get_field('email', 'option') ?></p>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-md-8 offset-md-2 offset-lg-2 col-sm-12">
        <?php if ($layout['shortcode']) {
          echo do_shortcode($layout['shortcode']); 
        } ?>
      </div>
    </div>
  </div>
</section>