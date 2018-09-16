@extends('app')

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
@stop

@section('content')

    <div id="error" style="color:red"></div>
    <div id="success" style="color:green"></div>
    <h2>Songs Administration</h2>

    <div class="box">
        <form action="" method="POST" id="form">
            <input class="form-control" type="hidden" name="id" value="" />
            <label>Artist</label>
            <input class="form-control" type="text" name="artist" value="" />
            <label>Track</label>
            <input class="form-control" type="text" name="track" value="" />
            <label>Link</label>
            <input class="form-control" type="text" name="link" value=""/>
            <label>Length(minutes)</label>
            <input class="form-control" type="text" name="length" value=""/>
            <input class="btn btn-primary" style="margin:20px 0px;" type="button" onclick='saveSong(this.form)' name="submit_save_song" value="Save"/>
            <input class="btn btn-primary" style="margin:20px 0px;display:none" type="button" onclick='editSong(this.form)' name="submit_edit_song" value="Edit"/>
        </form>
    </div>

    <!-- main content output -->
    <div>
        <h3>List of songs</h3>
        <table class="table">
            <thead>
            <tr>
                <td>Id</td>
                <td>Artist</td>
                <td>Track</td>
                <td>Link</td>
                <td>Length</td>
                <td>DELETE</td>
                <td>EDIT</td>
            </tr>
            </thead>
            <tbody id="rows">

            </tbody>
        </table>
    </div>
    </div>
    <div id="paginate"></div>
    <script type="text/javascript">

        var token = window.localStorage.getItem('jwt-token');
        var error = document.getElementById('error');
        var success = document.getElementById('success');

        // Show Modal if token is not set
        $( document ).ready(function() {
            if(!token)
            $('#popUpWindow').modal('show');
        });

        // Delete song
        function deleteSong(id){
            if(!confirm("Are you sure that you want to delete this song?")){
                return;
            }
            request('api/songs/'+id, 'DELETE', null, function (data){
                $('#success').append("<p>Deleted successfully</p>");
                $('#' + data).remove();
                clearMsg();
            });
        }
        // Save song
        function saveSong(form){
            var song = {};
            song.artist = form.artist.value;
            song.track = form.track.value;
            song.link = form.link.value;
            song.length = form.length.value;
            request('api/songs', 'POST', song, function (data){
                $('#success').append("<p>Saved successfully</p>");
                var song = data.data;
                var tr = $("<tr />");
                tr.append("<td>"+ song.id +"</td><td>"+ song.artist +"</td><td>"+ song.track +"</td><td>"+ song.link +"</td><td>"+ song.length +"</td>");
                tr.append("<td><a onclick='deleteSong(" + song.id + ")' href='#'>Delete</a><td><a onclick='editForm(" + song.id + ")' href='#'>Edit</a></td>");
                tr.attr('id', song.id);
                $('#rows').append(tr);
                $('#form')[0].reset();
                clearMsg();
            });
        }

        // Fillling the form for editing
        function editForm(id){
            $('[name="submit_save_song"]').css('display', 'none');
            $('[name="submit_edit_song"]').css('display', 'block');
            var form = $('#form')[0];
            var data = $('#' +id).children();
            form.id.value = data[0].innerHTML;
            form.artist.value = data[1].innerHTML;
            form.track.value = data[2].innerHTML;
            form.link.value = data[3].innerHTML;
            form.length.value = data[4].innerHTML;
        }

        // Edit song
        function editSong(form){

            var song = {};
            song.id = form.id.value;
            song.artist = form.artist.value;
            song.track = form.track.value;
            song.link = form.link.value;
            song.length = form.length.value;

            request('api/songs/' + song.id, 'PUT', song, function (data) {
                $('#success').append("<p>Updated successfully</p>");
                var song = data.data;
                var tr = $('#' + song.id).empty();
                for(var j in song){
                    tr.append("<td>" + song[j] + "</td>");
                }
                tr.append("<td><a onclick='deleteSong(" + song.id + ")' href='#'>Delete</a><td><a onclick='editForm(" + song.id + ")' href='#'>Edit</a></td>");
                tr.attr('id', song.id);
                $('#form')[0].reset();
                $('[name="submit_save_song"]').css('display', 'block');
                $('[name="submit_edit_song"]').css('display', 'none');
                clearMsg();
            });
        }

        // Get all songs
        function getSongs() {
            request('api/songs', 'GET', null, function (data) {
                console.log(data);
                $("#rows").html("");
                var songs = data.data;
                for (var i = 0; i < songs.length; i++) {
                    var tr = $("<tr />");
                    var song = songs[i];
                    for (var j in song) {
                        tr.append("<td>" + song[j] + "</td>");
                    }
                    tr.append("<td><a onclick='deleteSong(" + song.id + ")' href='#'>Delete</a><td><a onclick='editForm(" + song.id + ")' href='#'>Edit</a></td>");
                    tr.attr('id', song.id);
                    $('#rows').append(tr);
                }
            });
        }

        // Clear message
        function clearMsg(){
            setTimeout(function () {
                success.innerHTML = "";
                error.innerHTML = "";
            }, 3000);
        }
        getSongs();
    </script>
@stop
