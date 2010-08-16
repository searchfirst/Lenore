<?php if(Configure::read('Product.sales_options')):?>
<div class="item">
<h3>Sales</h3>
<table>
<tr><th>Price</th>
<?php if(!empty($product['Product']['price'])): ?>
<td><?php echo money_format('%.2n',$product['Product']['price']); ?></td>
<?php else: ?>
<td>N/A</td>
<?php endif; ?>
</tr>
<tr>
<th>Options</th>
<td>s </td>
</tr>
</table>
</div>
<?php endif;?>