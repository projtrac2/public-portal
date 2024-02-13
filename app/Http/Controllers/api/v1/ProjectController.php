<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\FinancialYear;
use App\Models\Indicator;
use App\Models\Location;
use App\Models\Marker;
use App\Models\MeasurementUnit;
use App\Models\OutputDisaggregation;
use App\Models\Program;
use App\Models\Project;
use App\Models\ProjectDetails;
use App\Models\ProjectSites;
use App\Models\Section;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use stdClass;

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
        $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
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

    public function subCountiesAndFinancialYearsData()
    {
        $subCounties = Location::where('parent', '=', null)->get();
        $fYears = FinancialYear::all();
        return response([
            'subCounties' => $subCounties,
            'fYears' => $fYears,
        ]);
    }

    public function getWards($sub_county_id)
    {
        $wards = Location::where('parent', '=', $sub_county_id)->get(['id', 'state']);
        return response($wards);
    }

    /**
     * filter landing page data
     */
    public function query(Request $request)
    {
        $financialYears = FinancialYear::all();
        $subCounties = Location::where('parent', '=', null)->get();
        // remeber to take projects that the id is not supposed to be used
        $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        // remeber to take projects that the id is not supposed to be used
        //from
        if ($request->from != 'Select...' && $request->to == 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            $financialYears = FinancialYear::where('id', '>=', $request->from)->get();
        }

        // to

        if ($request->to != 'Select...' && $request->from == 'Select...') {
            $projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            $financialYears = FinancialYear::where('id', '<=', $request->to)->get();
        }

        // to from
        if ($request->to != 'Select...' && $request->from != 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            $financialYears = FinancialYear::where('id', '<=', $request->to)->get();
        }

        // sub county
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to == 'Select...' && $request->from == 'Select...') {
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            $projects = new Collection();
            foreach ($prjs as $key => $project) {
                $sub_county_id = explode(',', $project->projcommunity);
                $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
                if ($sub_county_ids) {
                    $projects->push($project);
                }
            }
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        }

        // to sub county
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to != 'Select...' && $request->from == 'Select...') {
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '<=', $request->to]])->get();
            $projects = new Collection();
            foreach ($prjs as $key => $project) {
                $sub_county_id = explode(',', $project->projcommunity);
                $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
                if ($sub_county_ids) {
                    $projects->push($project);
                }
            }
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        }

        // form sub county
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to == 'Select...' && $request->from != 'Select...') {
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from]])->get();
            $projects = new Collection();
            foreach ($prjs as $key => $project) {
                $sub_county_id = explode(',', $project->projcommunity);
                $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
                if ($sub_county_ids) {
                    $projects->push($project);
                }
            }
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        }

        // to from subcounty

        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to != 'Select...' && $request->from != 'Select...') {
            $prjs = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            $projects = new Collection();
            foreach ($prjs as $key => $project) {
                $sub_county_id = explode(',', $project->projcommunity);
                $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
                if ($sub_county_ids) {
                    $projects->push($project);
                }
            }

            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        }

        // ward sub county
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to == 'Select...' && $request->from == 'Select...') {
            $projects = new Collection();
            $prjsSubCounty = new Collection();
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            foreach ($prjs as $key => $project) {
                $ward_id = explode(',', $project->projlga);
                $ward_ids = in_array($request->ward_id, $ward_id);
                if ($ward_ids) {
                    $prjsSubCounty->push($project);
                }
            }

            foreach ($prjsSubCounty as $key => $proj) {
                $ward_id = explode(',', $proj->projlga);
                $ward_ids = in_array($request->ward_id, $ward_id);
                if ($ward_ids) {
                    $projects->push($project);
                }
            }
        }

        // ward sub county from
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to == 'Select...' && $request->from != 'Select...') {
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from]])->get();
            $prjsSubCounty = new Collection();
            $projects = new Collection();
            foreach ($prjs as $key => $project) {
                $sub_county_id = explode(',', $project->projcommunity);
                $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
                if ($sub_county_ids) {
                    $prjsSubCounty->push($project);
                }
            }

            foreach ($prjsSubCounty as $key => $proj) {
                $ward_id = explode(',', $proj->projlga);
                $ward_ids = in_array($request->ward_id, $ward_id);
                if ($ward_ids) {
                    $projects->push($project);
                }
            }

            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        }
        // ward  to sub county
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to != 'Select...' && $request->from == 'Select...') {
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '<=', $request->to]])->get();
            $prjsSubCounty = new Collection();
            $projects = new Collection();
            foreach ($prjs as $key => $project) {
                $sub_county_id = explode(',', $project->projcommunity);
                $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
                if ($sub_county_ids) {
                    $prjsSubCounty->push($project);
                }
            }

            foreach ($prjsSubCounty as $key => $proj) {
                $ward_id = explode(',', $proj->projlga);
                $ward_ids = in_array($request->ward_id, $ward_id);
                if ($ward_ids) {
                    $projects->push($project);
                }
            }

            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        }
        // ward from to sub county
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to != 'Select...' && $request->from != 'Select...') {
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to]])->get();
            $prjsSubCounty = new Collection();
            $projects = new Collection();
            foreach ($prjs as $key => $project) {
                $sub_county_id = explode(',', $project->projcommunity);
                $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
                if ($sub_county_ids) {
                    $prjsSubCounty->push($project);
                }
            }

            foreach ($prjsSubCounty as $key => $proj) {
                $ward_id = explode(',', $proj->projlga);
                $ward_ids = in_array($request->ward_id, $ward_id);
                if ($ward_ids) {
                    $projects->push($project);
                }
            }

            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
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
            'from' => $request->from,
            'projectDistributionPerSubCounty' => $subCounties,
            'budgetAllocationPerFinancialYear' => $financialYears
        ]);
    }

    /**
     * get all projects for projects page
     */
    public function allProjects()
    {
        $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        foreach ($projects as $key => $project) {
            $project['id'] = Crypt::encrypt($project->projid);
            $project['link'] = "<a href='/project/$project->id')}}><i class='fa-solid fa-eye text-success'></i></a>";
            $project['link2'] = "<a href='/feedback/$project->id')}}><i class='fa-solid fa-comment'></i></i></a>";
            if ($project->projstatus !== 0) {
                $project['fyear'] = $project->financialYear->name;
                if ($project->program->section) {
                    $project['section'] = $project->program->section ?? null;
                } else {
                    $project['section'] = null;
                }
                $project->status;
                $sub_county_id = explode(',', $project->projcommunity);
                $ward_id = explode(',', $project->projlga);

                for ($i = 0; $i < count($sub_county_id); $i++) {
                    $location = Location::where('id', '=', $sub_county_id[$i])->first();
                    if ($location) {
                        $project['location'] = $location->state;
                    } else {
                        $project['location'] = 'n/a';
                    }
                }

                for ($j = 0; $j < count($ward_id); $j++) {
                    $ward = Location::where('id', '=', $ward_id[$j])->first();
                    if ($location) {
                        $project['ward'] = $ward->state;
                    } else {
                        $project['ward'] = 'n/a';
                    }
                }
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
        $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        // from
        if ($request->from != 'Select...' && $request->to == 'Select...') {
            $projects = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from]])->get();
        }

        // to
        if ($request->to != 'Select...' && $request->from == 'Select...') {
            $projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        }

        // from and to
        if ($request->to != 'Select...' && $request->from != 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        }

        // sub county
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        }

        // from sub count
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->from != 'Select...' && $request->to == 'Select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        }

        // ward

        if ($request->sub_county_id == 'Select...' && $request->ward_id != 'Select...') {
            $projects = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projlga', 'like', $request->ward_id]])->get();
        }

        // sub county ward

        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...') {
            $projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projlga', 'like', $request->ward_id], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        }

        foreach ($projects as $key => $project) {
            $project['id'] = Crypt::encrypt($project->projid);
            $project->link = "<a href='/project/$project->id')}}><i class='fa-solid fa-eye text-success'></i></a>";
            $project['link2'] = "<a href='/api/feedback/$project->id')}}><i class='fa-solid fa-comment'></i></i></a>";
            if ($project->projstatus !== 0) {
                $project->fyear = $project->financialYear->name;
                if ($project->program->section) {
                    $project->section = $project->program->section ?? null;
                } else {
                    $project->section = null;
                }
                $project->status;

                $sub_county_id = explode(',', $project->projcommunity);
                $ward_id = explode(',', $project->projlga);

                for ($i = 0; $i < count($sub_county_id); $i++) {
                    $location = Location::where('id', '=', $sub_county_id[$i])->first();
                    if ($location) {
                        $project['location'] = $location->state;
                    } else {
                        $project['location'] = 'n/a';
                    }
                }

                for ($j = 0; $j < count($ward_id); $j++) {
                    $ward = Location::where('id', '=', $ward_id[$j])->first();
                    if ($location) {
                        $project['ward'] = $ward->state;
                    } else {
                        $project['ward'] = 'n/a';
                    }
                }
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
    public function getFeedback($id)
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
     * 
     */
    public function projectDistributionPerSubCounty()
    {
        $subCounties = Location::where('parent', '=', null)->get();
        $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        foreach ($projects as $key => $project) {
            $sub_county_id = explode(',', $project->projcommunity);
            foreach ($subCounties as $key => $subCounty) {
                $isSubCounty = in_array($subCounty->id, $sub_county_id);
                if ($isSubCounty) {
                    $subCounty->num_of_projects += 1;
                }
            }
        }

        return response($subCounties);
    }

    /**
     * budget allocation per financial year
     */
    public function budgetAllocationPerFinancialYear()
    {
        $financialYears = FinancialYear::all();
        foreach ($financialYears as $key => $fy) {
            $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '=', $fy->id]])->get();
            $fy->amount = 0;
            foreach ($projects as $project) {
                $fy->amount += $project->projcost;
            }
        }
        return response($financialYears);
    }

    public function budgetAllocationPerFinancialYearQuery(Request $request)
    {
        $financialYears = FinancialYear::all();

        if ($request->from != 'Select...' && $request->to == 'Select...') {
            //$projects = Project::where([['projstatus','>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from]])->get();
            $financialYears = FinancialYear::where('id', '>=', $request->from)->get();
        }

        if ($request->to != 'Select...' && $request->from == 'Select...') {
            //$projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
            $financialYears = FinancialYear::where('id', '<=', $request->to)->get();
        }

        if ($request->to != 'Select...' && $request->from != 'Select...') {
            //$projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
            $financialYears = FinancialYear::where([['id', '>=', $request->from], ['id', '<=', $request->to]])->get();
        }

        // if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...') {
        //     //$projects = Project::where([['projcommunity', 'like', $request->sub_county_id], ['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        // }
        foreach ($financialYears as $key => $fys) {
            $fys->amount = 0;
            $projectss = [];
            $prjs = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '=', $fys->id]])->get();
            // foreach ($prjs as $project) {
            //     if ($request->sub_county_id == 'Select...') {
            //         $fys->amount += $project->projcost;
            //         array_push($projectss, $project);
            //     } else {
            //         $sub_county_id = explode(',', $project->projcommunity);
            //         $isSubCounty = in_array($request->sub_county_id, $sub_county_id);
            //         if ($isSubCounty) {
            //             $fys->amount += $project->projcost;
            //             array_push($projectss, $project);
            //         }
            //     }
            // }
            foreach ($prjs as $project) {
                $fys->amount += $project->projcost;
            }
            $fys->projects = $projectss;
            // $prjs = Project::with('program')->where([['projstatus','>', 0], ['projstatus', '!=', 3], ['projfscyear', '=', $fys->id]])->get();
            // foreach ($prjs as $project) {
            //     $fys->amount += $project->projcost;
            // }
            // if ($request->sub_county_id != 'Select...') {
            //     $fys->amount = 0;
            //     foreach ($prjs as $key => $project) {
            //         $sub_county_id = explode(',',$project->projcommunity); 
            //         $isSubCounty = in_array($request->sub_county_id, $sub_county_id);
            //         if ($isSubCounty) {
            //             $fys->amount += $project->projcost;
            //         }
            //     }
        }

        return response($financialYears);
    }

    /**
     * 
     */
    public function projectDistributionPerSubCountyQuery(Request $request)
    {
        $subCounties = Location::where('parent', '=', null)->get();
        $projects = Project::with('program')->where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
        // remeber to take projects that the id is not supposed to be used
        // from
        if ($request->from != 'Select...' && $request->to == 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            if ($projects->count() > 0) {
                foreach ($projects as $key => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    foreach ($subCounties as $key => $subCounty) {
                        $isSubCounty = in_array($subCounty->id, $sub_county_id);
                        if ($isSubCounty) {
                            $subCounty->num_of_projects += 1;
                        }
                    }
                }
            } else {
                foreach ($subCounties as $key => $subCounty) {
                    $subCounty->num_of_projects = 0;
                }
            }
        }

        // to

        if ($request->to != 'Select...' && $request->from == 'Select...') {
            $projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            if ($projects->count() > 0) {
                foreach ($projects as $key => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    foreach ($subCounties as $key => $subCounty) {
                        $isSubCounty = in_array($subCounty->id, $sub_county_id);
                        if ($isSubCounty) {
                            $subCounty->num_of_projects += 1;
                        }
                    }
                }
            } else {
                foreach ($subCounties as $key => $subCounty) {
                    $subCounty->num_of_projects = 0;
                }
            }
        }

        // from to

        if ($request->to != 'Select...' && $request->from != 'Select...') {
            $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            if ($projects->count() > 0) {
                foreach ($projects as $key => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    foreach ($subCounties as $key => $subCounty) {
                        $isSubCounty = in_array($subCounty->id, $sub_county_id);
                        if ($isSubCounty) {
                            $subCounty->num_of_projects += 1;
                        }
                    }
                }
            } else {
                foreach ($subCounties as $key => $subCounty) {
                    $subCounty->num_of_projects = 0;
                }
            }
        }

        //return response([$request->sub_county_id,$request->ward_id, $request->to, $request->from]);

        // sub county 
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to == 'Select...' && $request->from == 'Select...') {

            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
            $projects = new Collection();
            if ($prjs->count() > 0) {
                foreach ($prjs as $key => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    foreach ($subCounties as $key => $subCounty) {
                        $isSubCounty = in_array($subCounty->id, $sub_county_id);
                        if ($isSubCounty) {
                            $subCounty->num_of_projects += 1;
                        }
                    }
                }
            }
        }

        // sub county to
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to != 'Select...' && $request->from == 'Select...') {
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '<=', $request->to]])->get();
            $projects = new Collection();
            if ($prjs->count() > 0) {
                foreach ($prjs as $key => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    foreach ($subCounties as $key => $subCounty) {
                        $isSubCounty = in_array($subCounty->id, $sub_county_id);
                        if ($isSubCounty) {
                            $subCounty->num_of_projects += 1;
                        }
                    }
                }
            }
        }

        // sub county from
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to == 'Select...' && $request->from != 'Select...') {
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from]])->get();
            $projects = new Collection();
            if ($prjs->count() > 0) {
                foreach ($prjs as $key => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    foreach ($subCounties as $key => $subCounty) {
                        $isSubCounty = in_array($subCounty->id, $sub_county_id);
                        if ($isSubCounty) {
                            $subCounty->num_of_projects += 1;
                        }
                    }
                }
            }
        }

        // sub county to from
        if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...' && $request->to != 'Select...' && $request->from != 'Select...') {
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to]])->get();
            $projects = new Collection();
            if ($prjs->count() > 0) {
                foreach ($prjs as $key => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    foreach ($subCounties as $key => $subCounty) {
                        $isSubCounty = in_array($subCounty->id, $sub_county_id);
                        if ($isSubCounty) {
                            $subCounty->num_of_projects += 1;
                        }
                    }
                }
            }
        }

        // ward sub county 
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to == 'Select...' && $request->from == 'Select...') {
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            foreach ($subCounties as $key => $subCounty) {
                $mWards = [];
                $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();

                $projects = new Collection();
                foreach ($prjs as $keys => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    $isSubCounty = in_array($subCounty->id, $sub_county_id);
                    if ($isSubCounty) {
                        $projects->push($prj);
                    }
                }


                $wards = Location::where('id', '=', $request->ward_id)->get();
                foreach ($wards as $key => $ward) {

                    $num_of_projects = 0;
                    foreach ($projects as $key => $project) {
                        $project_wards = explode(',', $project->projlga);
                        $isProjectInWard = in_array($ward->id, $project_wards);
                        if ($isProjectInWard) {
                            $num_of_projects += 1;
                        }
                    }
                    $ward->num_of_projects = $num_of_projects;
                    array_push($mWards, $ward);
                }

                $subCounty->ward_details = $mWards;
            }
        }

        // ward sub county to
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to != 'Select...' && $request->from == 'Select...') {
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            foreach ($subCounties as $key => $subCounty) {
                $mWards = [];
                $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '<=', $request->to]])->get();

                $projects = new Collection();
                foreach ($prjs as $keys => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    $isSubCounty = in_array($subCounty->id, $sub_county_id);
                    if ($isSubCounty) {
                        $projects->push($prj);
                    }
                }


                $wards = Location::where('id', '=', $request->ward_id)->get();
                foreach ($wards as $key => $ward) {

                    $num_of_projects = 0;
                    foreach ($projects as $key => $project) {
                        $project_wards = explode(',', $project->projlga);
                        $isProjectInWard = in_array($ward->id, $project_wards);
                        if ($isProjectInWard) {
                            $num_of_projects += 1;
                        }
                    }
                    $ward->num_of_projects = $num_of_projects;
                    array_push($mWards, $ward);
                }

                $subCounty->ward_details = $mWards;
            }
        }

        // ward sub county from
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to == 'Select...' && $request->from != 'Select...') {
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            foreach ($subCounties as $key => $subCounty) {
                $mWards = [];
                $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from]])->get();

                $projects = new Collection();
                foreach ($prjs as $keys => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    $isSubCounty = in_array($subCounty->id, $sub_county_id);
                    if ($isSubCounty) {
                        $projects->push($prj);
                    }
                }


                $wards = Location::where('id', '=', $request->ward_id)->get();
                foreach ($wards as $key => $ward) {

                    $num_of_projects = 0;
                    foreach ($projects as $key => $project) {
                        $project_wards = explode(',', $project->projlga);
                        $isProjectInWard = in_array($ward->id, $project_wards);
                        if ($isProjectInWard) {
                            $num_of_projects += 1;
                        }
                    }
                    $ward->num_of_projects = $num_of_projects;
                    array_push($mWards, $ward);
                }

                $subCounty->ward_details = $mWards;
            }
        }

        // ward subCounty from to
        if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...' && $request->to != 'Select...' && $request->from != 'Select...') {
            $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
            foreach ($subCounties as $key => $subCounty) {
                $mWards = [];
                $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to]])->get();

                $projects = new Collection();
                foreach ($prjs as $keys => $prj) {
                    $sub_county_id = explode(',', $prj->projcommunity);
                    $isSubCounty = in_array($subCounty->id, $sub_county_id);
                    if ($isSubCounty) {
                        $projects->push($prj);
                    }
                }


                $wards = Location::where('id', '=', $request->ward_id)->get();
                foreach ($wards as $key => $ward) {

                    $num_of_projects = 0;
                    foreach ($projects as $key => $project) {
                        $project_wards = explode(',', $project->projlga);
                        $isProjectInWard = in_array($ward->id, $project_wards);
                        if ($isProjectInWard) {
                            $num_of_projects += 1;
                        }
                    }
                    $ward->num_of_projects = $num_of_projects;
                    array_push($mWards, $ward);
                }

                $subCounty->ward_details = $mWards;
            }
        }
        // 
        // if ($request->sub_county_id != 'Select...' && $request->to != 'Select...' && $request->from != 'Select...') {
        //     $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        //     $prjs = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to]])->get();
        //     $projects = new Collection();
        //     if ($prjs->count() > 0) {
        //         foreach ($prjs as $key => $prj) {
        //             $sub_county_id = explode(',', $prj->projcommunity);
        //             foreach ($subCounties as $key => $subCounty) {
        //                 $isSubCounty = in_array($subCounty->id, $sub_county_id);
        //                 if ($isSubCounty) {
        //                     $subCounty->num_of_projects += 1;
        //                 }
        //             }
        //         }
        //     }
        // }

        // remove this
        // if ($request->sub_county_id == 'Select...' && $request->to != 'Select...' && $request->from == 'Select...') {
        //     foreach ($projects as $key => $project) {
        //         $sub_county_id = explode(',', $project->projcommunity);
        //         foreach ($subCounties as $key => $subCounty) {
        //             $isSubCounty = in_array($subCounty->id, $sub_county_id);
        //             if ($isSubCounty) {
        //                 $subCounty->num_of_projects += 1;
        //             }
        //         }
        //     }
        // }

        // if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...') {
        //     $prjs = Project::where([['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
        //     $prjsSubCounty = new Collection();
        //     $projects = new Collection();
        //     foreach ($prjs as $key => $project) {
        //         $sub_county_id = explode(',',$project->projcommunity);
        //         $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
        //         if ($sub_county_ids) {
        //             $prjsSubCounty->push($project);
        //         }
        //     }

        //     foreach ($prjsSubCounty as $key => $proj) {
        //         $ward_id = explode(',',$proj->projlga);
        //         $ward_ids = in_array($request->ward_id, $ward_id);
        //         if ($ward_ids) {
        //             $projects->push($project);
        //         }
        //     }

        //     $subCounties = Location::where('id', '=', $request->sub_county_id)->get();
        // }


        return response($subCounties);
    }

    /**
     * budget allocation per department
     */
    public function budgetAllocationPerDept()
    {
        $sectors = Section::where('parent', '=', 0)->get();
        foreach ($sectors as $keys => $sector) {
            $sector->amount = 0;
            $programs = Program::with('projects')->where('projsector', '=', $sector->stid)->get();

            foreach ($programs as $key => $program) {

                foreach ($program->projects as $key => $prj) {
                    $sector->amount += $prj->projcost;
                }
            }
        }

        return response($sectors);
    }

    public function budgetAllocationPerDeptQuery(Request $request)
    {
        $sectors = Section::where('parent', '=', 0)->get();

        foreach ($sectors as $keys => $sector) {
            $sector->amount = 0;
            $programs = Program::where('projsector', '=', $sector->stid)->get();

            foreach ($programs as $key => $program) {
                $projects = Project::where([['projstatus', '>', 0], ['projstatus', '!=', 3], ['progid', '=', $program->progid]])->get();

                // from
                if ($request->from != 'Select...' && $request->to == 'Select...') {
                    $projects = Project::where([['projfscyear', '>=', $request->from], ['projstatus', '>', 0], ['projstatus', '!=', 3]])->get();
                }

                // to 
                if ($request->from == 'Select...' && $request->to != 'Select...') {
                    $projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3], ['progid', '=', $program->progid]])->get();
                }
                // to from
                if ($request->from != 'Select...' && $request->to != 'Select...') {
                    $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus', '>', 0], ['projstatus', '!=', 3], ['progid', '=', $program->progid]])->get();
                }


                foreach ($projects as $key => $prj) {
                    $sector->amount += $prj->projcost;
                }
            }
        }
        return response($sectors);
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
                    $prjSite = ProjectSites::where([['projid', '=', $project->projid], ['site_id', '=', $dis->output_site]])->first();
                    $ward = Location::where('id', '=', $dis->outputstate)->first();
                    if ($ward) {
                        $indicator = Indicator::where('indid', '=', $details->indicator)->first();
                        if ($indicator) {
                            if ($indicator->indicator_mapping_type == 1) {
                                $si_unit = MeasurementUnit::where('id', '=', $indicator->indicator_unit)->first();
                                $markers = Marker::where([['site_id', '=', $prjSite->site_id], ['projid', '=', $project->projid], ['opid', '=', $details->id]])->get();
                                $obj = new stdClass;
                                $obj->ward_id = $ward->id;
                                $obj->name = $indicator->indicator_name;
                                $obj->si_unit = $si_unit->unit;
                                $obj->ind_id = $indicator->indid;
                                $obj->target = $dis->total_target;
                                $obj->map_type = $indicator->indicator_mapping_type;
                                $obj->markers = $markers;
                                array_push($data, $obj);
                            } else {
                                $si_unit = MeasurementUnit::where('id', '=', $indicator->indicator_unit)->first();
                                $markers = Marker::where([['opid', '=', $details->id], ['projid', '=', $project->projid]])->get();
                                $obj = new stdClass;
                                $obj->ward_id = $ward->id;
                                $obj->name = $indicator->indicator_name;
                                $obj->si_unit = $si_unit->unit;
                                $obj->ind_id = $indicator->indid;
                                $obj->target = $dis->total_target;
                                $obj->map_type = $indicator->indicator_mapping_type;
                                $obj->markers = $markers;
                                array_push($data, $obj);
                            }
                        }
                    }
                }

                if ($dis->outputstate == 0) {
                    $prjSite = ProjectSites::where([['projid', '=', $project->projid], ['site_id', '=', $dis->output_site]])->first();
                    if ($prjSite) {
                        $ward = Location::where('id', '=', $prjSite->state_id)->first();
                        if ($ward) {
                            $indicator = Indicator::where('indid', '=', $details->indicator)->first();
                            if ($indicator->indicator_mapping_type == 1) {
                                $markers = Marker::where([['site_id', '=', $prjSite->site_id], ['projid', '=', $project->projid], ['opid', '=', $details->id]])->get();
                                $si_unit = MeasurementUnit::where('id', '=', $indicator->indicator_unit)->first();
                                $obj = new stdClass;
                                $obj->ward_id = $ward->id;
                                $obj->name = $indicator->indicator_name;
                                $obj->si_unit = $si_unit->unit;
                                $obj->ind_id = $indicator->indid;
                                $obj->target = $dis->total_target;
                                $obj->map_type = $indicator->indicator_mapping_type;
                                $obj->markers = $markers;
                                array_push($data, $obj);
                            } else {
                                $markers = Marker::where([['opid', '=', $details->id], ['projid', '=', $project->projid]])->get();
                                $si_unit = MeasurementUnit::where('id', '=', $indicator->indicator_unit)->first();
                                $obj = new stdClass;
                                $obj->ward_id = $ward->id;
                                $obj->name = $indicator->indicator_name;
                                $obj->si_unit = $si_unit->unit;
                                $obj->ind_id = $indicator->indid;
                                $obj->target = $dis->total_target;
                                $obj->map_type = $indicator->indicator_mapping_type;
                                $obj->markers = $markers;
                                array_push($data, $obj);
                            }
                        }
                    }
                }
            }
        }


        $alldata = [];

        for ($c = 0; $c < count($data); $c++) {
            for ($d = 0; $d < count($data); $d++) {
                if (($data[$c]->ward_id == $data[$d]->ward_id) && ($data[$c]->ind_id == $data[$d]->ind_id) && ($c != $d)) {
                    $data[$c]->target = $data[$c]->target + $data[$d]->target;
                    for ($i = 0; $i < count($data[$d]->markers); $i++) {
                        $data[$c]->markers->push($data[$d]->markers[$i]);
                    }
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


        // dd($wards);

        for ($t = 0; $t < count($wards); $t++) {
            $ward = Location::where('id', '=', $wards[$t])->first();
            $obj3 = new stdClass;
            $obj3->ward = $ward->state;
            $obj3->data = [];

            for ($d = 0; $d < count($filteredData); $d++) {
                if ($ward->id == $filteredData[$d]->ward_id) {
                    array_push($obj3->data, $filteredData[$d]);
                }
            }

            array_push($alldata, $obj3);
        }


        return response($alldata);
    }

    function datediffInWeeks($date1, $date2)
    {
        if ($date1 > $date2) return $this->datediffInWeeks($date2, $date1);
        $first = DateTime::createFromFormat('m/d/Y', $date1);
        $second = DateTime::createFromFormat('m/d/Y', $date2);
        return floor($first->diff($second)->days / 7);
    }


    public function yearDistribution()
    {
        $task_start_date = '2023-07-01';
        $task_end_date = '2025-07-30';
        $start_year = 2022;
        $st = 2022;
        $duration = 4;
        $f_start = '07-01';
        $f_end = '06-30';
        $startYears = [];
        $years = [];

        for ($i = 0; $i < $duration; $i++) {
            $f_year_start = $st . '-' . $f_start;
            $m_start = $start_year . '-' . $f_start;
            $f_year_end = $start_year + 1 . '-' . $f_end;
            $startYears[] =  [$m_start, $f_year_end];
            // dd($task_start_date, $m_start, $task_end_date, $f_year_end);

            $start_year++;
        }

        $st2 = 2022;
        $start_year2 = 2022;

        for ($i = 0; $i < count($startYears); $i++) {
            $startFinancial = $startYears[$i][0];
            $endFinancial = $startYears[$i][1];


            if (
                ($task_start_date >= $startFinancial && $task_start_date <= $endFinancial) ||
                ($task_end_date >= $startFinancial && $task_end_date <= $endFinancial) ||
                ($task_start_date <= $startFinancial && $task_end_date >= $startFinancial && $task_end_date >= $endFinancial)
            ) {
                $years[] = $startFinancial . '/' . $endFinancial;
            }
        }
        // if (count($years) <  1) {
        //     for ($i = 0; $i < $duration; $i++) {
        //         $f_year_start = $st2 . '-' . $f_start;
        //         $m_start = $start_year2 . '-' . $f_start;
        //         $f_year_end = $start_year2 + 1 . '-' . $f_end;
        //         //dd($task_start_date, $f_year_start, $task_end_date, $f_year_end);
        //         if (
        //             $task_start_date >= $f_year_start && $task_end_date >= $f_year_end
        //         ) {
        //             $years[] = $m_start . '/' . $f_year_end;
        //         }

        //         if ($task_start_date >= $f_year_start && $task_end_date <= $f_year_end) {
        //             $years[] = $m_start . '/' . $f_year_end;
        //         }

        //         $start_year2++;
        //     }
        // }
        // for ($i=0; $i < count($startYears); $i++) { 
        //     $f_year_start = $st . '-' . $f_start;
        //     $m_start = $start_year . '-' . $f_start;
        //     $f_year_end = $start_year + 1 . '-' . $f_end;
        //     if ($task_start_date >= $f_year_start && $task_end_date <= $f_year_end) {
        //         $years[] = $m_start . '/' . $f_year_end;
        //     }
        //     $start_year++;
        // }

        $annually = [];

        $quarterly = [];

        $monthly = [];

        $weekly = [];


        // semi annually
        for ($i = 0; $i < count($startYears); $i++) {
            $startFinancial = $startYears[$i][0];
            $endFinancial = $startYears[$i][1];
            $startFinancialMidPoint = strtotime('+6 months -1 day', strtotime($startFinancial));
            $date = date('Y-m-d', $startFinancialMidPoint);
            $endFinancialMidPoint = strtotime('-6 months +2 day', strtotime($endFinancial));
            $datetwo = date('Y-m-d', $endFinancialMidPoint);
            $annually[] = [[$startFinancial, $date], [$datetwo, $endFinancial]];
        }


        // quarterly
        for ($i = 0; $i < count($annually); $i++) {
            for ($t = 0; $t < count($annually[$i]); $t++) {
                $startFinancial = $annually[$i][$t][0];
                $endFinancial = $annually[$i][$t][1];

                $startFinancialMidPoint = strtotime('+3 months -1 day', strtotime($startFinancial));
                $date = date('Y-m-d', $startFinancialMidPoint);
                $endFinancialMidPoint = strtotime('-3 months ', strtotime($endFinancial));
                $datetwo = date('Y-m-d', $endFinancialMidPoint);
                $quarterly[] = [[$startFinancial, $date], [$datetwo, $endFinancial]];
            }
        }

        // monthly
        for ($i = 0; $i < count($quarterly); $i++) {
            for ($t = 0; $t < count($quarterly[$i]); $t++) {
                $startFinancial = $quarterly[$i][$t][0];
                $endFinancial = $quarterly[$i][1][1];


                while ($startFinancial <= $endFinancial) {
                    $startFinancialMidPoint = strtotime('+1 month -1 day', strtotime($startFinancial));
                    // dd($startFinancialMidPoint);
                    $date = date('Y-m-d', $startFinancialMidPoint);
                    // $endFinancialMidPoint = strtotime('-1 month -30 days', strtotime($endFinancial));
                    // $datetwo = date('Y-m-d', $endFinancialMidPoint);
                    $monthly[] = [$startFinancial, $date];
                    $startFinancial = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                }

                break;
            }
        }


        // weekly


        $quaters = 0;
        $miaka = [];
        $miakaQuarter = [];
        $miakaMonthly = [];
        $miakaDaily = [];

        // semi annually
        for ($i = 0; $i < count($annually); $i++) {
            for ($t = 0; $t < count($annually[$i]); $t++) {
                $annual_start = $annually[$i][$t][0];
                $annual_end = $annually[$i][$t][1];
                if (
                    ($task_start_date >= $annual_start && $task_start_date <= $annual_end) ||
                    ($task_end_date >= $annual_start && $task_end_date <= $annual_end) ||
                    ($task_start_date <= $annual_start && $task_end_date >= $annual_start && $task_end_date >= $annual_end)
                ) {
                    $quaters++;
                    $miaka[] = $annual_start . '/' . $annual_end;
                }
            }
        }

        // quarterly
        for ($i = 0; $i < count($quarterly); $i++) {
            for ($t = 0; $t < count($quarterly[$i]); $t++) {
                $annual_start = $quarterly[$i][$t][0];
                $annual_end = $quarterly[$i][$t][1];
                if (
                    ($task_start_date >= $annual_start && $task_start_date <= $annual_end) ||
                    ($task_end_date >= $annual_start && $task_end_date <= $annual_end) ||
                    ($task_start_date <= $annual_start && $task_end_date >= $annual_start && $task_end_date >= $annual_end)
                ) {
                    $quaters++;
                    $miakaQuarter[] = $annual_start . '/' . $annual_end;
                }
            }
        }

        // monthly
        for ($i = 0; $i < count($monthly); $i++) {
            $annual_start = $monthly[$i][0];
            $annual_end = $monthly[$i][1];
            if (
                ($task_start_date >= $annual_start && $task_start_date <= $annual_end) ||
                ($task_end_date >= $annual_start && $task_end_date <= $annual_end) ||
                ($task_start_date <= $annual_start && $task_end_date >= $annual_start && $task_end_date >= $annual_end)
            ) {
                $miakaMonthly[] = $annual_start . '/' . $annual_end;
            }
        }


        // weekly
        for ($i = 0; $i < count($miakaMonthly); $i++) {
            $month = $miakaMonthly[$i];
            $month_array = explode('/', $month);
            $num_of_weeks = $this->getWeeksBetweenDates($month_array[0], $month_array[1]);
            $weekly[] = [$month => $num_of_weeks];
        }

        // daily
        // $task_start_date = '2023-07-01';
        // $task_end_date = '2025-05-01';
        $daily_task_start_date = $task_start_date;
        while ($daily_task_start_date <= $task_end_date) {
            $date = date('Y-m-d', strtotime($daily_task_start_date));
            $miakaDaily[] = $date;
            $daily_task_start_date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }

        // dd($annually, $quaters, $miaka);
        // dd($startYears, $annually);
        dd($years, $miaka, $miakaQuarter, $miakaMonthly,  $miakaDaily);
    }

    public function getWeeksBetweenDates($start_date, $end_date)
    {
        // Create DateTime objects for the start and end dates
        $start_datetime = new DateTime($start_date);
        $end_datetime = new DateTime($end_date);

        // Calculate the difference in days between the two dates
        $interval = $start_datetime->diff($end_datetime);
        $days_difference = $interval->days;

        // Calculate the number of weeks
        $weeks = floor($days_difference / 7);

        return $weeks;
    }

    // public function datediff($interval, $datefrom, $dateto, $using_timestamps = false)
    // {
    //     /*
    // $interval can be:
    // yyyy - Number of full years
    // q    - Number of full quarters
    // m    - Number of full months
    // y    - Difference between day numbers
    //        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    // d    - Number of full days
    // w    - Number of full weekdays
    // ww   - Number of full weeks
    // h    - Number of full hours
    // n    - Number of full minutes
    // s    - Number of full seconds (default)
    // */

    //     if (!$using_timestamps) {
    //         $datefrom = strtotime($datefrom, 0);
    //         $dateto   = strtotime($dateto, 0);
    //     }

    //     $difference        = $dateto - $datefrom; // Difference in seconds
    //     $months_difference = 0;

    //     switch ($interval) {
    //         case 'yyyy': // Number of full years
    //             $years_difference = floor($difference / 31536000);
    //             if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom) + $years_difference) > $dateto) {
    //                 $years_difference--;
    //             }

    //             if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto) - ($years_difference + 1)) > $datefrom) {
    //                 $years_difference++;
    //             }

    //             $datediff = $years_difference;
    //             break;

    //         case "q": // Number of full quarters
    //             $quarters_difference = floor($difference / 8035200);

    //             while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($quarters_difference * 3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
    //                 $months_difference++;
    //             }

    //             $quarters_difference--;
    //             $datediff = $quarters_difference;
    //             break;

    //         case "m": // Number of full months
    //             $months_difference = floor($difference / 2678400);

    //             while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom) + ($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
    //                 $months_difference++;
    //             }

    //             $months_difference--;

    //             $datediff = $months_difference;
    //             break;

    //         case 'y': // Difference between day numbers
    //             $datediff = date("z", $dateto) - date("z", $datefrom);
    //             break;

    //         case "d": // Number of full days
    //             $datediff = floor($difference / 86400);
    //             break;

    //         case "w": // Number of full weekdays
    //             $days_difference  = floor($difference / 86400);
    //             $weeks_difference = floor($days_difference / 7); // Complete weeks
    //             $first_day        = date("w", $datefrom);
    //             $days_remainder   = floor($days_difference % 7);
    //             $odd_days         = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?

    //             if ($odd_days > 7) { // Sunday
    //                 $days_remainder--;
    //             }

    //             if ($odd_days > 6) { // Saturday
    //                 $days_remainder--;
    //             }

    //             $datediff = ($weeks_difference * 5) + $days_remainder;
    //             break;

    //         case "ww": // Number of full weeks
    //             $datediff = floor($difference / 604800);
    //             break;

    //         case "h": // Number of full hours
    //             $datediff = floor($difference / 3600);
    //             break;

    //         case "n": // Number of full minutes
    //             $datediff = floor($difference / 60);
    //             break;

    //         default: // Number of full seconds (default)
    //             $datediff = $difference;
    //             break;
    //     }

    //     return $datediff;
    // }

    // public function yearDistribution()
    // {
    //     $task_start_date = '2022-04-01';
    //     $task_end_date = '2024-05-01';
    //     $start_year = 2021;
    //     $duration = 4;

    //     $start_date  = false;

    //     for ($i = 0; $i < $duration; $i++) {
    //         $end_year = $start_year + 1;
    //         $project_start_date = $start_year . '-07-01';
    //         $project_end_date = $end_year . '-06-30';
    //         if ($task_start_date >= $project_start_date && $task_start_date <= $project_end_date) {
    //             $start_date = true;
    //         }

    //         if ($start_date) {
    //             if ($task_end_date >= $project_start_date &&  $task_end_date <= $project_end_date) {
    //                 echo  "Start date " . $project_start_date . " is not before " . $project_end_date;
    //                 return;
    //             } else {
    //                 echo  "Start date " . $project_start_date . " is not before " . $project_end_date;
    //             }
    //         }
    //         $start_year++;
    //     }
    // }

    /**
     * yearly
     * 2022 - 2027
     * 2022/2023 - 2027/2028
     * 01-07-2022 - 30-06-2023
     */
    // public function yearDistribution()
    // {
    //     $startYear = Carbon::createFromFormat('Y-m-d', '2022-07-01');
    //     $startYears = Carbon::createFromFormat('Y-m-d', '2022-07-01');
    //     $endYear = $startYears->addYears(6)->subDay();
    //     $projectYears = 6;
    //     $taskStartDate = Carbon::createFromFormat('Y-m-d', '2023-04-01');
    //     $taskEndDate = Carbon::createFromFormat('Y-m-d', '2024-05-01');
    //     $projectYears = [];
    //     for ($year = $startYear->year; $year <= $endYear->year; $year++) {
    //         $projectYears[] = $year;
    //     }
    //     $taksYears = [];
    //     for ($year = $taskStartDate->year; $year <= $taskEndDate->year; $year++) {
    //         if () {
    //             # code...
    //         }
    //         $taksYears[] = $year;
    //     }
    //     array_pop($taksYears);
    //     dd($taksYears);


    //     for ($i = 0; $i < $projectYears; $i++) {
    //         $projStartDate = Carbon::createFromFormat('Y-m-d', $startYear . '-07-01');
    //         $projEndDate = Carbon::createFromFormat('Y-m-d', $startYear->addYear() . '-06-30');
    //     }
    // }

    // public function budgetAllocationPerDeptQueryTwo(Request $request) 
    // {
    //     return response($request->from);
    //     // $sectors = Section::where('parent', '=', 0)->get();

    //     // foreach ($sectors as $keys => $sector) {
    //     //     $sector->amount = 0;
    //     //     $programs = Program::where('projsector','=', $sector->stid)->get();
    //     //     return response($request->from);

    //     //     foreach ($programs as $key => $program) {
    //     //         $projects = Project::where([['projstatus','>', 0], ['projstatus', '!=', 3], ['progid','=', $program->progid]])->get();

    //     //         if ($request->from != 'Select...' && $request->to == 'Select...') {
    //     //             $projects = Project::where([['projstatus','>', 0], ['projstatus', '!=', 3]])->get();
    //     //             //['projfscyear', '>=', $request->from],
    //     //         }

    //     //         if ($request->from == 'Select...' && $request->to != 'Select...') {
    //     //             $projects = Project::where([['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3], ['progid','=', $program->progid]])->get();
    //     //         }

    //     //         if ($request->from == 'Select...' && $request->to == 'Select...') {
    //     //             $projects = Project::where([['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['projstatus','>', 0], ['projstatus', '!=', 3], ['progid','=', $program->progid]])->get();
    //     //         }

    //     //         if ($request->sub_county_id != 'Select...' && $request->ward_id == 'Select...') {
    //     //             $prjs = Project::where([['projstatus','>', 0], ['projstatus', '!=', 3], ['progid','=', $program->progid], ['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to]])->get();
    //     //             $projects = new Collection();
    //     //             foreach ($prjs as $key => $project) {
    //     //                 $sub_county_id = explode(',',$project->projcommunity);
    //     //                 $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
    //     //                 if ($sub_county_ids) {
    //     //                     $projects->push($project);
    //     //                 }
    //     //             }
    //     //         }

    //     //         if ($request->sub_county_id != 'Select...' && $request->ward_id != 'Select...') {
    //     //             $prjs = Project::where([['projstatus','>', 0], ['projstatus', '!=', 3], ['projfscyear', '>=', $request->from], ['projfscyear', '<=', $request->to], ['progid','=', $program->progid]])->get();
    //     //             $projects = new Collection();
    //     //             foreach ($prjs as $key => $project) {
    //     //                 $sub_county_id = explode(',',$project->projcommunity);
    //     //                 $sub_county_ids = in_array($request->sub_county_id, $sub_county_id);
    //     //                 if ($sub_county_ids) {
    //     //                     $projects->push($project);
    //     //                 }
    //     //             }
    //     //         }


    //     //         foreach ($projects as $key => $prj) {
    //     //             $sector->amount += $prj->projcost;
    //     //         }
    //     //     }
    //     // }
    //     // return response($sectors);
    // }

    // public function outputTarget($id) 
    // {
    //     $project = Project::find($id);
    //     $projectDetails = ProjectDetails::where('projid','=', $project->projid)->get();
    //     $wards = explode(',', $project->projlga);
    //     $data = [];
    //     $target = 0;
    //     foreach ($projectDetails as $key => $details) {
    //         $disaggregation = OutputDisaggregation::where([['projid','=',$project->projid], ['outputid','=', $details->id]])->get();
    //         foreach ($disaggregation as $key => $dis) {
    //             if ($dis->outputstate != 0) {
    //                 $indicator = Indicator::where('indid','=', $details->indicator)->first(); 
    //                 $ward = Location::where('id','=', $dis->outputstate)->first();
    //                 if ($ward) {
    //                     $obj = new stdClass;
    //                     $obj->ward_id = $ward->id;
    //                     $obj->name = $indicator->indicator_name;
    //                     $obj->ind_id = $indicator->indid;
    //                     $obj->target = $dis->total_target;
    //                     array_push($data, $obj);
    //                 }
    //             } 

    //             if ($dis->outputstate == 0) {
    //                 $prjSite = ProjectSites::where([['projid','=',$project->projid], ['site_id','=', $dis->output_site]])->first();
    //                 if ($prjSite) {
    //                     $ward = Location::where('id','=', $prjSite->state_id)->first();
    //                     if ($ward) {
    //                         $indicator = Indicator::where('indid','=', $details->indicator)->first(); 
    //                         $obj = new stdClass;
    //                         $obj->ward_id = $ward->id;
    //                         $obj->name = $indicator->indicator_name;
    //                         $obj->ind_id = $indicator->indid;

    //                         $obj->target = $dis->total_target;
    //                         array_push($data, $obj);
    //                     }
    //                 }
    //             }
    //         }
    //     }


    //     $alldata = [];


    //     for ($t=0; $t < count($wards); $t++) { 
    //         $ward = Location::where('id','=', $wards[$t])->first();
    //         $obj3 = new stdClass;
    //         $obj3->ward = $ward->state;
    //         $obj3->data = [];
    //         for ($d=0; $d < count($data); $d++) { 
    //             if ($ward->id == $data[$d]->ward_id) {
    //                 array_push($obj3->data, $data[$d]);
    //             }
    //         }

    //         array_push($alldata, $obj3);
    //     }


    //     return response($alldata);
    // }
}
