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

  angular.module('civiimport').controller('CiviimportImport', function($window, $scope, crmApi, crmStatus, crmUiHelp, allEntities, FileUploader) {
    // The ts() and hs() functions help load strings for this module.
    var ts = $scope.ts = CRM.ts('civiimport');
    var hs = $scope.hs = crmUiHelp({file: 'CRM/civiimport/Import'});

    // Determine whether the user is logged into Google Drive or not.
    // TODO: This piece feels kinda shady to me, fix when you have the time.
    $scope.isUserLoggedIn = false;
    crmApi('GDrive', 'Getlogincred').then(function(data) {
      if (data.values != 'error') {
        $scope.accessToken = data.values;
        $scope.isUserLoggedIn = true;
      }
    });

    // Use this to logout from Google Drive.
    $scope.logoutUser = function() {
      crmApi('GDrive', 'Userlogout').then(function(data) {
        if (data.values == 'success') {
          $scope.isUserLoggedIn = false;
          $scope.accessToken = NULL;
          CRM.alert('Logged out.');
        } else {
          CRM.alert('Logout was not successful.');
        }
      });
    }

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

    // Google Drive functionality.
    $scope.getAuthUrl = function() {
      var result = crmApi('GDrive', 'Getauthurl');
      result.then(function(data) {
        $window.location.href = data.values;
      });
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
