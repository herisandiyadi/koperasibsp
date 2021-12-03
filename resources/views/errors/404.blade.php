<!DOCTYPE html>
<html>
    <head>
        <title>Page Not Found.</title>
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Lato:300" rel="stylesheet" type="text/css">

        <style>
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                font-family: 'Lato';
                background-image: url('images/download.png');
                background-size: cover;
            }
            .btn {
                padding: 8px 30px 8px 30px;
                margin: 0px 20px 0px 0px;
            }
            .btn-default {
                color: #344A5F;
                background: #ffffff;
                font-family: Arial, Helvetica, sans-serif;
            }
            .btn-primary {
                background: transparent;
                border-color: #ffffff;
            }
            #content {
                padding: 3%;
            }
            @media only screen and (max-width: 1000px) and (min-width:300px){
                #side {
                    display: none;
                }
                #content {
                padding: 0%;
                }
            }
            a.btn.btn:hover {
                background: #8828A2;
                color: #ffffff;
                border: #8828A2;
            }
            .container {
                margin: 15%;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-7 col-sm-12" id="content">
                    <h1>Something's wrong here...</h1>
                    <h5>We can't find the page you'are looking for. 
                    <br> Back to previous page or head back to home.</h5>
                    <a href="#" onclick="window.history.back()" class="btn btn-default">Back</a>
                    <a href="{{asset('/home')}}" class="btn btn-primary">Home</a>
                </div>
                <div class="col-md-4" id="side">
                    <img src="{{asset('images/icon404.png')}}" alt="error 404" class="img-responsive">
                </div>
            </div>
        </div>
    </body>
</html>
