<?php
/**
 * This class handles all hooks for the filters.
 * 
 * @author MatthiasWeb
 * @package real-media-library\inc\attachment
 * @since 1.0
 * @singleton
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class RML_Filter {
        private static $me = null;
        
        private function __construct() {
                
        }
        
        /**
         * Prepare the view in javascript
         * 
         * @hooked admin_head
         * @author MatthiasWeb
         * @since 1.0
         */
        public function admin_head() {
                $arr = RML_Structure::getInstance()->namesSlugArray();
                $mode = get_user_option( 'media_library_mode', get_current_user_id() ) ? get_user_option( 'media_library_mode', get_current_user_id() ) : 'grid';
                ?>
                <script>
                        window.folderAttachmentsArray = {
                                names: <?php echo json_encode($arr["names"]); ?>,
                                slugs: <?php echo json_encode($arr["slugs"]); ?>
                        };
                        window.rml_ajax_url = '<?php echo admin_url('admin-ajax.php'); ?>';
                        window.rml_list_mode = '<?php echo $mode; ?>';
                </script>
                <?php
        }
        
        /**
         * Show a "Folder gallery" to the TinyMCE
         * 
         * @hooked add_media_button
         * @author MatthiasWeb
         * @since 1.0
         */
        public function add_media_button( $button ) {
			global $wp_version;
			$output = '';
			
	        	$img 	= '<i class="fa fa-folder-open-o"></i>&nbsp;';
	        	$output = '<a href="#TB_inline?width=640&inlineId=rml-iframe-container" class="thickbox button"
		                        title="' . __( 'Folder gallery', RML_TD) . '"
		                        style="padding-left: .4em;">' . $img . ' ' . __( 'Folder gallery', RML_TD) . '</a>';
	
			return $button . $output;
	
		}
		
		/**
         * Create the modal for the "Folder gallery" button.
         * 
         * @hooked add_media_display
         * @author MatthiasWeb
         * @since 1.0
         */
        function add_media_display() {
		?>
		<script type="text/javascript">
			function insertFolderGallery() {
				// Catch data
				//var data = { };
				var shortcodeAttr = "";
				jQuery("#rml-iframe-container-wrap [data-name]").each(function() {
				        var name = jQuery(this).attr("data-name"),
				                val = jQuery(this).val(),
				                def = jQuery(this).attr("data-default");
				        //data[name] = val;
				        
				        if (val != def) {
				                shortcodeAttr += name + '="' + val + '" ';
				        }
				});
				shortcodeAttr = shortcodeAttr.trim();
				
				window.send_to_editor('[folder-gallery ' + shortcodeAttr + ']');
				tb_remove();
			}
		</script>

		<div id="rml-iframe-container" style="display: none;">
			<div class="wrap" id="rml-iframe-container-wrap" style="padding: 1em">
				<div class="collection-settings gallery-settings">
				        <label class="setting" style="display:block;margin-bottom:10px;" data-default="-1">
                        			<span><?php _e('Choose your folder', RML_TD); ?></span>
                        			<select data-name="fid">
                        				<?php
                					echo RML_Structure::getInstance()->optionsHTML(-1);
                        				?>
                        			</select>
                        		</label>
				        
                        		<label class="setting" style="display:block;margin-bottom:10px;">
                        			<span><?php _e('Link To'); ?></span>
                        			<select data-name="link" data-default="post">
                        				<option value="post" selected="selected"><?php _e('Attachment File'); ?></option>
                        				<option value="file"><?php _e('Media File'); ?></option>
                        				<option value="none"><?php _e('None'); ?></option>
                        			</select>
                        		</label>
                        
                        		<label class="setting" style="display:block;margin-bottom:10px;">
                        			<span><?php _e('Columns'); ?></span>
                        			<select data-name="columns" data-default="3">
                        			        <?php
                        			        for ($i = 1; $i < 10; $i++) {
                        			                echo '<option value="' . $i . '" ' . (($i == 3) ? 'selected="selected"' : '') . '>' . $i . '</option>';
                        			        }
                        			        ?>
						</select>
                        		</label>

                        		<label class="setting" style="display:block;margin-bottom:10px;">
                        			<span><?php _e('Random Order'); ?></span>
                        			<select data-name="orderby" data-default="">
                        			        <option value="" selected="selected"><?php _e('No'); ?></option>
                        			        <option value="rand"><?php _e('Yes'); ?></option>
						</select>
                        		</label>
                        
                        		<label class="setting size" style="display:block;margin-bottom:10px;">
                        			<span><?php _e('Size'); ?></span>
                        			<select class="size" data-name="size" data-default="thumbnail">
        						<option value="thumbnail"><?php _e('Thumbnail'); ?></option>
        						<option value="medium"><?php _e('Medium'); ?></option>
        						<option value="large"><?php _e('Large'); ?></option>
        						<option value="full"><?php _e('Full Size'); ?></option>
						</select>
                        		</label>
                        	</div>
				<input type="button" class="button-primary" value="<?php echo esc_attr__( 'Insert gallery', RML_TD ); ?>" onclick="insertFolderGallery();" />
				<a class="button-secondary" onclick="tb_remove();" title="<?php echo esc_attr__( 'Cancel' ); ?>"><?php echo esc_attr__( 'Cancel' ); ?></a>
			</div>
		</div>

		<?php
        }
        
        /**
         * Define a new query option for WP_Query.
         * "rml_folder" integer
         * 
         * @hooked pre_get_posts
         * @author MatthiasWeb
         * @since 1.0
         */
        public function pre_get_posts($query) {
            $queryFolder = $query->get('rml_folder');
        	if (isset($queryFolder) && $queryFolder > 0) {
    	        // Query rml folder from query itself
        		$folder = $queryFolder;
        	}else if(current_user_can("upload_files")) {
        		if (isset($_REQUEST["rml_folder"])) {
	    	        // Query rml folder from lis mode
	        		$folder = $_REQUEST["rml_folder"];
	        	}else if (isset($_POST["query"]["rml_folder"])) {
	    	        // Query rml folder from grid mode
	    	        $folder = $_POST["query"]["rml_folder"];
	        	}else{
	        		return;
	        	}
            }else{
        		return;
        	}
        	
        	if(is_numeric($folder)){
        		$mq = $query->get('meta_query');
    			if (!is_array($mq)) {
    				$mq = array();
    			}
    			
        		if ($folder > 0) {
        			$mq[] = array(
			            'key' => '_rml_folder',
        	            'value' => $folder,
        	            'compare' => '='
        	        );
        			
        			$query->set('meta_query', $mq);
        		}else if ($folder == "-1"){
        			$mq[] = array(
        				'relation' => 'OR',
        				array(
							'key' => '_rml_folder',
        		            'value' => '-1',
        		            'compare' => '='
        	        	),
        	        	array(
        					'key' => '_rml_folder',
        		            'value' => '',
        		            'compare' => 'NOT EXISTS'
        	        	)
    	        	);
    	        	
    	        	$query->set('meta_query', $mq);
        		}
        	}
        }
        
        /**
         * Create a select option in list table of attachments
         * 
         * @hooked restrict_manage_posts
         * @author MatthiasWeb
         * @since 1.0
         */
        public function restrict_manage_posts() {
            $screen = get_current_screen();
        	if ($screen->id == "upload") {
        		echo '<select name="rml_folder" id="filter-by-rml-folder">
        			' . RML_Structure::getInstance()->optionsHTML(isset($_REQUEST['rml_folder']) ? $_REQUEST['rml_folder'] : "") . '
        		</select>&nbsp;';
        	}
        }
        
        public static function getInstance() {
            if (self::$me == null) {
                    self::$me = new RML_Filter();
            }
            return self::$me;
        }
}