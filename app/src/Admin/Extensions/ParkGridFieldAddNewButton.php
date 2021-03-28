<?php

namespace Doggo\Admin\Extensions;

use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\View\ArrayData;
use SilverStripe\Control\Controller;
use SilverStripe\View\SSViewer;

class ParkGridFieldAddNewButton extends GridFieldAddNewButton{

    //To define the link 
    protected $link;

    public function __construct($link, $targetFragment = 'before')
    {
        parent::__construct($targetFragment);

        $this->link = $link;
    }

    /**
     * OVERRIDE
     * We need to append TYPE var on the url
     */
    public function getHTMLFragments($gridField)
    {
        // $singleton = singleton($gridField->getModelClass());
        // $context = [];
        // if ($gridField->getList() instanceof RelationList) {
        //     $record = $gridField->getForm()->getRecord();
        //     if ($record && $record instanceof DataObject) {
        //         $context['Parent'] = $record;
        //     }
        // }

        // if (!$singleton->canCreate(null, $context)) {
        //     return [];
        // }

        // if (!$this->buttonName) {
        //     // provide a default button name, can be changed by calling {@link setButtonName()} on this component
        //     $objectName = $singleton->i18n_singular_name();
        //     $this->buttonName = _t('SilverStripe\\Forms\\GridField\\GridField.Add', 'Add {name}', ['name' => $objectName]);
        // }

        $data = new ArrayData([
            'NewLink' => Controller::join_links($gridField->Link('item'), 'new/' . $this->link),
            'ButtonName' => $this->buttonName,
        ]);

        //using GridFieldAddNewButton class
        $templates = SSViewer::get_templates_by_class($this, '', GridFieldAddNewButton::class);
        return [
            $this->targetFragment => $data->renderWith($templates),
        ];
    }

}