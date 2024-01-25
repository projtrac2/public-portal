{{-- <div class="text-center">
    <img src="{{asset('images/flag2.png')}}" alt="" srcset="" height="100vh">
</div> --}}
<div class="row">
    <div class="d-flex justify-content-end mb-3 gap-2">
        <a href="/feedback/{{$project->projid}}"><button class="btn btn-success">Give Feedback</button></a>
        <a href="{{route('projects-view')}}"><button class="btn btn-warning">Back</button></a>
    </div>
    <div class="col-md-8" style="padding-right: 50px; border-right: 1px solid #cbd5e1">
        <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-offset="0">
            <div id="item-1" class="card mb-3" style="background-color: #fecaca; border: 1px solid #cbd5e1;">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12 text-center">
                            <h5 style="margin-bottom: 10px; mb-3">Project Summary</h5>
                            <h3 class="text-center mb-2">{{$project->projname}}</h3>
                            <h6 class="text-center">{{$project->projdesc}}</h6>
                        </div>
                    </div>
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div>
                                <tr>
                                    <td style="padding-top: 10px;">Project Cost:</td>
                                    <?php
                                        $timestamp = strtotime( $project->projstartdate );  
                                        $date_st = date('M d, Y', $timestamp );

                                        $timestampEnd = strtotime( $project->projenddate );  
                                        $date_end = date('M d, Y', $timestampEnd );
                                    ?>
                                    <td style="padding-left: 10px; padding-top: 10px; font-weight:bold">ksh {{number_format($project->projcost)}}</td>
                                </tr>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                <tr>
                                    <td style="padding-top: 10px;">Start Date:</td>
                                    <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">{{$date_st}}</td>
                                </tr>
                            </div>
                            
                        </div>
                        <div class="col-md-4">
                            <div>
                                <tr>
                                    <td style="padding-top: 10px;">End Date:</td>
                                    <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">{{$date_end}}</td>
                                </tr>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- <div id="item-2" class="card mb-3" style="background-color: #fed7aa; border: 1px solid #cbd5e1; font-size: 14px;">
                <div class="card-body">
                    <h5 class="mb-4">Project Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Project Name:</td>
                                        <td style="padding-left: 10px;  font-weight: bold">{{$project->projname}}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 10px;">Project Cost:</td>
                                        <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">ksh {{number_format($project->projcost)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                   
                                    
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div> --}}
    
            <div id="item-3" class="card mb-3" style="background-color: #bbf7d0; border: 1px solid #cbd5e1; font-size: 14px;">
                <div class="card-body">
                    <h5 class="mb-4">Project Location</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Sub-County:</td>
                                        <?php
                                            $subCountiesId = explode(',',$project->projcommunity);
                                        ?>
                                        <td style="padding-left: 10px;  font-weight: bold d-flex gap-2">
                                            @foreach ($subCountiesId as $id)
                                                <?php $subCounty = App\Models\Location::find($id) ?>
                                                <p>{{$subCounty->state}}</p>
                                            @endforeach
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
    
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Ward:</td>
                                        <?php
                                            $wardIds = explode(',',$project->projlga);
                                        ?>
                                        <td style="padding-left: 10px; width: 100%; font-weight: bold d-flex gap-2">
                                            @foreach ($wardIds as $id)
                                                <?php $ward = App\Models\Location::find($id) ?>
                                                <p>{{$ward->state}}</p>
                                            @endforeach
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
    
            <div id="item-4" class="card mb-3" style="background-color: #bfdbfe; border: 1px solid #cbd5e1; font-size: 14px;">
                <div class="card-body">
                    <h5 class="mb-4">Project Sector And Department</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>Sector:</td>
                                        <td style="padding-left: 10px;  font-weight: bold">{{$project->program->section->sector}}</td>
                                    </tr>
                                    <tr>
                                        <?php 
                                            $dept = App\Models\Section::where('stid', '=', $project->program->projdept)->first();
                                        ?>
                                        <td>Department:</td>
                                        @if ($dept)
                                            <td style="padding-left: 10px;  font-weight: bold">{{$dept->sector}}</td>
                                        @else
                                            <td style="padding-left: 10px;  font-weight: bold"></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
    
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="padding-top: 10px;">Status:</td>
                                        <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">{{$project->status->statusname}}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 10px;">Financial Year:</td>
                                        <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">{{$project->financialYear->year}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div id="navbar-example3" class="navbar-light bg-light shadow-none p-3" style="border-radius: 5px">
            <h5 class="mb-3" >In this project</h5>
            <nav class="nav nav-pills flex-column">
                <a class="nav-link" href="#item-1">Summary</a>
                <a class="nav-link" href="#item-2">Details</a>
                <a class="nav-link" href="#item-3">Location</a>
                <a class="nav-link" href="#item-4">Financial</a>
            </nav>
        </div>
    </div>
</div>

