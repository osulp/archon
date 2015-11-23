<?php
/**
 * Main page for SCARC template
 *
 * @package Archon
 * @author OSU Special Collections and Archives Research Center
 */

isset($_ARCHON) or die();

$pages = array();
if (isset($_REQUEST['f']) && in_array($_REQUEST['f'], $pages)) {
  $page = $_REQUEST['f'];
  include "pages/{$page}.inc.php";
  return;
}
?>
<div class="row">
  <div class="col-md-6">
    <h1>Welcome</h1>
    <p>Welcome to the OSU Libraries Special Collections & Archives Research
      Center’s collections portal, home to detailed description of
      the more than 1,200 archival collections held by SCARC. Use the
      Collections menu above to navigate by collection title, collection
      type, collection subject (“People, Places, and Topics”) or collection
      creator. Or use the search box at the upper right to discover materials
      held at SCARC.</p>

    <p>Though many collections have full collection guides, (also known as
      “finding aids”) some collections are only minimally
      processed or unprocessed. To the extent that it is possible, we endeavor
      to provide research access to all of our collections,
      which include manuscripts, photographs, record groups, moving images, oral
      histories and more. For more information about any
      of our collections, please <a
        href="http://scarc.library.oregonstate.edu/ask-an-archivist.html">contact us</a>.
      If you are new to archival research, please <a
        href="http://scarc.library.oregonstate.edu/faq.html">review
        our FAQ.</a></p>
    <?php
      /**
       * This is a repeat of the generate_collection_atoz_list function in packages/collections/pub/collections.php
       * Since that file outputs page content when "required" we couldn't just require the file and call the method.
       */
      $arrCollectionCount = $_ARCHON->countCollections(TRUE, FALSE, $_SESSION['Archon_RepositoryID']);
      $collection_list = '';
      for ($i = 65; $i < 91; $i++) {
        $char = chr($i);
        if (!empty($arrCollectionCount[encoding_strtolower($char)])) {
          $href = "?p=collections/collections&amp;char=$char";
          $collection_list .= '<a class="browse-letter" href="' . $href . '">' . $char . '</a>';
        }
        else {
          $collection_list .= '<span class="browse-letter">' . $char . '</span>';
        }
      }
      if (!empty($collection_list)) {
        echo '<hr /><div class="center"><h2>Browse ' . $arrCollectionCount['*'] . ' Collections</h2>'
          . $collection_list . "<br /><a href='?p=collections/collections&browse'>View All</a></div><hr />";
      }
      ?>
    <h2>Search Tips</h2>
    <dl>
      <dt class='index'>Default Behaviors</dt>
      <dd class='index'>
        <ul>
          <li>The search engine looks for records containing every term you
            submit.
          </li>
        </ul>
      </dd>
      <dt class='index'>Search By Phrase</dt>
      <dd class='index'>
        <ul>
          <li>Use double quotes around your search query. (e.g "Festival of
            Contemporary Arts")
          </li>
        </ul>
      </dd>
      <dt class='index'>Narrow Your Search Results</dt>
      <dd class='index'>
        <ul>
          <li>Use a minus sign before a term you want to omit from your results.
            (e.g. 'bass -fish' finds bass guitars but not bass fishing.)
          </li>
          <li>Browse by collection title, subject, name, or classification.</li>
        </ul>
      </dd>
    </dl>
  </div>
  <div class="col-md-6">
    <figure>
      <a href="http://oregondigital.org/catalog/oregondigital:df70c053z" target="_blank"><img
          class="home-image" src="themes/<?php echo $_ARCHON->PublicInterface->Theme; ?>/images/oregondigital-df70c053z.jpg"
          alt="Ida Kidder (foreground) seated in the Oregon State College library, ca. 1910s"></a>
      <figcaption>Ida Kidder (foreground) seated in the Oregon State College
        library, ca. 1910s. Kidder was OSC’s first professional librarian and
        served the college from 1908 to her death in 1920. After a new library
        building was opened in 1964, the former library facility pictured here
        was renamed Kidder Hall in Ida Kidder’s memory.
      </figcaption>
    </figure>
  </div>
</div>
