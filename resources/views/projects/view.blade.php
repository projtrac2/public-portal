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
        <link href="{{ asset('assets/plugins/prismjs/prism.css') }}" rel="stylesheet" />
        <!-- CSRF Token -->
        <meta name="_token" content="{{ csrf_token() }}">
        
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
        <!-- plugin css -->
        <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
        <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
        <style>
            #map {
                height: 400px; /* The height is 400 pixels */
                width: 100%; /* The width is the width of the web page */
            }
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
                @include('welcome_components.search_area')
                @include('projects.projects_components.table')
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
       
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
        <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        <!-- end base js -->
        
        <!-- common js -->
        <script src="{{ asset('assets/js/template.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
        <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/data-table.js') }}"></script> --}}
        <script async>
            $(function () {
                


                let table;
                $.ajax({
                    type: "GET",
                    url: "/api/all-projects",
                    processData: false,
                    contentType: false,
                    cache: false,
                    error: function(data){
                        console.log(data);
                    },
                    success: function (message) {
                       
                        console.log(message);
                        let t = 1;
                        for (let i = 0; i < message.length; i++) {
                            let status = '';
                            let financialYear = '';
                            let sector = '';
                            if (message[i].status != null) {
                                console.log(message[i].status.statusname);
                                    status = message[i].status.statusname;
                            }

                            let data = `
                                <tr>
                                    <td>${t}</td>
                                    <td data-bs-toggle="tooltip" data-bs-title="Default tooltip">${message[i].projname}</td>
                                    <td>${message[i].location} - ${message[i].ward}</td>
                                    <td>${status}</td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            ${message[i].link}
                                            ${message[i].link2}
                                        </div>
                                    </td>
                                </tr>
                            `;
                            $('#proj-table').append(data);
                            t++;
                        }

                        table = $('#dataTableExample').DataTable({
                            "aLengthMenu": [
                                [10, 30, 50, -1],
                                [10, 30, 50, "All"]
                            ],
                            "iDisplayLength": 10,
                            "language": {
                                search: ""
                            }
                            });
                        $('#dataTableExample').each(function() {
                            var datatable = $(this);
                            // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                            search_input.attr('placeholder', 'Search');
                            search_input.removeClass('form-control-sm');
                            // LENGTH - Inline-Form control
                            var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                            length_sel.removeClass('form-control-sm');
                        });
                    }
                });

                $.ajax({
                    type: "GET",
                    url: "/api/sub-counties-financial-years",
                    processData: false,
                    contentType: false,
                    cache: false,
                    error: function(data){
                        console.log(data);
                    },
                    success: function (response) {
                        for (let i = 0; i < response.fYears.length; i++) {
                            let option = `
                                <option value="${response.fYears[i].id}">${response.fYears[i].year}</option>
                            `;
                            $('#from').append(option);
                            $('#to').append(option);
                        }

                        for (let i = 0; i < response.subCounties.length; i++) {
                            let option = `
                                <option value="${response.subCounties[i].id}">${response.subCounties[i].state}</option>
                            `;
                            $('#subCounty').append(option);
                        }
                    }
                });

                $('#subCounty').on('change', getWards);
                function getWards() {
                    let subCountyId = $('.subCounty').find(":selected").val();
                    console.log(subCountyId);
                    $.ajax({
                        type: "GET",
                        url: "/api/get-wards/"+subCountyId,
                        processData: false,
                        contentType: false,
                        cache: false,
                        error: function(data){
                            console.log(data);
                        },
                        success: function (message) {
                            $('#ward').children().remove();
                            $('#ward').append('<option>Select...</option>');
                            for (let i = 0; i < message.length; i++) {
                                let data = `<option value="${message[i].id}">${message[i].state}</option>`;
                                $('#ward').append(data);                        
                            }
                        }
                    });
                }

                //$('#filter-btn').on('click', filterProjects);
                $('#from').on('change', filterProjects);
                $('#to').on('change', filterProjects);
                $('#subCounty').on('change', filterProjects);
                $('#ward').on('change', filterProjects);
                function filterProjects() {
                    let subCountyId = $('.subCounty').find(":selected").val();
                    let wardId = $('#ward').find(":selected").val();
                    let from = $('#from').find(":selected").val();
                    let to = $('#to').find(":selected").val();

                    let data = new FormData();
                    data.append('_token', '{{csrf_token()}}');
                    data.append('sub_county_id', subCountyId);
                    data.append('ward_id', wardId);
                    data.append('from', from);
                    data.append('to', to);
                    $.ajax({
                        type: "POST",
                        url: "/api/all-projects/filter",
                        processData: false,
                        contentType: false,
                        cache: false,
                        data: data,
                        error: function(data){
                            console.log(data);
                        },
                        success: function (message) {
                            console.log(message);
                            $('#proj-table').children().remove();
                            let t = 1;
                            $('#dataTableExample').DataTable().clear();
                            $('#dataTableExample').DataTable().destroy();
                            for (let p = 0; p < message.length; p++) {
                                console.log(p);
                                let status = '';
                                
                                if (message[p].status != null) {
                                    console.log(message[p].status.statusname);
                                     status = message[p].status.statusname;
                                }

                            
                                var msg = message[p].projid;
                                var link = "<a href={{route('project-show', "message[p].id")}}><img src='{{asset('images/folder.svg')}} alt='' srcset=''></a>";
                                var aLink = '<a href={{route("project-show",'+msg+')}}><img src="{{asset("images/folder.svg")}} alt="" srcset=""></a>';
                                
                                let data = `
                                    <tr>
                                        <td>${t}</td>
                                        <td>${message[p].projname}</td>
                                        <td>${message[p].location} - ${message[p].ward}</td>
                                        <td>${status}</td>
                                        <td>
                                            <div class="d-flex gap-3">
                                                ${message[p].link}
                                                ${message[p].link2}
                                            </div>
                                        </td>
                                    </tr>
                                `;
                                console.log(data);
                                $('#proj-table').append(data);   
                                t++;                     
                            }
                            table = $('#dataTableExample').DataTable({
                            "aLengthMenu": [
                                [10, 30, 50, -1],
                                [10, 30, 50, "All"]
                            ],
                            "iDisplayLength": 10,
                            "language": {
                                search: ""
                            }
                            });
                        $('#dataTableExample').each(function() {
                            var datatable = $(this);
                            // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                            var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                            search_input.attr('placeholder', 'Search');
                            search_input.removeClass('form-control-sm');
                            // LENGTH - Inline-Form control
                            var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                            length_sel.removeClass('form-control-sm');
                        });
                        }
                    })
                }
            })
    </script>
    </body>
</html>
