<?php

namespace AutomateWoo;

if ( ! defined( 'ABSPATH' ) ) exit;

$utm_source = 'welcome-notice';

?>

<div class="notice automatewoo-welcome-notice">
	<h3 class="automatewoo-welcome-notice__heading"><?php _e( 'Welcome to AutomateWoo!', 'automatewoo' ) ?></h3>
	<div class="automatewoo-welcome-notice__text">
		<p><?php printf(
			__( "We're super excited you have decided to grow your store with AutomateWoo! If you haven't already, you should check out our <%s>Getting Started Guide<%s>, and for tutorials and tips, be sure to visit our <%s>blog<%s> and <%s>documentation<%s>. If you have any questions, don't hesitate to <%s>contact us<%s> for help.", 'automatewoo' ),
			'a href="'.Admin::get_docs_link('getting-started', $utm_source ).'" target="_blank"',
			'/a',
			'a href="'. Admin::get_website_link('blog', $utm_source ) .'" target="_blank"',
			'/a',
			'a href="'. Admin::get_docs_link('', $utm_source ) .'" target="_blank"',
			'/a',
			'a href="'. Admin::get_website_link('get-help', $utm_source ) .'" target="_blank"',
			'/a'
		) ?>
		</p>
	</div>
    <div class="automatewoo-welcome-notice__robot"></div>
</div>
