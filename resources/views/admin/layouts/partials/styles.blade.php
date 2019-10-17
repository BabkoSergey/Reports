<!-- Bootstrap 3.3.7 -->  
<link href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Font Awesome -->
<link href="{{ asset('/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

<!-- Ionicons -->
<link href="{{ asset('/bower_components/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">

<!-- select2 -->
<link rel="stylesheet" href="{{ asset('/bower_components/select2/dist/css/select2.min.css') }}">

<!-- Theme style -->
<link href="{{ asset('/dist/css/AdminLTE.min.css') }}" rel="stylesheet">
<!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
<link href="{{ asset('/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<!-- jQuery UI -->
<link href="{{ asset('/plugins/jquery-ui-1.12.1.custom/jquery-ui.css') }}" rel="stylesheet">

<!-- APP -->
<link href="{{ asset('/css/admin.css') }}" rel="stylesheet">
<link href="{{ asset('/css/flags.min.css') }}" rel="stylesheet">

@stack('styles')