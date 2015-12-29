<?php
/**
 * Footer file for SCARC theme
 *
 * @package Archon
 * @author OSU Special Collections and Archives Research Center
 */

isset($_ARCHON) or die();

?>
</div>
<footer class="row">
  <div class="col-md-1"></div>
  <div class="col-md-6">
    <p>
      &copy; <?php echo date('Y') ; ?>, <a class="footer" href="http://scarc.library.oregonstate.edu">Special Collections &amp; Archives Research Center</a><br /><a class="footer" href="http://library.oregonstate.edu">Oregon State University Libraries</a><br /><a class="footer" href="mailto:scarc@oregonstate.edu">scarc@oregonstate.edu</a>    (541) 737-2075 <br />
      Normal Operating Hours: 10:00-6:00, Mon, Wed-Fri; 10:00-8:00 Tues <br />
      <br />
      121 The Valley Library<br />
      Oregon State University<br />
      Corvallis, OR 97331-3411 <br /></p>
      <p class="page-info">
        Page Generated in: <?php echo(round(microtime(true) - $_ARCHON->StartTime, 3)); ?> seconds (using <?php echo($_ARCHON->QueryLog->QueryCount); ?> queries).<br/>
        <?php
        if(function_exists('memory_get_usage') && function_exists('memory_get_peak_usage'))
        {
          ?>
          Using <?php echo(round(memory_get_usage() / 1048576, 2)); ?>MB of memory. (Peak of <?php echo(round(memory_get_peak_usage() / 1048576, 2)); ?>MB.)<br/>
          <?php
        }
        ?>
      </p>
  </div>
  <div class="col-md-2">
    <p><a class="footer" href="http://scarc.library.oregonstate.edu/accolades.html">Accolades</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/copyright.html">Copyright</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/facilities.html">Facilities</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/faq.html">FAQ</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/legacy-award.html">Pauling Legacy Award</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/residentscholar.html">Resident Scholar Program</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/internship.html">Student Internship Program</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/using-our-collections.html">Using Our Collections</a><br /><a class="footer" href="http://scarc.library.oregonstate.edu/visiting-guide.html">Visiting Guide</a><br /></p>
  </div>
  <div class="col-md-2">
    <p><a class="footer" href="http://www.facebook.com/OSU.SpecColl.Arch">Facebook</a><br /><a class="footer" href="http://www.flickr.com/photos/osuarchives">Flickr</a><br /><a class="footer" href="http://www.flickr.com/photos/osucommons/">Flickr Commons</a> (<a class="footer" href="flickrcommons.html">About</a>)<br /><a class="footer" href="https://pinterest.com/SCARCpinned/">Pinterest</a><br /><a class="footer" href="https://twitter.com/OSU_scarc">Twitter</a><br /></p>
  </div>
  <div class="col-md-1"></div>
</footer>
