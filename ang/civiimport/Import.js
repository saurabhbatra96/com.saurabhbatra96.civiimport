(function(angular, $, _) {

  angular.module('civiimport').config(function($routeProvider) {
      $routeProvider.when('/import', {
        controller: 'CiviimportImport',
        templateUrl: '~/civiimport/Import.html',
        resolve: {
        	preLoad: function(crmApi) {
        		return crmApi('DataSource', 'Getpreload');
        	}
        }
      });
    }
  );

  angular.module('civiimport').controller('CiviimportImport', function($window, $scope, crmApi, crmStatus, crmUiHelp, preLoad, FileUploader) {
    // The ts() and hs() functions help load strings for this module.
    var ts = $scope.ts = CRM.ts('civiimport');
    var hs = $scope.hs = crmUiHelp({file: 'CRM/civiimport/Import'});

    $scope.isDsFormValid = false;
    $scope.isMatchFormValid = false;
    $scope.isImportValid = false;
    $scope.dsFormValid = function() { return $scope.isDsFormValid; }
    $scope.matchFormValid = function() { return $scope.isMatchFormValid; }
    $scope.importValid = function() { return $scope.isImportValid; }

    // Determine whether the user is logged into Google Drive or not.
    // TODO: This piece feels kinda shady to me, fix when you have the time.
    $scope.isUserLoggedIn = false;
    crmApi('GDrive', 'Getlogincred').then(function(data) {
      if (data.values != 'error') {
        $scope.isUserLoggedIn = true;
      }
    });

    // Use this to logout from Google Drive.
    $scope.logoutUser = function() {
      crmApi('GDrive', 'Userlogout').then(function(data) {
        if (data.values == 'success') {
          $scope.isUserLoggedIn = false;
          CRM.alert('Logged out.');
        } else {
          CRM.alert('Logout was not successful.');
        }
      });
    }

    // Use this to log the filename and name of the entity being imported.
    $scope.allEntities = preLoad;
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
      $scope.isDsFormValid = true;
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

    // This exports the Google Sheet as a CSV to the uploads directory.
    $scope.sheetId = '';
    $scope.exportSheet = function() {
      var params = {
        'sheet_id': '1QRH0vP8q3R4s82H0F7I8xpL9zLQ0EBkN8Q26EIPE3bM'
      };

      crmApi('GDrive', 'Exportsheet', params).then(function(data) {
        CRM.alert('Finished exporting Google Sheet');
        var params = {'file_address': data.values};
        var result = crmApi('DataSource', 'Getfirstrow', params);
        result.then(function(data) {
          $scope.firstRow = data.values;
        });
      });
    }

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
        if (data.values.iserror != 1) {
          $scope.isError = false;
        } else {
          $scope.isError = true;
        }
        $scope.errors = data.values.errvalues;
        $scope.skiprows = data.values.skiprows;
        $scope.totalRowsNo = data.values.total_rows;
        $scope.validRowsNo = data.values.valid_rows;

        $scope.isMatchFormValid = true;
      });
    }

    $scope.import = function() {
      var params = {
        'file_address': $scope.fileAddress,
        'matching': $scope.matching,
        'skiprows': $scope.skiprows,
        'entity_name': files.entityName,
      }
      crmApi('DataSource', 'import', params).then(function(data) {
        $scope.rowsImported = data.values.rows_imported;
      });

      $scope.isImportValid = true;
    }
  });

})(angular, CRM.$, CRM._);
