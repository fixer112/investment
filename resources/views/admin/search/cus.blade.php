@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Customers
@endsection
@section('content')
@section('bread')
{{count($cuss)}} Customers
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
                                    <table id="customer" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Date Joined</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Refered By</th>
                                                <th>Actioins</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($cuss)>0)
                                            @foreach($cuss as $cus)
                                            <tr>
                                                <td>{{$cus->created_at->format('d/m/Y H:i')}}</td>
                                                <td>{{$cus->name}}</td>
                                                <td>{{$cus->email}}</td>
                                                <td>{{$cus->referal}}</td>
                                                <td>
                                                <a href="/admin/cus/{{$cus->id}}"><button class="btn btn-primary">View Customer</button></a>
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
