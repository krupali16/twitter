<!DOCTYPE html>
<html>
<head>
  <title>Homepage</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <link rel="stylesheet" type="text/css" href="/css/slider.css">
</head>
<body>
  <?php
  $token = (session('access_token'));
  ?>
  <div class="container">
    
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="/">Hello {{$token['screen_name']}}</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="/logout">Logout</a></li>
        </ul>
      </div>
    
    <a href="/search" class="btn btn-primary btn-sm">Search Users</a>    
    <a href="/download" id="download" class="btn btn-primary btn-sm">Download Tweets</a>
    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Email Tweets</button>

    <!-- Modal starts-->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Email Tweets</h4>
          </div>
          <div class="modal-body">
            <form method="POST" action="/send">
              {{csrf_field()}}
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-default">Send</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>
    <!-- modal ends -->
    <hr>

    <div class="row" id="tweets">
      <div class="col-xs-offset-1 col-xs-10 col-sm-offset-2 col-sm-8 col-md-offset-2 col-md-8 col-lg-offset-2 col-lg-8">
        <div class="caurosel" id="caurosel">
          <div class="btn-bar">
            <div id="buttons">
              <a id="prev" href="#">&lt;</a>
              <a id="next" href="#">&gt;</a>
            </div>
          </div>
          <div id="slides">
            <ul id="tweetsul">
              @foreach($tweets as $key => $value)
              <li class="slide">
                <div class="quoteContainer">
                  {{$value['text']}}
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>

    <br>
    <h3>Followers</h3>
    <div class="ui-widget">      
      <input id="tags">
    </div>
    <br>
    <div class="row" id="followers">
      @foreach($followers['users'] as $value)
      <div class="col-md-6 col-xs-12 col-sm-12 col-lg-6">
        <div class="media">
          <div class="media-left">
            <img src="{{$value['profile_image_url']}}" class="media-object" style="width:60px">
          </div>
          <div class="media-body">
            <label data="{{$value['screen_name']}}"><h4 class="media-heading">{{$value['name']}}</h4></label>
            
          </div>
        </div>
      </div>
      @endforeach
    </div>

  </div>
  <script src="/js/slider.js"></script>
  <script type="text/javascript">

    $("label").click(function() {
      $.ajax({
        url: "/twitter/" + $(this).attr("data"),
        success: function(result){
          document.getElementById('tweetsul').innerHTML="";
          for(var i=0;i<result.length;i++){
            console.log(result[i]);
            var li=document.createElement('li');
            li.className="slide";
            // li.style.width="593.984px";
            var div=document.createElement('div');
            div.className="quoteContainer";
            div.innerHTML=result[i];
            li.appendChild(div);
            document.getElementById('tweetsul').appendChild(li);
          }
          slideshowcall();
        }
      })
    })

   

    // var list = new Array();

    //  $("#tags").click(function() {


    //      list = <?php json_encode($followers['users']); ?>
          
    //         $( "#tags" ).autocomplete({
    //           source: list
    //         });
    //         console.log(list);
    //  });

  

  </script>
</body>
</html>
