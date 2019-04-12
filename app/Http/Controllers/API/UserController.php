<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Team;
use App\projects;
use DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        //$this-> authorize('isAdmin');

        if(\Gate::allows('isAdmin')|| \Gate::allows('isManager'))
        {
            
            return User::latest()->paginate(8);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users',
            'type'=>'required|string',
            'password'=>'required|string|min:8'
        ]);
        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'type' => $request['type'],
            'bio' => $request['bio'],
            'photo' => $request['photo'],
            'password' => Hash::make($request['password']),
            'skills' => $request['skills'],
            'education' => $request['education'],
            'location' => $request['location'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return auth('api')->user();
    }
    public function updateProfile(Request $request){
         $user = auth('api')-> user();
        $currentPhoto = $user -> photo;
        if ($request->photo !=$currentPhoto){
            $name= time().'.' .explode('/',explode(':', substr($request->photo, 0, 
            strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('images/profile/').$name);

            $request -> merge(['photo' => $name]);
        } 
            $this->validate($request,[
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
            'skills'=>'sometimes|string|max:191',
            'education'=>'sometimes|string|max:191',
            'location'=>'sometimes|string|max:191'
        ]);
      
       
        $user -> update($request ->all());
        
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
        $user = User::findOrFail($id);
         $this->validate($request,[
            'name'=>'required|string|max:191',
            'email'=>'required|string|email|max:191|unique:users,email,'.$user->id,
            'type'=>'required|string',
            'password'=>'sometimes|string|min:6'
        ]);
        $user->update($request->all());
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this-> authorize('isAdmin');
        $user= User::findOrFail($id);

        //delete
        $user -> delete();
        return ['message'=> 'User Deleted'];

    }

    public function search(){
        if ($search = \Request::get('q')){
            $users = User::where(function($query) use ($search){
                $query -> where('name','LIKE',"%$search%")
                 -> orWhere ('email', 'LIKE', "%$search%");
            });
        }
        return $users;
    }
    public function totalCount()
    {   
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        
            $user_count = DB::table('users')->count();
            $team_count = DB::table('team')->count();
            $project_count = DB::table('team')->where('member_id',$userlogged)->count();
            $member_count =DB::table('team')->where('user_id',$userlogged)->count();
            $Aproject_count = DB::table('projects')->count();
            $Mproject_count = DB::table('projects')->where('user_id',$userlogged)->count();


            return collect([$user_count,$team_count,$project_count,$member_count,$Aproject_count, $Mproject_count]);
               
    }

}
