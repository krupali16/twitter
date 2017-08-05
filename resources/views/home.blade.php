<html>
 <body>
        <div class = "container">
            <div class = "content">
                <div class = "title">My Twitter App</div>
                	You are logged in
                
                <div>
                	<h4>Name is {{ Auth::user()->name }}</h4>
                	<h4>Twitter handle is {{ Auth::user()->handle }}</h4>
                	<img src = "{{ Auth::user()->avatar }}" height="200" width = "200" />
                </div>
            </div>
        </div>
    </body>
</html>