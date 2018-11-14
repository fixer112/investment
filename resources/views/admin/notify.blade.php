@extends('admin.layout')
@section('title')
Application Notification
@endsection
@section('content')
@section('bread')
Notification
@endsection    


<div class="card col-md-8 mx-auto">
    <div class="card-body">
            <div class="row">

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
                        <form method="POST" action="/notify" files="true" enctype="multipart/form-data">
                        @csrf
                                   <div class="form-group {{$errors->has('title') ? 'invalid-feedback' : ''}}">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control" required>
                                        @if ($errors->has('title'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('body') ? 'invalid-feedback' : ''}}">
                                        <label>Message</label>
                                        <textarea class="form-control" rows="5" name="body" class="form-control" rows="10" cols="50"></textarea>
                                        @if ($errors->has('body'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('body') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Send Notification</button>
                                    
                                </form>
                </div>
            </div>
    </div>
</div>

@endsection
