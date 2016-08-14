<?php

/**
 * DataSource.Getfirstrow API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_data_source_Getfirstrow($params) {
  // The main purpose of the extension was to allow multiple file format uploads;
  // Mid-term goal can be accomplished using this, but the main goal remains to somehow allow multiple file formats.
  $fileAddress = $params['file_address'];
  $csvFile = fopen($fileAddress, "r");
  $values = fgetcsv($csvFile);

  return civicrm_api3_create_success($values, $params);
}

/**
 * DataSource.Geterrors API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_data_source_Geterrorsfile($params) {
  $errvalues = array();
  $matching = $params['matching'];
  $entityName = $params['entity_name'];
  $rowcount = 0;

  // Call validate API, print errors to CSV file and then return
  // the CSV file and an array with the errors properly formatted.
  $fileAddress = $params['file_address'];
  $csvFile = fopen($fileAddress, "r");

  while ($values = fgetcsv($csvFile)) {
    $rowcount++;
    $validateApiParams['action'] = 'create';
    foreach ($values as $key => $value) {
      $validateApiParams[$matching[$key]]  = $value;
    }
    $validateApiResult = civicrm_api3($entityName, 'validate', $validateApiParams);

    // Format errvalues in a nice way in PHP because frankly I don't know enough
    // Angular to do fancy stuff there.
    foreach ($validateApiResult['values'][0] as $err) {
      $errvalues[$rowcount][] = $err['message'];
    }
  }

  // We have the error values in errvalues, now all we have to do is put
  // them in a CSV file and provide a link to download that file.

  return civicrm_api3_create_success($errvalues, $params);
}
