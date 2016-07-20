<?php
/**
 * Collection-level template for finding aid output
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
 * @author Chris Rishel, Bill Parod, Paul Sorensen, Chris Prom
 */
isset($_ARCHON) or die();

//$_ARCHON->PublicInterface->EscapeXML=false;
//$_ARCHON->AdministrativeInterface->EscapeXML=false;

$path = preg_replace('/[\w.]+php/u', '', $_SERVER['SCRIPT_NAME']);
// to avoid issues with getString if repository does not exist
$objCollection->Repository = $objCollection->Repository ? $objCollection->Repository : New Repository();
?>
<?php
if ($objCollection->Enabled) {
   $audience = "external";
} else {
   $audience = "internal";
}

$normalDate = $objCollection->getNormalDate();
// Get repository code

if ($objCollection->RepositoryID) {
   if (!$objCollection->Repository || $objCollection->Repository->ID != $objCollection->RepositoryID) {
      $objCollection->Repository = New Repository($objCollection->RepositoryID);
      $objCollection->Repository->dbLoad();
   }
}

$ark_identifier = '';
$ark_url = '';
if ($objCollection->ArkID) {
  $ark_identifier = 'identifier="'.$objCollection->ArkID.'"';
  $ark_url = 'url="http://archiveswest.orbiscascade.org/ark:/'.$objCollection->ArkID.'"';
}

$collectionidentifier = $objCollection->CollectionIdentifier;
echo '<?xml version="1.0" encoding="utf-8"?>'."\n";
?>
<!DOCTYPE ead PUBLIC "+//ISBN 1-931666-00-8//DTD ead.dtd (Encoded Archival Description (EAD) Version 2002)//EN" "ead.dtd">
<ead>
  <eadheader langencoding="iso639-2b" countryencoding="iso3166-1" dateencoding="iso8601" repositoryencoding="iso15511" scriptencoding="iso15924" relatedencoding="dc">
      <eadid countrycode="us" encodinganalog="identifier" mainagencycode="orcs" <?php echo $ark_identifier.' ' .$ark_url; ?>>ORE<?php echo(strtolower($collectionidentifier)); ?>.xml</eadid>
      <filedesc>
      <?php
      if ($objCollection->Title) {
      ?>
            <titlestmt>
               <titleproper encodinganalog="title">Guide to the <?php echo(bbcode_ead_encode($objCollection->getString('Title', 0, false, false)) . "\n"); ?>
              <?php
                if ($objCollection->InclusiveDates) {
              ?>
                   <date encodinganalog="date" era="ce" calendar="gregorian" normal="<?php echo $normalDate; ?>"><?php echo bbcode_ead_encode($objCollection->getString('InclusiveDates', 0, false, false)); ?></date>
              <?php
                }
              ?>
               </titleproper>
               <titleproper type="filing" altrender="nodisplay"><?php echo(bbcode_ead_encode($objCollection->getString('SortTitle', 0, false, false))); ?></titleproper>
              <?php
                 if ($objCollection->FindingAidAuthor) {
              ?>
                  <author encodinganalog="creator">Finding Aid Authors: <?php echo(bbcode_ead_encode($objCollection->getString('FindingAidAuthor', 0, false, false))); ?>.</author>
              <?php
                 }
              ?>
            </titlestmt>
<?php
      }
