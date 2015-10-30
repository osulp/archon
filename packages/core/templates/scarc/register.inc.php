<?php
/**
 * Registration template
 *
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
<h1 id="titleheader"><?php echo($strPageTitle); ?></h1>
<p>Fields marked with an asterisk (<span style="color:red">*</span>) are required.</p>
<?php echo($form); ?>
<?php echo($strSubmitButton); ?>
<br />
<p class="center"><a href="?p=core/privacy"><?php echo($strPrivacyNote); ?></a></p>
