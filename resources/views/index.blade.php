<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <link href="{{ asset('assets/fontawesome611/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/brands.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/fontawesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/regular.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/solid.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/svg-with-js.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/v4-font-face.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/v4-shims.min.css') }}" rel="stylesheet">
    <link href="{{ asset('../../fontawesome611/css/v5-font-face.min.css') }}" rel="stylesheet">
    <link href="{{asset('../../css/magazinstyle.css')}}" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-3.6.0.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <link href="{{ asset('../../css/core-style.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Bubbles&display=swap" rel="stylesheet" />


</head>

<body>
    <div id="app">
        <router-view></router-view>
    </div>
    <script src="{{asset('js/app.js')}}"></script>

    <script src="{{ asset('../../js/jquery/jquery-2.2.4.min.js') }}"></script>
    <script src="{{ asset('../../js/popper.min.js') }}"></script>
    <script src="{{ asset('../../js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('../../js/plugins.js') }}"></script>

</body>

</html>