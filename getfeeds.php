<?php

const FEEDS = [	'bbc' => ['bbc.xml', 'http://feeds.bbci.co.uk/news/rss.xml'], 
				'nyt' => ['nytimes.xml', 'https://rss.nytimes.com/services/xml/rss/nyt/World.xml'],
				'wsj' => ['wsj.xml', 'https://feeds.a.dj.com/rss/RSSWorldNews.xml']];

const FEED_DIR = 'feeds';

function updateFeed($feed){
	
	if (!file_exists(FEED_DIR .'/'. $feed[0])){
		getFeed($feed);
	}
	
	$lastTimeUpdated = filemtime(FEED_DIR .'/'. $feed[0]);
	if ((time() - 600) > $lastTimeUpdated){
		getFeed($feed);			
	} 
}

function getFeed($feed){
	$contents = file_get_contents($feed[1]);
	if (!is_dir(FEED_DIR)){
		mkdir(FEED_DIR);
	}
	file_put_contents(FEED_DIR . '/' . $feed[0], $contents);
}

function displayFeed($feed){
	foreach ($feed as $row){
		$stringdate = $row['date']->format('Y-m-d H:i:s');
		echo $stringdate. ' ' . $row['title'] . '<br>';
	}
}

//function to convert XML to php array
function getRows($feed){
	//define empty array store data
	$row_array = [];
	//load XML file into variable
	$xml=simplexml_load_file(FEED_DIR . '/' . $feed) or die("Error: Cannot create object");
	//select chanenel node
	$source = $xml->xpath("//channel");
	//get source title from XML, remember XPath queries always returns an array
	$source_title = $source[0]->title;
	
	//new XPath query to select items from a source
	$items = $xml->xpath("//item");
	//loop of the returned items and extract the data, title, description
	foreach($items as $f){
		$title = (string) $f->title;
		//convert data string to DateTime object
		$date = new DateTime($f->pubDate);
		$link = (string) $f->link;
		$description = (string) $f->description;
		//build an array row
		$row_array[] = ['date' => $date, 'title' => $title, 'link' => $link, 'description' => $description, 'source' => $source_title];
	}
	return $row_array;
}

//sort array by date
function sortByOrder($a, $b) {
	//catch sort preference
	global $sort;
	if ($sort == 'asc'){
		return $a['date']->getTimestamp() - $b['date']->getTimestamp();
	} else {
		return $b['date']->getTimestamp() - $a['date']->getTimestamp();
	}	   
} 

//function to obtain input parameters
function getParameters(){
	//get sort preference, default to desc
	if (!isset($_GET['sort'])) {
		$_GET['sort'] = 'desc';
	}

	//get sort
	global $sort;
	$sort = $_GET['sort'];
	//array to store sources
	$sources = [];
	//if sources have been set separate into array
	if (isset($_GET['sources'])) {
		$sources = array_unique(explode(",", $_GET["sources"]));
	}
	
	//array to store feeds
	$feeds = [];
	//match sources to feeds
	foreach ($sources as $s){
		if (array_key_exists($s, FEEDS)){
			$feeds[] = FEEDS[$s];
		}
	}

	//if no sources were selected, then show all feeds
	if (empty($feeds)){
		return FEEDS;
	} else {
		return $feeds;
	}
}

//for each feed requested get xml and process into rows
function getAndUpdateFeeds($feeds){
	$all_rows = [];
	foreach ($feeds as $f){
		updateFeed($f);
		$new_rows = getRows($f[0]);
		foreach($new_rows as $n){
			$all_rows[] = $n; 
		}
	}
	//return array of all feeds
	return $all_rows;
}

?>