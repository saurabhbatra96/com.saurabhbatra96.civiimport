(function(angular, $, _) {

  angular.module('civiimport').config(function($routeProvider) {
      $routeProvider.when('/datasource', {
        controller: 'CiviimportDataSource',
        templateUrl: '~/civiimport/DataSource.html',

        // If you need to look up data when opening the page, list it out
        // under "resolve".

      });
    }
  );

  // The controller uses *injection*. This default injects a few things:
  //   $scope -- This is the set of variables shared between JS and HTML.
  //   crmApi, crmStatus, crmUiHelp -- These are services provided by civicrm-core.
  angular.module('civiimport').controller('CiviimportDataSource', function($scope, crmApi, crmStatus, crmUiHelp) {
    // The ts() and hs() functions help load strings for this module.
    var ts = $scope.ts = CRM.ts('civiimport');
    var hs = $scope.hs = crmUiHelp({file: 'CRM/civiimport/DataSource'}); // See: templates/CRM/civiimport/DataSource.hlp
  });

})(angular, CRM.$, CRM._);
