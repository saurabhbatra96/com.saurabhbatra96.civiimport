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
  // Ideally the code here should re-direct to the BAO and should result in the file being read.
  // Until I figure out how to do that, let's just return dummy data.
  $data = array(
    'row' => array('abcd', 'efgh', 'ijkl')
  );

  return $data;
}
