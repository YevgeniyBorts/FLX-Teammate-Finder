<?php

$location = $_SERVER['DOCUMENT_ROOT'];

include($location . '/project/wp-config.php');
include($location . '/project/wp-load.php');
include($location . '/project/wp-includes/pluggable.php');

global $wpdb;

$racer_id = sanitize_text_field($_POST['racer_id']);

if (is_user_logged_in()) {
    $uid = get_current_user_id();
    $user = wp_get_current_user();
    if (in_array("administrator", $user->roles)) {
        $wpdb->delete('flx_teammate_finder'
            , array('racer_id' => $uid
            )
        );
        wp_redirect( site_url().'/page-teammatefinder-read.php' . $raceid . '/'); exit;
    }
    else if ($racer_id == $uid) {
        $wpdb->delete('flx_teammate_finder'
            , array('racer_id' => $uid
            )
        );
        wp_redirect( site_url().'/page-teammatefinder-read.php' . $racer_id . '/'); exit;
    }
    else {
        echo "<p>You do not have sufficient privileges to perform this action.</p>";
    }
}
else {
    echo "<p>You do not have sufficient privileges to perform this action.</p>";
}

?>