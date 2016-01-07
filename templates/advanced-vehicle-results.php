<?php

/* Template Name: archive vehicle */

get_header();

cde_scripts_styles(); /* include the necessary srctips and styles */
?>

<?php $style_width = '';
// Get data from URL into variables
$_name = $_GET['name'] != '' ? $_GET['name'] : '';
$_mileage = $_GET['maxmil'] != '' ? $_GET['maxmil'] : '';
$_price = $_GET['maxprice'] != '' ? $_GET['maxprice'] : '';
$_year = $_GET['minyear'] != '' ? $_GET['minyear'] : '3000';

// Start the Query
$v_args = array(
        'post_type'     =>  'vehicle', // your CPT
        's'             =>  $_name, // looks into everything with the keyword from your 'name field'
        'meta_query'    =>  array(
                                array(
                                    'key'     => '_cde_mileage', // assumed your meta_key is 'car_model'
                                    'value'   => $_mileage,
                                    'compare' => '>', // finds models that matches 'model' from the select field
                                ),
                                array(
                                    'key'     => '_cde_price', // assumed your meta_key is 'car_model'
                                    'value'   => $_price,
                                    'compare' => '<', // finds models that matches 'model' from the select field
                                ),
                                array(
                                    'key'     => '_cde_year', // assumed your meta_key is 'car_model'
                                    'value'   => $_year,
                                    'compare' => '<=', // finds models that matches 'model' from the select field
                                ),
                            )
    );
$vehicleSearchQuery = new WP_Query( $v_args );

    ?>
    <div class="cde_row">
     <div class="cde_col-sm-12"><strong><?php _e('Advanced results',"cde_pgl") ?></strong><hr></div>
           <!-- navigation holder -->
      <div class="holder">
      </div>

<div id="cde_jpag">

        <?php if ($vehicleSearchQuery->have_posts()): while ($vehicleSearchQuery->have_posts()) : $vehicleSearchQuery->the_post(); ?>
          <div class="cde_col-sm-6 cde_col-md-3">
          <div class="cde_thumbnail cde_grid">
              <?php if ( has_post_thumbnail()) { 
//Get the Thumbnail URL
                $src_orig = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', false, '' );
                $src_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'cde_size', false, '' );

                ?>
                <figure>
                  <a href="<?php echo $src_orig[0]; ?>" rel="gallery" class="thumb"><img src="<?php echo $src_thumb[0]; ?>" /></a>
                  <?php } else { ?>
                  <div style="background:url(<?php echo plugins_url( '/otw-portfolio-light/images/pattern-1.png' ) ?>);width:<?php echo get_option('otw_pfl_thumb_size_w', '303'); ?>px;height:<?php echo get_option('otw_pfl_thumb_size_h', '210'); ?>px" title="<?php _e( 'No Image', 'otw_pfl' ); ?>"></div>
                  <?php } ?>
                  <figcaption>
                    <h4><?php 
                $prefix = '_cde_';
                     $mileage = get_post_meta( get_the_ID(), $prefix.'mileage', true );
                     $year = get_post_meta( get_the_ID(), $prefix.'year', true );
                     $price = get_post_meta( get_the_ID(), $prefix.'price', true );
                     echo "&euro; ".$price;
                  ?></h4><br />
                    <span><?php printf( __( '<strong>Year: </strong> %s' ), $year );
                    echo "<br>";
                      printf( __( '<strong>Km: </strong> %s' ), $mileage )  ?></span>
                    <a href="<?php the_permalink(); ?>"><?php _e("Take a look", "cde_pgl") ?></a>
                  </figcaption>
                </figure>
                <div class="caption">
                  <h3>        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>
                  <p><?php the_excerpt(); ?></p>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
</div>
        </div>




      <?php else: ?>

        <article id="post-0" class="post no-results not-found">
          <header class="entry-header">
           <h1 class="entry-title"><?php _e( 'Nothing Found', 'otw_pfl' ); ?></h1>
         </header>

         <div class="entry-content">
           <p><?php _e( 'Apologies, but no results were found. Perhaps searching will help find a related post.', 'otw_pfl' ); ?></p>
           <?php get_search_form(); ?>
         </div><!-- .entry-content -->
       </article><!-- #post-0 -->

     <?php endif; ?>

   

   </div>
 </div>

 <?php get_footer(); ?>