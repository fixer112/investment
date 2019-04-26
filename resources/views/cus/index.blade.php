@extends('cus.layout')
@section('title')
Dashboard | {{Auth::user()->name}}
@endsection
@section('content')
@section('bread')
Dashbord
@endsection
@php
$paids = Auth::user()->history()->where('status', '=', 'paid')->get();
$actives = Auth::user()->history()->where('status', '=', 'active')->get();
$rolls = Auth::user()->roll()->where('status', 0)->get();
$p_refunds = Auth::user()->refund()->where('status', 0)->get();
$a_refunds = Auth::user()->refund()->where('status', 1)->get();
$all = $paids->sum('invest_amount') + $actives->sum('invest_amount');
$pendings = Auth::user()->history()->where('status', '=', 'pending')->get();
$rejecteds = Auth::user()->history()->where('status', '=', 'reject')->get();
@endphp

<div class="row">

                    @if(Auth::user()->id_change == '1')
                <div class="alert alert-danger alert-dismissible fade show col-lg-12 p-30">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Hello {{Auth::user()->name}}!</strong> You have an invalid identity, please change it in your <a href="/cus/edit"> profile</a>.
                </div>
                    @elseif(Auth::user()->id_change == '2')
                <div class="alert alert-warning alert-dismissible fade show col-lg-12 p-30">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Hello {{Auth::user()->name}}!</strong> Your identity is awaiting aproval.
                </div>
                @endif

                </div>

