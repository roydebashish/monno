<section class="admission mt-50" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom"
      data-aos-duration="500">
  <div class="container">
    <div class="row mb-3">
      <div class="col-md-8 offset-md-4 col-sm-12">
        <h4><?php echo $layout['title']; ?></h4>
      </div>
    </div>
    <?php if(!empty($layout['admissions'])): 
        foreach($layout['admissions'] as $item): ?>
    <div class="row mb-4">
      <div class="col-xs-12 col-md-4 col-sm-12 pr-md-5 pr-lg-5">
      <?php $imgURL = wp_get_attachment_image_url($item['image'], 'admission-506x404'); ?>
        <img src="<?php echo $imgURL; ?>" alt="" class="img-fluid mb-3 mb-md-0 mb-lg-0">
      </div>
      <div class="col-xs-12 col-md-8 col-sm-12 text-justify">
        <h5 class="mb-2"><?php echo $item['title']; ?></h5>
        <?php echo $item['description']; ?>
      </div>
    </div>
    <?php endforeach; 
    endif; ?>
  </div>
</section>