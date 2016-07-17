<?php

use Illuminate\Database\Seeder;

class CsvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $files = [
        'Product Import - Flavours.csv',
        'Product Import - Suppliers.csv',
        'Product Import - Product Tiers.csv',
        'Product Import - Product Master.csv',
        'Product Import - Product Content .csv',
        'Product Import - Product Variations.csv',
      ];
      
      foreach($files as $f)
      {
        $fpath = base_path("sample_data/{$f}");
        $file = new SplFileInfo($fpath);
        $i = new \App\Classes\DataImporter();
        echo("Importing {$f}\n");
        $i->import($file);
        foreach($i->warnings as $w)
        {
          echo("\tWarning: {$w}\n");
        }
        foreach($i->errors as $w)
        {
          echo("\tError: {$w}\n");
        }
      }
    }
}
