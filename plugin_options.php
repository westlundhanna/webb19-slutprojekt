<?php

add_action( 'admin_menu', 'my_plugin_menu' );

function my_plugin_menu() {
	add_menu_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'mt_settings_page' );
}


function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}


function mt_settings_page() {

    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $opt_color_rate = 'mt_button_color';
    $opt_color_unrate = 'mt_button_unrate_color';
    $hidden_field_name = 'mt_submit_hidden';
    $button_rate = 'mt_button_color';
    $button_unrate = 'mt_button_unrate_color';

    $opt_val_rate = get_option( $opt_color_rate);
    $opt_val_unrate = get_option( $opt_color_unrate );

    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
    
        $opt_val_rate = $_POST[ $button_rate ];
        $opt_val_unrate = $_POST[ $button_unrate ];

        update_option( $opt_color_rate, $opt_val_rate );
        update_option( $opt_color_unrate, $opt_val_unrate );


?>
    <div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
    <?php

    }

    echo '<div class="wrap">';

    echo "<h2>" . __( 'Menu Test Plugin Settings', 'menu-test' ) . "</h2>";
    
    ?>

    <form name="plugin_colors" method="post" action="">
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

    <p><?php _e("Change color of Rate button:", 'menu-test' ); ?> 
    <input type="text" name="<?php echo $button_rate; ?>" value="<?php echo $opt_val_rate; ?>" size="20">
    </p><hr />
    
    <p><?php _e("Change color of Unrate button:", 'menu-test' ); ?> 
    <input type="text" name="<?php echo $button_unrate; ?>" value="<?php echo $opt_val_unrate; ?>" size="20">
    </p><hr />

    <p class="submit">
    <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
    </p>

    </form>
    </div>
<?php 

}
?>