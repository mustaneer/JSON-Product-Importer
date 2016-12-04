<?php
/*
 Template Name: Product Home Template
 */
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/product_style.css" />
<link href="https://fonts.googleapis.com/css?family=Play" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Russo+One" rel="stylesheet">
<script   src="https://code.jquery.com/jquery-1.12.4.js"   integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="   crossorigin="anonymous"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-route.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular-sanitize.min.js"></script>
<script>var home_url = "<?php echo esc_url( home_url( '/' ) ) ?>"; </script>
<script src="<?=plugin_dir_url( __FILE__ ) ?>../../public/js/infinite.js"></script>
<script src="<?=plugin_dir_url( __FILE__ ) ?>../../public/js/app.js"></script>
	<header class="site-header" role="banner">
		<div class="wrapper">
			<div class="container">
				<div class="h1 header-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<h1> Trade Tracker Product Feeds</h1>
					</a>
				</div>
			</div>
		</div>
	</header>
	<section class="productContainer container" ng-app="myApp" ng-controller="Main">
		<div id="productsRow " class="row " ng-if="posts.items">
			<div class="col-md-12 col-sm-12" infinite-scroll='posts.nextPage()' infinite-scroll-disabled='posts.busy' infinite-scroll-distance='1'>
				<div class="col-md-12 col-sm-12">
					<h2>Feeds Categories:</h2>
					<select ng-change="posts.nextPage()"  ng-model="posts.myOption" class="feed_categories">
						<option value="">All Categories</option>
						<option ng-repeat="category in categories" value="{{category.slug}}" ng-bind-html="category.name" ></option>
					</select>
					{{categoriesnot}}
					<h2>{{posts.pageTitle}}</h2>
				</div>
				<section class="singleProduct col-md-3 col-sm-12" ng-repeat="post in posts.items" on-finish-render="ngRepeatFinished">
					<div class="productWrap">
						<div class="feed_category">
							<strong>Category :<p ng-repeat="(key, val) in post.terms.feedproduct_category"> {{val.name}} </p></strong>
						</div>
						<div class="product text-center">
							<img class="img-responsive center-block" ng-src="{{post.featuredurl}}" alt="{{post.alt}}" />
						</div>
						<article class="productContent">
							<h3><a href="{{post.link}}" ng-bind-html="post.title"></a></h3>
							<!--<p ng-bind-html="post.excerpt"></p>-->
						</article>
						<div class="price_brand">
							<span class="feed_price"> {{post.price}} {{post.currency}}</span><span class="feed_brand">Brand : {{post.brand}}</span>
						</div>
						<div class="productOverlay">
							<a class="productPerview" href="{{post.link}}" title="{{post.title}}"> Show Details </a>
						</div>
					</div>
				</section>
				<h2 class="loadmore">{{posts.pageResult}}</h2>
				<div class="clearboth"></div>
				<div ng-show='posts.busy' class="loadmore">Loading More Products ...</div>
			</div>
		</div>
	</section>
