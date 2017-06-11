<?php
/**
 * Runs a recursive scan on a directory and finds symlinks and unreadable files
 * and returns the results as an array
 * 
 * @package DupicatorPro\classes
 */
class DUP_PRO_ScanValidator 
{
	public $FileCount = 0;
	public $DirCount = 0;
	public $LimitReached = false;
	public $MaxFiles = 1000000;
	public $MaxDirs = 75000;

	public function getDirContents($dir, &$results = array())
	{
		if ($this->FileCount > $this->MaxFiles || $this->DirCount > $this->MaxDirs) 
		{	
			$this->LimitReached = true;
			return $results;
		}

		$files = @scandir($dir);
		if (is_array($files)) 
		{
			foreach($files as $key => $value)
			{
				$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
				if ($path) {
					if(!is_dir($path)) {
						if (!is_readable($path))
						{
							$results[] = $path;
						} 
						else if ($this->_is_link($path)) 
						{
							$results[] = $path;
						}
						$this->FileCount++;
					} 
					else if($value != "." && $value != "..") 
					{
						if (! $this->_is_link($path)) 
						{
							$this->getDirContents($path, $results);
						}

						if (!is_readable($path))
						{
							 $results[] = $path;
						}
						else if ($this->_is_link($path)) {
							$results[] = $path;
						}
						$this->DirCount++;
					}
				}
			}
		}
		return $results;
	}

	//Supports windows and linux
	private function _is_link($target) 
	{ 
		if (defined('PHP_WINDOWS_VERSION_BUILD')) {
			if(file_exists($target) && @readlink($target) != $target) {
				return true;
			}
		} elseif (is_link($target)) {
			return true;
		}
		return false;
	}
}
