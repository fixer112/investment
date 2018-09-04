@extends('admin.layout')
@section('title')
Dashboard | {{Auth::user()->name}}
@endsection
@section('content')
@section('bread')
Dashbord
@endsection
@php
$dues = $actives->where('return_date', '<', $now );

@endphp


<div class="row">
<div class="col-md-3">
<div class="card p-30">
<div class="media">
    <div class="media-left meida media-middle">
        <span><i class="fa fa-money f-s-40 color-primary"></i></span>
    </div>
    <div class="media-body media-text-right">
        <h2>{{$all}}</h2>
        <p class="m-b-0">Total Invested</p>
    </div>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card p-30">
<div class="media">
    <div class="media-left meida media-middle">
        <span><i class="fa fa-money f-s-40 color-success"></i></span>
    </div>
    <div class="media-body media-text-right">
        <h2>{{$paids->sum('return_amount')}}</h2>
        <p class="m-b-0">Total Paid Return</p>
    </div>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card p-30">
<div class="media">
    <div class="media-left meida media-middle">
        <span><i class="fa fa-money f-s-40 color-warning"></i></span>
    </div>
    <div class="media-body media-text-right">
        <h2>{{$actives->sum('return_amount')}}</h2>
        <p class="m-b-0">Total Expected Return</p>
    </div>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card p-30">
<div class="media">
<div class="media-left meida media-middle">
        <h6>Total Customers</h6>
        <h6>Total Mentors</h6>
        <h6>Total Admins</h6>
    </div>
    <div class="media-body media-text-right">
        <h6>{{count($cus)}}</h6>
        <h6>{{count($mentor)}}</h6>
        <h6>{{count($admin)}}</h6>
    </div>
    {{-- <div class="media-left meida media-middle">
        <span><i class="fa fa-user f-s-40 color-danger"></i></span>
    </div>
    <div class="media-body media-text-right">
        <h2>847</h2>
        <p class="m-b-0">Customer</p>
    </div> --}}
</div>
</div>
</div>
</div>



<div class="row bg-white m-l-0 m-r-0 box-shadow ">

{{-- <div class="col-lg-12 p-20">

@if (session('success'))
<div class="alert alert-success p-20">
{{ session('success') }}
</div>
@endif


@if (session('failed'))
<div class="alert alert-danger p-20">
{{ session('failed') }}
</div>
@endif
</div> --}}

<!-- column -->
<div class="col-lg-12">

<div class="col-lg-12 p-20">

@if (session('success'))
<div class="alert alert-success p-20">
{{ session('success') }}
</div>
@endif


