@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Mentors
@endsection
@section('content')
@section('bread')
Mentors {{count($mentors)}}
@endsection    

<div class="card">
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

                <div class="col-md-12">
                        <div class="table-responsive m-t-40">
                                    <table id="mentors" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Date Joined</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mentor Number</th>
                                                <th>Actioins</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($mentors)>0)
                                            @foreach($mentors as $mentor)
                                            <tr>
                                                <td>{{$mentor->created_at->format('d/m/Y H:i')}}</td>
                                                <td>{{$mentor->name}}</td>
                                                <td>{{$mentor->email}}</td>
                                                <td>{{$mentor->mentor}}</td>
                                                @if($mentor->role != 'admin')
                                                <td><a href="/admin/cus/{{$mentor->id}}"><button class="btn btn-primary">View Mentor</button></a></td>
                                                @else
                                                <td><span class="badge badge-warning">ADMIN</span></td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                            </div>
                </div>
            </div>
    </div>
</div>

@endsection
