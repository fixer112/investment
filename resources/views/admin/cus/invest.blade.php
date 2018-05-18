@extends('admin.cus.layout')
@section('title')
{{Auth::user()->name}} | Profile
@endsection
@section('content')
@section('bread')
Custom Invest
@endsection    


<div class="card col-md-8 mx-auto">
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

                <div class="col-md-6 mx-auto">
                        <form method="POST" action="/admin/invest/{{$user->id}}" files="true" enctype="multipart/form-data">
                        @csrf

                                    <div class="form-group {{$errors->has('invest_amount') ? 'invalid-feedback' : ''}}">
                                        <label>Invest Amount</label>
                                        <input type="number" name="invest_amount" class="form-control" value="{{old('invest_amount')}}" required>
                                        @if ($errors->has('invest_amount'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('invest_amount') }}</strong>
                                    </span>
                                @endif
                                    </div>


                                    <div class="form-group {{$errors->has('return_amount') ? 'invalid-feedback' : ''}}">
                                        <label>Return Amount</label>
                                        <input type="number" name="return_amount" class="form-control" value="{{old('return_amount')}}" required>
                                        @if ($errors->has('return_amount'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('return_amount') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('tenure') ? 'invalid-feedback' : ''}}">
                                        <label>tenure</label>
                                        <input type="text" name="tenure" class="form-control" value="{{old('tenure')}}" required>
                                        @if ($errors->has('tenure'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('tenure') }}</strong>
                                    </span>
                                @endif
                                    </div>

                                    <div class="form-group {{$errors->has('invest_date') ? 'invalid-feedback' : ''}}">
                                        <label>Invest Date</label>
                                        <input type="text" name="invest_date" class="form-control" value="{{ old('invest_date')}}" placeholder="dd/mm/yy e.g 27/12/18" required>
                                        @if ($errors->has('invest_date'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('invest_date') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    
                                    <div class="form-group {{$errors->has('return_date') ? 'invalid-feedback' : ''}}">
                                        <label>Maturity Date</label>
                                        <input type="text" name="return_date" class="form-control" value="{{old('return_date')}}" placeholder="dd/mm/yy e.g 27/12/18" required>
                                        @if ($errors->has('return_date'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('return_date') }}</strong>
                                    </span>
                                @endif
                                    </div>
                                    
                                    
                                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Submit</button>
                                    
                                </form>
                </div>
            </div>
    </div>
</div>

@endsection
