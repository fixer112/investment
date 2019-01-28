@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Admins
@endsection
@section('content')
@section('bread')
Admins {{count($admins)}}
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
                                    <table id="admins" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Date Joined</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mentor Number</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($admins)>0)
                                            @foreach($admins as $admin)
                                            <tr>
                                                <td>{{$admin->created_at->format('d/m/Y H:i')}}</td>
                                                <td>{{$admin->name}}</td>
                                                <td>{{$admin->email}}</td>
                                                <td>{{$admin->mentor}}</td>
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
