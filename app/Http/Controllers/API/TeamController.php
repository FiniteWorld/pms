<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use DB;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $user = auth('api')-> user();
        $userlogged =  $user->id;

        if(\Gate::allows('isAdmin')|| \Gate::allows('isManager'))
        {
            
            
            
           $name1 = DB::table('team')
                ->leftJoin('users','users.id','=', 'team.member_id')
                ->rightJoin('projects','projects.id','=','team.project_title')
                ->select('team.id','team.role','team.team_name','users.name','projects.project_title')
                ->where('team.user_id','=',$userlogged)
                ->paginate();
        
            return $name1;


        }
        
    }
    public function loadMembers()

    {
        $members = DB::table('users')
                    ->leftJoin('team','team.user_id','users.id')
                    ->select('users.name','users.id') 
                    ->where('users.type','Member')
                    ->get();
        return $members;

    }
    
    public function loadTitles()
    {
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        $members = DB::table('projects')->select('project_title','id')->where('user_id','=',$userlogged)->get();
        return $members;

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
            'role'=>'required|string',
            'member_id'=>'required|integer',
            'team_name'=>'required|string',
            'project_title'=>'required|integer'
         
        ]);
    
        return Team::create([
            'user_id' => $userlogged,
            'role'=> $request['role'],
            'member_id'=> $request['member_id'],
            'team_name'=> $request['team_name'],
            'project_title'=>$request['project_title']
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this-> authorize('isManager');
        $team= Team::findOrFail($id);

        //delete
        $team -> delete();
        return ['message'=> 'Project Details Deleted'];
    }
}
