<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = DB::table('pages')
            ->leftJoin('domains', 'pages.domain_id', '=', 'domains.id')
            ->select('pages.id', 'domains.name', 'pages.body')
            ->get();
            
        return view("page.index",["pages" => $pages]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $domains = Domain::pluck('name', 'id');
      
        return view('page.create', (compact('domains')));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Page::create(
          
            $request->validate([
                'body' => 'required|unique:pages|max:1500',
                'domain_id' => 'required',

            ])
        );

        return redirect()->action([PagesController::class, 'index']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        
        $domains = Domain::pluck('name', 'id');

        return view('page.edit', (compact('page', 'domains')));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        
          
        $page->update([
            "body" => request("body"),
            "domain_id" => request("domain_id")

        ]);

        return redirect()->action([PagesController::class, 'index']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->action([PagesController::class, 'index']);

    }

   

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $output = "";
            $domains = DB::table('domains')->where('name','LIKE','%'.$request->search."%")->get();
            dd($domains);
            // if ($domains) {

            //     foreach ($domains as $key => $domain) {
            //         $output.='<tr>'.
            //         '<td>'.$product->id.'</td>'.
            //         '<td>'.$domain->title.'</td>'.
            //         '<td>'.$product->description.'</td>'.
            //         '<td>'.$product->price.'</td>'.
            //         '</tr>';
            //         }
            //     return Response($output);

            // }
        }
    }

}
