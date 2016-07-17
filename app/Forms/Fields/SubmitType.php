<?php namespace  App\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class SubmitType extends FormField
{
    /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'forms.fields.custom_submit';
    }

    /**
     * @inheritdoc
     */
    protected function getDefaults()
    {
        return [
            'attr' => ['type' => $this->type, 'class'=>'btn btn-success']
        ];
    }
}
