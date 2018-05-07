@extends('admin.cus.layout')
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
                        <form method="POST" action="/admin/cus/edit/{{$user->id}}" files="true" enctype="multipart/form-data">
                        @csrf
                                    <div class="form-group {{$errors->has('name') ? 'invalid-feedback' : ''}}">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{$user->name}}" required>
                                        @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('email') ? 'invalid-feedback' : ''}}">
                                        <label>Email</label>
                                        <input type="text" name="email" class="form-control" placeholder="{{$user->email}}">
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
                                    <div class="form-group {{$errors->has('number') ? 'invalid-feedback' : ''}}">
                                        <label>Mobile Number</label>
                                        <input type="text" name="number" class="form-control" placeholder="{{$user->number}}">
                                        @if ($errors->has('number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('acc_no') ? 'invalid-feedback' : ''}}">
                                        <label>Account Number</label>
                                        <input type="text" name="acc_no" class="form-control" value="{{$user->acc_no}}" required>
                                        @if ($errors->has('acc_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('acc_no') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group {{$errors->has('acc_name') ? 'invalid-feedback' : ''}}">
                                        <label>Account Name</label>
                                        <input type="text" name="acc_name" class="form-control" value="{{$user->acc_name}}" required>
                                        @if ($errors->has('acc_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('acc_name') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group {{$errors->has('bank_name') ? 'invalid-feedback' : ''}}">
                                        <label>Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control" value="{{$user->bank_name}}" required>
                                        @if ($errors->has('bank_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('bank_name') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group {{$errors->has('addr') ? 'invalid-feedback' : ''}}">
                                        <label>Address</label>
                                        <input type="text" name="addr" class="form-control" value="{{$user->addr}}" required>
                                        @if ($errors->has('addr'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('addr') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group {{$errors->has('city') ? 'invalid-feedback' : ''}}">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" value="{{$user->city}}" required>
                                        @if ($errors->has('city'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group {{$errors->has('state') ? 'invalid-feedback' : ''}}">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control" value="{{$user->state}}" required>
                                        @if ($errors->has('state'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    <div class="form-group {{$errors->has('mode_id') ? 'invalid-feedback' : ''}}">
                                        <label>Mode Of Identity</label>
                                        <input type="text" name="mode_id" class="form-control" value="{{$user->mode_id}}" placeholder="e.g National Idcard" required>
                                        @if ($errors->has('mode_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mode_id') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    @if($user->id_change == '1')
                                    <div class="form-group {{$errors->has('identity') ? 'invalid-feedback' : ''}}">
                                        <label>Upload Identity</label>
                                        <input type="file" name="identity" accept="image/*">
                                        @if ($errors->has('identity'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('identity') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    @endif
                                    
                                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Edit Profile</button>
                                    
                                </form>
                </div>
            </div>
    </div>
</div>

@endsection
