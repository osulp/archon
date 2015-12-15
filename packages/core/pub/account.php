<?php
isset($_ARCHON) or die();

if($_REQUEST['f'] == 'sessioninfo')
{
   echo('{"authenticated":'.bool($_ARCHON->Security->isAuthenticated()).',"administrativeaccess":'.bool($_ARCHON->Security->userHasAdministrativeAccess()).'}');
   die();
}


if(!$_ARCHON->Security->isAuthenticated() || $_ARCHON->Security->userHasAdministrativeAccess())
{
    header('Location: index.php?p=');
}



$objMyAccountPhrase = Phrase::getPhrase('myaccount_title', PACKAGE_CORE, 0, PHRASETYPE_PUBLIC);
$strMyAccountTitle = $objMyAccountPhrase ? $objMyAccountPhrase->getPhraseValue(ENCODE_HTML) : 'My Account';

$_ARCHON->PublicInterface->Title = $strMyAccountTitle;
$_ARCHON->PublicInterface->addNavigation($_ARCHON->PublicInterface->Title, "?p={$_REQUEST['p']}");

require_once("header.inc.php");

?>
<b>General:</b>
<ul>
<li><a href="?p=core/editprofile">Edit My Profile</a></li>
</ul>
<?php

require_once("footer.inc.php");
?>
