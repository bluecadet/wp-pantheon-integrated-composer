<?php
/**
 * Plugin Name: Bluecadet TinyMCE
 * Plugin URI: https://bluecadet.com
 * Version: 1.0
 * Author: Shaun M Baer
 * Author URI: https://bluecadet.com
 * Description: A simple TinyMCE Plugin to add a custom link class in the Visual Editor
 * License: GPL2
 */

class TinyMCE_Custom_Link_Class {

    private $plugin_url;

    function __construct() {
      if ( is_admin() ) {
        $this->plugin_url = plugin_dir_url( __FILE__ );

        add_action( 'init', array(  $this, 'Setup_TinyMCE_Plugin' ) );
        add_action('acf/render_field_settings/type=wysiwyg', [$this, 'Add_Custom_WYSIWYG_Settings']);
        add_action('acf/render_field/type=wysiwyg', [$this, 'Add_Custom_WYSIWYG_Render']);
        add_action('admin_head', [$this, 'Add_Warning_CSS_To_Head']);
        add_filter('mce_buttons_2', [$this, 'Add_Buttons']);
        add_filter('tiny_mce_before_init', [$this, 'Add_Custom_Styles'], 10);

      }
    }


    function Add_Buttons($buttons) {
        array_unshift($buttons, 'styleselect');
        return $buttons;
    }


    function Add_Custom_Styles($settings) {
        // $settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';
        $settings['theme_advanced_blockformats'] = '';

        // From http://tinymce.moxiecode.com/examples/example_24.php
        $style_formats = [
            // ['title' => 'Heading 2', 'block' => 'p', 'classes' => 'u-wysiwyg__h2'],
            ['title' => 'Heading 1', 'block' => 'p', 'classes' => 'u-wysiwyg__h3'],
            ['title' => 'Heading 2', 'block' => 'p', 'classes' => 'u-wysiwyg__h4'],
            ['title' => 'Heading 3', 'block' => 'p', 'classes' => 'u-wysiwyg__h5'],
            ['title' => 'Heading 4', 'block' => 'p', 'classes' => 'u-wysiwyg__h6'],
            ['title' => 'Q and A',   'block' => 'p', 'classes' => 'u-wysiwyg__q-a'],
        ];
        // Before 3.1 you needed a special trick to send this array to the configuration.
        // See this post history for previous versions.
        $settings['style_formats'] = json_encode( $style_formats );

        return $settings;
    }

    /**
     * Check if the current user can edit Posts or Pages, and is using the Visual Editor
     * If so, add some filters so we can register our plugin
     *
     */
    function Setup_TinyMCE_Plugin() {

      // Check if the logged in WordPress User can edit Posts or Pages
      // If not, don't register our TinyMCE plugin
      if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
      }

      // Check if the logged in WordPress User has the Visual Editor enabled
      // If not, don't register our TinyMCE plugin
      if ( get_user_option( 'rich_editing' ) !== 'true' ) {
        return;
      }

      // Setup some filters
      add_filter( 'mce_external_plugins', array( &$this, 'Add_TinyMCE_Plugin' ) );

    }


    /**
     * Add the plugin js
     *
     */
    function Add_TinyMCE_Plugin( $plugin_array ) {

      $plugin_array['charactercount'] = plugin_dir_url( __FILE__ ) . 'bluecadet-tinymce.js';
      return $plugin_array;

    }


    /**
     * Add Custom settings to WYSIWYG fields
     *
     * @param array $field
     */
    function Add_Custom_WYSIWYG_Settings( $field ) {

      // Add Warn at Character Count setting to WYSIWYG fields
      acf_render_field_setting( $field, array(
        'label'			=> __('Warn at Character Count'),
        'instructions'	=> __('Number of words before warning appears'),
        'name'			=> 'want_character_count',
        'type'			=> 'number',
      ));

      // Add Height for WYSIWYG
      acf_render_field_setting( $field, array(
        'label'			=> __('Height of Editor'),
        'instructions'	=> __('Pixel value (number only, no px)'),
        'name'			=> 'wysiwyg_height',
        'type'			=> 'number',
      ));

    }


    /**
     * Add custom rendering to ACF WYSIWYG fields
     *
     * @param [type] $field
     */
    function Add_Custom_WYSIWYG_Render( $field ) {

      // Add Character Warn Message
      if ( isset($field['want_character_count']) && !empty($field['want_character_count']) ) {
        echo '<span class="acf-char-count-warn" data-charactercount="' . $field['want_character_count'] . '" hidden>';
        echo 'The current character limit for <span>' . $field['label'] .'</span> is over the recommended limit of ' . $field['want_character_count'];
        echo '</span>';
      }

      // Add custom WYSIWYG height
      if ( isset($field['wysiwyg_height']) && !empty($field['wysiwyg_height']) ) {

        $field_class = '.acf-'.str_replace('_', '-', $field['key']);
        ?>
          <style type="text/css">
          <?php echo $field_class; ?> iframe {
            min-height: <?php echo $field['wysiwyg_height']; ?>px;
            height: <?php echo $field['wysiwyg_height']; ?>px;
          }
          </style>
          <script type="text/javascript">
          jQuery(window).load(function() {
            jQuery('<?php echo $field_class; ?>').each(function() {
              jQuery('#'+jQuery(this).find('iframe').attr('id')).height( <?php echo $field['wysiwyg_height']; ?> );
            });
          });
          </script>
        <?php
      }

      return $field;
    }



    /**
     * Add css to the head of the admin page, all janky like
     *
     */
    function Add_Warning_CSS_To_Head() {
      echo '<style>
        .acf-char-count-warn {
          display: none;
          margin-top: 10px;
          padding: 8px 12px;
          border: 1px solid #DC3232;
          color: #DC3232;
          font-style: italic;
        }

        .acf-char-count-warn span {
          font-style: normal;
        }

        .acf-char-count-warn.show-warn {
          display: block !important;
        }

        .mce-charactercount.show-warn {
          color: #DC3232
        }
      </style>';
    }




 }

 $tinymce_custom_link_class = new TinyMCE_Custom_Link_Class;
