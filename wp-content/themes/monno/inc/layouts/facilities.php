<section class="mt-50" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="500">
  <div class="container">
    <div class="row mb-5">
      <div class="col-md-8 offset-md-2">
        <div class="section-title text-center">
          <h4 class="mb-3"><?php echo $layout['title']; ?></h4>
          <p><?php echo $layout['description']; ?></p>
        </div>
      </div>
    </div>
    <?php //prepare_data($layout['feature_lists']);?>
    <?php if ( !empty($layout['feature_lists']) ) : ?>
    <div class="row justify-content-center">
      <?php foreach($layout['feature_lists'] as $item) : ?>
      <div class="col-xs-12 col-md-4 col-sm-9 mb-3" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
      data-aos-duration="500">
        <div class="feature-box <?php echo $item['background']; ?>">
          <h5><?php echo $item['title']; ?></h5>
          <p><?php echo $item['description']; ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>