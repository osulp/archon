<?php
/**
 * Collection PDF importer script.
 *
 * This script takes a .pdf file, parses out the text, and stores it for searching with the selected collection.
 *
 *
 * @package Archon
 * @subpackage AdminUI
 * @author Mike Eaton
 */

isset($_ARCHON) or die();

$UtilityCode = 'collection_pdf';

$_ARCHON->addDatabaseImportUtility(PACKAGE_COLLECTIONS, $UtilityCode, '3.21', array('pdf'), true);

if($_REQUEST['f'] == 'import-' . $UtilityCode)
{
  if(!$_ARCHON->Security->verifyPermissions(MODULE_DATABASE, FULL_CONTROL))
  {
    die("Permission Denied.");
  }

  @set_time_limit(0);

  ob_implicit_flush();

  $arrFiles = $_ARCHON->getAllIncomingFiles();

  if(!empty($arrFiles))
  {

    foreach($arrFiles as $Filename => $strCSV)
    {
      echo("Parsing file $Filename...<br /><br />\n\n");

      // Remove byte order mark if it exists.
//      $strCSV = ltrim($strCSV, "\xEF\xBB\xBF");
//
//      $arrAllData = getCSVFromString($strCSV);
//      // ignore first line?
//      foreach($arrAllData as $arrData)
//      {
//        if(!empty($arrData))
//        {
//          $objDigitalContent = new DigitalContent();
//
//          $enabled = reset($arrData);
//          if($enabled)
//          {
//            $enabled = trim(strtolower($enabled));
//            if($enabled == 'yes' || $enabled == 'y')
//            {
//              $enabled = 1;
//            }
//            else
//            {
//              $enabled = 0;
//            }
//          }
//          else
//          {
//            $enabled = 0;
//          }
//
//          $objDigitalContent->Browsable = $enabled;
//
//          //TODO: implement check to ensure collection exists
//          $objDigitalContent->CollectionID = next($arrData);
//
//          $objDigitalContent->Title = next($arrData);
//
//
//          $objDigitalContent->Identifier = next($arrData);
//
//          $objDigitalContent->ContentURL = next($arrData);
//
//
//          $SortTitle = next($arrData);
//          $objDigitalContent->SortTitle = $SortTitle ? $SortTitle : $objDigitalContent->Title;
//
//          $objDigitalContent->Date = next($arrData);
//
//
//          $objDigitalContent->Scope = next($arrData);
//          $objDigitalContent->PhysicalDescription = next($arrData);
//          $objDigitalContent->Contributor = next($arrData);
//          $objDigitalContent->Publisher = next($arrData);
//          $objDigitalContent->RightsStatement = next($arrData);
//
//
//          $objDigitalContent->dbStore();
//          if(!$objDigitalContent->ID)
//          {
//            echo("Error storing digital content $objDigitalContent->Title: {$_ARCHON->clearError()}<br />\n");
//            continue;
//          }
//
//
//          if($objDigitalContent->ID)
//          {
//            echo("Imported {$objDigitalContent->Title}.<br /><br />\n\n");
//          }
//
//          flush();
//        }
//      }
    }

    echo("PDF imported");
  }
}
