<?php

namespace Bluecadet\Utils\Analytics;

/**
 * Google Analytics ID Field to WP Settings
 * ----------------------------------------
 * Add a new section to the General Settings Page
 *
 * @package BluecadetUtils
 * @since  1.0.0
 */


class GoogleAnalyticsForm {

  public function __construct() {
    add_action( 'admin_init', [$this, 'Google_Analytics_ID_Settings_Section'] );

    if ( get_option('ga_id') ) {
      add_action('wp_head', [$this, 'Google_Analytics_Script']);
    }
  }

  /**
   * Add form data to admin
   *
   */
  public function Google_Analytics_ID_Settings_Section() {

    // Add section to Admin Settings
    add_settings_section(
      'ga_id_section',
      'Google Analytics',
      [$this, 'Google_Analytics_ID_Callback'],
      'general'
    );

    // Add an analytics Form Field
    add_settings_field(
      'ga_id',
      'Google Analytics ID',
      [$this, 'Google_Analytics_ID_Textbox_Callback'],
      'general',
      'ga_id_section',
      array('ga_id')
    );

    // Register the field
    register_setting(
      'general',
      'ga_id',
      'esc_attr'
    );

  }

  /**
   * Callback for Google Analytics ID field
   *
   * @since  1.0.0
   */
  public function Google_Analytics_ID_Callback() {
    echo  '<p>Enter your '.
          '<a href="https://support.google.com/analytics/answer/1032385?hl=en" target="blank">' .
          'Google Analytics UA ID'.
          '</a> number to allow tracking.</p>';
  }


  /**
   * Save Analytics Field
   *
   * @since 1.0.0
   */
  public function Google_Analytics_ID_Textbox_Callback( $args ) {
    $option = get_option($args[0]);
    echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
  }


  /**
   * Add Google Analytics to Site Footer
   *
   * Returns analytics in wp_head if enviornment is production
   * and user is not admin AND Google Analytics is setup in options
   *
   * @since 1.0.0
   *
   */
  public function Google_Analytics_Script() {
    if ( !current_user_can('manage_options') ) : ?>
      <script async src="https://www.googletagmanager.com/gtag/js?id=<?= get_option('ga_id') ?>"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?= get_option('ga_id') ?>');
      </script>
    <?php endif;
  }


}

new GoogleAnalyticsForm;
