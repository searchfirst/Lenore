<?php
class MarkdownShell extends Shell {   
	var $uses = array('Product','Category','Section','Article');
	function main() {}

	function tohtml() {
		App::import('Vendor','markdown');
		if (!empty($this->args[0])) {
			$model = $this->args[0];
			$this->out(sprintf('Converting Markdown to HTML on %s',$model));
			$items = $this->{$model}->find('all',array('fields' => array('id','description')));
			foreach ($items as $item) {
				$item[$model]['description'] = Markdown($item[$model]['description']);
				if ($this->{$model}->save($item)) {
					$this->out(sprintf('Saved %s id: %s',$model,$item[$model]['id']));
				} else {
					$this->out(sprintf('Failed Saving %s id: %s',$model,$item[$model]['id']));
				}
			}
		}
	}
}
