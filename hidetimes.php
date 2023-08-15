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
 * Alter fields for an event registration to make them into a demo form.
 */
function hideTimes_civicrm_alterContent( &$content, $context, $tplName, &$object) {
  if ($context === 'page' && $tplName === 'CRM/Event/Page/EventInfo.tpl') {
    $eventId = $object->_id;
    $event = \Civi\Api4\Event::get(FALSE)
      ->addSelect('Event_times.Hide_times_', 'start_date', 'end_date')
      ->addWhere('id', '=', $eventId)
      ->execute()
      ->first();
    $hideTimes = $event['Event_times.Hide_times_'];
    if ($hideTimes) {
      // This expression handles the case of the start and end date being the same, i.e. the time is in 'from HH:MM AM|PM to HH:MM AM|PM' format
      // Ex: August 1 from 7:00 pm to 10:00 pm -> August 1 
      $content = preg_replace('/\sfrom&nbsp;\s1?[0-9]:[0-5][0-9]\s(AM|PM)&nbsp;to&nbsp;\s1?[0-9]:[0-5][0-9]\s(AM|PM)/i', '', $content);
      // This expressions handles the case of different start and end dates, i.e. the times appear twice in 'HH:MM AM|PM' format.
      // Ex: August 1 7:00 PM to August 2 4:00 pm -> August 1 to August 2
      $content = preg_replace('/\s1?[0-9]:[0-5][0-9]\s(AM|PM)(&nbsp;)?/i', '', $content);
    }
  }
}
