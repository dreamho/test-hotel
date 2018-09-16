<div class="modal fade" id="popUpWindow">
    <div class="modal-dialog">
        <div class="modal-content">

            <form role="form" id="modal-form" onsubmit="login(event)">

                <!-- header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Login or <a href="register">Register</a> if you don't have an account!</h3>

                    <button type="button" class="btn btn-danger" onclick="setUser('milos.mosic@quantox.com', 'milos123')">Admin</button>
                    <button type="button" class="btn btn-warning" onclick="setUser('peterp@mail.com', 'peter123')">Guest</button>
                    <button type="button" class="btn btn-success" onclick="setUser('susans@mail.com', 'susan123')">Party maker</button>
                    <button type="button" class="btn btn-primary" onclick="setUser('emilyw@mail.com', 'emily123')">DJ</button>
                </div>

                <!-- body (form) -->
                <div class="modal-body">
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>
                </div>

                <!-- button -->
                <div class="modal-footer">
                    <button class="btn btn-primary btn-block">Login</button>
                </div>

            </form>

        </div>
    </div>
</div>