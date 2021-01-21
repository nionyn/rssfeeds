<?php

require 'getfeeds.php';
require 'createhtml.php';
//define parameters to collect
define('PARAMS', array('sort', 'sources'));
//choose which feeeds to display
$feeds_to_display = getParameters();
//get and update feeds
$all_feeds = getAndUpdateFeeds($feeds_to_display);
//sprt feeds based on preference
usort($all_feeds, 'sortByOrder');
//outputfeeds as HTML
dataToHTML($all_feeds);

?>