<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\projects;
use DB;
use Carbon\Carbon;

class TaskController extends Controller
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
        $task_p = DB::table('tasks')
                        ->leftJoin('table_task_percent','tasks.status','table_task_percent.status')
                        ->select('tasks.id','tasks.status','tasks.task_name','tasks.end_date','tasks.priority',
                        'table_task_percent.percentage')
                        ->where('tasks.user_id',$userlogged)
                        ->get();
        return $task_p;
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

        $proj_id = DB::table('projects')
                    ->Join('team','team.project_title','=','projects.id')
                    ->select('projects.id')
                    ->where('team.member_id',$userlogged)
                    ->get();
         $end_date= date($request->end_date);
         
         
        
       return Task::create([
            'proj_id' => $proj_id[0]->id,
            'task_name'=> $request['task_name'],
            'end_date'=>$end_date,
            'priority'=>$request['priority'],
            'status'=>$request['status'],
            'user_id'=>$userlogged
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function loadStatus()
    {
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        $task_total = DB::table('tasks')
                        ->leftJoin('table_task_percent','tasks.status','table_task_percent.status')
                        ->select('table_task_percent.percentage')
                        ->where('tasks.user_id',$userlogged)
                        ->get();
        
        $task_count = DB::table('tasks')
                    ->where('user_id',$userlogged)
                    ->count();
        
        
        $count = 0; 
        foreach($task_total as $t){
            $count+=$t->percentage/$task_count;
           
        }
        return [$count];
    }
    public function loadProjectStatus()
    {
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        $task_count = DB::table('tasks')
                    ->Join('projects','projects.id','tasks.proj_id')
                    ->select('tasks.proj_id',DB::raw('count(tasks.id) as task_count'))
                    ->where('projects.user_id',$userlogged)
                    ->groupBy('tasks.proj_id')
                    ->get();
        

        $task= DB::table('tasks')
                ->Join('table_task_percent','tasks.status','table_task_percent.status')
                ->Join('projects','projects.id','tasks.proj_id')
                ->Join('clients', 'clients.id','projects.client_id')
                ->select('tasks.proj_id','projects.project_title','clients.client_company',
                 DB::raw('SUM(table_task_percent.percentage) as percentage'))
                ->where('projects.user_id',$userlogged)
                ->groupBy('tasks.proj_id','projects.project_title','clients.client_company')
                ->get();
        

        foreach($task as $a){
            foreach($task_count as $b){
                if($a->proj_id == $b->proj_id ){
                    $a->percentage= $a->percentage / $b->task_count;
                }
            }
            
        }
        return $task;
        

        
    }
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
        
        $task = Task::findOrFail($id);
        
        $this->validate($request,[
            'priority'=>'required|string',
            'status'=>'required|string',
            
        ]);
         
        $task->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
