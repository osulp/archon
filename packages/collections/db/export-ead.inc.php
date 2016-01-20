<?php

isset($_ARCHON) or die();


$UtilityCode = 'ead';

$temp_dir = realpath('./temp/');

$_ARCHON->addDatabaseExportUtility(PACKAGE_COLLECTIONS, $UtilityCode, '3.21');

if($_REQUEST['f'] == 'export-' . $UtilityCode)
{
   if(!$_ARCHON->Security->verifyPermissions($_ARCHON->Module->ID, READ))
   {
      die("Permission Denied.");
   }

   $repositoryID = $_REQUEST['repositoryid'] ? $_REQUEST['repositoryid'] : 0;

   if($repositoryID == 0)
   {
      die("RepositoryID not defined.");
   }


   function findingaid_DisplayContent($id, $objCollection, $readPermissions)
   {
      global $_ARCHON;

      $Content = $objCollection->Content[$id];

      if($_ARCHON->PublicInterface->Templates['collections'][$Content['LevelContainer']])
      {
         $ItemType = $Content['LevelContainer'];
      }
      else
      {
         $ItemType = "DefaultContent";
      }

      if(!$_ARCHON->PublicInterface->Templates['collections'][$ItemType])
      {
         $_ARCHON->declareError("Could not display $ItemType: $ItemType template not defined for template set {$_ARCHON->PublicInterface->TemplateSet}.");
         return;
      }

      // Process and send the first portion of the item template.
      // TODO: Finish content level suppression

      $enabled = $readPermissions || $Content['Enabled'];

      ob_start();
      eval($_ARCHON->PublicInterface->Templates['collections'][$ItemType]);

      $output = ob_get_clean();


      // Break the item template where %Items% occurs
      // into two strings, containing the template data before
      // and after the processed items should be inserted.z
      list($outputnow, $outputlater) = explode("#CONTENT#", $output);

      if(function_exists("template_ContentPreProcess"))
      {
         $outputnow = template_ContentPreProcess($outputnow, $Content);
      }

      echo($outputnow);

      flush();


      if($enabled)
      {
         // Process and display all the children recursively.
         if(!empty($Content['Content']))
         {
            foreach($Content['Content'] as $ID => $Child)
            {
               findingaid_DisplayContent($ID, $objCollection, $readPermissions);
            }
         }

      }

      if(function_exists("template_ContentPostProcess"))
      {
         $outputlater = template_ContentPostProcess($outputlater, $Content);
      }

      echo($outputlater);
      flush();
   }


   @set_time_limit(60);


   $arrCollections = $_ARCHON->searchCollections('', SEARCH_COLLECTIONS, 0, 0, 0, $repositoryID, 0, 0, NULL, NULL, NULL, 0);

   $foldername = "archon_{$repositoryID}_ead";
//   $dirname = realpath(sys_get_temp_dir())."/".$foldername;
   $dirname = $temp_dir."/".$foldername;

   if(file_exists($dirname))
   {
      $d = dir($dirname);
      while($entry = $d->read())
      {
         if ($entry!= "." && $entry!= "..")
         {
            unlink($dirname."/".$entry);
         }
      }
      $d->close();
      rmdir($dirname);
   }

   mkdir($dirname, 0755);

   header("Content-Type: archive/zip");
   header("Content-Disposition: attachment; filename={$foldername}.zip");

   $_ARCHON->PublicInterface = new PublicInterface();
   $_ARCHON->PublicInterface->initialize(CONFIG_CORE_DEFAULT_THEME, "EAD");
   $_ARCHON->PublicInterface->DisableTheme = true;

   foreach($arrCollections as $objCollection)
   {
      $_REQUEST['id'] = $objCollection->ID;
      $_REQUEST['output'] = formatFileName($objCollection->getString('SortTitle',0,false,false));

      $handle = fopen($dirname."/".$_REQUEST['output'].".xml", "w");

      ob_start();

      $objCollection->dbLoadRootContent();
      $arrRootContent = $objCollection->Content;
      $objCollection->dbLoadAll(LOADCONTENT_NONE);

      if(!$_ARCHON->PublicInterface->Templates['collections']['Collection'])
      {
         $_ARCHON->declareError("Could not display FindingAid: Collection template not defined for template set {$_ARCHON->PublicInterface->TemplateSet}.");
      }

      if(!$_ARCHON->Error)
      {
         ob_start();

         // Process the collection template.
         eval($_ARCHON->PublicInterface->Templates['collections']['Collection']);

         $output = ob_get_clean();

         // Break the collection template where %Items% occurs
         // into two strings, containing the template data before
         // and after the processed items should be inserted.
         list($outputnow, $outputlater) = explode("#CONTENT#", $output);

         echo($outputnow);
         flush();

         if(!$objCollection->enabled())
         {
            $readPermissions = false;
         }
         else
         {
            $readPermissions = false;

            if($_ARCHON->Security->verifyPermissions(MODULE_COLLECTIONCONTENT, READ)
                    || ($_ARCHON->Security->userHasAdministrativeAccess() && !CONFIG_CORE_LIMIT_REPOSITORY_READ_PERMISSIONS)
                    || (CONFIG_CORE_LIMIT_REPOSITORY_READ_PERMISSIONS && $_ARCHON->Security->verifyRepositoryPermissions($objCollection->RepositoryID)))
            {
               $readPermissions = true;
            }
         }

         if(!empty($arrRootContent))
         {
            $objContent = reset($arrRootContent);
            $rootContentIDsSet = $objCollection->rootContentIDsSet();

            if(!$rootContentIDsSet)
            {
               $objCollection->getContentArray(LOADCONTENT_ALL, true);
            }

            do
            {
               if(!$in_RootContentID || $objContent->ID == $in_RootContentID)
               {
                  if($rootContentIDsSet)
                  {
                     $objCollection->getContentArray($objContent->ID, true);
                  }
                  // Process and display the current item.
                  findingaid_DisplayContent($objContent->ID, $objCollection, $readPermissions);

               }
               // Advance the array pointer to the next item
               $objContent = next($arrRootContent);

            } while($objContent && !$objContent->ParentID);
         }

         echo($outputlater);
         flush();

         // END
      }
      else
      {
         echo($_ARCHON->Error);
      }

      $file = ob_get_clean();
      fwrite($handle, $file);
      fclose($handle);
   }

   $zip = new ZipArchive();
   $zip_file = $temp_dir . "/EADExport.zip";

   if ($zip->open($zip_file, ZipArchive::CREATE)!==TRUE) {
      $_ARCHON->declareError("Cannot create ZIP file: $zip_file");
   } else {
      $export_dir = $temp_dir . '/' . $foldername;

      $d = dir($export_dir);
      while($entry = $d->read())
      {
         if ($entry!= "." && $entry!= "..")
         {
            $add_file = $export_dir . '/' . $entry;
            $zip->addFile($add_file, $foldername . '/' . $entry);
         }
      }
      $d->close();

      $zip->close();

      // deliver the zip file
      $fp = fopen($zip_file,"r");
      echo fpassthru($fp);

      // Clean up the tmp zip file and the export directory.
      unlink($zip_file);

      $d = dir($export_dir);
      while($entry = $d->read())
      {
         if ($entry!= "." && $entry!= "..")
         {
            unlink($export_dir . '/' . $entry);
         }
      }
      $d->close();
      rmdir($export_dir);
   }

}
