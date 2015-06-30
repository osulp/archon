<?php
/**
 * Render functions common to controlcard and collection templates.
 *
 */

function render_container_list($_ARCHON, $objCollection) {

  ?>
  <div class="ccardcontent"><span
      class='ccardlabel'><a href="#boxfolder"><?php echo $_ARCHON->getPhrase('container_list', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>
    <?php
    $DisableTheme = $_ARCHON->PublicInterface->DisableTheme;
    $_ARCHON->PublicInterface->DisableTheme = true;

    foreach ($objCollection->Content as $ID => $objContent) {
      if (!$objContent->ParentID) {
        if ($objContent->enabled()) {
          echo("<div class='ccardserieslist'><a href='?p=collections/findingaid&amp;id=$objCollection->ID&amp;q=$_ARCHON->QueryStringURL#id$ID'>" . $objContent->toString() . "</a></div>\n");
        }
        else {
          $objInfoRestrictedPhrase = Phrase::getPhrase('informationrestricted', PACKAGE_CORE, 0, PHRASETYPE_PUBLIC);
          $strInfoRestricted = $objInfoRestrictedPhrase ? $objInfoRestrictedPhrase->getPhraseValue(ENCODE_HTML) : 'Information restricted, please contact us for additional information.';
          echo("<span class='ccardserieslist'>{$strInfoRestricted}</span><br/>\n");
        }
      }
    }
    echo '<div class="ccardserieslist"><a href="?p=collections/findingaid&amp;id='.$objCollection->ID.'&amp;q='.$_ARCHON->QueryStringURL.'#boxfolder">Entire Container List</a></div>';
    $_ARCHON->PublicInterface->DisableTheme = $DisableTheme;
    ?>
  </div>
<?php

}