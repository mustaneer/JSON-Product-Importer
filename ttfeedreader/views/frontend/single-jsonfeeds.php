<?php
 /*
 Template Name: Product Feed Template
 */
$post_id = get_the_ID();
include_once(plugin_dir_path( __FILE__ ) ."../../models/feedreader_database.php");

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<link rel="stylesheet" href="<?=plugin_dir_url( __FILE__ ) ?>../../public/css/product_style.css" />
<link href="https://fonts.googleapis.com/css?family=Play" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Russo+One" rel="stylesheet">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
<script   src="https://code.jquery.com/jquery-1.12.4.js"   integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU="   crossorigin="anonymous"></script>
<script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.0rc1/angular-route.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular-sanitize.min.js"></script>

<script>var post_id = "<?php echo $post_id; ?>"; </script>
<script>var home_url = "<?php echo esc_url( home_url( '/' ) ) ?>"; </script>
<script src="<?=plugin_dir_url( __FILE__ ) ?>../../public/js/app-single.js"></script>
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
    <section class="container singlePage" ng-app="myapp">
		<!--<a href="" class="previous_link page_links">Previous Product </a>
		<a href="" class="next_link page_links">Next Product </a>-->
		<div class="clearboth"></div>
		<div ng-controller="mycontroller" class="alloallwrapper">
		<article class="row" ng-if="posts">
			<div class="col-sm-12 col-md-5">
			  <div class="col-sm-12">
				<div class="productImage" id="productPhoto">
				  <img class="img-responsive" id="productPhotoImg" src="{{posts.largeimage}}" alt="{{posts.title}}">
				</div>
				  <ul class="productphotosmall" id="productThumbs">
					  <li ng-repeat="image in arrString track by $index">
						<a class="product-photo-thumb">
						  <img src="{{image}}" alt="Small Image">
						</a>
					  </li>
				  </ul>
			  </div>
			  <div class="clearboth"></div>
			</div>
			<div class="col-sm-12 col-md-7">
				<article class="feed_title">
					<h3 ng-bind-html="posts.title"></h3>
				</article>
				<span class="feed_price">{{posts.price}} <p class="currency">{{posts.currency}}</p></span>
				<span class="feed_brand">Brand : <strong>{{posts.brand}}</strong></span>
				<hr class="hrsmall">
				<span class="feed_category"><strong>Category : </strong><p ng-repeat="(key, val) in posts.terms.feedproduct_category"> {{val.name}} </p></span>
				<span class="feed_date"><strong>Date : </strong><p> {{ posts.date | date : 'medium'}}</p></span>
				<hr class="hrsmall">
				<article class="feed_detail">
					<h4>Description :</h4>
					<p ng-bind-html="posts.content"></p>
				</article>
				<div class="clearboth"></div>
				<section class="allmeta">
					<table class="table table-bordered">
					  <tbody>
						<tr>
						  <th><strong>Stock</strong></th>
						  <td>{{posts.stock}}</td>
						  <th><strong>Product Type</strong></th>
						  <td><span class="producttype">{{posts.type}}</span></td>
						</tr>
						<tr>
						  <th><strong>Delivery Cost</strong></th>
						  <td>{{posts.deliverycost}}</td>
						  <td><strong>Delivery Time</strong></td>
						  <td>{{posts.deliverytime}}</td>
						</tr>
						<tr>
						  <th><strong>EAN</strong></th>
						  <td>{{posts.ean}}</td>
						  <td><strong>SKU</strong></td>
						  <td>{{posts.sku}}</td>
						</tr>
					  </tbody>
					</table>
				</section>
				<div class="social-sharing is-default" data-permalink="{{posts.link}}">
					<a target="_blank" href="//www.facebook.com/sharer.php?u={{posts.link}}" class="share-facebook">
					    <i class="fa fa-facebook" aria-hidden="true"></i>
					    <span class="share-title">Share</span>
					</a>
					<a target="_blank" href="//twitter.com/share?url={{posts.link}}&amp;text={{posts.excerpt}}" class="share-twitter">
					    <i class="fa fa-twitter" aria-hidden="true"></i>
					    <span class="share-title">Tweet</span> 
					</a>
					<a target="_blank" href="//pinterest.com/pin/create/button/?url={{posts.link}}&amp;media={{posts.largeimage}}?v={{posts.ID}}&amp;description={{posts.excerpt}}" class="share-pinterest">
						<i class="fa fa-pinterest" aria-hidden="true"></i>
						<span class="share-title">Pin it</span>
					</a>
					<a target="_blank" href="//plus.google.com/share?url={{posts.link}}" class="share-google">
						<i class="fa fa-google" aria-hidden="true"></i>
						<span class="share-title">Google +</span>
					</a>
				</div>
			</div>
			</article>
		</div>
	</section>