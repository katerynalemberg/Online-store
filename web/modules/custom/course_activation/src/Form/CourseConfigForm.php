<?php

namespace Drupal\course_activation\Form;

use Drupal\commerce_product\Entity\ProductInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Course Activation settings for online course.
 */
final class CourseConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'course_activation_course_config';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return ['course_activation.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, ?ProductInterface $commerce_product = NULL): array {
    if (!$commerce_product) {
      $this->messenger()->addError($this->t('Product is invalid'));
      return $form;
    }

    $config = $this->configFactory()->getEditable('course_activation.settings.' . $commerce_product->id());

    $form['product'] = [
      '#type' => 'hidden',
      '#value' => $commerce_product->id(),
    ];

    $form['duration'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Course Duration'),
    ];

    $form['duration']['days'] = [
      '#type' => 'number',
      '#title' => $this->t('Days'),
      '#default_value' => $config->get('duration.days') ?? 30,
      '#min' => 0,
    ];

    $form['duration']['hours'] = [
      '#type' => 'number',
      '#title' => $this->t('Hours'),
      '#default_value' => $config->get('duration.hours') ?? 0,
      '#min' => 0,
      '#max' => 23,
    ];

    $form['duration']['minutes'] = [
      '#type' => 'number',
      '#title' => $this->t('Minutes'),
      '#default_value' => $config->get('duration.minutes') ?? 0,
      '#min' => 0,
      '#max' => 59,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->configFactory->getEditable('course_activation.settings.' . $form_state->getValue('product'))
      ->set('duration.days', (int) $form_state->getValue('days'))
      ->set('duration.hours', (int) $form_state->getValue('hours'))
      ->set('duration.minutes', (int) $form_state->getValue('minutes'))
      ->save();
    parent::submitForm($form, $form_state);
  }

}
