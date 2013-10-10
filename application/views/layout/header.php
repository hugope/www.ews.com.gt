<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
	<meta content="text/html; charset=ISO-8859-1" http-equiv="Content-Type">
	<title>FRAMEWORK PERINOLA</title>
	<?php foreach($external_files as $external_file) echo $external_file?>
</head>

<!-- BODY BEGGINS -->	
<body>
	<!-- ===== EXAMPLE HEADER ===== -->
	<div style="float:left; background:#EFEFEF; border-bottom: 1px solid #CDCDCD; width:100%; line-height:75px; height:75px; color:#999999; font-size:12px;">
	<?=display_img("logo.gif", array('alt' => "Grupo Perinola"))?> <p>FRONT-END Framework Perinola <a href="./cms">Ir al CMS</a></p></div>
	<?php echo $this->fw_alerts->display_alert_message();?>