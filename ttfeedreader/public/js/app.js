var myApp = angular.module('myApp', ['ngRoute', 'ngSanitize','infinite-scroll']);

myApp.run( ['$rootScope', function($rootScope) {
 $rootScope.home_url = home_url;
}]);
//Main controller
myApp.controller('Main', ['$scope', '$http','Posts', function($scope, $http, Posts) {
	$http.get($scope.home_url+'wp-json/taxonomies/feedproduct_category/terms').success(function(res){
		if(Object.keys(res).length > 0){
		  for (var i = 0; i < Object.keys(res).length; i++) {
			res[i].name = res[i].name + "  (" + res[i].count +")";
		  }
		  $scope.categoriesnot = "";
		} else {
		  $scope.categoriesnot = "No Categories Found!";
		}
		$scope.categories = res;
	});
	$scope.posts = new Posts();
	
}]);
myApp.factory('Posts', function($http) {
  var Posts = function() {
    this.items = [];
    this.busy = false;
    this.page = 1;
    this.optionChange = "";
    this.myOption = "";
    this.pageResult = "";
  };
  Posts.prototype.nextPage = function() {
	this.pageResult = "";
    if (this.busy) return;
	if(this.optionChange != this.myOption){
		this.page = 1;
		this.items = [];
	}
    this.busy = true;
	  $http.get('wp-json/posts?type=jsonfeeds&post_status=publish&page='+ this.page +'&posts_per_page=12&filter[posts_per_page]=12&filter[taxonomy]=feedproduct_category&filter[term]='+this.myOption).success(function(data){
	  var items = data;
	  if(Object.keys(items).length > 0){
		  for (var i = 0; i < Object.keys(data).length; i++) {
			this.items.push(data[i]);
		  }
	  } else {
		  if(this.page == 1)
		  this.pageResult = "No More Product Found!";
	  }
     
	  this.optionChange = this.myOption;
	  if(this.myOption == ""){
		  this.pageTitle = 'All Feed Product Posts:';
	  } else {
		  this.pageTitle = this.myOption.replace(/-/g , " ") + ' Posts';
	  }
      this.page = parseInt(this.page) + 1;
      this.busy = false;
    }.bind(this));
  };
  return Posts;
});