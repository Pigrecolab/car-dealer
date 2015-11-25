<?php
/*
Plugin Name: Car dealer
Plugin URI: http://www.pigrecolab.com
Description: Car dealer custom plugin
Version: 1.0
Author: Roberto Bruno
Author URI: http://www.pigrecolab.com
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

ini_set( 'mysql.trace_mode', 0 );

load_plugin_textdomain('cde_pgl',false,dirname(plugin_basename(__FILE__)) . '/languages/');

function cde_register_posttype() {
	$labels = array(
		'name' 					=> __( 'Vehicles', 'cde_pgl' ),
		'singular_name' 		=> __( 'Vehicle', 'cde_pgl' ),
		'add_new' 				=> __( 'Add', 'cde_pgl' ),
		'add_new_item'			=> __( 'Add new vehicle', 'cde_pgl' ),
		'edit_item' 			=> __( 'Update vehicle', 'cde_pgl' ),
		'new_item' 				=> __( 'New vehicle', 'cde_pgl' ),
		'all_items' 			=> __( 'All the vehicles', 'cde_pgl' ),
		'view_item' 			=> __( 'View vehicle', 'cde_pgl' ),
		'search_items' 			=> __( 'Search vehicle', 'cde_pgl' ),
		'not_found' 			=> __( 'No result found', 'cde_pgl' ),
		'not_found_in_trash' 	=> __( 'No result in trash', 'cde_pgl' )
		);

	$args = array(
		'labels' 				=> $labels,
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'show_ui' 				=> true,
		'can_export'			=> true,
		'show_in_nav_menus'		=> true,
		'query_var' 			=> true,
		'has_archive' 			=> true,
		'rewrite' 				=> array( 'slug' => __( 'vehicle', 'cde_pgl'  ), 'with_front' => false ) ,
		'capability_type' 		=> 'post',
		'hierarchical' 			=> false,
		'menu_position' 		=> null,
		'supports' 				=> array( 'title', 'editor', 'thumbnail' ,'revisions' )
		);

	register_post_type( 'vehicle' ,  $args );
}

add_action( 'init', 'cde_register_posttype', 0 );

/**
 * Register custom taxonomies.
 */

function vehicle_tax_init() {
	// create a new taxonomy
	register_taxonomy(
		'cde_category_makes',
		'vehicle',
		array(
			'label' => __( 'Makes' , 'cde_pgl' ),
			
			)
		);
}
add_action( 'init', 'vehicle_tax_init' );

