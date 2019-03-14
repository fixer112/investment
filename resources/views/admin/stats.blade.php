@extends('admin.layout')
@section('title')
{{Auth::user()->name}} | Statistics
@endsection
@section('content')
@section('bread')
Statistics
@endsection
@section('css')
<style type="text/css">
    .card-body p {
    color: black;
    text-align: center;
}
.stat-head {
    margin-top: 50px;
}
</style>
@endsection    
@php

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
                        <form action="/stats" method="GET" accept-charse="utf-8" class="form-inline">

                          <div class="input-group mb-2 mr-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">Mentor</div>
                            </div>
                         <select id="mentor" type="text" class="form-control" name="mentor" autofocus>

                          <option value="" {{ $mentor == "" ? "selected": ""}}>Choose</option>

                          @foreach($mentors as $ment)
                          @if($ment != "")
                          <option value="{{$ment}}" {{$mentor== $ment ? "selected": ""}}>{{$ment}}</option>
                          @endif

                          @endforeach
                                                          
                          </select>
                          
                        </div>
                        

                          <div class="input-group mb-2 mr-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">From</div>
                            </div>
                          <input id="from" class="form-control" type="date" name="from" value="{{session('from')}}">
                          
                          </div>

                          <div class="input-group mb-2 mr-2">
                            <div class="input-group-prepend">
                              <div class="input-group-text">To</div>
                            </div>
                          <input id="to" class="form-control" type="date" name="to" value="{{session('to')}}">
                          
                          </div>
                          <button type="submit" class="btn btn-primary mb-2">Submit</button>
                        </form> 

                        <div class="stat-head"><h2>Actives</h2></div>
                        <div class="row">

                        <div class="card stat col-4 bg-primary p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Number Of Transactions</p>
                        <p>{{$actives->count()}}</p>
                        </div>
                        </div>
                        </div>

                        <div class="card stat col-4 bg-success p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Total Amount Invested</p>
                        <p>@money($actives->sum('invest_amount'))</p>
                        </div>
                        </div>
                        </div>

                        <div class="card stat col-4 bg-warning p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Total Return Amount</p>
                        <p>@money($actives->sum('return_amount'))</p>
                        </div>
                        </div>
                        </div>

                        </div>


                        <div class="stat-head"><h2>Paids</h2></div>
                        <div class="row">

                        <div class="card stat col-4 bg-primary p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Number Of Transactions</p>
                        <p>{{$paids->count()}}</p>
                        </div>
                        </div>
                        </div>

                        <div class="card stat col-4 bg-success p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Total Amount Invested</p>
                        <p>@money($paids->sum('invest_amount'))</p>
                        </div>
                        </div>
                        </div>

                        <div class="card stat col-4 bg-warning p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Total Return Amount</p>
                        <p>@money($paids->sum('return_amount'))</p>
                        </div>
                        </div>
                        </div>

                        </div>


                        <div class="stat-head"><h2>Rejecteds</h2></div>
                        <div class="row">

                        <div class="card stat col-4 bg-primary p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Number Of Transactions</p>
                        <p>{{$rejecteds->count()}}</p>
                        </div>
                        </div>
                        </div>

                        <div class="card stat col-4 bg-success p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Total Amount Invested</p>
                        <p>@money($rejecteds->sum('invest_amount'))</p>
                        </div>
                        </div>
                        </div>

                        <div class="card stat col-4 bg-warning p-30">
                        <div class="card-content">
                        <div class="card-body">
                        <p>Total Return Amount</p>
                        <p>@money($rejecteds->sum('return_amount'))</p>
                        </div>
                        </div>
                        </div>

                        </div>
                        

                    </div>
            </div>
    </div>
</div>

@endsection
