@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Dues
@endsection
@section('content')
@section('bread')
Dues ({{$dues->count()}})
@endsection    
@php
$months = ["jan","Feb","Mar","April","May","Jun","July","Aug","Sep","Oct","Nov","Dec"];
@endphp

<div class="card col-12 mx-auto">
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

                    <div class="col-12 mx-auto">
                        <form method="POST" action="/admin/dues" files="true" enctype="multipart/form-data" class="form-inline">
                        @csrf
                          <div class="input-group mb-2 mr-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">Month</div>
                            </div>
                            <select name="month" class="form-control">
                        @foreach($months as $key=>$value)
                            @php
                                $key = $key < 9 ? "0".($key+1) : $key + 1;
                            @endphp
                            <option {{$month == $key ? 'selected':''}} value="{{$key}}">{{$value}}</option>
                        @endforeach
                        </select>
                          </div>
                          <div class="input-group mb-2 mr-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">Year</div>
                            </div>
                          <input id="from" class="form-control" type="number" name="year" value="{{$year}}">
                          </div>
                        
                            <button type="submit" class="form-control btn btn-primary btn-flat">Submit</button> 

                        </form>

                                <div class="table-responsive m-t-40">
                        <table id="due" class="data nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Maturity Date</th>
                                    <th>Invest Date</th>
                                    <th>Customer Email</th>
                                    <th>Transaction Id</th>
                                    <th>Invest Amount</th>
                                    <th>Tenure</th>
                                    <th>Approved Date</th>
                                    <th>Expected Return</th>
                                    <th>Reciept</th>
                                    <th>Actioins</th>
                                    
                                </tr>
                            </thead>
                            
                            <tbody>
                                @if (count($dues)>0)
                                @foreach($dues as $due)
                                @php
                                $due_user = $users->where('id','=', $due->user_id)->first();
                                @endphp
                                
                                <tr>
                                    <td>{{$due->return_date->format('d/m/Y H:i')}}</td>
                                    <td>{{$due->invest_date->format('d/m/Y H:i')}}</td>
                                    <td>{{$due_user->email}}</td>
                                    <td>{{$due->tran_id}}</td>
                                    <td>@money($due->invest_amount)</td>
                                    <td>{{$due->tenure}}</td>
                                    <td>{{$due->approved_date->format('d/m/Y H:i')}}</td>
                                    <td>@money($due->return_amount)</td>
                                    <td>
                                        @component('component.receipt')
                                            @slot('id')
                                                {{$due->id}}
                                            @endslot
                                        @endcomponent
                                    </td>
                                    <td><a href="{{asset($due->proof)}}"><button class="btn btn-primary">View Proof</button></a>

                                    <a href="/admin/cus/{{$due_user->id}}"><button class="btn btn-primary">View Customer</button></a>

                                    <a href="/admin/approvepaid/{{$due->id}}"><button class="btn btn-primary">Verify Payment</button></a>
                                    </td>
                                    
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                                </div>

                        <a href="/dues/{{$month}}/{{$year}}"><button class="btn btn-primary mt-5">Download</button></a>

                        <a href="/dues/{{$month}}/{{$year}}?type=email"><button class="btn btn-primary mt-5">Email</button></a>


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