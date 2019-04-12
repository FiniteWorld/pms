<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\projects;
use Auth;
use DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        if(\Gate::allows('isManager'))
        {
            $projects = DB::table('projects')
                        ->Join('clients','clients.id','projects.client_id')
                        ->select('projects.id','projects.project_title','projects.project_description','projects.team','clients.client_name')
                        ->where('projects.user_id',$userlogged)
                        ->get();
    
        }
        if(\Gate::allows('isAdmin'))
        {
            $projects = DB::table('projects')
                        ->Join('clients','clients.id','projects.client_id')
                        ->Join('users','users.id','projects.user_id')
                        ->select('projects.project_title','users.name','projects.team','clients.client_name')
                        ->get();
       }
       return $projects;

       
    }
    
    public function loadClients()
    {
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        $client = DB::table('clients')
                ->where('user_id',$userlogged)
                ->select('client_name','id')
                ->get();
        return $client;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        $this->validate($request,[
            'client_id'=>'required|integer',
            'project_title'=>'required|string|max:191',
            'project_description'=>'required|string|max:191',
            'team'=>'required|string'
            
        ]);
        return projects::create([
            'user_id' => $userlogged,
            'client_id'=> $request['client_id'],
            'project_title' => $request['project_title'],
            'project_description' => $request['project_description'],
            'team' => $request['team']
          
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $pro = projects::findOrFail($id);
        $this->validate($request,[
            'project_title'=>'required|string|max:191',
            'project_description'=>'required|string|max:191',
            'team'=>'required|string'
        ]);
         
        $pro->update($request->all());
        
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this-> authorize('isManager' || 'isAdmin');
        $project= projects::findOrFail($id);

        //delete
        $project -> delete();
        return ['message'=> 'Project Details Deleted'];
    }
}
;