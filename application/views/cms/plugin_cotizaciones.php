<div id="content" class="container-fluid">
	<div class="page-header">
		<h1> <?php echo $page_title; ?><small></small></h1>
	</div>
	<?php if(!empty($create_new_row)):?>
	<div class="row-fluid">
		<div class="span12 well">
			<div class="row-fluid">
				<div class="span4">
					<h3>Pais</h3>
					<?php
						
						$js = 'onchange="listfilter_function() " id="LIST_FILTER"';
						echo form_dropdown('FILTROPAISES', $paisesoptions,$currentPaises,$js);?>
						
				</div>
				<div class="span4">
					<h3>Mes</h3>
					<?php
						
						$jsm = 'onchange="listfilter_function_m() " id="LIST_FILTER_M"';
						echo form_dropdown('FILTROMES', $mesoptions,$currentMes,$jsm);?>
						
				</div>
				<div class="span4">
					<h3>A&ntilde;o</h3>
					<?php
						
						$jsa = 'onchange="listfilter_function_a() " id="LIST_FILTER_A"';
						echo form_dropdown('FILTROANIO', $aniooptions,$currentAnio,$jsa);?>
						
				</div>
			</div>
			<div class="row-fluid">
				<div class="span7"> &nbsp;</div>
				<div class="span5">
					<?php echo $pagination;?>
				</div>
			</div>
		</div>
	</div>
	<?php endif?>
	<div class="row-fluid">
		<div class="span12">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<?php foreach($header as $i => $th):?>
						<?php if($i > 0):?>
						<th><?=$th?></th>
						<?php endif; endforeach?>
					</tr>
				</thead>
				<tbody>
					<?php echo $body?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	function listfilter_function(){
		var filter = $('select#LIST_FILTER').val();		
		location.href = '<?php echo $this->config->site_url('cms/'.strtolower($this->current_plugin).'/index')?>/'+filter+'/<?php echo $this->uri->segment(6)?>';
	}
	function listfilter_function_m(){
		var filter_m = $('select#LIST_FILTER_M').val();
		<?php $pais = ($this->uri->segment(4) == '')?'GUATEMALA':$this->uri->segment(4);?>	
		location.href = '<?php echo $this->config->site_url('cms/'.strtolower($this->current_plugin).'/index')?>/<?php echo $pais?>/'+filter_m;
	}
	function listfilter_function_a(){
		var filter_a = $('select#LIST_FILTER_A').val();
		<?php $mes = ($this->uri->segment(5) == '')?date('m'):$this->uri->segment(5);?>	
		location.href = '<?php echo $this->config->site_url('cms/'.strtolower($this->current_plugin).'/index')?>/<?php echo $pais?>/<?php echo $mes?>/'+filter_a;
	}
</script>
