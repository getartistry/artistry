<?php

namespace AutomateWoo;

defined( 'ABSPATH' ) or exit;

/**
 * @class Mailer_Raw_HTML
 * @since 3.6.0
 */
class Mailer_Raw_HTML extends Mailer {

	/** @var string */
	public $raw_html;


	/**
	 * @param string $html
	 */
	function set_raw_html( $html ) {
		$this->raw_html = $html;
	}


	/**
	 * Inline styles already contained in the HTML
	 *
	 * @param string|null $content
	 * @return string
	 */
	function style_inline( $content ) {
		if ( ! class_exists( 'DOMDocument' ) ) {
			return $content;
		}

		$css = '';

		if ( $this->include_automatewoo_styles ) {
			ob_start();
			aw_get_template( 'email/styles.php' );
			$css = ob_get_clean();
		}

		$css = apply_filters( 'automatewoo/mailer_raw/styles', $css , $this );

		try {
			$emogrifier = new \AW_Emogrifier( $content, $css );
			$emogrifier->disableInvisibleNodeRemoval();
			$content = $emogrifier->emogrify();
		}
		catch ( \Exception $e ) {
			$logger = new \WC_Logger();
			$logger->add( 'emogrifier', $e->getMessage() );
		}

		return $content;
	}


	/**
	 * @return string
	 */
	function get_html() {
		$html = $this->raw_html;

		$html = $this->process_email_variables( $html );
		$html = $this->fix_links_with_double_http( $html );
		$html = $this->replace_urls_in_content( $html );
		$html = wptexturize( $html );
		$html = convert_smilies( $html );

		if ( $this->tracking_pixel_url ) {
			$html = $this->inject_tracking_pixel( $html );
		}

		return $this->style_inline( $html );
	}

}