add_action( 'cmb2_init', 'cde_register_vehicle_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */

function cde_register_vehicle_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cde_';


	$mycmb = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Vehicle features', 'cde_pgl' ),
		'object_types'  => array('vehicle', ), // Post type
		//'show_on_cb'    => 'pgl_show_if_front_page', // function should return a bool value
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
		) );

	$mycmb->add_field( array(
		'name'     => __( 'Make', 'cde_pgl' ),
		'desc'     => __( 'Choose the make', 'cde_pgl' ),
		'id'       =>  $prefix . 'make',
	    'taxonomy' => 'cde_category_makes', //Enter Taxonomy Slug
	    'type'     => 'taxonomy_select',
	    ) );

	$mycmb->add_field( array(
		'name'    => __( 'Model', 'cde_pgl' ),
		'desc'    => __( 'Model of the vehicle', 'cde_pgl' ),
		'id'      =>  $prefix . 'model',
		'type'    => 'text_medium'
		) );

	$mycmb->add_field( array(
		'name'    => __( 'Year', 'cde_pgl' ),
		'desc'    => __( 'Construction year of the vehicle', 'cde_pgl' ),
		'id'      =>  $prefix . 'year',
		'type'    => 'text_small'
		) );

	$mycmb->add_field( array(
		'name'    => __( 'Mileage', 'cde_pgl' ),
		'desc'    => __( 'Mileage of the vehicle', 'cde_pgl' ),
		'id'      =>  $prefix . 'mileage',
		'type'    => 'text_small'
		) );


	$mycmb->add_field( array(
		'name'     => __( 'Fuel', 'cde_pgl' ),
		'desc'     => __( 'Choose the fuel type', 'cde_pgl' ),
		'id'       =>  $prefix . 'fuel',
	    'type'             => 'select',
	    'show_option_none' => false,
	    'options'          => array(
	        __( 'benzine', 'cde_pgl' ) => __( 'benzine', 'cde_pgl' ),
	        __( 'diesel', 'cde_pgl' )   => __( 'diesel', 'cde_pgl' ),
	        __( 'LPG', 'cde_pgl' )     => __( 'LPG', 'cde_pgl' ),
	        __( 'methane', 'cde_pgl' )     => __( 'methane', 'cde_pgl' ),
	        __( 'electric', 'cde_pgl' )     => __( 'electric', 'cde_pgl' ),
	        __( 'mixed', 'cde_pgl' )     => __( 'mixed', 'cde_pgl' ),
	    ),
	    ) );

	$mycmb->add_field( array(
		'name' => __( 'Price', 'cde_pgl' ),
		'desc' => __( 'Price of the vehicle', 'cde_pgl' ),
		'id' =>  $prefix . 'price',
		'type' => 'text_money',
		'before_field' => '€'
		) );


	$mycmb->add_field( array(
		'name' => __( 'Photos', 'cde_pgl' ),
		'desc' => '',
		'id'   =>  $prefix . 'photos',
		'type' => 'file_list',
    'preview_size' => array( 100, 100 ), // Default: array( 50, 50 )
    // Optional, override default text strings
    // 'options' => array(
    //     'add_upload_files_text' => 'Replacement', // default: "Add or Upload Files"
    //     'remove_image_text' => 'Replacement', // default: "Remove Image"
    //     'file_text' => 'Replacement', // default: "File:"
    //     'file_download_text' => 'Replacement', // default: "Download"
    //     'remove_text' => 'Replacement', // default: "Remove"
    // ),
    ) );

}

/****** CUSTOM POST COLUMN ****/

add_filter('manage_edit-vehicle_columns', 'vehicle_columns');
add_action('manage_vehicle_posts_custom_column', 'vehicle_custom_columns', 10, 2);

function vehicle_columns($columns){
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( 'Vehicle', 'cde_pgl'  ),
		'thumbs' => __('Thumbs', 'cde_pgl' ),
		'price' => __( 'Price', 'cde_pgl'  ),
		'year' => __( 'Year', 'cde_pgl'  ),
		'mileage' => __( 'Mileage', 'cde_pgl'  )

		);

	return $columns;

}

function vehicle_custom_columns($column, $post_id){
	global $post;
	$prefix="_cde_";

	switch( $column ) {
		/* If displaying the 'price' column. */
		case 'price' :

		/* Get the post meta. */
		$price = get_post_meta( $post_id, $prefix.'price', true );

		/* If no price is found, output a default message. */
		if ( empty( $price ) )
			echo __( 'Unknown' );

		/* If there is a price, append 'minutes' to the text string. */
		else
			printf( __( '€ %s' ), $price );

		break;

		/* If displaying the 'year' column. */
		case 'year' :

		/* Get the post meta. */
		$year = get_post_meta( $post_id, $prefix.'year', true );

		/* If no mileage is found, output a default message. */
		if ( empty( $year ) )
			echo __( 'Unknown' );

		/* If there is a mileage, append 'minutes' to the text string. */
		else
			printf( __( '%s' ), $year );

		break;

		/* If displaying the 'mileage' column. */
		case 'mileage' :

		/* Get the post meta. */
		$mileage = get_post_meta( $post_id, $prefix.'mileage', true );

		/* If no mileage is found, output a default message. */
		if ( empty( $mileage ) )
			echo __( 'Unknown' );

		/* If there is a mileage, append 'minutes' to the text string. */
		else
			printf( __( '%s' ), $mileage );

		break;


		/* If displaying the 'thumb' column. */
		case 'thumbs' :

		echo the_post_thumbnail( array(80,80) );

		break;
		

		/* Just break out of the switch statement for everything else. */
		default :
		break;
	}

}

