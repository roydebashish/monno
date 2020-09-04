<section class=" mt-50 bg-grey-light pt-5 pb-5" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
        <h4><?php echo $layout['title']; ?></h4>
      </div>
    </div>
    <?php if(count($layout['academic_items']) > 0) : ?>
      <div class="row">
        <?php foreach($layout['academic_items'] as $item) : ?>
          <div class="col-lg-6 col-md-6 col-sm-12 mb-3" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
            <div class="feature-box <?php echo $item['background']; ?> text-justify">
              <h5><?php echo $item['title']; ?></h5>
              <?php echo $item['description']; ?>            
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>