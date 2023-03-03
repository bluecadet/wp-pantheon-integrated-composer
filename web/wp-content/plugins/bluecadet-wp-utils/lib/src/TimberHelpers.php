<?php

/**
 * Require Timber to be activated
 *
 */
class TimberHelpers {

  public function __construct() {
    add_filter( 'timber/twig', [$this, 'BC__Timber_Add_Functions'] );
    add_action('wp_enqueue_scripts', [$this, 'BC__TimberHelpers_CSS']);
  }


  public function BC__TimberHelpers_CSS() {

    if ( ! class_exists( 'Timber' ) || !is_user_logged_in() ) {
    	return;
    }

    wp_register_style( 'bc_timber_helpers', plugins_url('assets/dist/css/timberhelpers.css', dirname(__FILE__, 2) ) );
    wp_enqueue_style( 'bc_timber_helpers' );
    wp_register_script( 'bc_timber_helpers', plugins_url('assets/dist/js/timberhelpers.js', dirname(__FILE__, 2) ), array('jquery'));
    wp_enqueue_script( 'bc_timber_helpers');
  }


  /**
   * My custom Twig functionality.
   *
   * @param \Twig\Environment $twig
   * @return \Twig\Environment
   */
  public function BC__Timber_Add_Functions( $twig ) {
      // For the Drupal folks to dump out vars :)
      // Use ksm(_context) to print all variables
      $twig->addFunction( new Timber\Twig_Function( 'print', [$this, 'BC__Print_Timber_Vars'] ) );
      $twig->addFunction( new Timber\Twig_Function( 'kint', [$this, 'BC__Print_Timber_Vars'] ) );
      $twig->addFunction( new Timber\Twig_Function( 'acf_flex_templates', [$this, 'BC__ACF_Template_From_Array'] ) );
      $twig->addFunction( new Timber\Twig_Function( 'acf_rep_templates', [$this, 'BC__ACF_Template_From_Array'] ) );

      return $twig;
  }


  /**
   * Fake KSM
   *
   */
  public function BC__Print_Timber_Vars($data) {

    if ( !is_user_logged_in() ) {
      return;
    }

    $values = $this->BC__Recursive_Get_Vars($data);
    echo $this->BC__KSM_Esque($values);
  }

  /**
   * Make a nice array to iterate over
   *
   */
  private function BC__Recursive_Get_Vars($data, $int = 0) {
    $nice_data = [];
    $data_type = gettype($data);

    if ( $int === 0 ) {
      if ( $data_type === 'array' || $data_type === 'object' ) {
        $nice_data['list_title'] = $data_type;
      } else {
        $title = '<code class="ksm-esque__code-type ksm-esque__code-type--' . $data_type . '">' . $data_type;
        $title .= $data_type === 'string' ? '<span class="ksm-esque__length">[' . strlen($data) . ']</span>' : '';
        $title .= '</code> => ' . $data;

        $nice_data['list_title'] = $title;
        $nice_data['single_item'] = true;
      }
    }

    $int++;

    if ( $data_type === 'object' ) {

      $array = get_object_vars($data);
      $properties = array_keys($array);

      foreach ($properties as $i => $name) {
        $data_prop = $data->$name;
        $type = gettype($data_prop);

        $nice_data[$name] = [];
        $nice_data[$name]['type'] = $type;

        if ( $type === 'object' || $type === 'array' ) {
          $nice_data[$name]['value'] = $this->BC__Recursive_Get_Vars($data_prop, $int);
        } else {
          $nice_data[$name]['value'] = $data_prop;
        }
      }

    } elseif ($data_type === 'array' ) {

      foreach( $data as $name => $prop ) {
        $type = gettype($prop);
        $nice_data[$name] = [];
        $nice_data[$name]['type'] = $type;

        if ( $type === 'object' || $type === 'array' ) {
          $nice_data[$name]['value'] = $this->BC__Recursive_Get_Vars($prop, $int);
        } else {
          $nice_data[$name]['value'] = $prop;
        }
      }
    }

    return $nice_data;
  }

