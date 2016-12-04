var myapp = angular.module( 'myapp', ['ngSanitize'] );
// Set the configuration
myapp.run( ['$rootScope', function($rootScope) {
 // Variables defined by wp_localize_script
 $rootScope.post_id = post_id;
 $rootScope.home_url = home_url;
}]);
// Add a controller
myapp.controller( 'mycontroller', ['$scope', '$http', function( $scope, $http ) {
 // Load posts from the WordPress API
	$http({
		method: 'GET',
		url: $scope.home_url+"wp-json/posts/"+$scope.post_id
	}).success( function( data, status, headers, config ) {
		
		
		$scope.posts = data;
		
		$scope.string = data.images;
        $scope.arrString = new Array();
        $scope.arrString = $scope.string.split(',');

	}).error(function(data, status, headers, config) {});
}]);