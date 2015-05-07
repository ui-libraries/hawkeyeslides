<?php


class Scriptus extends Omeka_Record_AbstractRecord
{
    public $item;
    public $file;
    public $imageUrl;
    public $transcription;
    public $file_title;
    public $item_link;
    public $idl_link;
    public $collguide_link;
    public $collection_link;
    public $form;

    public function setRecords($itemId, $fileId) {
    	$this->item = get_record_by_id('item', $itemId);
    	$this->file = get_record_by_id('file', $fileId);    	
    }

    public function getItem() {
    	return $this->item;
    }

    public function getFile() {
    	return $this->file;
    }

    public function setMetadata($item, $file) {
    	$this->imageUrl = $file->getWebPath('original');

    	$this->transcription = metadata($file, array('Scriptus', 'Transcription'));
    	$this->location = metadata($file, array('Scriptus', 'location'));
    	$this->opponent = metadata($file, array('Scriptus', 'opponent'));
    	$this->playernames = metadata($file, array('Scriptus', 'playernames'));
    	$this->sport = metadata($file, array('Scriptus', 'sport'));
    	$this->comments = metadata($file, array('Scriptus', 'comments'));

    	$this->file_title = metadata($file, array('Dublin Core', 'Title') );
    	$this->item_link = link_to($item, 'show', metadata($item, array('Dublin Core', 'Title') )); 
    	$this->idl_link = metadata($file, array('Dublin Core', 'Source'));
    	$this->collguide_link = metadata($item, array('Dublin Core', 'Relation'));
    	$this->collection_link = link_to_collection_for_item();
    }

    public function getImageUrl() {
    	return $this->imageUrl;
    }

    public function getTranscription() {
    	return $this->transcription;
    }

    public function getLocation() {
    	return $this->location;
    }

    public function getOpponent() {
    	return $this->opponent;
    }

    public function getPlayernames() {
    	return $this->playernames;
    }

    public function getSport() {
    	return $this->sport;
    }

    public function getComments() {
    	return $this->comments;
    }

    public function getFileTitle() {
    	return $this->file_title;
    }

    public function getItemLink() {
    	return $this->item_link;
    }

    public function getIdlLink() {
    	return $this->idl_link;
    }

    public function getCollguideLink() {
    	return $this->collguide_link;
    }

    public function getCollectionLink() {
    	return $this->collection_link;
    }

    public function isValid($item, $file, $itemId) {
    	if (!$item || !$file) {

    	    return false;

    	} elseif ($file->item_id != $itemId) {

    	    return false;          

    	} else {

    		return true;
    	}
    }

    public function buildForm() {
    	//create a new Omeka form
    	$this->form = new Omeka_Form;         
    	$this->form->setMethod('post'); 

    	$transcriptionArea = new Zend_Form_Element_Text('transcribebox');  
    	$locationArea = new Zend_Form_Element_Text('locationbox');  

    	$transcriptionArea  ->setRequired(true)       
    	                    ->setValue($this->transcription)
    	                    ->setAttrib('cols', 3)
                            ->setAttrib('rows', 2)
    	                    ->setAttrib('class', 'col-md-6')
    	                    ->setAttrib('class', 'form-control');

    	$locationArea  		->setRequired(true)       
    		                ->setValue($this->location)
    		                ->setAttrib('cols', 3)
    	                    ->setAttrib('rows', 2)
    		                ->setAttrib('class', 'col-md-6')
    		                ->setAttrib('class', 'form-control');


    	
    	$this->form->addElement($transcriptionArea);
    	$this->form->addElement($locationArea);

    	$save = new Zend_Form_Element_Submit('save');
    	$save ->setLabel('Save');
    	$save->setAttrib('class', 'btn btn-primary');
    	$save->setAttrib('data-loading-text', "Saving...");
    	$save->setAttrib('id', 'save-button');
    	$this->form->addElement($save);

    	return $this->form;
    }

}