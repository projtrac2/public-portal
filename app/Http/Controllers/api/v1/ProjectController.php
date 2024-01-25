<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FinancialYear;
use App\Models\Location;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        // remeber to take projects that the id is not supposed to be used
        $projects = Project::with('program')->where([['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        $numOfProjects = $projects->count();
        $numOfCompleted = 0;
        $numOfOnTrack = 0;
        $numOfPendingCompletion = 0;
        $numOfBehindSchedule = 0;
        $numOfAwaitingProcurement = 0;
        $numOfOnHold = 0;
        $numOfCancelled = 0;
        foreach ($projects as $key => $project) {
            if ($project->projstatus == 5) {
                $numOfCompleted += 1;
            }

            if ($project->projstatus == 4) {
                $numOfOnTrack += 1;
            }

            if ($project->projstatus == 3) {
                $numOfPendingCompletion += 1;
            }

            if ($project->projstatus == 11) {
                $numOfBehindSchedule += 1;
            }

            if ($project->projstatus == 1) {
                $numOfAwaitingProcurement += 1;
            }

            if ($project->projstatus == 6) {
                $numOfOnHold += 1;
            }

            if ($project->projstatus == 2) {
                $numOfCancelled += 1;
            }
        }
        
        return response([
            'num_of_projects' => $numOfProjects,
            'num_of_completed' => $numOfCompleted,
            'num_on_track' => $numOfOnTrack,
            'num_pending_completion' => $numOfPendingCompletion,
            'num_behind_schedule' => $numOfBehindSchedule,
            'num_awaiting_procurement' => $numOfAwaitingProcurement,
            'num_completed' => $numOfCompleted,
            'num_cancelled' => $numOfCancelled,
            'num_on_hold' => $numOfOnHold
        ]);
    }

    public function subCountiesAndFinancialYearsData ()
    {
        $subCounties = Location::where('parent', '=', null)->get();
        $fYears = FinancialYear::all();
        return response([
            'subCounties' => $subCounties,
            'fYears' => $fYears,
        ]);
    }

    public function getWards ($sub_county_id) 
    {
        $wards = Location::where('parent', '=', $sub_county_id)->get(['id', 'state']);
        return response($wards);
    }

    /**
     * filter landing page data
     */
    public function query(Request $request)
    {
        // remeber to take projects that the id is not supposed to be used
        $projects = Project::with('program')->where([['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        // remeber to take projects that the id is not supposed to be used
        if ($request->from != 'Select...' && $request->to == 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        }

        if ($request->to != 'Select...' && $request->from == 'Select...') {
            $projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        }

        if ($request->to != 'Select...' && $request->from != 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
            
        }

        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        }

        if ($request->sub_county_id == 'Select...' && $request->ward_id != 'Select...') {
            $projects = Project::where([ ['projstatus','>', 0], ['projstatus', '!=', 3], ['projlga', 'like', $request->ward_id]])->get();
        }

        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projlga', 'like', $request->ward_id], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        }
        //$fYear = FinancialYear::where('id', '=', 2)/*where([['from','=', $request->fyear_start],['to','=', $request->fyear_end]])*/->first();
        $numOfProjects = 0;
        $numOfCompleted = 0;
        $numOfOnTrack = 0;
        $numOfPendingCompletion = 0;
        $numOfBehindSchedule = 0;
        $numOfAwaitingProcurement = 0;
        $numOfOnHold = 0;
        $numOfCancelled = 0;
        $numOfProjects = $projects->count();

        foreach ($projects as $key => $project) {
            if ($project->projstatus == 5) {
                $numOfCompleted += 1;
            }

            if ($project->projstatus == 4) {
                $numOfOnTrack += 1;
            }

            if ($project->projstatus == 3) {
                $numOfPendingCompletion += 1;
            }

            if ($project->projstatus == 11) {
                $numOfBehindSchedule += 1;
            }

            if ($project->projstatus == 1) {
                $numOfAwaitingProcurement += 1;
            }

            if ($project->projstatus == 6) {
                $numOfOnHold += 1;
            }

            if ($project->projstatus == 2) {
                $numOfCancelled += 1;
            }
        }
        return response([
            'num_of_projects' => $numOfProjects,
            'num_of_completed' => $numOfCompleted,
            'num_on_track' => $numOfOnTrack,
            'num_pending_completion' => $numOfPendingCompletion,
            'num_behind_schedule' => $numOfBehindSchedule,
            'num_awaiting_procurement' => $numOfAwaitingProcurement,
            'num_completed' => $numOfCompleted,
            'num_cancelled' => $numOfCancelled,
            'num_on_hold' => $numOfOnHold,
            'from' => $request->from
        ]);
    }

    /**
     * get all projects for projects page
     */
    public function allProjects()
    {
        $projects = Project::with('program')->where([['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        foreach ($projects as $key => $project) {
            $project['link'] = "<a href='/project/$project->projid')}}><i class='fa-solid fa-eye text-success'></i></a>";
            $project['link2'] = "<a href='/feedback/$project->projid')}}><i class='fa-solid fa-comment'></i></i></a>";
            if ($project->projstatus !== 0) {
                $project['fyear'] = $project->financialYear->name;
                if ($project->program->section) {
                    $project['section'] = $project->program->section ?? null;  
                } else {
                    $project['section'] = null;
                }
                $project->status;
                
            } else {
                $project['section'] = 'n/a';
                $project->status;
            }
        }
        return response($projects);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $projects = Project::with('program')->where([['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        if ($request->from != 'Select...' && $request->to == 'Select...') {
            $projects = Project::where([['projstatus','>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from]])->get();
        }

        if ($request->to != 'Select...' && $request->from == 'Select...') {
            $projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        }

        if ($request->to != 'Select...' && $request->from != 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
            
        }

        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        }

        if ($request->sub_county_id == 'Select...' && $request->ward_id != 'Select...') {
            $projects = Project::where([ ['projstatus','>', 0], ['projstatus', '!=', 3], ['projlga', 'like', $request->ward_id]])->get();
        }

        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projlga', 'like', $request->ward_id], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        }

        foreach ($projects as $key => $project) {
            $project->link = "<a href='/project/$project->projid')}}><i class='fa-solid fa-eye text-success'></i></a>";
            $project['link2'] = "<a href='/api/feedback/$project->projid')}}><i class='fa-solid fa-comment'></i></i></a>";
            if ($project->projstatus !== 0) {
                $project->fyear = $project->financialYear->name;
                if ($project->program->section) {
                    $project->section = $project->program->section ?? null;  
                } else {
                    $project->section = null;
                }
                $project->status;
                
            } else {
                $project->fyear = 'n/a';
                $project->status;
            }

        }

        return response($projects);
    }

    /**
     * 
     */
    public function getFeedback ($id) 
    {
        $project = Project::find($id);
        
        return view('reviews.reviews', compact('project'));
    }

    /**
     * 
     */
    public function saveFeedBack(Request $request) 
    {
        $request->validate([
            'full_name' => 'required',
            'email_address' => 'required|email',
            'phone_number' => 'required',
            'feedback_type' => 'required',
            'message' => 'required',
        ]);

        $feedback = new Feedback();
        $feedback->full_name = $request->full_name;
        $feedback->email = $request->email_address;
        $feedback->phone_number = $request->phone_number;
        $feedback->feedback_type = $request->feedback_type;
        $feedback->message = $request->message;
        $feedback->project_id = $request->project_id;
        if ($feedback->save()) {
            return response(true);
        }
        return response(false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
