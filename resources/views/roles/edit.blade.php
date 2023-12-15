@extends("layouts.admin")
@section("title",__("Edit Role"))
@section("title_content",__("Edit Role"))
@section('content')
<div class="row">
    <div class="col-md-3"></div>
        <div class="col-md-6">
            @include('flash-message')

            <div class="card">
                <div class="card-header bg-primary text-white"> <i class="fa fa-plus"></i> {{__('Add new role')}}</div>
                <div class="card-body">
                    <form method="POST"action="{{url('roles/update')}}" class="needs-validation">
                        @csrf
                        <input type="hidden" name="cmd" value="{{$role->id}}">
                        <div class="form-group">
                          <label for="roleName">{{__(" Role")}}</label>
                          <input type="text" name="roleName" value="{{$role->role_name}}" required class="form-control @error('roleName') is-invalid @enderror" id="roleName" aria-describedby="emailHelp" placeholder="{{__('Enter value')}}">

                        </div>
                        <div class="form-group">
                          <label for="roleCode">Code</label>
                          <input type="text" name="roleCode" required value="{{$role->code_role}}" class="form-control @error('roleCode') is-invalid @enderror" id="roleCode" placeholder="{{__('Enter value')}}">
                        </div>
                        <div class="form-group">
                            <label for="roleName">{{__(" Status")}}</label>
                            <select class="form-control" name="status">
                                <option @if($role->status_role =='Actif' ) selected @endif value="Actif">{{__("Actif")}}</option>
                                <option @if($role->status_role =='Inactif' ) selected @endif value="Inactif">{{__("Inactif")}}</option>
                            </select>

                          </div>
                        <button type="submit" class="btn btn-primary">{{__(" Update")}}</button>
                      </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
</div>
@endsection
