<?php

namespace Drupal\course_activation\EventSubscriber;

use Drupal\Core\Session\AccountInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\flag\Event\FlagEvents;
use Drupal\flag\Event\FlaggingEvent;
use Drupal\flag\Event\UnflaggingEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Subscribes to Course Activation events for test.
 */
final class CourseActivationSubscriber implements EventSubscriberInterface {

  public function __construct(
    protected StateInterface $state,
    protected AccountInterface $currentUser,
    protected RequestStack $requestStack,
  ) {}

  /**
   * Sets the course state status to unflagged (deactivated).
   */
  public function handleCourseDeactivation(UnflaggingEvent $event) {
    $user_id = $this->currentUser->id();
    $request = $this->requestStack->getCurrentRequest();
    $destination = $request->query->get('destination');
    $destination = parse_url($destination, PHP_URL_PATH);
    $this->state->set('course_' . $user_id . $destination . '_activated', FALSE);
  }

  /**
   * Sets the course state status to flagged (activated).
   */
  public function handleCourseActivation(FlaggingEvent $event) {
    $user_id = $this->currentUser->id();
    $request = $this->requestStack->getCurrentRequest();
    $destination = $request->query->get('destination');
    $destination = parse_url($destination, PHP_URL_PATH);
    $this->state->set('course_' . $user_id . $destination . '_activated', TRUE);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[FlagEvents::ENTITY_FLAGGED][] = ['handleCourseActivation'];
    $events[FlagEvents::ENTITY_UNFLAGGED][] = ['handleCourseDeactivation'];
    return $events;
  }

}
