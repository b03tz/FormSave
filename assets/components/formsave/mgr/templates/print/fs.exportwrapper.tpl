<!DOCTYPE>
<html>
	<head>
		<title>[[%formsave.form]] [[+formData.topic]]</title>
		<style>
			body {
				font-family: Verdana;
			}
			
			table {
				border-collapse: collapse
			}
			
			table td {
				font-size: 11px;
				padding: 5px;
			}
			
			.label {
				font-weight: bold;
				width: 150px;
				border-bottom: 1px solid #CCC;
			}
			
			.value {
				border-bottom: 1px solid #CCC;
			}
		</style>
	</head>
	<body>
		<h2>[[%formsave.form]] [[+formData.topic:ucfirst]]</h2>
		<table>
[[+content]]
		</table>
	</body>
</html>
