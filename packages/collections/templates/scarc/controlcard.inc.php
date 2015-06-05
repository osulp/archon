<?php
/**
 * Control Card template for "SCARC" template based on 'default'
 *
 * The variable:
 *
 *  $objCollection
 *
 * is an instance of a Collection object, with its properties
 * already loaded when this template is referenced.
 *
 * Refer to the Collection class definition in lib/collection.inc.php
 * for available properties and methods.
 *
 * The Archon API is also available through the variable:
 *
 *  $_ARCHON
 *
 * Refer to the Archon class definition in lib/archon.inc.php
 * for available properties and methods.
 *
 * @package Archon
 * @author Chris Rishel, Chris Prom, Paul Sorensen
 */
isset($_ARCHON) or die();

$repositoryid = $objCollection->RepositoryID;

$printerFriendly = $_ARCHON->getPhrase('printer_friendly', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)->getPhraseValue(ENCODE_HTML);
$emailUs = $_ARCHON->getPhrase('email_us', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)->getPhraseValue(ENCODE_HTML);
?>
<div id="scarc-controlcard" class="row" xmlns="http://www.w3.org/1999/html">
<div class="col-md-3">
  <div id="ccardprintcontact" class="smround">
    <p><a
        href="?p=collections/controlcard&amp;id=<?php echo $objCollection->ID; ?>&amp;templateset=print&amp;disabletheme=1"><img
          src="<?php echo $_ARCHON->PublicInterface->ImagePath; ?>/printer.png"
          alt="<?php echo $printerFriendly; ?>"/></a> <a
        href="?p=collections/controlcard&amp;id=<?php echo $objCollection->ID; ?>&amp;templateset=print&amp;disabletheme=1"><?php echo $printerFriendly; ?></a>
    </p>

    <p><a
        href="?p=collections/research&amp;f=email&amp;repositoryid=$repositoryid&amp;referer="<?php echo urlencode($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>
      "><img
        src="<?php echo $_ARCHON->PublicInterface->ImagePath; ?>/email.png"
        alt="<?php echo $emailUs; ?>"/> </a><a
        href="?p=collections/research&amp;f=email&amp;repositoryid=$repositoryid&amp;referer="<?php echo urlencode($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]); ?>
      "><?php echo $emailUs; ?></a></p>

    <p><?php
      if (defined('PACKAGE_COLLECTIONS')) {
        $_ARCHON->Security->Session->ResearchCart->getCart();
        $EntryCount = $_ARCHON->Security->Session->ResearchCart->getCartCount();
        $class = $_ARCHON->Repository->ResearchFunctionality & RESEARCH_COLLECTIONS ? '' : 'hidewhenempty';
        $hidden = ($_ARCHON->Repository->ResearchFunctionality & RESEARCH_COLLECTIONS || $EntryCount) ? '' : "style='display:none'";
        echo("<span id='viewcartlink' class='$class' $hidden><a href='?p=collections/research&amp;f=cart&amp;referer=" . urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . "'>" . $_ARCHON->getPhrase('view_cart', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
            ->getPhraseValue(ENCODE_HTML) . "</a></span>");
      } ?>
    </p>
    <?php
    if (!empty($objCollection->Content)) {
      ?>
      <div class='ccardcontent'><span
          class='ccardlabel'><?php echo $_ARCHON->getPhrase('container_list', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
            ->getPhraseValue(ENCODE_HTML); ?></span><br/>
        <?php
        $DisableTheme = $_ARCHON->PublicInterface->DisableTheme;
        $_ARCHON->PublicInterface->DisableTheme = true;

        foreach ($objCollection->Content as $ID => $objContent) {
          if (!$objContent->ParentID) {
            if ($objContent->enabled()) {
              echo("<span class='ccardserieslist'><a href='?p=collections/findingaid&amp;id=$objCollection->ID&amp;q=$_ARCHON->QueryStringURL&amp;rootcontentid=$ID#id$ID'>" . $objContent->toString() . "</a></span><br/>\n");
            }
            else {
              $objInfoRestrictedPhrase = Phrase::getPhrase('informationrestricted', PACKAGE_CORE, 0, PHRASETYPE_PUBLIC);
              $strInfoRestricted = $objInfoRestrictedPhrase ? $objInfoRestrictedPhrase->getPhraseValue(ENCODE_HTML) : 'Information restricted, please contact us for additional information.';
              echo("<span class='ccardserieslist'>{$strInfoRestricted}</span><br/>\n");
            }
          }
        }
        $_ARCHON->PublicInterface->DisableTheme = $DisableTheme;
        ?>
      </div>
    <?php
    }
    ?>
  </div>
</div>
<div class="col-md-6">
<h1 id='titleheader'><?php echo $_ARCHON->PublicInterface->Title; ?></h1>

<div id="ccardpublic" class='mdround'>  <!-- begin div ccardcontents -->
<?php

/**
 * Creator
 */
if (!empty($objCollection->Creators)) {
  ?>
  <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                        onclick="toggleDisplay('CollectionCreators'); return false;"><img
          id='CollectionCreatorsImage'
          src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
          alt='expand icon'/>
        <?php echo $_ARCHON->getPhrase('creators', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?>
      </a></span>

    <div class='ccardshowlist' style='display:none'
         id='CollectionCreatorsResults'>
      <?php echo($_ARCHON->createStringFromCreatorArray($objCollection->Creators, '<br/>', LINK_TOTAL, TRUE)); ?>
    </div>
  </div>
<?php
}

/**
 * ID
 */
if ($objCollection->Classification) {
  ?>
  <div class='ccardcontent'><span
      class='ccardlabel'><?php echo $_ARCHON->getPhrase('collection_id', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
        ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->Classification->toString(LINK_NONE, TRUE, FALSE, TRUE, FALSE)); ?> <?php echo($objCollection->getString('CollectionIdentifier')); ?>
  </div>
<?php
}

/**
 * ARK ID
 */
if ($objCollection->ArkID) {
  ?>
  <div class='ccardcontent'><span
      class='ccardlabel'><?php echo $_ARCHON->getPhrase('ark_id', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
        ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->ArkID); ?>
  </div>
<?php
}

/**
 * Extents
 */
if ($objCollection->Extent) {
  ?>
  <div class='ccardcontent'><span
      class='ccardlabel'><?php echo $_ARCHON->getPhrase('extent', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
        ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo (preg_replace('/\.(\d)0/', ".$1", $objCollection->getString('Extent'))) . " "; ?><?php echo(is_null($objCollection->ExtentUnit) ? '' : $objCollection->ExtentUnit->toString()); ?>
  </div>
<?php
}
if ($objCollection->AltExtentStatement) {
  ?>
  <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                        onclick="toggleDisplay('CollectionAltExtent'); return false;"><img
          id='CollectionAltExtentImage'
          src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
          alt='expand icon'/>
        <?php echo $_ARCHON->getPhrase('coll_alt_extents', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?>
      </a></span>

    <div class='ccardshowlist' style='display:none'
         id='CollectionAltExtentResults'>
      <?php echo($objCollection->AltExtentStatement); ?>
    </div>
  </div>
<?php
}

/**
 * Abstract
 */
if ($objCollection->Abstract) {
  ?>
  <div class='ccardcontent'>
    <div id='CollectionAbstractResults'>
      <?php echo($objCollection->getString('Abstract')); ?>
    </div>
  </div>
<?php
}

/**
 * Scope and Contents
 */
if ($objCollection->Scope || !empty($objCollection->Content) || ($objCollection->DigitalContent || $containsImages) || !empty($objCollection->OtherURL)) {
  ?>
  <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                        onclick="toggleDisplay('scopeAndContents'); return false;"><img
          id='scopeAndContentsImage'
          src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
          alt='expand icon'/> <?php echo $_ARCHON->getPhrase('scope_and_contents', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>

    <div class='ccardshowlist' style='display: none'
         id='scopeAndContentsResults'>
      <?php
      if ($objCollection->Scope) {
        ?>
        <div
          class='ccardcontent'><?php echo($objCollection->getString('Scope')); ?></div>
      <?php
      }
      ?>
    </div>
  </div>
<?php
}

/**
 * Biography / History Note
 */
if ($objCollection->BiogHist) {
  ?>
  <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                        onclick="toggleDisplay('CollectionBiogHist'); return false;"><img
          id='CollectionBiogHistImage'
          src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
          alt='expand icon'/>
        <?php echo $_ARCHON->getPhrase('bio_historical_notes', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?>
      </a></span>

    <div class='ccardshowlist' style='display:none'
         id='CollectionBiogHistResults'>
      <?php
      echo($objCollection->getString('BiogHist'));
      if ($objCollection->BiogHistAuthor) {
        echo(" <span class='bold'>Author:</span> " . $objCollection->getString('BiogHistAuthor'));
      }
      ?>
    </div>
  </div>
<?php
}

/**
 * Other Finding Aid
 */
if (!empty($objCollection->OtherURL)) {
  $onclick = ($_ARCHON->config->GACode && $_ARCHON->config->GACollectionsURL) ? "onclick='javascript: pageTracker._trackPageview(\"{$_ARCHON->config->GACollectionsURL}\");'" : "";
  ?>
  <div class='ccardcontent'><span
      class='ccardlabel'><?php echo $_ARCHON->getPhrase('other_find_aid', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
        ->getPhraseValue(ENCODE_HTML); ?></span> <a
      href='<?php echo($objCollection->getString('OtherURL')); ?>' <?php echo($onclick); ?>><?php echo($objCollection->getString('OtherURL')); ?></a>
  </div>
<?php
}

/**
 * Statement on Access
 */
if ($objCollection->AccessRestrictions) {
  ?>
  <div class='ccardcontent'><span
      class='ccardlabel'><?php echo $_ARCHON->getPhrase('statement_on_access', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
        ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->getString('AccessRestrictions')); ?>
  </div>
<?php
}

/**
 * More Information
 */
?>
<h2><?php echo $_ARCHON->getPhrase('more_information', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
    ->getPhraseValue(ENCODE_HTML); ?></h2>

<div class="ccardshowlist">
  <?php
  if ($objCollection->Arrangement) {
    ?>
    <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                          onclick="toggleDisplay('CollectionArrangement'); return false;"><img
            id='CollectionArrangementImage'
            src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
            alt='expand icon'/>
          <?php echo $_ARCHON->getPhrase('arrangement', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
            ->getPhraseValue(ENCODE_HTML); ?>
        </a></span>

      <div class='ccardshowlist' style='display:none'
           id='CollectionArrangementResults'>
        <?php echo($objCollection->getString('Arrangement')); ?>
      </div>
    </div>
  <?php
  }

  if ($objCollection->PreferredCitation) {
    ?>
    <div class='ccardcontent'><span
        class='ccardlabel'><?php echo $_ARCHON->getPhrase('preferred_citation', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></span> <?php
      echo($objCollection->getString('PreferredCitation')); ?></div>
  <?php
  }
  if ($objCollection->AcquisitionSource || $objCollection->AcquisitionMethod) {
    ?>
    <div class='ccardcontent'><span
        class='ccardlabel'><?php echo $_ARCHON->getPhrase('acquisition_note', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?> </span>
      <?php
      if ($objCollection->AcquisitionSource) {
        echo("&nbsp;<em>Source:</em> " . $objCollection->getString('AcquisitionSource') . ".<br/>");
      }
      if ($objCollection->AcquisitionMethod) {
        echo($objCollection->getString('AcquisitionMethod'));
      }
      ?>
    </div>
  <?php
  }

  if ($objCollection->AcquisitionDate || $objCollection->AccrualInfo) {
    ?>
    <div class='ccardcontent'><span
        class='ccardlabel'><?php echo $_ARCHON->getPhrase('acquired', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></span>
      <?php
      if ($objCollection->AcquisitionDate) {

        if ($objCollection->AcquisitionDateMonth <> "00") {
          echo($objCollection->AcquisitionDateMonth . '/');
        }
        if ($objCollection->AcquisitionDateDay <> "00") {
          echo($objCollection->AcquisitionDateDay . '/');
        }
        echo($objCollection->AcquisitionDateYear . ".  ");
      }
      if ($objCollection->AccrualInfo) {
        echo($objCollection->getString('AccrualInfo'));
      }
      ?>
    </div>
  <?php
  }

  if ($objCollection->ProcessingInformation) {
    ?>
    <div class='ccardcontent'><span
        class='ccardlabel'><?php echo $_ARCHON->getPhrase('processing_note', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></span> <?php
      echo($objCollection->getString('ProcessingInformation')); ?></div>
  <?php
  }

  if (!empty($objCollection->Languages)) {
    ?>
    <div class='ccardcontent'><span
        class='ccardlabel'><?php echo $_ARCHON->getPhrase('languages_of_materials', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>

      <div
        class='ccardshowlist'><?php echo($_ARCHON->createStringFromLanguageArray($objCollection->Languages, "<br/>\n", LINK_TOTAL)); ?></div>
    </div>
  <?php
  }
  ?>
</div>
<?php

/**
 * OTHER
 *
 */
if (!empty($objCollection->AcquisitionDate) || !empty($objCollection->AccrualInfo) || !empty($objCollection->AccessRestrictions) || !empty($objCollection->UseRestrictions) || !empty($objCollection->PhysicalAccessNote) || !empty($objCollection->TechnicalAccessNote) || !empty($objCollection->AcquisitionSource) || !empty($objCollection->AcquisitionMethod) || !empty($objCollection->AppraisalInformation) || !empty($objCollection->OrigCopiesNote) || !empty($objCollection->OrigCopiesURL) || !empty($objCollection->RelatedMaterials) || !empty($objCollection->RelatedMaterialsURL) || !empty($objCollection->RelatedPublications) || !empty($objCollection->PreferredCitation) || !empty($objCollection->ProcessingInfo) || !empty($objCollection->RevisionHistory) || !empty($objCollection->MaterialType)) {
  ?>
  <hr/>
  <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                        onclick="toggleDisplay('otherinformation'); return false;"><img
          id='otherinformationImage'
          src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
          alt='expand icon'/> <?php echo $_ARCHON->getPhrase('other_information', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>

    <div class='ccardshowlist' style='display:none'
         id='otherinformationResults'>
      <?php
      if ($objCollection->PredominantDates) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('predominant_dates', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->PredominantDates); ?>
        </div>
      <?php
      }

      if (!empty($objCollection->Repository) && ($objCollection->Repository != $_ARCHON->Repository)) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('repository', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo $objCollection->Repository->getString('Name'); ?>
        </div>
      <?php
      }

      if ($objCollection->UseRestrictions) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('rights', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->getString('UseRestrictions')); ?>
        </div>
      <?php
      }

      if ($objCollection->PhysicalAccess) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('access_notes', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?> </span><?php echo($objCollection->getString('PhysicalAccess')); ?>
        </div>
      <?php
      }

      if ($objCollection->TechnicalAccess) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('technical_notes', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?> </span><?php echo($objCollection->getString('TechnicalAccess')); ?>
        </div>

      <?php
      }

      if ($objCollection->AppraisalInformation) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('appraisal_notes', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->getString('AppraisalInformation')); ?>
        </div>
      <?php
      }

      if ($objCollection->OrigCopiesNote || $objCollection->OrigCopiesURL) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('other_formats', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span>
          <?php
          if ($objCollection->OrigCopiesNote) {
            echo($objCollection->getString('OrigCopiesNote'));
          }
          if ($objCollection->OrigCopiesURL) {
            echo("<br/>For more information please see <a href='{$objCollection->getString('OrigCopiesURL')}'>{$objCollection->getString('OrigCopiesURL')}</a>.");
          }
          ?>
        </div>
      <?php
      }


      if ($objCollection->RelatedPublications) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('related_publications', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->getString('RelatedPublications')); ?>
        </div>
      <?php
      }


      if ($objCollection->RevisionHistory) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('finding_aid_revisions', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->getString('RevisionHistory')); ?>
        </div>
      <?php
      }

      if ($objCollection->MaterialType) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('collection_material_type', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></span> <?php echo($objCollection->MaterialType->toString()); ?>
        </div>
      <?php
      }


      if (!empty($objCollection->OtherNote)) {
        ?>
        <div class='ccardcontent'><span
            class='ccardlabel'><?php echo $_ARCHON->getPhrase('other_note', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?> </span><?php echo($objCollection->getString('OtherNote')); ?>
        </div>
      <?php
      }

      if ($objCollection->DigitalContent) {
        ?>
        <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                              onclick="toggleDisplay('docsandfiles'); return false;"><img
                id='docsandfilesImage'
                src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
                alt='expand icon'/> <?php echo $_ARCHON->getPhrase('online_docs_files', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
                ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>

          <div class='ccardshowlist' style="display: none;"
               id="docsandfilesResults">
            <?php
            if ($objCollection->DigitalContent) {
              echo("<br/><span class='bold'>Documents and Files:</span><br/>&nbsp;" . $_ARCHON->createStringFromDigitalContentArray($objCollection->DigitalContent, "<br/>\n&nbsp;", LINK_TOTAL));
            }
            ?>
          </div>
        </div>
      <?php
      }

      if (!empty($arrDisplayAccessions)) {
        ?>
        <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                              onclick="toggleDisplay('accessions'); return false;"><img
                id='accessionsImage'
                src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
                alt='expand icon'/> <?php echo $_ARCHON->getPhrase('unprocessed_materials', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
                ->getPhraseValue(ENCODE_HTML); ?><?php
              if ($_ARCHON->Security->verifyPermissions(MODULE_COLLECTIONS, READ)) {
                echo $_ARCHON->getPhrase('processed_accessions', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
                  ->getPhraseValue(ENCODE_HTML);
              }
              ?></a></span><br/>

          <div class='ccardshowlist' style='display: none'
               id='accessionsResults'>
            <?php
            foreach ($arrDisplayAccessions as $objAccession) {
              echo($objAccession->toString(LINK_EACH) . "<br/>\n");
              $ResultCount++;
            }
            ?>
          </div>
        </div>
      <?php
      }

      if ($objCollection->Books) {
        ?>
        <div class='ccardcontent'><span class='bold'><a href='#'
                                                        onclick="toggleDisplay('LinkedBooks'); return false;"><img
                id='LinkedBooksImage'
                src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
                alt='expand icon'/> <?php echo $_ARCHON->getPhrase('books', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
                ->getPhraseValue(ENCODE_HTML); ?> </a></span><br/>

          <div class='ccardshowlist' style='display: none'
               id='LinkedBooksResults'><?php echo($_ARCHON->createStringFromBookArray($objCollection->Books, "<br/>\n", LINK_TOTAL)); ?></div>
        </div>

      <?php
      }

      ?>
    </div>
  </div> <!-- ending admininfo content -->
<?php
}

?>
</div>
<!-- end ccardpublic -->
<?php

/**
 * Staff Section
 */
if ($_ARCHON->Security->verifyPermissions(MODULE_COLLECTIONS, READ)) {
  ?>
  <div id='ccardstaff' class='mdround'>
    <h2><?php echo $_ARCHON->getPhrase('staff_info', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
        ->getPhraseValue(ENCODE_HTML); ?></h2>

    <div class='ccardcontents'>
      <span
        class='ccardlabel'><?php echo $_ARCHON->getPhrase('storage_locations', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
          ->getPhraseValue(ENCODE_HTML); ?></span>
      <?php
      if (!empty($objCollection->LocationEntries)) {
        ?>
        <table id='locationtable' border='1'>
          <tr>
            <th>Content</th>
            <th>Location</th>
            <th>Range</th>
            <th>Section</th>
            <th>Shelf</th>
            <th>Extent</th>
          </tr>
          <tr>
            <td>
              <?php echo($_ARCHON->createStringFromLocationEntryArray($objCollection->LocationEntries, '&nbsp;</td></tr><tr><td>', LINK_EACH, false, '&nbsp;</td><td>')); ?>
            </td>
          </tr>
        </table>
      <?php
      }
      else {
        ?>
        No locations are listed for this record series.
      <?php
      }
      ?>
    </div>

    <div class="ccardcontents"><br/><span class='ccardlabel'>Show this record as:</span><br/><br/>
      <a
        href='?p=collections/ead&amp;id=<?php echo($objCollection->ID); ?>&amp;templateset=ead&amp;disabletheme=1&amp;output=<?php echo(formatFileName($objCollection->getString('SortTitle', 0, false, false))); ?>'>EAD</a><br/>
      <a href='?p=collections/marc&amp;id=<?php echo($objCollection->ID); ?>'>MARC</a><br/>
      <a
        href='?p=collections/controlcard&amp;id=<?php echo($objCollection->ID); ?>&amp;templateset=kardexcontrolcard&amp;disabletheme=1'>5
        by 8 Kardex</a><br/>
      <a
        href='?p=collections/controlcard&amp;id=<?php echo($objCollection->ID); ?>&amp;templateset=draftcontrolcard&amp;disabletheme=1'>Review
        copy/draft</a>
    </div>
  </div>

<?php
}

?>
</div>
<div class="col-md-3">
  <?php
  if ($containsImages) {
    ?>
    <div class='ccardshowlist' id="digitalcontentResults">
      <?php foreach ($collectionImages as $img) {
        echo $img;
      } ?>
    </div>
  <?php
  }

  if (!empty($objCollection->Subjects)) {
    $GenreSubjectTypeID = $_ARCHON->getSubjectTypeIDFromString('Genre/Form of Material');

    foreach ($objCollection->Subjects as $objSubject) {
      if ($objSubject->SubjectTypeID == $GenreSubjectTypeID) {
        $arrGenres[$objSubject->ID] = $objSubject;
      }
      else {
        $arrSubjects[$objSubject->ID] = $objSubject;
      }
    }

    if (!empty($arrSubjects)) {
      ?>
      <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                            onclick="toggleDisplay('subjects'); return false;"><img
              id='subjectsImage'
              src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
              alt='expand icon'/> <?php echo $_ARCHON->getPhrase('subjects', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>

        <div class='ccardshowlist' style='display: none'
             id='subjectsResults'><?php echo($_ARCHON->createStringFromSubjectArray($arrSubjects, "<br/>\n", LINK_TOTAL)); ?></div>
      </div>
    <?php
    }
    if (!empty($arrGenres)) {
      ?>
      <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                            onclick="toggleDisplay('genres'); return false;"><img
              id='genresImage'
              src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
              alt='expand icon'/> <?php echo $_ARCHON->getPhrase('forms_of_material', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
              ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>

        <div class='ccardshowlist' style='display: none'
             id='genresResults'><?php echo($_ARCHON->createStringFromSubjectArray($arrGenres, "<br/>\n", LINK_TOTAL)); ?></div>
      </div>
    <?php
    }
  }

  if ($objCollection->RelatedMaterials || $objCollection->RelatedMaterialsURL) {
    ?>
    <div class='ccardcontent'><span class='ccardlabel'><a href='#'
                                                          onclick="toggleDisplay('relatedMats'); return false;"><img
            id='relatedMatsImage'
            src='<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/plus.gif'
            alt='expand icon'/> <?php echo $_ARCHON->getPhrase('related_materials', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)
            ->getPhraseValue(ENCODE_HTML); ?></a></span><br/>

      <div class='ccardshowlist' style='display: none' id='relatedMatsResults'>
        <?php
        if ($objCollection->RelatedMaterials) {
          echo($objCollection->getString('RelatedMaterials'));
        }
        if ($objCollection->RelatedMaterialsURL) {
          echo("<br/>For more information please see <a href='{$objCollection->getString('RelatedMaterialsURL')}'>{$objCollection->getString('RelatedMaterialsURL')}</a>.");
        }
        ?>
      </div>
    </div>
  <?php
  }

  ?>
</div>
</div>
