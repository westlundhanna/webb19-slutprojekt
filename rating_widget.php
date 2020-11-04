<?php


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
            'SELECT wp_ratings.post_id, wp_posts.post_title, 
            SUM(rating_value) AS total_ratings 
            FROM wp_ratings 
            INNER JOIN wp_posts 
            ON wp_posts.ID = wp_ratings.post_id 
            GROUP BY wp_ratings.post_id
            ORDER BY total_ratings DESC');

        echo '<h3>Best rated games</h3>';

        $best_rated = array();


        foreach($results as $result){
            array_push($best_rated, $result->post_title);
        }

        if(!empty($instance['amount'])){
            for($i = 0; $i < $instance['amount']; $i++){
                echo "<p>$best_rated[$i]</p>";
            }
        }
        echo $args['after_widget'];
    }


    function form($instance){
        // printf('
        // <label for=amount>Display how many games you want to show</label>
        // <select id=amount value=' . $instance['amount'] . '>
        // <option value=1>1</option>
        // <option value=2>2</option>
        // <option value=3>3</option>
        // </select>');
        // $this->get_field_name("amount");
        printf('<input type="number" name="%s" value="' . $instance['amount'] . '">',
        $this->get_field_name("amount")
    );
    }

}


function best_rated_games(){
    register_widget('rating_widget');
}


add_action('widgets_init', 'best_rated_games');