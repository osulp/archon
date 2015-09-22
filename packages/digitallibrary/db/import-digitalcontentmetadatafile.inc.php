<?php
/**
 * Digital Content Metadata importer script.
 *
 * This script takes .csv files in a defined format and creates a new collection record for each row in the database.
 * A sample csv/excel file is provided in the archon/incoming folder, to show the necessary format.
 *
 *
 * this script does not currently support the import and linking of controlled subject or genre terms.
 *
 * @package Archon
 * @subpackage AdminUI
 * @author Paul Sorensen
 */

isset($_ARCHON) or die();

$UtilityCode = 'digitalcontentmetadatafile';

$_ARCHON->addDatabaseImportUtility(PACKAGE_DIGITALLIBRARY, $UtilityCode, '3.21', array('xml'), TRUE);

if ($_REQUEST['f'] == 'import-' . $UtilityCode) {
  if (!$_ARCHON->Security->verifyPermissions(MODULE_DATABASE, FULL_CONTROL)) {
    die("Permission Denied.");
  }

  @set_time_limit(0);

  ob_implicit_flush();

  $arrFiles = $_ARCHON->getAllIncomingFiles();

  if (!empty($arrFiles)) {

    $allClassifications = array();
    foreach ($_ARCHON->getAllClassifications() as $id => $classification) {
      $allClassifications[$id] = strtolower($classification->ClassificationIdentifier);
    }

    $allCollections = array();
    // get a list of the collections
    foreach ($_ARCHON->getAllCollections() as $id => $collect) {
      if (preg_match('/\d{1,4}/', $collect->CollectionIdentifier)) {
        $cid = $allClassifications[$collect->ClassificationID].str_replace(' ', '', $collect->CollectionIdentifier);
      } else {
        $cid = str_replace(' ', '', $collect->CollectionIdentifier);
      }
      $allCollections[strtolower($cid)] = $id;
    }

    foreach ($arrFiles as $Filename => $strXML) {
      echo("Parsing file $Filename...<br/>\n");

      $xml = @simplexml_load_string($strXML);

      foreach ($xml->collection as $coll) {

        $coll_identifier = (string)$coll->id;

        // we get the collectionidentifier, from there we need the collection ID to store
        if (array_key_exists($coll_identifier, $allCollections)) {
          echo "[" . $coll->id . " - " . $coll->title . "]<br />";
          $objDigitalContent = new DigitalContent();
          $objDigitalContent->Browsable = 1;
          $objDigitalContent->CollectionID = $allCollections[$coll_identifier];
          $objDigitalContent->Title = (string) $coll->title;
          $objDigitalContent->dbStore();
          if (!$objDigitalContent->ID) {
            echo("Error storing digital content $objDigitalContent->Title: {$_ARCHON->clearError()}<br />");
          }
          else {
            echo("Imported {$objDigitalContent->Title}.<br /><br />");

            $images = $coll->image;
            foreach ($images as $img) {

              $img_id = (string)$img->id;
              $img_desc = (string)$img->desc;
              echo '--' .$img_id. ' - ' . $img_desc . '<br />';

              // we need to build a file from the img id and open it for reading
              $file_name = $img_id.'-600w.jpg';
              $path = $_ARCHON->RootDirectory . '/incoming/digitalcontent/'.$coll_identifier.'/images/'.$file_name;
              if (file_exists($path)) {
                // dbstore wants a temp file location, so we need to create one
                $tmp = tempnam('/tmp','chicken');
                $tmp_fp = fopen($tmp, 'wb');
                $fp = fopen($path,'rb');
                $data = fread($fp, filesize($path));
                fwrite($tmp_fp,$data);
                fclose($tmp_fp);
                fclose($fp);

                $objFile = new File();
                $objFile->DigitalContentID = $objDigitalContent->ID;
                $objFile->Title = $img_desc;
                $objFile->Source = (string)$img->source;
                $objFile->DefaultAccessLevel = DIGITALLIBRARY_ACCESSLEVEL_FULL;
                $objFile->Filename = $file_name;
                $objFile->TempFileName = $tmp;

                $objFile->dbStore();
                echo '---- Stored: ' . $file_name . '<br /><br />';
              } else {
                echo 'File '.$file_name.' not found at ' . $path . '<br />';
              }
              break;  // we only want to store one image for each collection
            }
          }
        } else {
          echo 'Collection '.$coll_identifier.' not found in collections list.<br />';
        }
      }
      flush();
    }
    echo("Import complete.");
  }
}
