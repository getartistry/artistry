<?php
    $data = c27()->merge_options([
            'members' => [],
            'overlay_type' => 'gradient',
            'overlay_gradient' => 'gradient1',
            'overlay_solid_color' => 'rgba(0, 0, 0, .5)',
        ], $data);
?>

<section class="i-section">
    <div class="container-fluid">
        <div class="row section-body">
            <?php foreach ((array) $data['members'] as $member): ?>
                <div class="col-xs-12 col-sm-6 col-md-4 reveal">
                    <div class="single-team">
                        <div class="st-background" style="background-image: url('<?php echo esc_url( $member['image']['url'] ) ?>');"></div>
                        <div class="img-hover-holder <?php echo $data['overlay_type'] == 'gradient' ? esc_attr( $data['overlay_gradient'] ) : '' ?>"
                             style="<?php echo $data['overlay_type'] == 'solid_color' ? 'background-color: ' . esc_attr( $data['overlay_solid_color'] ) . '; ' : '' ?>">
                            <div class="info-hover">
                                <ul>
                                    <li><h2><?php echo esc_html( $member['name'] ) ?></h2></li>
                                    <li><h3><?php echo esc_html( $member['position'] ) ?></h3></li>
                                </ul>
                            </div>
                            <ul class="social-nav">
                                <?php for ($i = 1; $i <= 5; $i++):
                                    $icon = isset($member['social_network_icon__' . $i]) && $member['social_network_icon__' . $i] ? $member['social_network_icon__' . $i] : false;
                                    $url = isset($member['social_network_link__' . $i]) && $member['social_network_link__' . $i] ? $member['social_network_link__' . $i]['url'] : false;
                                    ?>
                                    <?php if ($icon && $url): ?>
                                        <li class="fb-icon">
                                            <a href="<?php echo esc_url( $url ) ?>" target="_blank">
                                                <i class="<?php echo esc_attr( $icon ) ?>" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                <?php endfor ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</section>
