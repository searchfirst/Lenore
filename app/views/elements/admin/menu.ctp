<nav id="menu">
<ul>
<li><?php echo $html->link('Dashboard',array('admin'=>true,'action'=>'index','controller'=>''))?></li><?php if(is_array(Configure::read('Admin.active_modules'))): ?>
<?php foreach(Configure::read('Admin.active_modules') as $mod_title=>$module): ?><li><?php echo $html->link(Inflector::pluralize($module['alias']),array('admin'=>true,'controller'=>Inflector::tableize($mod_title),'action'=>'index')) ?></li><?php endforeach; ?>
<?php endif; ?><li><a href="http://moonlight-project.co.uk/help">Help</a></li>
</ul>
</nav>