<?php

/**
 * Home redirection after logout admin
 *
 * @return void
 */
function home_redirect_after_logout()
{
    //redirect to the desired page
    wp_safe_redirect(home_url('/'));
    exit();
}
add_action('wp_logout', 'home_redirect_after_logout');
