<?php

class Meow_WPMC_Checkers {

	private $core;

	public function __construct( $core ) {
		$this->core = $core;
	}

	function check( $file, $mediaId ) {
		global $wpdb;
		if ( !empty( $mediaId ) ) {
			$res = $this->check_media( $mediaId );
			if ($res)
				return $res;
		}
		if ( !empty( $file ) )
			return $this->check_file( $file );
	}

	function check_file( $file ) {
		global $wpdb;
		$table = $wpdb->prefix . "mclean_refs";
		// Is this make the results better?
		//$file = $this->core->wpmc_clean_uploaded_filename( $file );
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT originType FROM $table WHERE mediaUrl = %s", $file ) );
		if ( empty( $row ) )
			return false;
		$this->core->log( "File {$file} found as {$row->originType}" );
		$this->core->last_analysis = $row->originType;
		return true;
	}

	function check_media( $mediaId ) {
		global $wpdb;
		$table = $wpdb->prefix . "mclean_refs";
		$row = $wpdb->get_row( $wpdb->prepare( "SELECT originType FROM $table WHERE mediaId = %d", $mediaId ) );
		if ( empty( $row ) )
			return false;
		$this->core->log( "Media {$mediaId} found as {$row->originType}" );
		$this->core->last_analysis = $row->originType;
		return true;
	}

}

?>
