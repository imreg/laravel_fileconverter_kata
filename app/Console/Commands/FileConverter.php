<?php

namespace App\Console\Commands;

use App\Services\Containers\Details;
use App\Services\Corverters\CsvConverter;
use App\Services\Corverters\Json;
use App\Services\Corverters\JsonConverter;
use App\Services\Corverters\XmlConverter;
use App\Services\FileManagers\Reader;
use App\Services\FileManagers\Writer;
use App\Services\Files\File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FileConverter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'File converter';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $detailsContainer = new Details();
        $detailsContainer->addDetails(new Reader(
            new File(Storage::disk('public'), 'data/users.json'),
            new JsonConverter()
        ));
        $detailsContainer->addDetails(new Reader(
            new File(Storage::disk('public'), 'data/users.csv'),
            new CsvConverter()
        ));
        $detailsContainer->addDetails(new Reader(
            new File(Storage::disk('public'), 'data/users.xml'),
            new XmlConverter()
        ));

        $detailsContainer->sortDataById();

        $detailsContainer->saveDetails(new Writer(
            new File(Storage::disk('public'), 'converted/user.json'),
            new JsonConverter()
        ));
        $detailsContainer->saveDetails(new Writer(
            new File(Storage::disk('public'), 'converted/user.csv'),
            new CsvConverter()
        ));
        $detailsContainer->saveDetails(new Writer(
            new File(Storage::disk('public'), 'converted/user.xml'),
            new XmlConverter()
        ));

        $this->info('done');
    }
}
