<?php
/**
 * Plugin Name: Extra fields
 * job_select: Creates extra fields in the add a job of the Job Manager.
 * Version: 1.4.0
 * Author: Akoor
 * Author URI: mailto:akoor@ziggo.nl
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * License:           GPL v2 or later
 */

/**
 * Prevent direct access data leaks
 **/
if ( ! defined( 'ABSPATH' ) ) {
  exit; 
}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ako_wpjmef_add_support_link_to_plugin_page' );

// Submit form filters
add_filter( 'submit_job_form_fields', 'ako_wpjmef_frontend_add_job_select_field');
add_filter( 'submit_job_form_fields', 'ako_wpjmef_frontend_add_job_offer_field');
// Text fields filters
add_filter( 'job_manager_job_listing_data_fields', 'ako_wpjmef_admin_add_job_select_field' ); // #
add_filter( 'job_manager_job_listing_data_fields', 'ako_wpjmef_admin_add_job_offer_field' );
// Single Job page filters
add_action( 'single_job_listing_meta_end', 'ako_wpjmef_display_job_select_data' );
add_action( 'single_job_listing_meta_end', 'ako_wpjmef_display_job_offer_data' );
// Dashboard: Job Listings > Jobs filters
add_filter( 'manage_edit-job_listing_columns', 'ako_wpjmef_retrieve_job_select_column' );
add_filter( 'manage_job_listing_posts_custom_column', 'ako_wpjmef_display_job_select_column' );

/**
* Sets the job_select metadata as a new $column that can be used in the back-end
**/
function ako_wpjmef_retrieve_job_select_column($columns){

$columns['job_select']         = __( 'job_select', 'extra-field' );
return $columns;

};

/**
* Adds a new case to WP-Job-Manager/includes/admin/class-wp-job-manager-cpt.php
**/

function ako_wpjmef_display_job_select_column($column){

global $post;

switch ($column) {    
  case 'job_select':
    
    $job_select = get_post_meta( $post->ID, '_job_select', true );
    
    if ( !empty($job_select)) {
      echo $job_select;
    } else {
      echo '-';
    
    }
  break;
}

return $column;

};


/**

* Adds a new optional "job_select" text field at the "Submit a Job" form, generated via the [submit_job_form] shortcode
**/
function ako_wpjmef_frontend_add_job_select_field( $fields ) {

$fields['job']['job_select'] = array(
  'label'       => __( 'Select file', 'extra-field' ),
  'type'        => 'file',
  'required'    => false,
  'placeholder' => '',
  'job_select' => '',
  'priority'    => '7',
);

return $fields;

}

/**
* Adds a new optional "target Information" text field at the "Submit a Job" form, generated via the [submit_job_form] shortcode
**/
function ako_wpjmef_frontend_add_job_offer_field( $fields ) {

  $fields['job']['job_job_offer'] = array(
    'label'       => 'Select',
    'type'        => 'select',
    'required'    => false,
    'placeholder' => '',
    'job_select' => '',
    'priority'    => '8',
    'options'  => array(
      'Kies een optie' => 'Kies een optie',
     'www.google.com' =>'Google',
      'www.microsoft.com' => 'Microsoft',
       'www.apple.com' => 'Apple',
      )
    );


  return $fields;
}

/**
* Adds a text field to the Job Listing wp-admin meta box named “job_select”
**/
function ako_wpjmef_admin_add_job_select_field( $fields ) {

$fields['_job_select'] = array(
  'label'       => __( 'Job vacancy information', 'extra-field' ),
  'type'        => 'file',
  'placeholder' => '',
  'job_select' => ''
);

return $fields;

}

/**
* Adds a text field to the Job Listing wp-admin meta box named "target Information"
**/
function ako_wpjmef_admin_add_job_offer_field( $fields ) {

$fields['_job_job_offer'] = array(
  'label'       => __( 'Target information', 'extra-field' ),
  'type'        => 'select',
  'placeholder' => '',
  'job_select' => ''
);

return $fields;

}

/**
* Displays "job_select" on the Single Job Page, by checking if meta for "_job_select" exists and is displayed via do_action( 'single_job_listing_meta_end' ) on the template
**/
function ako_wpjmef_display_job_select_data() {

global $post;

$job_select = get_post_meta( $post->ID, '_job_select', true );
$job_offer = get_post_meta( $post->ID, '_job_job_offer', true );




if ( $job_select ) {

  echo '<br><p><br><br>Details job offer:<a href="'.( $job_select ). ' " target="_blank">&nbsp;&nbspClick here </a></p>';
}

}

/**
* Displays the content of the "target Information" text-field on the Single Job Page, by checking if meta for "_job_job_offer" exists and is displayed via do_action( 'single_job_listing_meta_end' ) on the template
**/
function ako_wpjmef_display_job_offer_data() {

global $post;

$job_offer = get_post_meta( $post->ID, '_job_job_offer', true );

if ( $job_offer ) {

  if (get_post_meta($post->ID, '_job_job_offer', true) === 'www.google.com') {
    echo "Google:<br>";
  }
 elseif (get_post_meta($post->ID, '_job_job_offer', true) === 'www.microsoft.com') {
    echo "Microsoft:<br>";
  }
  elseif (get_post_meta($post->ID, '_job_job_offer', true) === 'www.apple.com') {
    echo "Apple<br>";
  }
echo '<a href= "//'.rawurldecode($job_offer).  ' " target="_blank">&nbsp;'.$job_offer.'</a><br>';
}
}

/**
* Display an error message notice in the admin if WP Job Manager is not active
*/
function ako_wpjmef_admin_notice__error() {

$class = 'notice notice-error';
$message = __( 'An error has occurred. WP Job Manager must be installed in order to use this plugin', 'extra-field' );

printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 

}
