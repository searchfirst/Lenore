<script src="//ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.8.11/jquery-ui.min.js"></script>
<?php echo $this->Minify->js_link($pub_minify_js['core']); ?> 
<!--[if lt IE 9 ]><?php echo $this->Minify->js_link($pub_minify_js['ltie9']); ?><![endif]-->
