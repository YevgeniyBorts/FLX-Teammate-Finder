<?php

$location = $_SERVER['DOCUMENT_ROOT'];

include($location . '/project/wp-config.php');
include($location . '/project/wp-load.php');
include($location . '/project/wp-includes/pluggable.php');

global $wpdb;

$uid = get_current_user_id();

$results = $wpdb->get_results("
                        select active
                          from flx_teammate_finder WHERE racer_id = $uid
                    ");

foreach ($results as $result) {
    if ($result->active > 0) {
        $wpdb->update('flx_teammate_finder'
            , array('active' => 0
            )
            , array('racer_id' => $uid
            ));
    } elseif ($result->active < 1) {
        $wpdb->update('flx_teammate_finder'
            , array('active' => 1
            )
            , array('racer_id' => $uid
            ));
    }
}

wp_redirect( site_url().'/page-teammatefinder-profile-create.php' . $racer_id . '/'); exit;