<?php
 /*Template Name: single-vehicle
 */
 
 get_header(); 
 cde_scripts_styles();
 ?>
 <div id="primary">
 
    <div id="content" role="main">
        <?php


        $prefix = '_cde_';
        ?>
        <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <div class="container">
                        <div class="row">
                            <!-- Display featured image in right-aligned floating div -->
                            <div class="col-sm-4">
                                <?php the_post_thumbnail( 'medium' ); ?>
                            </div>
                            <div class="col-sm-8">
                                <!-- Display Title and Author Name -->
                                <h1><?php the_title(); ?></h1><br />
                                <strong><?php _e( 'Make', 'cde_pgl' ) ?>: </strong>
                                <?php 
                                $mk=get_post_meta( get_the_ID(), $prefix.'make', true );
                                $mk = wp_get_post_terms(get_the_ID(), 'makes', array("fields" => "names"));
                                echo esc_html( $mk[0] ); ?>
                                <br />

                                <strong><?php _e( 'Model', 'cde_pgl' ) ?>: </strong>
                                <?php echo esc_html( get_post_meta( get_the_ID(), $prefix.'model', true ) ); ?>
                                <br />


                                <strong><?php _e( 'Mileage', 'cde_pgl' ) ?>: </strong>
                                <?php echo esc_html( get_post_meta( get_the_ID(), $prefix.'mileage', true ) ); ?>
                                <br />

                                <strong><?php _e( 'Price', 'cde_pgl' ) ?>: </strong>
                                <?php echo esc_html( get_post_meta( get_the_ID(), $prefix.'price', true ) ); ?>
                                <br />   
                            </div>    
                        </div>

                        <div class="col-sm-12">
                                          <!-- Portfolio Image Slider -->
                        <?php
                            $check_imgs = get_post_meta( get_the_ID(), $prefix.'photos', true);

                            if( !empty( $check_imgs) ) {
                            //if(get_post_meta($post->ID, $prefix.'photos', true) ){
                         ?>
                          <div class="portfolio-gallery-wrapper">
                            <div class="flexslider" id="portfolio-gallery" style="margin-bottom:10px;">

                            <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 1600%; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">
                                <?php
                                    $files = get_post_meta( get_the_ID(),$prefix.'photos', 1 );

 var_dump( $files);
                                   foreach ( (array) $files as $attachment_id => $attachment_url ) {
                                        $url = $attachment_url;


                                        echo '<li data-thumb="" style="width: 500px; float: left; display: block;">'.wp_get_attachment_image( $attachment_id, 'fullwidth' ).'</li>';
                                   }
                                ?>
                              </ul></div><ul class="flex-direction-nav"><li><a href="#" class="flex-prev flex-disabled"><?php _e( 'Previous', 'otw_pfl' ); ?></a></li><li><a href="#" class="flex-next"><?php _e( 'Next', 'otw_pfl' ); ?></a></li></ul></div>
                            <div class="flexslider" id="portfolio-carousel" style="margin-bottom:10px;">

                            <div class="flex-viewport" style="overflow: hidden; position: relative;"><ul class="slides" style="width: 1600%; transition-duration: 0s; transform: translate3d(0px, 0px, 0px);">
                                <?php
                                  $files = get_post_meta( get_the_ID(),$prefix.'photos', 1 );

 var_dump( $files);
                                   foreach ( (array) $files as $attachment_id => $attachment_url ) {
                                        $url = $attachment_url;


                                        echo '<li data-thumb="" style="width: 100px; float: left; display: block;">'.wp_get_attachment_image( $attachment_id, 'thumbnail' ).'</li>';
                                   }
                                ?>
                              </ul></div><ul class="flex-direction-nav"><li><a href="#" class="flex-prev flex-disabled"><?php _e( 'Previous', 'otw_pfl' ); ?></a></li><li><a href="#" class="flex-next"><?php _e( 'Next', 'otw_pfl' ); ?></a></li></ul></div>
                          </div>
                        <?php } ?>
                  <!-- END Portfolio Image Slider -->
                  </div>
                    </div> 
                </header>

                <!-- Display movie review contents -->
                <div class="entry-content"><?php the_content(); ?></div>
            </article>

        <?php endwhile; ?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>