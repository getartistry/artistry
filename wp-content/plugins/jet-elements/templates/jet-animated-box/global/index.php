<?php
/**
 * Loop item template
 */
?>
<div class="jet-animated-box <?php $this->__html( 'animation_effect', '%s' ); ?>">
	<div class="jet-animated-box__front">
		<div class="jet-animated-box__overlay"></div>
		<div class="jet-animated-box__inner">
			<?php
				$this->__html( 'front_side_icon', '<div class="jet-animated-box__icon jet-animated-box__icon--front"><div class="jet-animated-box-icon-inner"><i class="%s"></i></div></div>' );
			?>
			<div class="jet-animated-box__content">
			<?php
				$this->__html( 'front_side_title', '<h3 class="jet-animated-box__title jet-animated-box__title--front">%s</h3>' );
				$this->__html( 'front_side_subtitle', '<h4 class="jet-animated-box__subtitle jet-animated-box__subtitle--front">%s</h4>' );
				$this->__html( 'front_side_description', '<p class="jet-animated-box__description jet-animated-box__description--front">%s</p>' );
			?>
			</div>
		</div>
	</div>
	<div class="jet-animated-box__back">
		<div class="jet-animated-box__overlay"></div>
		<div class="jet-animated-box__inner">
			<?php
				$this->__html( 'back_side_icon', '<div class="jet-animated-box__icon jet-animated-box__icon--back"><div class="jet-animated-box-icon-inner"><i class="%s"></i></div></div>' );
			?>
			<div class="jet-animated-box__content">
			<?php
				$this->__html( 'back_side_title', '<h3 class="jet-animated-box__title jet-animated-box__title--back">%s</h3>' );
				$this->__html( 'back_side_subtitle', '<h4 class="jet-animated-box__subtitle jet-animated-box__subtitle--back">%s</h4>' );
				$this->__html( 'back_side_description', '<p class="jet-animated-box__description jet-animated-box__description--back">%s</p>' );
				$this->__glob_inc_if( 'action-button', array( 'back_side_button_link', 'back_side_button_text' ) );
			?>
			</div>
		</div>
	</div>
</div>
