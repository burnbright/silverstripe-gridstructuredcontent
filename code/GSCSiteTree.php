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
		
		//TODO: introduce grid box selector
		$values = array(1,2,3,4,5,6,7);
		$fields->addFieldToTab("Root.Main",DropdownField::create("GridPos","Position",$values),"Content");
		$fields->addFieldToTab("Root.Main",
			HtmlEditorField::create("GridContent")->setAttribute("data-gscdelimiter",GSCRenderer::$defaultdelimiter)
		,"Content");
		$fields->addFieldToTab("Root.Main",HiddenField::create("Content"));
		Requirements::javascript("gridstructuredcontent/javascript/gridedit.js");
	}
	
	function GridContent($pos = null){
		$renderer = new GSCRenderer($this->owner->GridLayout, $this->owner->Content);
		return $renderer->render();
	}
	
}