<?php

namespace Doggo\Admin;

use SilverStripe\Admin\ModelAdmin;
use Doggo\Model\Park;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use Doggo\Admin\Extensions\ParkGridFieldDetailForm_ItemRequest;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldDataColumns;

class ParkAdmin extends ModelAdmin
{
    private static $managed_models = [
        Park::class => ['title' => "All parks"],
        "Doggo-Model-Park-Pending" => ['title' => "Pending Images"],
    ];

    private static $menu_title = 'Parks';

    private static $url_segment = 'parks';

    /**
     * Define the vars to indicate if we need to show Pending Park List
     */
    private $showPendingList = false;

    /**
     * OVERRIDE
     */
    protected function init()
    {
        parent::init();

        if($this->showPendingList){
            $this->modelTab = "Doggo-Model-Park-Pending";
        }
    }

    /**
     * OVERRIDE
     */
    protected function unsanitiseClassName($class)
    {
        if($class === "Doggo-Model-Park-Pending"){
            $class = "Doggo-Model-Park";
            $this->showPendingList = true;
        }
        return parent::unsanitiseClassName($class);
    }

    /**
     * OVERRIDE
     */
    public function getList()
    {
        $list = parent::getList();

        //If showPendingList set TRUE, we need to filter
        //item has PendingImage
        if($this->showPendingList){
            $list = $list->filterByCallback(function($item, $selflist){
                return $item->hasPendingImage();
            });
        }

        return $list;
    }


    /**
     * OVERIDE
     * We just customrize gridfield
     */
    public function getGridField(): GridField{
        $field = parent::getGridField();

        $config = $field->getConfig();

        //To remove buttons form PendingList panel
        if($this->showPendingList){
            $config
                ->removeComponentsByType(GridFieldAddNewButton::class)
                ->removeComponentsByType(GridFieldExportButton::class)
                ->removeComponentsByType(GridFieldImportButton::class)
                ->removeComponentsByType(GridFieldPrintButton::class);
        }

        $detailForm = $config->getComponentByType(GridFieldDetailForm::class);
        $detailForm->setItemRequestClass(ParkGridFieldDetailForm_ItemRequest::class);

        //To setup GridField Column title
        $dataColumns = $config->getComponentByType(GridFieldDataColumns::class);
        $dataColumns->setDisplayFields([
            'Title' => 'Title',
            'Provider' => "Provider",
            'hasPendingImage' => "Pending Image"
        ]);

        $dataColumns->setFieldFormatting([

            "hasPendingImage" => function($value, $object){
                if($value){
                    $imageUrl = $object->PendingImage()->AbsoluteLink();
                    return "<img src='{$imageUrl}' width='100' height='80' />";
                }
                return null;
            }
            
        ]);

        return $field;
    }

}
