<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="description" content="Responsive Laravel Admin Dashboard Template based on Bootstrap 5">
        <meta name="author" content="NobleUI">
        <meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, laravel, theme, front-end, ui kit, web">

        <title>NobleUI - Laravel Admin Dashboard Template</title>

        <!-- Fonts -->
        <script src="https://kit.fontawesome.com/6557f5a19c.js" crossorigin="anonymous"></script>


        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
        <!-- End fonts -->
        
        <!-- CSRF Token -->
        <meta name="_token" content="{{ csrf_token() }}">
        
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        <!-- plugin css -->
        <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
        <style>
            .page-wrapper {
                background-image: url("{{asset('images/flag1.png')}}") !important;
            }
            .legal {
                bottom: 0;
                width: 100%;
                background-color: #03A9F4;
                border-top: 1px solid #eee;
                padding: 5px;
                overflow: hidden;
                color: black;
                display: flex;
            }
        </style>
      </head>
    <body class="antialiased">
        <script src="{{ asset('assets/js/spinner.js') }}"></script>

        <div class="main-wrapper" id="app">
            <div class="page-wrapper">
              @include('layouts.header')
              <div class="page-content">
               
                @include('projects.projects_components.show_data')
              </div>
              <div class="legal">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 copyright">
                    ProjTrac M&amp;E - Your Best Result-Based Monitoring &amp; Evaluation System.
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 version" align="right">
                    Copyright @ 2017 -2024. ProjTrac Systems Ltd.
                </div>
              </div>
            </div>
        </div>
        

        <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <!-- end base js -->

    <!-- plugin js -->
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    
    </body>
</html>
