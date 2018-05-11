<div class="item">
    <?php
    $o = new wd_DateFilter("date_filter_from", "Display 'Posts from date' filter", $sd['date_filter_from']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("date_filter_from_t", "Filter text", $sd['date_filter_from_t']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item" style="border-bottom: 1px dashed #E5E5E5;padding-bottom: 26px;">
    <?php
    $o = new wpdreamsText("date_filter_from_format", "Date format", $sd['date_filter_from_format']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">dd/mm/yy is the most used format, <a href="http://api.jqueryui.com/datepicker/#utility-formatDate" target="_blank">list of accepted params</a></p>
</div>
<div class="item">
    <?php
    $o = new wd_DateFilter("date_filter_to", "Display 'Posts to date' filter", $sd['date_filter_to']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsText("date_filter_to_t", "Filter text", $sd['date_filter_to_t']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item" style="border-bottom: 1px dashed #E5E5E5;padding-bottom: 26px;">
    <?php
    $o = new wpdreamsText("date_filter_to_format", "Date format", $sd['date_filter_to_format']);
    $params[$o->getName()] = $o->getData();
    ?>
    <p class="descMsg">dd/mm/yy is the most used format, <a href="http://api.jqueryui.com/datepicker/#utility-formatDate" target="_blank">list of accepted params</a></p>
</div>