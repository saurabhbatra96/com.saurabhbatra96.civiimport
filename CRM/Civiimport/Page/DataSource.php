<?php

class CRM_Civiimport_Page_DataSource {
  public static function run() {
    $config = CRM_Core_Config::singleton();
    $uploadDir = $config->uploadDir;
    $uploadTarget = $uploadDir . $_FILES['file']['name'];

    move_uploaded_file($_FILES["file"]["tmp_name"], $uploadTarget);

    $result = $uploadTarget;
    CRM_Utils_JSON::output($result);
  }
}
