<?php
if (!class_exists("wpdreamsUpload")) {
    /**
     * Class wpdreamsUpload
     *
     * DEPRECATED
     *
     * @deprecated
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsUpload extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();

            echo "
      <div class='wpdreamsUpload' id='wpdreamsUpload" . self::$_instancenumber . "'>";
            ?>
            <label for='wpdreamsUpload_input<?php echo self::$_instancenumber; ?>'>
                <?php echo $this->label; ?>
            </label>
            <input id="wpdreamsUpload_input<?php echo self::$_instancenumber; ?>" type="text"
                   size="36" name="<?php echo $this->name; ?>" value="<?php echo $this->data; ?>"/>
            <input id="wpdreamsUpload_button<?php echo self::$_instancenumber; ?>" class="button" type="button"
                   value="Upload"/>

            <?php
            ?>
            <script type='text/javascript'>
                (function ($) {
                    $(document).ready(function () {

                        var custom_uploader;

                        $('#wpdreamsUpload_button<?php echo self::$_instancenumber; ?>').click(function (e) {

                            e.preventDefault();

                            //If the uploader object has already been created, reopen the dialog
                            if (custom_uploader) {
                                custom_uploader.open();
                                return;
                            }

                            //Extend the wp.media object
                            custom_uploader = wp.media.frames.file_frame = wp.media({
                                title: 'Choose Image',
                                button: {
                                    text: 'Choose Image'
                                },
                                multiple: false
                            });

                            //When a file is selected, grab the URL and set it as the text field's value
                            custom_uploader.on('select', function () {
                                attachment = custom_uploader.state().get('selection').first().toJSON();
                                $('#wpdreamsUpload_input<?php echo self::$_instancenumber; ?>').val(attachment.url);
                            });

                            //Open the uploader dialog
                            custom_uploader.open();

                        });
                    });
                }(jQuery));
            </script>
            <?php
            echo "
      </div>";
        }


        function processData() {

        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {

        }

        final function getItems() {

        }
    }
}