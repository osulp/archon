<?php
/**
 * Exports contents of Collection Locations as CSV file.
 *
 * @package Archon
 * @subpackage AdminUI
 */
isset($_ARCHON) or die();
$UtilityCode = 'collection_new_csv';
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
  $filename = 'collection-' . encoding_strtolower(date('Y-m-d-His'));
  $arrCollections = $_ARCHON->searchCollections('', SEARCH_COLLECTIONS, 0, 0, 0, $repositoryID, 0, 0, NULL, NULL, NULL, 0);
  $arrClassifications = $_ARCHON->getAllClassifications();
  $csv = '"ID","Title","Sort Title","Extent","Creators","BiogHist","BiogHistAuthor","Scope","Inclusive Dates","Predominant Dates","Subjects","Related Materials","Material Type","Collection URL"' . "\n";
  $sep = ',';
  $quote = '"';
  /** @var $collection Collections_Collection */
  foreach ($arrCollections as $collection) {
    $collection->dbLoad();
    $collection->dbLoadCreators();
    $collection->dbLoadSubjects();

    if($collection->MaterialTypeID)
    {
      $collection->MaterialType = New MaterialType($collection->MaterialTypeID);
      $collection->MaterialType->dbLoad();
    }

    $creators = array_map(function($obj) { return $obj->Name; }, $collection->Creators);
    $creators = implode(", ", $creators);
    $subjects = array_map(function($obj) { return $obj->Subject; }, $collection->Subjects);
    $subjects = implode(", ", $subjects);
    $collection_url = "http://scarc.library.oregonstate.edu/findingaids?p=collections/findingaid&id={$collection->ID}";

    $csv .= $quote . $arrClassifications[$collection->ClassificationID]->ClassificationIdentifier . ' ' . $collection->CollectionIdentifier . $quote . $sep
      . $quote . $collection->Title. $quote . $sep
      . $quote . str_replace("\"","\"\"",$collection->SortTitle). $quote . $sep
      . $quote . str_replace("\"","\"\"",$collection->Extent) . $quote . $sep
      . $quote . str_replace("\"","\"\"",$creators) . $quote . $sep
      . $quote . str_replace("\"","\"\"",$collection->BiogHist) . $quote . $sep
      . $quote . str_replace("\"","\"\"",$collection->BiogHistAuthor) . $quote . $sep
      . $quote . str_replace("\"","\"\"",$collection->Scope) . $quote . $sep
      . $quote . $collection->InclusiveDates . $quote . $sep
      . $quote . $collection->PredominantDates . $quote . $sep
      . $quote . str_replace("\"","\"\"",$subjects) . $quote . $sep
      . $quote . str_replace("\"","\"\"",$collection->RelatedMaterials) . $quote . $sep
      . $quote . str_replace("\"","\"\"",$collection->MaterialType) . $quote . $sep
      . $quote . $collection_url . $quote . "\n";
  }
  header('Content-type: text/csv; charset=UTF-8');
  header('Content-Disposition: attachment; filename="' . $filename . '.csv"');
  echo $csv;
}
