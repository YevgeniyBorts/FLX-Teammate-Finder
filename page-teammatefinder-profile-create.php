<?php
    // This page is the profile management page. The profile creation page is page-teammatefinder.php

    $location = $_SERVER['DOCUMENT_ROOT'];

    include($location . '/project/wp-config.php');
    include($location . '/project/wp-load.php');
    include($location . '/project/wp-includes/pluggable.php');

    global $wpdb;

    get_header();
?>

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

    // Login check
    if (is_user_logged_in()) {
        $page = $_GET['page'];
        if($page == null) {
            $uid = get_current_user_id();
        }
        else {
            $uid = $page;
        }

        $user = wp_get_current_user();
        $racerid = get_current_user_id();
        $ufo = get_userdata($uid);
        $username = $ufo->user_login;
        $first_name = $ufo->first_name;
        $last_name = $ufo->last_name;
        $profilecheck = $wpdb->query("SELECT racer_id FROM flx_teammate_finder WHERE racer_id='$racerid'");
        if ($profilecheck < 1){
            echo "<br />You do not have a Teammate Finder profile. Please click the button below to create one.";
            echo "<a href='http://localhost/project/teammate-finder-create-profile/'>
            <br /><div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Create Profile</div></a>";
        }
        // Get results
        $results = $wpdb->get_results("
                        select racer_id
                              ,first_name
                              ,last_name
                              ,email_address
                              ,years_experience
                              ,gender
                              ,race_history
                              ,race_preference 
                              ,prior_teams
                              ,racer_bio
                              ,active
                          from flx_teammate_finder WHERE racer_id = $uid
                    ");

        $racer_id = get_flx_racer_id();

        // Output each result
        foreach ( $results as $result )
        {
            echo '<h1>FLX Teammate Finder: Racer Profile</h1><h3>Racer: '.$ufo->user_login.'</h3><p>';
            echo '<option value="'.$result->racer_id.'">
                Racer ID: '.($result->racer_id).'</option>
             <option value="'.$result->racer_id.'">First Name: '.($result->first_name).'</option>
             <option value="'.$result->racer_id.'">Last Name: '.($result->last_name).'</option>
             <option value="'.$result->racer_id.'">Email Address: '.($result->email_address).'</option>
             <option value="'.$result->racer_id.'">Years of Experience: '.($result->years_experience).'</option>
             <option value="'.$result->racer_id.'">Gender: '.($result->gender).'</option>
             <option value="'.$result->racer_id.'">Race Preference: '.($result->race_preference).'</option>
             <div class="column" style="max-height: 800px; max-width: 80%; overflow-y: scroll; text-wrap: normal;">
             <h5>Profile Summary:</h5> '.($result->racer_bio).'
             
             ';
            echo '
             <h5>Race History:</h5> '.($result->race_history).'
             <br /><br /><h5>Team History:</h5> '.($result->prior_teams).'
             </div><br /><br />';
            // User Control Panel
            if ($uid == $racerid) {
            echo "<a href='http://localhost/project/teammate-finder-edit-profile/'><div class='button' 
                style='width: 140px; display: inline-block; margin: 10px; text-align: center;'>Edit Profile</div></a>";

            if ($result->active > 0) {
            echo "<a href='/project/page-teammatefinder-profile-deactivate.php'><div class='button' style='width: 140px;
                  display: inline-block; margin: 10px; text-align: center;'>Deactivate Profile</div></a>";
            }
            elseif ($result->active < 1) {
                echo "<a href='/project/page-teammatefinder-profile-deactivate.php'><div class='button' 
                style='width: 140px; display: inline-block; margin: 10px; text-align: center;'>Activate Profile</div></a>";
            }
            }
            echo '<br />';
        }
        // Role check
        if (in_array("administrator", $user->roles)) {
            echo "<br /><div class='container' style='text-align: center; margin: 20px; padding: 20px; 
                background: orange;'>You are logged in as an administrator with full administrator privileges. <br />";
            // Admin control panel
            echo "<h3>Admin Panel</h3>";
            echo "<br /><a href='http://localhost/project/teammate-finder-admin-page/'>
            <div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Create User</div>";
            echo "<div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Edit User</div>";
            echo "<div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Delete User</div></a>
            </div>";
        }
        else {
            echo "<br /><p style='margin-left: 15vw;'>You are logged in as a racer with standard user privileges.</p>";
        }

    }
    else {
        echo "<br />You are not logged in. Please log in to view this page.";
    }
?>
            </div>

    </div>

<?php
    get_sidebar();
    get_footer();