<div class="item">
	<?php
	$o = new wpdreamsYesNo("display_search_text", "Display the search text button?",
		$sd['display_search_text']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item">
	<?php
	$o = new wpdreamsYesNo("hide_magnifier", "Hide the magnifier icon?",
		$sd['hide_magnifier']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item">
	<?php
	$o = new wpdreamsText("search_text", "Button text",
		$sd['search_text']);
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item">
	<?php
	$o = new wpdreamsCustomSelect("search_text_position", "Button position", array(
		'selects'=>array(
			array('option' => 'Left to the magnifier', 'value' => "left"),
			array('option' => 'Right to the magnifier', 'value' => "right")
		),
		'value'=>$sd['search_text_position']) );
	$params[$o->getName()] = $o->getData();
	?>
</div>
<div class="item"><?php
	$o = new wpdreamsFontComplete("search_text_font", "Button font", $sd['search_text_font']);
	$params[$o->getName()] = $o->getData();
	?>
</div>