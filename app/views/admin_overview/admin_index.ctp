<h2>Dashboard: <?php echo vsprintf('%s (%s)',array(Configure::read('Moonlight.website_name'),$_SERVER['HTTP_HOST'])) ?></h2>
<div class="dashboard">
<div class="primary">
<h3>Manage This Site</h3>
<?php if(!empty($site_modules)):?>
<ul class="grid">
<?php foreach($site_modules as $x=>$module):?>
<li><a href="<?php echo $html->url(array('controller'=>Inflector::tableize($x),'action'=>'index','admin'=>true)) ?>">
<img src="<?php echo $module['dashboard_icon'] ?>"> <?php echo Inflector::pluralize($module['alias']);?>
</a></li>
<?php endforeach;?>
</ul>
<?php else:?>
<p class="warning error">This site has no manageable modules.</p>
<?php endif;?>
</div>
<div class="secondary">
<h3>Inbox</h3>
<h3>Cart</h3>
</div>
</div>
