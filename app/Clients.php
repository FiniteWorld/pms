<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{
    protected $fillable= ['user_id','client_name','client_email','client_address','client_contact','client_company'];
}
