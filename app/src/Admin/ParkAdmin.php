<?php

namespace Doggo\Admin;

use SilverStripe\Admin\ModelAdmin;
use Doggo\Model\Park;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use Doggo\Admin\Extensions\ParkGridFieldDetailForm_ItemRequest;

class ParkAdmin extends ModelAdmin
{
    private static $managed_models = [
        Park::class,
    ];

    private static $menu_title = 'Parks';

    private static $url_segment = 'parks';


    /**
     * OVERIDE
     * We just customrize gridfield
     */
    public function getGridField(): GridField{
        $field = parent::getGridField();

        $config = $field->getConfig();

        $detailForm = $config->getComponentByType(GridFieldDetailForm::class);
        $detailForm->setItemRequestClass(ParkGridFieldDetailForm_ItemRequest::class);

        return $field;
    }
}