?>
         <publicationstmt>
           <publisher encodinganalog="publisher">Oregon State University Libraries, Special Collections and Archives Research Center</publisher>
            <?php
            if ($objCollection->PublicationNote) {
            ?>
               <publisher encodinganalog="260$b"><?php echo(bbcode_ead_encode($objCollection->Repository->getString('Name', 0, false, false))); ?></publisher>
         <?php
            }

            if ($objCollection->getPublicationDate()) {
               $date = $objCollection->getPublicationDate();
         ?>
               <date encodinganalog="date" era="ce" calendar="gregorian" normal="<?php echo($date); ?>"><?php echo($date); ?></date>

            <?php
            }

            if ($objCollection->Repository->Address) {
            ?>
               <address>
                <addressline><?php echo(bbcode_ead_encode($objCollection->Repository->getString('Address', 0, false, false))); ?></addressline>
            <?php
               if ($objCollection->Repository->Address2) {
            ?>
                <addressline><?php echo(bbcode_ead_encode($objCollection->Repository->getString('Address2', 0, false, false))); ?></addressline>
            <?php
               }

               if ($objCollection->Repository->City) {
                  if ($objCollection->Repository->ZIPPlusFour) {
                     $zipcode = bbcode_ead_encode($objCollection->Repository->getString('ZIPCode', 0, false, false) . '-' . $objCollection->Repository->getString('ZIPPlusFour', 0, false, false));
                  } else {
                     $zipcode = bbcode_ead_encode($objCollection->Repository->getString('ZIPCode', 0, false, false));
                  }
            ?>
                <addressline><?php echo(bbcode_ead_encode($objCollection->Repository->getString('City', 0, false, false) . ', ' . $objCollection->Repository->getString('State', 0, false, false) . ', ' . $zipcode)); ?></addressline>
            <?php
               }

            if ($objCollection->Repository->Phone) {
              ?>
                <addressline>Phone: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('Phone', 0, false, false))); ?></addressline>
              <?php
            }

            if ($objCollection->Repository->Email) {
              ?>
                <addressline>Email: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('Email', 0, false, false))); ?></addressline>
              <?php
            }

            if ($objCollection->Repository->URL) {
            ?>
                <addressline>Web: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('URL', 0, false, false))); ?></addressline>
            <?php
            }

             if ($objCollection->Repository->Fax) {
             ?>
                <addressline>Fax: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('Fax', 0, false, false))); ?></addressline>
             <?php
             }
            ?>
            </address>
               <?php
            }
               ?>

         </publicationstmt>
      </filedesc>
      <profiledesc>
         <creation>This finding aid was encoded in EAD by Archon <?php
           echo(bbcode_ead_encode($_ARCHON->getString('Version', 0, false, false))); ?> from an SQL database source on <date
             type="encoded" normal="<?php echo(date('Y-m-d', time())); ?>" era="ce" calendar="gregorian"><?php echo(date('F jS, Y', time())); ?></date>. Encoding was modified by Elizabeth Nielsen for Archives West compliance.</creation>
               <?php
               if ($objCollection->FindingLanguageID) {
               ?>
          <language>Finding aid written in <language encodinganalog="language" langcode="<?php echo(bbcode_ead_encode($objCollection->FindingLanguage->getString('LanguageShort', 0, false, false))); ?>" scriptcode="latn"><?php echo($objCollection->FindingLanguage->getString('LanguageLong', 0, false)); ?></language></language>
               <?php
               }
               ?>
          <descrules>Finding aid based on DACS (<title render="italic">Describing Archives: A Content Standard, 2nd Edition</title>).</descrules>
      </profiledesc>
      <?php
      if (!empty($objCollection->RevisionHistory)) {
      ?>
      <revisiondesc>
        <change encodinganalog="583">
        <date type="encoded" normal="<?php echo(date('Y-m-d', time())); ?>"><?php echo(date('F jS, Y', time())); ?></date>
        <?php
        $revisionDescParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('RevisionHistory', 0, false, false)));

        if (!empty($revisionDescParagraphs)) {
          foreach ($revisionDescParagraphs as $paragraph) {
            if (trim($paragraph)) {
          ?>
        <item><?php echo(trim($paragraph)); ?></item>
        <?php
            }
          }
        }
        ?>
        </change>
      </revisiondesc>
    <?php
      }
    ?>
  </eadheader>

      <archdesc level="collection" type="guide" relatedencoding="marc21">
        <did>
            <?php
              if (!empty($objCollection->PrimaryCreator)) {
                $objCreator = $objCollection->PrimaryCreator;
            ?>
          <origination>
            <?php
                if ($objCreator->CreatorType->CreatorType == 'Corporate Name') {
                  $type = 'corp';
                  $encodinganalog = ' encodinganalog="110"';
                  $string = bbcode_ead_encode($objCreator->getString('Name', 0, false, false));
                  $normal = $string;
                } else {
                  $encodinganalog = ' encodinganalog="100"';
                  $string = bbcode_ead_encode($objCreator->getString('Name', 0, false, false));

                  if ($objCreator->CreatorType->CreatorType == 'Personal Name') {
                    $type = 'pers';
                  } else {
                    $type = 'fam';
                  }
                  $normal = $string;
                  if ($objCreator->Dates) {
                    $string .= ", " . bbcode_ead_encode($objCreator->getString('Dates', 0, false, false));
                  }
                }
                $source = $objCreator->CreatorSource->getString('SourceAbbreviation');
            ?>
            <<?php echo($type); ?>name role="creator"<?php echo($encodinganalog); ?>><?php echo($string); ?></<?php echo($type); ?>name>
          </origination>
            <?php
              }
            ?>

        <?php
          if ($objCollection->Title) {
        ?>
          <unittitle encodinganalog="245$a"><?php echo(bbcode_ead_encode($objCollection->getString('Title', 0, false, false))); ?></unittitle>
        <?php
          }

        if ($objCollection->InclusiveDates) {
          $normal = ($normalDate) ? ' normal="' . $normalDate . '"' : '';
        ?>
          <unitdate encodinganalog="245$f" type="inclusive"<?php echo($normal); ?> era="ce" calendar="gregorian"><?php echo(bbcode_ead_encode($objCollection->getString('InclusiveDates', 0, false, false))); ?></unitdate>
        <?php
          }

          if ($objCollection->PredominantDates) {
            $normal = ($normalDate) ? ' normal="' . $normalDate . '"' : '';
        ?>
          <unitdate encodinganalog="245$g" type="bulk" era="ce" calendar="gregorian"<?php echo($normal); ?>><?php echo(bbcode_ead_encode($objCollection->getString('PredominantDates', 0, false, false))); ?></unitdate>
        <?php
          }
        ?>
          <unitid encodinganalog="099" repositorycode="orcs" countrycode="us"><?php echo $objCollection->Classification->ClassificationIdentifier . ' ' . $objCollection->CollectionIdentifier ; ?></unitid>
        <?php

          if ($objCollection->Extent && $objCollection->ExtentUnit->ExtentUnit) {
        ?>
          <physdesc>
            <extent encodinganalog="300$a"><?php echo(bbcode_ead_encode($objCollection->getString('Extent', 0, false, false))); ?> <?php echo($objCollection->ExtentUnit->toString()); ?>, including <?php echo(bbcode_ead_encode($objCollection->getString('AltExtentStatement', 0, false, false))); ?></extent>
          </physdesc>
        <?php
          }

        if (!empty($objCollection->Languages)) {

          $langMaterial = '';
          $languages = count($objCollection->Languages);
          foreach ($objCollection->Languages as $objLanguage) {
            $langMaterial .= "<language encodinganalog=\"546\" langcode=\"{$objLanguage->getString('LanguageShort', 0, false)}\">{$objLanguage->getString('LanguageLong', 0, false)}</language>";
            $languages--;
            if ($languages > 0) {
              $langMaterial .= ' and ';
            }
          }
            ?>
          <langmaterial>Materials in <?php echo $langMaterial; ?>.</langmaterial>
            <?php
        }
            ?>
            <repository encodinganalog="852$b">
            <?php
               if ($objCollection->Repository->Name) {
            ?>
                  <corpname><?php echo(bbcode_ead_encode($objCollection->Repository->getString('Name', 0, false, false))); ?></corpname>
            <?php
               }

               if ($objCollection->Repository->Address) {
            ?>
                  <address>
                     <addressline><?php echo(bbcode_ead_encode($objCollection->Repository->getString('Address', 0, false, false))); ?></addressline>
            <?php
                  if ($objCollection->Repository->Address2) {
            ?>
                        <addressline><?php echo(bbcode_ead_encode($objCollection->Repository->getString('Address2', 0, false, false))); ?></addressline>
            <?php
                  }

                  if ($objCollection->Repository->City) {
                     if ($objCollection->Repository->ZIPPlusFour) {
                        $zipcode = bbcode_ead_encode($objCollection->Repository->getString('ZIPCode', 0, false, false) . '-' . $objCollection->Repository->getString('ZIPPlusFour', 0, false, false));
                     } else {
                        $zipcode = bbcode_ead_encode($objCollection->getString('ZIPCode', 0, false, false));
                     }
            ?>
                           <addressline><?php echo(bbcode_ead_encode($objCollection->Repository->getString('City', 0, false, false) . ', ' . $objCollection->Repository->getString('State', 0, false, false) . ', ' . $zipcode)); ?></addressline>
         <?php
                  }

            if ($objCollection->Repository->Phone) {
              ?>
              <addressline>Phone: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('Phone', 0, false, false))); ?></addressline>
              <?php
            }
            if ($objCollection->Repository->Email) {
              ?>
              <addressline>Email: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('Email', 0, false, false))); ?></addressline>
              <?php
            }
                  if ($objCollection->Repository->URL) {
         ?>
                           <addressline>Web: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('URL', 0, false, false))); ?></addressline>
         <?php
                  }



                  if ($objCollection->Repository->Fax) {
         ?>
                           <addressline>Fax: <?php echo(bbcode_ead_encode($objCollection->Repository->getString('Fax', 0, false, false))); ?></addressline>
         <?php
                  }
         ?>
                     </address>
