<?php
class GSCSiteTree extends DataExtension{
	
	static $delimiter = "<p>[SPLIT]</p>";
	protected $splitcontent = null;
	
	public static $casting = array(
		"GridContent" => "HTMLText" //prevents save error
	);
	
	function updateCMSFields(FieldList $fields){
		//TODO: introduce grid box selector
		$values = array(1,2,3,4,5,6,7);
		$fields->addFieldToTab("Root.Main",DropdownField::create("GridPos","Position",$values),"Content");
		
		$fields->addFieldToTab("Root.Main",HtmlEditorField::create("GridContent"),"Content");
		$fields->addFieldToTab("Root.Main",HiddenField::create("Content"));
		Requirements::javascript("gridstructuredcontent/javascript/gridedit.js");
	}
	
	function GridContent($pos = null){
		if($pos === null){
			return $this->RenderGridContent();
		}
		$splitcontent = $this->getSplitContent();
		if(isset($splitcontent[$pos])){
			return $splitcontent[$pos];
		}
		return null;		
	}
	
	protected function RenderGridContent(){
		$splitcontent = $this->getSplitContent();
		$output = "";
		//drop split content into appropriate grid parts
		$currentrow = array();
		foreach($splitcontent as $key => $content){
			$width = 4; //magic number
			$currentrow[] = new ArrayData(array(
				"Width" => $width,
				"EnglishWidth" => $this->englishWidth($width),
				"Offset" => 0, //magic number
				"Content" => $content	
			));
			
			if(count($currentrow) == 3 || $key == count($splitcontent) - 1){ //magic number
				$row = new ArrayData(array(
					"Columns" => new ArrayList($currentrow)
				));
				$output .= $row->renderWith("FoundryGridRow"); //magic
				$currentrow = array();
			}
		}
		return $output;
	}
	
	protected function getSplitContent(){
		if(!$this->splitcontent){
			$this->splitcontent = explode(self::$delimiter,$this->owner->Content);
			foreach($this->splitcontent as $key => $content){
				$this->splitcontent[$key] = DBField::create_field("HTMLText",$content);
			}
		}
		return $this->splitcontent;
	}
	
	function englishWidth($width){
		$widths = array(
			"zero",
			"one",
			"two",
			"three",
			"four",
			"five",
			"six",
			"seven",
			"eight",
			"nine",
			"ten",
			"eleven",
			"twelve",
			"thirteen",
			"fourteen"
		);
		return $widths[$width];
	}
	
}