<?php

namespace Maka\Lesscmd\Console;

use Less_Parser;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;

class LessBuilder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:less';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Less builder';
    
    /**
     * @var Less ressources
     */
    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Repository $config)
    {
        parent::__construct();
        
        $this->files = $config->get('lesscmd');
        
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Start Less builder');
        
        try{
            
            $parser = new Less_Parser([
                'compress' => true
            ]);
            foreach ($this->files as $less_path => $css_path) {
                
                $less_file = base_path($less_path);
                $css_file = public_path($css_path);
                
                // parse file
                $parser->parseFile($less_file, url());
                
                // create dir if not exist
                if (!is_dir(dirname($css_file))) {
                    mkdir(dirname($css_file), 0777, true);
                    $this->info(dirname($css_file).' folder created');
                }
                
                // create file css
                $fic = fopen($css_file, 'w+');
                fwrite($fic, $parser->getCss());
                fclose($fic);
                
                // log file created
                $this->info($css_file.' file generated');
            }
            $this->info('End Less builder with success');
        }catch(Exception $e){
            $this->error('End Less builder with error : '.$e->getMessage());
        }
        
    }
}
