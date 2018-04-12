<?php
    $data = c27()->merge_options([
            'content' => '',
            'is_edit_mode' => false,
        ], $data);
?>

<div class="featured-section c27-featured-section">
    <div class="featured-caption">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 fc-description">
                    <?php echo do_shortcode($data['content']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($data['is_edit_mode']): ?>
    <!-- When the section is being edited in Elementor, re-call the necessary scripts for it to be displayed properly. -->
    <script type="text/javascript">case27_ready_script(jQuery);</script>
<?php endif ?>
