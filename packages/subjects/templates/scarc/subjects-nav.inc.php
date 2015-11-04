<?php
/**
 * Digital content navigation template
 *
 *
 * The Archon API is also available through the variable:
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
<h1 id='titleheader'><?php echo($strPageTitle); ?></h1>
<?php echo $aToZList; ?>
<div class="<?php echo($strSubTitleClasses); ?> center"><?php echo($strSubTitle); ?></div>
<div class="bground beginningwith" id="<?php echo($strBackgroundID); ?>"><div class='listitemcover'></div>
  <?php echo($content); ?>
</div>

<?php if($subTopics){ ?>
<div class="center"><span class='bold'><?php echo($strFilterBy); ?>:</span>
<br /><br />
<?php echo($subTopics); ?>
</div>
<?php } ?>

<?php if(isset($pages)){ echo($pages); } ?>