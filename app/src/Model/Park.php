<?php

namespace Doggo\Model;

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

    // private static $indexes = [
    //     'Provider' => [
    //         'columns' => ['Provider'],
    //     ],
    //     'ProviderCode' => [
    //         'columns' => ['Provider', 'ProviderCode'],
    //     ],
    // ];

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
     * Function is to handle upload image into Pending Image
     */
    // public function uploadPendingImage($imageFile){

    //     $pendingImage = $this->PendingImage();

    //     //If pending image exists, we just clear it before we
    //     //handle submit new image
    //     if($pendingImage->ID){
    //         $this->PendingImage()->Image()->delete();
    //     }else{
    //         $pendingImage = ParkImage::create([
    //             'BelongToPark' => $this->ID
    //         ]);

    //         $pendingImage->write();
    //     }

    //     $image = Image::create();
    //     $upload = Upload::create();
    //     $upload->loadIntoFile($imageFile, $image, 'ParkImages' );
    //     $image = $upload->getFile();

    //     //To update image
    //     $pendingImage->ImageID = $image->ID;
    //     $pendingImage->write();
    // }





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
        $fields->removeByName("PendingImage");

        return $fields;
    }
}
