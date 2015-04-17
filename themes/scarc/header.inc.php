<?php
/**
 * Header file for SCARC theme
 *
 * @package Archon
 * @author OSU Special Collections and Archives Research Center
 */
isset($_ARCHON) or die();

// *** This is now a configuration directive. Please set in the Configuration Manager ***
//$_ARCHON->PublicInterface->EscapeXML = false;


if($_ARCHON->Script == 'packages/collections/pub/findingaid.php')
{
   require("faheader.inc.php");
   return;
}

$_ARCHON->PublicInterface->Header->OnLoad .= "externalLinks();";

if($_ARCHON->Error)
{
   $_ARCHON->PublicInterface->Header->OnLoad .= " alert('" . encode(str_replace(';', "\n", $_ARCHON->processPhrase($_ARCHON->Error)), ENCODE_JAVASCRIPT) . "');";
}

if(defined('PACKAGE_COLLECTIONS'))
{

   if($objCollection->Repository)
   {
      $RepositoryName = $objCollection->Repository->getString('Name');
   }
   elseif($objDigitalContent->Collection->Repository)
   {
      $RepositoryName = $objDigitalContent->Collection->Repository->getString('Name');
   }
   else
   {
      $RepositoryName = $_ARCHON->Repository ? $_ARCHON->Repository->getString('Name') : '';
   }

   $_ARCHON->PublicInterface->Title = $_ARCHON->PublicInterface->Title ? $_ARCHON->PublicInterface->Title . ' | ' . $RepositoryName : $RepositoryName;

   if($_ARCHON->QueryString && $_ARCHON->Script == 'packages/core/pub/search.php')
   {
      $_ARCHON->PublicInterface->addNavigation("Search Results For \"" . $_ARCHON->getString(QueryString) . "\"", "?p=core/search&amp;q=" . $_ARCHON->QueryStringURL, true);
   }
}
else
{
   $RepositoryName = $_ARCHON->Repository ? $_ARCHON->Repository->getString('Name') : 'Archon';

   $_ARCHON->PublicInterface->Title = $_ARCHON->PublicInterface->Title ? $_ARCHON->PublicInterface->Title . ' | ' . $RepositoryName : $RepositoryName;

   if($_ARCHON->QueryString)
   {
      $_ARCHON->PublicInterface->addNavigation("Search Results For \"" . encode($_ARCHON->QueryString, ENCODE_HTML) . "\"", "?p=core/search&amp;q=" . $_ARCHON->QueryStringURL, true);
   }
}

$_ARCHON->PublicInterface->addNavigation('Archon', 'index.php', true);

//header('Content-type: text/html; charset=UTF-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title><?php echo(strip_tags($_ARCHON->PublicInterface->Title)); ?></title>
     <link rel="stylesheet" type="text/css" href="themes/<?php echo($_ARCHON->PublicInterface->Theme); ?>/style-scarc.css" />
<!--      <link rel="stylesheet" type="text/css" href="themes/--><?php //echo($_ARCHON->PublicInterface->Theme); ?><!--/style.css" />-->
      <link rel="stylesheet" type="text/css" href="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/cluetip/jquery.cluetip.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/jgrowl/jquery.jgrowl.css" />

      <link rel="icon" type="image/ico" href="<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/archon.ico"/>
      <!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="themes/<?php echo($_ARCHON->PublicInterface->Theme); ?>/ie.css" />
        <link rel="stylesheet" type="text/css" href="themes/<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/cluetip/jquery.cluetip.ie.css" />
      <![endif]-->
      <?php echo($_ARCHON->getJavascriptTags('jquery.min')); ?>
      <?php echo($_ARCHON->getJavascriptTags('jquery-ui.custom.min')); ?>
      <?php echo($_ARCHON->getJavascriptTags('jquery-expander')); ?>
      <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/jquery.hoverIntent.js"></script>
      <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/cluetip/jquery.cluetip.js"></script>
      <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/jquery.scrollTo-min.js"></script>
      <?php echo($_ARCHON->getJavascriptTags('jquery.jgrowl.min')); ?>
      <?php echo($_ARCHON->getJavascriptTags('archon')); ?>




       <script type="text/javascript">
         /* <![CDATA[ */
         imagePath = '<?php echo($_ARCHON->PublicInterface->ImagePath); ?>';
         $(document).ready(function() {
            $('div.listitem:nth-child(even)').addClass('evenlistitem');
            $('div.listitem:last-child').addClass('lastlistitem');
            $('#locationtable tr:nth-child(odd)').addClass('oddtablerow');
            $('.expandable').expander({
               slicePoint:       600,              // make expandable if over this x chars
               widow:            100,              // do not make expandable unless total length > slicePoint + widow
               expandText:         '[read more]',  //text to use for expand link
               expandEffect:     'fadeIn',         // or slideDown
               expandSpeed:      700,              // in milliseconds
               collapseTimer:    0,                // milliseconds before auto collapse; default is 0 (don't re-collape)
               userCollapseText: '[collapse]'      // text for collaspe link
            });
         });

         function js_highlighttoplink(selectedSpan)
         {
            $('.currentBrowseLink').toggleClass('browseLink').toggleClass('currentBrowseLink');
            $(selectedSpan).toggleClass('currentBrowseLink');
            $(selectedSpan).effect('highlight', {}, 300);
         }

         $(document).ready(function() {<?php echo($_ARCHON->PublicInterface->Header->OnLoad); ?>});
         $(window).unload(function() {<?php echo($_ARCHON->PublicInterface->Header->OnUnload); ?>});
         /* ]]> */
      </script>
      <?php
      if($_ARCHON->PublicInterface->Header->Message && $_ARCHON->PublicInterface->Header->Message != $_ARCHON->Error)
      {
         $message = $_ARCHON->PublicInterface->Header->Message;
      }
      ?>
   </head>
   <body>
   <div id="header-blacktop">
  <div id="header-blacktop-container">
    <div id="header-blacktop-text"><a href="http://library.oregonstate.edu" class="header-blacktop">OSU Libraries</a></div>
  </div>
