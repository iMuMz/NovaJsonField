<?php

namespace Otrsw\NovaJsonField;

use Laravel\Nova\Fields\Field;

class NovaJsonField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-json-field';

    protected $inputFields = [];
    protected $outputFields = [];

    public function dontSave() {

        return $this->withMeta([
            'save' => false,
        ]);

    }

    public function withButtonLabel($value) {

        return $this->withMeta([
            'buttonLabel' => $value,
        ]);

    }


    public function max($value) {

        return $this->withMeta([
            'maxLength' => $value,
        ]);

    }

    public function url($value) {

        return $this->withMeta([
            'url' => $value,
        ]);

    }

    public function fetchMethod($value) {

        return $this->withMeta([
            'method' => $value,
        ]);

    }

    public function addInputField($fieldIdOnForm, $fetchKeyName = null) {
        
        if (is_null($fetchKeyName)) $fetchKeyName = $fieldIdOnForm;
        
        $this->inputFields[] = ['formid' => $fieldIdOnForm,
                                'fetchkeyname' => $fetchKeyName];
        return $this->withMeta([
            'inputFields' => $this->inputFields,
        ]);

    }

    public function addOutputField($fieldIdOnForm, $jsonKey = null) {
        
        if (is_null($jsonKey)) $jsonKey = $fieldIdOnForm;
        $this->outputFields[] = ['formid' => $fieldIdOnForm,
                                'jsonkey' => $jsonKey];
        return $this->withMeta([
            'outputFields' => $this->outputFields,
        ]);

    }
}
