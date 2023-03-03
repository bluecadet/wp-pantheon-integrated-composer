<?php

namespace Bluecadet\Utils;

/**
 * Create Custom Post Types
 *
 * @package BluecadetUtils
 * @since  1.0.0
 *
 */
class PostTypes {

  private $templates;

  public function __construct() {
    add_action( 'init', [$this, 'Register_CPTs']);

    // Change default Post labels to something else
    // add_action( 'init', [$this, 'BC__Change_Post_Object_Label'] );
    // add_action( 'admin_menu', [$this, 'BC__Change_Post_Menu_Label'] );

  }

  /**
   * Register Custom Post Types
   * --------------------------
   *
   */
  public function Register_CPTs() {


    /**
     * People
     *
     */
    $labels = new LabelMaker('Sample Post Type');
    $labels = $labels->labels;

    $rewrite = array(
      'slug'                  => 'sample/reqrite',
      'with_front'            => false,
      'pages'                 => true,
    );

    $args = array(
      'label'                 => $labels['name'],
      'labels'                => $labels,
      'supports'              => ['title', 'thumbnail', 'excerpt', 'slug', 'revisions'],
      'taxonomies'            => [],
      'hierarchical'          => false,
      'public'                => true,
      'menu_position'         => 20,
      'has_archive'           => true,
      'rewrite'               => $rewrite,
      'show_in_nav_menus'			=> false,
      'show_in_rest'          => true,
      'menu_icon'             => 'dashicons-superhero',
    );

    register_post_type( 'sample-post-type', $args );

  }


  /**
   * Change default Post type to Feature
   *
   * @return void
   */
  // public function BC__Change_Post_Menu_Label() {
  //   global $menu;
  //   global $submenu;
  //   $menu[5][0] = 'Features';
  //   $submenu['edit.php'][5][0] = 'Features';
  //   $submenu['edit.php'][10][0] = 'Add Feature';
  //   echo '';
  // }

  /**
   * Change default Post labels to Feature
   *
   * @return void
   */
  // public function BC__Change_Post_Object_Label() {
  //   global $wp_post_types;
  //   $labels = &$wp_post_types['post']->labels;
  //   $labels->name = 'Features';
  //   $labels->singular_name = 'Feature';
  //   $labels->add_new = 'Add Feature';
  //   $labels->add_new_item = 'Add Feature';
  //   $labels->edit_item = 'Edit Feature';
  //   $labels->new_item = 'Feature';
  //   $labels->view_item = 'View Feature';
  //   $labels->search_items = 'Search Features';
  //   $labels->not_found = 'No Features found';
  //   $labels->not_found_in_trash = 'No Features found in Trash';
  //   $labels->name_admin_bar = 'Add Feature';
  // }

}

new PostTypes;

