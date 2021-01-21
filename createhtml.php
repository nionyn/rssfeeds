<?php

require 'vendor/autoload.php';

//function to display array rows as HTML
function dataToHTML($data){
	//load HTML template for a row and amend values
	foreach ($data as $d){
		$row = new IvoPetkov\HTML5DOMDocument();
		$row->loadHTMLFile('html/proto_row.html', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
		$stringdate = $d['date']->format('H:i d M Y');
		$row->querySelector('.title')->innerHTML = $d['title'];
		$row->querySelector('.description')->innerHTML = $d['description'];
		$row->querySelector('.date')->innerHTML = $stringdate;
		//out row
		echo $row->saveHTML($row->documentElement);
	}
	
}

?>