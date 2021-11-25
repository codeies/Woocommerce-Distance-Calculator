<?php 
wp_enqueue_script($this->plugin_name.'googleplaces');
wp_enqueue_script($this->plugin_name);
wp_enqueue_style($this->plugin_name); 
$default_origin = get_option( $this->option_name . '_default_origin' );
global $product;
?>    
<div class="dc-container">
	<div class="container-map" id="google-map"></div>   
        <div>
            <!-- Location 1 -->
            <div class="dcrow">
                <div class="location-label">
                    <label>Vertrek punt: </label>                                    
                </div>
                <div class="location-input">
                    <input type="text" id="location-1" name="origin" readonly="" value="<?php echo $default_origin; ?>" placeholder=""> 
                </div>
            </div>
            <!-- Location 2 -->
            <div class="dcrow">
                <div class="location-label">
                    <label>Bestemming: </label>
                </div>
                <div class="location-input">
                    <input required type="text" id="location-2" name="destination" placeholder="Adres">
                </div>
            </div>
            <div class="dcrow">
            	<div id="output" class="result-table"></div>
				 <p class="price"><span class="amount"></span></p> 
            </div>
            <input required id="totalDistance" type="hidden" name="totalDistance">
     
            
            <!-- Stats table -->                
            
       </div> 
          
    </div>
    <script>

jQuery(function($){
        var b = 'p.price .amount',
            c = '#totalDistance';
        $(c).on( 'change paste keyup', function(){
            $(b).html('Calculating...');
             var data = {
            'action': 'update_ce_dc_price',
            'product': <?php echo $product->id; ?>,      // We pass php values differently!
            'distance': $(c).val()      // We pass php values differently!
        };
            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data, function(response) {
                $(b).html(response);
            });
        });
    });
</script>
<?php return; 