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
  // TODO: Shift to BAO

  // The main purpose of the extension was to allow multiple file format uploads;
  // Mid-term goal can be accomplished using this, but the main goal remains to somehow allow multiple file formats.
  $fileAddress = $params['file_address'];
  $csvFile = fopen($fileAddress, "r");
  $firstRow = fgetcsv($csvFile);

  $data = array(
    'row' => $firstRow
  );

  return $data;
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
  $errno = 0;
  $errvalues = array();
  // Let's select those fields which are required and throw errors based on that.
  // This will go to a separate function once this is shifted to the BAO.
  $entityFields = civicrm_api3($params['entity_name'], 'getfields');
  foreach($entityFields['values'] as $field) {
    if (isset($field['required']) && $field['required'] && !in_array($field['name'], $params['matching'])) {
      $errno++;
      array_push($errvalues, $field['title'] . " is a required field, you need to fill it out.");
    }
  }

  $errors = array(
    'values' => $errvalues,
    'errno' => $errno
  );


  return $errors;
}
