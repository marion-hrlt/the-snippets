<?php

/**
 * Exemple cron for delete old files
 */

/**
 * Delete the specifics files (pdf for exemple) after 30 days
 *
 * @return void
 */
function delete_files()
{
    $files = glob(get_stylesheet_directory() . '/pdfs/file-*.pdf');

    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < strtotime('-30 days')) {
            unlink($file);
        }
    }
}
add_action('delete_files', 'delete_files');

/**
 * Schedule the cron job recurrence => Check every day
 *
 * @param [type] $schedules
 * @return void
 */
function custom_cron_job_recurrence($schedules)
{
    $schedules['daily'] = array(
        'display' => __('Daily', 'kreenoot-child'),
        'interval' => DAY_IN_SECONDS,
    );
    return $schedules;
}
add_filter('cron_schedules', 'custom_cron_job_recurrence');

/**
 * Schedule the cron job
 *
 * @return void
 */
function custom_cron_job()
{
    if (!wp_next_scheduled('delete_quotations')) {
        wp_schedule_event(time(), 'daily', 'delete_quotations');
    }
}
add_action('wp', 'custom_cron_job');
