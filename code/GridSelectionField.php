<?php

/**
 * Field for picking which grid section to edit.
 * 
 * Displays a grid, with selectable sections.
 */
class GridSelectionField extends FormField{
	
	protected $template = "GridSelectionField";
	
	function FieldHolder($properties = array()){
		Requirements::css(GSC_DIR."/css/gridselectionfield.css");
		return parent::FieldHolder($properties);
	}
	
	function RenderGrid(){
		$renderer = new GSCRenderer($this->value, "","GridSelectionFieldRow");
		return $renderer->render();
	}
	
	public function performReadonlyTransformation() {
		$copy = $this->castedCopy('HiddenField');
		$copy->setReadonly(true);
		return $copy;
	}

}