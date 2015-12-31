<?php

class GSCHiddenContentField extends Hiddenfield
{
    
    public function performReadonlyTransformation()
    {
        $field = $this->castedCopy('HtmlEditorField_Readonly');
        $field->dontEscape = true;
        return $field;
    }
}
