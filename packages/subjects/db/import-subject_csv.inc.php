<?php
/**
 * Subject importer script.
 *
 */

isset($_ARCHON) or die();

$UtilityCode = 'subject_csv';

$_ARCHON->addDatabaseImportUtility(PACKAGE_SUBJECTS, $UtilityCode, '3.21', array('csv'), TRUE);

if ($_REQUEST['f'] == 'import-' . $UtilityCode) {
  if (!$_ARCHON->Security->verifyPermissions(MODULE_DATABASE, FULL_CONTROL)) {
    die("Permission Denied.");
  }

  @set_time_limit(0);
  ob_implicit_flush();

  $arrFiles = $_ARCHON->getAllIncomingFiles();
  if (!empty($arrFiles)) {
    // here is where you would setup any lookup hashes for subject types or the like if you want to pass something other than IDs
    // see the languages example below
    $arrLanguages = $_ARCHON->getAllLanguages();
    foreach ($arrLanguages as $objLanguage) {
      $arrLanguagesMap[encoding_strtolower($objLanguage->LanguageShort)] = $objLanguage->ID;
    }

    foreach ($arrFiles as $Filename => $strCSV) {
      echo("Parsing file $Filename...<br><br>\n\n");

      // Remove byte order mark if it exists.
      $strCSV = ltrim($strCSV, "\xEF\xBB\xBF");

      $arrAllData = getCSVFromString($strCSV);
      // ignore first line?
      // each $arrData is the row of CSV
      foreach ($arrAllData as $arrData) {
//        if(!empty($arrData))
//        {
//          $objSubject = new Subject();
//
//          $objSubject->Subject = reset($arrData);
//          $objSubject->Description = next($arrData);
//
//          // and so on...
//
//          $objSubject->dbStore();
//          if(!$objAccession->ID)
//          {
//            echo("Error storing subject $objSubject->Subject: {$_ARCHON->clearError()}<br>\n");
//            continue;
//          }
//
//          if($objSubject->ID)
//          {
//            echo("Imported {$objSubject->Subject}.<br><br>\n\n");
//          }
//
//          flush();
//        }
        print_r($arrData);
      }
    }
    echo("All files imported!");
  }
}
