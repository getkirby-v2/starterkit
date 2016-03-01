<?php

class StructureFieldController extends Kirby\Panel\Controllers\Field {

  public function add() {

    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $modalsize = $this->field()->modalsize();
    $form      = $this->form('add', array($model, $structure), function($form) use($model, $structure, $self) {

      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      $structure->add($form->serialize());
      $self->redirect($model);

    });

    return $this->modal('add', compact('form', 'modalsize'));

  }

  public function update($entryId) {

    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $entry     = $structure->find($entryId);

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    $modalsize = $this->field()->modalsize();
    $form      = $this->form('update', array($model, $structure, $entry), function($form) use($model, $structure, $self, $entryId) {

      // run the form validator
      $form->validate();

      if(!$form->isValid()) {
        return false;
      }

      $structure->update($entryId, $form->serialize());
      $self->redirect($model);

    });

    return $this->modal('update', compact('form', 'modalsize'));
        
  }

  public function delete($entryId) {
    
    $self      = $this;
    $model     = $this->model();
    $structure = $this->structure($model);
    $entry     = $structure->find($entryId);

    if(!$entry) {
      return $this->modal('error', array(
        'text' => l('fields.structure.entry.error')
      ));
    }

    $form = $this->form('delete', $model, function() use($self, $model, $structure, $entryId) {
      $structure->delete($entryId);
      $self->redirect($model);
    });
    
    return $this->modal('delete', compact('form'));

  }

  public function sort() {
    $model     = $this->model();
    $structure = $this->structure($model);
    $structure->sort(get('ids'));
    $this->redirect($model);
  }

  protected function structure($model) {
    return $model->structure()->forField($this->fieldname());
  }

}