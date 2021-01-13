<?php

namespace App\Http\Controllers;
use App\Models\Rule;

use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function index()
    {
      
        return view("rule.index",["rules" => Rule::all()]);

    }

   
    public function create()
    {
        
        return view('rule.create');

    }

   
    public function store(Request $request)
    {

        Rule::create(
          
            $request->validate([
                'name' => 'required',
                'from' => 'required',
                'to' => 'required'
            ])
        );

        return redirect()->action([RulesController::class, 'index']);
    }

   
    public function show(Rule $rule)
    {
        
    }

   
    public function edit(Rule $rule)
    {
        

        return view('rule.edit', (compact('rule')));

    }

   
    public function update(Request $request, Rule $rule)
    {
          
        $rule->update([
            "name" => request("name"),
            "from" => request("from"),
            "to" => request("to")

        ]);

        return redirect()->action([RulesController::class, 'index']);

    }

   
    public function destroy(Rule $rule)
    {
        $rule->delete();
        return redirect()->action([RulesController::class, 'index']);

    }

}
