@extends('cus.layout') @section('title') {{Auth::user()->name}} | Invest with
Card @endsection @section('content')
@section('bread')
New Investment with card
@endsection

@section('js')
<script src="{{ asset('js/vue.js')}}"></script>
{{-- <script src="{{ asset('js/axios.js')}}"></script> --}}
@endsection
@php
$name = explode(' ', Auth::user()->name, 2);
@endphp
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
                <form method="POST" action="{{ route('pay') }}" id="paymentForm">
                    @csrf
                    <div class="form-group {{$errors->has('amount') ? 'invalid-feedback' : ''}}">
                        <label>Amount</label>
                        <input type="number" step=".01" class="form-control" min="10" v-model="amount" required />
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
                    <input type="hidden" name="amount" :value="amount">
                    <!-- Replace the value with your transaction amount -->
                    <input type="hidden" name="payment_method" value="both" /> <!-- Can be card, account, both -->
                    <input type="hidden" name="description"
                        :value="'Investment of USD '+amount+' for '+tenure+' months by ' + '{{ Auth::user()->email }}'" />
                    <!-- Replace the value with your transaction description -->
                    <input type="hidden" name="country" value="NG" />
                    <!-- Replace the value with your transaction country -->
                    <input type="hidden" name="currency" value="USD" />
                    <!-- Replace the value with your transaction currency -->
                    <input type="hidden" name="email" value="{{ Auth::user()->email }}" />
                    <!-- Replace the value with your customer email -->
                    <input type="hidden" name="firstname" value="{{$name[0]}}" />
                    <!-- Replace the value with your customer firstname -->
                    <input type="hidden" name="lastname" value="{{$name[1]}}" />
                    <!-- Replace the value with your customer lastname -->
                    <input type="hidden" name="phonenumber" value="{{Auth::user()->number}}" />
                    <!-- Replace the value with your customer phonenumber -->
                    <input type="hidden" name="metadata" :value="metadata">
                    <!-- Meta data that might be needed to be passed to the Rave Payment Gateway -->
                    {{-- <input type="hidden" name="paymentplan" value="362" /> <!-- Ucomment and Replace the value with the payment plan id --> --}}
                    {{-- <input type="hidden" name="ref" value="MY_NAME_5uwh2a2a7f270ac98" /> <!-- Ucomment and  Replace the value with your transaction reference. It must be unique per transaction. You can delete this line if you want one to be generated for you. --> --}}
                    {{-- <input type="hidden" name="logo" value="https://pbs.twimg.com/profile_images/915859962554929153/jnVxGxVj.jpg" /> <!-- Replace the value with your logo url (Optional, present in .env)--> --}}
                    {{-- <input type="hidden" name="title" value="Flamez Co" /> <!-- Replace the value with your transaction title (Optional, present in .env) --> --}}


                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">
                        Pay Now
                    </button>
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
                amount:'',
                tenure:"",
                meta:[{metaname: "tenure",
                metavalue: ""}],
                metadata:""
                
                
            }
          },
          watch:{
              tenure(n){
                this.meta[0]['metavalue'] = n;
                console.log(this.meta);
                this.metadata = JSON.stringify(this.meta);
                console.log(JSON.stringify(this.meta));
              }
          } 
        });
</script>
@endsection