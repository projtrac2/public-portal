<div class="row">
    <div class="d-flex justify-content-end mb-3 gap-2">
        <a href="/feedback/{{$project->id}}"><button class="btn btn-success">Give Feedback</button></a>
        <a href="{{route('projects-view')}}"><button class="btn btn-warning">Back</button></a>
    </div>
    <div class="col-md-6" style="padding-right: 50px; border-right: 1px solid #cbd5e1">
        <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-offset="0">
            <div id="item-1" class="card mb-3" style="background-color: #fecaca; border: 1px solid #cbd5e1;">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <h3 class="pr-4">Project Name:</h3>
                                <h3 class="mb-2">{{$project->projname}}</h3>
                            </div>
                           <div class="d-flex gap-2 mb-2">
                                <h4 class="pr-4">Project Description:</h4>
                                <h4>{{$project->projdesc}}</h4>
                           </div>
                            <div class="d-flex gap-2">
                                <h6 class="pr-4">Financial Year:</h6>
                                <h6>{{$project->financialYear->year}}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-6">
                            <div>
                                <tr>
                                    <td style="padding-top: 10px;"><span style="font-weight: bold">Status:</span></td>
                                    <td style="padding-left: 10px; padding-top: 10px;">{{$project->status->statusname}}</td>
                                </tr>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <tr>
                                    <td style="padding-top: 10px;"><span style="font-weight: bold">Project Cost:</span></td>
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
                        <div class="col-md-6">
                            <div>
                                <tr>
                                    <td style="padding-top: 10px;"><span style="font-weight: bold">Start Date:</span></td>
                                    <td style="padding-left: 10px; padding-top: 10px; font-weight: bold">{{$date_st}}</td>
                                </tr>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div>
                                <tr>
                                    <td style="padding-top: 10px;"><span style="font-weight: bold">End Date:</span></td>
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
                                        <td><span style="font-weight: bold">Sub-County:</span></td>
                                        <?php
                                            $subCountiesId = explode(',',$project->projcommunity);
                                        ?>
                                        <td style="padding-left: 10px;  font-weight: bold d-flex gap-2">
                                            @foreach ($subCountiesId as $key => $id)
                                                <?php $subCounty = App\Models\Location::find($id) ?>
                                                <span>{{$subCounty->state}}@if(count($subCountiesId) - 1 != $key),@endif</span>
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
                                        <td><span style="font-weight: bold">Ward:</span></td>
                                        <?php
                                            $wardIds = explode(',',$project->projlga);
                                        ?>
                                        <td style="padding-left: 10px; width: 100%; font-weight: bold d-flex gap-2">
                                            @foreach ($wardIds as $key => $id)
                                                <?php $ward = App\Models\Location::find($id) ?>
                                                <span>{{$ward->state}}@if(count($subCountiesId) - 1 != $key),@endif</span>
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
                        <div class="col-md-12">
                            <table>
                                <tbody>
                                    <tr>
                                        <td><span style="font-weight: bold">Sector:</span></td>
                                        <td style="padding-left: 10px;">{{$project->program->section->sector}}</td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
    
                        <div class="col-md-12">
                            <table>
                                <tbody>
                                    <tr>
                                        <?php 
                                            $dept = App\Models\Section::where('stid', '=', $project->program->projdept)->first();
                                        ?>
                                        <td><span style="font-weight: bold">Department:</span></td>
                                        @if ($dept)
                                            <td style="padding-left: 10px;">{{$dept->sector}}</td>
                                        @else
                                            <td style="padding-left: 10px;"></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div id="item-4" class="card mb-3" style="background-color: #fafebf; border: 1px solid #cbd5e1; font-size: 14px;">
                <div class="card-body">
                    <h5 class="mb-4">Output Distribution</h5>
                    <div class="row" id="output-data">
                        {{-- <div class="col-md-12 mb-2">
                            <h5>Kipsomba</h5>
                        </div>
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="padding-left: 10px;  font-weight: bold">Output A:</td>
                                        <td >4</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 10px;  font-weight: bold">Output A:</td>
                                        <td >4</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="padding-left: 10px;  font-weight: bold">Output A:</td>
                                        <td >4</td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 10px;  font-weight: bold">Output A:</td>
                                        <td >4</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <div class="mb-2">
                <label for="search-indicator">Filter outputs</label>
                <select id="search-indicator" class="form-select" style="background-color: #cbd5e1">
                    <option value="Select...">select...</option>
                </select>
            </div>
            <div id="map"></div>
        </div>
    </div>
</div>

