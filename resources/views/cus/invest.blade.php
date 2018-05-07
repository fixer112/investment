@extends('cus.layout')
@section('title')
{{Auth::user()->name}} | Invest
@endsection
@section('content')
@section('bread')
New Investment
@endsection    

<div class="card col-md-8 mx-auto">
    <div class="card-body">
            <div class="row">
            <div class="alert alert-danger alert-dismissible fade show col-lg-12 p-30">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Make a payment to Honeypays Micro Credit Investment Limited before You Invest..
                    <br>
                    SKYE BANK 4091107137
                    <br>
                </div>

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

                <div class="col-md-6 mx-auto">
                        <form method="POST" action="/cus/invest" files="true" enctype="multipart/form-data">
                        @csrf
                                    <div class="form-group {{$errors->has('amount') ? 'invalid-feedback' : ''}}">
                                        <label>Amount</label>
                                        <input type="text" name="amount" class="form-control" required>
                                        @if ($errors->has('amount'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('number') ? 'invalid-feedback' : ''}}">
                                        <label>Tenure</label>
                                        <select type="option" class="form-control" name="rate" required>
                                                <option value="30">30Days/1%daily</option>
                                                <option value="90">90Days/2%daily</option>
                                                <option value="180">180Days/3%daily</option>
                                                <option value="360">360Days/4%daily</option>
                                                </select>
                                        @if ($errors->has('rate'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('rate') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('proof') ? 'invalid-feedback' : ''}}">
                                        <label>Upload Proof</label>
                                        <input type="file" name="proof" accept="image/*">
                                        @if ($errors->has('proof'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('proof') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    
                                    
                                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Invest</button>
                                    
                                </form>
                </div>
            </div>
    </div>
</div>

@endsection
