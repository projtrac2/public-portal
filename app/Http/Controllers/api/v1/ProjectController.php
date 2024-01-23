<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
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
        $projects = Project::all();
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
