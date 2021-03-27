<?php

namespace Doggo\Model;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\Upload;

class Park extends DataObject
{
    //defien the CONST vars for feature of leash
    const ON_LEASH = "On-leash";
    const OFF_LEASH = "Off-leash";

    private static $table_name = 'Park';

    private static $db = [
        'Title' => 'Varchar',
        'Latitude' => 'Decimal(9,6)',
        'Longitude' => 'Decimal(9,6)',
        'Notes' => 'Text',
        'GeoJson' => 'Text',
        'FeatureOnOffLeash' => "Enum(array('On-leash', 'Off-leash'), 'On-leash')",
        'IsToPurge' => 'Boolean',
        'ObjectID' => "Int"
    ];


    private static $has_one = [
        'LiveImage' => Image::class,
        'PendingImage' => Image::class
    ];

    private static $summary_fields = [
        'Title' => 'Title',
        'Provider' => "Provider"
    ];

    /**
     * TODO
     * retunr the provider name
     */
    public function getProvider(){
        return $this->plural_name();
    }

    private static $searchable_fields = [
        'Title'
    ];


    private static $api_access = true;

    public function validate()
    {
        $validate = parent::validate();

        if (empty($this->Title)) {
            $validate->addFieldError('Title', 'Title is required');
        }

        return $validate;
    }

    public function canView($member = null)
    {
        return true;
    }


    /**
     * TODO
     * Check if leash on/off
     * @return boolean
     */
    public function isLeashOn(){
        if($this->FeatureOnOffLeash === self::ON_LEASH){
            return true;
        }
        return false;
    }


    /**
     * Function is to check if there ia LIVE image
     * @return boolean
     */
    public function hasLiveImage(){
        //Do we need to check $this->LiveImage() to make sure
        //the image exists
        if($this->LiveImageID){
            return true;
        }

        return false;
    }

    /**
     * Function is to chec kif there is PENDING image 
     * @return boolean
     */
    public function hasPendingImage(){
        if($this->PendingImageID){
            return true;
        }
        return false;
    }


    /**
     * Function is to do APPROVE pending image
     */
    public function approvePendingImage(){

        //If there is NO pending image, just STOP 
        if(!$this->hasPendingImage()){
            return;
        }

        //To remove current Live Image,
        //if there is LIVE IMAGE
        if($this->hasLiveImage()){
            $this->LiveImage()->delete();
        }

        //To update image ID
        $this->LiveImageID = $this->PendingImageID;
        $this->PendingImageID = 0;
        $this->write();
    }


    /**
     * Function is to handle upload image into Pending Image
     */
    public function uploadPendingImage($imageFile){

        //If pending image exists, we just clear it before we
        //handle submit new image
        if($this->PendingImageID){
            $this->PendingImage()->delete();
        }

        //init new image object
        $image = Image::create();
        $upload = Upload::create();
        $upload->loadIntoFile($imageFile, $image, 'ParkImages' );
        $image = $upload->getFile();

        //To update database reacord
        $this->PendingImageID = $image->ID;
        $this->write();
    }





    /**
     * OVERRIDE
     * CMS Fields
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        
        //To remove fields
        $fields->removeByName("IsToPurge");

        //To remove pending image field, if it is NEW record
        if($this->ID == 0 || !$this->hasPendingImage()){
            $fields->removeByName("PendingImage");
        }

        return $fields;
    }
}
