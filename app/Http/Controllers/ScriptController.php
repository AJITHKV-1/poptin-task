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
        $checkedMessage = '';
        $userRules = User::where('unique_token', '=', $request->input('token'))->with('rules')->first();
        foreach ($userRules->rules  as $key => $rule) {
            if($rule->rule_value === 'contains') {
                if(strpos($pathName,$rule->domain_value) !== false) {
                    array_push($responses, $rule->show_hide_value);
                }
            } else if($rule->rule_value === 'startsWith') { 
                if(substr($pathName, 0, strlen($rule->domain_value)) === $rule->domain_value) {
                    array_push($responses, $rule->show_hide_value);
                }
            } else if($rule->rule_value === 'endsWith') { 
                $len = strlen($rule->domain_value); 
                if ($len == 0) { 
                    return true; 
                } 
                if(substr($pathName, -$len) ===  $rule->domain_value) {
                    array_push($responses, $rule->show_hide_value);
                }
            } else if($rule->rule_value === 'exact') { 
                if($rule->domain_value === $pathName) {
                    array_push($responses, $rule->show_hide_value);
                }
            }
        }
        if($userRules->checked) {
            $checkedMessage = $userRules->checked_message;
        }
        if(count($responses) === 1) {
			if(in_array('hide',$responses) === false) {
				return Response::json(['message' => $userRules->alert_message,'checked' => $userRules->checked,'checked_message' => $checkedMessage], 200);
			} else {
				return Response::json(['checked' => $userRules->checked, 'checked_message' => $checkedMessage], 200);
			}
        } else {
            if(in_array('show',$responses) && in_array('hide',$responses) === false) {
                return Response::json(['message' => $userRules->alert_message,'checked' => $userRules->checked, 'checked_message' => $checkedMessage], 200);
            } else {
				return Response::json(['checked' => $userRules->checked, 'checked_message' => $checkedMessage], 200);
			}
        }
    }
}
