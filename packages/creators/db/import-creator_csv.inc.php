<?php
/**
 * Creator importer script.
 *
 */

isset($_ARCHON) or die();

$UtilityCode = 'creator_csv';

$_ARCHON->addDatabaseImportUtility(PACKAGE_CREATORS, $UtilityCode, '3.21', array('csv'), TRUE);

if ($_REQUEST['f'] == 'import-' . $UtilityCode) {
  if (!$_ARCHON->Security->verifyPermissions(MODULE_DATABASE, FULL_CONTROL)) {
    die("Permission Denied.");
  }

  @set_time_limit(0);
  ob_implicit_flush();

  $arrFiles = $_ARCHON->getAllIncomingFiles();
  if (!empty($arrFiles)) {

    $creatorTypesMap = array();
    $creatorTypes = $_ARCHON->getAllCreatorTypes();
    foreach ($creatorTypes as $creatorType) {
      $creatorTypesMap[encoding_strtolower($creatorType->CreatorType)] = $creatorType->ID;
    }

    $creatorSourceMap = array();
    $arrCreatorSources = $_ARCHON->getAllCreatorSources();
    foreach ($arrCreatorSources as $objCreatorSource)
    {
      $creatorSourceMap[$objCreatorSource->SourceAbbreviation] = $objCreatorSource->ID;
    }

    $repositoryMap = array();
    $repositorySources = $_ARCHON->getAllRepositories();
    foreach ($repositorySources as $repositorySource) {
      $repositoryMap[$repositorySource->Name] = $repositorySource->ID;
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

          $objCreator = new Creator();
          $objCreator->Name = $arrData[0];

          $creator_type = strtolower($arrData[1]);
          if (array_key_exists($creator_type, $creatorTypesMap)) {
            $objCreator->CreatorTypeID = $creatorTypesMap[$creator_type];
          } else {
            echo "Creator Type not found: $creator_type<br>\n";
          }

          $creator_source = strtolower($arrData[2]);
          if (array_key_exists($creator_source, $creatorSourceMap)) {
            $objCreator->CreatorSourceID = $creatorSourceMap[$creator_source];
          } else {
            echo "Creator Source not found: $creator_source<br>\n";
          }

          // Repository ID
          if (!empty($arrData[3])) {
            $objCreator->RepositoryID = $repositoryMap[$arrData[3]];
          }

          // Roles - a semicolon delimited list of roles
          $roles = '';
          for ($i = 4; $i < 11; $i++) {
            if (!empty($arrData[$i])) {
              $roles .= $arrData[$i].';';
            }
          }
          if (strlen($roles) > 0) {
            // trim off the trailing ; if present
            $roles = (';' == substr($roles,strlen($roles) - 1)) ? substr($roles,0,strlen($roles) - 1) : $roles;
            $objCreator->Roles = $roles;
          }

          $objCreator->dbStore();
          if(!$objCreator->ID)
          {
            echo("Error storing subject $objCreator->Name: {$_ARCHON->clearError()}<br>\n");
            continue;
          }

          if($objCreator->ID)
          {
            echo("Imported {$objCreator->Name}.<br><br>\n\n");
          }
          flush();
        }
      }
    }
    echo("All files imported!");
  }
}
