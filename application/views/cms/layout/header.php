<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
	<meta http-equiv="Content-Type" content="<?php echo $RESOURCE_THEME_HTMLTYPE?>; charset=<?php echo $RESOURCE_THEME_CHARSET?>" />
	<title><?php echo $engine_show_header_website.'::'.$engine_show_header_pagename; ?></title>
	<!-- FRAMEWORK STYLESHEETS -->
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo base_url('library/cms/css/bootstrap.min.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('library/cms/css/bootstrap-responsive.min.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('library/cms/css/bootstrap-responsive-fluid.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('library/cms/css/menu.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('library/cms/css/preview.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url('library/cms/css/prometheus.css'); ?>" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('library/cms/css/prettify.css')?>"></link>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('library/cms/src/bootstrap-wysihtml5.css')?>"></link>
	<link rel="stylesheet" href="<?php echo base_url('library/cms/js/fileUploader/css/jquery.fileupload-ui.css')?>" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('library/cms/css/prettify.css')?>"></link>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('library/cms/src/bootstrap-wysihtml5.css')?>"></link>
	    
    <!-- FRAMEWORK JAVASCRIPT -->
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" ></script>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" ></script>
	<script type="text/javascript">var base_url = '<?php echo base_url(); ?>';</script>
    <script src="<?php echo base_url('library/cms/js/modernizr.js')?>"></script>
    <script src="<?php echo base_url('library/cms/js/bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('library/cms/js/excanvas.min.js')?>"></script>
    <script src="<?php echo base_url('library/cms/js/menu.js')?>"></script>
    <script src="<?php echo base_url('library/cms/js/wysihtml5-0.3.0.js')?>"></script>
	<script src="<?php echo base_url('library/cms/js/jquery-1.7.2.min.js')?>"></script>
	<script src="<?php echo base_url('library/cms/js/prettify.js')?>"></script>
	<script src="<?php echo base_url('library/cms/src/bootstrap-wysihtml5.js')?>"></script>	
	<script src="<?php echo base_url('library/cms/js/fileUploader/js/vendor/jquery.ui.widget.js')?>"></script>
	<script src="<?php echo base_url('library/cms/js/fileUploader/js/jquery.iframe-transport.js')?>"></script>
	<script src="<?php echo base_url('library/cms/js/fileUploader/js/jquery.fileupload.js')?>"></script>
	<script src="<?php echo base_url('library/cms/js/uploader.js')?>"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>

	<script>
	$(function(){
		$('.textarea').wysihtml5();
		$(prettyPrint);
		
		$(".alert").alert()
	});
	</script>

</head>

<!-- BODY BEGGINS -->	
<body>
	
<!-- ========================
            Header
============================= -->
<header>

    <!-- navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <div class="container">

                <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <!-- Be sure to leave the brand out there if you want it shown -->
                <!-- logo -->
                <a class="logo" href="<?php echo $this->config->site_url('cms')?>"><img src="<?php echo base_url('library/cms/img/caja_verde.png')?>" alt="CajaVerde" /></a>

                <!-- breadcrumbs -->
                <?php 
                if($this->session->userdata('logged_in')):
                	echo set_breadcrumb();
				endif;
				?>
				
                <!-- profile bar -->
                <?php if($this->session->userdata('logged_in')):?>
                <ul class="nav pull-right">
                    <li class="profile">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo $this->session->userdata('USERNAME')?><span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a tabindex="-1" href="<?php echo $this->config->site_url('cms/panel_perfil/')?>">Editar Perfil</a></li>
                            <?php if($this->session->userdata('ACCESS_LEVEL') > 999):?>
                            <li><a tabindex="-1" href="<?php echo $this->config->site_url('cms/panel_usuarios/')?>">Administrar Usuarios</a></li>
                            <?php endif?>
                            <li class="divider"></li>
                            <li><a tabindex="-1" href="<?php echo $this->config->site_url("cms/panel_login/logoff")?>">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
				<?php endif;?>
            </div>
        </div>
    </div>

</header>

<?php echo $this->fw_alerts->display_alert_message();?>

<!-- ========================
            Sidebar
============================= -->
<aside class="visible-desktop">
	<?php if($this->session->userdata('logged_in')):?>
    <!-- search -->
    <form class="form-search">
        <div class="input-prepend">
        	<!--
            <button type="submit" class="btn"></button>
            <input type="text" class="search-query"> -->
        </div>
    </form>

    <!-- menu -->
    <ul class="sideMenu">
    	<?php foreach($CMS_PAGES_LIST as $btn):?>
    	<?php $active = ($btn->PATH === $engine_show_header_pagename)? 'class="active"':'';?>
    	<?php $href = (!empty($btn->CHILDS))?'javascript:void(0);':$this->config->site_url("cms/".strtolower($btn->PATH))?>
        <li <?=$active?>>
            <a href="<?=$href?>"><?=$btn->LABEL?></a>
            <?php if (count($btn->CHILDS) > 0):?>
            <span><?=count($btn->CHILDS)?></span>
            <ul>
            	<?php foreach($btn->CHILDS as $btnchild):?>
                <li><a href="<?=$this->config->site_url("cms/".strtolower($btnchild->PATH))?>"><?=$btnchild->LABEL?></a></li>
                <?php endforeach?>
            </ul>
            <?php endif;?>
        </li>
        <?php endforeach;?>
    </ul>
	<?php endif;?>
</aside>