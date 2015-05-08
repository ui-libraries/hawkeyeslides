<?php

class Scriptus_IndexController extends Omeka_Controller_AbstractActionController
{
    
    public function transcribeAction()
    {
        $transcription = '';

        $itemId = $this->getParam('item');
        $fileId = $this->getParam('file');
        
        $item = get_record_by_id('item', $itemId);
        $file = get_record_by_id('file', $fileId);       

        if (!$item || !$file) {

            throw new Zend_Controller_Action_Exception('This page does not exist', 404);  

        } elseif ($file->item_id != $itemId) {

            throw new Zend_Controller_Action_Exception('This page does not exist', 404);             

        } else {
            
           set_current_record('item', $item); 

           //get the path to the file image  
           $imageUrl = $file->getWebPath('original');           

           //get the metadata from each Scriptus field
           $transcription = metadata($file, array('Scriptus', 'Transcription'));
           $location = metadata($file, array('Scriptus', 'location'));
           $opponent = metadata($file, array('Scriptus', 'opponent'));
           $playernames = metadata($file, array('Scriptus', 'playernames'));
           $sport = metadata($file, array('Scriptus', 'sport'));
           $comments = metadata($file, array('Scriptus', 'comments'));


           //get the DC fields
           $dc_file_title = metadata($file, array('Dublin Core', 'Title') );
           $dc_item_link = link_to($item, 'show', metadata($item, array('Dublin Core', 'Title') )); 
           $idl_link = metadata($file, array('Dublin Core', 'Source'));
           $collguide_link = metadata($item, array('Dublin Core', 'Relation'));
           $collection_link = link_to_collection_for_item();

           //send everything to the view
           $this->view->imageUrl = $imageUrl;            
           $this->view->dc_file_title = $dc_file_title;            
           $this->view->dc_item_link = $dc_item_link;
           $this->view->collection_link = $collection_link; 
           $this->view->idl_link = $idl_link; 
           $this->view->collguide_link = $collguide_link; 

        }   

        //create a new Omeka form
        $form = new Omeka_Form;         
        $form->setMethod('post'); 

       //create new text element for each field
       $transcriptionArea = new Zend_Form_Element_Textarea('transcribebox');  
       $locationArea = new Zend_Form_Element_Textarea('locationbox'); 
       $opponentArea = new Zend_Form_Element_Textarea('opponentbox'); 
       $playernamesArea = new Zend_Form_Element_Textarea('playernamesbox'); 
       $sportArea = new Zend_Form_Element_Textarea('sportbox'); 
       $commentsArea = new Zend_Form_Element_Textarea('commentsbox'); 


       
       //set attributes for each element
       $transcriptionArea ->setRequired(true)       
                          ->setValue($transcription)
                          ->setAttrib('rows', 1)
                          ->setAttrib('class', 'col-md-1'); 

       $locationArea      ->setRequired(true)       
                          ->setValue($location)                           
                          ->setAttrib('rows', 1)
                          ->setAttrib('class', 'col-md-1');

       $opponentArea      ->setRequired(true)       
                          ->setValue($opponent)                           
                          ->setAttrib('rows', 1)
                          ->setAttrib('class', 'col-md-1');

      $playernamesArea    ->setRequired(true)       
                          ->setValue($playernames)                           
                          ->setAttrib('rows', 1)
                          ->setAttrib('class', 'col-md-1');

      $sportArea          ->setRequired(true)       
                          ->setValue($sport)                           
                          ->setAttrib('rows', 1)
                          ->setAttrib('class', 'col-md-1');

      $commentsArea       ->setRequired(true)       
                          ->setValue($comments)                           
                          ->setAttrib('rows', 1)
                          ->setAttrib('class', 'col-md-1');
       
        $form->addElement($transcriptionArea);
        $form->addElement($locationArea);
        $form->addElement($opponentArea);
        $form->addElement($playernamesArea);
        $form->addElement($sportArea);
        $form->addElement($commentsArea);


        
        //construct save button
        $save = new Zend_Form_Element_Submit('save');
        $save ->setLabel('Save');
        $save->setAttrib('class', 'btn btn-primary');
        $save->setAttrib('data-loading-text', "Saving...");
        $save->setAttrib('id', 'save-button');
        $form->addElement($save);

        $this->view->form = $form;         
    }

     public function saveAction() 
     {        

        //get the record based on URL param
        $fileId = $this->getParam('file');
        $file = get_record_by_id("file", $fileId);

        //get a new http request from the form      
        $request = new Zend_Controller_Request_Http();

        //get the posted data from the view
        $transcription = $request->getPost('transcription');      
        $location = $request->getPost('location');  
        $opponent = $request->getPost('opponent');    
        $playernames = $request->getPost('playernames');  
        $sport = $request->getPost('sport'); 
        $comments = $request->getPost('comments'); 

        //save the new transcription data

        //get each element
        $transcriptionElement = $file->getElement('Scriptus', 'Transcription');
        $locationElement = $file->getElement('Scriptus', 'location');
        $opponentElement = $file->getElement('Scriptus', 'opponent');
        $playernamesElement = $file->getElement('Scriptus', 'playernames');
        $sportElement = $file->getElement('Scriptus', 'sport');
        $commentsElement = $file->getElement('Scriptus', 'comments');

        //for some reason we have to remove each element first
        $file->deleteElementTextsByElementId(array($transcriptionElement->id));
        $file->deleteElementTextsByElementId(array($locationElement->id));
        $file->deleteElementTextsByElementId(array($opponentElement->id));
        $file->deleteElementTextsByElementId(array($playernamesElement->id));
        $file->deleteElementTextsByElementId(array($sportElement->id));
        $file->deleteElementTextsByElementId(array($commentsElement->id));


        //add text to each element
        $file->addTextForElement($transcriptionElement, $transcription, false);
        $file->addTextForElement($locationElement, $location, false);
        $file->addTextForElement($opponentElement, $opponent, false);
        $file->addTextForElement($playernamesElement, $playernames, false);
        $file->addTextForElement($sportElement, $sport, false);
        $file->addTextForElement($commentsElement, $comments, false);
    
        $file->save();    
    }
    

}