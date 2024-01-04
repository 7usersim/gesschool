@extends("layouts.connexion")
@section("title",__("Log to your account"))
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5">
        <div class="card">

            <div class="card-body p-4">
                <div class="p-2">
                    @include('flash-message')
                    <h5 class="mb-5 text-center">Sign in to continue to {{env('APP_NAME')}}</h5>
                    <form class="form-horizontal" method="POST" action="{{url('/singIn')}}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{old("username")}}" id="username" placeholder="Enter username">
                                    @error('username')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
				 					 @enderror
                                </div>
                                <div class="form-group mb-4">
                                    <label for="userpassword">Password</label>
                                    <input type="password" name="password" value="{{old("password")}}" class="form-control @error('password') is-invalid @enderror" id="userpassword" placeholder="Enter password">
                                    @error('password')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
				 					 @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" value="{{old("username")}}" class="custom-control-input" id="customControlInline">
                                            <label class="custom-control-label" for="customControlInline">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-md-right mt-3 mt-md-0">
                                            <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock"></i> Forgot your password?</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-success btn-block waves-effect waves-light" type="submit">Log In</button>
                                </div>
                                <div class="mt-4 text-center">
                                    <a href="/" class="text-muted"><i class="mdi mdi-account-circle mr-1"></i> Create an account</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
