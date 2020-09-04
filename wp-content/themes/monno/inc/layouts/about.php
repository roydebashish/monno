<section class="about-area mt-50" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <h4 class="mb-3"><?php echo $layout['title']; ?></h4>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-12" >
        <div class="section-title about-details">
          <?php echo $layout['description']; ?>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12">
      <?php $imgId = $layout['image']; 
        $imgURL = wp_get_attachment_image_url($imgId, 'banner'); ?>
        <img src="<?php echo $imgURL; ?>" class="img-fluid">
      </div>
    </div>
  </div>
</section>
<?php if($layout['enable_features']) : ?>
<section class="feature-area-details mt-20" id="features" >
  <div class="container">
    <div class="row mt-3 mb-5">
      <div class="col-lg-3 col-md-6 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="feature-box bg-blue">
          <h5>VISION</h5>
          <p><?php echo $layout['vission']; ?></p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="feature-box bg-light1">
          <h5>MISSION</h5>
          <p><?php echo $layout['mission']; ?></p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="feature-box bg-light2">
          <h5>OBJECTIVES</h5>
          <p><?php echo $layout['objectives']; ?></p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
        <div class="feature-box bg-grey">
          <h5>VALUES</h5>
          <p><?php echo $layout['values']; ?></p>
        </div>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>