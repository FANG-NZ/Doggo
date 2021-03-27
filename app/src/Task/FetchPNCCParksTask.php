<?php

namespace Doggo\Task;

use Doggo\Model\PNCCPark;
use Doggo\Model\Park;
use GuzzleHttp\Client;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class FetchPNCCParksTask extends BuildTask{

    public function run($request){

        $_title = $this->config()->get("api_title");
        $_url = $this->config()->get('api_url');

        $client = new Client();
        $response = $client->request(
            'GET',
            $_url,
            ['User-Agent' => 'Doggo (www.somar.co.nz)']
        );

        if ($response->getStatusCode() !== 200) {
            user_error('Could not access ' . $_title);
            exit;
        }

        /*
         * Mark existing records as IsToPurge.
         *
         * As we encounter each record in the API source, we unset this.
         * Once done, any still set are deleted.
         */
        $existingParks = PNCCPark::get();
        foreach ($existingParks as $park) {
            $park->IsToPurge = true;
            $park->write();
        }

        $data = json_decode($response->getBody());

        $parks = $data->features;
        foreach ($parks as $park) {
            
            $properties = $park->properties;

            if($properties->DESCRIPTION !== "Dog on leash area" && 
                $properties->DESCRIPTION !== "Dog exercise area"){
                continue;
            }

            //We use OBJECTID here instead of "ProviderCode"
            $parkObject = PNCCPark::get()->filter([
                'ObjectID' => $properties->OBJECTID,
            ])->first();
            $status = 'changed';

            //If NOT FOUND, create new object
            if (!$parkObject) {
                $status = 'created';
                $parkObject = PNCCPark::create();
            }

            if ($properties->DESCRIPTION === 'Dog on leash area') {
                $leash = Park::ON_LEASH;
            } else {
                $leash = Park::OFF_LEASH;
            }

            //Setup geo
            if ($park->geometry->type === 'MultiPolygon') {
                $geometry = $park->geometry->coordinates[0][0][0];
            }
            else {
                $geometry = $park->geometry->coordinates[0][0];
            }

            $parkObject->update([
                'Title' => !empty($properties->RESERVE_NAME) ? $properties->RESERVE_NAME : "--- NG ---",
                'Latitude' => $geometry[0],
                'Longitude' => $geometry[1],
                'Notes' => $properties->DESCRIPTION,
                'GeoJson' => json_encode($park),
                'FeatureOnOffLeash' => $leash,
                'ObjectID' => $properties->OBJECTID
            ]);
            $parkObject->write();

            DB::alteration_message('[' . $geometry[0] . ' , ' .$geometry[1] .'] ' . $parkObject->Title, $status);
        }


        $existingParks = PNCCPark::get()->filter([
            'IsToPurge' => true,
        ]);
        foreach ($existingParks as $park) {
            DB::alteration_message('[' . $parkObject->ProviderCode . '] ' . $parkObject->Title, 'deleted');
            $park->delete();
        }
    }

}