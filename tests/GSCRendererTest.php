<?php

class GSCRendererTest extends SapphireTest
{
    
    public function setUp()
    {
        $this->delimiter = $d = "<!-- DELIMITER -->";
        $this->content = "section1{$d}section2{$d}section3{$d}section4";//sample delimited content
    }
    
    public function testBasicGrid()
    {
        $grid = file_get_contents(GSC_PATH."/tests/fixtures/gridbasic.json");
        $renderer = new GSCRenderer($grid, $this->content, "JSONGridRow", $this->delimiter);
        $output = $renderer->render();
        $expectedoutput =<<<OUT
{"cols":[{"width":1,"content":"section1"},{"width":2,"content":"section2"},{"width":3,"content":"section3"}]}
OUT;
        $this->assertEquals($output, $expectedoutput);
    }
    
    public function testNestedGrid()
    {
        $grid = file_get_contents(GSC_PATH."/tests/fixtures/gridnested.json");
        $renderer = new GSCRenderer($grid, $this->content, "JSONGridRow", $this->delimiter);
        $output = $renderer->render();
        $expectedoutput =<<<OUT
{"cols":[{"width":2,"content":"section1"},{"width":4,"content":"{"cols":[{"width":2,"content":"section2"},{"width":2,"content":"section3"}]}{"cols":[{"width":4,"content":"section4"}]}"}]}
OUT;
        $this->assertEquals($output, $expectedoutput);
    }
}
