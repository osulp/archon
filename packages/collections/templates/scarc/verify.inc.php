<?php
/**
 * Verify template (for research carts)
 *
 * The appointment form must contain at minimum an ArrivalTime field
 *
 * The Archon API is available through the variable:
 *
 *  $_ARCHON
 *
 * Refer to the Archon class definition in lib/archon.inc.php
 * for available properties and methods.
 *
 * @package Archon
 * @author Kyle Fox
 */

isset($_ARCHON) or die();

echo("<h1 id='titleheader'>" . strip_tags($_ARCHON->PublicInterface->Title) . "</h1>\n");
research_displaycart();
?>
<input type="hidden" name="ArrivalTime" value="<?php echo($ArrivalTimestamp); ?>" />
<input type="hidden" name="DepartureTime" value="<?php echo($DepartureTimestamp); ?>" />
<input type="hidden" name="Topic" value="<?php echo(encode($_REQUEST['topic'], ENCODE_HTML)); ?>" />
<textarea name="ResearcherComments" style="display: none;"><?php echo(encode($_REQUEST['researchercomments'], ENCODE_HTML)); ?></textarea>
  <p class="center"><span class="bold">Verify Your Appointment</span><br/>(To make changes, click Back in your browser.)</p>
  <div class="form-group">
    <label class="control-label col-sm-4">Date/Time of Arrival:</label>
    <div class="col-sm-8">
      <p class="form-control-static"><?php echo(date(CONFIG_CORE_DATE_FORMAT, $ArrivalTimestamp)); ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-4">Estimated Date/Time of Departure:</label>
    <div class="col-sm-8">
      <p class="form-control-static"><?php if($DepartureTimestamp) { echo(date(CONFIG_CORE_DATE_FORMAT, $DepartureTimestamp)); } else { echo("Unspecified"); } ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-4">Topic of Research:</label>
    <div class="col-sm-8">
      <p class="form-control-static"><?php echo(encode($_REQUEST['topic'], ENCODE_HTML)); ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-4">Additional Comments for the Archivist:</label>
    <div class="col-sm-8">
      <p class="form-control-static"><?php echo(nl2br(encode($_REQUEST['researchercomments'], ENCODE_HTML))); ?></p>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-4 col-sm-8">
      <input type="submit" class="btn btn-primary" value="Finalize Appointment Request" />
    </div>
  </div>
</div>
<script type="text/javascript">
   $(function(){
      var repoid = $('#RepositoryIDField');

      if(repoid.val() != 0){
         $('.repogrp').hide();
         $('#repo' + repoid.val()).fadeIn();
      }
   });
</script>