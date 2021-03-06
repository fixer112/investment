<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Honeypays | Register</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
<!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
<!--===============================================================================================-->

</head>
<body>
    
    <div class="limiter">
        <div class="container-login100">

            <div class="wrap-login100">
           
            
                <form class="login100-form validate-form" method="POST" action="/register" files="true" enctype="multipart/form-data">
                @csrf
                
                    <span class="login100-form-title p-b-26">
                        <img src="{{ asset('honeylogo.jpg') }}">
                    </span>

                    <span class="login100-form-title p-b-48">
                        Join Us
                    </span>
                    <div>
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
                    <div class="wrap-input100 validate-input" data-validate = "name">
                        <input class="input100" type="text" name="name" value="{{ old('name') }}">
                        <span class="focus-input100" data-placeholder="*Full name"></span>
                        @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                    </div>


                    <div class="wrap-input100 validate-input" data-validate = "email">
                        <input class="input100" type="email" name="email" value="{{ old('email') }}" required>
                        <span class="focus-input100" data-placeholder="*Email"></span>
                        @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="password" required>
                        <span class="focus-input100" data-placeholder="*Password"></span>
                        @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        
                    </div>

                    <div class="wrap-input100 validate-input" data-validate="Enter password">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="password_confirmation" required>
                        <span class="focus-input100" data-placeholder="*Confirm Password"></span>
                        @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                        
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "number">
                        <input class="input100" type="text" name="number" value="{{ old('number') }}" placeholder="080xxxxxxxx" required>
                        <span class="focus-input100" data-placeholder="*Mobile Number"></span>
                        @if ($errors->has('number'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('number') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "acc_no">
                        <input class="input100" type="text" name="acc_no" value="{{ old('acc_no') }}" required>
                        <span class="focus-input100" data-placeholder="*Account Number"></span>
                        @if ($errors->has('acc_no'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('acc_no') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "acc_name">
                        <input class="input100" type="text" name="acc_name" value="{{ old('acc_name') }}" required>
                        <span class="focus-input100" data-placeholder="*Account Name"></span>
                        @if ($errors->has('acc_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('acc_name') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "bank_name">
                        <select class="input100" type="text" name="bank_name" value="{{ old('bank_name') }}" required>
                        <option value="Access Bank" >Access Bank</option><option value="Citibank" >Citibank</option><option value="Diamond Bank" >Diamond Bank</option><option value="Ecobank Nigeria" >Ecobank Nigeria</option><option value="Fidelity Bank Nigeria" >Fidelity Bank Nigeria</option><option value="First Bank of Nigeria" >First Bank of Nigeria</option><option value="First City Monument Bank" >First City Monument Bank</option><option value="Guaranty Trust Bank" >Guaranty Trust Bank</option><option value="Heritage Bank plc" >Heritage Bank plc</option><option value="Keystone Bank Limited" >Keystone Bank Limited</option><option value="Providus Bank Plc" >Providus Bank Plc</option><option value="Skye Bank" >Skye Bank</option><option value="Stanbic IBTC Bank Nigeria Limited" >Stanbic IBTC Bank Nigeria Limited</option><option value="Standard Chartered Bank" >Standard Chartered Bank</option><option value="Sterling Bank" >Sterling Bank</option><option value="Suntrust Bank Nigeria Limited" >Suntrust Bank Nigeria Limited</option><option value="Union Bank of Nigeria" >Union Bank of Nigeria</option><option value="United Bank for Africa" >United Bank for Africa</option><option value="Unity Bank plc" >Unity Bank plc</option><option value="Wema Bank" >Wema Bank</option><option value="Zenith Bank" >Zenith Bank</option>
                        </select>
                        <span class="focus-input100" data-placeholder="*Bank Name"></span>
                        @if ($errors->has('bank_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('bank_name') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "addr">
                        <input class="input100" type="text" name="addr" value="{{ old('addr') }}" required>
                        <span class="focus-input100" data-placeholder="*Address"></span>
                        @if ($errors->has('addr'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('addr') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "city">
                        <input class="input100" type="text" name="city" value="{{ old('city') }}" required>
                        <span class="focus-input100" data-placeholder="*City"></span>
                        @if ($errors->has('city'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                    </div>


                    <div class="wrap-input100 validate-input" data-validate = "state">
                        <input class="input100" type="text" name="state" value="{{ old('state') }}" required>
                        <span class="focus-input100" data-placeholder="*State"></span>
                        @if ($errors->has('state'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "mentor">
                        <input class="input100" type="text" name="mentor" value="{{ old('mentor') }}" placeholder="e.g National id card" required>
                        <span class="focus-input100" data-placeholder="*Account Manager Number"></span>
                        @if ($errors->has('mentor'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mentor') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "mode_id">
                        <select class="input100" type="text" name="mode_id" value="{{ old('mode_id') }}" required>
                        <option value="INTERNATIONAL PASSPORT" >INTERNATIONAL PASSPORT</option><option value="DRIVERS LICENSE" >DRIVERS LICENSE</option><option value="NATIONAL ID" >NATIONAL ID</option><option value="VOTERS ID" >VOTERS ID</option><option value="BVN PRINTOUT" >BVN PRINTOUT</option>
                        </select>
                        <span class="focus-input100" data-placeholder="*Mode of Identity"></span>
                        @if ($errors->has('mode_id'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('mode_id') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "identity">
                        <input class="input100" type="file" name="identity" accept="image/*" value="{{ old('identity') }}">
                        <span class="focus-input100" data-placeholder="Upload Identity"></span>
                        @if ($errors->has('identity'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('identity') }}</strong>
                                    </span>
                                @endif
                    </div>

                    <div class="wrap-input100 validate-input" data-validate = "accept">
                        <input class="input100" type="checkbox" name="accept" value="1">
                        <span class="focus-input100" data-placeholder="*Agreement"></span>
                        <p>I have read and understood the agreement and hereby consent to it. I also understand that 20% will be deducted from my total earning as IRM (Investment and Risk Management) fee.</p>

                        <p><b>Follow link to read agreement</b> <i><a href="https://honeypays.com.ng/legal">here</a></i></p>
                        @if ($errors->has('accept'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('accept') }}</strong>
                                    </span>
                                @endif
                    </div>


                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button class="login100-form-btn">
                                Register
                            </button>
                        </div>
                    </div>

                    <div class="text-center p-t-115">
                        <span class="txt1">
                            Already have an account?
                        </span>

                        <a class="txt2" href="/login">
                            Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    

    <div id="dropDownSelect1"></div>
    

<!--===============================================================================================-->
    <script src="{{ asset('js/lib/jquery/jquery-3.2.1.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('js/lib/bootstrap/js/bootstrap.min.js') }}"></script>
<!--===============================================================================================-->
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="https://code.tidio.co/myuqp2mwyctv2bsno70lihphmhxi3afo.js"></script>

</body>
</html>