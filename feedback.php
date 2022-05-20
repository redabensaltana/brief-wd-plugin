<?php

/**
 * @package feedback-plugin
 * /

/* 
    Plugin Name: feedback
    Plugin URI: http://reda.com/plugin
    Description: A simple way to add a feedback part to your website
    Version: 1.0.0
    Author: reda bensaltana
    Author URI: https://github.com/redabensaltana
    License: GPLv2 or later
    Text Domain: feedback-plugin

 */

//  if( ! defined('ABSPATH') ){
//    die;
//  }
defined('ABSPATH') or die('you do not have access to this file');

class feedBackPlugin
{
   function __construct()
   {
      add_action('init', array($this, 'custom_post_type'));
      add_action('wp_enqueue_scripts', array($this, 'load_assets'));
      add_shortcode('feedback-form', array($this, 'load_shortcode'));
   }

   function activate()
   {
      //generated a CPT
      $this->custom_post_type();
      //flush-rewrite_rules
      flush_rewrite_rules();
   }
   function deactivate()
   {
      //flush-rewrite_rules
      flush_rewrite_rules();
   }
   function uninstall()
   {
   }
   function custom_post_type()
   {
      $arr = array(
         'public' => true,
         'has_archive' => true,
         'supports' => array('title'),
         'exclude_from_search' => true,
         'publicly_queryable' => false,
         'capability' => 'manage_options',
         'labels' => array(
            'name' => 'feedback',
            'singular_name' => 'Contact Form Entry',
         ),
         'menu_icon' => 'dashicons-feedback',
      );
      register_post_type('feedBackPlugin', $arr);
   }
   public function load_assets()
   {
      wp_enqueue_style(
         'feedBackPlugin',
         plugin_dir_url(__FILE__) . 'css/style.css',
         array(),
         1,
         'all'
      );
      wp_enqueue_script(
         'cosmic-plugin',
         plugin_dir_url(__FILE__) . 'js/script.js',
         array(),
         1,
         'all'
      );
   }
   public function load_shortcode()
   {
?>
      <script src="https://cdn.tailwindcss.com"></script>
      <div class="flex justify-center">
         <form style="width: 500px; height:400px;" action="" method="POST" id="form" class=" flex flex-col items-center rounded-lg p-5">
            <div class="relative z-0 w-full mb-6 group">
               <input type="text" name="name" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="Name" required />
            </div>

            
            <div class="relative z-0 w-full mb-6 group">
                <input type="text" name="Review" id="Review" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="Review" required />
                <input type="hidden" name="id" value="<?php echo get_the_ID() ?> ">
            </div>
            
            <div class="relative z-0 w-full mb-6 group">
               <div class="flex flex-row-reverse justify-end ">
                  <input type="number" max="5" min="1" name="rating" id="Rating" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder="rating" required />
               </div>
            </div>

            <button type="submit" name="submit" class="text-white bg-orange-400 hover:bg-orange-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-orange-600 dark:hover:bg-orange-700 dark:focus:ring-orange-800">Submit</button>
         </form>
      </div>




<?php


   }
}





if (class_exists('feedBackPlugin')) {
   $feedBackPlugin = new feedBackPlugin();
}
//activation
register_activation_hook(__FILE__, array($feedBackPlugin, 'activate'));
//deactivation
register_deactivation_hook(__FILE__, array($feedBackPlugin, 'deactivate'));

global $wpdb;

if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $rating = $_POST['rating'];
   $review = $_POST['Review'];
   $id = $_POST['id'];
   $wpdb->insert('wp_feedback', array('Name' => $name, 'Rating' => $rating, 'Review' => $review, 'post_id' => $id));
   echo " <script> alert('We received your review') </script>";
   header('refresh:0', 'Location: ' . $_SERVER['HTTP_REFERER']);
   exit();
}