@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Records
@endsection
@section('content')
@section('bread')
Records
@endsection
<div class="table-responsive m-t-40">
        <table id="detail" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
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
                    <th>Reciept</th>
                    <th>Actioins</th>
                    
                </tr>
            </thead>
            
            <tbody>
                @if (count($details)>0)
                @foreach($details as $detail)
                @php
                $detail_user = $users->where('id','=', $detail->user_id)->first();
                @endphp
                <tr>
                    <td>{{$detail->approved_date}}</td>
                    <td>{{$detail->invest_date}}</td>
                    <td>{{$detail_user->email}}</td>
                    <td>{{$detail->tran_id}}</td>
                    <td>@money($detail->invest_amount)</td>
                    <td>{{$detail->tenure}}</td>
                    <td>{{$detail->return_date}}</td>
                    <td>@money($detail->return_amount)</td>
                    <td>
                        @component('component.receipt')
                            @slot('id')
                                {{$detail->id}}
                            @endslot
                        @endcomponent
                    </td>
                    <td><a href="{{asset($detail->proof)}}"><button class="btn btn-primary">View Proof</button></a>

                    <a href="/admin/cus/{{$detail_user->id}}"><button class="btn btn-primary">View Customer</button></a>
                    </td>
                    
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
        
                </div>
@endsection
