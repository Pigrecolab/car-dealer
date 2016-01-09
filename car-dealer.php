<?php
/*
Plugin Name: PL Car dealer
Plugin URI: http://www.pigrecolab.com
Description: Car dealer custom plugin
Version: 1.0
Author: Roberto Bruno
Author URI: http://www.pigrecolab.com
 License: GPL2 or later
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



/**
 * Create custom settings page.
 */

require_once dirname( __FILE__ ) . '/classes/cde_admin.php';

/**
 * Create metabox for vehicle custom post type.
 */

$cde_opt=get_option( 'cde_options');
$cde_mil_abb=$cde_opt['cde_mil_abb'];
$cde_mon_sym=$cde_opt['cde_mon_sym'];




add_action( 'cmb2_init', 'cde_register_vehicle_metabox' );

function cde_register_vehicle_metabox() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cde_';

global $cde_mil_abb;
global $cde_mon_sym;

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
		'type'    => 'text_small',
				'before_field' => $cde_mil_abb
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
		'before_field' => $cde_mon_sym
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

	global $cde_mil_abb;
global $cde_mon_sym;

	$prefix="_cde_";

	switch( $column ) {
		/* If displaying the 'price' column. */
		case 'price' :

		/* Get the post meta. */
		$price = get_post_meta( $post_id, $prefix.'price', true );

		/* If no price is found, output a default message. */
		if ( empty( $price ) )
			echo __( 'Unknown' );

		/* If there is a price, append 'symbol' to the text string. */
		else
			printf( __( $cde_mon_sym.' %s' ), $price );

		break;

		/* If displaying the 'year' column. */
		case 'year' :

		/* Get the post meta. */
		$year = get_post_meta( $post_id, $prefix.'year', true );

		/* If no mileage is found, output a default message. */
		if ( empty( $year ) )
			echo __( 'Unknown' );

		/* If there is a mileage, append 'mil' to the text string. */
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
			printf( __( $cde_mil_abb.' %s' ), $mileage );

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



/*-----------------------------------------------------------------------------------*/
/* ADVANCED SEARCH SHORTCODE*/
/*-----------------------------------------------------------------------------------*/

add_shortcode( 'cde_advanced_search', 'cde_adv_short' );

function cde_adv_short( $atts ) {

$advshrt='<form method="get" id="advanced-searchform" role="search" action="'.esc_url( home_url( '/' ) ).'">


    <!-- PASSING THIS TO TRIGGER THE ADVANCED SEARCH RESULT PAGE FROM functions.php -->
    <input type="hidden" name="search" value="advanced">

    <br /><label for="name" class="">'. __( 'Name: ', 'cde_pgl' ).'</label><br>
    <input type="text" value="" placeholder="'. __( 'Type the Car Name', 'cde_pgl' ).'" name="name" id="name" />

    <br /><label for="maxmil" class="">'. __( 'Max Mileage: ', 'cde_pgl' ).'</label><br>
    <input type="text" value="" placeholder="'. __( 'Maximum mileage', 'cde_pgl' ).'" name="maxmil" id="maxmil" />

    <br /><label for="maxprice" class="">'. __( 'Max price: ', 'cde_pgl' ).'</label><br>
    <input type="text" value="" placeholder="'. __( 'Maximum Price', 'cde_pgl' ).'" name="maxprice" id="maxprice" />

    <br /><label for="minyear" class="">'. __( 'Min Year: ', 'cde_pgl' ).'</label><br>
    <input type="text" value="" placeholder="'. __( 'Minimum Year of Constr.', 'cde_pgl' ).'" name="minyear" id="minyear" />



    <input type="submit" id="searchsubmit" value="Search" />

</form>';
	
	return $advshrt;
}

/*-----------------------------------------------------------------------------------*/
/* LAST VEHICLES SHORTCODE*/
/*-----------------------------------------------------------------------------------*/

add_shortcode( 'cde_last_vehicles', 'cde_adv_last' );

function cde_adv_last( $atts ) {

cde_scripts_styles(); 

$r = new WP_Query( array(
   'posts_per_page' => 6,
   'no_found_rows' => true, /*suppress found row count*/
   'post_status' => 'publish',
   'post_type' => 'vehicle',
   'ignore_sticky_posts' => true,
) );


global $cde_mil_abb;
global $cde_mon_sym;

	$cnt=0;
	$shrt="";
    if ($r->have_posts()){
     while ($r->have_posts()) : $r->the_post();
         $shrt.=" <div class=\"col-sm-6 col-md-4 \">";
           $shrt.="<div class=\"cde_thumbnail cde_grid\">";
              if ( has_post_thumbnail()) { 
//Get the Thumbnail URL
                $src_orig = wp_get_attachment_image_src( get_post_thumbnail_id($r->post->ID), 'full', false, '' );
                $src_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($r->post->ID), 'cde_size', false, '' );

               
                $shrt.="<figure><a href=\"".$src_orig[0]."\" rel=\"gallery\" class=\"thumb\"><img src=\"".$src_thumb[0]."\" /></a>";
                   } else { 
                  $shrt.="<div style=\"background:url(".plugins_url( '/car-dealer/images/pattern-1.png' ) .");width:".get_option('cde_thumb_size_w', '303')."px;height:". get_option('cde_thumb_size_h', '210')."px\" title=\"". __( 'No Image', 'cde_pgl' )."\"></div>";
                 } 
                  $shrt.="<figcaption>
                    <h4>";
                $prefix = '_cde_';
                     $mileage = get_post_meta( get_the_ID(), $prefix.'mileage', true );
                     $year = get_post_meta( get_the_ID(), $prefix.'year', true );
                     $price = get_post_meta( get_the_ID(), $prefix.'price', true );
                     $shrt.= $cde_mon_sym." ".$price;
                   $shrt.="</h4><br /><span>".sprintf( __( '<strong>Year: </strong> %s', "cde_pgl" ), $year );
                    $shrt.="<br>";
                      $shrt.=sprintf( __( '<strong>'.$cde_mil_abb.' : </strong> %s', "cde_pgl" ), $mileage )."</span><br>
                    <a href=\"".get_the_permalink()."\">".__("Take a look", "cde_pgl")."</a>";
                 $shrt.=" </figcaption>";
                $shrt.="</figure>";
                $shrt.="<div class=\"caption\">
                  <h3>        <a href=\"".get_the_permalink()."\" title=\"". get_the_title()."\">". get_the_title()."</a></h3>";
                  $shrt.="<p>".get_the_excerpt()."</p>";
                $shrt.="</div>";
             $shrt.=" </div>";
           $shrt.=" </div>";

            $cnt+=1;
            if ($cnt==3) {
            	         $shrt.="<div class=\"cde_clearfix\"></div>";
            	        $cnt=0;
            }
            
          endwhile; 

          $shrt.="<div class=\"cde_clearfix\"></div>";
}
return $shrt;
}

