{{-- <div class="text-center">
    <img src="{{asset('images/flag2.png')}}" alt="" srcset="" height="100vh">
</div> --}}
<div class="row">
    <div class="col-md-8" style="padding-right: 50px; border-right: 1px solid #cbd5e1">
        <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-offset="0">
            <div id="item-1" class="card mb-3" style="background-color: #fecaca; border: 1px solid #cbd5e1;">
                <div class="card-body">
                    <h5 style="margin-bottom: 10px;">Project Summary</h5>
                    <p>{{$project->projdesc}}</p>
                </div>
            </div>

            <div id="item-2" class="card mb-3" style="background-color: #fed7aa; border: 1px solid #cbd5e1; font-size: 14px;">
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
                                   
                                    <tr>
                                        <td style="padding-top: 10px;">Start Date:</td>
                                        <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">{{$project->projstartdate}}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-top: 10px;">End Date:</td>
                                        <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">{{$project->projenddate}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
    
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
                                        <td style="padding-top: 10px;">Ward:</td>
                                        <?php
                                            $wardIds = explode(',',$project->projlga);
                                        ?>
                                        <td style="padding-left: 10px; padding-top: 10px; font-weight: bold d-flex gap-2">
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
                    <h5 class="mb-4">Project Financial Year && Status</h5>
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

