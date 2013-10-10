
<!-- ========================
            Content
============================= -->
<div id="content" class="container-fluid">
	<div class="page-header">
		<h1>Mi Perfil <small>Datos generales</small></h1>
	</div>
	<div class="row-fluid">
		<form class="form-horizontal" action="<?php echo $this->config->site_url().'/cms/panel_perfil/editar'?>" method="POST" name="login_form" id="login_form">
			<div class="control-group">
				<label class="control-label" for="inputEmail">Email</label>
				<div class="controls">
					<input type="text" id="inputEmail" placeholder="Correo electrónico" name="EMAIL" value="<?php echo $user_email?>"/>
				</div>
			</div>			<div class="control-group">
				<label class="control-label" for="inputEmail">Usuario</label>
				<div class="controls">
					<input type="text" id="inputUser" placeholder="Usuario" name="USERNAME" value="<?php echo $user_name?>"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputEmail">Contrase&ntilde;a</label>
				<div class="controls">
					<input type="password" id="inputPassword" placeholder="Contrase&ntilde;a" name="PASSWORD" />
				</div>
			</div>
    		<div class="form-actions">
    			<input type="submit" class="btn btn-primary" value="Editar Información" />
    			<a href="<?php echo $this->config->site_url('cms')?>" class="btn">Cancelar</a>
    		</div>
    	</form>
	</div>
</div>