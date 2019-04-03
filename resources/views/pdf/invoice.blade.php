<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Invoice</title>
	{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"> --}}
	{{-- <style type="text/css">
	  .container {
	    margin: 10px;
	  }
	  table{
	    width: 100%;
	  }
	  img{
	    float: right;
	  }
	  td {
	      border-bottom: 1px solid black;
	  }
	  span.info {
	      background-color: dimgrey;
	      padding: 2px;
	      color:white;
	  }
	  .head {
	      text-align: center;
	  }
	  p.name {
	    font-size: larger;
	}
	p.foot {
	    background-color: black;
	    color: white;
	    padding: 10px;
	}

</style> --}}
</head>
<body>
	<div class="container">
		<div class="row">
			<div class=>

			<div class="col-6 rounded float-right">
				<img src="{{asset('honeylogo.jpg')}}">
			</div>

			<div class="col-6">
        <p>{{$name}}</p>
        <p>{{$addr}}</p>
        <p>{{$email}}</p>
      </div>

      

      </div>

      <div class="col-12 head">
        <p class="name">Dear {{ucwords(strtolower($name))}},</p>

        <p>We are pleased to inform you that your funds have been placed<br>
         with us and has been booked under the following terms:</p>
      </div>

      <div class="col-12">
        <table class="table">
          <tbody>
            <tr>
              <td id="left">
                <span class="info">DEPOSIT TYPE</span>
              </td>

              <td id="right">
                FIXED DEPOSIT - {{$tenure}} {{$type}}
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">PLACEMENT NUMBER</span>
              </td>
              <td id="right">
                {{$id}}
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">AMOUNT</span>
              </td>
              <td id="right">
                {{$invest_amount}} (NGN)
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">TENOR</span>
              </td>
              <td id="right">
                {{$tenure}} {{$type}}
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">RATE</span>
              </td>
              <td id="right">
                {{$rate}}{{$tenure > 36 ? "": "%"}}
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">COMMENCEMENT DATE</span>
              </td>
              <td id="right">
                {{$invest_date}}
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">MATURITY DATE></span>
              </td>
              <td id="right">
                {{$return_date}}
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">INTEREST</span>
              </td>
              <td id="right">
                {{$interest}} (NGN)
              </td>
            </tr>

            <tr>
              <td id="left">
                <span class="info">VALUE AT MATURITY</span>
              </td>
              <td id="right">
                {{$return_amount}} (NGN)
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="col-12">
        <p>Please note that interest accrued from deposited funds can only apply at the expiration of the tenure plan.Principal and interest is paid back within seven(7) working days of maturity.<br>

        While thanking you for your patronage,we would be delighted to do more business with you in the future.</p>

        <p class="foot">
          Ensure bank details profiled on your account is accurate as payment of principal and interest will be paid to profiled bank account within seven(7) working days of maturity without verifying from you, so keep your client cabinet login details safe.
        </p>

        <p>Regards.</p>
      </div>
    </div>
  </div>
</body>
</html>