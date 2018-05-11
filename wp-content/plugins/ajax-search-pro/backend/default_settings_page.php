<div class="wrap" id="wpdreams"> 
    <div class="item">
      <?php 
        $o = new wpdreamsColorPicker("imagebg_1","Test background", 'rgba(255, 255, 0, 0.6)');
      ?>
    </div>
    <div class="item">
      <?php 
        $o = new wpdreamsColorPicker("imagebg_2","Test background", 'rgba(255, 0, 0, 0.6)');
      ?>
    </div>
    <div class="item">
    <?php       
      $o = new wpdreamsBoxShadowMini("inputshadow_1", "Search input field Shadow", '');
    ?>
    </div>
    <div class="item">
    <?php       
      $o = new wpdreamsTextShadowMini("inputshadow_44", "Shadow", 'text-shadow:1px 2px 3px #123123;');
    ?>
    </div>
    <div class="item">
    <?php       
      $o = new wpdreamsFontMini("titlefont_1", "Results title link font", "font-weight:normal;font-family:'Arial', Helvetica, sans-serif;color:#adadad;font-size:12px;line-height:15px;");
    ?>
    </div>
    <div class="item">
    <?php       
      $o = new wpdreamsNumericUnit("numericunit", "Test",
        array(
          'units'=>array(
            'px'=>'px',
            'em'=>'em'
           ),
           'value'=>'123px'
        )
      );
    ?>
    </div>
</div>