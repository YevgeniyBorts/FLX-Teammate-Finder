<?php
// This page is the profile creation page. The profile management page is page-teammatefinder-profile-create.php

$location = $_SERVER['DOCUMENT_ROOT'];

include($location . '/project/wp-config.php');
include($location . '/project/wp-load.php');
include($location . '/project/wp-includes/pluggable.php');

global $wpdb;


if (is_user_logged_in()) {
    if (in_array("administrator", $user->roles)) {
        $uid = sanitize_text_field($_POST['racer_id']);
    } else {
        $uid = get_current_user_id();
    }

    $racer_id = $uid;
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $gender = sanitize_text_field($_POST['gender']);
    $email_address = sanitize_text_field($_POST['email_address']);
    $years_experience = sanitize_text_field($_POST['years_experience']);
    $race_preference = sanitize_text_field($_POST['race_preference']);
    $racer_bio = sanitize_text_field($_POST['racer_bio']);

    $racer_id = get_flx_racer_id();

    $races_racer_id = urldecode($wp_query->query_vars['racerid']);


// set races_racer_id
    if (empty($races_racer_id)) {
        $races_racer_id = $racer_id;
    }

    $teams = $wpdb->get_results("
                      select t.team_id
                            ,t.team_name
                        from flx_teams t
                            ,flx_team_registrations tr
                            ,flx_team_registration_racers trr
                            ,flx_racers r
                       where t.team_id = tr.team_id
                         and trr.team_registration_id = tr.team_registration_id
                         and r.racer_id = trr.racer_id
                         and r.racer_id = " . $racer_id . "
                       group by t.team_id
                               ,t.team_name;
               ");

    $races = $wpdb->get_results("
                      select ra.race_id
                            ,ra.race_name
                            ,rt.race_type
                            ,ra.race_date
                            ,ra.race_webpage_slug
                            ,tr.team_registration_id
                            ,t.team_name
                        from flx_races ra
                            ,flx_race_types rt
                            ,flx_team_registrations tr
                            ,flx_team_registration_racers trr
                            ,flx_teams t
                       where ra.race_id = tr.race_id
                         and rt.race_type_id = tr.race_type_id
                         and t.team_id = tr.team_id
                         and tr.team_registration_id = trr.team_registration_id
                         and trr.racer_id = " . $races_racer_id . "
                       group by ra.race_id
                               ,ra.race_name
                               ,rt.race_type
                               ,ra.race_date
                               ,t.team_name
                      UNION
                      select ra.race_id
                            ,ra.race_name
                            ,rt.race_type
                            ,ra.race_date
                            ,ra.race_webpage_slug
                            ,tr.team_registration_id
                            ,t.team_name
                        from flx_races ra
                            ,flx_race_types rt
                            ,flx_team_registrations tr
                            ,flx_teams t
                       where ra.race_id = tr.race_id
                         and rt.race_type_id = tr.race_type_id
                         and t.team_id = tr.team_id
                         and tr.created_by = " . $races_racer_id . "
                       group by ra.race_id
                               ,ra.race_name
                               ,rt.race_type
                               ,ra.race_date
                               ,t.team_name
                       order by 4 desc
               ");

    $r = "";

    foreach ($races as $race) {
        $r .= "$race->race_name, ";
    }

    $t = "";

    foreach ($teams as $team) {
        $t .= "$team->team_name, ";
    }

    $wpdb->insert('flx_teammate_finder'
        , array('racer_id' => $uid
        , 'first_name' => $first_name
        , 'last_name' => $last_name
        , 'email_address' => $email_address
        , 'gender' => $gender
        , 'years_experience' => $years_experience
        , 'race_history' => $r
        , 'prior_teams' => $t
        , 'race_preference' => $race_preference
        , 'racer_bio' => $racer_bio
        )
    );
}
else {
    echo "<br />You are not logged in. Please log in to use this feature.";
}

wp_redirect( site_url().'/page-teammatefinder-profile-create.php'); exit;

?>