<?php

add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_menu_page( 'My Plugin Options', 'Games Rating Options', 'manage_options', 'my-unique-identifier', 'mt_settings_page' );
}

function mt_settings_page() {

    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $hidden_field_name = 'mt_submit_hidden';

    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        
        if($_POST['symbol'] == 1) {
          update_option( 'symbol', '&#11088' );
        }elseif($_POST['symbol'] == 2){
          update_option( 'symbol', '&#129505' );
        }elseif($_POST['symbol'] == 3){
          update_option( 'symbol', '&#128156' );          
        }elseif($_POST['symbol'] == 4){
          update_option( 'symbol', '&#128155' );  
        }elseif($_POST['symbol'] == 5){
          update_option( 'symbol', '&#128154' );
        }else{
          update_option( 'symbol', '&#128153' );
        }

        if($_POST['unrate_color'] == 3) {
          update_option('unrate_color', 'green');
        }elseif($_POST['unrate_color'] == 1){
          update_option('unrate_color', 'black');
        }elseif($_POST['unrate_color'] == 2){
          update_option('unrate_color', 'pink');
        }

        if($_POST['rate_color'] == 3) {
          update_option('rate_color', 'green');
        }elseif($_POST['rate_color'] == 1){
          update_option('rate_color', 'black');
        }elseif($_POST['rate_color'] == 2){
          update_option('rate_color', 'pink');
        }
        

?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
    <?php

    }

    echo '<div class="wrap">';

    echo "<h1>" . __( 'Plugin Settings', 'menu-test' ) . "</h1>";

    echo '<div class="wrap">';

    echo "<h3>" . __( 'Plugin colors and symbols', 'menu-test' ) . "</h3>";
    
    ?>

    <form name="plugin_colors" method="post" action="">
      <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

      <label for="rate_color">Choose color of rate button:</label>
      <select name="rate_color" id="rate_color_select">
        <option value="1">Black</option>
        <option value="2">Pink</option>
        <option value="3">Green</option>
      </select>
      <hr />
      
     
      <label for="unrate_color">Choose color of unrate button:</label>
      <select name="unrate_color" id="unrate_color_select">
        <option value="1">Black</option>
        <option value="2">Pink</option>
        <option value="3">Green</option>
      </select>
      <hr />

      <label for="symbol">Choose rating symbol:</label>
      <select id="symbol" name="symbol">
        <option value="1">&#11088</option>
        <option value="2">&#129505</option>
        <option value="3">&#128156</option>
        <option value="4">&#128155</option>
        <option value="5">&#128154</option>
        <option value="6">&#128153</option>
      </select>

      <p class="submit">
      <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
      </p>

    </form>
    <?php
    }


?>