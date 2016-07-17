<?php namespace App\Forms;

use Kris\LaravelFormBuilder\Form;

class AdminImportForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('CSV', 'file')
              ->add('import', 'custom_submit', ['label' => 'Import',])
              ;
    }
}