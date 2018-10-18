/*
 * Gutenberg block Javascript code
 */

var __                = wp.i18n.__;
var createElement     = wp.element.createElement;
var registerBlockType = wp.blocks.registerBlockType;
var InspectorControls = wp.editor.InspectorControls;
var Button = wp.components.Button;
var RichText = wp.editor.RichText;
var Editable = wp.blocks.Editable;

/**
 * Register block
 *
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          Block itself, if registered successfully,
 *                             otherwise "undefined".
 */
registerBlockType(
	'gdm/google-drive-embedder-viewer', // Registering a Block for the Drive Plugin
	{
		title: __( 'Google Drive Embedder' ),
		icon : 'category',
		category: 'common',
		attributes: {
			content: {
				type: "string",
				source: 'children',
				selector: 'p',
			},
		},

		// Defines the block within the editor.
		edit: function( props ) {
			var content = props.attributes.content;
							
			function updateMessage(newContent){
				props.setAttributes({ content: newContent });
			}
			
			var {attributes , setAttributes, focus, className} = props;
			
			var onAddGoogleFile = function(e) {

				var chooseDriveFile = jQuery("#gdm-choose-drivefile");
				
				jQuery.gdmColorbox({href: chooseDriveFile, inline: true,
					onLoad: function() {
						chooseDriveFile.show();
					},
					onCleanup: function() {
						chooseDriveFile.hide();
					}
				});

				window.setTimeout( function() {
					gdmThickDims();
				}, 1); 
			}
			
			var get_class = new Date();
			var class_name = get_class.getTime();
			
			function newButtonFunction(){
				var class_name_full = 'p.' + class_name;
				newContent = jQuery(class_name_full).text();
				props.setAttributes({ content: newContent });
				var hide_specific_button = 'button.' + class_name;
				jQuery(hide_specific_button).css("display", "none");
			}
			
			return [
				createElement(
					RichText,
					{	
						tagName: 'p',
						className: class_name,
						value: props.attributes.content,
						placeholder: 'ShortCode',
						key: 'New Text Area',
						onChange: updateMessage,
					},
					
				),
				createElement(
					'button',
					{
						id: 'root',
						key:'demo',
						className: class_name,
						style : {backgroundColor: '#0085ba' , color: '#fff', height: '25px',fontSize: '12px', marginTop:'10px', display: 'none'},
						onClick: newButtonFunction,
					},
					__('Click to Add ShortCode')
				),
									
				createElement( InspectorControls, { key: 'inspector' }, // Display the block options in the inspector pancreateElement.
					createElement('div',{ className: 'gde_div_main'}	,
						createElement(
							'p',
							{},
							__('Enter Google Drive file using this button.'),
						),
						createElement(
							'span',
							{
								key: 'button-for-google',
								className: class_name,
								id: 'gde_ins_btn_gb',
								onClick: onAddGoogleFile,
								style : { backgroundColor:'#0085ba', color:'#fff', height:'40px', padding:'15px', textAlign:'center', display:'inline-flex', alignItems:'center', borderRadius:'30px', fontSize: '15px', cursor: 'pointer'}
							},
							__('Select Google File')
						),
					),    
				),
			];
		},

		// Defines the saved block.
		save: function( props ) {
			return createElement(
				'p',
				{
					className: props.className,
					key: 'return-key',
				},props.attributes.content);
		},
	}
);

function enable_append_btn(){
	var className = jQuery('span#gde_ins_btn_gb').attr('class');
	var btn_add_name = 'button.'+ className;
	jQuery(btn_add_name).css("display", "block");	
	setTimeout(function(){
		jQuery(btn_add_name).click();
	},10);
	jQuery('button#gdmCboxClose').click();
}			
function close_insert_popup(){
	jQuery('button#gdmCboxClose').click();	
}
jQuery(document).ready(function() {
	jQuery(document).on('click','button#gdmCboxClose',function(){
		var getData = jQuery('#gdmCboxTitle').html();
		if(getData){
			jQuery("#gdm-choose-drivefile").css('display','none');
			setTimeout(openSelectBox, 350);
		}else{
			//alert("content not found");
		}
	});
	function openSelectBox(){
		jQuery('span#gde_ins_btn_gb').click();
	}
});

jQuery(document).on('keydown', function(e) {
	if (e.keyCode === 27) {
		var getData = jQuery('#gdmCboxTitle').html();
		if(getData){
			jQuery("#gdm-choose-drivefile").css('display','none');
			setTimeout(openSelectBox, 350);
		}else{
		}
		function openSelectBox(){
			jQuery('span#gde_ins_btn_gb').click();
		}
	}
});