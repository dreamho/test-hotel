@extends('app')

@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
@stop

@section('content')
    <div class="row">

        <div class="col-md-12 text-center"><h1>Quantox Hotel</h1></div>

    </div>

    <div class="row">

        <div class="col-md-12">

            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <img src="images/1.jpg" alt="...">
                        <div class="carousel-caption">

                        </div>
                    </div>
                    <div class="item">
                        <img src="images/2.jpg" alt="...">
                        <div class="carousel-caption">

                        </div>
                    </div>
                    <div class="item">
                        <img src="images/3.jpg" alt="...">
                        <div class="carousel-caption">

                        </div>
                    </div>

                </div>

                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

    </div>
    <hr>

    <div class="row" id="parties">

    </div>

    <hr>

    <div class="row">
        <div class="col-md-6"><h3>Contact our administration</h3></div>
        <div class="col-md-6"><h3>Visit us</h3></div>
    </div>
    <div class="row">

        <div class="col-md-6">
            <form>
                <label>Enter your name</label><br>
                <input type="text" name="name" class="form-control"><br>
                <label>Your Email</label><br>
                <input type="email" name="email" class="form-control"><br>
                <label>Your Phone</label><br>
                <input type="number" name="phone" class="form-control"><br>
                <input type="submit" name="btn-submit" value="Submit" class="btn btn-default btn-block">
            </form>
        </div>
        <div class="col-md-6" id="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2869.779476370681!2d20.900138215847058!3d44.005284479110856!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4757211a385cd97d%3A0x954fc66e4e527eed!2sQuantox+Technology!5e0!3m2!1sen!2srs!4v1528207994113"
                    width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>

    </div>
    <div class="row">

        <div class="col-md-6"></div>
        <div class="col-md-6">
            <h5>Address: Kneza Mihaila 112</h5>
        </div>
    </div>

    <script type="text/javascript">

        $.ajax({
            url: "api/parties/data",
            type: "GET",
            data: null,
            dataType: 'json',
            success: function (data) {
                var parties = data.data;
                console.log(parties);
                for (var i = 0; i < parties.length; i++) {
                    var div = $('<div class="col-md-6" id="'+ parties[i].id +'"></div>');
                    div.append('<div class="thumbnail"><img src="images/'+ parties[i].image +'"><div class="caption"><h3>' + parties[i].name + '</h3><p>Date: ' + parties[i].date + '</p><p>Capacity: ' + parties[i].capacity + '</p><p>Duration(hours): ' + parties[i].length + '</p><p>' + parties[i].description + '</p><p><a onclick="joinParty('+ parties[i].id +')" class="btn btn-primary" role="button" id="btn-join-'+ parties[i].id +'">Join us</a> <a onclick="startParty('+ parties[i].id +')" class="btn btn-default party-maker" style="display:none" role="button" id="btn-start-'+ parties[i].id +'">Start</a></p><p>' + parties[i].tags + '</p></div></div>');
                    $('#parties').append(div);
                    if (parties[i].started==1){
                        $('#btn-start-' + parties[i].id).removeClass('btn btn-default').addClass('btn btn-success').html('Started');
                    }                    
                }
                if(hasRole('party_maker')){
                    $('.party-maker').show();
                }
                if(window.localStorage.getItem('parties')!=undefined){
                    setJoinedParties();
                }
            },
            error: function (xhr) {
                   $('#error').empty();
                    var error = xhr.responseJSON.error;
                    switch (xhr.status) {
                        case 400:
                        case 401:
                        case 403:
                            showLoginModal();
                            break;
                    }
            }
        });

        function hasRole(role){
            if(window.localStorage.getItem('role')==null) return false;
            var roles = window.localStorage.getItem('role').split(',');
            for(var i=0;i<roles.length;i++){
                if(roles[i] == role){
                    return true;
                }
                else{
                    return false;
                }
            }
        }

        function joinParty(id){
            $.ajax({
                url: "api/parties/join/" + id,
                type: "POST",
                data: null,
                dataType: 'json',
                beforeSend: function(request) {
                    request.setRequestHeader("Authorization", "Bearer " + getToken());
                },
                success: function (data) {
                    var party = data.data;
                    $('#btn-join-' + party.id).removeClass('btn btn-primary').addClass('btn btn-success').html('Joined');
                    $('#btn-join-' + party.id).attr('onclick', '');
                    var parties = window.localStorage.getItem('parties')=='' ? window.localStorage.getItem('parties') + party.id : window.localStorage.getItem('parties') + "," + party.id;
                    window.localStorage.setItem('parties', parties);
                },
                error: function (xhr) {
                    $('#error').empty();
                    var error = xhr.responseJSON.error;
                    switch (xhr.status) {
                        case 400:
                        case 401:
                        case 403:
                            showLoginModal();
                            break;
                    }
                }
            });
        }

        function setJoinedParties(){
            var parties = window.localStorage.getItem('parties');
            var parties_array = parties.split(',');
            for(var i=0;i<parties_array.length;i++){
                if(parties_array[i] === $('#' + parties_array[i]).attr('id')){
                    $('#btn-join-' + parties_array[i]).removeClass('btn btn-primary').addClass('btn btn-success').html('Joined');
                    $('#btn-join-' + parties_array[i]).attr('onclick', '');
                }
            }
        }

    </script>

@stop

