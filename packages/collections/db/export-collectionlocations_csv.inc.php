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

  $csv = '"ID","Title","Sort Title","Location","Content","Range Value","Section","Shelf","Extent","Creators","BiogHist","BiogHistAuthor","Scope","Inclusive Dates","Predominant Dates","Subjects","Related Materials","Collection URL"' . "\n";

  $sep = ',';
  $quote = '"';
  /** @var $collection Collections_Collection */
  $count = 0;
  foreach ($arrCollections as $collection) {
    $count = $count + 1;
    // Depending on the allowed memory, we are limited to approx 1000 collection
    // records at the time using the current flow
    //
    // TODO: Implement a batch mechanism to query groups of collections and
    // combine result into one csv file or
    // provide additional options to download multiple csv files (about 12) of
    // 1000 collections each
    if ($count == 1000) {
      break;
    }
    $collection->dbLoad();
    $collection->dbLoadLocationEntries();
    $collection->dbLoadCreators();

    $creators = array_map(function($obj) { return $obj->Name; }, $collection->Creators);
    $creators = implode(", ", $creators);

    $subjects = array_map(function($obj) { return $obj->Subject; }, $collection->Subjects);
    $subjects = implode(", ", $subjects);

    $collection_url = "http://scarc.library.oregonstate.edu/findingaids?p=collections/findingaid&amp;id={$collection->ID}";

    /** @var $location Collections_LocationEntry */
    foreach ($collection->LocationEntries as $location) {
      $csv .= $quote . $arrClassifications[$collection->ClassificationID]->ClassificationIdentifier . ' ' . $collection->CollectionIdentifier . $quote . $sep
        . $quote . $collection->Title. $quote . $sep
        . $quote . $collection->SortTitle. $quote . $sep
        . $quote . $location->Location->Location . $quote . $sep
        . $quote . $location->Content . $quote . $sep
        . $location->RangeValue . $sep
        . $location->Section . $sep
        . $location->Shelf . $sep
        . $quote . $location->Extent . ' ' . $location->ExtentUnit->ExtentUnit . $quote . $sep
        . $quote . $creators . $quote . $sep
        . $quote . $collection->BiogHist . $quote . $sep
        . $quote . $collection->BiogHistAuthor . $quote . $sep
        . $quote . $collection->Scope . $quote . $sep
        . $quote . $collection->InclusiveDates . $quote . $sep
        . $quote . $collection->PredominantDates . $quote . $sep
        . $quote . $subjects . $quote . $sep
        . $quote . $collection->RelatedMaterials . $quote . $sep
        . $quote . $collection_url . $quote . "\n";
    }
  }

  header('Content-type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
  echo $csv;

}
