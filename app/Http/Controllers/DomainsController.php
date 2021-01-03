<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Page;
use Illuminate\Http\Request;

class DomainsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view("domain.index",["domains" => Domain::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('domain.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       
        Domain::create(
          
            $request->validate([
                'name' => 'required|unique:domains|max:255',
                'score' => 'required',
            ])
        );

        return redirect()->action([DomainsController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function show(Domain $domain)
    {
        dd(Domain::all());

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function edit(Domain $domain)
    {

        return view('domain.edit', ["domain"=> $domain]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Domain $domain)
    {
        $domain->update([

            "name" => request("name"),
            "score" =>request("score")

        ]);

        return redirect()->action([DomainsController::class, 'index']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Domain  $domain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Domain $domain)
    {  
        $this->authorize('delete', $domain);
        $domain->removeCascade();
        return redirect()->action([DomainsController::class, 'index']);

    }
    
    
}
