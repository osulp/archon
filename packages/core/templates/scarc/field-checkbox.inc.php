<?php
/**
 * Form sub-template: checkbox inputs
 *
 * This file inserts a checkbox control and its label into a form.
 *
 * The Archon API is available through the variable:
 *
 *  $_ARCHON
 *
 * Refer to the Archon class definition in lib/archon.inc.php
 * for available properties and methods.
 *
 * @package Archon
 */

isset($_ARCHON) or die();
?>
<div class="form-group">
  <div class="col-sm-offset-4 col-sm-8">
    <div class="checkbox">
      <label>
        <?php echo($strInputElement); echo($strRequired); echo($strInputLabel);   ?>
      </label>
    </div>
  </div>
</div>

