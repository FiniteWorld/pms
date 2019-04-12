<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
     protected $table = 'team';
    protected $fillable = ['id','user_id','role','member_id','team_name','project_title'];
   
}
