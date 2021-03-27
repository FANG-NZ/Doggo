<?php

namespace Doggo\Admin\Extensions;

use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldDetailForm_ItemRequest;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\ValidationResult;

class ParkGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest{

    /**
     * OVERRIDE
     * Function is to update form actions
     */
    protected function getFormActions()
    {
        $actions = parent::getFormActions();

        //If it is NEW or the park object doesn't have Pending Image,
        //we just return Actions back
        if($this->record->ID == 0 || !$this->record->hasPendingImage()){
            return $actions;
        }

        //To add new Action for approve
        $noChangesClasses = 'btn-outline-primary font-icon-image';
        $approveAction = FormAction::create('doApprove','Approve Pending Image')
                            ->addExtraClass($noChangesClasses)
                            ->setUseButtonTag(true);

        $actions->insertAfter(
            'MajorActions', 
            $approveAction
        );

        return $actions;
    }


    /**
     * Function is to do approve request
     */
    public function doApprove($data, $form){
        $isNewRecord = $this->record->ID == 0;

        if($isNewRecord || !$this->record->hasPendingImage()){
            $this->httpError(403, "Approve Pending Image request error");
            return null;
        }

        //call approve method 
        $this->record->approvePendingImage();

        $form->sessionMessage("Image has been approved", 'good', ValidationResult::CAST_HTML);

        // Redirect after save
        return $this->redirectAfterSave($isNewRecord);
    }

}