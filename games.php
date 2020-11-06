<?php

/* 
Plugin Name: Game rating
Authors: Joakim Hedlund & Hanna Westlund
*/ 



include( plugin_dir_path( __FILE__ ) . 'rating_widget.php');
include( plugin_dir_path( __FILE__ ) . 'admin.php');


function create_cpt_game() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('style', $plugin_url . "/includes/style.css");
    wp_enqueue_script('script', $plugin_url . "/includes/script.js");

    register_post_type( 'cpt_game',
    
        array(
            'labels' => array(
                'name' => 'Games',
                'singular_name' => 'Game'
            ),
            'public' => true,
            'has_archive' => true,
            'description' => "",
            'supports' => array( 'title', 'editor', 'thumbnail', 'author' )
 
        )
    );
}


function ratings_uninstall() {
    global $wpdb; 
    $table_name = $wpdb->prefix . "ratings"; 
    $wpdb->query("DROP TABLE IF EXISTS $table_name"); 
}

function create_rating() {
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "ratings"; 
    $post_table = $wpdb->prefix . "posts"; 
    $user_table = $wpdb->prefix . "users"; 

    $sql = "CREATE TABLE $table_name(
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        owner_id BIGINT(20) UNSIGNED NOT NULL,
        post_id BIGINT(20) UNSIGNED NOT NULL,
        rating_value INT(1) UNSIGNED NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(owner_id) REFERENCES $user_table(ID),
        FOREIGN KEY(post_id) REFERENCES $post_table(ID)
        ) $charset_collate;";

        require_once ( ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
}

function handling_ratings($content){
    global $wpdb;
    if( is_singular() && in_the_loop() && is_main_query() ){
        $id = get_the_ID();
        $user_id = wp_get_current_user();

        $wpdb->get_results( "SELECT * FROM wp_ratings WHERE (owner_id = $user_id->ID AND post_id = $id)" );
        if($wpdb->num_rows == 0){
            return $content .

            "
            <h2>Rate this game</h2>
            <form method=POST>
            <input type=checkbox id=rate1 onclick=onlyOne(this) name=rate value=1>
            <label for=rate1> 1 </label><br>
            <input type=checkbox id=rate2 onclick=onlyOne(this) name=rate value=2>
            <label for=rate2> 2 </label><br>
            <input type=checkbox id=rate3 onclick=onlyOne(this) name=rate value=3>
            <label for=rate3> 3 </label><br>
            <input type=checkbox id=rate4 onclick=onlyOne(this) name=rate value=4>
            <label for=rate4> 4 </label><br>
            <input type=checkbox id=rate5 onclick=onlyOne(this) name=rate value=5>
            <label for=rate5> 5 </label><br>
            <input type=submit name=submit style=background-color:" . get_option('mt_button_color') . ">
            <input type=hidden name=issubmit value=$id>
            </form>";
        }else{
            return $content . 
            "
            <form method=POST>
            <button style=background-color:" . get_option('mt_button_unrate_color') . "> Unrate </button>
            <input type=hidden name=unrate value=$id></input>
            </form>"; 
        }
    }
    return $content;
}

function write_rating($content){
    global $wpdb;
    $id = get_the_ID();
    
    if( in_the_loop() && is_main_query() ){
        $rating_stars = $wpdb->get_results( 
        
        "SELECT post_id, AVG (rating_value) AS average_rating 
            FROM wp_ratings WHERE wp_ratings.post_id = $id GROUP BY post_id" );

        foreach($rating_stars as $rating_star) {
            $stars = $rating_star->average_rating; 
            $loops = 0;
            $star_symbol = '<span>&#11088</span>';
            
            while($loops < $stars) {
                echo $star_symbol;
                $loops++;
            }
        }
        

    return $content; 
        
    }
}

function check_input() {
    global $wpdb; 
    
        if(isset($_POST['rate'])) {
            $user_id = wp_get_current_user(); 
            $post_id = $_POST['issubmit'];
            $rated = $_POST['rate'];
            
            $wpdb->get_results( "INSERT INTO wp_ratings (owner_id, post_id, rating_value) VALUES ($user_id->ID, $post_id, $rated)"); 
                
        }
        if(isset($_POST['unrate'])) {
            $user_id = wp_get_current_user(); 
            $post_id = $_POST['unrate'];

            $wpdb->get_results( "DELETE FROM wp_ratings WHERE (owner_id = $user_id->ID AND post_id = $post_id)");
        }

}
add_action('init', 'create_cpt_game');
add_action('init', 'check_input');

add_filter('the_content', 'handling_ratings'); 
add_filter('the_content', 'write_rating');


register_activation_hook(__FILE__, 'create_rating');
register_deactivation_hook(__FILE__, 'ratings_uninstall'); 
register_uninstall_hook(__FILE__, 'ratings_uninstall'); 