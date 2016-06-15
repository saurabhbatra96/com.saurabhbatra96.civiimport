<?php

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
