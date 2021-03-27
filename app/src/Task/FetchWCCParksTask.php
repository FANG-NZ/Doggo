<?php

namespace Doggo\Task;

use Doggo\Model\WCCPark;
use Doggo\Model\Park;
use GuzzleHttp\Client;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class FetchWCCParksTask extends BuildTask
{
    private static $api_url;

    private static $api_title;

    public function run($request)
    {
        $client = new Client();

        $response = $client->request(
            'GET',
            $this->config()->get('api_url'),
            ['User-Agent' => 'Doggo (www.somar.co.nz)']
        );

        if ($response->getStatusCode() !== 200) {
            user_error('Could not access ' . $this->config()->get('api_url'));
            exit;
        }

        /*
         * Mark existing records as IsToPurge.
         *
         * As we encounter each record in the API source, we unset this.
         * Once done, any still set are deleted.
         */
        
        $existingParks = WCCPark::get();
        foreach ($existingParks as $park) {
            $park->IsToPurge = true;
            $park->write();
        }

        $data = json_decode($response->getBody());

        $parks = $data->features;
        foreach ($parks as $park) {

            //If we get "Prohibited", just stop here
            if($park->properties->On_Off === 'Prohibited')
                continue;

            $parkObject = WCCPark::get()->filter([
                'ProviderCode' => $park->properties->GlobalID,
            ])->first();
            $status = 'changed';

            if (!$parkObject) {
                $status = 'created';
                $parkObject = WCCPark::create();
                $parkObject->Provider = $this->config()->get('api_title');
                $parkObject->ProviderCode = $park->properties->GlobalID;
            }

            if ($park->properties->On_Off === 'Off leash') {
                $leash = Park::OFF_LEASH;
            } else {
                $leash = Park::ON_LEASH;
            }

            if ($park->geometry->type === 'MultiPolygon') {
                $geometry = $park->geometry->coordinates[0][0][0];
            }
            else {
                $geometry = $park->geometry->coordinates[0][0];
            }

            $parkObject->update([
                //'IsToPurge' => false,
                'Title' => $park->properties->name,
                'Latitude' => $geometry[0],
                'Longitude' => $geometry[1],
                'Notes' => $park->properties->Details,
                'GeoJson' => json_encode($park),
                'FeatureOnOffLeash' => $leash,
            ]);

            $parkObject->write();

            DB::alteration_message('[' . $parkObject->ProviderCode . '] ' . $parkObject->Title, $status);
        }

        $existingParks = WCCPark::get()->filter([
            'IsToPurge' => true,
        ]);
        foreach ($existingParks as $park) {
            DB::alteration_message('[' . $parkObject->ProviderCode . '] ' . $parkObject->Title, 'deleted');
            $park->delete();
        }
    }
}
