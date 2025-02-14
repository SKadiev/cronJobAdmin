<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Rule;
use App\Models\Domain;
use App\Models\JobType;

use DB;
class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $jobs = DB::table('jobs')
        ->leftJoin('rules', 'jobs.rules_id', '=', 'rules.id')
        ->leftJoin('job_types', 'jobs.job_type_id', '=', 'job_types.id')
        ->select(
            'jobs.id',
            'rules.name',
            'jobs.videos_to_crawl',
            'rules.from',
            'rules.to',
            'job_types.type'
            )    
        ->get();
        
        return view("job.index",["jobs" => $jobs]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rules = Rule::pluck('id', 'name');
      
        $jobTypes = JobType::pluck('id', 'type');

        return view('job.create', (compact('rules', 'jobTypes')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Job::create(
          
            $request->validate([
                'videos_to_crawl' => 'required',
                'rules_id' => 'required',
                'job_type_id' => 'required'

            ])
        );

        return redirect()->action([JobsController::class, 'index']);
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
    public function edit(Job $job)
    {
        // $domains = Domain::pluck('name', 'id');
        $rules = Rule::pluck('name', 'id');
      
        $jobTypes = JobType::pluck('type', 'id');
        
        // dd($rules, $jobTypes);


        return view('job.edit', (compact( 'rules', 'jobTypes', 'job')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Job $job)
    {
        $job->update([
            'job_type_id' => request('job_type_id'),
            'rules_id' => request('rules_id'),
            "videos_to_crawl" => request('videos_to_crawl')

        ]);

        return redirect()->action([JobsController::class, 'index']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy (Job $job)
    {
        $job->delete();
        return redirect()->action([JobsController::class, 'index']);

    }
}
