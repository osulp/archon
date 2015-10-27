<?php
/**
 * Collection Location Index importer script.
 *
 * This script takes .csv files in a defined format and creates a new Collection Location Index record for each row in the database.
 * A sample csv/excel file is provided in the archon/incoming folder, to show the necessary format.
 *
 * @package Archon
 * @subpackage AdminUI
 * @author Mike Eaton
 */

isset($_ARCHON) or die();

$UtilityCode = 'collectionlocations_csv';

$_ARCHON->addDatabaseImportUtility(PACKAGE_COLLECTIONS, $UtilityCode, '3.21', array('csv'), TRUE);

if ($_REQUEST['f'] == 'import-' . $UtilityCode) {
  if (!$_ARCHON->Security->verifyPermissions(MODULE_DATABASE, FULL_CONTROL)) {
    die("Permission Denied.");
  }

  @set_time_limit(0);

  ob_implicit_flush();

  $arrFiles = $_ARCHON->getAllIncomingFiles();

  if (!empty($arrFiles)) {
    // Locations
    $arrLocations = $_ARCHON->getAllLocations();
    foreach ($arrLocations as $objLocation) {
      $arrLocationsMap[encoding_strtolower($objLocation->Location)] = $objLocation->ID;
    }

    // Extent Units
    $arrExtentUnits = $_ARCHON->getAllExtentUnits();
    foreach ($arrExtentUnits as $objExtentUnit) {
      $arrExtentUnitsMap[encoding_strtolower($objExtentUnit->ExtentUnit)] = $objExtentUnit->ID;
    }

    /**
     * Get an array of collections so we can validate the collection ID
     */
    $arrCollections = $_ARCHON->getAllCollections();

    foreach ($arrFiles as $Filename => $strCSV) {
      echo("Parsing file $Filename...<br /><br />\n\n");

      // Remove byte order mark if it exists.
      $strCSV = ltrim($strCSV, "\xEF\xBB\xBF");

      $arrAllData = getCSVFromString($strCSV);
      foreach ($arrAllData as $arrData) {

        // Skip the header line
        if ('Collection' == $arrData[0]) {
          continue;
        }

        if (!empty($arrData)) {

          $collectionId = reset($arrData);

          $location     = strtolower(next($arrData));
          $content      = next($arrData);
          $range        = next($arrData);
          $section      = next($arrData);
          $shelf        = next($arrData);
          $extent       = next($arrData);
          $extentUnit   = strtolower(next($arrData));

          if (!array_key_exists($collectionId, $arrCollections)) {
            echo("Error storing location: collection $collectionId does not exist : {$_ARCHON->clearError()}<br />\n");
            continue;
          }

          if (!array_key_exists($location, $arrLocationsMap)) {
            echo("Error storing location: location $location does not exist : {$_ARCHON->clearError()}<br />\n");
            continue;
          }

          if (!array_key_exists($extentUnit, $arrExtentUnitsMap)) {
            echo("Error storing location: extent unit $extentUnit does not exist : {$_ARCHON->clearError()}<br />\n");
            continue;
          }

          $objLocationEntry = new LocationEntry();
          $objLocationEntry->CollectionID = $collectionId;
          $objLocationEntry->LocationID = $arrLocationsMap[$location];
          $objLocationEntry->Content = $content;
          $objLocationEntry->RangeValue = str_pad($range,2,'0',STR_PAD_LEFT);
          $objLocationEntry->Section = str_pad($section,2,'0',STR_PAD_LEFT);
          $objLocationEntry->Shelf = str_pad($shelf,2,'0',STR_PAD_LEFT);
          $objLocationEntry->Extent = $extent;
          $objLocationEntry->ExtentUnitID = $arrExtentUnitsMap[$extentUnit];

          $objLocationEntry->dbStore();
          if (!$objLocationEntry->ID) {
            echo("Error storing collection location $objLocationEntry->Content: {$_ARCHON->clearError()}<br />\n");
            continue;
          }

          if ($objLocationEntry->ID) {
            echo("Imported location {$objLocationEntry->Content} in collection {$objLocationEntry->Collection->Title}.<br /><br />\n\n");
          }

          flush();
        }
      }
    }

    echo("All files imported!");
  }
}
