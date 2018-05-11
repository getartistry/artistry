<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_bp_activities", "Search in buddypress activities?",
        $sd['search_in_bp_activities']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_bp_groups", "Search in buddypress groups?",
        $sd['search_in_bp_groups']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_bp_groups_public", "Search in public groups?",
        $sd['search_in_bp_groups_public']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_bp_groups_private", "Search in private groups?",
        $sd['search_in_bp_groups_private']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
<div class="item">
    <?php
    $o = new wpdreamsYesNo("search_in_bp_groups_hidden", "Search in hidden groups?",
        $sd['search_in_bp_groups_hidden']);
    $params[$o->getName()] = $o->getData();
    ?>
</div>
