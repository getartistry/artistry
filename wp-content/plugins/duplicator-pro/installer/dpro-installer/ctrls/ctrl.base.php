<?php

/**
 * Base controller class for installer controllers
 *
 * Standard: PSR-2
 * @link http://www.php-fig.org/psr/psr-2 Full Documentation
 *
 * @package SC\DUPX\CTRL\Base
 *
 */
//Enum used to define the various test statues 
final class DUPX_CTRL_Status
{
	const FAILED	 = 0;
	const SUCCESS	 = 1;

}

/**
 * A class structer used to report on controller methods
 *
 * @package Dupicator\ctrls\
 */
class DUPX_CTRL_Report
{
	//Properties
	public $runTime;
	public $outputType = 'JSON';
	public $status;

}

/**
 * Base class for all controllers
 * 
 * @package Dupicator\ctrls\
 */
class DUPX_CTRL_Base
{
	public $opts;
	public $report;
	public $model;

	private $time_start;
	private $time_end;

	/**
	 *  Init this instance of the object
	 */
	public function __construct($opts)
	{
		$this->report = new DUPX_CTRL_Report();
		$this->opts	  = $opts;
	}

	public function startProcessTime()
	{
		$this->time_start = $this->microtimeFloat();
	}

	public function getProcessTime()
	{
		$this->time_end = $this->microtimeFloat();
		$this->report->runTime = $this->time_end - $this->time_start;
		return $this->report->runTime;
	}

	private function microtimeFloat()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}


}
?>