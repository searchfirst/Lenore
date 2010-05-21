<h2>Search Results</h2>
<p>Search Results for <?php echo $search_query;?></p>
<?php if (!empty($results)): ?>
<ul>
<?php foreach ($results as $x => $result): ?>
<li><?php echo $textAssistant->link($result['Product']['title'],"/products/{$result['Category']['slug']}/{$result['Product']['slug']}") ?> in <?php echo $result['Category']['title'] ?></li>
<?php endforeach ?>
</ul>
<?php else: ?>
<p>0 results found.</p>
<?php endif ?>
