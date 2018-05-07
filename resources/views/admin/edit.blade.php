@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Profile
@endsection
@section('content')
@section('bread')
Profile
@endsection    


<div class="card col-md-8 mx-auto">
    <div class="card-body">
            <div class="row">
            <div class="alert alert-danger alert-dismissible fade show col-lg-12 p-20">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Only Edit Field You Need To Change!!!</strong>
                </div>

                <div class="col-lg-12 p-20">

                @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    @if (session('failed'))
                        <div class="alert alert-danger">
                            {{ session('failed') }}
                        </div>
                    @endif
                    </div>

                <div class="col-md-6 mx-auto">
                        <form method="POST" action="/admin/edit" files="true" enctype="multipart/form-data">
                        @csrf
                                    <div class="form-group {{$errors->has('name') ? 'invalid-feedback' : ''}}">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{Auth::user()->name}}" required>
                                        @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('email') ? 'invalid-feedback' : ''}}">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control" placeholder="{{Auth::user()->email}}">
                                        @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    
                                    <div class="form-group {{$errors->has('password') ? 'invalid-feedback' : ''}}">
                                        <label>New Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="leave blank if not changing">
                                        @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group {{$errors->has('password_confirmation') ? 'invalid-feedback' : ''}}">
                                        <label>Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="leave blank if not changing">
                                        @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Edit Profile</button>
                                    
                                </form>
                </div>
            </div>
    </div>
</div>

@endsection
