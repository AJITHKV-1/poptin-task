<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Rule;
use Illuminate\Http\Request;
use Auth;

class RuleController extends Controller
{


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all rules for the logged in user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       return  Rule::with('user')->where('user_id', Auth::user()->id)->get();
    }


    /**
     * Create Rules for the logged in user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    { 
        $ruleExist = Rule::where('user_id', '=', Auth::user()->id)->first();
        if($ruleExist) {
            Rule::where('user_id', '=', Auth::user()->id)->delete();
        }
        foreach ($request->input()[0]['rows'] as $key => $rule) {
            Rule::create([
                'user_id' => Auth::user()->id,
                'show_hide_value' => $rule['show_hide_value'],
                'rule_value' => $rule['rule_value'],
                'domain_value' => $rule['domain_value'],
            ]);
        }
        $user = User::find(Auth::user()->id);
        $user->alert_message = $request->input()[0]['message'];
        $user->checked = $request->input()[0]['checked'];
        $user->checked_message = $request->input()[0]['checked_message'];
        $user->save();
    }

   
}
