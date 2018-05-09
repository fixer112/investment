@extends('cus.mentorcus.layout')
@section('title')
{{$user->name}} | Histories
@endsection
@section('content')
@section('bread')
{{count($historys)}} Historys
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
                                    <table id="history" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Invest Date</th>
                                                <th>Approved Date</th>
                                                <th>Transaction Id</th>
                                                <th>Invest Amount</th>
                                                <th>Tenure</th>
                                                <th>Maturity Date</th>
                                                <th>Paid Date</th>
                                                <th>Expected Return</th>
                                                <th>Status</th>
                                                <th>Actioins</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            @if (count($historys)>0)
                                            @foreach($historys as $history)
                                            <tr>
                                                <td>{{$history->invest_date->format('d/m/Y H:i')}}</td>
                                                <td>{{!empty($history->approved_date) ? $history->approved_date->format('d/m/Y H:i') : ''}}</td>
                                                <td>{{$history->tran_id}}</td>
                                                <td>₦{{$history->invest_amount}}</td>
                                                <td>{{$history->tenure}}</td>
                                                <td>{{!empty($history->return_date) ? $history->return_date->format('d/m/Y H:i') : ''}}</td>
                                                <td>{{!empty($history->paid_date) ? $history->paid_date->format('d/m/Y H:i') : ''}}</td>
                                                <td>₦{{$history->return_amount}}</td>
                                                <td>
                                                @if($history->status == 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                                @elseif ($history->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                                @elseif ($history->status == 'paid')
                                                <span class="badge badge-success">Paid</span>
                                                @elseif ($history->status == 'reject')
                                                <span class="badge badge-success">Rejected</span>
                                                @endif
                                                </td>
                                                
                                                <td><a href="{{asset($history->proof)}}"><button class="btn btn-primary">View Proof</button></a></td>
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
