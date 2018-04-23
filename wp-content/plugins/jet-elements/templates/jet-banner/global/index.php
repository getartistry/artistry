<?php
/**
 * Loop item template
 */
?>
<figure class="jet-banner jet-effect-<?php $this->__html( 'animation_effect', '%s' ); ?>"><?php
	$this->__html( 'banner_link', '<a href="%s" class="jet-banner__link">' );
		echo '<div class="jet-banner__overlay"></div>';
		echo $this->__get_banner_image();
		echo '<figcaption class="jet-banner__content">';
			echo '<div class="jet-banner__content-wrap">';
				$this->__html( 'banner_title', '<h5 class="jet-banner__title">%s</h5>' );
				$this->__html( 'banner_text', '<div class="jet-banner__text">%s</div>' );
			echo '</div>';
		echo '</figcaption>';
	$this->__html( 'banner_link', '</a>' );
?></figure>
