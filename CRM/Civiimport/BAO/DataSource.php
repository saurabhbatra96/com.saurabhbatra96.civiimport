<?php

class CRM_Civiimport_BAO_DataSource extends CRM_Civiimport_DAO_DataSource {

  /**
   * Create a new DataSource based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Civiimport_DAO_DataSource|NULL
   *
  public static function create($params) {
    $className = 'CRM_Civiimport_DAO_DataSource';
    $entityName = 'DataSource';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */
}
