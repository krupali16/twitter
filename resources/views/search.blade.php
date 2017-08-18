<html>
<head>
  
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
	<br/>
	<div class="container">
	<div>      
      <input id="text_search" width="100px" class="form-control" placeholder="Search by id, screen name, name"><br/><br/>
      <button type="button" class="btn btn-primary btn-sm" id="btn_search">Search</button><br/><br/>
    </div>
    <div id="users" class="table-responsive">
    </div>
    </div>

    <script type="text/javascript">

    $("#btn_search").click(function() {
      $.ajax({
        url: "/search_users/" + document.getElementById('text_search').value,
        success: function(result){
        	document.getElementById('users').innerHTML="";
        	var table = document.createElement('table');
        	table.setAttribute('id', 'tbl');
        	table.setAttribute('style', "border:1px solid");
        	table.className= "table table-striped";
        	
          for(var i=0;i<result.length;i++){
            		
			    var tr = document.createElement('tr'); 
			    tr.setAttribute('class', 'cls');
			    tr.setAttribute('onclick', 'getData("'+result[i]['screen_name']+'")');
			    tr.setAttribute('style', "border:1px solid grey; cursor:pointer;");

			    var td1 = document.createElement('td');
			    var td2 = document.createElement('td');

			    td1.setAttribute('style', "padding:10px");
			    td2.setAttribute('style', "padding:10px");

			    var text1 = document.createTextNode(result[i]['name']);
			    var text2 = document.createTextNode(result[i]['location']);

			    td1.appendChild(text1);
			    td2.appendChild(text2);
			    tr.appendChild(td1);
			    tr.appendChild(td2);
			    table.appendChild(tr);			
			    				
				
          }
          document.getElementById('users').appendChild(table);

        }
      })
    })

	function getData(name){		
          window.location.href = "/download_user_tweets/" + name;
	}
     

  </script>

</body>
</html>