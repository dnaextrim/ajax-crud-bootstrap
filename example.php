<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<?php //Bootstrap\loadAssetsCSS(); ?>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="assets/css/bootstrap.min.css">

		<!-- Optional theme -->
		<link rel="stylesheet" href="assets/css/bootstrap-theme.min.css">

		<link rel="stylesheet" href="assets/css/font-awesome.min.css"  />
		<link rel="stylesheet" href="assets/css/dataTables.bootstrap.css"  />
		<link rel="stylesheet" href="assets/css/hover-min.css"  />
		<link rel="stylesheet" href="assets/css/select2.min.css"  />
		<link rel="stylesheet" href="assets/css/bootstrap-datepicker3.min.css"  />
		<link rel="stylesheet" href="assets/css/bootstrap-timepicker.min.css"  />
		<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css"  />
		<link rel="stylesheet" href="assets/css/bootstrap-markdown.min.css"  />
		<link rel="stylesheet" href="assets/css/colorpicker.css"  />

	</head>

	<body class="container">


		<div class="row navbar-default">
			<div class="container">
				<h1 data-toggle="collapse" data-parent="#accordion" href="#donation" aria-expanded="true" aria-controls="donation">Donation Please</h1>
				<div id="donation" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="donation">
					<h3>PayPal:<h3>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
						<input type="hidden" name="cmd" value="_donations">
						<input type="hidden" name="business" value="dony_cavalera_md@yahoo.com">
						<input type="hidden" name="lc" value="US">
						<input type="hidden" name="item_name" value="Dony Wahyu Isp">
						<input type="hidden" name="no_note" value="0">
						<input type="hidden" name="currency_code" value="USD">
						<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_LG.gif:NonHostedGuest">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>

					<h3>Bank Mandiri:</h3><strong>113-000-6944-585</strong><br />
					<h4>Atas/Nama:</h4><strong>Dony Wahyu Isprananda</strong>
				</div>
			</div>
		</div>



		<?php include("form.php") ?>



		<!-- Latest compiled and minified JavaScript -->
		<script src="assets/js/jquery.js"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="assets/js/bootstrap.min.js"></script>

		<script type="text/javascript" src="assets/js/bootbox.min.js" ></script>
		<script type="text/javascript" src="assets/js/jquery.dataTables.min.js" ></script>
		<script type="text/javascript" src="assets/js/dataTables.bootstrap.min.js" ></script>
		<script type="text/javascript" src="assets/js/dataTables.responsive.js" ></script>
		<script type="text/javascript" src="assets/js/select2.min.js" ></script>
		<script type="text/javascript" src="assets/js/bootstrap-datepicker.min.js" ></script>
		<script type="text/javascript" src="assets/js/bootstrap-timepicker.min.js" ></script>
		<script type="text/javascript" src="assets/js/moment.min.js" ></script>
		<script type="text/javascript" src="assets/js/bootstrap-datetimepicker.min.js" ></script>
		<script type="text/javascript" src="assets/js/autosize.min.js" ></script>
		<script type="text/javascript" src="assets/js/jquery.maskedinput.js" ></script>
		<script type="text/javascript" src="assets/js/bootstrap-markdown.js" ></script>
		<script type="text/javascript" src="assets/js/bootstrap-colorpicker.js" ></script>
		<script type="text/javascript" src="assets/js/dropzone.js" ></script>

		<script src="assets/js/kecik.js"></script>

		<script type="text/javascript">
			kecik.init({
				'base_url'		: '',
				'insert_url'	: 'insert.php',
				'update_url'	: 'update.php',
				'delete_url'	: 'delete.php',
				'get_url'		: 'get.php',
				'find_url' 		: 'find.php',

				'form_data'		: '#form_data',

				'table'			: '#list',
				'table_column': [
					{"data"	: "username"},
					{"data"	: "fullname"},
					{"data"	: "email"},
					{"data"	: "level"},
				],
				'table_actions': {
			        "view"	: {
			            "action"	: "kecik.View",
			            "desc"		: "View",
			            "icon"		: "fa fa-search-plus",
			            "class"		: "text-primary"
			        },
			        "edit"	: {
			            "action"	: "kecik.Get",
			            "desc"		: "Edit",
			            "icon"		: "fa fa-pencil",
			            "class"		: "text-success"
			        },
			        "delete": {
			            "action"	: "kecik.Delete",
			            "desc"		: "Delete",
			            "icon"		: "fa fa-trash",
			            "class"		: "text-danger"
			        }
			    },
				'table_order': [ [1,"asc"] ],

				'btn_add'	: '#btn_add',
				'btn_save'	: '#btn_save',
				'btn_cancel': '#btn_cancel'
			});
			
			$('.collapse').collapse();
		</script>
	</body>
</html>