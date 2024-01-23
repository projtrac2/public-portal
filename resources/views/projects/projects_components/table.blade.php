<div class="row">
    <div class="col-md-12 grid-margin stretch-card">  
        <div class="card">
            <div class="card-body">
                <h6 class="card-title" style="color: #1d4ed8">Projects Table</h6>
                <div class="table-responsive p-4">
                    <table class="table table-bordered table-striped" id="dataTableExample">
                        <thead>
                            <tr>
                                <th style="color: black">#</th>
                                <th style="color: black">Project Name</th>
                                <th style="color: black">Department</th>
                                <th style="color: black">Cost</th>
                                <th style="color: black">Year</th>
                                <th style="color: black">Status</th>
                                <th style="color: black">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="proj-table">
                            <?php $num = 1; ?>
                            @foreach ($projects as $project)
                            <tr>
                                <td>{{$num}}<?php $num++ ?></td>
                                <td>{{$project->projname}}</td>
                                <td>{{$project->program->section->sector}}</td>
                                <td>{{ number_format($project->projcost, 2)}}</td>
                                <td>{{$project->financialYear->year}}</td>
                                <td>
                                    @if ($project->projstatus !== 0)
                                        {{$project->status->statusname}}</td>
                                    @else
                                        n/a
                                    @endif
                                <td>
                                    <div class="d-flex gap-3">
                                        <a href="{{route('project-show', $project->projid)}}"><img src="{{asset('images/folder.svg')}}" alt="folder" style="height: 20px"></a>
                                        <a href="{{route('get-feedback', $project->projid)}}"><img src="{{asset('images/message-circle.svg')}}" alt="folder"  style="height: 20px; color: red"></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>