<?php
               }
?>
            </repository>
            <?php
               if ($objCollection->Abstract) {
            ?>
                  <abstract encodinganalog="5203_"><?php echo(str_replace(NEWLINE, '<lb/>', bbcode_ead_encode($objCollection->getString('Abstract', 0, false, false)))); ?></abstract>
         <?php
               }
         ?>
            <?php
               if (!empty($objCollection->OtherNote) || !empty($objCollection->OtherURL)) {
                  $arrOtherNoteParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('OtherNote', 0, false, false)));
            ?>
               <note encodinganalog="500">
                  <p>Other Information:</p>
            <?php
                  if (!empty($arrOtherNoteParagraphs)) {
                     foreach ($arrOtherNoteParagraphs as $paragraph) {
                        if (trim($paragraph)) {
            ?>
                           <p><?php echo(trim($paragraph)); ?></p>
               <?php
                        }
                     }
                  }

                  if ($objCollection->OtherURL) {
               ?>
                  <p>Additional information may be found at <?php echo(bbcode_ead_encode($objCollection->getString('OtherURL', 0, false, false))); ?></p>
               <?php
                  }
               ?>
            </note>
               <?php
               }
               ?>

      </did>
      <!--COLLECTION LEVEL METADATA: -->
<?php
// NOTE: THE FOLLOWING LINE ONLY NEEDS TO BE INSERTED ONCE (FOR THE PRIMARY CREATOR)

               $bioghist_a = false;
               $bioghist_b = false;

               if ($objCollection->PrimaryCreator) {
                  if ($objCollection->PrimaryCreator->BiogHist) {
                     $bioghist_a = true;
                  }
               }

               if ($objCollection->BiogHist) {
                  $bioghist_b = true;
               }

               $use_subfields = false;

               if ($bioghist_a && $bioghist_b) {
                  $use_subfields = true;
?>
               <?php
               }

               if ($bioghist_a) {
                  if ($objCollection->PrimaryCreator->CreatorType->CreatorType == "Corporate Name") {
                     $head = "Historical Note";
                  } elseif ($objCollection->PrimaryCreator->CreatorType->CreatorType == "Family Name") {
                     $head = "Family History";
                  } else {
                     $head = "Biographical Information";
                  }

                  $enc = ($use_subfields) ? '545$a' : '5450_';
               ?>
        <bioghist encodinganalog="<?php echo($enc); ?>"><head><?php echo($head); ?>:</head>
<?php
                  $arrBiogHistParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->PrimaryCreator->getString('BiogHist', 0, false, false)));

                  if (!empty($arrBiogHistParagraphs)) {
                     foreach ($arrBiogHistParagraphs as $paragraph) {
                        if (trim($paragraph)) {
?>
                              <p><?php echo(trim($paragraph)); ?></p>
         <?php
                        }
                     }
                  }
         ?>
        </bioghist>
         <?php
               }

               if ($bioghist_b) {
                  $head = "Administrative History";
                  $enc = ($use_subfields) ? '545$b' : '5451_';
         ?>
        <bioghist encodinganalog="<?php echo($enc); ?>"><head><?php echo($head); ?>:</head>
            <?php
                  $arrBiogHistParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('BiogHist', 0, false, false)));

                  if (!empty($arrBiogHistParagraphs)) {
                     foreach ($arrBiogHistParagraphs as $paragraph) {
                        if (trim($paragraph)) {
            ?>
            <p><?php echo(trim($paragraph)); ?></p>
            <?php
                        }
                     }
                  }
            ?>
        </bioghist>
            <?php
               }
