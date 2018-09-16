<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!-- Nav links based on user log status -->
<script type="text/javascript">
    if(window.localStorage.getItem('jwt-token') && window.localStorage.getItem('name')){
        $('#auth').append('<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hello ' + window.localStorage.getItem('name') + '! <span class="caret"></span></a><ul class="dropdown-menu"><li><a onclick="logout()" href="#">Logout</a></li></ul></li>');
    } else{
        window.localStorage.clear();
        $('#auth').append('<li><a href="#" onclick="showLoginModal()">Login</a></li><li><a href="/register">Register</a></li>');
    }
</script>