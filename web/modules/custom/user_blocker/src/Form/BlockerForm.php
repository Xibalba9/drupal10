<?php

namespace Drupal\user_blocker\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\User;

/**
 * Provides a User blocker form.
 */
class BlockerForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'user_blocker_blocker';
  }

  /**
   * {@inheritdoc}
   * 
   * This is the autocomplete version.
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {

      // $form['message'] = [
      //   '#type' => 'textarea',
      //   '#title' => $this->t('Message'),
      //   '#required' => TRUE,
      // ];

      // $form['actions'] = [
      //   '#type' => 'actions',
      // ];
      // $form['actions']['submit'] = [
      //   '#type' => 'submit',
      //   '#value' => $this->t('Send'),

      // $form['username'] = [
      //   '#type' => 'textfield',
      //   '#required' => true,
      //   '#title' => $this->t('Username'),
      //   '#description' => $this->t('Enter the username of the user you want to block.'),
      //   '#maxlength' => 64,
      //   '#size' => 64,
      //   '#weight' => '0',
      // ];
      // $form['submit'] = [
      //   '#type' => 'submit',
      //   '#value' => $this->t('Submit'),
      // ];

      $form['uid'] = [
        '#title' => $this->t('Username'),
        '#type' => 'entity_autocomplete',
        '#target_type' => 'user',
        '#required' => true,
        '#description' => $this->t('Enter the username of the user you want to block.'),
      ];
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];

    return $form;
  }

  /**
   * {@inheritdoc}
   * 
   * This is the autocomplete version.
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // if (mb_strlen($form_state->getValue('message')) < 10) {
    //   $form_state->setErrorByName('message', $this->t('Message should be at least 10 characters.'));
    // }

    parent::validateForm($form, $form_state);

    // $username = $form_state->getValue('username');
    // $user = user_load_by_name($username);
    // if (empty($user) || !$username) {
    //   $form_state->setError(
    //     $form['username'],
    //     $this->t('User %username was not found.', ['%username' => $username])
    //   );
    // }
    // elseif ($user->id() === "0") {

    //   $form_state->setError(
    //     $form['username'],
    //     $this->t('You need to enter the value!')
    //   );

    // }
    // else {
    //   $current_user = \Drupal::currentUser();
    //   if ($user->id() == $current_user->id()) {
    //     $form_state->setError(
    //       $form['username'],
    //       $this->t('You cannot block your own account.')
    //     );
    //   }
    // }

    $uid = $form_state->getValue('uid');
    $current_user = \Drupal::currentUser();
    if ($uid == $current_user->id()){
      $form_state->setError(
        $form['uid'],
        $this->t('You cannot block your own account.')
      );
    }     

  }

  /**
   * {@inheritdoc}
   * 
   * This the autocomplete version.
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // $this->messenger()->addStatus($this->t('The message has been sent.'));
    // $form_state->setRedirect('<front>');

    // $username = $form_state->getValue('username');
    // $user = user_load_by_name($username);
    // $user->block();
    // $user->save();
    // $this->messenger()->addMessage($this->t('User ↩ %username has been blocked.', ['%username' => ↩ $user->getAccountName()]));


    $uid = $form_state->getValue('uid');
    $user = User::load($uid);
    $user->block();
    $user->save();
    $this->messenger()->addMessage($this->t('User @username has been blocked.', ['@username' => $user->getAccountName()]));

  }
}
