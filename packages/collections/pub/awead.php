<?php
/**
 * Output file for Archives West EAD finding aids
 *
 * @package Archon
 * @author Chris Rishel
 */

isset($_ARCHON) or die();

$filename = ($_REQUEST['output']) ? $_REQUEST['output'] : 'awead';

header('Content-type: text/xml; charset=UTF-8');
header('Content-Disposition: attachment; filename="'.$filename.'.xml"');

$_REQUEST['templateset'] = "EAD";

$_ARCHON->PublicInterface->DisableTheme = true;

include('packages/collections/pub/findingaid.php');
?>