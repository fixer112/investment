@extends('cus.layout')
@section('title')
{{Auth::user()->name}} | Account Manager
@endsection
@section('content')
@section('bread')
Account Manager
@endsection    

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Account Manager {{count($referals)}}</h4>
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
                                    <table id="referals" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Date Joined</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($referals)>0)
                                            @foreach($referals as $referal)
                                            <tr>
                                                <td>{{$referal->created_at->format('d/m/Y H:i')}}</td>
                                                <td>{{$referal->name}}</td>
                                                <td>{{$referal->email}}</td>
                                                <td>
                                                    <a href="/cus/mentorcus/{{$referal->id}}"><button class="btn btn-primary">View Customer</button></a>
                                                </td>
                                                
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
