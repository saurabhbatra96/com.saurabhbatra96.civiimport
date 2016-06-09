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

    $scope.uploader = new FileUploader({
      onSuccessItem: function onSuccessItem(item, response, status, headers) {
        CRM.alert("File upload complete.");
      }
    });

  });

})(angular, CRM.$, CRM._);
