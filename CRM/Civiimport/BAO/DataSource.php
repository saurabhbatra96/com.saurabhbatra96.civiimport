<?php

class CRM_Civiimport_BAO_DataSource extends CRM_Civiimport_DAO_DataSource {

  public static function Getfirstrow($params) {
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

  public static function Geterrors($params) {
    $errors = array(
      'values' => array(
        'someval',
        'some other val'
        ),
      'errno' => 2
    );


    return $errors;
  }
}
