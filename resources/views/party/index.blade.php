@extends('app')

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
@stop

@section('content')

    <div id="error" style="color:red"></div>
    <div id="success" style="color:green"></div>
    <h2>Parties Organization</h2>

    <div class="box">
        <form action="" method="POST" id="form-party" enctype="multipart/form-data">
            <input class="form-control" type="hidden" name="id" value="" />
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" value="" />
            <label  for="description">Description</label>
            <textarea name="description" class="form-control"></textarea>
            <label for="date">Date</label>
            <input class="form-control" type="date" name="date" value=""/>
            <label for="length">Length(hours)</label>
            <input class="form-control" type="text" name="length" value=""/>
            <label for="capacity">Capacity</label>
            <input class="form-control" type="text" name="capacity" value=""/>
            <label>Tags</label>
            <input class="form-control" type="text" name="tags" value=""/>
            <label for="image">Image</label>
            <input type="file" name="image" value="" id="img"/>

            <input class="btn btn-primary" style="margin:20px 0px;" type="button" onclick="saveParty()" name="submit_save_party" value="Save"/>
            <input class="btn btn-primary" style="margin:20px 0px;display:none" type="button" onclick="editParty()" name="submit_edit_party" value="Edit"/>
        </form>
    </div>
    <hr>

    <h3>List of parties</h3>

    <div class="row" id="parties">

    </div>

    <script type="text/javascript">

        $( document ).ready(function() {
            if(!getToken())
                $('#popUpWindow').modal('show');
        });

        function saveParty(){
            var fd = new FormData();
            fd.append('name', $('[name="name"]').val());
            fd.append('description', $('[name="description"]').val());
            fd.append('date', $('[name="date"]').val());
            fd.append('length', $('[name="length"]').val());
            fd.append('capacity', $('[name="capacity"]').val());
            fd.append('tags', $('[name="tags"]').val());
            fd.append('image', document.getElementById('img').files[0]);

            $.ajax({
                url: "api/parties",
                type: "POST",
                data: fd,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + getToken());
                },
                success: function(data)
                {
                    $('#form-party')[0].reset();
                    $('#success').append("<p>Saved successfully</p>");
                    var party = data.data;
                    var div = $('<div class="col-md-4" id="party-'+ party.id +'"></div>');
                    div.append('<div class="thumbnail"><img id="image-'+ party.id +'" src="images/'+ party.image +'"><div class="caption" id="'+party.id+'"><h3>' + party.name + '</h3><p>Date: ' + party.date + '</p><p>Capacity: ' + party.capacity + '</p><p>Duration(hours): ' + party.length + '</p><p>' + party.description + '</p><p>' + party.tags + '</p><p><a href="#" onclick="getParty('+ party.id +')" class="btn btn-primary" role="button">Edit</a> <a href="#" class="btn btn-default" role="button">Delete</a> <a onclick="startParty('+ party.id +')" class="btn btn-success" style="float:right" role="button" id="btn-start-'+ party.id +'">Start</a></p></div></div>');
                    $('#parties').append(div);
                    clearMsg();
                },
                error: function(xhr) {
                    $('#error').empty();
                    var error = xhr.responseJSON.error;
                    switch(xhr.status){
                        case 400:
                        case 401:
                            $('#error').append("<p>"+error+"</p>");
                            window.localStorage.removeItem("jwt-token");
                            window.localStorage.removeItem("name");
                            showLoginModal();
                            break;
                        case 403:
                            var error = xhr.responseJSON.error;
                            $('#error').append("<p>"+error+"</p>");
                            showLoginModal();
                            break;
                        case 422:
                            var errors = xhr.responseJSON.errors;
                            for(var i in errors){
                                $('#error').append("<p>"+errors[i][0]+"</p>");
                            }
                            break;
                    }
                }
            });
        }

        function getParty(id){
            $('#form-party')[0].reset();
            var data = $('#' + id).children();
            $('[name="description"]').val(data[4].innerHTML);
            $('[name="tags"]').val(data[5].innerHTML);
            $('[name="id"]').val(id);

            $('label[for=name], [name="name"]').hide();
            $('label[for=length], [name="length"]').hide();
            $('label[for=capacity], [name="capacity"]').hide();
            $('label[for=date], [name="date"]').hide();
            $('[name="submit_edit_party"]').css('display', 'block');
            $('[name="submit_save_party"]').css('display', 'none');
        }

        function editParty(){
            var fd = new FormData();
            var id = $('[name="id"]').val();
            fd.append('description', $('[name="description"]').val());
            fd.append('tags', $('[name="tags"]').val());
            if(document.getElementById('img').files[0]){
                fd.append('image', document.getElementById('img').files[0]);
            }

            $.ajax({
                url: "api/parties/" + id,
                type: "POST",
                data: fd,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + getToken());
                },
                success: function(data)
                {
                    $('#success').append("<p>Updated successfully</p>");
                    var party = data.data;
                    $('#form-party')[0].reset();
                    $('label[for=name], [name="name"]').show();
                    $('label[for=length], [name="length"]').show();
                    $('label[for=capacity], [name="capacity"]').show();
                    $('label[for=date], [name="date"]').show();
                    $('[name="submit_edit_party"]').css('display', 'none');
                    $('[name="submit_save_party"]').css('display', 'block');

                    var div = $('#' + party.id).children();
                    div[4].innerHTML = party.description;
                    div[5].innerHTML = party.tags;
                    $("#image-" + party.id).attr("src", 'images/' + party.image);
                    clearMsg();
                },
                error: function(xhr) {
                    $('#error').empty();
                    var error = xhr.responseJSON.error;
                    switch(xhr.status){
                        case 400:
                        case 401:
                            $('#error').append("<p>"+error+"</p>");
                            window.localStorage.removeItem("jwt-token");
                            window.localStorage.removeItem("name");
                            showLoginModal();
                            break;
                        case 403:
                            var error = xhr.responseJSON.error;
                            $('#error').append("<p>"+error+"</p>");
                            showLoginModal();
                            break;
                        case 422:
                            var errors = xhr.responseJSON.errors;
                            for(var i in errors){
                                $('#error').append("<p>"+errors[i][0]+"</p>");
                            }
                            break;
                    }
                }
            });
        }

        function deleteParty(id){
            $.ajax({
                url: "api/parties/" + id,
                type: "DELETE",
                data: null,
                dataType: 'json',
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + getToken());
                },
                success: function (data) {
                    console.log(data);
                    $('#success').append("<p>Deleted successfully</p>");
                    $('#party-' + data).remove();
                    clearMsg();
                }
            });

        }

        $.ajax({
            url: "api/parties",
            type: "GET",
            data: null,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                var parties = data.data;
                for (var i = 0; i < parties.length; i++) {
                    var div = $('<div class="col-md-4" id="party-'+ parties[i].id +'"></div>');
                    div.append('<div class="thumbnail"><img id="image-'+ parties[i].id +'" src="images/'+ parties[i].image +'"><div class="caption" id="'+parties[i].id+'"><h3>' + parties[i].name + '</h3><p>Date: ' + parties[i].date + '</p><p>Capacity: ' + parties[i].capacity + '</p><p>Duration(hours): ' + parties[i].length + '</p><p>' + parties[i].description + '</p><p>' + parties[i].tags + '</p><p><a href="#" onclick="getParty('+ parties[i].id +')" class="btn btn-primary" role="button">Edit</a> <a href="#" onclick="deleteParty('+ parties[i].id +')" class="btn btn-default" role="button">Delete</a> <a onclick="startParty('+ parties[i].id +')" class="btn btn-success party-maker" style="float:right" role="button" id="btn-start-'+ parties[i].id +'">Start</a></p></div></div>');
                    $('#parties').append(div);
                }

            },
            error: function (xhr) {
                $('#error').empty();
                var error = xhr.responseJSON.error;
                switch (xhr.status) {
                    case 400:
                        $('#error').append("<p>" + error + "</p>");
                        break;
                    case 401:
                        if (error != 'token_expired') {
                            $('#error').append("<p>" + error + "</p>");
                        }
                        else {
                            window.localStorage.removeItem("jwt-token");
                            window.localStorage.removeItem("name");
                            window.localStorage.removeItem("user_id");
                            window.location = "/";
                        }
                        break;
                    case 403:
                        var error = xhr.responseJSON.error;
                        $('#error').append("<p>" + error + "</p>");
                        showLoginModal();
                        break;
                }
            }
        });

        function clearMsg(){
            setTimeout(function () {
                success.innerHTML = "";
                error.innerHTML = "";
            }, 3000);
        }

    </script>

@stop
