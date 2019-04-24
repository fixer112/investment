@extends('cus.layout')
@section('title')
{{Auth::user()->name}} | Apply Roll Over
@endsection
@section('content')
@section('bread')
Roll Over
@endsection
@section('js')
<script src="{{ asset('js/vue.js')}}"></script>
<script src="{{ asset('js/axios.js')}}"></script>

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

                <div class="col-md-6 mx-auto" id="roll">
                        <form method="POST" action="/cus/roll" @keydown.enter.prevent.self>
                        @csrf
                                <div class="form-group">
                                    <label>Active Transactions</label>
                                    <select name="trans" class="form-control" required>
                                    @foreach($actives as $active)
                                    @php
                                    $roll = $active->roll;
                                    if ($roll) {
                                        $disable = 1;
                                        $status = $roll->status ? 'Active' : 'Pending';
                                    }else{
                                        $disable = 0;
                                        $status = '';
                                    }
                                    @endphp
                                    <option value="{{$active->id}}" {{$disable ? 'disabled':''}}>@money($active->invest_amount) - @money($active->return_amount) {{$status}}</option>
                                    @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label>Rollover Type</label>
                                    <select name="type" class="form-control" v-model="type" required>
                                    <option value="1">One Time Payment</option>
                                    <option value="0">Six Time Payment</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label>Tenure</label>
                                    <select name="tenure" class="form-control" v-model="tenure" required>
                                    <option value="5" :disabled="!select">5 months</option>
                                    <option value="9" :disabled="!select">9 months</option>
                                    <option value="18" :disabled="select">18 months</option>
                                    <option value="36" :disabled="select">36 months</option>
                                    </select>
                                </div>
                                 
                                    
                                    <button type="submit" class="btn btn-primary btn-flat m-b-30 m-t-30">Submit</button>
                                    
                                </form>
                </div>
            </div>
    </div>
</div>
<script>

const tran = new Vue({
    el: '#roll',
    data: function() {
    return {
        type:"",
        select:"",
        tenure:""
        
    }
  }, 
       methods:{
       

        },
        watch:{
            type(n){
                this.select = n == '1' ? true : false;
                this.tenure = "";
            }
        },
        created(){
           
        }
});
</script>
@endsection
