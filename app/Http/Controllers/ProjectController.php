<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\FinancialYear;
use App\Models\Indicator;
use App\Models\Location;
use App\Models\Marker;
use App\Models\OutputDisaggregation;
use App\Models\Program;
use App\Models\Project;
use App\Models\ProjectDetails;
use App\Models\ProjectSites;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use stdClass;

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
        $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
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
        /**
         * project details === project outputs
         */
        //$project = Project::with('program')->find(Crypt::decrypt($id));
        $project = Project::with('program')->find($id);
        if (!$project) {
            $project = Project::with('program')->find(Crypt::decrypt($id));
        }
        $projectDetails = ProjectDetails::where('projid', '=', $project->projid)->get();
        $markers_array = [];
        foreach ($projectDetails as $key => $details) {
            $indicator = Indicator::where('indid', '=', $details->indicator)->first();
            if ($indicator) {
                $mappingType = $indicator->indicator_mapping_type;
                if ($mappingType == 1) {
                    $disegrigation = OutputDisaggregation::where('outputid', '=', $details->outputid)->get();
                    foreach ($disegrigation as $key => $dis) {
                        $totalSites = ProjectSites::where([['projid', '=', $project->projid], ['site_id', '=', $dis->output_site]])->get();
                        foreach ($totalSites as $key => $site) {
                            $markers = Marker::where([['site_id', '=', $site->site_id], ['projid', '=', $project->projid], ['opid', '=', $details->indid]])->get();
                            array_push($markers_array, ['details' => $details->unique_key, 'site_name' => $site->site, 'markers' => $markers]);
                        }
                    }
                    // add to project output ie project details
                } else {
                    $markers = Marker::where([['opid', '=', $details->id], ['projid', '=', $project->projid]])->get();
                    array_push($markers_array, ['details' => $details->unique_key, 'site_name' => " ", 'markers' => $markers]);
                    // add to project output ie project details
                }
            }
        }
        $project->markers = $markers_array;
        $project->id = Crypt::encrypt($project->projid);
        if (!$project) {
            return redirect()->back()->with('error', 'Project details not found');
        }
        return view('projects.show', compact('project'));
    }

    /**
     * 
     */
    public function getFeedback(Request $request, $id)
    {
        $project = Project::find(Crypt::decrypt($id));
        $back_url = $request->server->getParams()['HTTP_REFERER'];
        $back_route = explode('/', $back_url);
        if (isset($back_route[3]) && isset($back_route[4])) {
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
            return redirect()->route('home')->with('success', 'Thankyou for your feedback');
        }
        return redirect()->back()->with('unsuccess', 'System error please try again');
    }

    public function outputTarget($id)
    {
        $project = Project::find($id);
        $projectDetails = ProjectDetails::where('projid', '=', $project->projid)->get();
        $wards = explode(',', $project->projlga);
        $data = [];
        $target = 0;
        foreach ($projectDetails as $key => $details) {
            $disaggregation = OutputDisaggregation::where([['projid', '=', $project->projid], ['outputid', '=', $details->id]])->get();
            foreach ($disaggregation as $key => $dis) {
                if ($dis->outputstate != 0) {
                    $indicator = Indicator::where('indid', '=', $details->indicator)->first();
                    $ward = Location::where('id', '=', $dis->outputstate)->first();
                    if ($ward) {
                        $obj = new stdClass;
                        $obj->ward_id = $ward->id;
                        $obj->name = $indicator->indicator_name;
                        $obj->ind_id = $indicator->indid;
                        $obj->target = $dis->total_target;
                        array_push($data, $obj);
                    }
                }

                if ($dis->outputstate == 0) {
                    $prjSite = ProjectSites::where([['projid', '=', $project->projid], ['site_id', '=', $dis->output_site]])->first();
                    if ($prjSite) {
                        $ward = Location::where('id', '=', $prjSite->state_id)->first();
                        if ($ward) {
                            $indicator = Indicator::where('indid', '=', $details->indicator)->first();
                            $obj = new stdClass;
                            $obj->ward_id = $ward->id;
                            $obj->name = $indicator->indicator_name;
                            $obj->ind_id = $indicator->indid;
                            $obj->target = $dis->total_target;
                            array_push($data, $obj);
                        }
                    }
                }
            }
        }

        $alldata = [];

        $m_data = [];


        for ($c = 0; $c < count($data); $c++) {
            for ($d = 0; $d < count($data); $d++) {
                if (($data[$c]->ward_id == $data[$d]->ward_id) && ($data[$c]->ind_id == $data[$d]->ind_id) && ($c != $d)) {
                    $data[$c]->target = $data[$c]->target + $data[$d]->target;
                }
            }
        }

        // Initialize an array to store unique items
        $filteredData = [];

        // Loop through the original array
        foreach ($data as $item) {
            // Check if the combination of ward_id and ind_id is already present
            $key = $item->ward_id . '_' . $item->ind_id;

            if (!isset($filteredData[$key])) {
                // If not present, add it to the filtered array
                $filteredData[$key] = $item;
            }
        }
        // Convert the associative array back to indexed array if needed
        $filteredData = array_values($filteredData);
       


        for ($t = 0; $t < count($wards); $t++) {
            $ward = Location::where('id', '=', $wards[$t])->first();
            $obj3 = new stdClass;
            $obj3->ward = $ward->state;
            $obj3->data = [];

            for ($d = 0; $d < count($data); $d++) {
                if ($ward->id == $data[$d]->ward_id) {
                    array_push($obj3->data, $data[$d]);
                }
            }

            array_push($alldata, $obj3);
        }

        dd($alldata);

        return response($alldata);
    }

    public function activityBreakDown() 
    {
        return view('activity-breakdown');
    }
}
// $data_location = [];
// $wards = explode(',', $project->projlga);
// for ($i=0; $i < count($wards); $i++) { 
//     $ward = Location::where('id','=',$wards[$i])->first();
//     $prjSite = ProjectSites::where('projid','=',$project->projid)->get();
//     foreach ($prjSite as $key => $site) {
//         $dataA = [];
//         $disaggregation = OutputDisaggregation::where([['outputstate','=',$site->state_id], ['projid','=', $project->projid]])->get();
//         foreach ($disaggregation as $key => $dis) {
//             $obj = new stdClass;
//             $obj->target = $dis->total_target;
//             array_push($dataA, $obj);
//         }

//     }


//     $obj2 = new stdClass;
//     $obj2->location = $ward->state;
//     $obj2->ward_id = $ward->id;
//     $obj2->data = $dataA;

//     array_push($data_location,$obj2);
// }

// dd($data_location);



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

/**
 * project details === project outputs
 */
        // $project = Project::find($id);
        // $projectDetails = ProjectDetails::where('projid','=', $project->projid)->get();
        // foreach ($projectDetails as $key => $detail) {
        //     $target = 0;
        //     $disegrigation = OutputDisaggregation::where([['outputid', '=', $detail->id],['projid','=', $project->projid]])->get();
        //     foreach ($disegrigation as $key => $dis) {

        //         $target += $dis->total_target;
        //     }
        //     $detail->target = $target;
        // }

        // dd($projectDetails);