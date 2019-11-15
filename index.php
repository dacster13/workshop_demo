<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Demo</title>
    <link rel="stylesheet" href="assets/vendor/bootstrap/bootstrap-4.3.1/css/bootstrap.min.css">
</head>
<body style="display:flex; justify-content: center; align-content:center">

    <div class="card" style="margin-top:10em;">
        <div class="card-body">
            <div id="alert"></div>
            <form id="loginForm" action="authenticate.php">
                <div class="row form-group">
                    <div class="col">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter Username">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/vendor/jquery/jquery-3.4.1.min.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap-4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: e.target.action,
                    method: 'post',
                    data: $(this).serialize()
                })
                .then(function(response) {
                    return JSON.parse(response);
                })
                .then(function(response) {
                    if (response.success) {
                        window.location = 'dashboard.php';
                    } else {
                        $('<div>')
                            .addClass('alert alert-danger')
                            .text(response.message)
                            .appendTo($('#alert'))
                    }
                })
            })

        })
    </script>
</body>
</html>