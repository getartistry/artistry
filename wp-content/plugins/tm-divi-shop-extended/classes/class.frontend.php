<?php

if (!defined( 'ABSPATH')) exit;
 
class tm_frontend{
	
	public $tm_plugin_dir_url;
    public $tm_options;
    public $tm_style;

	function __construct($tm_plugin_dir_url){

		$this->tm_plugin_dir_url = $tm_plugin_dir_url;
		$this->tm_options = array(
			'enable_quick_view' => '1',
			'enable_mobile'     => '0',
			'button_lable'      => 'Quick View'
		);
  		$this->tm_style   = array(
			'modal_bg'   		=> '#fff',
			'close_btn'   		=> '#95979c',
			'close_btn_bg' 		=> '#4C6298',
			'navigation_bg'		=> 'rgba(255, 255, 255, 0.2)',
			'navigation_txt'    => '#fff'
		);

        add_action( 'wp_enqueue_scripts', array($this,'tm_load_assets'));
		add_action( 'woocommerce_after_shop_loop_item', array($this,'tm_add_button') );
		add_action( 'wp_footer', array($this, 'tm_remodel_model'));
		add_action( 'wp_ajax_tm_get_product', array($this,'tm_get_product') );
        add_action( 'wp_ajax_nopriv_tm_get_product', array($this,'tm_get_product') );

        add_action('tm_show_product_sale_flash','woocommerce_show_product_sale_flash');
        add_action('tm_show_product_images', array($this,'tm_woocommerce_show_product_images'));

        add_action( 'tm_product_data', 'woocommerce_template_single_title');
        add_action( 'tm_product_data', 'woocommerce_template_single_rating' );
        add_action( 'tm_product_data', 'woocommerce_template_single_price');
        add_action( 'tm_product_data', 'woocommerce_template_single_excerpt');
        add_action( 'tm_product_data', 'woocommerce_template_single_add_to_cart');
        add_action( 'tm_product_data', 'woocommerce_template_single_meta' );
 
	}
    



