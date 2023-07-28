<?php

require_once 'hidetimes.civix.php';
// phpcs:disable
use CRM_Hidetimes_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function hidetimes_civicrm_config(&$config): void {
  _hidetimes_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function hidetimes_civicrm_install(): void {
  _hidetimes_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function hidetimes_civicrm_enable(): void {
  _hidetimes_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function hidetimes_civicrm_preProcess($formName, &$form): void {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function hidetimes_civicrm_navigationMenu(&$menu): void {
//  _hidetimes_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _hidetimes_civix_navigationMenu($menu);
//}

/**
 * Implements hook_civicrm_buildForm().
 *
 * Set a default value for an event price set field.
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function hidetimes_civicrm_pageRun($page) {
  $eventId = $page->getVar('_id');
  $event = \Civi\Api4\Event::get(FALSE)
    ->addSelect('Event_times.Hide_times_', 'start_date', 'end_date')
    ->addWhere('id', '=', $eventId)
    ->execute()
    ->first();
  $startDateTime = $event['start_date'];
  $endDateTime = $event['end_date'];
  $hideTimes = $event['Event_times.Hide_times_'];
  if ($hideTimes) {
    // Convert the string of date and time into an array, since they are separated by a single space.
    $startDateTime = explode(' ', $startDateTime);
    $endDateTime = explode(' ', $endDateTime);
    // Set new variables with just the date portion of the event's date/time info.
    $startDate = $startDateTime[0];
    $endDate = $startDateTime[0];

    // Get the event array out into its own variable.
    $eventArr = $page->get_template_vars('event');
    // Modify the start and end date in the variable.
    $eventArr['event_start_date'] = $startDate;
    $eventArr['event_end_date'] = $endDate;
    // Assign that whole array back to event.
    $page->assign('event', $eventArr);
  }
}
