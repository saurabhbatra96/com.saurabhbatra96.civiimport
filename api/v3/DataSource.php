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
function civicrm_api3_data_source_Geterrors($params) {
  $errvalues = array();
  // call validate API, print errors to CSV file
  // and then return the CSV file.
  return civicrm_api3_create_success($errvalues, $params);
}