@if (session('failed'))
<div class="alert alert-danger p-20">
{{ session('failed') }}
</div>
@endif
</div>
<div class="card">
<div class="card-body">
    <h4 class="card-title">History</h4>
     <!-- Nav tabs -->
    <ul class="nav nav-tabs customtab" role="tablist">
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#due" role="tab"><span style="color: #26dad2">{{count($dues)}} Due </span></a> </li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#active" role="tab"><span style="color: blue">{{count($actives)}} Active</span></a> </li>
        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#paid" role="tab"><span style="color: green">{{count($paids)}} Paid</span></a> </li>
        <li class="nav-item"> <a class="nav-link  active" data-toggle="tab" href="#pending" role="tab"><span style="color: yellow">{{count($pendings)}} Pending</span></a> </li>
		<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#reject" role="tab"><span style="color: red">{{count($rejecteds)}} Rejected</span></a> </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">

        <div class="tab-pane" id="due" role="tabpanel">
            
                <div class="table-responsive m-t-40">
        <table id="due" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
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
                    <td>₦{{$due->invest_amount}}</td>
                    <td>{{$due->tenure}}</td>
                    <td>{{$due->approved_date->format('d/m/Y H:i')}}</td>
                    <td>₦{{$due->return_amount}}</td>
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

            
        </div>

        <div class="tab-pane" id="active" role="tabpanel">
            
                <div class="table-responsive m-t-40">
        <table id="active" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Approved Date</th>
                    <th>Invest Date</th>
                    <th>Customer Email</th>
                    <th>Transaction Id</th>
                    <th>Invest Amount</th>
                    <th>Tenure</th>
                    <th>Maturity Date</th>
                    <th>Expected Return</th>
                    <th>Actioins</th>
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($actives)>0)
                @foreach($actives as $active)
                @php
                $active_user = $users->where('id','=', $active->user_id)->first();
                @endphp
                <tr>
                    <td>{{$active->approved_date->format('d/m/Y H:i')}}</td>
                    <td>{{$active->invest_date->format('d/m/Y H:i')}}</td>
                    <td>{{$active_user->email}}</td>
                    <td>{{$active->tran_id}}</td>
                    <td>₦{{$active->invest_amount}}</td>
                    <td>{{$active->tenure}}</td>
                    <td>{{$active->return_date->format('d/m/Y H:i')}}</td>
                    <td>₦{{$active->return_amount}}</td>
                    <td><a href="{{asset($active->proof)}}"><button class="btn btn-primary">View Proof</button></a>

                    <a href="/admin/cus/{{$active_user->id}}"><button class="btn btn-primary">View Customer</button></a>

                    <a href="#delete" aria-expanded="false"  data-toggle="modal"><button class="btn btn-danger" onclick="getid('{{$active->id}}')">Delete</button></a>
                    </td>
                    
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
                </div>

            
        </div>
        <div class="tab-pane" id="paid" role="tabpanel">
		  
                <div class="table-responsive m-t-40">
        <table id="paid" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Paid Date</th>
                    <th>Approved Date</th>
                    <th>Invest Date</th>
                    <th>Customer Email</th>
                    <th>Transaction Id</th>
                    <th>Invest Amount</th>
                    <th>Tenure</th>
                    <th>Maturity Date</th>
                    <th>Amount Returned</th>
                    <th>Actioins</th>
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($paids)>0)
                @foreach($paids as $paid)
                @php
                $paid_user = $users->where('id','=', $paid->user_id)->first();
                @endphp
                <tr>
                    <td>{{$paid->paid_date->format('d/m/Y H:i')}}</td>
                    <td>{{$paid->approved_date->format('d/m/Y H:i')}}</td>
                    <td>{{$paid->invest_date->format('d/m/Y H:i')}}</td>
                    <td>{{$paid_user->email}}</td>
                    <td>{{$paid->tran_id}}</td>
                    <td>₦{{$paid->invest_amount}}</td>
                    <td>{{$paid->tenure}}</td>
                    <td>{{$paid->return_date->format('d/m/Y H:i')}}</td>
                    <td>₦{{$paid->return_amount}}</td>
                    <td><a href="{{asset($paid->proof)}}"><button class="btn btn-primary">View Proof</button></a>

                    <a href="/admin/cus/{{$paid_user->id}}"><button class="btn btn-primary">View Customer</button></a>
                    </td>
                    
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
                

            </div>
		</div>
        <div class="tab-pane  active" id="pending" role="tabpanel">
		   
                <div class="table-responsive m-t-40">
        <table id="pending" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Invest Date</th>
                    <th>Customer Email</th>
                    <th>Transaction Id</th>
                    <th>Invest Amount</th>
                    <th>Tenure</th>
                    <th>Expected Return</th>
                    <th>Actioins</th>
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($pendings)>0)
                @foreach($pendings as $pending)
                @php
                $pending_user = $users->where('id','=', $pending->user_id)->first();
                @endphp
                <tr>
                    <td>{{$pending->invest_date->format('d/m/Y H:i')}}</td>
                    <td>{{$pending_user->email}}</td>
                    <td>{{$pending->tran_id}}</td>
                    <td>₦{{$pending->invest_amount}}</td>
                    <td>{{$pending->tenure}}</td>
                    <td>₦{{$pending->return_amount}}</td>
                    <td><a href="{{asset($pending->proof)}}"><button class="btn btn-primary">View Proof</button></a>

                    <a href="/admin/invest/approve/{{$pending->id}}"><button class="btn btn-success">Approve</button></a>

                    <a href="/admin/invest/reject/{{$pending->id}}"><button class="btn btn-danger">Reject</button></a>

                    <a href="/admin/cus/{{$pending_user->id}}"><button class="btn btn-primary">View Customer</button></a>
                    </td>
                    
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
                </div>

            
		</div>
		<div class="tab-pane" id="reject" role="tabpanel">
		   
                <div class="table-responsive m-t-40">
        <table id="reject" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Rejected Date</th>
                    <th>Invest Date</th>
                    <th>Customer Email</th>
                    <th>Transaction Id</th>
                    <th>Invest Amount</th>
                    <th>Tenure</th>
                    <th>Expected Return</th>
                    <th>Actioins</th>
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($rejecteds)>0)
                @foreach($rejecteds as $rejected)
                @php
                $rejected_user = $users->where('id','=', $rejected->user_id)->first();
                @endphp
                <tr>
                    <td>{{$rejected->approved_date->format('d/m/Y H:i')}}</td>
                    <td>{{$rejected->invest_date->format('d/m/Y H:i')}}</td>
                    <td>{{$rejected_user->email}}</td>
                    <td>{{$rejected->tran_id}}</td>
                    <td>₦{{$rejected->invest_amount}}</td>
                    <td>{{$rejected->tenure}}</td>
                    <td>₦{{$rejected->return_amount}}</td>
                    <td><a href="{{asset($rejected->proof)}}"><button class="btn btn-primary">View Proof</button></a>

                    <a href="/admin/cus/{{$rejected_user->id}}"><button class="btn btn-primary">View Customer</button></a>
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
</div>
</div>

    <!-- The Modal Delete -->
<div class="modal" id="delete">
  <div class="modal-dialog modal-confirm">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
            <div class="icon-box">
                    <i class="material-icons fa fa-trash"></i>
                </div>
        <h4 class="modal-title">Are you sure?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
            <p>Do you really want to delete this transaction? This process cannot be undone.</p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
        <a href="#" id="link"><button type="button" class="btn btn-danger">Delete</button></a>
      </div>

    </div>
  </div>
</div>



@endsection
