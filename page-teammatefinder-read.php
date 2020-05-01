<?php

$location = $_SERVER['DOCUMENT_ROOT'];

include($location . '/project/wp-config.php');
include($location . '/project/wp-load.php');
include($location . '/project/wp-includes/pluggable.php');

global $wpdb;

get_header(); ?>

<style>

    .site-content .entry-header,
    .site-content .entry-content,
    .site-content .entry-summary,
    .site-content .entry-meta,
    .page-content,
    .hentry,
    .entry-content {
        max-width: 1260px !important;
    }

    .site-content {
        margin-right:20px; !important;
    }

</style>

    <div id="main-content" class="main-content">
        <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
<?php

$results = $wpdb->get_results("
                    select racer_id
                          ,first_name
                          ,last_name
                          ,email_address
                          ,years_experience
                      from flx_teammate_finder
                ");

foreach ( $results as $result )
{
    echo '<p>';
    echo '<option value="'.$result->racer_id.'">Racer ID: '.($result->racer_id).'</option>
         <option value="'.$result->racer_id.'">First Name: '.($result->first_name).'</option>
         <option value="'.$result->racer_id.'">Last Name: '.($result->last_name).'</option>
         <option value="'.$result->racer_id.'">Email Address: '.($result->email_address).'</option>
         <option value="'.$result->racer_id.'">Years of Experience: '.($result->years_experience).'</option>
         ';
    echo '</p>';
}

?>

        </div>
    </div>
    </div>
<?php
get_sidebar();
get_footer();