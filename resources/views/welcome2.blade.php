<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Laravel 5</div>
            </div>
        </div>
        <script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
        <script type="text/javascript">
            var i = 1;
            function goExportCsv(){
                $.ajax({
                    url: '/exportcsv?p='+i,
                    success: function(d){
                        $('.title').append('<br>'+d);
                        i++;
                        goExportCsv();
                    },
                    error: function(d){
                        $('.title').append('<br>Error ('+i+')');
                        i++;
                        goExportCsv();
                    }
                });
            }
            $(document).ready(function(){
                goExportCsv();
            });
        </script>
    </body>
</html>
