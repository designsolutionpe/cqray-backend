<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;
use League\Csv\Exception;

class ImportArticlesCsV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-articles-csv {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importar data de articulos desde un csv.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //get file path
        $file = $this->argument('file');

        if(!file_exists($file))
        {
            echo "The file $file does not exists";
            return 1;
        }

        try
        {
            $csv = Reader::createFromPath($file,'r');
            $csv->setHeaderOffset(0);

            foreach( $csv->getRecords() as $record )
            {
                var_dump($record);
            }

            $this->info("Importacion completa.");
        }
        catch(Exception $e)
        {
            $this->error("Hubo un error ejecutando el lector de csv");
            return 0;
        }
    }
}
