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

/**
 * GDrive.Getlogincred API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_g_drive_Getlogincred($params) {
  $session = CRM_Core_Session::singleton();
  $authToken = $session->get('auth_token');
  $accessToken = $session->get('access_token');
  if (empty($accessToken) && !empty($authToken)) {
    $client = new Google_Client();
    $client->setAuthConfigFile(__DIR__ . '/client_secret.json');
    $client->addScope(Google_Service_Drive::DRIVE_READONLY);
    $accessToken = $client->authenticate($authToken);

    $session->set('access_token', $accessToken);
    return civicrm_api3_create_success($accessToken, $params);
  } elseif (!empty($accessToken)) {
    return civicrm_api3_create_success($accessToken, $params);
  } else {
    return civicrm_api3_create_success('error', $params);
  }
}

/**
 * GDrive.Userlogout API
 *
 * @param array $params
 * @return array API result descriptor
 * @todo this does not look pretty at all, maybe use a page to perform the logout?
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_g_drive_Userlogout($params) {
  $session = CRM_Core_Session::singleton();
  $session->set('auth_token', NULL);
  $session->set('access_token', NULL);

  return civicrm_api3_create_success('success', $params);
}

/**
 * GDrive.Exportsheet API
 *
 * @param array $params
 * @return array API result descriptor
 * @todo this does not look pretty at all, maybe use a page to perform the logout?
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_g_drive_Exportsheet($params) {
  $session = CRM_Core_Session::singleton();
  $accessToken = $session->get('access_token');

  $config = CRM_Core_Config::singleton();
  $uploadDir = $config->uploadDir;
  $uploadTarget = $uploadDir . 'googleimport.csv';

  $client = new Google_Client();
  $client->setAuthConfigFile(__DIR__ . '/client_secret.json');
  $client->addScope(Google_Service_Drive::DRIVE_READONLY);
  $client->setAccessToken($accessToken);

  // if ($client->isAccessTokenExpired()) {
  //   $client->refreshToken($client->getRefreshToken());
  // }

  $driveService = new Google_Service_Drive($client);

  $fileId = $params['sheet_id'];
  $content = $driveService->files->export($fileId, 'text/csv', array('alt' => 'media' ));
  file_put_contents($uploadTarget, $content);

  return civicrm_api3_create_success($uploadTarget, $params);
}
