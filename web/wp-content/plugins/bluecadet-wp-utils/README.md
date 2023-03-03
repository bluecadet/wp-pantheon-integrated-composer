# Bluecadet Utils

- Create custom post types and custom taxonomies via plugin (instead of the Theme)
- Adds Google Analytics field to admin settings, outputs script in wp_head if option is set

## Post Types and Taxonomies

Add post types to the `Register_CPTs` function in the `PostTypes` class in `lib/src/PostTypes.php`.

Add taxonomies to the `Register_Taxonomies` function in the `Taxonomies` class in `lib/src/Taxonomies.php`.

The `LabelMaker` class can be used to quickly produce labels for both Post Types and
Taxonomies (see `lib/src/LabelMaker.php`).

## Google Analytics

Plugin adds a field to Admin > Settings > General, where an admin can enter a Google
Analytics id number. If the field has a value, it will be loaded into `wp_head`, and only
be printed if a user _is not_ logged in.

## Timber Helpers

- `print(some_var)` or `kint(some_var)`: A Drupal ksm/kint style print of variables
