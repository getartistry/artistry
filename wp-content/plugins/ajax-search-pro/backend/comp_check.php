<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");

/* Error Checking*/
$_comp = wpdreamsCompatibility::Instance();
$_comp_errors = $_comp->get_errors();
?>
<div id="wpdreams" class='wpdreams wrap'>
<?php if ($_comp->has_errors()): ?>
  <div class="wpdreams-box errorbox">
       <h1>Possible errors (<?php echo count($_comp_errors['errors']); ?>)</h1>
       <?php foreach($_comp_errors['errors'] as $k=>$err): ?>
        <div>
          <h3>Error #<?php echo ($k+1); ?></h3><p class='err'><?php echo $err; ?></p>
          <h3>Possible Consequences</h3><p class='cons'><?php echo $_comp_errors['cons'][$k]; ?></p>
          <h3>Solutions</h3><p class='sol'><?php echo $_comp_errors['solutions'][$k]; ?></p>
        </div>
       <?php endforeach; ?>
       Please note, that these errors may not be accurate!
  </div>
  <?php else: ?>
  <div class="wpdreams-box errorbox">
       <p class='tick'>No errors found!</p>
  </div>
<?php endif; ?>
</div>