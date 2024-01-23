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
                
            }
        </style>
        <script src="https://kit.fontawesome.com/6557f5a19c.js" crossorigin="anonymous"></script>
    </head>
    <body class="antialiased">
        <script src="{{ asset('assets/js/spinner.js') }}"></script>

        <div class="main-wrapper" id="app">
            <div class="page-wrapper">
              @include('layouts.header')
              <div class="page-content">
                <div class="container">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 p-5 text-center">
                            <img src="{{asset('images/rev2.svg')}}" alt="review" style="height: 40vh;"  srcset="">
                            <div class="pt-4 text-center">
                                <span class="p-2" style="margin-right: 15px; border: 1px solid #3B82F6; border-radius: 50%;">
                                    <img src="{{asset('images/phone-call.svg')}}" alt="" srcset="" style="height: 3vh"> 
                                </span>
                                <span class="text-left"> 074345551</span>
                            </div>
                            <div class="pt-4 ">
                                <span class="p-2 text-center" style="margin-right: 15px; border: 1px solid #3B82F6; border-radius: 100%;">
                                    <img src="{{asset('images/inbox.svg')}}" alt="inbox" srcset="" style="height: 3.2vh"> 
                                </span>
                                <span class="text-left"> proj@mail.com</span>
                            </div>
                            
                        </div>
                        <div class="col-md-6 pt-4">
                            <form class="forms-sample" method="POST" action="{{route('save-feedback')}}">
                                @csrf
                                <input type="hidden" name="project_id" value="{{$project->projid}}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleInputUsername1" class="form-label">Full name</label>
                                            <input name="full_name" type="text" class="form-control" id="full_name" autocomplete="off" placeholder="Full name" style="background-color: #bfdbfe">
                                            <span class="text-danger" id="full_name_error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                                            <input name="email_address" type="text" class="form-control" id="email_address" placeholder="Email" style="background-color: #bfdbfe">
                                            <span class="text-danger" id="email_address_error"></span>

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleInputPassword1" class="form-label">Phone number</label>
                                            <input name="phone_number" type="text" class="form-control" id="phone_number" style="background-color: #bfdbfe" autocomplete="off" placeholder="Phone">
                                            <span class="text-danger" id="phone_number_error"></span>
                                        
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="exampleFormControlSelect1" class="form-label">Feedback type</label>
                                            <select name="feedback_type" class="form-select" id="feedback_type" style="background-color: #bfdbfe">
                                              <option selected disabled>Select...</option>
                                              <option>Complaint</option>
                                              <option>Compliment</option>
                                            </select>
                                            <span class="text-danger" id="feedback_type_error"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Message</label>
                                    <textarea name="message" class="form-control" style="background-color: #bfdbfe" id="message" rows="5"></textarea>
                                    <span id="message_error" class="text-danger"></span>
                                </div>
                               
                                <button type="submit" id="submit-btn" class="btn me-2 text-white" style="background-color: #1d4ed8">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="d-flex flex-row justify-content-center" style="margin-top: 3%">
                        <div style="margin-right: 20px;">
                            <i class="fa-brands fa-facebook" style="color: #172554; font-size: 20px"></i>
                        </div>
                        <div style="margin-right: 20px;">
                            <i class="fa-brands fa-twitter" style="color: #172554; font-size: 20px"></i>
                        </div>
                        <div >
                            <i class="fa-brands fa-facebook" style="color: #172554; font-size: 20px"></i>
                        </div>
                    </div>
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
    <script>
        $(function() {
            $('#submit-btn').on('click', function(e) {
                if (!$('#full_name').val()) {
                    $('#full_name_error').text('field required');
                    e.preventDefault();
                    return;
                } else {
                    $('#full_name_error').text('');
                }

                if (!$('#email_address').val()) {
                    $('#email_address_error').text('field required');
                    e.preventDefault();
                    return;
                } else {
                    $('#email_address_error').text('');
                }

                if (!$('#phone_number').val()) {
                    $('#phone_number_error').text('field required');
                    e.preventDefault();
                    return;
                } else {
                    $('#phone_number_error').text('');
                }


                if($('#feedback_type').find(':selected').text() == 'Select...'){
                    $('#feedback_type_error').text('field required');
                    e.preventDefault();
                    $('#feedback_type').focus();
                    return;
                } else {
                    $('#feedback_type_error').text('');
                }

                if (!$('#message').val()) {
                    $('#message_error').text('field required');
                    e.preventDefault();
                    return;
                } else {
                    $('#message_error').text('');
                }
            })
        })
    </script>
    </body>
</html>
