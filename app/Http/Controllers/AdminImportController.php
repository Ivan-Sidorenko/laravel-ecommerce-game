<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Kris\LaravelFormBuilder\FormBuilder;
use Illuminate\Http\Request;


class AdminImportController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    
    function import(FormBuilder $formBuilder)
    {
      $form = $formBuilder->create('App\Forms\AdminImportForm', [
        'method' => 'POST',
        'url' => route('admin.do_import'),
      ]);
      return view('admin.import', compact('form'));
    }
    
    function do_import(Request $request)
    {
      $file = $request->file('CSV');
      $i = new \App\Classes\DataImporter();
      $i->import($file);
      
      if(count($i->errors)>0)
      {
        $request->session()->flash('danger', $i->errors);
      }
      if(count($i->warnings))
      {
        $request->session()->flash('warning', $i->warnings);
      }
      $request->session()->flash('success', sprintf("Import %s completed.", $file->getClientOriginalName()));      
      
      return redirect()->route('admin.import');
    }
    

}
