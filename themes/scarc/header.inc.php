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

   if($_ARCHON->QueryString && $_ARCHON->Script == 'packages/core/pub/search.php')
   {
      $_ARCHON->PublicInterface->addNavigation("Search Results For \"" . $_ARCHON->getString(QueryString) . "\"", "?p=core/search&amp;q=" . $_ARCHON->QueryStringURL, true);
   }
}
else
{
   $RepositoryName = $_ARCHON->Repository ? $_ARCHON->Repository->getString('Name') : 'Archon';

   if($_ARCHON->QueryString)
   {
      $_ARCHON->PublicInterface->addNavigation("Search Results For \"" . encode($_ARCHON->QueryString, ENCODE_HTML) . "\"", "?p=core/search&amp;q=" . $_ARCHON->QueryStringURL, true);
   }
}

$_ARCHON->PublicInterface->addNavigation('Archon', 'index.php', true);
if (empty($_ARCHON->PublicInterface->Title)) {
  $_ARCHON->PublicInterface->Title = $RepositoryName;
}

//header('Content-type: text/html; charset=UTF-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
      <title><?php echo(strip_tags($_ARCHON->PublicInterface->Title)); ?></title>
     <link rel="stylesheet"  href="themes/<?php echo($_ARCHON->PublicInterface->Theme); ?>/css/bootstrap.min.css" >
     <link rel="stylesheet" type="text/css" href="themes/<?php echo($_ARCHON->PublicInterface->Theme); ?>/style-scarc.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/cluetip/jquery.cluetip.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/jgrowl/jquery.jgrowl.css" />
      <link rel="icon" type="image/ico" href="<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/archon.ico"/>
      <!--[if lte IE 7]>
        <link rel="stylesheet" type="text/css" href="themes/<?php echo($_ARCHON->PublicInterface->Theme); ?>/ie.css" />
        <link rel="stylesheet" type="text/css" href="themes/<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/cluetip/jquery.cluetip.ie.css" />
      <![endif]-->
     <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
     <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-3.0.1.min.js"></script>
     <script type="text/javascript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
     <?php echo($_ARCHON->getJavascriptTags('jquery-expander')); ?>
      <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/jquery.hoverIntent.js"></script>
      <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/cluetip/jquery.cluetip.js"></script>
      <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/jquery.scrollTo-min.js"></script>
      <?php echo($_ARCHON->getJavascriptTags('jquery.jgrowl.min')); ?>
     <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/bootstrap.js"></script>
     <script type="text/javascript" src="<?php echo($_ARCHON->PublicInterface->ThemeJavascriptPath); ?>/search.js"></script>
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
           $('#ccardprintcontact')
             .affix({
               offset: { top: 230, bottom: 220 }
             })
             .on('affixed.bs.affix', function () {
               $(this).removeAttr('style');
               resize_left_column();
             });
           resize_left_column();
            $(window).resize(function () {
              resize_left_column();
            });
         });

         function resize_left_column() {
           $('#ccardprintcontact').width($('#fa-left-column').width());
         }

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
     <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
     <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
     <!--[if lt IE 9]>
     <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
     <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
     <![endif]-->
     <?php  echo $_ARCHON->PublicInterface->outputGoogleAnalyticsCode(); ?>
   </head>
  <body data-spy="scroll" data-target="#nav-container-list">
    <div id="skiptocontent"><a href="#main">skip to main content</a></div>
    <div id="header-blacktop">
      <div id="header-blacktop-container">
        <div id="header-blacktop-text"><a href="https://library.oregonstate.edu" class="header-blacktop">OSU Libraries</a></div>
        <div id="researchblock">
          <?php
          $go = urlencode($_SERVER['QUERY_STRING']);
          if($_ARCHON->Security->isAuthenticated())
          {
            echo("<span class='bold'>Welcome, " . $_ARCHON->Security->Session->User->DisplayName . "</span>");

            if($_ARCHON->Security->userHasAdministrativeAccess())
            {
              echo(" | <a href='?p=admin' rel='external'>Admin</a>&nbsp;");
            }


            $logoutURI = preg_replace('/(&|\\?)f=([\\w])*/', '', $_SERVER['REQUEST_URI']);
            $Logout = (encoding_strpos($logoutURI, '?') !== false) ? '&amp;f=logout' : '?f=logout';
            $strLogout = encode($logoutURI, ENCODE_HTML) . $Logout;
            echo(" | <a href='$strLogout'>Logout</a>");
          }
          elseif($_ARCHON->config->ForceHTTPS)
          {
            echo("<a href='?p=core/login&amp;go={$go}'>Log In</a>");
          }
          else
          {
            echo("<a href='?p=core/login&amp;go={$go}'>Log In</a>");
//            echo("<a href='#' onclick='$(window).scrollTo(\"#archoninfo\"); if($(\"#userlogin\").is(\":visible\")) $(\"#loginlink\").html(\"Log In\"); else $(\"#loginlink\").html(\"Hide\"); $(\"#userlogin\").slideToggle(\"normal\"); $(\"#ArchonLoginField\").focus(); return false;'>Log In</a>");
          }

          if(!$_ARCHON->Security->userHasAdministrativeAccess())
          {
            $emailpage = defined('PACKAGE_COLLECTIONS') ? "collections/research" : "core/contact";

            echo(" | <a href='?p={$emailpage}&amp;f=email&amp;referer=" . urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . "'>Contact Us</a> ");
            if($_ARCHON->Security->isAuthenticated())
            {
              echo(" | <a href='?p=core/account&amp;f=account'>My Account</a> ");
            }
            if(defined('PACKAGE_COLLECTIONS'))
            {
              $_ARCHON->Security->Session->ResearchCart->getCart();
              $EntryCount = $_ARCHON->Security->Session->ResearchCart->getCartCount();
              $class = $_ARCHON->Repository->ResearchFunctionality & RESEARCH_COLLECTIONS ? '' : 'hidewhenempty';
              $hidden = ($_ARCHON->Repository->ResearchFunctionality & RESEARCH_COLLECTIONS || $EntryCount) ? '' : "style='display:none'";

              echo("<span id='viewcartlink' class='$class' $hidden>| <a href='?p=collections/research&amp;f=cart&amp;referer=" . urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']) . "'>" . $_ARCHON->getPhrase('view_cart', PACKAGE_COLLECTIONS, 0, PHRASETYPE_PUBLIC)->getPhraseValue(ENCODE_HTML) . " (<span id='cartcount'>$EntryCount</span>)</a></span>");
            }
          }
          ?>
        </div>
      </div>

    </div>
    <div id="header-title"><a href="http://oregonstate.edu" class="nostyle"><img id="osu-tag"
       src="<?php echo($_ARCHON->PublicInterface->ImagePath); ?>/osu-tag.gif" width="101" height="119" alt="Oregon State University" /></a><div
        class="scarctitle"><a href="http://scarc.library.oregonstate.edu">Special Collections and Archives<br/><span style="font-size: 0.8em;">Research Center</span></a></div></div>
    <div id="header-nav">
      <ul id="nav">
        <li><a href="index.php">Collections</a><ul>
            <li><a href="?p=collections/collections">Collections by Title</a></li>
            <li><a href="?p=collections/classifications">Collections by Type</a></li>
            <li><a href="?p=subjects/subjects">People, Places, and Topics</a></li>
            <li><a href="?p=creators/creators">Creators</a></li>
          </ul>
        </li>
        <li><a href="http://scarc.library.oregonstate.edu/digital-resources.html">Digital Resources</a><ul>
            <li><a href="http://scarc.library.oregonstate.edu/digitalresources/osuhistory/index.html">University History</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/digitalresources/historyofscience/index.html">History of Science</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/digitalresources/pauling/index.html">Linus Pauling Online</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/digitalresources/oma/index.html">Oregon Multicultural Archives</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/digitalresources/naturalresources/index.html">Natural Resources</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/events/index.html">Online Audio/Video</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/digitalresources/socialmedia/index.html">Social Media</a></li>
          </ul>
        </li>
        <li><a href="http://scarc.library.oregonstate.edu/about-us.html">About Us</a><ul>
            <li><a href="http://scarc.library.oregonstate.edu/about-us.html#mission-statement">Mission Statement</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/about-us.html#department-history">Department History</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/about-us.html#staff">Staff</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/using-our-collections.html">Using Our Collections</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/facilities.html">Facilities</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/faq.html">Frequently Asked Questions</a></li>
          </ul>
        </li>
        <li><a href="http://scarc.library.oregonstate.edu/services.html">Services</a><ul>
            <li><a href="http://scarc.library.oregonstate.edu/faq.html">Frequently Asked Questions</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/donate-materials.html">Donating Materials</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/facilities.html">Facilities</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/instruction-and-outreach.html">Instruction and Outreach</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/records-management.html">Records Management</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/reference.html">Reference</a></li>
            <li><a href="http://scarc.library.oregonstate.edu/duplication.html">Reproduction &amp; Use</a></li>
          </ul>
        </li>
        <li><a href="http://scarc.library.oregonstate.edu/ask-an-archivist.html">Ask An Archivist</a></li>
      </ul>
      <div id="search">
        <form class="form-inline" action="index.php" accept-charset="UTF-8" method="get" onsubmit="return routeSearch(this)">
          <div class="form-group-sm">
            <input type="hidden" name="p" value="core/search" />
            <label class="sr-only" for="qfa">Search Term</label>
            <input type="text" size="25" class="form-control" title="search" maxlength="150" name="q" id="qfa"
                 placeholder="Search"
                 value="<?php echo(encode($_ARCHON->QueryString, ENCODE_HTML)); ?>" />
            <label class="sr-only" for="scope">Search Scope</label>
            <select class="form-control" id="scope">
              <option value="fa" selected="selected">Collections Only</option>
              <option value="site">Entire Site</option>
            </select>
            <button type="submit" value="Search" class="btn btn-primary btn-sm">Search</button>
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
  <?php
  $arrP = explode('/', $_REQUEST['p']);
  $TitleClass = $arrP[0] == 'collections' && $arrP[1] != 'classifications' ? 'currentBrowseLink' : 'browseLink';
  $ClassificationsClass = $arrP[1] == 'classifications' ? 'currentBrowseLink' : 'browseLink';
  $SubjectsClass = $arrP[0] == 'subjects' ? 'currentBrowseLink' : 'browseLink';
  $CreatorsClass = $arrP[0] == 'creators' ? 'currentBrowseLink' : 'browseLink';
  $DigitalLibraryClass = $arrP[0] == 'digitallibrary' ? 'currentBrowseLink' : 'browseLink';
  ?>
<div id="main" class="container">
