<?php

/**
 * DataSource.create API
 *
 * @param array $params
 * @return array API result descriptor
 * @throws API_Exception
 */
function civicrm_api3_data_source_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}

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
  // This code belongs to the BAO layer, shift later.
  //
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
  // This code belongs to the BAO layer, shift later.

  $errors = array(
    'values' => array(
      'someval',
      'some other val'
      ),
    'errno' => 2
  );


  return $errors;
}
