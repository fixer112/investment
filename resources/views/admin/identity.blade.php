@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Customer Identity Check
@endsection
@section('content')
@section('bread')
Identities
@endsection    

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Identities {{count($identitys)}}</h4>
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
                                    <table id="identitys" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Date Joined</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Actioins</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($identitys)>0)
                                            @foreach($identitys as $identity)
                                            <tr>
                                                <td>{{$identity->created_at->format('d/m/Y H:i')}}</td>
                                                <td>{{$identity->name}}</td>
                                                <td>{{$identity->email}}</td>
                                                <td>
                                                <a href="{{$identity->identity}}"><button class="btn btn-primary">View Identity</button></a>
                                                <a href="/admin/cus/{{$identity->id}}"><button class="btn btn-primary">View Customer</button></a>
                                                <a href="/admin/verifyid/{{$identity->id}}"><button class="btn btn-success">Verify Id</button></a>
                                                <a href="/admin/changeid/{{$identity->id}}"><button class="btn btn-success">Invalid Id</button></a>
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
