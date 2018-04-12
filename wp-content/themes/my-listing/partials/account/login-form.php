<?php if (!class_exists('WooCommerce')) return; ?>

<div class="sign-in-box element">
	<div class="title-style-1">
		<i class="material-icons">perm_identity</i>
		<h5><?php _e( 'Sign in', 'my-listing' ) ?></h5>
	</div>
	<form class="sign-in-form woocomerce-form woocommerce-form-login login" method="POST">

		<?php do_action( 'woocommerce_login_form_start' ); ?>

		<div class="form-group">
			<input type="text" name="username" id="username" value="<?php if ( ! empty( $_POST['username'] ) ) echo esc_attr( $_POST['username'] ); ?>" placeholder="<?php esc_attr_e( 'Username', 'my-listing' ) ?>">
		</div>

		<div class="form-group">
			<input type="password" name="password" id="password" placeholder="<?php esc_attr_e( 'Password', 'my-listing' ) ?>">
		</div>

		<?php do_action( 'woocommerce_login_form' ); ?>

		<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>

		<div class="form-group">
			<button type="submit" class="buttons button-2 full-width button-animated" name="login" value="Login">
				<?php _e( 'Sign in', 'my-listing' ) ?> <i class="material-icons">keyboard_arrow_right</i>
			</button>
		</div>

		<div class="form-info">
			<div class="md-checkbox">
				<input type="checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever">
				<label for="rememberme" class=""><?php _e( 'Remember me', 'my-listing' ) ?></label>
			</div>
			<div class="forgot-password">
				<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php _e( 'Forgot password?', 'my-listing' ); ?></a>
			</div>
		</div>

		<?php if ( ! empty( $_GET['redirect_to'] ) ): ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( $_GET['redirect_to'] ) ?>">
		<?php else: ?>
			<input type="hidden" name="redirect" value="<?php echo esc_url( wc_get_page_permalink('myaccount') ) ?>">
		<?php endif ?>

		<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
			<a href="#" class="buttons button-5 full-width c27-open-modal" data-target="#sign-up-modal"><?php _e( 'Don\'t have an account?', 'my-listing' ) ?></a>
		<?php endif ?>

		<?php do_action( 'woocommerce_login_form_end' ); ?>

	</form>
</div>