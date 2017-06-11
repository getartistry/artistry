<?php

require_once (DUPLICATOR_PRO_PLUGIN_PATH . 'classes/ui/class.ui.screen.base.php');

class DUP_PRO_Package_Screen extends DUP_PRO_UI_Screen
{
	
	public function __construct($page)
    {
       add_action('load-'.$page, array($this, 'Init'));
    }
	
	public function Init() 
	{
		$active_tab = isset($_GET['inner_page']) ? $_GET['inner_page'] : 'list';
		$active_tab = isset($_GET['action']) && $_GET['action'] == 'detail' ? 'detail' : $active_tab;
		$this->screen = get_current_screen();
		
		switch (strtoupper($active_tab)) {
			case 'LIST':	$content = $this->get_list_help();		break;	
			case 'NEW1':	$content = $this->get_step1_help();		break;	
			case 'NEW2':	$content = $this->get_step2_help(); 	break;	
			case 'DETAIL':	$content = $this->get_details_help(); 	break;	
			default:
				$content = $this->get_list_help(); 
				break;
		}
		
		$guide = '#guide-packs';
		$faq   = '#faq-package';
		$content .= "<b>References:</b><br/>"
					. "<a href='https://snapcreek.com/duplicator/docs/guide/{$guide}' target='_sc-guide'>Guide</a> | "
					. "<a href='https://snapcreek.com/duplicator/docs/faqs-tech/{$faq}' target='_sc-guide'>FAQs</a>";
		
		$this->screen->add_help_tab( array(
				'id'        => 'dpro_help_package_overview',
				'title'     => DUP_PRO_U::__('Overview'),
				'content'   => "<p>{$content}</p>"
			)
		);
		
		$this->getSupportTab($guide, $faq);
		$this->getHelpSidbar();
	}	
	
	public function get_list_help() 
	{
		return  DUP_PRO_U::__("<b>Packages » All</b><br/> The 'Packages' section is the main interface for managing all the packages that have been created. A Package consists "
				. "of two core files: an 'Installer' and an 'archive'.  The installer file is a php file that when browsed to presents an installation wizard that redeploys or installs the your website by processing the Archive file."
				. "The Archive file is a compressed zip file containing all your WordPress files and a copy of your WordPress database.<br/><br/>"
                . "To create a package click the "
				. "'Create New' button and follow the prompts. To view the details of a package build click on the <i class='fa fa-archive'></i> Package Details icon.<br/><br/>");
	}			
	

	public function get_step1_help() 
	{
		return DUP_PRO_U::__("<b>Packages New » 1 Setup</b> <br/>"
				. "The setup screen allows users to choose where they would like to store thier package, such as Google Drive, Dropbox, on the local server or a combination of both."
				. "Setup also allow users to setup optional filtered directory paths, files and database tables to change what is included in the archive file.  The optional option "
				. "to also have the installer pre-filled can be used.  To expedited the workflow consider using a Template. <br/><br/>");
	}		
	
	
	public function get_step2_help() 
	{
		return DUP_PRO_U::__("<b>Packages » 2 Scan</b> <br/>"
				. "The plugin will scan your system, files and database to let you know if there are any concerns or issues that may be present.  All items in green mean the checks "
				. "looked good.  All items in red indicate a warning.  Warnings will not prevent the build from running, however if you do run into issues with the build then checking "
				. "the warnings should be considered. <br/><br/>");
	}
	
	public function get_details_help() 
	{
		return DUP_PRO_U::__("<b>Packages » Details</b> <br/>"
				. "The details view will give you a full break-down of the package including any errors that may have occured during the install. <br/><br/>");
	}

	
	/**
	*  Packages List: Screen Options Tab
	 * TODO: Need to iron out how to add custom controls such as Create format
	*/
	public function get_list_opts() 
	{
		$args = array(
			'label' => __('Packages per page'),
			'default' => 10,
			'option' => 'duplicator_pro_opts_per_page',
			'content' => 'test'
		);
		add_screen_option( 'per_page', $args );
	}
		
	
}


