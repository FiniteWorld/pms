<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Clients;
use DB;

class ClientsController extends Controller
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
            
            // $client = DB::table('clients')
            //         ->Join('projects','clients.id','projects.client_id')
            //         ->select('client_name','client_email','client_company',
            //          DB::raw('count(projects.id) as project_count'))
            //          ->where('clients.user_id',$userlogged)
            //         ->groupBy('client_name','client_email','client_company')
            //         ->get();
            $client = Clients::where('clients.user_id',$userlogged)->get();
            return $client;
            //return $client;
            //return Clients::where('user_id',$userlogged)->get();
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
        $user = auth('api')-> user();
        $userlogged =  $user->id;
        $this->validate($request,[
            'client_name'=>'required|string',
            'client_email'=>'required|string|max:191',
            'client_address'=>'required|string|max:191',
            'client_contact'=>'required|string',
            'client_company'=>'required|string',
            
            
        ]);
        return Clients::create([
            'user_id' => $userlogged,
            'client_name'=> $request['client_name'],
            'client_email' => $request['client_email'],
            'client_address' => $request['client_address'],
            'client_contact' => $request['client_contact'],
            'client_company' => $request['client_company']

          
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
        $client = Clients::findOrFail($id);
        $this->validate($request,[
            'client_name'=>'required|string|max:191',
            'client_company'=>'required|string|max:191',
            'client_email'=>'required|string',
            'client_contact'=>'required|string',
            'client_address'=>'required|string'
        ]);
         
        $client->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            
        $client= Clients::findOrFail($id);

        //delete
        $client -> delete();
        return ['message'=> 'Client Details Deleted'];
    }
    public function search()
    {
        if($search = \Request::get('q')){
            $c = Clients::where(function($query) use ($search){
                
                $query->where('client_name','LIKE',"%search%" )
                        ->orWhere('client_company','LIKE',"%search%" )
                        ->get();

            });
                
        }
        return $c;
    }
}
