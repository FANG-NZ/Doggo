<?php

namespace Doggo\APIController;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use Doggo\Model\Park;

class ParkController extends Controller{

    private static $url_handlers = [
        'load' => "doLoadAll",
        'upload-image/$ParkID' => "doUploadImage"
    ];

    private static $allowed_actions = [
        "doLoadAll",
        "doUploadImage"
    ];


    /**
     * OVERRIDE
     */
    public function init(){
        parent::init();

        //To add header for JSON
        $this->response->addHeader('Content-Type', 'application/json');
    }


    /**
     * Function is to handle load all park data
     */
    public function doLoadAll(HTTPRequest $request){
        $parks = Park::get()->limit(5);
        //$parks = Park::get();

        $data = [];
        foreach($parks as $item){

            $data[] = [
                'id' => $item->ID,
                'title' => $item->Title,
                'latlng' => [
                    'latitude' => $item->Latitude,
                    'longitude' => $item->Longitude
                ],
                'notes' => $item->Notes,
                'geo_json' => $item->GeoJson,
                'is_leash_on' => $item->isLeashOn(),
                'leash_note' => $item->FeatureOnOffLeash,
                'provider' => $item->getProvider(),
                'live_image' => $item->hasLiveImage() ? $item->LiveImage()->AbsoluteLink() : null,
                'has_pending_image' => $item->hasPendingImage()
            ];
        }

        return json_encode($data);
    }


    /**
     * Functio is to handle image upload
     */
    public function doUploadImage(HTTPRequest $request){
        $parkID = $request->param('ParkID');

        if(!$parkID || !is_numeric($parkID)){
            die("ERROR! Park ID NOT FOUND");
        }

        //To load file(image)
        $image = $request->postVar('image');
        

        //To load park object
        $park = Park::get_by_id($parkID);
        $park->uploadPendingImage($image);
        
        
        return json_encode(['status' => 'DONE']);
    }

}