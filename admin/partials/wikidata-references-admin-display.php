<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       zeko3991@gmail.com
 * @since      1.0.0
 *
 * @package    Wikidata_References
 * @subpackage Wikidata_References/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->




<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
	
	<form method="post" name="wiki_references_options" action="options.php">
	<?php
	
	$plugin_url = plugin_dir_url(__FILE__);
	
	
	settings_fields ( $this->plugin_name );
	do_settings_sections ( $this->plugin_name );

	// Grab all setup options
	$options = get_option ( $this->plugin_name );
	
    //microformats enable
	$wkrf_term_microformat_links_enable = isset($options['wkrf_term_microformat_links_enable']) ? $options['wkrf_term_microformat_links_enable'] : null;
	
	//term archive title links enable
	$wkrf_wikidata_tag_title_link_enable      = isset($options['wkrf_wikidata_tag_title_link_enable']) ? $options['wkrf_wikidata_tag_title_link_enable'] : null;
	$wkrf_wikidata_category_title_link_enable = isset($options['wkrf_wikidata_category_title_link_enable']) ? $options['wkrf_wikidata_category_title_link_enable'] : null;
	
	//head meta links enable and examples for description
	$wkrf_wikidata_link_enable = isset($options['wkrf_wikidata_link_enable']) ? $options['wkrf_wikidata_link_enable'] : null;
	$wkrf_wikidata_json_enable = isset($options['wkrf_wikidata_json_enable']) ? $options['wkrf_wikidata_json_enable'] : null;
	$wkrf_wikidata_n3_enable   = isset($options['wkrf_wikidata_n3_enable']) ? $options['wkrf_wikidata_n3_enable'] : null;
	$wkrf_wikidata_nt_enable   = isset($options['wkrf_wikidata_nt_enable']) ? $options['wkrf_wikidata_nt_enable'] : null;
	$wkrf_wikidata_php_enable  = isset($options['wkrf_wikidata_php_enable']) ? $options['wkrf_wikidata_php_enable'] : null;
	$wkrf_wikidata_rdf_enable  = isset($options['wkrf_wikidata_rdf_enable']) ? $options['wkrf_wikidata_rdf_enable'] : null;
	$wkrf_wikidata_ttl_enable  = isset($options['wkrf_wikidata_ttl_enable']) ? $options['wkrf_wikidata_ttl_enable'] : null;
	
	$wkrf_wikidata_link_example = esc_html__('< link rel="describedby" href="https://wikidata.org/entity/Q13166" type="text/html" >');
	$wkrf_wikidata_json_example = esc_html__('< link rel="describedby" href="https://wikidata.org/entity/Q13166.json" type="application/json" >');
	$wkrf_wikidata_n3_example   = esc_html__('< link rel="describedby" href="https://wikidata.org/entity/Q13166.n3" type="text/n3" >');
	$wkrf_wikidata_nt_example   = esc_html__('< link rel="describedby" href="https://wikidata.org/entity/Q13166.nt" type="application/n-triples" >');
	$wkrf_wikidata_php_example  = esc_html__('< link rel="describedby" href="https://wikidata.org/entity/Q13166.php" type="application/vnd.php.serialized" >');
	$wkrf_wikidata_rdf_example  = esc_html__('< link rel="describedby" href="https://wikidata.org/entity/Q13166.rdf" type="application/rdf+xml" >');
	$wkrf_wikidata_ttl_example  = esc_html__('< link rel="describedby" href="https://wikidata.org/entity/Q13166.ttl" type="text/turtle" >');
	
	$wkrf_license_paragraph_1   = esc_html__('This program is free software: you can redistribute it and/or modify
                                              it under the terms of the GNU General Public License as published by
                    						  the Free Software Foundation, either version 3 of the License, or
                     						  (at your option) any later version.');
	
	$wkrf_license_paragraph_2	= esc_html__('This program is distributed in the hope that it will be useful,
                    						 but WITHOUT ANY WARRANTY; without even the implied warranty of
                    						 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
                    						 GNU General Public License for more details.');
	
	$wkrf_license_paragraph_3	= esc_html__('You should have received a copy of the GNU General Public License
                    along with this program.  If not, see ');

	$wkrf_license_url			= '<a target="_blank" href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>.';
		
	
	?>
	
		<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- //////////////////////////////WIKIDATA REFERENCES DESCRIPTION////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	
    	<div class="wkrf-setup  col-md-12 col-xs-12">
    		<!-- <h1 class="title" onClick="wkrf_toggleDiv('wkrf_plugin_description');"><?php //echo _e("Wikidata References"); ?></h1> -->
    		
    		<div id="wkrf_plugin_description">
	    		<div class="row">
		    		<p class="col-md-8 col-xs-10"> <?php esc_html_e('Wikidata references is a plugin developed with the purpose of helping you to add associations among 
		    		your WordPress Tags or Categories and Wikidata Items to add some metadata and microdata into your Website.
		    		It also lets you add a link to the associated Wikidata item at your Tags and Categories\' archive pages.'); ?>
		    		</p>
		    		<div class="col-md-3 col-xs-12 col-xs-offset-6">
		    			<img src="<?php echo $plugin_url; ?>images/Wikidata-logo-en.svg" height="90" width="128" style="display:inline">
		    		</div>
		    	</div>
	    		<div class="row">
	    			<p class="col-md-8 col-xs-10"><?php _e('Here, you can decide which metadata or links you add to your website, to associate your Tags and 
		    			Categories with Wikidata Items, simply go to the <a href="edit-tags.php">WordPress Term editor.</a>'); ?>
		    		</p>
	    		</div>
    		</div>
    	</div>
    	
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- /////////////////////////////META ADDED TO HEAD SECTION//////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->

		<!-- META ADDED TO HEAD, LINKS -->
		<div class="wkrf-setup box col-md-12 col-xs-12" >
					<h1 class="title" onClick="wkrf_toggleDiv('wkrf_meta_added_to_head');"><?php _e('Meta added to head'); ?></h1>
			<div id="wkrf_meta_added_to_head">	
				<p class="col-md-8 col-xs-10"><?php _e('This metadata will be added to your tags and categories archive pages head.'); ?> </p>
    			<!-- LINK -->
    			<div class="wkrf-metadata-form margin-top  col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_link_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_link_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_link_enable]" value="1" <?php checked($wkrf_wikidata_link_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Wikidata item link', $this->plugin_name); ?></span>
    	            	<p class="description"><?php echo $wkrf_wikidata_link_example;?></p>
    	        </label>
    			</div>
    			<!-- JSON -->
    			<div class="wkrf-metadata-form  col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_json_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_json_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_json_enable]" value="1" <?php checked($wkrf_wikidata_json_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Wikidata item json link', $this->plugin_name); ?></span>
    	        		<p class="description"><?php echo $wkrf_wikidata_json_example;?></p>
    	        </label>
    			</div>
    			<!-- n3 -->
    			<div class="wkrf-metadata-form  col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_n3_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_n3_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_n3_enable]" value="1" <?php checked($wkrf_wikidata_n3_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Wikidata item n3 link', $this->plugin_name); ?></span>
    	        		<p class="description"><?php echo $wkrf_wikidata_n3_example;?></p>
    	        </label>
    			</div>
    			<!-- nt -->
    			<div class="wkrf-metadata-form  col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_nt_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_nt_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_nt_enable]" value="1" <?php checked($wkrf_wikidata_nt_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Wikidata item nt link', $this->plugin_name); ?></span>
    	        		<p class="description"><?php echo $wkrf_wikidata_nt_example;?></p>
    	        </label>
    			</div>
    			<!-- php -->
    			<div class="wkrf-metadata-form  col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_php_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_php_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_php_enable]" value="1" <?php checked($wkrf_wikidata_php_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Wikidata item php link', $this->plugin_name); ?></span>
    	        		<p class="description"><?php echo $wkrf_wikidata_php_example;?></p>
    	        </label>
    			</div>
    			<!-- rdf -->
    			<div class="wkrf-metadata-form  col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_rdf_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_rdf_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_rdf_enable]" value="1" <?php checked($wkrf_wikidata_rdf_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Wikidata item rdf link', $this->plugin_name); ?></span>
    	        		<p class="description"><?php echo $wkrf_wikidata_rdf_example;?></p>
    	        </label>
    			</div>
    			<!-- ttl -->
    			<div class="wkrf-metadata-form  col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_ttl_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_ttl_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_ttl_enable]" value="1" <?php checked($wkrf_wikidata_ttl_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Wikidata item ttl link', $this->plugin_name); ?></span>
    	        		<p class="description"><?php echo $wkrf_wikidata_ttl_example;?></p>
    	        </label>
    			</div>.
    		</div>
		</div>
		
		
		<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- /////////////////////////////LINKS ADDED TO TERM ARCHIVE TITLE//////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->

		<div class="wkrf-setup box col-md-12 col-xs-12">
			<h1 class="title" onClick="wkrf_toggleDiv('wkrf_plugin_title_link_section');"><?php echo _e("Link to Wikidata"); ?></h1>
    		<div id="wkrf_plugin_title_link_section">
    			<p class="col-md-8 col-xs-10"><?php echo __('This will add a link to the associated Wikidata item at your tag and categories archives\'s titles'); ?> </p>
	    		<!-- tag archive title link enable -->
    			<div class="wkrf-metadata-form margin-top col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_tag_title_link_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_tag_title_link_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_tag_title_link_enable]" value="1" <?php checked($wkrf_wikidata_tag_title_link_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Add link to Wikidata Item at tag archive titles', $this->plugin_name); ?></span>
    	            	<p class="description"><?php echo __('Add link to archive title at ').get_site_url().'/tag/[tag-name]';?></p>
    	        </label>
    			</div>
    			<!-- category archive title link enable -->
    			<div class="wkrf-metadata-form col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_wikidata_category_title_link_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_wikidata_category_title_link_enable" name="<?php echo $this->plugin_name; ?>[wkrf_wikidata_category_title_link_enable]" value="1" <?php checked($wkrf_wikidata_category_title_link_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Add link to Wikidata Item at category archive titles', $this->plugin_name); ?></span>
    	            	<p class="description"><?php echo __('Add link to archive title at ').get_site_url().'/category/[category-name]';?></p>
    	        </label>
    			</div>
    		</div>
    	</div>
    	
    	
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- /////////////////////////////LINKS ADDED TO TERM ARCHIVE TITLE//////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->

		<div class="wkrf-setup box col-md-12 col-xs-12">
			<h1 class="title" onClick="wkrf_toggleDiv('wkrf_microformats_section');"><?php echo _e("Schema.org frame"); ?></h1>
    		<div id="wkrf_microformats_section">
    			<p class="col-md-8 col-xs-10"><?php echo __('This will frame your tag and categories links into a microformat schema,
                                                                following Schema.org, adding info about the associated Wikidata Item'); ?> </p>
    			<!-- schema.org microformat enable -->
    			<div class="wkrf-metadata-form col-xl-10 col-md-10 col-xs-12 input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-wkrf_term_microformat_links_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-wkrf_term_microformat_links_enable" name="<?php echo $this->plugin_name; ?>[wkrf_term_microformat_links_enable]" value="1" <?php checked($wkrf_term_microformat_links_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Frame tags and categories links into a Schema.org schema', $this->plugin_name); ?></span>
    	            	<p class="description"><?php echo __('&ltspan itemscope itemtype="http://schema.org/Thing"&gt <br>
                                                                 &emsp; &lta itemprop="sameAs" href="'.get_site_url().'/tag/[tag-name]" rel="tag"&gt[tag-name]&lt/a&gt <br>
                                                                &lt/span&gt');?> </p>
    	        </label>
    			</div>
    		</div>
    	</div>
		
		
		
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- //////////////////////////////SAVE SETTINGS//////////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->


		<div class="wkrf-setup box">
			<h1 class="title"> <?php echo _e("Save settings"); ?></h1>
			<div class="margin-top">
				<?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>
			</div>
		</div>
		
		<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- ////////////////////////////////////LICENSE//////////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->

		<div class="wkrf-setup col-md-12 col-xs-12">
			<div class="offset-md-5 offset-sm-5">
			<h1 class="title" ><?php echo _e("License"); ?></h1>
			</div>
    		<div class="offset-md-2 col-md-8 offset-xs-1 col-xs-10">
    			<p class="row">
        			<?php echo $wkrf_license_paragraph_1; ?>
                </p>
                <p>
                    <?php echo $wkrf_license_paragraph_2; ?>
                </p>
                <p>
                    <?php echo $wkrf_license_paragraph_3.$wkrf_license_url; ?>
    			</p>
    		</div>
    	</div>
		
	</form>

</div>





<div class="wrap">
	<fieldset>
		<legend class="screen-reader-text"><span><?php _e('Edit post', $this->plugin_name)?></span></legend>
		
	</fieldset>

</div>