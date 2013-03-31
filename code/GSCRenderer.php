<?php

class GSCRenderer{
	
	static $defaultdelimiter = "<!-- GSCdelimiter -->";
	
	protected $grid,$content,$template, $delimiter;
	
	function __construct($grid, $content, $template = "GSCRow", $delimiter = null){
		$this->grid = $grid;
		$this->content = $content;
		$this->template = $template;
		$this->delimiter = ($delimiter) ? $delimiter : self::$defaultdelimiter;
	}
	
	function render(){
		$splitcontent = $this->getSplitContent();
		$maincolumn = json_decode($this->grid);
		if($maincolumn === null){
			user_error("malformed grid JSON given");
		}
		return $this->renderRows($maincolumn->rows,new ArrayIterator($splitcontent));
	}
	
	protected function renderRows($rows, ArrayIterator $splitcontent, $pos = -1){
		$output = "";
		foreach($rows as $row){
			if($row->cols){
				$columns = array();
				foreach($row->cols as $col){
					$nextcontent = $splitcontent->current();
					$isholder = !isset($col->rows);
					if($isholder){
						$splitcontent->next(); //advance iterator if there are no sub-rows
						$pos++;
					}
					$width = $col->width ? (int)$col->width : 1; //width is at least 1
					$columns[] = new ArrayData(array(
						"Width" => $width,
						"EnglishWidth" => $this->englishWidth($width),
						"Content" => $isholder ? $nextcontent : $this->renderRows($col->rows, $splitcontent, $pos),
						"IsHolder" => $isholder,
						"GridPos" => $pos
					));
				}
				$output .= ArrayData::create(array(
									"Columns" => new ArrayList($columns)
								))->renderWith($this->template);
			}else{
				//every row should have columns!!
			}
		}
		return $output;
	}
	
	protected function getSplitContent(){
		$splitcontent = explode($this->delimiter, $this->content);
		foreach($splitcontent as $key => $content){
			$splitcontent[$key] = $content;
		}
		return $splitcontent;
	}
	
	protected function englishWidth($width){
		$widths = array(
			"zero","one","two","three","four","five","six","seven","eight","nine","ten",
			"eleven","twelve","thirteen","fourteen"
		);
		return $widths[$width];
	}
	
}