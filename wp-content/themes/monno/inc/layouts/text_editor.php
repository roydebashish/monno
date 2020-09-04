<section class="<?php echo $layout['css_class'].' '.$layout['background']; ?>" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom" data-aos-duration="500">
  <div class="container">
    <?php 
        $col = '';
        if($layout['width'] == 'full') { 
            $col = 'col-md-12 col-lg-12 col-sm-12 col-xs-12'; 
        } else { 
            $col = 'col-md-8 col-lg-8 col-sm-12 offset-md-2 offset-lg-2'; 
        } 
    ?> 
    <?php if ( !empty($layout['title']) ) : ?>
    <div class="row mb-3">
      <div class="<?php echo $col; ?>">
        <h4><?php echo $layout['title']; ?></h4>
      </div>
    </div>
    <?php endif; ?>
    <div class="row">
      <div class="<?php echo $col; ?>">
        <?php echo $layout['description']; ?>
      </div>
    </div>
  </div>
</section>