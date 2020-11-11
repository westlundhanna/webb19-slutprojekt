<?php

echo 'Big no no';

if(!defined('ABSPATH')){
  exit;
}


class rating_widget extends WP_Widget{

    function __construct(){


        parent:: __construct(
            'best_rated_games',
            'Best Rated Games',
            array(
                'description' => 'Displays the best rated games',
            )
        );
    }

    function widget($args, $instance) {
        echo $args['before_widget'];
        global $wpdb;

        $results = $wpdb->get_results(
            "SELECT wp_ratings.post_id, wp_posts.post_title,
            SUM(rating_value) AS total_ratings 
            FROM wp_ratings 
            INNER JOIN wp_posts 
            ON wp_posts.ID = wp_ratings.post_id 
            GROUP BY wp_ratings.post_id
            ORDER BY total_ratings DESC
            LIMIT " . $instance['amount']);

        echo '<h3>Best rated games</h3>';
        
        echo "<h4>Top " . $instance['amount'] . " games</h4>";
     

        if(!empty($instance['amount'])){
            echo "<ul>";
            foreach($results as $result){ 
                echo "<li>";
                ?>
                <a href="<?php echo get_permalink($result->post_id) ?>">
                <?php echo $result->post_title ?>
                </a>
                <?php
                echo "</li>";
            }
            echo "</ul>";
        }
        echo $args['after_widget'];
    }

    
    function form($instance){
        printf('<input type="number" name="%s" value="%s" max=10>', 
            $this->get_field_name("amount"), $instance['amount']
        );
    }

}


function best_rated_games(){
    register_widget('rating_widget');
}


add_action('widgets_init', 'best_rated_games');