?>
            <!-- CONTROLLED ACCESS / SUBJECT TERMS -->
      <?php
               if (!empty($objCollection->Subjects)) {
                  foreach ($objCollection->Subjects as $objSubject) {
                     $arrTraversal = $_ARCHON->traverseSubject($objSubject->ID);
                     $objParent = reset($arrTraversal);

                     $arrEADSubjects[$objParent->SubjectType->ID][$objSubject->ID] = $objSubject->toString(LINK_NONE, true, ' -- ');
                  }
               }


               if (!empty($arrEADSubjects)) {
      ?>
        <controlaccess>
      <?php

                 if (!empty($objCollection->Creators)) {
      ?>
         <controlaccess>
      <?php
                     foreach ($objCollection->Creators as $objCreator) {

                       if (empty($objCollection->PrimaryCreator) || ($objCollection->PrimaryCreator->ID != $objCreator->ID)) {
                         if ($objCreator->CreatorType->CreatorType == 'Corporate Name') {
                           $type = 'corp';
                           $encodinganalog = ' encodinganalog="110"';
                           $string = bbcode_ead_encode($objCreator->getString('Name', 0, false, false));
                           $normal = $string;
                         } else {
                           $encodinganalog = ' encodinganalog="100"';
                           $string = bbcode_ead_encode($objCreator->getString('Name', 0, false, false));

                           if ($objCreator->CreatorType->CreatorType == 'Personal Name') {
                             $type = 'pers';
                           } else {
                             $type = 'fam';
                           }

                           $normal = $string;

                           if ($objCreator->Dates) {
                             $string .= ", " . bbcode_ead_encode($objCreator->getString('Dates', 0, false, false));
                           }
                         }

                         $source = $objCreator->CreatorSource->getString('SourceAbbreviation');
                         ?>
          <<?php echo($type); ?>name role="creator"<?php echo($encodinganalog); ?>><?php echo($string); ?></<?php echo($type); ?>name>
                       <?php
                       }
                     }
      ?>
        </controlaccess>
      <?php
                 }

                 $arrSubjectTypes = $_ARCHON->getAllSubjectTypes();
                  $arrSubjectSources = $_ARCHON->getAllSubjectSources();

                  foreach ($arrSubjectTypes as $objSubjectType)
                     if (!empty($arrEADSubjects[$objSubjectType->ID])) {
                        $subjects = $arrEADSubjects[$objSubjectType->ID];
      ?>
        <controlaccess>
         <?php
                        @asort($subjects);
                        @reset($subjects);

                        foreach ($subjects as $id => $subject) {
                           $encodinganalog = $objSubjectType->EncodingAnalog ? " encodinganalog=\"" . bbcode_ead_encode($objSubjectType->getString('EncodingAnalog', 0, false, false)) . "\"" : '';
                           $source = $arrSubjectSources[$objCollection->Subjects[$id]->SubjectSourceID]->EADSource ? bbcode_ead_encode($arrSubjectSources[$objCollection->Subjects[$id]->SubjectSourceID]->getString('EADSource', 0, false, false)) : 'local';
                           $tag = bbcode_ead_encode($objSubjectType->getString('EADType', 0, false, false));
                           if (strpos($tag, 'name')) {
                              $role = ' role="subject"';
                           } else {
                              $role = '';
                           }
         ?>
          <<?php echo(bbcode_ead_encode($objSubjectType->getString('EADType', 0, false, false)) . $encodinganalog); ?> source="<?php echo($source); ?>"<?php echo($role) ?>><?php echo($subject); ?></<?php echo(bbcode_ead_encode($objSubjectType->getString('EADType', 0, false, false))); ?>>
         <?php
                        }
         ?>
        </controlaccess>
<?php
                     }
?>
      </controlaccess>
            <?php
               }
            ?>
      <!-- END CONTROLLED ACCESS TERMS -->
      <!-- ADMINISTRATIVE INFORMATION -->
