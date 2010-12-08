<div>
<h3>Details</h3>
<ul>
<li><b>From:</b> <?php echo empty($message['Message']['email'])?$message['Message']['name']:sprintf('%s &lt;%s&gt;',$message['Message']['name'],$message['Message']['email']) ?></li>
<?php foreach($message['Message']['additional_parameters'] as $param=>$detail): ?>
<li><b><?php echo Inflector::humanize($param) ?>: </b> <?php echo $detail ?></li>
<?php endforeach ?>
</ul>
</div>