<?php get_header(); ?>

<section class="pt-150 mb-5" id="blog-content">
    <div class="container">
        <div class="row ">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-title about-details text-center">
                    <h2 class="gradient-bg-2 title"><?php the_archive_title(); ?></h2>
                </div>
            </div>
        </div>
        <div class="row ">
            <?php while ( have_posts() ) :
                the_post(); ?>
                
                <div class="col-lg-4 col-md-4 col-sm-12 blog-item mb-3">
                    <img src="<?php the_post_thumbnail_url('full'); ?>" alt="" class="img-fluid shadow" />
                    <h4 class="blog-title text-truncate mt-3 mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <p><?php the_excerpt(); ?></p>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                <?php 
                    global $wp_query;
                    $big = 999999999; // need an unlikely integer
                    echo paginate_links( array(
                        'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                        'format' => '?paged=%#%',
                        'current' => max( 1, get_query_var('paged') ),
                        'total' => $wp_query->max_num_pages,
                        'before_page_number' => ''
                    ) );
                ?>
            </div>
        </div> 
    </div>
</section>

<?php get_footer(); ?>