<?php
               $c = $objCollection;
               $admin_info = $c->AcquisitionMethod || $c->CustodialHistory || $c->AccrualInfo || $c->ProcessingInfo || $c->SeparatedMaterials || $c->AppraisalInfo ||
                       $c->UseRestrictions || $c->AccessRestrictions || $c->OrigCopiesNote || $c->PreferredCitation || $c->RelatedMaterials || $c->RelatedMaterialsURL ||
                       $c->PhysicalAccess || $c->TechnicalAccess;


               if ($admin_info) {
                  if ($objCollection->AcquisitionMethod) {
      ?>
                        <acqinfo encodinganalog="541">
                           <p><?php echo(bbcode_ead_encode($objCollection->getString('AcquisitionMethod', 0, false, false))); ?></p>
                        </acqinfo>
<?php
                  }

                  if ($objCollection->CustodialHistory) {
                     $arrCustodialHistoryParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('CustodialHistory', 0, false, false)));
?>
                     <custodhist encodinganalog="561">
         <?php
                     if (!empty($arrCustodialHistoryParagraphs)) {
                        foreach ($arrCustodialHistoryParagraphs as $paragraph) {
                           if (trim($paragraph)) {
         ?>
                                 <p><?php echo(trim($paragraph)); ?></p>
<?php
                           }
                        }
                     }
