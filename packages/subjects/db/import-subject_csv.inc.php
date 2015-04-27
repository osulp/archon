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

    $subjectTypesMap = array();
    $subjectTypes = $_ARCHON->getAllSubjectTypes();
    foreach ($subjectTypes as $subjectType) {
      $subjectTypesMap[encoding_strtolower($subjectType->SubjectType)] = $subjectType->ID;
    }

    $subjectSourcesMap = array();
    $subjectSources = $_ARCHON->getAllSubjectSources();
    foreach ($subjectSources as $subjectSource) {
      $subjectSourcesMap[encoding_strtolower($subjectSource->EADSource)] = $subjectSource->ID;
    }

    foreach ($arrFiles as $Filename => $strCSV) {
      echo("Parsing file $Filename...<br><br>\n\n");

      // Remove byte order mark if it exists.
      $strCSV = ltrim($strCSV, "\xEF\xBB\xBF");
      $arrAllData = getCSVFromString($strCSV);

      echo count($arrAllData)." entries to import.<br>\n";

      foreach ($arrAllData as $arrData) {

        // # denotes a header row, so we ignore it
        if (!empty($arrData) && '#' != substr($arrData[0],0,1)) {
          $objSubject = new Subject();
          $objSubject->Subject = $arrData[0];

          $subject_type = strtolower($arrData[1]);
          if (array_key_exists($subject_type, $subjectTypesMap)) {
            $objSubject->SubjectTypeID = $subjectTypesMap[$subject_type];
          } else {
            echo "Subject Type not found: $subject_type<br>\n";
          }

          $subject_source = strtolower($arrData[2]);
          if (array_key_exists($subject_source, $subjectSourcesMap)) {
            $objSubject->SubjectSourceID = $subjectSourcesMap[$subject_source];
          } else {
            echo "Subject Source not found: $subject_source<br>\n";
          }

          // Controlled Term
          if (!empty($arrData[3])) {
            $objSubject->Identifier = $arrData[3];
          }

          // Roles - a semicolon delimited list of roles
          if (!empty($arrData[4])) {
            $roles = $arrData[4];
            // trim off the trailing ; if present
            $roles = (';' == substr($roles,strlen($roles) - 1)) ? substr($roles,0,strlen($roles) - 1) : $roles;
            $objSubject->Roles = $roles;
          }

          $objSubject->dbStore();
          if(!$objSubject->ID)
          {
            echo("Error storing subject $objSubject->Subject: {$_ARCHON->clearError()}<br>\n");
            continue;
          }

          if($objSubject->ID)
          {
            echo("Imported {$objSubject->Subject}.<br><br>\n\n");
          }
          flush();
        }
      }
    }
    echo("All files imported!");
  }
}
