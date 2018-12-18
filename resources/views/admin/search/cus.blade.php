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
                                    <table id="customer" class="data nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Date Joined</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Mobile Number</th>
                                                <th>Refered By</th>
                                                <th>Status</th>
                                                <th>Reason</th>
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
                                                <td>{{$cus->number}}</td>
                                                <td>{{$cus->referal}}</td>
                                                @if($cus->active)
                                                <td><span class="badge badge-success">Active</span></td>
                                                @else
                                                <td><span class="badge badge-danger">Suspended</span></td>
                                                @endif
                                                <td>{{$cus->active ? '' : $cus->reason}}</td>
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
@section('data')
    <script src="{{ asset('js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js') }}"></script>
    <script src="{{ asset('js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js') }}"></script>
@endsection
