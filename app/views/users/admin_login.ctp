<h2>Login</h2>
<div class="content">
<?php echo $form->create('User', array('url'=>array('admin'=>true,'controller'=>'users','action'=>'login')));
echo $form->input('User.username');
echo $form->input('User.password');
echo $form->end('Login');?>
</div>