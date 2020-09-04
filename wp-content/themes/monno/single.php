
<?php get_header(); ?>

<section class="pt-150 mb-5" id="blog-content">
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
                <img src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php the_title(); ?>" class="img-fluid w-100 mb-4"/>
                <!-- <h4 class="blog-title mt-2 mb-2"><?php //the_title(); ?></h4> -->
                <?php 
                    while (have_posts()): the_post();
                        the_content();
                    endwhile; 
                ?>
            </div>
        </div>
        
    </div>
</section>

<?php get_footer(); ?>
