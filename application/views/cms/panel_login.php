
<!-- ========================
            Content
============================= -->
<div id="content" class="container-fluid">
	<div class="page-header">
		<h1>Inicio de sesión <small>Login al sistema.</small></h1>
	</div>
	<div class="row-fluid">
		<form class="form-horizontal" action="<?php echo $this->config->site_url().'/cms/panel_login/session'?>" method="POST" name="login_form" id="login_form">
			<div class="control-group">
				<label class="control-label" for="inputEmail">Usuario</label>
				<div class="controls">
					<input type="text" id="inputUser" placeholder="Usuario" name="login_username"/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="inputEmail">Contrase&ntilde;a</label>
				<div class="controls">
					<input type="password" id="inputPassword" placeholder="Contrase&ntilde;a" name="login_password" />
				</div>
			</div>
    		<div class="control-group">
    			<div class="controls">
    			<button type="submit" class="btn btn-primary">Ingresa</button>
    			</div>
    		</div>
    	</form>
	</div>
</div>