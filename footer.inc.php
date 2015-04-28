<?php
/**
 * Footer for all output documents
 *
 * @package Archon
 * @author Chris Rishel
 */
isset($_ARCHON) or die();

if($_ARCHON->AdministrativeInterface)
{
   if(file_exists("adminthemes/{$_ARCHON->AdministrativeInterface->Theme}/footer.inc.php"))
   {
      $cwd = getcwd();

      chdir("adminthemes/{$_ARCHON->AdministrativeInterface->Theme}/");

      require_once('footer.inc.php');

      chdir($cwd);
   }
}
else
{
   if($_ARCHON->PublicInterface->DisableTheme)
   {
      return;
   }

   $output = '';
   if(ob_get_level() > $_ARCHON->DefaultOBLevel)
   {
      $output = ob_get_clean();

      $arrWords = $_ARCHON->createSearchWordArray($_ARCHON->QueryString);

      $count = 0;
      if(!empty($arrWords))
      {
         foreach($arrWords as $word)
         {
            if($word && $word{0} != "-")
            {
               $output = preg_replace("/(\A|\>)([^\<]*[^\w^=^\<^\+^\/]|)(" . preg_quote($word, '/') . ")(|[^\w^=^\>\+][^\>]*)(\<|\z)/ui", "$1$2<span class='highlight$count bold'>$3</span>$4$5", $output);
               $count++;
            }
         }
      }
   }
   echo($output);

   if(file_exists('themes/' . $_ARCHON->PublicInterface->Theme))
   {
      $cwd = getcwd();

      chdir('themes/' . $_ARCHON->PublicInterface->Theme);

      require_once('footer.inc.php');

      chdir($cwd);
   }
   ?>
</body>
</html>
   <?php
}
$_ARCHON->Security->Session->close();
$_ARCHON->mdb2->disconnect();
?>