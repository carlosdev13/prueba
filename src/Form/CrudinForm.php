<?php

namespace Drupal\crudin\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Messenger;
use Drupal\Core\Link;


class CrudinForm extends FormBase {

    public function getFormId() {
        return "crudin_form";
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $conn = Database::getConnection();
        
        $record = [];
        if(isset($_GET['id']))
{ 
      $query = $conn->select('crudin','m')->condition('id',$$_GET['id'])->fields('m');
      $record = $query->execute()->fetchAssoc();
}

$form['name']=['#type'=>'textfield','#title'=>t('Name'),'#required'=>TRUE,
'#default_value'=>(isset($record['name'])&&$_GET['id'])? $record['name']:'',];

$form['age']=['#type'=>'textfield','#title'=>t('Age'),'#required'=>TRUE,
'#default_value'=>(isset($record['age'])&&$_GET['id'])? $record['age']:'',];

$form['action']=['#type'=>'action',];

$form['action']['submit'] = ['#type' => 'submit','#value' => t('Save'),];

$form['action']['reset']=['#type'=>'button','#value'=>t('Reset'),'#attributes'=>['onclick'=>'this.form.reset(); return false;'],];

$link = Url::fromUserInput('/crudin');

$form['action']['cancel']=['#markup'=>Link::fromTextAndUrl(t('Back to page'),$link,['attributes'=>['class'=>'button']])->toString(),];
return $form;
}

public function validateForm(array &$form, FormStateInterface $form_state) {
        
        $name = $form_state->getValue('name');
        
        if(preg_match('/[^A-Za-z]/', $name))
        {
          $form_state->setErrorByName('name',$this->t('Name must be in Characters Only'));
         }
         
         
         $age = $form_state->getValue('age');
        
        if(!preg_match('/[^A-Za-z]/', $age))
        {
          $form_state->setErrorByName('age',$this->t('Age must be in Numbers Only'));
         }

parent::validateForm($form, $form_state);

}

public function submitForm(array &$form, FormStateInterface $form_state)
{

  $field = $form_state->getValue();
  
  $name = $field['name'];
  $age = $field['age'];
  
  if(isset($_GET['id']))
  {
     $field = ['name'=> $name,'age'=> $age,];
     
     $query = \Drupal::database();
     $query->update('crudin')->fields($field)->condition('id',$_GET['id'])->execute();
     $this->messenger()->addMessage('Sucessfully Update Record');
    }
    else
    {
       $field = ['name'=> $name,'age'=> $age,];
       $query = \Drupal::database();
     $query->insert('crudin')->fields($field)->execute();
     $this->messenger()->addMessage('Sucessfully Saved Record');
     
     $form_state->setRedirect('crudin.crudin_controller_listing');
    }
    
    }
    
 }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
       
