(function(angular, $, _) {

  angular.module('civiimport').config(function($routeProvider) {
      $routeProvider.when('/import', {
        controller: 'CiviimportImport',
        templateUrl: '~/civiimport/Import.html',
        resolve: {
        	allEntities: function(crmApi) {
        		return crmApi('Entity', 'get');
        	}
        }
      });
    }
  );

  angular.module('civiimport').controller('CiviimportImport', function($scope, crmApi, crmStatus, crmUiHelp, allEntities, FileUploader) {
    // The ts() and hs() functions help load strings for this module.
    var ts = $scope.ts = CRM.ts('civiimport');
    var hs = $scope.hs = crmUiHelp({file: 'CRM/civiimport/Import'});

    // Use this to log the filename and name of the entity being imported.
    $scope.allEntities = allEntities;
    var files = {entityName: "", fileName: ""}
    $scope.files = files;

    // When the selected entity is changed, get the associated fields.
    $scope.changeEntity = function() {
      var result = crmApi(files.entityName, 'getfields');
      result.then(function(data) {
        $scope.entityFields = data;
      });
    }

    $scope.userIsLoggedIn = function() {
      return true;
    }

    // Call this after upload of file is finished.
    var afterUpload = function afterUpload(response) {
      $scope.fileAddress = response;
      CRM.alert('Finished uploading.');
      var params = {'file_address': response};
      var result = crmApi('DataSource', 'Getfirstrow', params);
      result.then(function(data) {
        $scope.firstRow = data.values;
      });
    }

    // Use this to upload the CSV file.
    $scope.uploader = new FileUploader({
      url: CRM.url('civicrm/civiimport/datasource'),
      onSuccessItem: function onSuccessItem(item, response, status, headers) {
        afterUpload(response);
      }
    });

    // This array stores the mappings the user dictates.
    $scope.matching = [];

    $scope.useMapping = function() {
      // Perform validation.
      // IF there are errors, populate an array with them.

      var params = {
        'file_address': $scope.fileAddress,
        'matching': $scope.matching,
        'entity_name': files.entityName
      };
      var result = crmApi('DataSource', 'Geterrors', params);
      result.then(function(data) {
        $scope.err = data;

        if (data.count == 0) {
          $scope.validationtext = "No errors detected. Click import now to import records.";
        } else {
          $scope.validationtext = "CiviCRM has detected "+ data.count +" fatal error(s) in your import and listed them below. Please go back and fix them before continuing.";
          // $scope.validationtext = "CiviCRM has detected invalid data or formatting errors in "+ data.errno +" records. If you continue, these records will be skipped.";
        }

      });
    }

    $scope.isError = function() {
      if ($scope.err.count == 0) {
        return false;
      } else {
        return true;
      }
    }

  });

})(angular, CRM.$, CRM._);
