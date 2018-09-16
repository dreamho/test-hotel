@extends('app')

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div id="error" style="color:red"></div>
            <form method="POST" action="">
                <label>Name:</label><br>
                <input type="text" name="name" class="form-control"><br>
                <label>Email:</label><br>
                <input type="email" name="email" class="form-control"><br>
                <label>Password:</label><br>
                <input type="password" name="password" class="form-control"><br>
                <label>Confirm password:</label><br>
                <input type="password" name="password_confirmation" class="form-control"><br>
                <select name="role" class="form-control"><option>Choose role:</option></select><br><br>
                <input onclick='SubmitForm(this.form)' type="button" name="btn-submit" value="Submit" class="btn btn-info btn-block">
            </form>
        </div>
    </div>

    <script type="text/javascript">

        $.ajax({
            type: 'GET',
            url: 'api/roles',
            data: null,
            dataType: 'json',
            success: function(data){
                var roles = data.data;
                console.log(roles);
                for(var i=0;i<roles.length;i++){
                    $('[name="role"]').append('<option value="'+ roles[i].id +'">'+ roles[i].name +'</option>');
                }
            }
        });

        function SubmitForm(form){
            var user = {};
            user.name = form.name.value;
            user.email = form.email.value;
            user.password = form.password.value;
            user.password_confirmation = form.password_confirmation.value;
            user.role = form.role.value;
            $.ajax({
                type: "POST",
                url: "api/register",
                data: user,
                dataType: "json",
                success: function (data){
                var token = data.token ? data.token : null;
                var name = data.data.name ? data.data.name : data.data.email;
                var role = data.data.role ? data.data.role : null;
                window.localStorage.setItem('jwt-token', token);
                window.localStorage.setItem('name', name);
                window.localStorage.setItem('parties', '');
                window.localStorage.setItem('role', role);

                window.location = "/";
                },
                error: function(xhr) {
                    var error = xhr.responseJSON.error;
                    $('#error').empty();
                    switch (xhr.status) {
                        case 401:
                            $('#error').append("<p>" + error + "</p>");
                        break;
                        case 422:
                            var errors = xhr.responseJSON.errors;
                            for (var i in errors) {
                                $('#error').append("<p>" + errors[i][0] + "</p>");
                            }
                        break;
                    }
                }
            });
        }
    </script>
@stop