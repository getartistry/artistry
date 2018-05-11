<?php
/* Prevent direct access */
defined( 'ABSPATH' ) or die( "You can't access this file directly." );

if ( ! class_exists( 'asp_indexTable' ) ) {
	/**
	 * Class operating the index table
	 *
	 * @class        asp_indexTable
	 * @version        1.0
	 * @package        AjaxSearchPro/Classes
	 * @category      Class
	 * @author        Ernest Marcinko
	 */
	class asp_indexTable {

		/**
		 * @var array of constructor arguments
		 */
		private $args;

		/**
		 * @var string the index table name without prefix here
		 */
		private $asp_index_table = 'asp_index';

		/**
		 * @var int keywords found and added to database this session
		 */
		private $keywords_found = 0;

        /**
         * @var array posts indexed through
         */
        private $posts_indexed_now = 0;

        /**
         * @var array of post IDs to ignore from selection
         */
        private $posts_to_ignore = array();

        /**
         * @var string unique random string for special replacements
         */
        private $randstr = "wpivdny3htnydqd6mlyg";

        /**
         * Static instance storage. This is not a singleton, but used in static method to access object only functions
         *
         * @var self
         */
        private static $_instance;

		// ------------------------------------------- PUBLIC METHODS --------------------------------------------------

		function __construct( $args = array() ) {

			$defaults = array(
				// Arguments here
				'index_title'         => 1,
				'index_content'       => 1,
				'index_excerpt'       => 1,
				'index_tags'          => 0,
				'index_categories'    => 0,
				'index_taxonomies'    => "",
                'attachment_mime_types' => "",
				'index_permalinks'	  => 0,
				'index_customfields'  => "",
				'index_author_name'   => "",
				'index_author_bio'    => "",
				'blog_id'             => get_current_blog_id(),
				'extend'              => 1,
				'limit'               => 25,
				'use_stopwords'       => 1,
				'stopwords'           => '',
				'min_word_length'     => 3,
				'post_types'          => "post|page",
				'post_statuses'       => 'publish',
				'extract_shortcodes'  => 1,
				'exclude_shortcodes'  => '',
				'extract_iframes'	  => 0
			);

			$this->args = wp_parse_args( $args, $defaults );
			$this->args = apply_filters( 'asp_it_args', $this->args, $defaults);

			// Swap here to have the asp_posts_indexed option for each blog different
			if ( is_multisite() && !empty($this->args['blog_id']) && $this->args['blog_id'] != get_current_blog_id() ) {
				switch_to_blog( $this->args['blog_id'] );
			}

			$this->asp_index_table = wd_asp()->tables->index;
            $this->posts_indexed_now = 0;
            $this->initIngoreList();
		}

		/**
		 * Generates the index table if it does not exist
		 */
		function createIndexTable() {
			global $wpdb;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$charset_collate = "";

			if ( ! empty( $wpdb->charset ) ) {
				$charset_collate_bin_column = "CHARACTER SET $wpdb->charset";
				$charset_collate            = "DEFAULT $charset_collate_bin_column";
			}
			if ( strpos( $wpdb->collate, "_" ) > 0 ) {
				$charset_collate .= " COLLATE $wpdb->collate";
			}

			$table_name = $this->asp_index_table;
			$query      = "
				CREATE TABLE IF NOT EXISTS " . $table_name . " (
					doc bigint(20) NOT NULL DEFAULT '0',
					term varchar(50) NOT NULL DEFAULT '0',
					term_reverse varchar(50) NOT NULL DEFAULT '0',
					blogid mediumint(9) NOT NULL DEFAULT '0',
					content mediumint(9) NOT NULL DEFAULT '0',
					title mediumint(9) NOT NULL DEFAULT '0',
					comment mediumint(9) NOT NULL DEFAULT '0',
					tag mediumint(9) NOT NULL DEFAULT '0',
					link mediumint(9) NOT NULL DEFAULT '0',
					author mediumint(9) NOT NULL DEFAULT '0',
					category mediumint(9) NOT NULL DEFAULT '0',
					excerpt mediumint(9) NOT NULL DEFAULT '0',
					taxonomy mediumint(9) NOT NULL DEFAULT '0',
					customfield mediumint(9) NOT NULL DEFAULT '0',
					post_type varchar(50) NOT NULL DEFAULT 'post',
					item bigint(20) NOT NULL DEFAULT '0',
					lang varchar(20) NOT NULL DEFAULT '0',
			    UNIQUE KEY doctermitem (doc, term, blogid)) $charset_collate";

			dbDelta( $query );
			$query            = "SHOW INDEX FROM $table_name";
			$indices          = $wpdb->get_results( $query );
			$existing_indices = array();

			foreach ( $indices as $index ) {
				if ( isset( $index->Key_name ) ) {
					$existing_indices[] = $index->Key_name;
				}
			}

			// Worst case scenario optimal indexes
			if ( ! in_array( 'term_ptype_bid_lang', $existing_indices ) ) {
				$sql = "CREATE INDEX term_ptype_bid_lang ON $table_name (term(20), post_type(20), blogid, lang(10))";
				$wpdb->query( $sql );
			}
			if ( ! in_array( 'rterm_ptype_bid_lang', $existing_indices ) ) {
				$sql = "CREATE INDEX rterm_ptype_bid_lang ON $table_name (term_reverse(20), post_type(20), blogid, lang(10))";
				$wpdb->query( $sql );
			}
		}

		/**
		 * Checks if the index table exists. Creates it if the argument is set to true.
		 *
		 * @param bool $create_if_not_exist
		 *
		 * @return bool
		 */
		function checkIndexTable( $create_if_not_exist = false ) {
			global $wpdb;

			$table_name = $this->asp_index_table;
			if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
				if ( $create_if_not_exist === true ) {
					$this->createIndexTable();

					return $this->checkIndexTable( false );
				} else {
					return false;
				}
			}

			return true;
		}

		/**
		 * Re-generates the index table
		 *
		 * @return array (posts to index, posts indexed)
		 */
		function newIndex() {
			$this->emptyIndex( false );
            $this->emptyIgnoreList();

			return $this->extendIndex();
		}


		/**
		 * Extends the index database
		 *
		 * @param bool $switching_blog - will clear the indexed posts array
		 *
		 * @return array (posts to index, posts indexed)
		 */
		function extendIndex( $switching_blog = false ) {

			// this respects the limit, no need to count again
			$posts = $this->getPostIdsToIndex();

			foreach ( $posts as $tpost ) {
				if ( $this->indexDocument( $tpost->ID, false ) ) {
                    $this->posts_indexed_now++;
                } else {
                    if ( !isset($this->posts_to_ignore[$this->args['blog_id']]) )
                        $this->posts_to_ignore[$this->args['blog_id']] = array();
                    $this->posts_to_ignore[$this->args['blog_id']][] = $tpost->ID;
                }

			}

			// THIS MUST BE HERE!!
			// ..the statment below restores the blog before getting the correct count!
			$return = array(
				'postsToIndex'    => $this->getPostIdsToIndexCount(),
				'postsIndexed'    => $this->getPostsIndexed(),
                'postsIndexedNow' => $this->getPostsIndexedNow(),
				'keywordsFound'   => $this->keywords_found,
                'totalKeywords'   => $this->getTotalKeywords()
			);

			if ( is_multisite() ) {
				restore_current_blog();
			}

            if ( count($this->posts_to_ignore) > 0)
                $this->updateIgnoreList();

			return $return;
		}

        function emptyIgnoreList() {
            delete_option("_asp_index_ignore");
            $this->posts_to_ignore = array();
        }

        function updateIgnoreList() {
            update_option("_asp_index_ignore", $this->posts_to_ignore);
        }

        function initIngoreList() {
            $this->posts_to_ignore = get_option("_asp_index_ignore", array());
        }

		/**
		 * Indexes a document based on its ID
		 *
		 * @param int $post_id the post id
		 * @param bool $remove_first
		 *
		 * @return bool
		 */
		function indexDocument( $post_id, $remove_first = true, $post_editor_context = false ) {
			$args = $this->args;

			// array of all needed tokens
			$tokens = array();

			// On creating or extending the index, no need to remove
			if ( $remove_first ) {
				$this->removeDocument( $post_id );
			}

            /**
             * This prevents the fancy quotes and special characters to HTML output
             * NOTE: it has to be executed here before every get_post() call!!
             */
            remove_filter('the_title', 'wptexturize');
            remove_filter('the_title', 'convert_chars');

			$the_post = get_post( $post_id );
			if ( $the_post == null ) {
				return false;
			}

			// This needs to be here, after the get_post()
			if ( $post_editor_context === true ) {
				if ( $args['post_types'] != '' ) {
					$types = explode( '|', $args['post_types'] );
					if (!in_array($the_post->post_type, $types))
						return false;
				} else {
					return false;
				}
			}

            /**
             * For product variations set the title, content and excerpt to the original product
             */
            if ( $the_post->post_type == "product_variation" ) {
                $parent_post = get_post($the_post->post_parent);
                if ( !empty($parent_post) ) {
                    $the_post->post_title .= " " . $parent_post->post_title;
                    $the_post->post_content = $parent_post->post_content;
                    $the_post->post_excerpt = $parent_post->post_excerpt;
                }
            }

			if ( $args['index_content'] == 1 ) {
				$this->tokenizeContent( $the_post, $tokens );
			}

			if ( $args['index_title'] == 1 ) {
				$this->tokenizeTitle( $the_post, $tokens );
			}

			if ( $args['index_excerpt'] == 1 ) {
				$this->tokenizeExcerpt( $the_post, $tokens );
			}

			if ( $args['index_categories'] == 1 || $args['index_tags'] == 1 || $args['index_taxonomies'] != "" ) {
				$this->tokenizeTerms( $the_post, $tokens );
			}

			if ( $args['index_author_name'] == 1 || $args['index_author_bio'] == 1 ) {
				$this->tokenizeAuthor( $the_post, $tokens );
			}

			if ( $args['index_permalinks'] == 1 ) {
				$this->tokenizePermalinks( $the_post, $tokens );
			}

			$this->tokenizeCustomFields( $the_post, $tokens );

			if ( count( $tokens ) > 0 ) {
				return $this->insertTokensToDB( $the_post, $tokens );
			}

			/*
			 DO NOT call finishOperation() here, it would switch back the blog too early.
			 Calling this function from an action hooks does not require switching the blog,
			 as the correct one is in use there.
			*/

			return false;
		}

		/**
		 * Removes a document from the index (in case of deleting posts, etc..)
		 *
		 * @param int|array $post_id the post id
		 */
		function removeDocument( $post_id ) {
			global $wpdb;
			$asp_index_table = $this->asp_index_table;

            if ( is_array($post_id) ) {
                foreach ( $post_id as $k=>&$v )
                    $v = $v + 0;
                $post_ids = implode(', ', $post_id);
                $wpdb->query( "DELETE FROM $asp_index_table WHERE doc IN ($post_ids)"  );
            } else {
                $wpdb->query( $wpdb->prepare(
                    "DELETE FROM $asp_index_table WHERE doc = %d", $post_id
                ) );
            }

			/*
			 DO NOT call finishOperation() here, it would switch back the blog too early.
			 Calling this function from an action hooks does not require switching the blog,
			 as the correct one is in use there.
			*/
		}


        /**
         * Empties the index table
         *
         * @param bool $restore_current_blog if set to false, it wont restore multiste blog - for internal usage mainly
         * @return array
         */
		function emptyIndex( $restore_current_blog = true ) {
			global $wpdb;
			$asp_index_table = $this->asp_index_table;
			$wpdb->query( "TRUNCATE TABLE $asp_index_table" );

			if ( is_multisite() ) {
				$current = get_current_blog_id();
				$blogs   = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
				if ( $blogs ) {
					foreach ( $blogs as $blog ) {
						switch_to_blog( $blog['blog_id'] );
					}
					// Switch back to the current, like nothing happened
					switch_to_blog( $current );
				}
			}

			if ( $restore_current_blog && is_multisite() ) {
				restore_current_blog();
			}

            $return = array(
                'postsToIndex'    => $this->getPostIdsToIndexCount(),
                'totalKeywords'   => $this->getTotalKeywords()
            );

            return $return;
		}

        /**
         * Suggests pool sizes for the index table search process
         *
         * @param bool $refresh
         *
         * @return string
         */
		public static function suggestPoolSizes( $refresh = false ) {
            $default = array(
                'one'   => 500,
                'two'   => 800,
                'three' => 2000,
                'rest'  => 2000
            );
            if ( $refresh ) {
                if ( ! ( self::$_instance instanceof self ) ) {
                    self::$_instance = new self();
                }
                $size = self::$_instance->getTotalKeywords();
                if ( $size > 100000 ) {
                    $pool = $default;
                } else if ( $size > 50000 ) {
                    $pool = array(
                        'one'   => 800,
                        'two'   => 1200,
                        'three' => 2500,
                        'rest'  => 2500
                    );
                } else if ( $size > 10000 ) {
                    $pool = array(
                        'one'   => 1500,
                        'two'   => 2000,
                        'three' => 2500,
                        'rest'  => 2500
                    );
                } else {
                    $pool = array(
                        'one'   => 2500,
                        'two'   => 2500,
                        'three' => 3000,
                        'rest'  => 3000
                    );
                }
                update_option('_asp_it_pool_sizes', $pool);
                return $pool;
            } else {
                return get_option('_asp_it_pool_sizes', $default);
            }
        }

		/**
		 * An empty function to override individual shortcodes. This must be a public method.
		 *
		 * @return string
		 */
		function return_empty_string() {
			return "";
		}


		// ------------------------------------------- PRIVATE METHODS -------------------------------------------------

		/**
		 * Generates the content tokens and puts them into the tokens array
		 *
		 * @param object $the_post the post object
		 * @param array $tokens tokens array
		 *
		 * @return int keywords count
		 */
		private function tokenizeContent( $the_post, &$tokens ) {
			$args = $this->args;

			$content = apply_filters( 'asp_post_content_before_tokenize_clear', $the_post->post_content, $the_post );

			if ( $args['extract_shortcodes'] ) {
				$content = $this->executeShortcodes( $content );
			}

			if ( $args['extract_iframes'] == 1 )
				$content .= ' ' . $this->extractIframeContent($content);

			// Strip the remaining shortcodes
			$content = strip_shortcodes( $content );

			$content = preg_replace( '/<[a-zA-Z\/][^>]*>/', ' ', $content );
			$content = strip_tags( $content );

			$filtered_content = apply_filters( 'asp_post_content_before_tokenize', $content, $the_post );

			if ( $filtered_content == "" ) {
				return 0;
			}

			$content_keywords = $this->tokenize( $filtered_content );

			foreach ( $content_keywords as $keyword ) {
				$this->insertToken( $tokens, $keyword[0], $keyword[1], 'content' );
			}

			return count( $content_keywords );
		}

		/**
		 * Generates the excerpt tokens and puts them into the tokens array
		 *
		 * @param object $the_post the post object
		 * @param array $tokens tokens array
		 *
		 * @return int keywords count
		 */
		private function tokenizeExcerpt( $the_post, &$tokens ) {
            $args = $this->args;

			if ( $the_post->post_excerpt == "" ) {
				return 0;
			}

			$filtered_excerpt = apply_filters( 'asp_post_excerpt_before_tokenize', $the_post->post_excerpt, $the_post );

            if ( $args['extract_shortcodes'] ) {
                $filtered_excerpt = $this->executeShortcodes( $filtered_excerpt );
            }

			$excerpt_keywords = $this->tokenize( $filtered_excerpt );

			foreach ( $excerpt_keywords as $keyword ) {
				$this->insertToken( $tokens, $keyword[0], $keyword[1], 'excerpt' );
			}

			return count( $excerpt_keywords );
		}

		/**
		 * Generates the title tokens and puts them into the tokens array
		 *
		 * @param object $the_post the post object
		 * @param array $tokens tokens array
		 *
		 * @return int keywords count
		 */
		private function tokenizeTitle( $the_post, &$tokens ) {
			$filtered_title = apply_filters( 'asp_post_title_before_tokenize', $the_post->post_title, $the_post );

			$title          = apply_filters( 'the_title', $filtered_title, $the_post->ID );
			$title_keywords = $this->tokenize( $title );

            // No-reverse exact title
            $this->insertToken( $tokens, $title, 1, 'title', true );

			foreach ( $title_keywords as $keyword ) {
				$this->insertToken( $tokens, $keyword[0], $keyword[1], 'title' );
			}

			return count( $title_keywords );
		}

		/**
		 * Generates the permalink tokens and puts them into the tokens array
		 *
		 * @param object $the_post the post object
		 * @param array $tokens tokens array
		 *
		 * @return int keywords count
		 */
		private function tokenizePermalinks( $the_post, &$tokens ) {
			$filtered_permalink = apply_filters( 'asp_post_permalink_before_tokenize', $the_post->post_name, $the_post );
			// Store the permalink as is, with an occurence of 1
			$this->insertToken( $tokens, $filtered_permalink, 1, 'link' );

			return 1;
		}

		/**
		 * Generates the author display name and biography tokens and puts them into the tokens array
		 *
		 * @param object $the_post the post object
		 * @param array $tokens tokens array
		 *
		 * @return int keywords count
		 */
		private function tokenizeAuthor( $the_post, &$tokens ) {
			global $wpdb;
			$args = $this->args;
			$bio  = "";

			$display_name = $wpdb->get_var(
				$wpdb->prepare( "SELECT display_name FROM $wpdb->users WHERE ID=%d", $the_post->post_author )
			);
			if ( $args['index_author_bio'] ) {
				$bio = get_user_meta( $the_post->post_author, 'description', true );
			}

			$author_keywords = $this->tokenize( $display_name . " " . $bio );
			foreach ( $author_keywords as $keyword ) {
				$this->insertToken( $tokens, $keyword[0], $keyword[1], 'author' );
			}

			return count( $author_keywords );
		}

		/**
		 * Generates taxonomy term tokens and puts them into the tokens array
		 *
		 * @param object $the_post the post object
		 * @param array $tokens tokens array
		 *
		 * @return int keywords count
		 */
		private function tokenizeTerms( $the_post, &$tokens ) {
			$args       = $this->args;
			$taxonomies = array();
			$all_terms  = array();

			if ( $args['index_tags'] ) {
				$taxonomies[] = 'post_tag';
			}
			if ( $args['index_categories'] ) {
				$taxonomies[] = 'category';
			}
			$custom_taxonomies = explode( '|', $args['index_taxonomies'] );

			$taxonomies = array_merge( $taxonomies, $custom_taxonomies );

			foreach ( $taxonomies as $taxonomy ) {
				$terms = wp_get_post_terms( $the_post->ID, trim( $taxonomy ), array( "fields" => "names" ) );
				if ( is_array( $terms ) ) {
					$all_terms = array_merge( $all_terms, $terms );
				}
			}

			if ( count( $all_terms ) > 0 ) {
				$terms_string  = implode( ' ', $all_terms );
				$term_keywords = $this->tokenize( $terms_string );

				// everything goes under the tags, thus the tokinezer is called only once
				foreach ( $term_keywords as $keyword ) {
					$this->insertToken( $tokens, $keyword[0], $keyword[1], 'tag' );
				}

				return count( $term_keywords );
			}

			return 0;
		}

		/**
		 * Generates selected custom field tokens and puts them into the tokens array
		 *
		 * @param object $the_post the post object
		 * @param array $tokens tokens array
		 *
		 * @return int keywords count
		 */
		private function tokenizeCustomFields( $the_post, &$tokens ) {
			$args = $this->args;

			// all of the CF content to this variable
			$cf_content = "";

			if ( $args['index_customfields'] != "" )
				$custom_fields = explode( '|', $args['index_customfields'] );
			else
				$custom_fields = array();

			if ( !in_array('_asp_additional_tags', $custom_fields) )
				$custom_fields[] = '_asp_additional_tags';

			foreach ( $custom_fields as $field ) {
				// get CF values as array
				$values = get_post_meta( $the_post->ID, $field, false );
				foreach ( $values as $value ) {
					if ( is_array( $value ) ) {
						$value = $this->arrayToString( $value );
					}
					$cf_content .= " " . $value;
				}
			}

			if ( $cf_content != "" ) {
				$cf_keywords = $this->tokenize( $cf_content );
				foreach ( $cf_keywords as $keyword ) {
					$this->insertToken( $tokens, $keyword[0], $keyword[1], 'customfield' );
				}

				return count( $cf_keywords );
			}

			return 0;
		}

		/**
		 * Extracts content from an IFRAME source
		 *
		 * @param $str
		 * @return string
		 * @uses ASP_Helpers::stripTagsWithContent
         */
		private function extractIframeContent($str ) {
			preg_match_all('/\<iframe.+?src=[\'"]([^"\']+)["\']/', $str, $match);
			if ( isset($match[1]) ) {
				$ret = '';
				foreach($match[1] as $link) {
					$s = wp_remote_get($link);
					if ( !is_wp_error($s) ) {
						$xs = explode('<body', $s['body']);
						$final = $s['body'];
						if ( isset($xs[1]) ) {
							$final = '<html><body ' . $xs[1];
						}
						$ret .= ' ' . ASP_Helpers::stripTagsWithContent($final, array('head','script', 'style', 'img', 'input'));
					}
				}
				return $ret;
			}
			return '';
		}


		/**
		 * Puts the keyword token into the tokens array.
		 *
		 * @param array $tokens array to the tokens
		 * @param string $keyword keyword
		 * @param int $count keyword occurrence count
		 * @param string $field the field
         * @param bool $no_reverse if the reverse keyword should be stored
		 */
		private function insertToken( &$tokens, $keyword, $count = 1, $field = 'content', $no_reverse = false ) {
			// Cant use numeric keys, it would break things..
			// We need to trim it at inserting
			if ( is_numeric( $keyword ) ) {
				$keyword = " " . $keyword;
			}

			if ( isset( $tokens[ $keyword ] ) ) {
				// No need to check if $field key exists, it must exist due to the else statement
				$tokens[ $keyword ][ $field ] += $count;
			} else {
				$tokens[ $keyword ] = array(
					"content"     => 0,
					"title"       => 0,
					"comment"     => 0,
					"tag"         => 0,
					"link"        => 0,
					"author"      => 0,
					"category"    => 0,
					"excerpt"     => 0,
					"customfield" => 0,
					"taxonomy"    => 0,
					'_keyword'    => $keyword,
                    '_no_reverse' => $no_reverse
				);
				$tokens[ $keyword ][ $field ] += $count;
			}
		}


		/**
		 * Generates the query based on the post and the token array and inserts into DB
		 *
		 * @param object $the_post the post
		 * @param array $tokens tokens array
		 *
		 * @return bool
		 */
		private function insertTokensToDB( $the_post, $tokens ) {
			global $wpdb;
			$asp_index_table = $this->asp_index_table;
			$args            = $this->args;
			$values          = array();

			if ( count( $tokens ) <= 0 ) {
				return false;
			}

            $lang = "";

			// Is WPML used?
            if ( class_exists('SitePress') )
                $lang = $this->wpml_langcode_post_id( $the_post );

			// Is Polylang used?
			if ( function_exists('pll_get_post_language') && $lang == "" )
				$lang = pll_get_post_language($the_post->ID, 'slug');

			foreach ( $tokens as $term => $d ) {
				// If it's numeric, delete the leading space
				$term = trim( $term );

                if ( isset($d['_no_reverse']) && $d['_no_reverse'] === true ) {
                    $value    = $wpdb->prepare(
                        "(%d, %s, %s, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %s, %d, %s)",
                        $the_post->ID, $term, '', $args['blog_id'], $d['content'], $d['title'], $d['comment'], $d['tag'],
                        $d['link'], $d['author'], $d['category'], $d['excerpt'], $d['taxonomy'], $d['customfield'],
                        $the_post->post_type, 0, $lang
                    );
                } else {
                    $value    = $wpdb->prepare(
                        "(%d, %s, REVERSE(%s), %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %d, %s, %d, %s)",
                        $the_post->ID, $term, $term, $args['blog_id'], $d['content'], $d['title'], $d['comment'], $d['tag'],
                        $d['link'], $d['author'], $d['category'], $d['excerpt'], $d['taxonomy'], $d['customfield'],
                        $the_post->post_type, 0, $lang
                    );
                }

				$values[] = $value;
			}

			if ( count( $values ) > 0 ) {
				$values = implode( ', ', $values );
				$query  = "INSERT IGNORE INTO $asp_index_table
				(`doc`, `term`, `term_reverse`, `blogid`, `content`, `title`, `comment`, `tag`, `link`, `author`,
				 `category`, `excerpt`, `taxonomy`, `customfield`, `post_type`, `item`, `lang`)
				VALUES $values";
				$wpdb->query( $query );

				$this->keywords_found += count( $tokens );
			}

			return true;
		}

		/**
		 * Performs a keyword extraction on the given content string.
		 *
		 * @param string $str content to tokenize
		 *
		 * @return array of keywords $keyword = array( 'keyword', {count} )
		 */
		private function tokenize( $str ) {

			if ( is_array( $str ) ) {
				$str = $this->arrayToString( $str );
			}
            if ( function_exists("mb_strlen") )
                $fn_strlen = "mb_strlen";
            else
                $fn_strlen = "strlen";

			$args      = $this->args;
			$stopWords = array();

			if ( function_exists( 'mb_internal_encoding' ) ) {
				mb_internal_encoding( "UTF-8" );
			}

			$str = apply_filters( 'asp_indexing_string_pre_process', $str );

			$str = $this->html2txt( $str );

			$str = strip_tags( $str );
			$str = stripslashes( $str );

            // Replace non-word boundary dots with a unique string + 'd'
            $str = preg_replace("/([0-9])[\.]([0-9])/", "$1".$this->randstr."d$2", $str);

			// Remove potentially dangerous characters
			$str = str_replace( array(
				"Â·",
				"â€¦",
				"â‚¬",
				"&shy;"
			), "", $str );
			$str = str_replace( array(
				". ", // dot followed by space as boundary, otherwise it might be a part of the word
				", ", // comma followed by space only, otherwise it might be a word part
				"\\",
				//"{",
				"^",
				//"}",
				"?",
				"!",
				";",
				"+",
				"Ă‹â€ˇ",
				"Ă‚Â°",
				"~",
				"Ă‹â€ş",
				"Ă‹ĹĄ",
				"Ă‚Â¸",
				"Ă‚Â§",
				//"%",
				//"=",
				"Ă‚Â¨",
				"`",
				"â€™",
				"â€",
				"â€ť",
				"â€ś",
				"â€ž",
				"Â´",
				"â€”",
				"â€“",
				"Ă—",
				'&#8217;',
				"&nbsp;",
				chr( 194 ) . chr( 160 )
			), " ", $str );
			$str = str_replace( 'Ăź', 'ss', $str );

			//$str = preg_replace( '/[[:punct:]]+/u', ' ', $str );
			$str = preg_replace( '/[[:space:]]+/', ' ', $str );

			$str = str_replace( array( "\n", "\r", "  " ), " ", $str );

			// Most objects except unicode characters
			$str = preg_replace( '/[\x00-\x08\x0B\x0C\x0E-\x1F\x80-\x9F]/u', '', $str );

			// Line feeds, carriage returns, tabs
			$str = preg_replace( '/[\x00-\x1F\x80-\x9F]/u', '', $str );

			if ( function_exists( 'mb_strtolower' ) ) {
				$str = mb_strtolower( $str );
			} else {
				$str = strtolower( $str );
			}

			//$str = preg_replace('/[^\p{L}0-9 ]/', ' ', $str);
			$str = str_replace( "\xEF\xBB\xBF", '', $str );

			$str = trim( preg_replace( '/\s+/', ' ', $str ) );

            // Set back the non-word boundary dots
            $str = str_replace( $this->randstr."d", '.', $str );

			$str = apply_filters( 'asp_indexing_string_post_process', $str );

			$words = explode( ' ', $str );

            // Get additional words if available
			$additional_words = array();
			foreach ($words as $wk => $ww) {
                // ex.: 123-45-678 to 123, 45, 678
				$ww1 = str_replace(array('.', ',', '-', '_', "=", "%", '(', ')', '{', '}'), ' ', $ww);
				$wa = explode(" ", $ww1);
				if (count($wa) > 1) {
				    foreach ( $wa as $wak => $wav ) {
                        $wav = trim(preg_replace( '/[[:space:]]+/', ' ', $wav ));
                        if ( $wav != '' ) {
                            $wa[$wak] = $wav;
                        } else {
                            unset($wa[$wak]);
                        }
                    }
                    $additional_words = array_merge($additional_words, $wa);
                }
                // ex.: 123-45-678 to 12345678
                $ww2 = str_replace(array('.', ',', '-', '_', "=", "%", '(', ')', '{', '}'), '', $ww);
                if ( $ww2 != '' && $ww2 != $ww ) {
                    $additional_words[] = $ww2;
                }
			}

			// Append them after the words array
			$words = array_merge($words, $additional_words);

			// Only compare to common words if $restrict is set to false
			if ( $args['use_stopwords'] == 1 && $args['stopwords'] != "" ) {
				$args['stopwords'] = str_replace(" ", "", $args['stopwords']);
				$stopWords = explode( ',', $args['stopwords'] );
			}

			$keywords = array();

			while ( ( $c_word = array_shift( $words ) ) !== null ) {
                $c_word = trim($c_word);

				if ( $c_word == '' || $fn_strlen( $c_word ) < $args['min_word_length'] ) {
					continue;
				}
				if ( in_array( $c_word, $stopWords ) ) {
					continue;
				}
				// Numerics wont work otherwise, need to trim that later
				if ( is_numeric( $c_word ) ) {
					$c_word = " " . $c_word;
				}

				if ( array_key_exists( $c_word, $keywords ) ) {
					$keywords[ $c_word ][1] ++;
				} else {
					$keywords[ $c_word ] = array( $c_word, 1 );
				}
			}

			$keywords = apply_filters( 'asp_indexing_keywords', $keywords );

			return $keywords;
		}

		/**
		 * Converts a multi-depth array elements into one string, elements separated by space.
		 *
		 * @param $arr
		 * @param int $level
		 *
		 * @return string
		 */
		private function arrayToString( $arr, $level = 0 ) {
			$str = "";
			if ( is_array( $arr ) ) {
				foreach ( $arr as $sub_arr ) {
					$str .= $this->arrayToString( $sub_arr, $level + 1 );
				}
			} else {
				$str = " " . $arr;
			}
			if ( $level == 0 ) {
				$str = trim( $str );
			}

			return $str;
		}

        /**
         * Executes the shortcodes within the given string
         *
         * @param string $content
         * @return string
         */
        private function executeShortcodes($content) {
            $args = $this->args;

            $content = apply_filters( 'asp_it_before_shortcode_removal', $content );

            // WP Table Reloaded support
            if ( defined( 'WP_TABLE_RELOADED_ABSPATH' ) ) {
                include_once( WP_TABLE_RELOADED_ABSPATH . 'controllers/controller-frontend.php' );
                $wpt_reloaded = new WP_Table_Reloaded_Controller_Frontend();
            }
            // TablePress support
            if ( defined( 'TABLEPRESS_ABSPATH' ) ) {
                $tp_controller = TablePress::load_controller( 'frontend' );
                $tp_controller->init_shortcodes();
            }

            // Remove user defined shortcodes
            $shortcodes = explode( ',', $args['exclude_shortcodes'] );
            $try_getting_sc_content = apply_filters('asp_it_try_getting_sc_content', true);
            foreach ( $shortcodes as $shortcode ) {
                // First let us try to get any contents from the shortcode itself
                if ( $try_getting_sc_content ) {
                    $content = preg_replace(
                        '/(?:\[' . $shortcode . '[ ]+.*?\]|\[' . $shortcode . '[ ]*\])(.*?)\[\/' . $shortcode . '[ ]*]/su',
                        ' $1 ',
                        $content
                    );
                }
                // Then remove the shortcode completely
                remove_shortcode( trim( $shortcode ) );
                add_shortcode( trim( $shortcode ), array( $this, 'return_empty_string' ) );
            }

            $more_shortcodes = array(
                'vc_asp_search',
                'wd_asp',
                'wpdreams_ajaxsearchpro',
                'wpdreams_ajaxsearchpro_results',
                'wpdreams_asp_settings',
                'contact-form',
                'starrater',
                'responsive-flipbook',
                'avatar_upload',
                'product_categories',
                'recent_products',
                'templatera',
                'cws-widget', 'cws-row', 'cws-column', 'col', 'item', 'bsf-info-box', 'logo-slider',
                'ourteam', 'embedyt', 'gallery', 'bsf-info-box', 'tweet', 'blog', 'portfolio'
            );
            foreach ( $more_shortcodes as $shortcode ) {
                remove_shortcode( $shortcode );
                add_shortcode( $shortcode, array( $this, 'return_empty_string' ) );
            }

            $content = do_shortcode( $content );

            // WP 4.2 emoji strip
            if ( function_exists( 'wp_encode_emoji' ) ) {
                $content = wp_encode_emoji( $content );
            }

            if ( defined( 'TABLEPRESS_ABSPATH' ) ) {
                unset( $tp_controller );
            }

            if ( defined( 'WP_TABLE_RELOADED_ABSPATH' ) ) {
                unset( $wpt_reloaded );
            }

            return apply_filters( 'asp_it_after_shortcode_removal', $content );
        }

		/**
		 * A better powerful strip tags - removes scripts, styles completely
		 *
		 * @param $document
		 *
		 * @return string stripped document
		 */
		private function html2txt( $document ) {
			$search = array(
				'@<script[^>]*?>.*?</script>@si', // Strip out javascript
				'@<[\/\!]*?[^<>]*?>@si', // Strip out HTML tags
				'@<style[^>]*?>.*?</style>@siU', // Strip style tags properly
				'@<![\s\S]*?--[ \t\n\r]*>@' // Strip multi-line comments including CDATA
			);
			$text   = preg_replace( $search, '', $document );

			return $text;
		}

        /**
         * A working hack to get the post language by post object WPML
         *
         * @param Post $post object
         *
         * @return string language string
         */
        private function wpml_langcode_post_id($post){
            global $wpdb;

            $post_type = "post_" . $post->post_type;

            $query = $wpdb->prepare("
				SELECT language_code
				FROM " . $wpdb->prefix . "icl_translations
				WHERE
				element_type = '%s' AND
				element_id = %d"
                , $post_type, $post->ID);
            $query_exec = $wpdb->get_row($query);

            if ( null !== $query_exec )
                return $query_exec->language_code;

            return "";
        }

		/**
		 * Gets the post IDs to index
		 *
		 * @return array of post IDs
		 */
		private function getPostIdsToIndex() {
			global $wpdb;
			$asp_index_table = $this->asp_index_table;
			$args            = $this->args;

            $_statuses = explode(",", $args['post_statuses']);
            foreach ($_statuses as $sk => &$sv)
                $sv = trim($sv);
            $valid_status    = "'" . implode("', '", $_statuses ) . "'";

			if ( $args['post_types'] != '' ) {
                $post_types = explode( '|', $args['post_types'] );
                if ( class_exists('WooCommerce') && in_array('product_variation', $post_types) ) { // Special case for Woo variations
                    $post_types = array_diff($post_types, array('product_variation'));
                    $rest = '';
                    if (count($post_types) > 0)
                        $rest = " OR post.post_type IN('".implode("', '", $post_types)."') ";
                    // In case of product variation the parent post status must also match, otherwise it is not relevant
                    $restriction = " AND ( (post.post_type = 'product_variation' AND parent.post_status IN($valid_status) ) $rest )";
                } else {
                    $restriction = " AND post.post_type IN ('" . str_replace( "|", "', '", $args['post_types'] ) . "')";
                }
			} else {
                return array();
            }

            $ignore_posts = "";
            if ( !empty($this->posts_to_ignore[$this->args['blog_id']]) )
                $ignore_posts = " AND post.ID NOT IN( ".implode(',', $this->posts_to_ignore[$this->args['blog_id']])." )";

            $mimes_restrict = '';
            if ( $args['attachment_mime_types'] != '' ) {
                $mimes_arr = explode(',', $args['attachment_mime_types']);
                foreach($mimes_arr as $mk => $mv) {
                    $mimes_arr[$mk] = trim($mv);
                    if ( empty($mimes_arr[$mk]) )
                        unset($mimes_arr[$mk]);
                }
                if ( count($mimes_arr) > 0 )
                    $mimes_restrict = "AND ( post.post_mime_type = '' OR post.post_mime_type IN ('" . implode("','", $mimes_arr) . "') )";
            }

			$limit        = $args['limit'] > 500 ? 500 : ( $args['limit'] + 0 );

			if ( $args['extend'] == 1 ) {
				// We are extending, so keep the existing
				$q = "SELECT post.ID
						FROM $wpdb->posts post
						LEFT JOIN $wpdb->posts parent ON (post.post_parent = parent.ID)
						LEFT JOIN $asp_index_table r ON (post.ID = r.doc AND r.blogid = " . $args['blog_id'] . ")
						WHERE
								r.doc is null
						AND
							(post.post_status IN ($valid_status)
							OR
							(post.post_status='inherit'
								AND(
									(parent.ID is not null AND (parent.post_status IN ($valid_status)))
									OR (post.post_parent=0)
								)
							)
						)
						$restriction
						$mimes_restrict
						$ignore_posts
						ORDER BY post.ID ASC
						LIMIT $limit";
			} else {
				$q = "SELECT post.ID
						FROM $wpdb->posts post
						LEFT JOIN $wpdb->posts parent ON (post.post_parent=parent.ID)
						WHERE
							(post.post_status IN ($valid_status)
							OR
							(post.post_status='inherit'
								AND(
									(parent.ID is not null AND (parent.post_status IN ($valid_status)))
									OR (post.post_parent=0)
								)
							))
						$restriction
						$mimes_restrict
						$ignore_posts
						ORDER BY post.ID ASC
						LIMIT $limit";

			}

			$res = $wpdb->get_results( $q );

			return $res;
		}

		/**
		 * Gets the number documents to index
		 *
		 * @return int number of documents to index yet
		 */
		public function getPostIdsToIndexCount() {
			global $wpdb;
			$args = $this->args;

			$asp_index_table = $this->asp_index_table;

			$_statuses = explode(",", $args['post_statuses']);
			foreach ($_statuses as $sk => &$sv)
				$sv = trim($sv);
			$valid_status    = "'" . implode("', '", $_statuses ) . "'";

            if ( $args['post_types'] != '' ) {
                $post_types = explode( '|', $args['post_types'] );
                if ( class_exists('WooCommerce') && in_array('product_variation', $post_types) ) { // Special case for Woo variations
                    $post_types = array_diff($post_types, array('product_variation'));
                    $rest = '';
                    if (count($post_types) > 0) // ..are there any left?
                        $rest = " OR post.post_type IN('".implode("', '", $post_types)."') ";
                    // In case of product variation the parent post status must also match, otherwise it is not relevant
                    $restriction = " AND ( (post.post_type = 'product_variation' AND parent.post_status IN($valid_status) ) $rest )";
                } else {
                    $restriction = " AND post.post_type IN ('" . str_replace( "|", "', '", $args['post_types'] ) . "')";
                }
            } else {
                return 0;
            }

            $ignore_posts = "";
            if ( !empty($this->posts_to_ignore[$this->args['blog_id']]) )
                $ignore_posts = " AND post.ID NOT IN( ".implode(',', $this->posts_to_ignore[$this->args['blog_id']])." )";

			$q = "SELECT COUNT(DISTINCT post.ID)
						FROM $wpdb->posts post
						LEFT JOIN $wpdb->posts parent ON (post.post_parent = parent.ID)
						LEFT JOIN $asp_index_table r ON (post.ID = r.doc AND r.blogid = " . $args['blog_id'] . ")
						WHERE
								r.doc is null
						AND
							(post.post_status IN ($valid_status)
							OR
							(post.post_status='inherit'
								AND(
									(parent.ID is not null AND (parent.post_status IN ($valid_status)))
									OR (post.post_parent=0)
								)
							)
						)
						$restriction
						$ignore_posts";
			return $wpdb->get_var( $q );
		}

        /**
         * Gets the number of so far indexed documents
         *
         * @return int number of indexed documents
         */
        public function getPostsIndexed() {
            global $wpdb;

            $sql = "SELECT COUNT(DISTINCT doc) FROM " . $this->asp_index_table;

            return $wpdb->get_var($sql);
        }

        /**
         * Gets the number of items in the index table, multisite supported
         *
         * @return int number of rows
         */
        public function getTotalKeywords() {
            global $wpdb;

            if ( is_multisite() )
                $sql = "SELECT COUNT(doc) FROM " . $this->asp_index_table;
            else
                $sql = "SELECT COUNT(doc) FROM " . $this->asp_index_table . " WHERE blogid = " . get_current_blog_id();

            return $wpdb->get_var($sql);
        }


        /**
         * Gets the number of indexed documents on this run instance
         *
         * @return int number of indexed documents
         */
        private function getPostsIndexedNow() {
            return $this->posts_indexed_now;
        }


    }
}