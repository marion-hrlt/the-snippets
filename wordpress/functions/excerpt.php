<?php

/**
 * Change the excerpt length in articles
 *
 * @param int $length
 */
function my_excerpt_length($length)
{
    return 20;
}
add_filter('excerpt_length', [$this, 'my_excerpt_length']);


/**
 * Change the excerpt more in articles
 *
 * @param $more
 * @return string
 */
function my_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', [$this, 'my_excerpt_more']);
