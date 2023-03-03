<?php

namespace Bluecadet\Utils;

/**
 * Create Custom Taxonomies
 * For settings, refer to http://generatewp.com/taxonomy/
 * Multiple Taxonomies can be added to this function
 *
 * @package BluecadetUtils
 * @since  1.0.0
 */
class Taxonomies {

  public function __construct() {
    // Register Taxonomies
    add_action( 'init', [$this, 'Register_Taxonomies'] );
  }

  /**
   * Register Custom Taxonomies
   * --------------------------
   *
   */
  public function Register_Taxonomies() {

    /**
     * Person
     *
     */
    $labels = new LabelMaker('Sample Taxonomy Type');
    $labels = $labels->labels;

    $rewrite = array(
      'slug'                       => 'sample-taxonomy/rewrite',
      'with_front'                 => false,
      'hierarchical'               => false,
    );

    $args = array(
      'labels'                     => $labels,
      'hierarchical'               => true,
      'public'                     => true,
      'show_ui'                    => true,
      'show_admin_column'          => true,
      'show_in_nav_menus'          => true,
      'show_tagcloud'              => true,
      'rewrite'                    => $rewrite,
    );

    register_taxonomy( 'sample_taxonomy_type', ['sample-post-type'], $args );

  }
}

new Taxonomies;
