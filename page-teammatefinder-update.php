<?php

$location = $_SERVER['DOCUMENT_ROOT'];

include($location . '/project/wp-config.php');
include($location . '/project/wp-load.php');
include($location . '/project/wp-includes/pluggable.php');

global $wpdb;

$racer_id          = sanitize_text_field($_POST['racer_id']);
$first_name        = sanitize_text_field($_POST['first_name']);
$last_name         = sanitize_text_field($_POST['last_name']);
$email_address     = sanitize_text_field($_POST['email_address']);
$years_experience  = sanitize_text_field($_POST['years_experience']);
$race_history = sanitize_text_field($_POST['race_history']);
$prior_teams = sanitize_text_field($_POST['prior_teams']);
$racer_bio = sanitize_text_field($_POST['racer_bio']);
$race_preference = sanitize_text_field($_POST['race_preference']);
$active = sanitize_text_field($_POST['active']);

if (is_user_logged_in()) {
    $uid = get_current_user_id();
    $user = wp_get_current_user();
        if (in_array("administrator", $user->roles)) {
            $wpdb->update('flx_teammate_finder'
                , array('racer_id' => $uid
                , 'first_name' => $first_name
                , 'last_name' => $last_name
                , 'email_address' => $email_address
                , 'years_experience' => $years_experience
                , 'race_history' => $race_history
                , 'prior_teams' => $prior_teams
                , 'racer_bio' => $racer_bio
                , 'race_preference' => $race_preference
                , 'active' => $active
                )
                , array('racer_id' => $racer_id
                )
            );
            wp_redirect( site_url().'/page-teammatefinder-read.php' . $raceid . '/'); exit;

        }
        else if ($racer_id == $uid) {
            $wpdb->update('flx_teammate_finder'
                , array('racer_id' => $uid
                , 'first_name' => $first_name
                , 'last_name' => $last_name
                , 'email_address' => $email_address
                , 'years_experience' => $years_experience
                )
                , array('racer_id' => $racer_id
                )
            );
            wp_redirect( site_url().'/page-teammatefinder-read.php' . $racer_id . '/'); exit;

        }
        else {
            echo "You do not have sufficient privileges.";
        }
}

?>