?>
                  </custodhist>
            <?php
                  }

                  if ($objCollection->AccrualInfo) {
                     $arrAccrualInfoParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('AccrualInfo', 0, false, false)));
            ?>
                  <accruals encodinganalog="584">
            <?php
                     if (!empty($arrAccrualInfoParagraphs)) {
                        foreach ($arrAccrualInfoParagraphs as $paragraph) {
                           if (trim($paragraph)) {
            ?>
                              <p><?php echo(trim($paragraph)); ?></p>
            <?php
                           }
                        }
                     }
            ?>
                     </accruals>
         <?php
                  }

                  if ($objCollection->ProcessingInfo) {
                     $arrProcessingInfoParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('ProcessingInfo', 0, false, false)));
         ?>
                        <processinfo encodinganalog="583">
<?php
                     if (!empty($arrProcessingInfoParagraphs)) {
                        foreach ($arrProcessingInfoParagraphs as $paragraph) {
                           if (trim($paragraph)) {
?>
                                    <p><?php echo(trim($paragraph)); ?></p>
      <?php
                           }
                        }
                     }
      ?>
                        </processinfo>
      <?php
                  }


                  if ($objCollection->SeparatedMaterials) {
                     $arrSepMaterialsParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('SeparatedMaterials', 0, false, false)));
      ?>
                        <separatedmaterial encodinganalog="544 0">
      <?php
                     if (!empty($arrSepMaterialsParagraphs)) {
                        foreach ($arrSepMaterialsParagraphs as $paragraph) {
                           if (trim($paragraph)) {
      ?>
                                    <p><?php echo(trim($paragraph)); ?></p>
<?php
                           }
                        }
                     }
?>
                        </separatedmaterial>
      <?php
                  }


                  if ($objCollection->AppraisalInfo) {
                     $arrAppraisalInfoParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('AppraisalInfo', 0, false, false)));
      ?>
                     <appraisal encodinganalog="583">
<?php
                     if (!empty($arrAppraisalInfoParagraphs)) {
                        foreach ($arrAppraisalInfoParagraphs as $paragraph) {
                           if (trim($paragraph)) {
?>
                                 <p><?php echo(trim($paragraph)); ?></p>
         <?php
                           }
                        }
                     }
         ?>
                        </appraisal>
      <?php
                  }

                  if ($objCollection->UseRestrictions) {
                     $arrUseRestrictParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('UseRestrictions', 0, false, false)));
      ?>
                        <userestrict encodinganalog="540">
      <?php
                     if (!empty($arrUseRestrictParagraphs)) {
                        foreach ($arrUseRestrictParagraphs as $paragraph) {
                           if (trim($paragraph)) {
      ?>
                                    <p><?php echo(trim($paragraph)); ?></p>
      <?php
                           }
                        }
                     }
      ?>
                        </userestrict>
<?php
                  }


                  if ($objCollection->AccessRestrictions) {
                     $arrAccessRestrictParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('AccessRestrictions', 0, false, false)));
?>
                     <accessrestrict encodinganalog="506">
         <?php
                     if (!empty($arrAccessRestrictParagraphs)) {
                        foreach ($arrAccessRestrictParagraphs as $paragraph) {
                           if (trim($paragraph)) {
         ?>
                                 <p><?php echo(trim($paragraph)); ?></p>
         <?php
                           }
                        }
                     }
         ?>
                  </accessrestrict>
            <?php
                  }


                  if ($objCollection->OrigCopiesNote) {
            ?>
                  <altformavail encodinganalog="530">
            <?php
                     $origCopiesNoteParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('OrigCopiesNote', 0, false, false)));

                     if (!empty($origCopiesNoteParagraphs)) {
                        foreach ($origCopiesNoteParagraphs as $paragraph) {
                           if (trim($paragraph)) {
            ?>
                                 <p><?php echo(preg_replace("/\s+/u", " ", $paragraph)); ?></p>
         <?php
                           }
                        }
                     }
         ?>
            <?php
                     if (bbcode_ead_encode($objCollection->getString('OrigCopiesURL', 0, false, false))) {
            ?>
                        <p><?php echo(bbcode_ead_encode($objCollection->getString('OrigCopiesURL', 0, false, false))); ?></p>
            <?php
                     }
            ?>
                  </altformavail>