    public function tm_woocommerce_show_product_images(){

		global $post, $product, $woocommerce;

		?>
		<div class="images">
		<?php 

        if ( has_post_thumbnail() ) {
			$attachment_count = count( $product->get_gallery_attachment_ids() );
			$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $props['url'], $props['caption'], $image ), $post->ID );
		} else {
			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
		}


		$attachment_ids = $product->get_gallery_attachment_ids();
		if ( $attachment_ids ) :
			$loop 		= 0;
			$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
			?>
			<div class="thumbnails <?php echo 'columns-' . $columns; ?>"><?php
				foreach ( $attachment_ids as $attachment_id ) {
					$classes = array( 'thumbnail' );
					if ( $loop === 0 || $loop % $columns === 0 )
						$classes[] = 'first';
					if ( ( $loop + 1 ) % $columns === 0 )
						$classes[] = 'last';
					$image_link = wp_get_attachment_url( $attachment_id );
					if ( ! $image_link )
						continue;
					$image_title 	= esc_attr( get_the_title( $attachment_id ) );
					$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );
					$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), 0, $attr = array(
						'title'	=> $image_title,
						'alt'	=> $image_title
						) );
					$image_class = esc_attr( implode( ' ', $classes ) );
					echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s" >%s</a>', $image_link, $image_class, $image_caption, $image ), $attachment_id, $post->ID, $image_class );
					$loop++;
				}
			?>
				
			</div>
		<?php endif; ?>
		</div>
		<?php
    }




	public function tm_load_assets(){
        
        wp_enqueue_style  ( 'tm_remodal_default_css',    $this->tm_plugin_dir_url.'modules/tm-module-shop/css/style.css');
		wp_register_script( 'tm_frontend_js', $this->tm_plugin_dir_url.'modules/tm-module-shop/js/frontend.js',array('jquery'),'1.0', true);
		$frontend_data = array(

		'tm_nonce'          => wp_create_nonce('tm_nonce'),
		'ajaxurl'             => admin_url( 'admin-ajax.php' ),
		'tm_plugin_dir_url' => $this->tm_plugin_dir_url
 

		);

		wp_localize_script( 'tm_frontend_js', 'tm_frontend_obj', $frontend_data );
		wp_enqueue_script ( 'jquery' );
		wp_enqueue_script ( 'tm_frontend_js' );
		wp_register_script( 'tm_remodal_js',$this->tm_plugin_dir_url.'modules/tm-module-shop/js/remodal.js',array('jquery'),'1.0', true);
		wp_enqueue_script('tm_remodal_js');

		global $woocommerce;
 
		$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$lightbox_en = 'no' == 'yes' ? true : false;
		 
		if ( $lightbox_en ) {
		    wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/modules/tm-module-shop/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.6', true );
		    wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/modules/tm-module-shop/assets/css/prettyPhoto.css' );
		}
		wp_enqueue_script('thickbox');

 
	    $custom_css = '
	    .remodal .remodal-close{
	    	color:'.$this->tm_style['close_btn'].';
	    }
	    .remodal .remodal-close:hover{
	    	background-color:'.$this->tm_style['close_btn_bg'].';
	    }
	    .woocommerce .remodal{
	    	background-color:'.$this->tm_style['modal_bg'].';
	    }
	    .tm_prev h4,.tm_next h4{
	    	color :'.$this->tm_style['navigation_txt'].';
	    }
	    .tm_prev,.tm_next{
	    	background :'.$this->tm_style['navigation_bg'].';
	    }
        .woocommerce a.quick_view{
            background-color: '.$this->tm_style['close_btn'].' ;
        }';
        wp_add_inline_style( 'tm_remodal_default_css', $custom_css );


         
	}


	public function tm_remodel_model(){
 
		echo '<div class="remodal" data-remodal-id="modal" role="dialog" aria-labelledby="modalTitle" aria-describedby="modalDesc">
		  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
		    <div id = "tm_contend"></div>
		</div>';

		 
	}


	public function tm_add_button(){

		global $post;
        echo '<a data-product-id="'.$post->ID.'"class="quick_view button" title="Preview">
        <span>'.$this->tm_options['button_lable'].'</span></a>';
	}


	public function tm_get_product(){

		global $woocommerce;

		$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$lightbox_en = 'no' == 'yes' ? true : false;

		
		global $post;
		$product_id = $_POST['product_id'];
		if(intval($product_id)){

			 wp( 'p=' . $product_id . '&post_type=product' );
 	         ob_start();
 	

		 	while ( have_posts() ) : the_post(); ?>
	 	    
 	        <div class="product">  

 	                <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class('product'); ?> >  
 	                        <?php do_action('tm_show_product_sale_flash'); ?> 

 	                           <?php do_action( 'tm_show_product_images' );  ?>
                               
	 	                        <div class="summary entry-summary scrollable">
	 	                                <div class="summary-content">   
	                                       <?php

	                                        do_action( 'tm_product_data' );

	                                        ?>
	 	                                </div>
	 	                        </div>
	 	                         <div class="scrollbar_bg"></div>
 
 	                </div> 
 	        </div>
 	       
 	        <?php endwhile;

            	$post                  = get_post($product_id);
            	$next_post             = get_next_post();
			    $prev_post             = get_previous_post();
			    $next_post_id          = ($next_post != null)?$next_post->ID:'';
			    $prev_post_id          = ($prev_post != null)?$prev_post->ID:'';
			    $next_post_title       = ($next_post != null)?$next_post->post_title:'';
 		     	$prev_post_title       = ($prev_post != null)?$prev_post->post_title:'';
			 	$next_thumbnail        = ($next_post != null)?get_the_post_thumbnail( $next_post->ID,
			 		                  'shop_thumbnail',''):'';
 		     	$prev_thumbnail        = ($prev_post != null)?get_the_post_thumbnail( $prev_post->ID,
 		     		                   'shop_thumbnail',''):'';

 	        ?> 
            
 	        <div class ="tm_prev_data" data-tm-prev-id = "<?php echo $prev_post_id; ?>">
 	        <?php echo $prev_post_title; ?>
 	            <?php echo $prev_thumbnail; ?> 
 	        </div> 
 	        <div class ="tm_next_data" data-tm-next-id = "<?php echo $next_post_id; ?>">
 	        <?php echo $next_post_title; ?>
 	             <?php echo $next_thumbnail; ?> 
 	        </div> 

 	        <?php
 	                  
 	        echo  ob_get_clean();
 	
 	        exit();
            
			
	    }
	}
	
}

?>