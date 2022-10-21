<?php

/**
 * Templating Megamenu functions.
 *
 */

use stdClass;
use Walker_Nav_Menu;
use WP_Post;

/**
 * Megamenu Nav Walker class.
 */
class Megamenu extends Walker_Nav_Menu
{

    /**
     * Lorsqu'un niveau commence (<ul> + content)
     *
     * @param string $output correspond à la variable retournée en fin de walker
     * @param integer $depth correspond à la profondeur du niveau
     * @param stdClass $args correspond aux variables supplémentaires
     * @return void
     */
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? 'megamenu__sub-sub-menu' : 'megamenu__sub-menu';

        if ($depth == 0) {
            $output .= "\n$indent<div class='megamenu'><div class='container'>";
        }

        $output .= "\n$indent<ul class='$submenu depth_$depth'>\n";

        if ($depth == 0) {
            $output .= "<li class='megamenu-column'><ul>\n";
        }
    }

    /**
     * Lorsqu'un élément est initialisé (<li> + content)
     *
     * @param string $output correspond à la variable retournée en fin de walker
     * @param WP_Post $item correspond aux information sur l'item en cours
     * @param int $depth correspond à la profondeur du niveau
     * @param stdClass $args aux variables supplémentaires
     * @return void
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $hasColumnDivider = get_post_meta($item->ID, 'menu-item-mm-column-divider', true);
        $hasSubmenu = get_post_meta($item->ID, 'menu-item-mm-sub-menu-active', true);
        $hasFeaturedImage = get_post_meta($item->ID, 'menu-item-mm-featured-image', true);

        // Définition des attributs et classes pour la balise <li>
        $li_attributes = '';
        $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        //Si "Image à la une" est choisie, on ajoute la classe "has-featured-image"
        $classes[] = ($hasFeaturedImage) ? 'has-featured-image' : '';

        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = ' class="' . esc_attr($class_names) . '"';
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = strlen($id) ? ' id="' . esc_attr($id) . '"' : '';

        //Si "Nouvelle colonne" est choisie, on ajoute la classe "megamenu-column"
        $output .= ($hasColumnDivider) ? '</ul></li><li class="megamenu-column"><ul>' : '';

        // Début du <li>
        $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

        // Attributs de la balise <a> à l'intérieur de chaque <li>
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

        // Début du <a> | <p> si présence d'un sous menu
        $item_output = $args->before;
        $item_output .= ($hasSubmenu) ? '<p class="sub-menu--label">' : '<a' . $attributes . '>';

        // Ajout de l'image à la une si présente
        if ($hasFeaturedImage) {
            $page_id = get_post_meta($item->ID, '_menu_item_object_id', true);
            $item_output .= '<span class="featured-image">' . get_the_post_thumbnail($page_id, 'medium_large') . '</span>';
        }

        if ($hasFeaturedImage) {
            $item_output .= '<span class="link">' . $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after . '</span>';
        } else {
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        }

        // Fin du </a> | </p> si présence d'un sous menu
        $item_output .= ($hasSubmenu) ? '</p>' : '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Lorsqu'un élément se termine (</li>)
     *
     * @param string $output correspond à la variable retournée en fin de walker
     * @param WP_Post $item correspond aux informations sur l'item en cours
     * @param integer $depth correspond à la profondeur du niveau
     * @param stdClass $args aux variables supplémentaires
     * @return void
     */
    public function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= '</li>';
    }

    /**
     * Lorsqu'un niveau se termine (</ul>)
     *
     * @param string $output correspond à la variable retournée en fin de walker
     * @param integer $depth correspond à la profondeur du niveau
     * @param stdClass $args aux variables supplémentaires
     * @return void
     */
    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth == 0) {
            $output .= "</ul></li></ul></div></div>";
        }

        if ($depth > 0) {
            $output .= "</ul></li>";
        }
    }
}
