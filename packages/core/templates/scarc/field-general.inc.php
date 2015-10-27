<?php
/**
 * Form sub-template: general inputs
 *
 * This file inserts an input control and its label into a form.  It handles
 * all inputs except compound inputs (i.e. radio buttons).
 *
 * The Archon API is available through the variable:
 *
 *  $_ARCHON
 *
 * Refer to the Archon class definition in lib/archon.inc.php
 * for available properties and methods.
 *
 * @package Archon
 * @author Will Martin
 */

isset($_ARCHON) or die();
?>
<div class="form-group">
	<?php echo($strRequired); echo($strInputLabel); ?>
	<div class="col-sm-8">
		<?php echo($strInputElement);  ?>
	</div>
</div>

