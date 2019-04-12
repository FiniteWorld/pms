@extends('layouts.master')

@section('content')
  <div class="row mt-3">
    <div class="col-md-6">
    </div>
  
    <div class="col-md-6">
      <div class="card card-primary card-outline" >
        <div class="card-header">
          <h3 class="card-title">Chat</h3>
          <div class="card-tools">
            <button
              type="button"
              class="btn btn-tool"
              data-widget="collapse">
              <i class="fa fa-plus"></i>
            </button>
          </div>
        </div>
        <div class="card-body" style="display:block;">
          <message :user="{{auth()->user()}}"></message>
        </div>
      </div>      
    </div>
  </div>
@endsection


