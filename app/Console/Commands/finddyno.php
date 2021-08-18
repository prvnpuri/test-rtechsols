<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class finddyno extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dyno:find {stance} {--order=desc}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'dyno:find {stance} {--order=desc|asc}';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $stance=$this->argument("stance");
        $order=$this->option("order");
        $this->println( "Listing $stance order by $order");
        

        $dataset1 = <<<DS1
NAME,LEG_LENGTH,DIET
Hadrosaurus,1.2,herbivore
Struthiomimus,0.92,omnivore
Velociraptor,1.0,carnivore
Euoplocephalus,1.6,herbivore
Stegosaurus,1.40,herbivore
Tyrannosaurus Rex,2.5,carnivore
DS1;

        $dataset2 = <<<DS2
NAME,STRIDE_LENGTH,STANCE
Euoplocephalus,1.87,quadrupedal
Stegosaurus,1.90,quadrupedal
Tyrannosaurus Rex,5.76,bipedal
Hadrosaurus,1.4,bipedal
Struthiomimus,1.34,bipedal
Velociraptor,2.72,bipedal
DS2;

        $data1=$this->csvToArray($dataset1);
        $data1=$this->indexToArrayByindex($data1,"NAME");

        $data2=$this->csvToArray($dataset2);
        $data2=$this->indexToArrayByindex($data2,"NAME");
        
        $data2=array_filter($data2,function($item,$key)use($stance){
            return $item["STANCE"]==$stance;
        },ARRAY_FILTER_USE_BOTH);
        
        $stanceFilter=array_map(function($item)use($data1){
            $name=$item["NAME"];
            $data=array_merge($item,$data1[$name]);
            $data["SPEED"]=(($data["STRIDE_LENGTH"]/$data["LEG_LENGTH"])-1)* sqrt($data["LEG_LENGTH"] * 9.8); 
            return $data;
        },$data2);

        $speedlist=array_map(function($item){
            return $item["SPEED"];
        },$stanceFilter);
        
        $dynonames=array_keys( $stanceFilter);

        if($order=="asc"){
            $result=array_multisort($speedlist,SORT_ASC, $dynonames);
        }else{
            $result=array_multisort($speedlist,SORT_DESC, $dynonames);
        }

        array_walk($dynonames,function($item){
            $this->println($item);
        });
        
        return 0;
    }

    private function println($str){
        echo $str."\n";
    }

    private function csvToArray($str){
        // str_getcsv will not enough for row indexed array.
        return array_map(function($item){
            return explode(",",$item);
        },explode("\n",$str));
    }

    private function indexToArrayByindex($array,$keyname){
        $keys=$array[0];
        $keylen=count($keys);
        $keynameidex=array_search($keyname,$keys);
        $arr=[];
        $arrlen=count($array);
        for($i=1;$i<$arrlen;$i++){
            $item=[];
            for($k=0;$k<$keylen;$k++){
                $item[$keys[$k]]=$array[$i][$k];
            }
            $itemkey=$item[$keys[$keynameidex]];
            $arr[$itemkey]=$item;
        }
        return $arr;
    }

}
