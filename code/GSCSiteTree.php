<?php
class GSCSiteTree extends DataExtension
{
    
    protected $splitcontent = null;
    
    public static $db = array(
        "GridLayout" => "Text"
    );
    
    public static $casting = array(
        "GridContent" => "HTMLText", //prevents save error
    );
    
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab("Root.Grid", TextareaField::create("GridLayout"));
        
        if ($this->owner->GridLayout) {
            $fields->addFieldToTab("Root.Main",
                GridSelectionField::create("GridPos", "Content Position", $this->owner->GridLayout), "Content");
            $fields->addFieldToTab("Root.Main",
                HtmlEditorField::create("GridContent", "Content")->setAttribute("data-gscdelimiter", GSCRenderer::$defaultdelimiter), "Content");
            $fields->addFieldToTab("Root.Main", GSCHiddenContentField::create("Content"), "GridContent");
            Requirements::javascript("gridstructuredcontent/javascript/gridedit.js");
        }
    }
    
    public function GridContent($pos = null)
    {
        $renderer = new GSCRenderer($this->owner->GridLayout, $this->owner->Content);
        return $renderer->render();
    }
    
    public function contentcontrollerInit()
    {
        $this->owner->Content = $this->GridContent();
    }
}
