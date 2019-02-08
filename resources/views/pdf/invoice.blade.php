<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Invoice</title>
	{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"> --}}
	<style type="text/css">
	/*.container {
    margin: 60px;
	}*/
	table{
		width: 800px;
	}
	/*#right{
		text-align: right;
	}
	#left{
		text-align: left;
	}*/
	/*.terms {
	    width: 100%;
	}*/

</style>
</head>
<body>
	<div class="container">
		<div class="row">
			<div>

			<div class="info col-6 mt-5">
				<p>{{isset($name) ? strtoupper($name) : ''}}</p>
				<p>{{ isset($addr) ? strtoupper($addr) : ''}}</p>
			</div>

			<div class="img col-6 mt-5 rounded float-right">
				<img src="{{asset('honeylogo.jpg')}}">
			</div>

			</div>

			<div class="msg col-12 mt-5">
				<p>Dear Wale Ajayi,</p>

				<p>We are pleased to inform you that your funds have been placed with us and has been booked under the following terms:</p>
			</div>

			<div class="terms col-12 mt-2">
				<table class="table">
					<tbody>
						<tr>
							<td id="left">
								DEPOSIT TYPE
							</td>

							<td id="right">
								FIXED DEPOSIT - number of days
							</td>
						</tr>

						<tr>
							<td id="left">
								PLACEMENT NUMBER
							</td>
							<td id="right">
								1233455666676
							</td>
						</tr>

						<tr>
							<td id="left">
								AMOUNT
							</td>
							<td id="right">
								N4,500,000.00 (NGN)
							</td>
						</tr>

						<tr>
							<td id="left">
								TENOR
							</td>
							<td id="right">
								120 DAYS
							</td>
						</tr>

						<tr>
							<td id="left">
								RATE
							</td>
							<td id="right">
								7.00%
							</td>
						</tr>

						<tr>
							<td id="left">
								COMMENCEMENT DATE
							</td>
							<td id="right">
								29-JUN-!6
							</td>
						</tr>

						<tr>
							<td id="left">
								MATURITY DATE
							</td>
							<td id="right">
								29-JUN-16
							</td>
						</tr>

						<tr>
							<td id="left">
								INTEREST
							</td>
							<td id="right">
								N9000(NGN)
							</td>
						</tr>

						<tr>
							<td id="left">
								VALUE AT MATURITY
							</td>
							<td id="right">
								Amount + Interest (NGN)
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="footer col-12 mt-5">
				<p>Please note that interest accrued from deposited funds can only apply at the expiration of the tenure plan.Principal and interest is paid back within seven(7) working days of maturity.<br>

				While thanking you for your patronage,we would be delighted to do more business with you in the future.</p>

				<p>Regards.</p>
			</div>
		</div>
	</div>
</body>
</html>