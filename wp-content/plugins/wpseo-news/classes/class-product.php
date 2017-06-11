<?php

if ( ! class_exists( 'WPSEO_News_Product', false ) && class_exists( 'Yoast_Product' ) ) {

	/**
	 * Class WPSEO_News_Product
	 */
	class WPSEO_News_Product extends Yoast_Product {

		public function __construct() {
			$file = plugin_basename( WPSEO_NEWS_FILE );
			$slug = dirname( $file );

			parent::__construct(
				'http://yoast.com/edd-sl-api',
				'News SEO',
				$slug,
				WPSEO_News::VERSION,
				'https://yoast.com/wordpress/plugins/news-seo/',
				'admin.php?page=wpseo_licenses#top#licenses',
				'wordpress-seo-news',
				'Yoast',
				$file
			);
		}
	}

}
