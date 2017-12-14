<?php

	$nomTheme='woogoogad';

	$pot='#'.$nomTheme."\n\n";
	$total=array();
	
	$folders = array(
		''
	);
	
	foreach($folders as $folder)
	{
		if ($handle = opendir('..'.$folder)) 
		{
			while (false !== ($file = readdir($handle))) 
			{
				if (substr($file,-3)=='php')
				{
					$str=file_get_contents('..'.$folder.'/'.$file);
				
					$matches=array();
					preg_match_all('/_[_ex]\(\s*?\'(.+)\'\s*?(,\s*?([^\)]+)\s*?)?\)/Uis',$str,$matches,PREG_SET_ORDER);
				
					if (isset($matches))
					{
						foreach($matches as $index => $string)
						{
							if (count($string) === 4 && $string[3] == '\''.$nomTheme.'\'')
							{
								$total[$string[1]][]=$file;
							}
						}
					}
				}
			}
			closedir($handle);
		}
	}
	
	foreach($total as $k=>$t)
	{
		$pot.='#:'.implode(' ',$t)."\n";
		$pot.='msgid "'.str_replace(array('"','\\\''),array('\"','\'',),$k).'"'."\n";
		$pot.='msgstr ""';
		$pot.="\n\n";
	}

	file_put_contents($nomTheme.'.pot',$pot);
?>