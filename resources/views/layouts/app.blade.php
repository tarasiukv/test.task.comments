<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Text massager</title>
    <link rel="icon" href="favicon.ico" type="image/x-icon"> <!-- Favicon-->
{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>--}}

    @vite('resources/css/app.css')
</head>
<body class="hold-transition dark-skin theme-primary sidebar-mini fixed">
<div id="app"></div>

@vite('resources/js/app_user.js')

</body>
</html>
