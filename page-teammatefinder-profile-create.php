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

    $user = wp_get_current_user();
    if (is_user_logged_in()) {
        $uid = get_current_user_id();
        $ufo = get_userdata($uid);
        $username = $ufo->user_login;
        $first_name = $ufo->first_name;
        $last_name = $ufo->last_name;
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
                          from flx_teammate_finder WHERE racer_id = $uid
                    ");

        foreach ( $results as $result )
        {
            echo '<h2>FLX Teammate Finder</h2><h3>Racer Profile</h3><br /><p>';
            echo '<option value="'.$result->racer_id.'">Racer ID: '.($result->racer_id).'</option>
             <option value="'.$result->racer_id.'">First Name: '.($result->first_name).'</option>
             <option value="'.$result->racer_id.'">Last Name: '.($result->last_name).'</option>
             <option value="'.$result->racer_id.'">Email Address: '.($result->email_address).'</option>
             <option value="'.$result->racer_id.'">Years of Experience: '.($result->years_experience).'</option>
             <option value="'.$result->racer_id.'">Gender: '.($result->gender).'</option>
             <option value="'.$result->racer_id.'">Race History: '.($result->race_history).'</option>
             <option value="'.$result->racer_id.'">Race Preference: '.($result->race_preference).'</option>
             <option value="'.$result->racer_id.'">Team History: '.($result->prior_teams).'</option>
             <option value="'.$result->racer_id.'">Racer Profile: '.($result->racer_bio).'</option>
             ';
            echo '</p>';
            echo "<div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Edit Profile</div>";
            echo "<div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Delete Profile</div>";
            echo '<br />';
        }
        if (in_array("administrator", $user->roles)) {
            echo "<br /><div class='container' style='text-align: center; margin: 20px; padding: 20px; 
                background: orange;'>You are logged in as an administrator with full administrator privileges. <br />";
            echo "<h3>Admin Panel</h3>";
            echo "<br /><div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Create User</div>";
            echo "<div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Edit User</div>";
            echo "<div class='button' style='width: 100px; display: inline-block; margin: 10px;'>Delete User</div></div>";
        }
        else {
            echo "<br />You are logged in as a racer with standard user privileges.";
        }

    }
    else {
        echo "<br />You are not logged in.";
    }
    ?>
            </div>

    </div>

    <?php
    get_sidebar();
    get_footer();