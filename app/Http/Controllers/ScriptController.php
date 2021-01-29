<?php

namespace App\Http\Controllers;

use App\User;
use App\Rule;
use Response;
use Illuminate\Http\Request;

class ScriptController extends Controller
{
    
	
	/**
     * check Rules 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function checkRules(Request $request)
    {
        $path = $request->input('path');
        $pathName = ltrim($path, '/');
        $responses = [];
        $userRules = User::where('unique_token', '=', $request->input('token'))->with('rules')->first();
        foreach ($userRules->rules  as $key => $rule) {
            if($rule->rule_value === 'contains') {
                if(strpos($pathName,$rule->domain_value) !== false) {
                    array_push($responses, $rule->show_hide_value);
                }
            } else if($rule->rule_value === 'startsWith') { 
                if(substr($rule->domain_value, 0, strlen($pathName)) === $rule->domain_value) {
                    array_push($responses, $rule->show_hide_value);
                }
            } else if($rule->rule_value === 'endsWith') { 
                $len = strlen($pathName); 
                if ($len == 0) { 
                    return true; 
                } 
                if(substr($rule->domain_value, -$len) ===  $rule->domain_value) {
                    array_push($responses, $rule->show_hide_value);
                }
            } else if($rule->rule_value === 'exact') { 
                if($rule->domain_value === $pathName) {
                    array_push($responses, $rule->show_hide_value);
                }
            }
        }
        if(count($responses) === 1) {
            return Response::json(['message' => $userRules->alert_message], 200);
        } else {
            if(in_array('show',$responses) && in_array('hide',$responses) === false) {
                return Response::json(['message' => $userRules->alert_message], 200);
            }
        }
    }
}
