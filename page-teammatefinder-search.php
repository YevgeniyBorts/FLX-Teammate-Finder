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

<?php
// Start the Loop.
while ( have_posts() ) : the_post();
    // Include the page content template.
    get_template_part( 'content', 'page' );
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }
endwhile;
?>

    <div id="main-content" class="main-content">
        <div id="content" class="site-content" role="main">
<?php

// Check for racer profile
$uid = get_current_user_id();
$profilecheck = $wpdb->query("SELECT racer_id FROM flx_teammate_finder WHERE racer_id='$uid'");

if ($uid == null) {
    echo "<br />You are not logged in. Please log in to use this feature.";
}
elseif ($profilecheck < 1){
    echo "<br />You do not have a Teammate Finder profile, which is required to use this feature.
          Please click the button below to create one.";
    echo "<a href='http://localhost/project/teammate-finder-create-profile/'>
            <br /><div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Create Profile</div></a>";
} else {

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $gender = sanitize_text_field($_POST['gender']);
    $email_address = sanitize_text_field($_POST['email_address']);
    $years_experience = sanitize_text_field($_POST['years_experience']);
    $race_history = sanitize_text_field($_POST['prior_race']);
    $prior_teams = sanitize_text_field($_POST['prior_teams']);
    $race_preference = sanitize_text_field($_POST['race_type']);
    $racer_bio = sanitize_text_field($_POST['racer_bio']);

    // Get results based on input. If not input is given then all racers are displayed.
    $results = $wpdb->get_results("
                        select racer_id
                        ,first_name
                        ,last_name
                        from flx_teammate_finder
                        where REPLACE(first_name, '''', '') LIKE '%$first_name%'
                        and REPLACE(last_name, '''', '') LIKE '%$last_name%'
                        and REPLACE(gender, '''', '') LIKE '%$gender%'
                        and REPLACE(years_experience, '''', '') LIKE '%$years_experience%'
                        and REPLACE(race_preference, '''', '') LIKE '%$race_preference%'
                        and REPLACE(race_history, '''', '') LIKE '%$race_history%'
                        and REPLACE(prior_teams, '''', '') LIKE '%$prior_teams%'
                        and REPLACE(racer_bio, '''', '') LIKE '%$racer_bio%'
                        and active = 1
                        ");

    echo '<h2>Results</h2><br />';
    foreach ($results as $result) {
        echo '<option value="' . $result->racer_id . '">' . ($result->first_name) . ' ' . ($result->last_name) . '
        </option><a href="http://localhost/project/page-teammatefinder-profile-create.php?page=' . $result->racer_id . '">
        View Racer Profile</a><br /><br />';
    }
}
?>
        </div>

    </div>

<?php
get_sidebar();
get_footer();