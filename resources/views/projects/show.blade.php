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
             #map {
                height: 600px; /* The height is 400 pixels */
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
    <script async>
        (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
          key: "AIzaSyDiyrRpT1Rg7EUpZCUAKTtdw3jl70UzBAU",
          v: "weekly",
          // Use the 'v' parameter to indicate the version to use (weekly, beta, alpha, etc.).
          // Add other bootstrap parameters as needed, using camel case.
        });
      </script>
      
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
    <script async>
        $(function () {
                
                let map;
                let filteredDataIndicators = [];
                let markers = [];
                let flightPathArray = [];
                let bermudaTriangleArray = [];
                let filteredIndicatorsCoords = [];

                async function initMap() {
                    // The location of Uluru
                    const center = { lat: -1.286389, lng: 36.817223 };
                    // Request needed libraries.
                    //@ts-ignore
                    const { Map } = await google.maps.importLibrary("maps");
                    

                    map = new Map(document.getElementById("map"), {
                        zoom: 10,
                        center: center,
                        mapId: "DEMO_MAP_ID",
                    });


                }

                initMap();

                let mData = [];

                

                $.ajax({
                    type: "GET",
                    url: "/api/output-targets/"+"{{$project->projid}}",
                    processData: false,
                    contentType: false,
                    cache: false,
                    error: function(data){
                        console.log(data.responseText);
                    },
                    success: function (response) {
                        

                        for (let i = 0; i < response.length; i++) {
                            let temp = `
                                    <div class="col-md-12 mb-2">
                                        <h5>${response[i].ward}</h5>
                                        <div id="ward-data-${i}" class="row"></div>
                                    </div>
                            `;
                            $('#output-data').append(temp);

                            for (let t = 0; t < response[i].data.length; t++) {
                                let obj = {
                                    location: response[i].ward,
                                    indicator: response[i].data[t].name,
                                    mapping_type: response[i].data[t].map_type,
                                    markers: response[i].data[t].markers,
                                }
                                mData.push(obj);
                                let template = `
                                    <div class="col-md-6">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td style="padding-left: 10px;  font-weight: bold">${response[i].data[t].name}:</td>
                                                    <td >${response[i].data[t].target} (${response[i].data[t].si_unit})</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                `;

                                $(`#ward-data-${i}`).append(template);
                            }
                            
                        }

                        let indicators = [];

                        for (let s = 0; s < mData.length; s++) {
                            indicators.push(mData[s]['indicator']);

                            if (mData[s].mapping_type == 1) {
                                let position;
                                for (let t = 0; t < mData[s].markers.length; t++) {
                                    position = { lat: mData[s].markers[t].lat - 0, lng: mData[s].markers[t].lng }
                                    let marker = new google.maps.Marker({
                                        position: position,
                                        map,
                                        title: "Hello World!",
                                        label: 'project location'
                                    });

                                    let contentString = `
                                        <div class="p-2">
                                            <h5 class="mb-2" style="color:blue">${mData[s].indicator}</h5>
                                            <h6>Location: ${mData[s].location}</h6>
                                        </div>
                                    `;

                                    let infoWindow = new google.maps.InfoWindow({
                                        content: contentString,
                                        ariaLabel: mData[s].indicator,
                                    });

                                    marker.addListener("click", () => {
                                        infoWindow.open({
                                            anchor: marker,
                                            map,
                                        });
                                    });

                                    markers.push(marker);
                                }
                            }

                            if (mData[s].mapping_type == 2) {
                                let flightPlanCoordinates = [];
                                for (let t = 0; t < mData[s].markers.length; t++) {
                                    let coords = { lat: mData[s].markers[t].lat - 0, lng: mData[s].markers[t].lng }
                                    flightPlanCoordinates.push(coords);
                                }

                                flightPath = new google.maps.Polyline({
                                    path: flightPlanCoordinates,
                                    geodesic: true,
                                    strokeColor: "#FF0000",
                                    strokeOpacity: 1.0,
                                    strokeWeight: 2,
                                });

                                flightPath.setMap(map);

                                flightPathArray.push(flightPath);
                               
                            }

                            if (mData[s].mapping_type == 3) {
                                let triangleCoords = [];

                                for (let t = 0; t < mData[s].markers.length; t++) {
                                    let coords = { lat: mData[s].markers[t].lat - 0, lng: mData[s].markers[t].lng }
                                    triangleCoords.push(coords);
                                }

                                // Construct the polygon.
                                bermudaTriangle = new google.maps.Polygon({
                                    paths: triangleCoords,
                                    strokeColor: "#FF0000",
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: "#FF0000",
                                    fillOpacity: 0.35,
                                });

                                bermudaTriangle.setMap(map);

                                bermudaTriangleArray.push(bermudaTriangle);
                            }
                            
                        }

                        console.log(flightPathArray);

                        let filteredIndicators = [];

                        for (let r = 0; r < indicators.length; r++) {
                            let key = indicators[r];

                            if (!filteredIndicators[key]) {
                                filteredIndicators[key] = indicators[r];
                            }
                        }
                        let indexedArray = Object.values(filteredIndicators);
                       
                        for (let l = 0; l < indexedArray.length; l++) {
                            let temp = `
                                <option value="${indexedArray[l]}">${indexedArray[l]}</option>
                            `
                            $('#search-indicator').append(temp);
                        }


                        $('#search-indicator').on('change', (e) => {
                            let value = $('#search-indicator').val();
                                for (let u = 0; u < bermudaTriangleArray.length; u++) {
                                    bermudaTriangleArray[u].setMap(null);                                
                                }

                                bermudaTriangleArray = [];
                            console.log(value);
                            if (value == 'Select...') {
                                mapData(mData);
                            } else {
                                filteredIndicatorsCoords = [];
                                for (let p = 0; p < mData.length; p++) {
                                    if (mData[p].indicator == value) {
                                        filteredIndicatorsCoords.push(mData[p]);
                                    }
                                }
                                mapData(filteredIndicatorsCoords);
                            }
                        });
                    }
                });

                function mapData(mData) {
                    console.log(mData);
                    for (let u = 0; u < markers.length; u++) {
                        markers[u].setMap(null);                                
                    }

                    markers = [];

                    console.log(flightPathArray.length);


                    for (let u = 0; u < flightPathArray.length; u++) {
                        flightPathArray[u].setMap(null);                                
                    }

                    flightPathArray = [];
                    
                    for (let s = 0; s < mData.length; s++) {
                            if (mData[s].mapping_type == 1) {
                                let position;
                                for (let t = 0; t < mData[s].markers.length; t++) {
                                    position = { lat: mData[s].markers[t].lat - 0, lng: mData[s].markers[t].lng }
                                    let marker = new google.maps.Marker({
                                        position: position,
                                        map,
                                        title: "Hello World!",
                                        label: 'project location'
                                    });
                                    markers.push(marker);
                                }
                                var bounds = new google.maps.LatLngBounds();
                                bounds.extend(position);
                                map.fitBounds(bounds);
                                map.setZoom(10);
                            }

                            if (mData[s].mapping_type == 2) {
                                let locBound = ''; 
                                let flightPlanCoordinates = [];
                                for (let t = 0; t < mData[s].markers.length; t++) {
                                    let coords = { lat: mData[s].markers[t].lat - 0, lng: mData[s].markers[t].lng }
                                    if (t == 0) {
                                        locBound = coords;
                                    }
                                    flightPlanCoordinates.push(coords);
                                }

                                flightPath = new google.maps.Polyline({
                                    path: flightPlanCoordinates,
                                    geodesic: true,
                                    strokeColor: "#FF0000",
                                    strokeOpacity: 1.0,
                                    strokeWeight: 2,
                                });

                                flightPath.setMap(map);


                                flightPathArray.push(flightPath);

                                var bounds = new google.maps.LatLngBounds();
                                bounds.extend(locBound);
                                map.fitBounds(bounds);
                                map.setZoom(12);

                            }

                            if (mData[s].mapping_type == 3) {
                                let triangleCoords = [];

                                for (let t = 0; t < mData[s].markers.length; t++) {
                                    let coords = { lat: mData[s].markers[t].lat - 0, lng: mData[s].markers[t].lng }
                                    triangleCoords.push(coords);
                                }

                                // Construct the polygon.
                                bermudaTriangle = new google.maps.Polygon({
                                    paths: triangleCoords,
                                    strokeColor: "#FF0000",
                                    strokeOpacity: 0.8,
                                    strokeWeight: 2,
                                    fillColor: "#FF0000",
                                    fillOpacity: 0.35,
                                });

                                bermudaTriangle.setMap(map);

                                bermudaTriangleArray.push(bermudaTriangle);
                            }
                            
                    }
                }

        })
    </script>
    </body>
</html>
