<?php

class GSCSiteTree extends DataExtension {

	protected $splitcontent = null;
	public static $db = array(
		"GridLayout" => "Text"
	);
	public static $casting = array(
		"GridContent" => "HTMLText", //prevents save error
	);

	public function updateSettingsFields(FieldList $fields) {
		if(class_exists('CodeEditorField')) {
			$editorfield = CodeEditorField::create("GridLayout");
			$editorfield->setMode('json');
		}
		else {
			$editorfield = TextareaField::create("GridLayout");
		}
		$fields->addFieldToTab("Root.Grid", $editorfield);
		return $fields;
	}

	function updateCMSFields(FieldList $fields) {
		if ($this->owner->GridLayout) {
			$fields->addFieldToTab("Root.Main", GridSelectionField::create("GridPos", "Content Position", $this->owner->GridLayout)
					, "Content");
			$fields->addFieldToTab("Root.Main", HtmlEditorField::create("GridContent", "Content")->setAttribute("data-gscdelimiter", GSCRenderer::$defaultdelimiter)
					, "Content");
			$fields->addFieldToTab("Root.Main", GSCHiddenContentField::create("Content"), "GridContent");
			Requirements::javascript("gridstructuredcontent/javascript/gridedit.js");
		}
	}

	function GridContent($pos = null) {
		$renderer = new GSCRenderer($this->owner->GridLayout, $this->owner->Content);
		return $renderer->render();
	}

	function contentcontrollerInit() {
		$this->owner->Content = $this->GridContent();
	}

}
