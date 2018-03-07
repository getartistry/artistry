<?php

/**
 * Note object - includes the main functionality for creating, updating and
 * retrieving notes.
 */
class USIN_Note{
	
	public $id;
	public $date;
	public $content;
	public $by;
	public $state = 'default';
	
	protected static $post_type = 'usin_note';
	protected static $user_meta_key = '_usin_note_for';
	protected static $count_key = '_usin_note_count';
	
	/**
	 * The ID of the note.
	 */
	public function __construct($note_id){
		$this->id = $note_id;
	}


	/**
	 * Public function to access the Note post type.
	 * @return string the Note custom post type ID
	 */
	public static function get_post_type(){
		return self::$post_type;
	}
	
	
	/**
	 * Creates a new note.
	 * @param  int $user_id the ID of the user to whom the note is attached
	 * @param  string $content the content of the note
	 * @return mixed          the ID of the note if the note has been created 
	 * successfully or false if the note was not created
	 */
	public static function create($user_id, $content){
		$post = array(
			'post_title' => sanitize_title($content, __('Note', 'usin')),
			'post_content' => $content,
			'post_type' => self::$post_type,
			'post_status' => 'publish'
		);
		$note_id = wp_insert_post($post, true);
		
		if(is_wp_error($note_id)){
			return false;
		}
		
		//update the note meta to set the user
		add_post_meta($note_id, self::$user_meta_key, $user_id, true);
		
		//save count meta
		$count = (int)get_user_meta($user_id, self::$count_key, true);
		update_user_meta($user_id, self::$count_key, ++$count);
		
		return $note_id;
	}
	
	
	/**
	 * Deletes the current note object.
	 * @return bool result of whether the note has been deleted or not
	 */
	public function delete(){
		$user_id = $this->get_note_user();
		if(!$user_id){
			return false;
		}
		$res = wp_delete_post($this->id, true);
		
		if($res === false){
			return false;
		}
		
		//save count meta
		$count = (int)get_user_meta($user_id, self::$count_key, true);
		update_user_meta($user_id, self::$count_key, --$count);
		
		return true;
	}
	
	
	/**
	 * Retrieves the user to whom this note is attached.
	 * @return int the ID of the user
	 */
	public function get_note_user(){
		return intval(get_post_meta($this->id, self::$user_meta_key, true));
	}
	
	
	/**
	 * Retrieves all of the notes attached to a user.
	 * @param  int $user_id the user ID
	 * @return array          array containing USIN_Note objects
	 */
	public static function get_all($user_id){
		$note_posts = array(); //load note posts
		$notes = array();
		$query = new WP_Query( array( 
			'post_type' => self::$post_type,
			'meta_key' => self::$user_meta_key, 
			'meta_value'=>$user_id,
			'posts_per_page'=>-1,
			'orderby' => 'date ID',
			'order' => 'DESC'
		) );
			
		$note_posts = $query->posts;
		
		
		foreach ($note_posts as $np) {
			$note = self::note_post_to_obj($np);
			$notes[]= $note;
		}
		
		$notes = apply_filters('usin_notes_list', $notes);
		
		return $notes;
		
	}
	
	public static function note_post_to_obj($np){
		$note = new USIN_Note($np->ID);
		$note->date = USIN_Helper::format_date($np->post_date);
		$note_author = get_user_by( 'id', $np->post_author );
		$note->by = $note_author->user_nicename;
		$note->content = $np->post_content;
		
		return $note;
	}
}
