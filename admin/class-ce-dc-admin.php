<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://codended.com
 * @since      1.0.0
 *
 * @package    Ce_Dc
 * @subpackage Ce_Dc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ce_Dc
 * @subpackage Ce_Dc/admin
 * @author     Muhammad Junaid <invisiblevision2011@gmail.com>
 */
class Ce_Dc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $option_name = 'ce_dc';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ce_Dc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ce_Dc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ce-dc-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ce_Dc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ce_Dc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ce-dc-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function add_options_page() {
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Distance Calculator', 'ce-dc' ),
			__( 'Distance Calculator', 'ce-dc' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
		);
	}

	public function display_options_page() {
		include_once 'partials/ce-dc-admin-display.php';
	}

	public function register_setting() {
		add_settings_section(
			$this->option_name . '_general',
			__( 'General', 'ce-dc' ),
			array( $this, $this->option_name . '_general' ),
			$this->plugin_name
		);
		add_settings_field(
			$this->option_name . '_enable_all',
			__( 'Enable for all products', 'ce-dc' ),
			array( $this, $this->option_name . '_enable_all' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_enable_all' )
		);		

		add_settings_field(
			$this->option_name . '_latlng',
			__( 'Map center Lat / Lng', 'ce-dc' ),
			array( $this, $this->option_name . '_latlng' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_latlng' )
		);		
		add_settings_field(
			$this->option_name . '_default_origin',
			__( 'Default Origin', 'ce-dc' ),
			array( $this, $this->option_name . '_default_origin' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_default_origin' )
		);
		add_settings_field(
			$this->option_name . '_google_api',
			__( 'Google API', 'ce-dc' ),
			array( $this, $this->option_name . '_google_api' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_google_api' )
		);
		add_settings_field(
			$this->option_name . '_price',
			__( 'Price per Mile/Km', 'ce-dc' ),
			array( $this, $this->option_name . '_price' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_price' )
		);

		add_settings_field(
			$this->option_name . '_scale',
			__( 'Distance in ', 'ce-dc' ),
			array( $this, $this->option_name . '_scale' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_scale' )
		);

		register_setting( $this->plugin_name, $this->option_name . '_google_api');
		register_setting( $this->plugin_name, $this->option_name . '_price');
		register_setting( $this->plugin_name, $this->option_name . '_scale');
		register_setting( $this->plugin_name, $this->option_name . '_enable_all');
		register_setting( $this->plugin_name, $this->option_name . '_default_origin');
		register_setting( $this->plugin_name, $this->option_name . '_latlng');
	}

	public function ce_dc_general() {
		echo '<p>' . __( 'Please change the settings accordingly.', 'ce-dc' ) . '</p>';
	}

	public function ce_dc_google_api() {
		$google_api = get_option( $this->option_name . '_google_api' );
		echo '<input type="text" name="' . $this->option_name . '_google_api' . '" id="' . $this->option_name . '_google_api' . '" value="' . $google_api . '"> ';
	}	
	public function ce_dc_default_origin() {
		$default_origin = get_option( $this->option_name . '_default_origin' );
		echo '<input type="text" name="' . $this->option_name . '_default_origin' . '" id="' . $this->option_name . '_default_origin' . '" value="' . $default_origin . '"> ';
	}	
	public function ce_dc_latlng() {
		$lat = get_option( $this->option_name . '_latlng' );
		echo '<input type="text" name="' . $this->option_name . '_latlng[lat]' . '" value="' . $lat['lat'] . '"> ';
		echo '<input type="text" name="' . $this->option_name . '_latlng[lng]' . '" value="' . $lat['lng'] . '"> ';
	}	

	public function ce_dc_enable_all() {
	 $enable_all = get_option( $this->option_name . '_enable_all' );
	?>
	<input type='checkbox' name='<?php echo  $this->option_name . '_enable_all' ?>' <?php checked(1,$enable_all ); ?> value='1'>
	<?php
	}	
	public function ce_dc_price() {
		$price = get_option( $this->option_name . '_price' );
		echo '<input type="text" name="' . $this->option_name . '_price' . '" id="' . $this->option_name . '_google_api' . '" value="' . $price . '"> ';
	}	
	public function ce_dc_scale() {
		$scale = get_option( $this->option_name . '_scale' ); 
	?>
	<select name='<?php echo $this->option_name . '_scale' ; ?>'>
		<option value='1' <?php selected( $scale, 1 ); ?>>KMs</option>
		<option value='2' <?php selected( $scale, 2 ); ?>>Miles</option>
	</select>

<?php
	}

	public function ce_dc_product_settings_tabs($tabs){
			$tabs['ce_dc'] = array(
			'label'    => 'Distance Calculator',
			'target'   => 'ce_dc_product_data',
			'class'    => array(),
			'priority' => 3000,
		);
		return $tabs;
	}

	public function ce_dc_product_panels(){
 
	echo '<div id="ce_dc_product_data" class="panel woocommerce_options_panel hidden">';
	
	echo '<div class="options_group">';

		woocommerce_wp_checkbox( array(
			'id'      => 'ce_dc_enable',
			'value'   => get_post_meta( get_the_ID(), 'ce_dc_enable', true ),
			'label'   => __( 'Enable for this Product', 'ce-dc' ) ,
			'desc_tip' => true,
			'description' => __( 'If this option checked it will enable for current product', 'ce-dc' ) ,
		) );
	 
		woocommerce_wp_checkbox( array(
			'id'      => 'ce_dc_override',
			'value'   => get_post_meta( get_the_ID(), 'ce_dc_override', true ),
			'label'   => __( 'Override global options', 'ce-dc' ) ,
			'desc_tip' => true,
			'description' => __( 'If this option checked it will override global options for this product', 'ce-dc' ) ,
		) );

	 
	echo '</div>';
 		woocommerce_wp_checkbox( array(
			'id'      => 'ce_dc_showMap',
			'value'   => get_post_meta( get_the_ID(), 'ce_dc_override', true ),
			'label'   => __( 'Display Map', 'ce-dc' ) ,
			//'description' => __( 'If this option checked Map will be displayed', 'ce-dc' ) ,
		) );
	woocommerce_wp_text_input( array(
		'id'          => 'ce_dc_price',
		'value'       => get_post_meta( get_the_ID(), 'ce_dc_price', true ),
		'label'       => 'Price per Mile/Km',
		'desc_tip'    => true,
		'description' => 'Price per mile/km',
	) );
 
	woocommerce_wp_select( array(
		'id'          => 'ce_dc_scale',
		'value'       => get_post_meta( get_the_ID(), 'ce_dc_scale', true ),
		//'wrapper_class' => 'show_if_downloadable',
		'label'       => 'Distance',
		'options'     => array('km' => 'KMs','miles' => 'Miles'),
	) );
 
	echo '</div>';
 
}

}
