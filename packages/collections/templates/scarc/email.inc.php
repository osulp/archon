<?php
/**
 * ResearchEmail template
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
 * @author Kyle Fox
 */

isset($_ARCHON) or die();


echo("<h1 id='titleheader'>" . strip_tags($_ARCHON->PublicInterface->Title) . "</h1>\n");
?>
  <p>Fields marked with an asterisk (<span style="color:red">*</span>) are required.</p>
  <div class="form-group">
    <label class="control-label col-sm-3" for="name"><?php echo($strFromName); ?>:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name="FromName" id="name" size="30" value="<?php echo($strName); ?>" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="email"><span style="color:red">*</span> <?php echo($strFromAddress); ?>:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name="FromAddress" id="email" size="25" value="<?php echo($strFrom); ?>" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="phone"><?php echo($strFromPhone); ?>:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name="FromPhone" id="phone" size="20" value="<?php echo($strPhone); ?>" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="subject"><?php echo($strSubject); ?>:</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" name="subject" id="subject" size="40" value="<?php echo(encode($_REQUEST['subject'], ENCODE_HTML)); ?>" />
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-sm-3" for="message"><span style="color:red">*</span> <?php echo($strMessage); ?>:</label>
    <div class="col-sm-9">
      <textarea class="form-control" name="message" id="message" cols="38" rows="5"><?php echo(encode($_REQUEST['message'], ENCODE_HTML)); ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
      <input type="submit" value="<?php echo($strSendEmail); ?>" class="btn btn-primary" />
    </div>
  </div>

<?php
    $_ARCHON->Security->Session->ResearchCart->getCart();
    if($_ARCHON->Security->Session->ResearchCart->getCartCount())
    {
?>
<div class='listitemhead bold'><?php echo($strCartAppend); ?></div>
<?php
        research_displaycart();
    }
    

?>
