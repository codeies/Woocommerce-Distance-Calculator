<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://codended.com
 * @since      1.0.0
 *
 * @package    Ce_Dc
 * @subpackage Ce_Dc/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ce_Dc
 * @subpackage Ce_Dc/public
 * @author     Muhammad Junaid <invisiblevision2011@gmail.com>
 */
class Ce_Dc_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	private $option_name = 'ce_dc';
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ce-dc-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		$google_api = get_option( $this->option_name . '_google_api' );

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ce-dc-public.js', array( 'jquery' ), $this->version,  false );
		$lat = get_option( $this->option_name . '_latlng' );
		$price = get_option( $this->option_name . '_price' );
		$defualts = array(
			'lat' => $lat['lat'],
			'lng' =>  $lat['lng'],
			'amount' =>  $price
		);
		wp_localize_script( $this->plugin_name, 'ce_dc', $defualts );
		wp_register_script( $this->plugin_name.'googleplaces','https://maps.googleapis.com/maps/api/js?key='.$google_api.'&libraries=places', $this->version,  false );

	}

	public function register_shortcodes() {
		 $shortcodes = array('distance_calculator');
		 foreach ($shortcodes as $shortcode) {
		 	$this->add_shortcodes($shortcode);
		 } 
	}

	public function add_shortcodes($shortcode){
			add_shortcode($shortcode,function()use(&$shortcode){
				  ob_start();
				   if(file_exists(CE_DC_PLUGIN_PATH.'/public/templates/shortcodes/'.''.$shortcode.'.php'));
				   include_once(CE_DC_PLUGIN_PATH.'/public/templates/shortcodes/'.''.$shortcode.'.php');
		    	   return ob_get_clean();   
			});
	}


	public function append_calculation_form(){
		global $product;
		if( $product->is_type('variable') ) return; // Not variable products

		if ( is_product() && has_term( 'inmeting', 'product_cat' ) ) {
			include_once(CE_DC_PLUGIN_PATH.'/public/templates/shortcodes/distance_calculator.php');
		}else{
			return;
		}
		//locate_template('/templates/shortcodes/distance_calculator.php',true);
	}

	public function update_ce_dc_price(){
		$product = wc_get_product( $_POST['product'] );
		$distance =  $_POST['distance']/1000;
		$amount = get_option( $this->option_name . '_price' );
		$base_price = (float) wc_get_price_to_display( $product );

		$new_amount = $amount * $distance;
		$base_price = $base_price + $new_amount;
		echo wc_price($base_price);
		die();
	}
	// Add selected pack data as custom data to cart items
	public function woocommerce_add_cart_item_data( $cartItemData, $productId, $variationId ){
			if(!isset($_POST['totalDistance']))
				return $cartItemData;
			$totaldistance = (float) $_POST['totalDistance']/1000;
			$amount = get_option( $this->option_name . '_price' );
			$amount = $totaldistance * $amount;

			$product    = wc_get_product($productId);
			$base_price = (float) $product->get_price();
			$new_price = $base_price + $amount;
			$cartItemData['ce_Distance'] = array(
				'totaldistance'=>(float)$_POST['totalDistance']/1000,
				'origin'=>$_POST['origin'],
				'destination'=>$_POST['destination'],
				'new_price'=>$new_price
			);
		    return $cartItemData;
	}
	public function woocommerce_get_cart_item_from_session($cartItemData, $cartItemSessionData, $cartItemKey){
		   if ( isset( $cartItemSessionData['ce_Distance'] ) ) {
		        $cartItemData['ce_Distance'] = $cartItemSessionData['ce_Distance'];
		    }

		    return $cartItemData;
	}

	// Set conditionally a custom item price
	public function woocommerce_before_calculate_totals($cart_object ){
	    if( !WC()->session->__isset( "ce_Distance" )) {

	        foreach ( $cart_object->cart_contents as $key => $value ) {
	            if( isset( $value["ce_Distance"]['new_price'] ) ) {
	                // Turn $value['data']->price in to $value['data']->get_price()
	                $orgPrice = floatval($value["ce_Distance"]['new_price']);
	               //$discPrice = $orgPrice + $additionalPrice;
	                $value['data']->set_price($orgPrice);
	            }
	        }
	    }
	}

	// Display custom data in  checkout page
	public function woocommerce_get_item_data( $data, $cartItem){
	 if ( isset( $cartItem['ce_Distance'] ) ) {
        $data[] = array(
            'name' => 'Rij afstand',
            'value' => $cartItem['ce_Distance']['totaldistance'].' KM'
        );       
        $data[] = array(
            'name' => '',
            'value' => $cartItem['ce_Distance']['origin'].' to '.$cartItem['ce_Distance']['destination']
        );
    }

    return $data;
	}

	public function woocommerce_add_order_item_meta($itemId, $values, $key){
		if ( isset( $values['ce_Distance'] ) ) {
        wc_add_order_item_meta( $itemId, 'ce_Distance', $values['ce_Distance'] );
    	}
	}
}

