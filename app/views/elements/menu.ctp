<nav id="menu">
<?php echo $menu->makeMenu(array_merge($menu_prefix,$moonlight_website_menu,$menu_suffix),array('omissions'=>$menu_omissions)) ?> 
</nav>
<?php if(!empty($moonlight_product_list) && is_array($moonlight_product_list)):?>
<nav id="product_menu">
<?php echo $menu->makeMenu($moonlight_product_list,array('slug_prefix'=>'products'));?> 
</nav>
<?php endif;?>
</div>