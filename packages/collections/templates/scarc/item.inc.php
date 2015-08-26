<?php
/**
 * Item template for finding aid output
 *
 * The variable:
 *
 *  $objContent
 *
 * is an instance of a CollectionContent object, with its properties
 * already loaded when this template is referenced.
 *
 * Refer to the CollectionContent class definition in lib/collection.inc.php
 * for available properties and methods.
 *
 * The Archon API is also available through the variable:
 *
 *  $_ARCHON
 *
 * Refer to the Archon class definition in lib/archon.inc.php
 * for available properties and methods.
 *
 * @package Archon
 * @author Chris Rishel
 */
isset($_ARCHON) or die();

//echo "<pre>";print_r($Content);echo "</pre>";


if($enabled)
{
  $dtclass = (count($Content['Content']) > 0) ? 'faitem' : 'faitemnormal';
   ?>

<dt class='<?php echo $dtclass; ?>' id="id<?php echo($Content['ID']); ?>"><a name="id<?php echo($Content['ID']); ?>"></a><?php echo($Content['String']); ?></dt>

   <?php
   if($Content['Description'])
   {
      echo("<dd class='faitemdesc'>" . $Content['Description'] . "</dd>\n");
   }

   if($Content['UserFields'])
   {
      natcasesort(&$Content['UserFields']);
      foreach($Content['UserFields'] as $ID => $String)
      {
        // Suppress the 'UnitID field display - Issue #27 - 6/22/15 ME
        if ('UnitID' != substr($String, 0, 6)) {
          echo "<dd class='faitemuserfields'>" . $String . "</dd>\n";
        }
      }
   }

   if(!empty($Content['Subjects']))
   {
      echo("<dd class='faitemcontent'><dl><dt>Subject/Index Terms:</dt><dd>\n");
      echo($_ARCHON->createStringFromSubjectArray($Content['Subjects'], "</dd>\n<dd>\n", LINK_NONE));
      echo("</dd></dl></dd>\n");
   }

   if(!empty($Content['Creators']))
   {
      echo("<dd class='faitemcontent'><dl><dt>Creators:</dt><dd>\n");
      echo($_ARCHON->createStringFromCreatorArray($Content['Creators'], "</dd>\n<dd>\n", LINK_NONE));
      echo("</dd></dl></dd>\n");
   }

   if(!empty($Content['Content']))
   {
      echo("<dd class='faitemcontent'><dl class='faitem'>#CONTENT#</dl></dd>");
   }


}


?>
