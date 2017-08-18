<!DOCTYPE html>
<html>
<head>
  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
  <center><h1>Tweets</h1></center>
  <?php
  $token = (session('access_token'));
  ?>
  <div class="container">
    <ol>
      @foreach($tweets as $key => $value)      
       <li>{{$value['text']}}</li>
      @endforeach
    </ol>
  </div>
</body>
</html>
