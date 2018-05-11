<?php 
function getGoodBadText($inValue, $greenGood)
{
	// we start with it all good man
	$text = 'Yes';
	$class = 'good';
	// is the value isn't true and did we say green should be good, then let's make it bad with red
	if(!$inValue):
		$text = 'No';
		$class = 'bad';	 // what you say a YES should be bad... (red) 
	endif;

	if(!$greenGood && !$inValue)
		$class = 'good';	 // what you say a YES should be bad... (red) 

	if($greenGood && $inValue)
		$class = 'good';	 // what you say a YES should be bad... (red) 

	if(!$greenGood && $inValue)
		$class = 'bad';	 // what you say a YES should be bad... (red) 
	// return span with the right class and text
	return '<span class="'. $class .'"><strong>'.$text.'</strong></span>';	
}
function getOnOff($inValue, $greenGood)
{
	// we start with it all good man
	$text = 'On';
	$class = 'good';
	// is the value isn't true and did we say green should be good, then let's make it bad with red
	if(!$inValue):
		$text = 'Off';
		$class = 'bad';	 // what you say a YES should be bad... (red) 
	endif;

	if(!$greenGood && !$inValue)
		$class = 'good';	 // what you say a YES should be bad... (red) 

	if($greenGood && $inValue)
		$class = 'good';	 // what you say a YES should be bad... (red) 

	if(!$greenGood && $inValue)
		$class = 'bad';	 // what you say a YES should be bad... (red) 
	// return span with the right class and text
	return '<span class="'. $class .'"><strong>'.$text.'</strong></span>';	
}

function get_my_pluginlist($array_Plugins, $var_sShow) {
	$var_iPlugInNumber = 1;

	$var_translatePlugin = __('Plugin', 'wp-list-plugins');
	$var_translateVersion = __('Version', 'wp-list-plugins');
	$var_translateDescription = __('Description', 'wp-list-plugins');

	$plugins_allowedtags1 = array(
		'a' => array(
			'href' => array(),
			'title' => array()
		),
		'abbr' => array(
			'title' => array()
		),
		'acronym' => array(
			'title' => array()
		),
		'code' => array(),
		'em' => array(),
		'strong' => array()
	);
	$plugins_allowedtags2 = array(
		'abbr' => array(
			'title' => array()
		),
		'acronym' => array(
			'title' => array()
		),
		'code' => array(),
		'em' => array(),
		'strong' => array()
	);

	switch($var_sShow) {
		case 'all':
			$var_sHeadline = __('List of all installed plugins. Inactive plugins will be stroke through.', 'wp-list-plugins');
			break;

		case 'active':
		//	<i class="fa fa-files-o"></i> .htaccess file backups 
			$var_sHeadline = __('<a href="javascript:;" name="plugInBlock_'.$var_sShow.'" class="show_backup_files">List of all installed (active) plugins.</a>', 'wp-list-plugins');
			break;

		case 'inactive':
			$var_sHeadline = __('<a href="javascript:;" name="plugInBlock_'.$var_sShow.'" class="show_backup_files">List of all installed (inactive) plugins.</a>', 'wp-list-plugins');
			break;
	}

	$var_sHtml = '
			<div class="plugInListWrapper">
				<div class="plugInListLine plugInListHeadline">
					<div class="plugInListHeadDescription">' . $var_sHeadline . '</div>
				</div>					<div class="plugInBlock_'.$var_sShow.'">
						<div class="plugInListNumber">Nr.</div>
						<div class="plugInListName">' . __('Plugin', 'wp-list-plugins') . '</div>
						<div class="plugInListVersion">' . __('Version', 'wp-list-plugins') . '</div>
						<div class="plugInListDescription">' . __('Description', 'wp-list-plugins') . '</div>
';


	foreach($array_Plugins as $plugin_file => $plugin_data) {
		if(is_plugin_active($plugin_file)) {
			if($var_sShow == 'inactive') {
				continue;
			}

			if($var_sShow == 'all') {
				$plugin_data['active'] = 'plugInIsActive';
			}
		} else {
			if($var_sShow == 'active') {
				continue;
			}

			if($var_sShow == 'all') {
				$plugin_data['active'] = 'plugInIsInactive';
			}
		}

		// PlugIn-Daten sammeln
		$plugin_data['Title'] = wp_kses($plugin_data['Title'], $plugins_allowedtags1);
		$plugin_data['Title'] = ($plugin_data['PluginURI']) ? '<a href="' . $plugin_data['PluginURI'] . '">' . $plugin_data['Title'] . '</a>' : $plugin_data['Title'];
		$plugin_data['Version'] = wp_kses($plugin_data['Version'], $plugins_allowedtags1);
		$plugin_data['Description'] = wp_kses($plugin_data['Description'], $plugins_allowedtags2);
		$plugin_data['Author'] = wp_kses($plugin_data['Author'], $plugins_allowedtags1);
		$plugin_data['Author'] = (empty($plugin_data['Author'])) ? '' : ' <cite>' . sprintf(__('By %s', 'wp-list-plugins'), ($plugin_data['AuthorURI']) ? '<a href="' . $plugin_data['AuthorURI'] . '">' . $plugin_data['Author'] . '</a>' : $plugin_data['Author']) . '.</cite>';

		$var_sHtml .= '
				<div class="plugInListLine ' . $plugin_data['active'] . '">
					<div class="plugInListNumber">
						<p>' . $var_iPlugInNumber . '</p>
					</div>
					<div class="plugInListName">
						<p>' . $plugin_data['Title'] . '</p>
					</div>
					<div class="plugInListVersion">
						<p>' . $plugin_data['Version'] . '</p>
					</div>
					<div class="plugInListDescription">
						<p>' . $plugin_data['Description'] . '</p>
						<p>' . $plugin_data['Author'] . '</p>
					</div>
				</div>';

		$var_iPlugInNumber++;
	}

	$var_sHtml .= '</div></div>';

	return $var_sHtml;
}
?>