  /**
   * Start KSM-style HTML
   *
   */
  private function BC__KSM_Esque($values) {
    $is_single_item = isset($values['single_item']) ? true : false;
    $single_class = $is_single_item ? ' ksm-esque__item--single' : '';

    $html = '<div class="ksm-esque ksm-esque__item' . $single_class  .'">';


    if( $values['list_title'] ) {

      $p_class = $is_single_item ? 'ksm-esque__title ksm-esque__title--no-button' : 'ksm-esque__title ksm-esque__title--button';

      $html .= '<p class="' . $p_class . '">';

      if( $is_single_item ) {
        $html .= $values['list_title'] . '</p>';
      } else {
        $html .= '<button class="ksm-esque__toggle">+</button>' . $values['list_title'] . '</p>';
      }

      unset($values['list_title']);
    }

    if( !$is_single_item ) {

      $html .= '<ul class="ksm-esque__sub-content">';

      foreach ($values as $title => $children) {
        $html .= $this->BC__Recursive_Get_HTML($title, $children);
      }

      $html .= '</ul>';
    }

    $html .= '</div>';

    return $html;

  }

  /**
   * Recursive HTML for inner KSM items
   *
   */
  private function BC__Recursive_Get_HTML($title, $children) {
    $type = $children['type'];
    $val  = $children['value'];
    $html = '<li class="ksm-esque__item">';

    $show_button = $type === 'array' || $type === 'object' || ( $type === 'string' && strlen($val) > 100 );
    $p_class = $show_button ? 'ksm-esque__title ksm-esque__title--button' : 'ksm-esque__title ksm-esque__title--no-button';

    $html .= '<p class="' . $p_class . '">';

    if ( $show_button ) {
      $html .= '<button class="ksm-esque__toggle">+</button>';
    }

    $html .= '<code class="ksm-esque__code-title">' . $title . '</code> <code class="ksm-esque__code-type ksm-esque__code-type--' . $type . '">' . $type;

    if( $type === 'array' ) {
      $html .= '<span class="ksm-esque__length">[' . count($val) . ']</span>';
    }

    $html .= '</code>';

    if ( $type === 'array' || $type === 'object' ) {

      $html .= '</p><ul class="ksm-esque__sub-content">';
      foreach ($val as $title => $children) {
        $html .=    $this->BC__Recursive_Get_HTML($title, $children);
      }
      $html .=  '</ul>';
    } else {

      if ( $type === 'string' ) {
        $length = strlen($val);
        $html .= '<span class="ksm-esque__length">[' . $length . ']</span>';
        if ( $length > 100 ) {
          $html .= '<div class="ksm-esque__sub-content ksm-esque__sub-content--string-content">';
          $html .= '<p class="ksm-esque__value">' . $val . '</p>';
          $html .= '</div>';
        } else {
          $html .= ' => ' . $val . '</p>';
        }
      } else {
        $html .= ' => ' . $val . '</p>';
      }
    }

    $html .= '</li>';

    return $html;
  }


  /**
   * Render child field templates from a flexible content or repeater field.
   *
   * @param [string] $path - path to parent folder where templates live
   * @param [array] $fields - ACF Flexible Content or Repeater field values (`content` key in template)
   * @param [array] $shared - Shared data passed to all templates (`shared` key in template)
   * @param [array] $omit - Array of machine names to omit
   * @return rendered content
   *
   * Assumptions:
   *  - A template file exists within the `$path` variable, and the template
   *    name matches the value of `acf_fc_layout` (the value of the fields
   *    machine name)
   *  - Field data is passes to the template in a `content` key.
   *  - Shared data can be passed to each template as an optional third parameter
   *
   * Example:
   *
   * In a template we call: `{{ acf_flex_templates('acf/content-blocks', fields) }}`, where
   * `fields` is the value of an ACF Flexible Content field, which has "Image Callout" and
   * "Content Section" layouts (with row machine names `image_callout` and `content_section`).
   *
   * This means the file structure requires:
   * [theme_folder]
   * |- templates (or default Timber directory (`views`, etc))
   * |  |- acf
   * |     |- content-blocks
   * |        |- image_callout.twig
   * |        |- content_section.twig
   *
   * `content_section.twig` can then reference `content.[field_machine_name]` for field
   * values. If shared data was passed, that can be referenced by `shared.[key_value]`.
   *
   */
  public function BC__ACF_Template_From_Array($path, $fields, $shared = false, $omit = false) {

    if ( !is_array($fields) ) {
      throw new \Exception('Parameter 2 ($fields) should be an array');
    }

    foreach ($fields as $idx => $field) {
      $context = [];
      $context['content'] = $field;

      if ($shared) {
        $context['shared'] = $shared;
      }

      if ( $omit ) {
        if ( !is_array($omit) ) {
          throw new \Exception('Parameter 4 ($omit) should be an array');
        }

        if (in_array($field['acf_fc_layout'], $omit) ) {
          continue;
        }
      }

      Timber::render( [$path . '/' . $field['acf_fc_layout'] . '.twig'], $context);
    }

  }


}


new TimberHelpers;
