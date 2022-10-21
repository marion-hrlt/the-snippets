<?php

/**
 * Megamenu options functions | Admin
 * Options class for Megamenu.
 */
class Options
{

    /**
     * Inititialize hooks.
     *
     * @return void
     */
    public function init()
    {
        add_filter('wp_setup_nav_menu_item', [$this, 'megamenu_setup_nav_menu_item']);
        add_action('wp_nav_menu_item_custom_fields', [$this, 'megamenu_fields'], 10, 4);
        add_filter('manage_nav-menus_columns', [$this, 'megamenu_columns'], 99);
        add_action('wp_update_nav_menu_item', [$this, 'megamenu_save'], 10, 3);
    }

    /**
     * Undocumented function
     *
     * @param mixed $menu_item
     * @return void
     */
    public function megamenu_setup_nav_menu_item($menu_item)
    {
        if (isset($menu_item->post_type)) {
            if ('nav_menu_item' == $menu_item->post_type) {
                $menu_item->description = apply_filters('nav_menu_description', $menu_item->post_content);
            }
        }
        return $menu_item;
    }

    /**
     * Setup fields for megamenu
     *
     * @param [type] $id
     * @param [type] $item
     * @param [type] $depth
     * @param [type] $args
     * @return void
     */
    public function megamenu_fields($id, $item, $depth, $args)
    {
        $fields = self::options_list();

        foreach ($fields as $_key => $label) :
            $key   = sprintf('menu-item-%s', $_key);
            $id    = sprintf('edit-%s-%s', $key, $item->ID);
            $name  = sprintf('%s[%s]', $key, $item->ID);
            $value = get_post_meta($item->ID, $key, true);
            $class = sprintf('field-%s', $_key);

            echo '<p class="description description-wide ' . esc_attr($class) . '">';
            echo '<label for="' . esc_attr($id) . '">';
            echo '<input type="checkbox" id="' . esc_attr($id) . '" class="widefat code edit-menu-item-custom" name="' . esc_attr($name) . '" value="1" ' . checked($value, 1, false) . ' />';
            echo '<span>' . esc_html($label) . '</span>';
            echo '</label>';
            echo '</p>';

        endforeach;
    }

    /**
     * Create columns for megamenu
     *
     * @param [type] $columns
     * @return mixed
     */
    public function megamenu_columns($columns)
    {
        $fields = self::options_list();

        $columns = array_merge($columns, $fields);

        return $columns;
    }

    /**
     * Save fields for megamenu
     *
     * @param [type] $menu_id
     * @param [type] $menu_item_db_id
     * @param [type] $menu_item_args
     * @return void
     */
    public function megamenu_save($menu_id, $menu_item_db_id, $menu_item_args)
    {
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        check_admin_referer('update-nav_menu', 'update-nav-menu-nonce');

        $fields = self::options_list();

        foreach ($fields as $_key => $label) {
            $key = sprintf('menu-item-%s', $_key);

            //Sanitize.
            if (!empty($_POST[$key][$menu_item_db_id])) {
                $value = $_POST[$key][$menu_item_db_id];
            } else {
                $value = null;
            }

            //Update.
            if (!is_null($value)) {
                update_post_meta($menu_item_db_id, $key, $value);
            } else {
                delete_post_meta($menu_item_db_id, $key);
            }
        }
    }

    /**
     * List of menu item options
     *
     * @return array
     */
    function options_list()
    {
        //Note: The key is used as the class name for the menu item | menu-item-{key}
        return array(
            'mm-column-divider' => __('Nouvelle colonne', 'kreenoot-child'),
            'mm-featured-image' => __('Image à la une', 'kreenoot-child'),
            'mm-sub-menu-active' => __('Présence d\'un sous menu ?', 'kreenoot-child'),
        );
    }
}
