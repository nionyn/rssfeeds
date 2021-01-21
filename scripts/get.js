function filter() {
    var xmlhttp = new XMLHttpRequest();
	
	//get sort dropdiwb
	var sort = document.getElementById("sort").value;
	
	//get checkbox values
	var chk = []
	var checkboxes = document.querySelectorAll('input[name=name]:checked')
	for (var i = 0; i < checkboxes.length; i++) {
	  chk.push(checkboxes[i].value)
	}
	//display checkbox value separated by a comma
	var sources = chk.join(",");
	
	//build url to quesry
	var url = `get.php?sort=${sort}&sources=${sources}`;

	//if page is loaded then display rows inside div element
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("feeds").innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

//first time page loads run filter to populate rows
window.addEventListener("load", function(){
   filter();
});