<?php

namespace App\Http\Controllers;

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
        return view('welcome', compact('subCounties', 'fYears', 'numOfProjects', 'numOfCompleted', 'numOfOnTrack', 'numOfPendingCompletion', 'numOfBehindSchedule', 'numOfAwaitingProcurement', 'numOfOnHold', 'numOfCancelled'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCounties = Location::where('parent', '=', null)->get();
        $fYears = FinancialYear::all();
        //$programs = Program::with('projects')->where([['projstatus','!=', 1], ['projstatus', '!=', 2]])->get();
        // $projects = Project::with('program')->where([['projstatus','!=', 1], ['projstatus', '!=', 2]])->get();
        // foreach($projects as $project){
        //     $department = $project->program->section;
        //     $year = $project->financialYear->name;
        //     $status = $project->status;
        // }
        // dd($projects);
        return view('projects.view', compact('subCounties', 'fYears'));
    }

    public function query(Request $request)
    {
        // remeber to take projects that the id is not supposed to be used
        $projects = Project::all();
        // if (!empty($from)) {
        //     $projects = Project::where([['projfscyear', '>=', $from]])->get();
        //     if (!empty($to)) {
        //         $projects = Project::where([['projfscyear', '>=', $from], ['projfscyear', '>=', $to], []])->get();
        //     }
        // }

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
}







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