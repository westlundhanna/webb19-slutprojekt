<?php

/* Plugin Name: Game rating */ 

// Create Game Post

include( plugin_dir_path( __FILE__ ) . 'rating_widget.php');

function create_cpt_game() {
    $plugin_url = plugin_dir_url(__FILE__);

    wp_enqueue_style('style', $plugin_url . "/assets/style.css");

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

function add_rating($content){
    global $wpdb;
    if( is_singular() && in_the_loop() && is_main_query() ){
        $id = get_the_ID();
        $user_id = wp_get_current_user();

        $results = $wpdb->get_results( "SELECT * FROM wp_ratings WHERE (owner_id = $user_id->ID AND post_id = $id)" );
        if($wpdb->num_rows == 0){
            return $content .

            "
            <h2>Rate this game</h2>
            <form method=POST>
            <input type=checkbox id=rate1 name=rate value=1>
            <label for=rate1> 1 </label><br>
            <input type=checkbox id=rate2 name=rate value=2>
            <label for=rate2> 2 </label><br>
            <input type=checkbox id=rate3 name=rate value=3>
            <label for=rate3> 3 </label><br>
            <input type=checkbox id=rate4 name=rate value=4>
            <label for=rate4> 4 </label><br>
            <input type=checkbox id=rate5 name=rate value=5>
            <label for=rate5> 5 </label><br>
            <input type=submit name=submit></input>
            <input type=hidden name=issubmit value=$id></input>
            </form>";
            // "
            // <form method=POST>
            // <button>&#11088</button>
            // <button>&#11088</button>
            // <button>&#11088</button>
            // <button>&#11088</button>
            // <button>&#11088</button>

            // <input type=hidden name=issubmit value=$id></input>
            // </form>
            // ";
        }
    }
    return $content;
}
function remove_rating($content) {
    global $wpdb;
        
        if (is_singular() && in_the_loop() && is_main_query() ) {

            $id = get_the_ID(); 
            $user_id = wp_get_current_user(); 

            $wpdb->get_results( "SELECT owner_id, post_id FROM wp_ratings WHERE (owner_id = $user_id->ID AND post_id = $id)" ); 
                if($wpdb->num_rows > 0) {
                    return $content . 
                    "
                    <form method=POST>
                    <button style='background-color: #000';> Unrate </button>
                    <input type=hidden name=unrate value=$id></input>
                    </form>"; 
                }    
        
        
    }
    return $content; 
}
function check_input() {
    global $wpdb; 
    
    // $unrated = $_POST['unrate'];
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

add_filter('the_content', 'add_rating'); 
add_filter('the_content', 'remove_rating');

register_activation_hook(__FILE__, 'create_rating');
register_deactivation_hook(__FILE__, 'ratings_uninstall'); 
register_uninstall_hook(__FILE__, 'ratings_uninstall'); 