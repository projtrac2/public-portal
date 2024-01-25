<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FinancialYear;
use App\Models\Location;
use App\Models\Program;
use App\Models\Project;
use App\Models\Section;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function home()
    {
        $subCounties = Location::where('parent', '=', null)->get();
        $fYears = FinancialYear::all();
        // remeber to take projects that the id is not supposed to be used
        $projects = Project::all();
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
        return view('welcome');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('projects.view');
    }

    public function filterProject(Request $request)  
    {
        $projects = Project::with('program')->where([['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        if ($request->from != 'select...' && $request->to == 'select...') {
            $projects = Project::where('projfscyear', '>=', $request->from)->get();
        }

        if ($request->to != 'select...' && $request->from == 'select...') {
            $projects = Project::where('projfscyear', '<=', $request->to)->get();
        }

        if ($request->to != 'select...' && $request->from != 'select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to]])->get();
        }

        if ($request->sub_county_id != 'select...' && $request->ward_id != 'select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projlga', 'like', $request->ward_id]])->get();
        }

        foreach ($projects as $key => $project) {
            $project->link = "<a href='/project/$project->projid')}}><img src='{{asset('images/folder.svg')}} alt='' srcset=''></a>";
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

    public function query(Request $request)
    {
        // remeber to take projects that the id is not supposed to be used
        $projects = Project::all();
        

        // remeber to take projects that the id is not supposed to be used
        if ($request->from != 'select...' && $request->to == 'select...') {
            $projects = Project::where('projfscyear', '>=', $request->from)->get();
        }

        if ($request->to != 'select...' && $request->from == 'select...') {
            $projects = Project::where('projfscyear', '<=', $request->to)->get();
        }

        if ($request->to != 'select...' && $request->from != 'select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to]])->get();
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
        $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projlga', 'like', $request->ward_id]])->get();
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
        return response([$numOfProjects, $numOfCompleted, $numOfOnTrack, $numOfPendingCompletion, $numOfBehindSchedule, $numOfAwaitingProcurement, $numOfOnHold, $numOfCancelled, $projects]);
        
    }

    public function getWards(Request $request)
    {
        $wards = Location::where('parent', '=', $request->sub_county_id)->get(['id', 'state']);
        return response($wards);
    }

    /**
     * @param Project $id
     */
    public function show($id) 
    {
        $project = Project::with('program')->find($id);
        if (!$project) {
           return redirect()->back()->with('error', 'Project details not found');
        }
        
        return view('projects.show', compact('project'));
    }

    /**
     * 
     */
    public function getFeedback (Request $request,$id) 
    {
        $project = Project::find($id);
        $back_url = $request->server->getParams()['HTTP_REFERER'];
        $back_route = explode('/', $back_url);
        if (isset($back_route[3]) && isset($back_route[4]) ) {
            $back_route = '/' . $back_route[3] . '/' . $back_route[4];
        }

        if (isset($back_route[3]) && !isset($back_route[4])) {
            $back_route = '/' . $back_route[3];
        }
        
        return view('reviews.reviews', compact('project', 'back_route'));
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
            return redirect()->route('home')->with('success','Thankyou for your feedback');
        }
        dd('p');
        return redirect()->back()->with('unsuccess','System error please try again');
    }
}




// if (!empty($from)) {
        //     $projects = Project::where([['projfscyear', '>=', $from]])->get();
        //     if (!empty($to)) {
        //         $projects = Project::where([['projfscyear', '>=', $from], ['projfscyear', '>=', $to], []])->get();
        //     }
        // }


 // $projects = Program::whereHas('projects', function($query) use ($fYear) {
            //     $query->where('projyear','=',$fYear->id);
            // })->where('dept','=', $sect->id)->get();


        // if ($level1_id) {
                //     $community = explode(',', $project->projcommunity);

                //     if (in_array($level1_id, $community)) {
                //         if ($level2_id) {
                //             $wards = explode(',', $project->projward);
                //             if (in_array($level2_id, $wards)) {
                //                 if ($level2_id) {

                //                 }
                //             }
                //         }
                //     }
                //     $community = explode(',', $project->projcommunity);
                // }