<div class="row">
                    <div class="col-md-4">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-money f-s-40 color-primary"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2>@money($all)</h2>
                                    <p class="m-b-0">Total Invested</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-money f-s-40 color-success"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2>@money($paids->sum('return_amount'))</h2>
                                    <p class="m-b-0">Total Paid Return</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-money f-s-40 color-warning"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2>@money($actives->sum('return_amount'))</h2>
                                    <p class="m-b-0">Total Expected Return</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-user f-s-40 color-danger"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2>847</h2>
                                    <p class="m-b-0">Customer</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>


                <div class="card">
                                        <div class="card-body">
                                        <h4 class="card-title">Profile</h4>

                                        <div class="row">
                                        
                                        <div class="col-md-6">

                                            <div class="col-xs-12"> <strong>Full Name</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->name}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Mobile</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->number}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Email</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->email}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Address</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->addr}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>City</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->city}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>State</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->state}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Account Number</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->acc_no}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Account Name</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->acc_name}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Bank Name</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->bank_name}}</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Account Manager</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->referal}}</p>
                                            </div>
                                            @if(!empty(Auth::user()->mentor))
                                            <div class="col-md-12"> <strong>Mentor Number</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->mentor}}</p>
                                            </div>
                                            @endif

                                            @if(Auth::user()->active)
                                            <div class="col-md-12"> <strong>Status</strong>
                                                <br>
                                                <p class="text-muted" style="color: green !important">Active</p>
                                            </div>
                                            
                                            @else
                                            <div class="col-md-12"> <strong>Status</strong>
                                                <br>
                                                <p class="text-muted" style="color: red !important">Suspended</p>
                                            </div>
                                            <div class="col-md-12"> <strong>Reason</strong>
                                                <br>
                                                <p class="text-muted">{{Auth::user()->reason}}</p>
                                            </div>
                                            
                                            @endif

                                        </div>
                                        <div class="col-md-6">
                                        <br>
                                        <div class="col-md-12">
                                        <a href="{{ asset(Auth::user()->identity)}}"><img src="{{ asset(Auth::user()->identity)}}" width="300px" height="250px"></a>
                                        <br><br>
                                        @if(Auth::user()->id_change == '1')
                                        <a href="/cus/edit"><button class="btn btn-primary"><i class="fa fa-edit"></i>Change Id</button></a>
                                        @endif
                                        </div>
                                        <br><br>
                                         <div class="col-md-12">
                                         <div class="alert alert-primary">
                                             Make a payment to Honeypays Micro Credit Investment Limited before You Invest..
                                             <br>
                                             SKYE BANK 4091107137
                                         </div>
                                        </div>

                                        </div>
                                        </div>
                                        </div>
                </div>
                

                <div class="row bg-white m-l-0 m-r-0 box-shadow ">

                    <!-- column -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">History</h4>
                                 <!-- Nav tabs -->
                                <ul class="nav nav-tabs customtab" role="tablist">
                                    <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#active" role="tab"><span style="color: blue">{{$actives->count()}} Active</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#paid" role="tab"><span style="color: green">{{$paids->count()}} Paid</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#pending" role="tab"><span style="color: yellow">{{$pendings->count()}} Pending</span></a> </li>
									<li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#reject" role="tab"><span style="color: red">{{$rejecteds->count()}} Rejected</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#roll" role="tab"><span style="color: blue">{{$rolls->count()}} Rollovers</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#a_refund" role="tab"><span style="color: green">{{$a_refunds->count()}} Approved Refunds</span></a> </li>
                                    <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#p_refund" role="tab"><span style="color: blue">{{$p_refunds->count()}} Pending Refunds</span></a> </li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div class="tab-pane active" id="active" role="tabpanel">
                                        
                                            <div class="table-responsive m-t-40">
                                    <table id="active" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Approved Date</th>
                                                <th>Invest Date</th>
                                                <th>Transaction Id</th>
                                                <th>Invest Amount</th>
                                                <th>Tenure</th>
                                                <th>Maturity Date</th>
                                                <th>Expected Return</th>
                                                <th>Due for Payment</th>
                                                <th>Reciept</th>
                                                <th>Actioins</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($actives)>0)
                                            @foreach($actives as $active)
                                           
                                            <tr>
                                                <td>{{$active->approved_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$active->invest_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$active->tran_id}}</td>
                                                <td>@money($active->invest_amount)</td>
                                                <td>{{$active->tenure}}</td>
                                                <td>{{$active->return_date->format('d/m/Y H:i')}}</td>
                                                <td>@money($active->return_amount)</td>
                                                <td>
                                                <span class="badge {{$active->return_date < $now ? 'badge-success' : 'badge-danger'}}">{{$active->return_date < $now ? 'Yes' : 'No'}}</span>
                                                </td>
                                                <td>
                                                    @component('component.receipt')
                                                        @slot('id')
                                                            {{$active->id}}
                                                        @endslot
                                                    @endcomponent
                                                </td>
                                                <td><a href="{{asset($active->proof)}}"><button class="btn btn-primary">View Proof</button></a></td>
                                                
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
                                                <th>Transaction Id</th>
                                                <th>Invest Amount</th>
                                                <th>Tenure</th>
                                                <th>Maturity Date</th>
                                                <th>Amount Returned</th>
                                                <th>Reciept</th>
                                                <th>Actioins</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($paids)>0)
                                            @foreach($paids as $paid)
                                            <tr>
                                                <td>{{$paid->paid_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$paid->approved_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$paid->invest_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$paid->tran_id}}</td>
                                                <td>@money($paid->invest_amount)</td>
                                                <td>{{$paid->tenure}}</td>
                                                <td>{{$paid->return_date->format('d/m/Y H:i')}}</td>
                                                <td>@money($paid->return_amount)</td>
                                                <td>
                                                    @component('component.receipt')
                                                        @slot('id')
                                                            {{$paid->id}}
                                                        @endslot
                                                    @endcomponent
                                                </td>
                                                <td><a href="{{asset($paid->proof)}}"><button class="btn btn-primary">View Proof</button></a></td>
                                                
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                       

                                        </div>
									</div>
                                    <div class="tab-pane" id="pending" role="tabpanel">
									   
                                            <div class="table-responsive m-t-40">
                                    <table id="pending" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Invest Date</th>
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
                                            <tr>
                                                <td>{{$pending->invest_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$pending->tran_id}}</td>
                                                <td>@money($pending->invest_amount)</td>
                                                <td>{{$pending->tenure}}</td>
                                                <td>@money($pending->return_amount)</td>
                                                <td><a href="{{asset($pending->proof)}}"><button class="btn btn-primary">View Proof</button></a></td>
                                                
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
                                            <tr>
                                                <td>{{$rejected->approved_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$rejected->invest_date->format('d/m/Y H:i')}}</td>
                                                <td>{{$rejected->tran_id}}</td>
                                                <td>@money($rejected->invest_amount)</td>
                                                <td>{{$rejected->tenure}}</td>
                                                <td>@money($rejected->return_amount)</td>
                                                <td><a href="{{asset($rejected->proof)}}"><button class="btn btn-primary">View Proof</button></a></td>
                                                
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    
                                            </div>

                                        
									</div>

                                <div class="tab-pane" id="roll" role="tabpanel">
           
                <div class="table-responsive m-t-40">
        <table id="reject" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Apply Date</th>
                    <th>Investment Id</th>
                    <th>Customer Email</th>
                    <th>Invest Amount</th>
                    <th>Tenure</th>
                    <th>Type</th>
                    
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($rolls)>0)
                @foreach($rolls as $roll)
                @php
               
                @endphp
                <tr>
                    <td>{{$roll->created_at->format('d/m/Y H:i')}}</td>
                    <td>{{$roll->history->tran_id}}</td>
                    <td>{{$roll->user->email}}</td>
                    <td>@money($roll->history->return_amount)</td>
                    <td>{{$roll->tenure}}</td>
                    <td>{{$roll->type ? 'one time' : 'six times'}}</td>
                    
                    
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
       
                </div>

            
        </div>

        <div class="tab-pane" id="a_refund" role="tabpanel">
           
                <div class="table-responsive m-t-40">
        <table id="reject" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Approved Date</th>
                    <th>Transaction Id</th>
                    <th>Customer Email</th>
                    <th>Invest Amount</th>
                    <th>Tenure</th>
                   
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($a_refunds)>0)
                @foreach($a_refunds as $a_refund)
                @php
               
                @endphp
                <tr>
                    <td>{{$a_refund->updated_at}}</td>
                    <td>{{$a_refund->history->tran_id}}</td>
                    <td>{{$a_refund->user->email}}</td>
                    <td>@money($a_refund->history->invest_amount)</td>
                    <td>{{$a_refund->history->tenure}}</td>
                    
                    
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        
                </div>

            
        </div>
        <div class="tab-pane" id="p_refund" role="tabpanel">
           
                <div class="table-responsive m-t-40">
        <table id="reject" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Apply Date</th>
                    <th>Transaction Id</th>
                    <th>Invest Amount</th>
                    <th>Tenure</th>
                    <th>Message</th>
                    {{-- <th>Actioins</th> --}}
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($p_refunds)>0)
                @foreach($p_refunds as $p_refund)
                @php
               
                @endphp
                <tr>
                    <td>{{$p_refund->created_at}}</td>
                    <td>{{$p_refund->history->tran_id}}</td>
                    <td>@money($p_refund->history->invest_amount)</td>
                    <td>{{$p_refund->history->tenure}}</td>
                    <td>{{$p_refund->message}}</td>
                    
                    
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
                    
                


                
@endsection
