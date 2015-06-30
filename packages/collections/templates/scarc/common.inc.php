<?php
/**
 * Render functions common to controlcard and collection templates.
 *
 */

function render_container_list($_ARCHON, $objCollection) {

  ?>
  <div id="nav-container-list" class="ccardcontent"><span
      class='ccardlabel'><a href="#boxfolder"><?php echo $_ARCHON->getPhrase('container_list', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>
    <?php
    $DisableTheme = $_ARCHON->PublicInterface->DisableTheme;
    $_ARCHON->PublicInterface->DisableTheme = true;

    $content_list = '';
    foreach ($objCollection->Content as $ID => $objContent) {
      if (!$objContent->ParentID) {
        if ($objContent->enabled()) {
          $content_list .= '<li class="ccardserieslist"><a href="#id'.$ID.'">' . $objContent->toString() . "</a></li>\n";
        }
        else {
          $objInfoRestrictedPhrase = Phrase::getPhrase('informationrestricted', PACKAGE_CORE, 0, PHRASETYPE_PUBLIC);
          $strInfoRestricted = $objInfoRestrictedPhrase ? $objInfoRestrictedPhrase->getPhraseValue(ENCODE_HTML) : 'Information restricted, please contact us for additional information.';
          $content_list .= "<span class='ccardserieslist'>{$strInfoRestricted}</span><br/>\n";
        }
      }
    }
    if (strlen($content_list) > 0) { ?>
      <ul class="nav nav-tabs nav-stacked"><?php echo $content_list; ?></ul>
      <?php
    }
    $_ARCHON->PublicInterface->DisableTheme = $DisableTheme;
    ?>
  </div>
<?php

}