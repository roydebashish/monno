<section class="pt-150 mb-5" >
    <div class="container">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-title about-details text-center">
                    <h2 class="gradient-bg-2 title"><?php the_title(); ?></h2>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-title about-details">
                <?php 
                  while(have_posts()): 
                    the_post();
                    the_content();
                  endwhile;
                ?>
                </div>
            </div>
        </div>
        <?php ?>
        <div class="row ">
          
        </div>
    </div>
</section>