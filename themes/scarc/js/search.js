/**
 * Determines whether to run Archon search of finding aids or Google search
 * of entire SCARC site based on which radio button is checked.
 *
 * @param form
 * @returns {boolean}
 */
function routeSearch(form) {
    var query = form['q'].value;
    if (!query) {
        alert("Please enter a search term.");
        return false;
    } else {
        var scope = form['scope'];
        if ('fa' == scope.value) {
            // finding aids only, so process the Archon search
            return true;
        } else {
            // Search the entire SCARC site
            var searchUrl = "http://www.google.com/search?sitesearch="
                + encodeURIComponent("http://scarc.library.oregonstate.edu")
                + "&q=" + encodeURIComponent(query);
            window.location = searchUrl;
            return false;
        }
    }
}
