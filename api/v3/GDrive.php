<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

/**
 * GDrive.Getauthurl API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_g_drive_Getauthurl($params) {
  $client = new Google_Client();
  $client->setAuthConfigFile(__DIR__ . '/client_secret.json');
  $client->addScope(Google_Service_Drive::DRIVE_READONLY);
  $client->setRedirectUri(CRM_Utils_System::url('civicrm/civiimport/gdrive', NULL, TRUE, NULL, FALSE));
  $values = $client->createAuthUrl();

  return civicrm_api3_create_success($values, $params);
}
