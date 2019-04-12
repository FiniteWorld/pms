<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    protected $dates = ['end_date','updated_at','created_at'];
    protected $fillable = ['proj_id','task_name','end_date','priority','status','user_id'];
}
