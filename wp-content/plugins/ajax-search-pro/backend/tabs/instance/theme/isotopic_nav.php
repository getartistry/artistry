<div class="item item-rlayout item-rlayout-isotopic">
    <p>These options are hidden, because the <span>vertical</span> results layout is selected.</p>
    <p>You can change that under the <a href="#402" data-asp-os-highlight="resultstype" tabid="402">Layout Options -> Results layout</a> panel,
        <br>..or choose a <a href="#601" tabid="601">different theme</a> with a different pre-defined layout.</p>
</div>
<div class="item"><?php
    $o = new wpdreamsCustomSelect("i_pagination_position", "Navigation position",  array(
        'selects'=>array(
            array('option' => 'Top', 'value' => 'top'),
            array('option' => 'Bottom', 'value' => 'bottom'),
            array('option' => 'Both Top and Bottom', 'value' => 'both')
        ),
        'value'=>$sd['i_pagination_position']
    ));
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_pagination_background", "Pagination background", $sd['i_pagination_background']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsImageRadio("i_pagination_arrow", "Arrow image", array(
            'images'  => array(
                "/ajax-search-pro/img/svg/arrows/arrow1.svg",
                "/ajax-search-pro/img/svg/arrows/arrow2.svg",
                "/ajax-search-pro/img/svg/arrows/arrow3.svg",
                "/ajax-search-pro/img/svg/arrows/arrow4.svg",
                "/ajax-search-pro/img/svg/arrows/arrow5.svg",
                "/ajax-search-pro/img/svg/arrows/arrow6.svg",
                "/ajax-search-pro/img/svg/arrows/arrow7.svg",
                "/ajax-search-pro/img/svg/arrows/arrow8.svg",
                "/ajax-search-pro/img/svg/arrows/arrow9.svg",
                "/ajax-search-pro/img/svg/arrows/arrow10.svg",
                "/ajax-search-pro/img/svg/arrows/arrow11.svg",
                "/ajax-search-pro/img/svg/arrows/arrow12.svg",
                "/ajax-search-pro/img/svg/arrows/arrow13.svg",
                "/ajax-search-pro/img/svg/arrows/arrow14.svg",
                "/ajax-search-pro/img/svg/arrows/arrow15.svg",
                "/ajax-search-pro/img/svg/arrows/arrow16.svg",
                "/ajax-search-pro/img/svg/arrows/arrow17.svg",
                "/ajax-search-pro/img/svg/arrows/arrow18.svg"
            ),
            'value'=> $sd['i_pagination_arrow']
        )
    );
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_pagination_arrow_background", "Arrow background color", $sd['i_pagination_arrow_background']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_pagination_arrow_color", "Arrow color", $sd['i_pagination_arrow_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_pagination_page_background", "Active page background color", $sd['i_pagination_page_background']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item"><?php
    $o = new wpdreamsColorPicker("i_pagination_font_color", "Font color", $sd['i_pagination_font_color']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>