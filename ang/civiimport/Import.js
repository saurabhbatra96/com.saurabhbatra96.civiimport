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
    var hs = $scope.hs = crmUiHelp({file: 'CRM/civiimport/Import'}); // See: templates/CRM/civiimport/DataSource.hlp

    $scope.allEntities = allEntities;

    var files = {entityName: "", fileName: ""}
    $scope.files = files;

    $scope.changeEntity = function() {
      var result = crmApi(files.entityName, 'getfields');
      result.then(function(data) {
        $scope.entityFields = data;
      });
    }

    var afterUpload = function afterUpload() {
      CRM.alert('Finished uploading.');
      var params; // This should send the file resource/address somehow.
      var result = crmApi('DataSource', 'Getfirstrow', params);
      result.then(function(data) {
        $scope.firstRow = data.row;
      });
    }

    $scope.uploader = new FileUploader({
      onSuccessItem: function onSuccessItem(item, response, status, headers) {
        afterUpload();
      }
    });

    $scope.matching = [];

    $scope.saveMapping = function() {
      console.log($scope.matching);
    }

  });

})(angular, CRM.$, CRM._);
