@extends("layouts.admin")
@section("title",__("Manage Roles"))
@section("title_content",__("Manage Role"))
@section('content')
<div class="row">
    <div class="col-md-3"></div>
        <div class="col-md-6">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('message'))
                <div class="alert">{{ session('message') }}</div>
            @endif

            <div class="card">
                <div class="card-header bg-primary text-white"> <i class="fa fa-plus"></i> {{__('Add new role')}}</div>
                <div class="card-body">
                    <form method="POST"action="{{url('roles/save')}}" class="needs-validation">
                        @csrf
                        <div class="form-group">
                          <label for="roleName">{{__(" Role")}}</label>
                          <input type="text" name="roleName" required class="form-control @error('roleName') is-invalid @enderror" id="roleName" aria-describedby="emailHelp" placeholder="{{__('Enter value')}}">

                        </div>
                        <div class="form-group">
                          <label for="roleCode">Code</label>
                          <input type="text" name="roleCode" required class="form-control @error('roleCode') is-invalid @enderror" id="roleCode" placeholder="{{__('Enter value')}}">
                        </div>
                        <div class="form-group">
                            <label for="roleName">{{__(" Status")}}</label>
                            <select class="form-control" name="status">
                                <option value="Actif">{{__("Actif")}}</option>
                                <option value="Inactif">{{__("Inactif")}}</option>
                            </select>

                          </div>
                        <button type="submit" class="btn btn-primary">{{__(" Save")}}</button>
                      </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
</div>
@endsection
