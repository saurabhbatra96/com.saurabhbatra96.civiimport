<?php

class CRM_Civiimport_Page_GDrive {
  public function run() {
    $session = CRM_Core_Session::singleton();

    $authToken = $_GET['code'];
    $session->set('auth_token', $authToken);

    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/a/#/import'));
  }
}
