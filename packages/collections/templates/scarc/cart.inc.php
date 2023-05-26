<?php
/**
 * ResearchCart template
 *
 * The appointment form must contain at minimum an ArrivalDateString field
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

echo("<h1 id='titleheader'>" . strip_tags($_ARCHON->PublicInterface->Title) . "</h1>\n<div id='researchcartwrapper'>\n");
$_ARCHON->Security->Session->ResearchCart->getCart();
if(!$_ARCHON->Security->Session->ResearchCart->getCartCount())
{
   echo("<div id='researchcart' class='mdround center'><strong>".$_ARCHON->getPhrase('research_cartempty', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)->getPhraseValue(ENCODE_HTML)."</strong>");
   if($_ARCHON->Security->isAuthenticated())
   {
      echo("<strong>You may wish to add something to it before setting up an appointment.</strong><br/>");
      echo("Add materials by searching the collections and clicking the icons next to item names.<br/>Preparing a cart helps us to have your materials ready for you when you arrive for an appointment.");
   }
   echo("<br/><br/></div>");
}
else
{
   echo("<div class='listitemhead bold'>".$_ARCHON->getPhrase('research_cartholds', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)->getPhraseValue(ENCODE_HTML)."</div>");
}

research_displaycart();
?>
<div id="researchcartinfo" class="bground">
<?php
if(!$_ARCHON->Security->isAuthenticated())
{
?>

<span class='bold'>To learn more about our by-appointment hours, and the logistics of in-person research, please visit the <a href="https://guides.library.oregonstate.edu/guidetoscarc/engaging">Conducting In-person Research</a> page of our <a href="https://guides.library.oregonstate.edu/guidetoscarc/welcome">Guide to SCARC</a>.</span>

<?php
}
else
{
   $arrRepositories = $_ARCHON->Security->Session->ResearchCart->getCartRepositories();

   if($_REQUEST['arrivaltimestamp'])
   {
      $_REQUEST['arrivaldatestring'] = date(CONFIG_CORE_DATE_FORMAT, $_REQUEST['arrivaldatestring']);
   }

   if($_REQUEST['departuretimestamp'])
   {
      $_REQUEST['departuredatestring'] = date(CONFIG_CORE_DATE_FORMAT, $_REQUEST['departuredatestring']);
   }
?>
   <div class='userformbox mdround'>
     <legend>Make An Appointment</legend>
     <p class="info-message"><strong>Please note:</strong> We require at least 24 hours notice to page
       materials in advance of your appointment. Materials will be removed from your
       shelf in this system after making an appointment. You will receive an
       appointment confirmation email listing the contents of your shelf.</p>
     <p><em>Fields marked with an asterisk (<span style="color:red">*</span>) are required.</em></p>
     <div class="form-group">
         <label class="col-sm-4 control-label" for="RepositoryIDField">Repository:</label>
         <div class="col-sm-8">
           <?php
           if (!empty($arrRepositories)) {
             if (count($arrRepositories) > 1) {
               ?>
               <select class="form-control" id="RepositoryIDField" name="RepositoryID">
                 <option value="0">(Select One)</option>
                 <?
                 foreach ($arrRepositories as $objRepository) {
                   $selected = $objRepository->ID == $_REQUEST['repositoryid'] ? 'selected' : '';
                   echo("        <option value=\"$objRepository->ID\" $selected>" . $objRepository->toString() . "</option>");
                 }
                 ?>
               </select>
             <?php
             }
             else {
               $objRepository = reset($arrRepositories);
               ?>
               <input type="hidden" name="RepositoryID" id="RepositoryIDField" value="<?php echo($objRepository->ID); ?>"/>
               <p class="form-control-static"><?php echo($objRepository->toString()); ?></p>
             <?php
             }
           }
           ?>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-sm-4" for="ArrivalDateStringField"><span style="color:red">*</span> Date/Time of Arrival:</label>
         <div class="col-sm-8">
           <input class="form-control" type="text" size="40" id="ArrivalDateStringField" name="ArrivalDateString" value="<?php echo(encode($_REQUEST['arrivaldatestring'], ENCODE_HTML)); ?>" aria-describedby="arriveHelp" />
           <span id="arriveHelp" class='help-block'>(eg. 4/30/<?php echo(date('Y')); ?> 10:00 AM)</span>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-sm-4" for="DepartureDateStringField">Estimated Date/Time of Departure:</label>
         <div class="col-sm-8">
           <input class="form-control" type="text" size="40" id="DepartureDateStringField" name="DepartureDateString" value="<?php echo(encode($_REQUEST['departuredatestring'], ENCODE_HTML)); ?>" aria-describedby="departHelp" />
           <span id="departHelp" class='help-block'>(eg. 4/30/<?php echo(date('Y')); ?> 1:30 PM)</span>
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-sm-4" for="TopicField">Topic of Research:</label>
         <div class="col-sm-8">
           <input class="form-control" type="text" size="40" maxlength="100" id="TopicField" name="Topic" value="<?php echo(encode($_REQUEST['topic'], ENCODE_HTML)); ?>" />
         </div>
      </div>
      <div class="form-group">
         <label class="control-label col-sm-4" for="ResearcherCommentsField">Additional Comments for the Archivist:</label>
         <div class="col-sm-8">
           <textarea class="form-control" id="ResearcherCommentsField" name="ResearcherComments" cols="33" rows="5"><?php echo(encode($_REQUEST['researchercomments'], ENCODE_HTML)); ?></textarea>
         </div>
      </div>
     <div class="form-group">
       <div class="col-sm-offset-4 col-sm-8">
         <input type="submit" class="btn btn-primary" value="Next" />
       </div>
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

      repoid.bind('change',function(){
         if(repoid.val() != 0){
            $('.repogrp').hide();
            $('#repo' + repoid.val()).fadeIn();
         }else{
            $('.repogrp').fadeIn();
         }
      });
   })
</script>
<?php
}
?>
</div>
</div>
