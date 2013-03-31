<?php
class GSCSiteTree extends DataExtension{
	
	protected $splitcontent = null;
	
	public static $db = array(
		"GridLayout" => "Text"
	);
	
	public static $casting = array(
		"GridContent" => "HTMLText", //prevents save error
	);
	
	function updateCMSFields(FieldList $fields){
		$fields->addFieldToTab("Root.Grid", TextareaField::create("GridLayout"));
		
		if($this->owner->GridLayout){
			$fields->addFieldToTab("Root.Main",GridSelectionField::create("GridPos","Content Position",$this->owner->GridLayout),"Content");
			$fields->addFieldToTab("Root.Main",
				HtmlEditorField::create("GridContent","Content")->setAttribute("data-gscdelimiter",GSCRenderer::$defaultdelimiter)
			,"Content");
			$fields->addFieldToTab("Root.Main",HiddenField::create("Content"));
			Requirements::javascript("gridstructuredcontent/javascript/gridedit.js");
		}
	}
	
	function GridContent($pos = null){
		$renderer = new GSCRenderer($this->owner->GridLayout, $this->owner->Content);
		return $renderer->render();
	}
	
}