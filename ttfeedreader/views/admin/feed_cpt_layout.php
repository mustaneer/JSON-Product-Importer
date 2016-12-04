<div class="wrap">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <!-- main content -->
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <!-- Toggle -->
                        <h2 class="hndle"><span><strong><?php esc_attr_e( 'Feed Product Attributes'); ?></strong></span>
                        </h2>
                        <div class="inside">
							<label for="feed_id"> <strong> <?php esc_attr_e('Feed Product ID'); ?> </strong></label><br />
                            <input type="text" name="feed[id]" value="<?= $fieldLists['id']?>" placeholder="<?php esc_attr_e('Feed Product ID'); ?>" class="all-options" id="feed_id" /><br /><br />
							<label for="feed_url"> <strong> <?php esc_attr_e('Feed Product URL'); ?> </strong></label><br />
							<input type="text" name="feed[url]" value="<?= $fieldLists['url']?>" placeholder="<?php esc_attr_e('Feed Product URL'); ?>" class="regular-text" id="feed_url" /><br /><br />
                        </div>
                        <!-- .inside -->
                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables .ui-sortable -->
            </div>
            <!-- post-body-content -->
            <!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <!-- Toggle -->
                        <h2 class="hndle"><span><strong><?php esc_attr_e('Feed Product Price &amp; Currency'); ?></strong></span></h2>
                        <div class="inside">
							<label for="feed_price"> <strong><?php esc_attr_e('Price'); ?>  </strong></label><br />
                            <input type="text" value="<?= $fieldLists['price']?>" name="feed[price]" placeholder="<?php esc_attr_e('Feed Product Price'); ?>" class="all-options" id="feed_price"/><br /><br />
							<strong> <?php esc_attr_e('Currency'); ?> </strong><br />
							<?php foreach($currency as $currency): 
							if($currency){
							?>
							<label title='Currency'>
								<input type="radio" name="feed[currency]" value="<?= $currency ?>"  <?php checked( $currency, $fieldLists['currency'], $echo = TRUE ); ?> />
								<span><?= $currency ?></span>
							</label><br /><br />
							<?php }
							endforeach; ?>							
						</div>
                        <!-- .inside -->
                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables -->
            </div>
            <!-- #postbox-container-1 .postbox-container -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<div class="handlediv" title="Click to toggle" aria-expanded="true"><br></div>
						<!-- Toggle -->
						<h2 class="hndle ui-sortable-handle"><span><strong>Feed Product Properties</strong></span>
						</h2>
						<div class="inside">
							<label for="feed_brand"> <strong> <?php esc_attr_e('Feed Product Brand'); ?> </strong></label><br>
							<select name="feed[brand]">
								<?php 
								if(!empty($brands)){
								foreach($brands as $brand): ?>
									<option value="<? echo $brand; ?>" <?php selected( $brand, $fieldLists['brand'], TRUE ); ?>><?= $brand ?></option>
								<?php endforeach; 
								}?>
							</select><br>
							
							<label for="feed_type"> <strong><?php esc_attr_e('Feed Product Type'); ?> </strong></label><br>
							<input type="text" name="feed[type]" value="<?= $fieldLists['type']?>" placeholder="<?php esc_attr_e('Feed Product Type'); ?> " class="regular-text" id="feed_type"><br><br>
							
							<label for="feed_sku"> <strong><?php esc_attr_e('Feed SKU (Stock Keeping Unit)'); ?> </strong></label><br>
							<input type="text" name="feed[sku]" value="<?= $fieldLists['sku']?>" placeholder="<?php esc_attr_e('Feed Product SKU'); ?> " class="regular-text" id="feed_sku"><br><br>
							
							<label for="feed_stock"> <strong><?php esc_attr_e('Feed Product Stock'); ?> </strong></label><br>
							<input type="text" id="feed_stock" value="<?= $fieldLists['stock']?>" name="feed[stock]" placeholder="<?php esc_attr_e('Feed Product Stock'); ?> " class="regular-text" ><br><br>
							
							<label for="feed_ean"> <strong><?php esc_attr_e('Feed EAN (European Article Number)'); ?> </strong></label><br>
							<input type="text" id="feed_ean" value="<?= $fieldLists['ean']?>" name="feed[ean]" placeholder="<?php esc_attr_e('Feed EAN (European Article Number)'); ?> " class="regular-text" ><br><br>
							
							<label for="feed_thumbnail"> <strong><?php esc_attr_e('Feed Product Thumbnail URL'); ?> </strong></label><br>
							<input type="text" id="feed_thumbnail" name="feed[thumbnail]" value="<?= $fieldLists['thumbnail']?>" placeholder="<?php esc_attr_e('Feed Product Thumbnail URL'); ?> " class="regular-text" ><br><br>
							
							<label for="feed_largeimage"> <strong><?php esc_attr_e('Feed Large Image URL '); ?> </strong></label><br>
							<input type="text" id="feed_largeimage" name="feed[largeimage]" value="<?= $fieldLists['largeimage']?>" placeholder="<?php esc_attr_e('Feed Large Image URL'); ?> " class="regular-text" ><br><br>
							
							<label for="feed_images"> <strong><?php esc_attr_e('Feed Product Images URLs'); ?> </strong></label><br>
							<textarea id="feed_images" name="feed[images]" cols="80" rows="10" class="large-text" placeholder="<?php esc_attr_e('Feed Product Images'); ?>"><?= $fieldLists['images']?></textarea><br>
						</div>
						<!-- .inside -->
					</div>
					<!-- .postbox -->
				</div>
			</div>
			<!-- post-body-content -->
			<!-- sidebar -->
            <div id="postbox-container-1" class="postbox-container">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle"><br></div>
                        <!-- Toggle -->
                        <h2 class="hndle"><span><strong><?php esc_attr_e('Feed Product Delivery'); ?></strong></span></h2>
                        <div class="inside">
							<label for="feed_deliverycost"> <strong><?php esc_attr_e('Delivery Cost'); ?>  </strong></label><br />
                            <input type="text" name="feed[deliverycost]" value="<?= $fieldLists['deliverycost']?>" placeholder="<?php esc_attr_e('Delivery Cost'); ?>" class="all-options" id="feed_deliverycost"/><br /><br />
							
							<label for="feed_deliverytime"> <strong><?php esc_attr_e('Delivery Time'); ?>  </strong></label><br />
                            <input type="text" name="feed[deliverytime]" value="<?= $fieldLists['deliverytime']?>" placeholder="<?php esc_attr_e('Delivery Time'); ?>" class="all-options" id="feed_deliverytime"/><br /><br />
						</div>
                        <!-- .inside -->
                    </div>
                    <!-- .postbox -->
                </div>
                <!-- .meta-box-sortables -->
            </div>
            <!-- #postbox-container-1 .postbox-container -->
        </div>
        <!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div>
    <!-- #poststuff -->
</div> <!-- .wrap -->