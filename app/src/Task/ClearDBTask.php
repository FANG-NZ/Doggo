<?php

namespace Doggo\Task;

use SilverStripe\Dev\BuildTask;
use Doggo\Model\Park;
use SilverStripe\ORM\DB;

class ClearDBTask extends BuildTask{

    protected $title = "Clear Database Task";
    protected $description = 'This is to clear all data from DATABASE. It should be removed from LIVE';

    public function run($request){

        $parks = Park::get();

        $count = 0;
        foreach($parks as $i => $item){
            $item->delete();
            $count++;
        }

        DB::alteration_message("Total [{$count}] parks object deleted", "deleted");
    }

}