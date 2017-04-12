<?php
/**
 * Exports contents of Collection Locations as CSV file.
 *
 * @package Archon
 * @subpackage AdminUI
 */

isset($_ARCHON) or die();

$UtilityCode = 'collectionlocations_csv';

$_ARCHON->addDatabaseExportUtility(PACKAGE_COLLECTIONS, $UtilityCode, '3.21');

if($_REQUEST['f'] == 'export-' . $UtilityCode) {

  if (!$_ARCHON->Security->verifyPermissions($_ARCHON->Module->ID, READ)) {
    die("Permission Denied.");
  }

  $repositoryID = $_REQUEST['repositoryid'] ? $_REQUEST['repositoryid'] : 0;
  if($repositoryID == 0 )
  {
    die("RepositoryID not defined.");
  }

  $filename = 'collection_locations-' . encoding_strtolower(date('Y-m-d-His'));

  $arrCollections = $_ARCHON->searchCollections('', SEARCH_COLLECTIONS, 0, 0, 0, $repositoryID, 0, 0, NULL, NULL, NULL, 0);
  $arrClassifications = $_ARCHON->getAllClassifications();

  $csv = '"ID","Title","Location","Content","Range Value","Section","Shelf","Extent"' . "\n";
  $sep = ',';
  $quote = '"';
  /** @var $collection Collections_Collection */
  foreach ($arrCollections as $collection) {

    $collection->dbLoadLocationEntries();

    /** @var $location Collections_LocationEntry */
    foreach ($collection->LocationEntries as $location) {
      $csv .= $quote . $arrClassifications[$collection->ClassificationID]->ClassificationIdentifier . ' ' . $collection->CollectionIdentifier . $quote . $sep
        . $quote . $collection->Title. $quote . $sep
        . $quote . $location->Location->Location . $quote . $sep
        . $quote . $location->Content . $quote . $sep
        . $location->RangeValue . $sep
        . $location->Section . $sep
        . $location->Shelf . $sep
        . $quote . $location->Extent . ' ' . $location->ExtentUnit->ExtentUnit . $quote . "\n";
    }
  }

  header('Content-type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
  echo $csv;

}
