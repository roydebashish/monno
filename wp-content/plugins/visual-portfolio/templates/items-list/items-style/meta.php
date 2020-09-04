<?php
/**
 * Item meta template.
 *
 * @var $args
 * @var $opts
 * @package visual-portfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// phpcs:ignore
$inline_meta = $opts['show_author'] && $args['author'] ||
    $opts['show_date'] ||
    $opts['show_comments_count'] && '' !== $args['comments_count'] ||
    $opts['show_views_count'] && $args['views_count'] ||
    $opts['show_reading_time'] && $args['reading_time'];

// phpcs:ignore
$show_meta = $inline_meta ||
    $opts['show_title'] && $args['title'] ||
    $opts['show_excerpt'] && $args['excerpt'] ||
    $opts['show_categories'] && $args['categories'] && ! empty( $args['categories'] );

?>

<figcaption class="vp-portfolio__item-overlay vp-portfolio__item-align-<?php echo esc_attr( $opts['align'] ); ?>">
    <?php if ( $show_meta ) : ?>
        <div class="vp-portfolio__item-meta">
            <?php

            // Show Categories.
            if ( $opts['show_categories'] && $args['categories'] && ! empty( $args['categories'] ) ) {
                ?>
                <div class="vp-portfolio__item-meta-categories">
                    <?php
                    // phpcs:ignore
                    $count = $opts['categories_count'];

                    // phpcs:ignore
                    foreach ( $args['categories'] as $category ) {
                        if ( ! $count ) {
                            break;
                        }
                        ?>
                        <div class="vp-portfolio__item-meta-category">
                            <a href="<?php echo esc_url( $category['url'] ); ?>">
                                <?php echo esc_html( $category['label'] ); ?>
                            </a>
                        </div>
                        <?php
                        $count--;
                    }
                    ?>
                </div>
                <?php
            }

            // Show Title.
            if ( $opts['show_title'] && $args['title'] ) {
                ?>
                <h2 class="vp-portfolio__item-meta-title">
                    <?php
                    if ( $args['url'] ) {
                        ?>
                        <a href="<?php echo esc_url( $args['url'] ); ?>"
                            <?php
                            if ( isset( $args['url_target'] ) && $args['url_target'] ) :
                                ?>
                                target="<?php echo esc_attr( $args['url_target'] ); ?>"
                                <?php
                            endif;
                            if ( isset( $args['url_rel'] ) && $args['url_rel'] ) :
                                ?>
                                rel="<?php echo esc_attr( $args['url_rel'] ); ?>"
                                <?php
                            endif;
                            ?>
                        >
                            <?php echo wp_kses_post( $args['title'] ); ?>
                        </a>
                        <?php
                    } else {
                        echo wp_kses_post( $args['title'] );
                    }
                    ?>
                </h2>
                <?php
            }

            // Inline Meta.
            if ( $inline_meta ) {
                ?>
                <div class="vp-portfolio__item-meta-inline">
                <?php

                // Show Author.
                if ( $opts['show_author'] && $args['author'] ) {
                    ?>
                    <div class="vp-portfolio__item-meta-part vp-portfolio__item-meta-author">
                        <span class="vp-portfolio__item-meta-part-icon">
                            <span class="vp-screen-reader-text">
                                <?php echo esc_html__( 'Author', 'visual-portfolio' ); ?>
                            </span>
                            <?php
                            if ( $args['author_avatar'] ) {
                                if ( $args['author_url'] ) {
                                    ?>
                                    <a href="<?php echo esc_url( $args['author_url'] ); ?>">
                                    <?php
                                }
                                ?>
                                <img src="<?php echo esc_url( $args['author_avatar'] ); ?>" alt="<?php echo esc_attr( $args['author'] ); ?>" width="50" height="50">
                                <?php
                                if ( $args['author_url'] ) {
                                    ?>
                                    </a>
                                    <?php
                                }
                            } else {
                                visual_portfolio()->include_template( 'icons/user' );
                            }
                            ?>
                        </span>
                        <span class="vp-portfolio__item-meta-part-text">
                            <?php
                            printf(
                                // translators: %s - author name.
                                esc_html__( 'By %s', 'visual-portfolio' ),
                                $args['author_url'] ? '<a href="' . esc_url( $args['author_url'] ) . '">' . esc_html( $args['author'] ) . '</a>' : esc_html( $args['author'] )
                            );
                            ?>
                        </span>
                    </div>
                    <?php
                }

                // Show Date.
                if ( $opts['show_date'] ) {
                    ?>
                    <div class="vp-portfolio__item-meta-part vp-portfolio__item-meta-date">
                        <span class="vp-portfolio__item-meta-part-icon">
                            <span class="vp-screen-reader-text">
                                <?php echo esc_html__( 'Date', 'visual-portfolio' ); ?>
                            </span>
                            <?php visual_portfolio()->include_template( 'icons/calendar' ); ?>
                        </span>
                        <span class="vp-portfolio__item-meta-part-text">
                            <?php echo esc_html( $args['published'] ); ?>
                        </span>
                    </div>
                    <?php
                }

                // Show Comments Count.
                if ( $opts['show_comments_count'] && '' !== $args['comments_count'] ) {
                    ?>
                    <div class="vp-portfolio__item-meta-part vp-portfolio__item-meta-comments">
                        <span class="vp-portfolio__item-meta-part-icon">
                            <span class="vp-screen-reader-text">
                                <?php echo esc_html__( 'Comments', 'visual-portfolio' ); ?>
                            </span>
                            <?php visual_portfolio()->include_template( 'icons/message' ); ?>
                        </span>
                        <span class="vp-portfolio__item-meta-part-text">
                            <?php
                            if ( $args['comments_url'] ) {
                                ?>
                                <a href="<?php echo esc_url( $args['comments_url'] ); ?>">
                                <?php
                            }

                            if ( ! $args['comments_count'] ) {
                                echo esc_html__( 'No Comments', 'visual-portfolio' );
                            } else {
                                // translators: %s Number of comments.
                                echo esc_html( sprintf( _n( '%s Comment', '%s Comments', $args['comments_count'], 'visual-portfolio' ), number_format_i18n( (int) $args['comments_count'] ) ) );
                            }

                            if ( $args['comments_url'] ) {
                                ?>
                                </a>
                                <?php
                            }
                            ?>
                        </span>
                    </div>
                    <?php
                }

                // Show Views Count.
                if ( $opts['show_views_count'] && $args['views_count'] ) {
                    ?>
                    <div class="vp-portfolio__item-meta-part vp-portfolio__item-meta-views">
                        <span class="vp-portfolio__item-meta-part-icon">
                            <span class="vp-screen-reader-text">
                                <?php echo esc_html__( 'Views', 'visual-portfolio' ); ?>
                            </span>
                            <?php visual_portfolio()->include_template( 'icons/eye' ); ?>
                        </span>
                        <span class="vp-portfolio__item-meta-part-text">
                            <?php
                            // translators: %s Number of views.
                            echo esc_html( sprintf( _n( '%s View', '%s Views', $args['views_count'], 'visual-portfolio' ), number_format_i18n( (int) $args['views_count'] ) ) );
                            ?>
                        </span>
                    </div>
                    <?php
                }

                // Show Reading Time.
                if ( $opts['show_reading_time'] && $args['reading_time'] ) {
                    ?>
                    <div class="vp-portfolio__item-meta-part vp-portfolio__item-meta-reading-rime">
                        <span class="vp-portfolio__item-meta-part-icon">
                            <span class="vp-screen-reader-text">
                                <?php echo esc_html__( 'Reading Time', 'visual-portfolio' ); ?>
                            </span>
                            <?php visual_portfolio()->include_template( 'icons/book' ); ?>
                        </span>
                        <span class="vp-portfolio__item-meta-part-text">
                            <?php
                            echo esc_html(
                                sprintf(
                                    // translators: %s Reading time minutes.
                                    _n(
                                        '%s Min Read',
                                        '%s Mins Read',
                                        is_string( $args['reading_time'] ) ? 1 : $args['reading_time'],
                                        'visual-portfolio'
                                    ),
                                    is_string( $args['reading_time'] ) ? $args['reading_time'] : number_format_i18n( (int) $args['reading_time'] )
                                )
                            );
                            ?>
                        </span>
                    </div>
                    <?php
                }

                ?>
                </div>
                <?php
            }

            // Show Excerpt.
            if ( $opts['show_excerpt'] && $args['excerpt'] ) {
                ?>
                <div class="vp-portfolio__item-meta-excerpt">
                    <?php echo wp_kses_post( $args['excerpt'] ); ?>
                </div>
                <?php
            }

            // Show Read More.
            if ( $opts['show_read_more'] && $opts['read_more_label'] ) {
                ?>
                <a class="vp-portfolio__item-meta-read-more" href="<?php echo esc_url( $opts['read_more_url'] ); ?>">
                    <?php echo esc_html( $opts['read_more_label'] ); ?>
                </a>
                <?php
            }
            ?>
        </div>
    <?php endif; ?>
</figcaption>
