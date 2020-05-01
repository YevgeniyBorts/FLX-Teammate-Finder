<?php


$location = $_SERVER['DOCUMENT_ROOT'];

include($location . '/project/wp-config.php');
include($location . '/project/wp-load.php');
include($location . '/project/wp-includes/pluggable.php');

global $wpdb;

$uid = get_current_user_id();
$racer_id          = $uid;
$first_name        = sanitize_text_field($_POST['first_name']);
$last_name         = sanitize_text_field($_POST['last_name']);
$gender        = sanitize_text_field($_POST['gender']);
$email_address     = sanitize_text_field($_POST['email_address']);
$years_experience  = sanitize_text_field($_POST['years_experience']);
$race_history        = sanitize_text_field($_POST['race_history']);
$prior_teams        = sanitize_text_field($_POST['prior_teams']);
$race_preference        = sanitize_text_field($_POST['race_preference']);
$racer_bio       = sanitize_text_field($_POST['racer_bio']);


$wpdb->insert('flx_teammate_finder'
    ,array('racer_id'        => $uid
    ,'first_name'   => $first_name
    ,'last_name'  => $last_name
    ,'email_address'  => $email_address
    ,'gender' => $gender
    ,'years_experience' => $years_experience
    ,'race_history' => $race_history
    ,'prior_teams' => $prior_teams
    ,'race_preference' => $race_preference
    ,'racer_bio' => $racer_bio
    )
);

wp_redirect( site_url().'/page-teammatefinder-profile-create.php'); exit;

?>