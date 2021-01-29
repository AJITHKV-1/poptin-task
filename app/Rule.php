<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'user_id', 'show_hide_value', 'rule_value', 'domain_value'
   ];


    /**
     * Get the user for this rule.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
