<?php
/*
Plugin Name: Visual Composer Modal Popup
Description: Extends Visual Composer with a modal element shortcode.
Version: 0.1.0
Author: Scott Gustas
License: GPLv2 or later
*/

// don't load directly
if (!defined('ABSPATH')) die('-1');

class VCExtendAddonClass {
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
 
        // Use this when creating a shortcode addon
        add_shortcode( 'vc_modal', array( $this, 'renderModal' ) );

        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );

        // Set up image size
        add_action( 'init', 'vcmodal_image_setup' );
        function vcmodal_image_setup() {
            add_image_size( 'vc-modal', 356, 220, true ); // (cropped)
        }
    }
 
    public function integrateWithVC() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
 
        /*
        Add your Visual Composer logic here.
        Lets call vc_map function to "register" our custom shortcode within Visual Composer interface.

        More info: http://kb.wpbakery.com/index.php?title=Vc_map
        */
        vc_map( array(
            "name" => __("Modal Popup", 'vc_extend'),
            "description" => __("Adds a block that displays a preview and opens the full text in a modal window.", 'vc_extend'),
            "base" => "vc_modal",
            "class" => "",
            "controls" => "full",
            "icon" => plugins_url('assets/popup.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
            "category" => __('Content', 'js_composer'),
            "params" => array(
                array(
                  "type" => "textfield",
                  "holder" => "div",
                  "class" => "",
                  "heading" => __("Name", 'vc_extend'),
                  "param_name" => "name",
                  "description" => __("Name of Person", 'vc_extend')
              ),
              array(
                  "type" => "attach_image",
                  "heading" => __("Attach Image", 'vc_extend'),
                  "param_name" => "image",
                  "description" => __("Attach an image", 'vc_extend')
              ),
              array(
                  "type" => "textarea_html",
                  "heading" => __("Description", 'vc_extend'),
                  "param_name" => "content",
                  "description" => __("Enter your description.", 'vc_extend')
              ),
            )
        ) );
    }
    
    /*
    Shortcode logic how it should be rendered
    */
    public function renderModal( $atts, $content = null ) {
      extract( shortcode_atts( array(
        'name' => 'Section Title',
        'image' => 'assets/blank.png'
      ), $atts ) );
      $content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content

      if ( $image == 'assets/blank.png' ) {
        $img_string = "<img src='" . plugins_url($image, __FILE__) . "' alt='" . $name . "' />";
      } else {
        $img_src = wp_get_attachment_image_url( $image, array(356, 220) );
        $img_string = "<img src='" . esc_url( $img_src ) . "' alt='" . $name . "' />";
      }

      $output = "<div class='expand_vc_modal'>";
      $output .= "<div class='vc_modal'><div class='vc_modal_image'>" . $img_string . "</div><div class='vc_modal_name'>{$name}</div><div class='vc_modal_description condensed'>{$content}</div></div>";
      $output .= "<div class='modal hide_modal'><div class='modal-content'><div class='vc_modal_image modal-left'>" . $img_string . "</div><div class='modal-right'><span class='close'>x</span><div class='vc_modal_name'>{$name}</div><div class='vc_modal_description'>{$content}</div></div></div></div>";
      $output .= "</div>";
      return $output;
    }

    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'vc_extend_style', plugins_url('assets/vc_modal.css', __FILE__) );
      wp_enqueue_style( 'vc_extend_style' );

      // If you need any javascript files on front end, here is how you can load them.
      wp_enqueue_script( 'vc_extend_js', plugins_url('assets/vc_modal.js', __FILE__), array('jquery') );
    }

    /*
    Show notice if your plugin is activated but Visual Composer is not
    */
    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_extend'), $plugin_data['Name']).'</p>
        </div>';
    }
}
// Finally initialize code
new VCExtendAddonClass();