/****** VEHICLE CUSTOM TEMPLATE ****/



add_filter( 'template_include', 'include_template_vehicle', 1 );

function include_template_vehicle( $template_path ) {

	if ( get_post_type() == 'vehicle' ) {

		/* -------- template for single -------*/
		if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
			if ( $theme_file = locate_template( array ( 'single-vehicle.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . '/templates/single-vehicle.php';
			}
		}

		/* -------- template for archive -------*/
		if ( is_archive() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
			if ( $theme_file = locate_template( array ( 'archive-vehicle.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . '/templates/archive-vehicle.php';
			}
		}

	}
	return $template_path;
}

/*-----------------------------------------------------------------------------------*/
/* Image size for gallery                                           */
/*-----------------------------------------------------------------------------------*/

 add_image_size( "cde_size", 300, 200, true ); 

/*
function custom_posts_per_page( $query ) {

 if ( $query->is_archive('vehicle') ) {
 	global $wp_query;

           $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
           //query_posts('post_type=vehicle&posts_per_page=2&paged='.$paged);
           $loop = new WP_Query( array( 'post_type' => 'vehicle', 'posts_per_page' => 2, 'paged'=>$paged ) ); 

           // Pagination fix
$temp_query = $wp_query;
$wp_query   = NULL;
$wp_query   = $loop;

$mypag=get_next_posts_link('Next Entries');

     wp_reset_postdata();

// Reset main query object
$wp_query = NULL;
$wp_query = $temp_query;
}
}
add_action( 'pre_get_posts', 'custom_posts_per_page' );*/


/*-----------------------------------------------------------------------------------*/
/* Vehicle Frontend STYLES and SCRIPTS                                             */
/*-----------------------------------------------------------------------------------*/

// cde portfolio main style
add_action( 'wp_enqueue_scripts', 'cde_main_style' );
function cde_main_style() {
	wp_register_style('cde-portfolio', plugins_url( '/cde-portfolio-light/css/cde-portfolio.css'), array(), '2.0', 'all' );
	wp_enqueue_style( 'cde-portfolio');
}


//add_action('wp_enqueue_scripts', 'cde_scripts_styles'); // included from templates
function cde_scripts_styles(){

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'jquery-effects-core' );

	/* Bootstrap only grid Styles */
	wp_register_style('grid12', plugins_url( '/css/grid12.css', __FILE__ ), array(), '2.0', 'all' );
	wp_enqueue_style( 'grid12');

	/* colorbox Styles */
	wp_register_style('colorbox', plugins_url( '/css/colorbox.css', __FILE__ ), array(), '2.0', 'all' );
	wp_enqueue_style( 'colorbox');

		/* jpages Styles */
	wp_register_style('jPages', plugins_url( '/css/jPages.css', __FILE__ ), array(), '2.0', 'all' );
	wp_enqueue_style( 'jPages');

	/* car-dealer Styles */
	wp_register_style('car-dealer', plugins_url( '/css/car-dealer.css', __FILE__ ), array(), '2.0', 'all' );
	wp_enqueue_style( 'car-dealer');

	wp_register_script('colorbox', plugins_url( '/js/jquery.colorbox-min.js', __FILE__ ), array(), false, true);
	wp_enqueue_script('colorbox');


	wp_register_script('jPages', plugins_url( '/js/jPages.min.js', __FILE__ ), array(), false, true);
	wp_enqueue_script('jPages');

	wp_register_script('modernizer', plugins_url( '/js/modernizr.custom.js', __FILE__ ), array(), false, true);
	wp_enqueue_script('modernizer');

	/* Custom plugin JS */
	wp_register_script('cde-js', plugins_url( '/js/cde-js.js', __FILE__ ), array(), false, true);
	wp_enqueue_script('cde-js');

}

function cde_pfl_filter_scripts_styles(){
	/* Filterable Portfolio Items */
	wp_register_script('jquery-quicksand', plugins_url( '/js/jquery.quicksand.js', __FILE__ ), array(), false, true);
	wp_enqueue_script('jquery-quicksand');
}



/*-----------------------------------------------------------------------------------*/
/* SEARCH WIDGET */
/*-----------------------------------------------------------------------------------*/

// Creating the widget 
class cde_widget extends WP_Widget {

function __construct() {
parent::__construct(
// Base ID of your widget
'cde_widget', 

// Widget name will appear in UI
__('Vehicle Search Widget', 'cde_pgl'), 

// Widget description
array( 'description' => __( 'widget for searching a vehicle', 'cde_pgl' ), ) 
);
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 ?>

<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">


    <!-- PASSING THIS TO TRIGGER THE ADVANCED SEARCH RESULT PAGE FROM functions.php -->
    <input type="hidden" name="search" value="advanced">

    <br /><label for="name" class=""><?php _e( 'Name: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Type the Car Name', 'cge_pgl' ); ?>" name="name" id="name" />

    <br /><label for="maxmil" class=""><?php _e( 'Max Mileage: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Maximum mileage', 'cge_pgl' ); ?>" name="maxmil" id="maxmil" />

    <br /><label for="maxprice" class=""><?php _e( 'Max price: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Maximum Price', 'cge_pgl' ); ?>" name="maxprice" id="maxprice" />

    <br /><label for="minyear" class=""><?php _e( 'Min Year: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Minimum Year of Constr.', 'cge_pgl' ); ?>" name="minyear" id="minyear" />



    <input type="submit" id="searchsubmit" value="Search" />

</form>

 <?php
echo $args['after_widget'];
}
		
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'cde_pgl' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
	
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here

// Register and load the widget
function cde_load_widget() {
	register_widget( 'cde_widget' );
}
add_action( 'widgets_init', 'cde_load_widget' );

/*-----------------------------------------------------------------------------------*/
/* ACTIVATE ADVANCED SEARCH */
/*-----------------------------------------------------------------------------------*/


function cde_load_custom_search_template(){
    if( isset($_REQUEST['search']) == 'advanced' ) {

			if ( $theme_file = locate_template( array ( 'advanced-vehicle-results.php' ) ) ) {
				$template_path = $theme_file;
			} else {
				$template_path = plugin_dir_path( __FILE__ ) . '/templates/advanced-vehicle-results.php';
			}
		require($template_path);
        die();

	}
        
    
}
add_action('init','cde_load_custom_search_template');

add_shortcode( 'cde_advanced_search', 'cde_adv_short' );

/*-----------------------------------------------------------------------------------*/
/* ADVANCED SEARCH SHORTCODE*/
/*-----------------------------------------------------------------------------------*/

function cde_adv_short( $atts ) {

	?>
<form method="get" id="advanced-searchform" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">


    <!-- PASSING THIS TO TRIGGER THE ADVANCED SEARCH RESULT PAGE FROM functions.php -->
    <input type="hidden" name="search" value="advanced">

    <br /><label for="name" class=""><?php _e( 'Name: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Type the Car Name', 'cge_pgl' ); ?>" name="name" id="name" />

    <br /><label for="maxmil" class=""><?php _e( 'Max Mileage: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Maximum mileage', 'cge_pgl' ); ?>" name="maxmil" id="maxmil" />

    <br /><label for="maxprice" class=""><?php _e( 'Max price: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Maximum Price', 'cge_pgl' ); ?>" name="maxprice" id="maxprice" />

    <br /><label for="minyear" class=""><?php _e( 'Min Year: ', 'cge_pgl' ); ?></label><br>
    <input type="text" value="" placeholder="<?php _e( 'Minimum Year of Constr.', 'cge_pgl' ); ?>" name="minyear" id="minyear" />



    <input type="submit" id="searchsubmit" value="Search" />

</form>
	<?php
}


add_shortcode( 'cde_pagin', 'cde_pagin' );

/*-----------------------------------------------------------------------------------*/
/* ADVANCED SEARCH SHORTCODE*/
/*-----------------------------------------------------------------------------------*/

function cde_pagin( $atts ) {

	cde_scripts_styles();

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
<?php
}
?>