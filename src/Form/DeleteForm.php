<?php

namespace Drupal\crudin\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Messenger;


class DeleteForm extends ConfirmFormBase {

    public function getFormId() {
        return 'delete_form';
    }
    
    public $cid;
    
    public function getQuestion() {
        return t('delete Record?');
    }
    
    public function getCancelUrl()
    {
        return new Url('crudin.crudin_controller_listing');
    }
    
    public function getDescription()
    {
        return t('Are you sure Do you want to Delete Record');
    }
    
    public function getConfirmText()
    {
        return t('Delete it');
    }
    
    public function getCancelText()
    {
        return t('Cancel');
    }
    
    public function buildForm(array $form, FormStateInterface $form_state, $cid = NULL)
    {
        $this->id = $cid;
        return parent::buildForm($form, $form_state);
    }
    
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
     parent::validateForm($form, $form_state);
    }
    
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
     $query = \Drupal::database();
     $query->delete('crudin')->condition('id',$this->id)->execute();
     
     $this->messenger()->addMessage('Successfully Deleted Record');
     
     $form_state->setRedirect('crudin.crudin_controller_listing');
        
    }
    
    
        
}

?>    
