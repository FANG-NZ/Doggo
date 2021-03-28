<?php

namespace Doggo\Admin\Extensions;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\ORM\DataObject;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;

class ParkGridFieldDetailForm extends GridFieldDetailForm{

    /**
     * OVERRIDE
     */
    public function getURLHandlers($gridField)
    {
        return [
            'item/$ID/$TYPE' => 'handleItem'
        ];
    }


    /**
     * OVERRIDE
     * COPY from parent 
     * We need to check which NEW REQUEST for (PNCC / WCC)
     */
    protected function getRecordFromRequest(GridField $gridField, HTTPRequest $request) : ?DataObject{
        /** @var DataObject $record */
        if (is_numeric($request->param('ID'))) {
            /** @var Filterable $dataList */
            $dataList = $gridField->getList();
            $record = $dataList->byID($request->param('ID'));
        } else {

            $type = $request->param('TYPE');
            $className = str_replace('-', '\\', $type);

            $record = Injector::inst()->create($className);
        }

        return $record;
    }

}