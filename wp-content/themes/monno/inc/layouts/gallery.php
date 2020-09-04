<section class="gallery bg-grey-light mt-50 pt-4 pb-4" id="<?php echo $layout['section_id']; ?>">
  <div class="container">
    <div class="row mb-3">
      <div class="col-md-12 text-center mb-2">
        <h4><?php echo $layout['title']; ?></h4>
      </div>
    </div>
    <?php if ( !empty($layout['gallery_shortcode']) ) : ?>
        <div class="row">
              <div class="col-xs-12 col-md-13 mb-4 col-sm-12" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="500">
                <?php  echo do_shortcode($layout['gallery_shortcode']); ?>
              </div>
        </div>
    <?php endif; ?>
  </div>
</section>