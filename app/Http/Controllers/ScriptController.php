<?php

namespace App\Http\Controllers;

use App\User;
use App\Rule;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    
	
	/**
     * Create Rules for the logged in user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function checkRules(Request $request)
    {
        return  $request->input();
    }
}
