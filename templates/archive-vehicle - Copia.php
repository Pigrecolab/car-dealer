<?php

/* Template Name: archive vehicle */

get_header();

cde_scripts_styles(); /* include the necessary srctips and styles */
?>

<?php $style_width = '';
/*  if( get_option( 'otw_pfl_content_width' ) ) {
      $style_width = 'style="width:'.get_option('otw_pfl_content_width').'px;"';
    }*/
    ?>
    <div class="row">
    <?php
  $order = "&order=ASC";
  if ($_POST['select'] == 'price') { $order = "&orderby=_cde_price";  }
  if ($_POST['select'] == 'year') { $order = "&orderby=_cde_year";  }
  if ($_POST['select'] == 'mileage') { $order = "&orderby=_cde_mileage";  }

  if (isset($_POST['order'])) { $order .= "&order=".$_POST['order'];  }

?>
 


<form method="post" id="order">
  <?php _e( 'Sort vehicles by:','cde_pgl' ) ?>
  <select name="select" onchange='this.form.submit()'>
    <option value="price"<?php selected( $_POST['select'],'price', 1 ); ?>><?php _e( 'price','cde_pgl' ) ?></option>
    <option value="year"<?php selected( $_POST['select'],'year', 1 ); ?>><?php _e( 'year','cde_pgl' ) ?></option>
    <option value="mileage"<?php selected( $_POST['select'],'mileage', 1 ); ?>><?php _e( 'mileage','cde_pgl' ) ?></option>

  </select>

  <select name="order" onchange='this.form.submit()'>
    <option value="ASC"<?php selected( $_POST['order'],'ASC', 1 ); ?>><?php _e( 'ASC','cde_pgl' ) ?></option>
    <option value="DESC"<?php selected( $_POST['order'],'DESC', 1 ); ?>><?php _e( 'DESC','cde_pgl' ) ?></option>
  

  </select>
</form>
 


      <?php if (is_page()||is_archive()) { 


           $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
           query_posts('post_type=vehicle&posts_per_page=2&paged='.$paged);
           //$loop = new WP_Query( array( 'post_type' => 'vehicle', 'posts_per_page' => 2, 'paged'=>$paged ) ); 
         }
  ?>



        
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
          <div class="col-sm-6 col-md-3">
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

     <?php cde_pagination(); ?>

   </div>
 </div>

 <?php get_footer(); ?>