<?php
                  }

                  if ($objCollection->PreferredCitation) {
?>
                  <prefercite encodinganalog="524">
                     <p><?php echo(bbcode_ead_encode($objCollection->getString('PreferredCitation', 0, false, false))); ?></p>
                  </prefercite>
         <?php
                  }

                  if ($objCollection->RelatedMaterials) {
                     $arrRelMaterialsParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('RelatedMaterials', 0, false, false)));
         ?>
                     <relatedmaterial encodinganalog="5441_">
            <?php
                     if (!empty($arrRelMaterialsParagraphs)) {
                        foreach ($arrRelMaterialsParagraphs as $paragraph) {
                           if (trim($paragraph)) {
            ?>
                              <p><?php echo(trim($paragraph)); ?></p>
            <?php
                           }
                        }
                     }

                     if ($objCollection->RelatedMaterialsURL) {
            ?>
                        <p>Information about related materials is available at <?php echo(bbcode_ead_encode($objCollection->getString('RelatedMaterialsURL', 0, false, false))); ?></p>
         <?php
                     }
         ?>
                     </relatedmaterial>
         <?php
                  }

                  if ($objCollection->PhysicalAccess || $objCollection->TechnicalAccess) {
         ?>
                     <phystech encodinganalog="340">
            <?php
                     if ($objCollection->PhysicalAccess) {
                        $arrPhysicalAccessParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('PhysicalAccess', 0, false, false)));

                        if (!empty($arrPhysicalAccessParagraphs)) {
                           foreach ($arrPhysicalAccessParagraphs as $paragraph) {
                              if (trim($paragraph)) {
            ?>
                                 <p><?php echo(trim($paragraph)); ?></p>
            <?php
                              }
                           }
                        }
                     }


                     if ($objCollection->TechnicalAccess) {
                        $arrTechnicalAccessParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('TechnicalAccess', 0, false, false)));

                        if (!empty($arrTechnicalAccessParagraphs)) {
                           foreach ($arrTechnicalAccessParagraphs as $paragraph) {
                              if (trim($paragraph)) {
            ?>
                                    <p><?php echo(trim($paragraph)); ?></p>
            <?php
                              }
                           }
                        }
                     }
            ?>
                  </phystech>
            <?php
                  }
               }

               if ($objCollection->Arrangement) {
                  $arrArrangementParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('Arrangement', 0, false, false)));
            ?>
               <arrangement encodinganalog="351">
         <?php
                  if (!empty($arrArrangementParagraphs)) {
                     foreach ($arrArrangementParagraphs as $paragraph) {
                        if (trim($paragraph)) {
         ?>
                        <p><?php echo(trim($paragraph)); ?></p>
            <?php
                        }
                     }
                  }
            ?>
            </arrangement>
<?php
               }

               if ($objCollection->Scope) {
?>
            <scopecontent encodinganalog="5202_">
         <?php
                  $arrScopeParagraphs = explode(NEWLINE, bbcode_ead_encode($objCollection->getString('Scope', 0, false, false)));
                  foreach ($arrScopeParagraphs as $paragraph) {
                     if (trim($paragraph)) {
         ?>
                        <p><?php echo(trim(trim($paragraph))); ?></p>
         <?php
                     }
                  }
         ?>
               </scopecontent>
            <?php
               }
            ?>
         <!-- END ADMINISTRATIVE INFORMATION -->
         <!-- END COLLECTION LEVEL METADATA -->
            <?php
               if (!empty($objCollection->Content)) {   //call templates/awead/item.inc.php to insert collection content into <dsc>
            ?>
            <!-- BEGIN SUBORDINATE COMPONENTS -->
            <dsc type="combined">
               #CONTENT#
            </dsc>
         <?php
               }
         ?>
      <!-- END SUBORDINATE COMPONENTS -->
   </archdesc>
</ead>

