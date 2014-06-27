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
		if (class_exists('CodeEditorField')) {
			$editorfield = CodeEditorField::create("GridLayout");
			$editorfield->setMode('json');
		} else {
			$editorfield = TextareaField::create("GridLayout");
		}
		if (!$this->owner->GridLayout) {
			$editorfield->setDescription('Use JSON to create a grid layout. Valid keys are : <strong>rows</strong>, <strong>cols</strong>, <strong>extraclass</strong> and <strong>width</strong>. An extra class can be applied
to a row or a col.
<br/><br/>
Use the example below to get you started.
<br/><br/>
<pre>
{
	"rows":[
		{
		"cols":[
			{"width":4},
			{"width":4},
			{"width":4}
		]}
	]
}
</pre>
');
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

	/**
	 * @link https://github.com/GerHobbelt/nicejson-php/blob/master/nicejson.php
	 * @param string $json
	 * @return string
	 */
	static function json_format($json) {
		if (!is_string($json)) {
			if (phpversion() && phpversion() >= 5.4) {
				return json_encode($json, JSON_PRETTY_PRINT);
			}
			$json = json_encode($json);
		}
		$result = '';
		$pos = 0;   // indentation level
		$strLen = strlen($json);
		$indentStr = "\t";
		$newLine = "\n";
		$prevChar = '';
		$outOfQuotes = true;

		for ($i = 0; $i < $strLen; $i++) {
			// Grab the next character in the string
			$char = substr($json, $i, 1);

			// Are we inside a quoted string?
			if ($char == '"' && $prevChar != '\\') {
				$outOfQuotes = !$outOfQuotes;
			}
			// If this character is the end of an element,
			// output a new line and indent the next line
			else if (($char == '}' || $char == ']') && $outOfQuotes) {
				$result .= $newLine;
				$pos--;
				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}
			// eat all non-essential whitespace in the input as we do our own here and it would only mess up our process
			else if ($outOfQuotes && false !== strpos(" \t\r\n", $char)) {
				continue;
			}

			// Add the character to the result string
			$result .= $char;
			// always add a space after a field colon:
			if ($char == ':' && $outOfQuotes) {
				$result .= ' ';
			}

			// If the last character was the beginning of an element,
			// output a new line and indent the next line
			if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
				$result .= $newLine;
				if ($char == '{' || $char == '[') {
					$pos++;
				}
				for ($j = 0; $j < $pos; $j++) {
					$result .= $indentStr;
				}
			}
			$prevChar = $char;
		}

		return $result;
	}

	function onBeforeWrite() {
		parent::onBeforeWrite();

		if ($this->owner->GridLayout) {
			$this->owner->GridLayout = self::json_format($this->owner->GridLayout);
		}
	}

}
