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

  // Let's select those fields which are required and throw errors based on that.
  // TODO: Not sure if this is how we get the required values out of the API.
  $entityFields = civicrm_api3($params['entity_name'], 'getfields');
  foreach($entityFields['values'] as $field) {
    if (isset($field['required']) && $field['required'] && !in_array($field['name'], $params['matching'])) {
      array_push($errvalues, $field['title'] . " is a required field, you need to fill it out.");
    }
  }

  return civicrm_api3_create_success($errvalues, $params);
}
