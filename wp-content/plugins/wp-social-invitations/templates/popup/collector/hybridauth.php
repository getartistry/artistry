<?php
/**
 * Hybridauth providers collector template
 * @link       http://wp.timersys.com/wordpress-social-invitations/
 * @since      2.5.0
 *
 * @package    Wsi
 * @subpackage Wsi/templates/popup/collector
 */
?>
<div class="box-wrapper collector">
	<div class="search-box">
		<div class="unselect-all">
			<a href="#" class="unselect" style="display: none"><?php _e('Unselect All','wsi');?></a>
			<a href="#" class="select" style="display: block"><?php _e('Select All','wsi');?></a>
		</div>
		<div id="friendsSearch" class="textwrapper text-search labeloverlaywrapper">
			<input type="text" class="form-text required defaultInvalid toggleval" placeholder="<?php _e('Search friends','wsi');?>" id="searchinput" style="">
		</div>
	</div>
	<div class="scroll-box">
		<table id="FriendsList" class="friends_container" cellspacing="0" cellpadding="0">
			<tbody>
			<?php
				if(!empty($friends) ) {
					for ( $i = 0, $n = count( $friends ); $i < $n; ++ $i ) {
						$identifier = Wsi_Hybrid::getFriendId( $friends[ $i ] );
						$invited    = isset( $friends[ $i ]->already_invited ) ? 'invited' : false;
						if ( empty( $identifier ) ) {
							continue;
						}
						?>
						<tr class="<?php echo $invited; ?>">
							<td class="checkbox-container">
								<?php if ( $invited && ! $force_invites ) { ?>
									<div class="invited"></div>
								<?php } else { ?>
									<input type="checkbox" value="<?php echo $identifier; ?>" name="friend[]"/>
								<?php } ?>
							</td>
							<td class="user-img">
								<?php if ( isset( $friends[ $i ]->photoURL ) && $friends[ $i ]->photoURL ): ?>

									<img class="lazy" data-original="<?php echo $friends[ $i ]->photoURL; ?>" alt=""/>

								<?php endif; ?>
							</td>
							<td class="last-child">
								<?php Wsi_Hybrid::printName( $friends[ $i ]->displayName ); ?>
								<em><?php echo $friends[ $i ]->email; ?></em>
							</td>
						</tr>

						<?php
						unset( $friends[ $i ] );
					}
				}
			?>
			</tbody>
		</table>
	</div><!--scrollbox-->
</div><!--box-wrapper-->