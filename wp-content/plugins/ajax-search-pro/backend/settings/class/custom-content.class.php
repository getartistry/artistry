<?php
if (!class_exists("wpdreamsCustomContent")) {
    /**
     * Class wpdreamsCustomContent
     *
     * A custom content creator/selector UI element. The frontend and backend data is stored base64 encoded.
     *
     * @package  WPDreams/OptionsFramework/Classes
     * @category Class
     * @author Ernest Marcinko <ernest.marcinko@wp-dreams.com>
     * @link http://wp-dreams.com, http://codecanyon.net/user/anago/portfolio
     * @copyright Copyright (c) 2014, Ernest Marcinko
     */
    class wpdreamsCustomContent extends wpdreamsType {
        function getType() {
            parent::getType();
            $this->processData();

            echo "
      <div class='wpdreamsCustomContent' id='wpdreamsCustomContent" . self::$_instancenumber . "'>
        <fieldset>
          <legend>" . $this->label . "</legend>";
            echo '<div class="sortablecontainer"><ul id="sortable' . self::$_instancenumber . '" class="connectedSortable">';
            if ($this->decItems != null && is_array($this->decItems)) {
                foreach ($this->decItems as $k => $v) {
                    echo "
          <li class='ui-state-default' custom-data='" . $this->items[$k] . "'>
            <div class='content'>" . $this->decItems[$k]->title . "</div>
            <a class='deleteIcon iconTopRight'></a>
          </li>";
                }
            }
            echo "</ul></div>";
            ?>
            <div class='clear'></div>
            <div class='customContent'>
                <h4>Add custom content</h4>

                <form>
                    <div class='one-item'>
                        <label for='rppc_title'>Title</label>
                        <input type='text' name='rppc_title'/>
                    </div>
                    <div class='one-item'>
                        <label for='rppc_url'>URL</label>
                        <input type='text' name='rppc_url'/>
                    </div>
                    <div class='one-item'>
                        <label for='rppc_imgurl'>Image URL</label>
                        <input type='text' name='rppc_imgurl'/>
                        <button type='button' id='img_upload<?php echo self::$_instancenumber; ?>'>upload</button>
                    </div>
                    <div class='one-item'>
                        <label for='rppc_author'>Author</label>
                        <input type='text' name='rppc_author' id='rppc_author<?php echo self::$_instancenumber; ?>'/>
                    </div>
                    <div class='one-item'>
                        <label for='rppc_date'>Date & Time</label>
                        <input type='text' name='rppc_date' id='rppc_date<?php echo self::$_instancenumber; ?>'/>
                    </div>
                    <div class='one-item'>
                        <label for='rppc_content'>Content - <b>NO HTML</b></label>
                        <textarea name='rppc_content'/></textarea>
                    </div>
                    <div class='one-item'>
                        <button type='button' name='add'>Add!</button>
                    </div>
                </form>
            </div>

            <div class='searchContent'>
                <h4>Search blog content titles</h4>

                <div class='one-item'>
                    <input type='text' name='title'/>
                    <span class='searchmagn'></span>
                    <span class='searchload'></span>
                </div>
                <div class='itemResults'>
                </div>
            </div>
            <?php
            echo "
         <input isparam=1 type='hidden' value='" . $this->data . "' name='" . $this->name . "'>";
            echo "
         <input type='hidden' value='wpdreamsCustomContent' name='classname-" . $this->name . "'>";
            ?>
            <script type='text/javascript'>
                (function ($) {
                    $(document).ready(function () {
                        var sortableCont = $("#sortable<?php echo self::$_instancenumber ?>");
                        var $customContent = $('#wpdreamsCustomContent<?php echo self::$_instancenumber; ?> .customContent');
                        var $searchContent = $('#wpdreamsCustomContent<?php echo self::$_instancenumber; ?> .searchContent');
                        var customAdd = $('#wpdreamsCustomContent<?php echo self::$_instancenumber; ?> .customContent button[name="add"]');
                        var $searchAdd = $('#wpdreamsCustomContent<?php echo self::$_instancenumber; ?> .itemResults .addicon');
                        var $dateField = $('#wpdreamsCustomContent<?php echo self::$_instancenumber; ?> input[name="rppc_date"]');

                        $dateField.datetimepicker();

                        sortableCont.sortable({
                            connectWith: ".connectedSortable"
                        }, {
                            update: function (event, ui) {
                                parent = $('#wpdreamsCustomContent<?php echo self::$_instancenumber; ?>');

                                var items = $('ul.connectedSortable li', parent);
                                var hidden = $('input[name=<?php echo $this->name; ?>]', parent);
                                var val = "";
                                items.each(function () {
                                    val += "|" + $(this).attr('custom-data');
                                });
                                val = val.substring(1);
                                hidden.val(val);
                            }
                        }).disableSelection();

                        function checkEmpty() {
                            var fields = ['rppc_title', 'rppc_url', 'rppc_content'];
                            var empty = false;
                            $(fields).each(function () {
                                if ($('*[name="' + this.toString() + '"]', $customContent).val() == '') {
                                    $('*[name="' + this.toString() + '"]', $customContent).addClass('missing');
                                    empty = true;
                                }
                            });
                            return empty;
                        }

                        customAdd.click(function (e) {
                            if (checkEmpty()) return;
                            var $content = $("<div class='content'></div>");


                            var $a = $("<a class='deleteIcon iconTopRight'></a>");


                            var data = {
                                title: ($('input[name="rppc_title"]', $customContent).val()),
                                url: ($('input[name="rppc_url"]', $customContent).val()),
                                imgurl: ($('input[name="rppc_imgurl"]', $customContent).val()),
                                author: ($('input[name="rppc_author"]', $customContent).val()),
                                date: ($('input[name="rppc_date"]', $customContent).val()),
                                content: ($('textarea[name="rppc_content"]', $customContent).val())
                            };

                            $content.text(data.title);
                            var $li = $("<li class='ui-state-default'/>")
                                .append($content).append($a);
                            $li.attr("custom-data", Base64.encode(JSON.stringify(data)));

                            sortableCont.append($li);
                            sortableCont.sortable("refresh");
                            sortableCont.sortable('option', 'update').call(sortableCont);
                        });

                        $searchContent.on("click", ".addIcon", function (e) {

                            var $content = $("<div class='content'></div>");
                            var $a = $("<a class='deleteIcon iconTopRight'></a>");


                            var data = {
                                title: $('p.title', $(this).parent()).html(),
                                id: $('p.title', $(this).parent()).attr('pid')
                            };

                            $content.text(data.title);
                            var $li = $("<li class='ui-state-default'/>")
                                .append($content).append($a);
                            $li.attr("custom-data", Base64.encode(JSON.stringify(data)));

                            sortableCont.append($li);
                            sortableCont.sortable("refresh");
                            sortableCont.sortable('option', 'update').call(sortableCont);
                        });

                        $('input, textarea', $customContent).focus(function () {
                            $(this).removeClass('missing');
                        });

                        sortableCont.on("click", "a.deleteIcon", function () {
                            $(this).parent().remove();
                            sortableCont.sortable("refresh");
                            sortableCont.sortable('option', 'update').call(sortableCont);
                        });

                        $searchContent.on("click", '.searchmagn', function () {
                            var _this = this;
                            var $input = $('input', $(this).parent());
                            var data = {
                                action: 'wpdreams-ajaxinput',
                                wpdreams_callback: 'wpdreamsCustomContent::getContent',
                                wpdreams_phrase: $input.val()
                            };
                            $('.searchmagn', $searchContent).css('display', "none");
                            $('.searchload', $searchContent).css('display', "inline-block");
                            $.post(ajaxurl, data, function (response) {
                                if (response != null && response.length > 0) {
                                    $('.itemResults', $searchContent).html('');
                                    $(response).each(function () {
                                        var $item = $("<div class='result-item'></div>");
                                        $item.append("<p pid=" + this.id + " class='title'>" + this.title + "</p>");
                                        var $etc = $("<p class='etc'></p>");
                                        $etc.append('by ' + this.author + ' [' + this.post_type + '] ');
                                        $etc.append(this.date);
                                        var $plus = $("<span class='addIcon iconRightMiddle'/>");
                                        $item.append($plus);
                                        $item.append($etc);
                                        $('.itemResults', $searchContent).append($item);
                                    });
                                } else {
                                    $('.itemResults', $searchContent).html('');
                                    var $item = $("<div class='result-item'>No results :(</div>");
                                    $('.itemResults', $searchContent).append($item);
                                }
                                $('.searchmagn', $searchContent).css('display', "inline-block");
                                $('.searchload', $searchContent).css('display', "none");
                            }, 'json');
                        });

                        var custom_uploader;

                        $('#img_upload<?php echo self::$_instancenumber; ?>').click(function (e) {
                            var _this = this;
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
                                $(_this).prev().val(attachment.url);
                            });

                            //Open the uploader dialog
                            custom_uploader.open();

                        });

                    });
                }(jQuery));
            </script>
            <?php
            echo "
        </fieldset>
      </div>";
        }

        public static function getContent() {
            global $wpdb;

            $s = $_POST['wpdreams_phrase'];
            $s = strtolower(trim($s));
            $s = preg_replace('/\s+/', ' ', $s);
            $pageposts = "[]";

            $querystr = "
    		SELECT 
          $wpdb->posts.post_title as title,
          $wpdb->posts.ID as id,
          $wpdb->posts.post_date as date,               
          $wpdb->users.user_nicename as author,
          $wpdb->posts.post_type as post_type";
            $querystr .= "
    		FROM $wpdb->posts
        LEFT JOIN $wpdb->users ON $wpdb->users.ID = $wpdb->posts.post_author
    		WHERE
          $wpdb->posts.post_status = 'publish' AND
          $wpdb->posts.post_title LIKE '%$s%'
        GROUP BY
          $wpdb->posts.ID";
            $querystr .= " ORDER BY $wpdb->posts.post_date
        LIMIT 30";

            $pageposts = $wpdb->get_results($querystr, OBJECT);
            print_r(json_encode($pageposts));
            die();
        }

        function processData() {
            $this->decItems = array();
            if ($this->data != "") {
                $this->items = explode('|', $this->data);
                foreach ($this->items as $k => $v) {
                    $this->decItems[$k] = json_decode(base64_decode($v));
                }
            }

        }

        final function getData() {
            return $this->data;
        }

        final function getSelected() {
            return $this->decItems;
        }

        final function getItems() {
            return $this->getSelected();
        }
    }
}