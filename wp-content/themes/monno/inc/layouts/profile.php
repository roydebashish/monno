<?php if ( !empty($layout['style'] == 'style_one') ) : ?>

<section class="profile mt-50" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-12 pl-md-4 pl-lg-4">
        <?php if($layout['profile_picture']) :
          $imgURL = wp_get_attachment_image_url($layout['profile_picture'], 'profile-637x841'); ?>
          <img src="<?php echo $imgURL; ?>" class="img-fluid">
        <?php else: ?>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/monno/placeholder.jpg" class="img-fluid">
        <?php endif; ?>
        </div>
      <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="section-title about-details text-justify">
          <h4 class=" mb-3"><?php echo $layout['designation']; ?></h4>
          <?php echo $layout['description']; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php else:  ?>

<section class="profile mt-50 <?php if ( $layout['background'] == 'grey' ){ echo 'bg-grey-light'; }?>" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-12 pr-md-5 pr-lg-5">
        <?php if($layout['profile_picture']) :
          $imgURL = wp_get_attachment_image_url($layout['profile_picture'], 'profile-459x459'); ?>
          <img src="<?php echo $imgURL; ?>" class="img-fluid mt-md-5 mt-lg-5">
        <?php else: ?>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/monno/placeholder.jpg" class="img-fluid">
        <?php endif; ?>
      </div>
      <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="section-title about-details text-justify">
          <h4 class=" mb-3"><?php echo $layout['designation']; ?></h4>
          <?php echo $layout['description']; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php endif; ?>