</div>
<div id="header-title"><a href="http://oregonstate.edu" class="nostyle"><img id="osu-tag" src="<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/osu-tag.gif" width="101" height="119"
                                                                             alt="Oregon State University"
                                                                             title="Oregon State University" /></a><a href="index.html" class="nostyle"><img id="scarc-title" src="<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/scarc-header-title.jpg" width="843" height="98"
                                                                                                                                                             alt="Special Collections &amp; Archives Research Center"
                                                                                                                                                             title="Special Collections &amp; Archives Research Center" /></a></div>
<div id="header-nav">
  <ul id="nav">
    <li><a href="?p=collections/collections">Collections</a><ul>
        <li><a href="?p=collections/collections&browse">All Collections</a></li>
        <li><a href="">    University History</a></li>
        <li><a href="">    Natural Resources</a></li>
        <li><a href="">    Multicultural Archives</a></li>
        <li><a href="">    History of Science</a></li>
        <li><a href="">    Local History</a></li>
      </ul>
    </li>
    <li><a href="">Digital Resources</a><ul>
        <li><a href="">University History</a></li>
        <li><a href="">History of Science</a></li>
        <li><a href="">Linus Pauling Online</a></li>
        <li><a href="">Oregon Multicultural Archives</a></li>
        <li><a href="">Natural Resources</a></li>
        <li><a href="">Online Audio/Video</a></li>
        <li><a href="">Social Media</a></li>
      </ul>
    </li>
    <li><a href="?f=about-us">About Us</a><ul>
        <li><a href="?f=about-us#mission-statement">Mission Statement</a></li>
        <li><a href="?f=about-us#department-history">Department History</a></li>
        <li><a href="?f=about-us#staff">Staff</a></li>
        <li><a href="?f=using-our-collections">Using Our Collections</a></li>
        <li><a href="?f=facilities">Facilities</a></li>
        <li><a href="?f=faq">Frequently Asked Questions</a></li>
      </ul>
    </li>
    <li><a href="?f=services">Services</a><ul>
        <li><a href="?f=faq">Frequently Asked Questions</a></li>
        <li><a href="?f=donate-materials">Donating Materials</a></li>
        <li><a href="?f=facilities">Facilities</a></li>
        <li><a href="?f=instruction-and-outreach">Instruction and Outreach</a></li>
        <li><a href="?f=records-management">Records Management</a></li>
        <li><a href="?f=reference">Reference</a></li>
        <li><a href="?f=duplication">Reproduction &amp; Use</a></li>
      </ul>
    </li>
    <li><a href="?f=ask-an-archivist">Ask An Archivist</a></li>
  </ul>
  <div id="search">
<!--    <form name="gs" method="get" action="http://www.google.com/search"><input name="sitesearch" value="http://scarc.library.oregonstate.edu" type="hidden" /><input name="q" id="search-field" class="search-field" type="text" /><input class="button" value="Search" title="Search" type="submit" /></form>-->
    <form action="index.php" accept-charset="UTF-8" method="get" onsubmit="if(!this.q.value) { alert('Please enter search terms.'); return false; } else { return true; }">
      <div>
        <input type="hidden" name="p" value="core/search" />
        <input type="text" size="25" class="search-field" title="search" maxlength="150" name="q" id="qfa" value="<?php echo(encode($_ARCHON->QueryString, ENCODE_HTML)); ?>" tabindex="100" />
        <input type="submit" value="Search" tabindex="300" class='button' title="Search" />
        <?php
        if(defined('PACKAGE_COLLECTIONS') && CONFIG_COLLECTIONS_SEARCH_BOX_LISTS)
        {
          ?>
          <input type="hidden" name="content" value="1" />
        <?php
        }
        ?>
      </div>
    </form>
  </div>
</div>
<div id="main">