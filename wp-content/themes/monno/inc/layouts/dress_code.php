<section class="<?php echo $layout['css_class']; ?> <?php echo $layout['background']; ?>" id="<?php echo $layout['section_id']; ?>" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
  <div class="container">
    <?php if($layout['title']) : ?>
    <div class="row">
      <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
        <h4><?php echo $layout['title']; ?></h4>
      </div>
    </div>
    <?php endif; ?>
    <?php if(count($layout['dress_code_block']) > 0) : ?>
        <?php foreach($layout['dress_code_block'] as $block) : ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 mb-3" >
                    <h5 class="text-center"><?php echo $block['title']; ?></h5>
                </div>
            </div>
            <div class="row mb-3">
                <?php foreach($block['block_items'] as $item) : ?>
                    <div class="col-lg-6 col-md-6 col-sm-12 ">
                        
                        <div class="dress-code-item h-100 pt-2 text-justify <?php echo $item['color']; ?>">
                        <?php 
                            $lbg = '';
                            if ($layout['background'] == 'white') {
                                $lbg = 'background-color: #fff';
                            }
                        ?>
                            <h5 class="p-2 text-center <?php echo $layout['background']; ?>"  style="<?php echo $lbg; ?>" > <?php echo $item['title']; ?></h5>
                            <div class="dress-description"> <?php echo $item['description']; ?>  </div> 
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>