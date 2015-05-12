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
				'insert_url'	: 'insert_model.php',
				'update_url'	: 'update_model.php',
				'delete_url'	: 'delete_model.php',
				'get_url'		: 'get_model.php',
				'find_url' 		: 'find_model.php',

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
		</script>
	</body>
</html>