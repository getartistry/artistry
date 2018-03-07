<?php 

class USIN_Templates{
	
	
	public static function progress_tag($percentage){
		return sprintf('<span class="usin-tag usin-progress-tag">
			<span class="usin-progress usin-progress-%d"></span><span class="usin-progress-percentage">%d%%</span></span>', 
		self::round_percentage($percentage), $percentage);
	}
	
	
	// HELPER FUNCTIONS
	
	protected static function round_percentage($percentage){
	    if($percentage > 0 && $percentage < 10){
			//set to 10 to show some progress
	        return 10;
	    }
	    if($percentage > 90 && $percentage < 100){
			//set to 90 to indicate it's still not completed
	        return 90;
	    }
	    
	    return round($percentage / 10) * 10;

	}
	
}