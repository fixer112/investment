@extends('cus.layout') @section('title') {{Auth::user()->name}} | Invest with
Card @endsection @section('content')
@section('bread')
New Investment with card
@endsection

@section('js')
<script src="{{ asset('js/vue.js')}}"></script>
{{-- <script src="{{ asset('js/axios.js')}}"></script> --}}
@endsection

<div class="card col-md-8 mx-auto">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-12 p-20">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session("success") }}
                </div>
                @endif @if (session('failed'))
                <div class="alert alert-danger">
                    {{ session("failed") }}
                </div>
                @endif
            </div>

            <div class="col-md-6 mx-auto" id="invest">
                <form {{-- method="POST" action="/cus/invest_card" --}} id="payform" method="POST"
                    action="https://voguepay.com/pay/">
                    @csrf
                    <div class="form-group {{$errors->has('amount') ? 'invalid-feedback' : ''}}">
                        <label>Amount</label>
                        <input type="number" step=".01" name="amount" class="form-control" v-model="amount" min="10" required />
                        @if ($errors->has('amount'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('amount') }}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group {{$errors->has('rate') ? 'invalid-feedback' : ''}}">
                        <label>Tenure</label>
                        <select type="option" class="form-control" name="rate" v-model="tenure" required>
                            <option disabled selected>Choose tenure</option>
                            {{--
              <option value="30">30Days/1%daily</option>
              <option value="90">90Days/2%daily</option>
              <option value="180">180Days/3%daily</option>
              <option value="360">360Days/4%daily</option>
              --}}
                            <option value="5">5 Months/1.40%daily</option>
                            <option value="9">9 Months/1.96%daily</option>
                            <option value="18">18 Months/3.84%daily</option>
                            <option value="36">36 Months/7.7%daily</option>
                        </select>
                        @if ($errors->has('rate'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('rate') }}</strong>
                        </span>
                        @endif
                    </div>
                    <input type="hidden" name="success_url" value="http://investment.fixer/notify" />
                    <input type="hidden" name="v_merchant_id" value="9749-0088131" />
                    <input type="hidden" name="price_1" v-model="amount" />
                    <input type="hidden" name="cur" value="USD" />
                    <input type="hidden" name="merchant_ref" v-model="tenure" />
                    <input type="hidden" name="memo"
                        :value="'Investment of USD '+amount+' for '+tenure+' months by {{ Auth::user()->email }}'" />

                    <input type="hidden" name="item_1" :value="'USD '+amount+' - '+tenure+' months'" />
                    <input type="hidden" name="description_1"
                        :value="'Investment of USD '+amount+' for '+tenure+' months by {{ Auth::user()->email }}'" />
                    <input type="hidden" name="developer_code" value="5ce40e64da908" />
                    
                    <input type="image" src="http://voguepay.com/images/buttons/make_payment_green.png" alt="Submit" />
                    {{-- <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">
                        Pay Now
                    </button> --}}
                </form>
            </div>
        </div>
    </div>
</div>
<script>
        new Vue({
            el: '#invest',
            data: function() {
            return {
                amount:"",
                tenure:"",
                
                
            }
          }, 
        });
</script>
@endsection