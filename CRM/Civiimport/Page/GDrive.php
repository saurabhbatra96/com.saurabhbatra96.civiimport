<?php

class CRM_Civiimport_Page_GDrive {
  public function run() {
    $session = CRM_Core_Session::singleton();

    $accessToken = $_GET['code'];
    $session->set('access_token', $accessToken);

    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/a/#/import'));
  }
}
