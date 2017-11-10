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
<!-- https://www.sitepoint.com/create-a-wordpress-theme-settings-page-with-the-settings-api/ -->




<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
	
	<form method="post" name="wiki_references_options" action="options.php">
	<?php
	
	settings_fields ( $this->plugin_name );
	do_settings_sections ( $this->plugin_name );

	
	// Grab all tags
	$tags = get_tags ();
	// Grab all setup options
	$options = get_option ( $this->plugin_name );
	
	
	
	$metadata_enable = isset($options['metadata_enable']) ? $options['metadata_enable'] : null;
	$tag_title_link_enable = isset($options['tag_title_link_enable']) ? $options['tag_title_link_enable'] : null;
	$metadata_posts_enable = isset($options['metadata_posts_enable']) ? $options['metadata_posts_enable'] : null;
	$metadata_tags_enable = isset($options['metadata_tags_enable']) ? $options['metadata_tags_enable'] : null;
	
	// Wiki references options
	$references_by_tag = isset($options ['references_by_tag']) ? $options ['references_by_tag']: null;
	$references_footnote = isset($options ['references_footnote']) ? $options ['references_footnote'] : null;
	


	
	
	// format selection
	$ieee_format = isset($options['ieee_format']) ? $options ['ieee_format'] : 0;
	$harvard_format = isset($options ['harvard_format']) ? $options ['harvard_format'] : 0;
	$simple_format = isset($options ['simple_format']) ? $options ['simple_format'] : 0;

	if (! isset ( $options ['ieee_format'] ) && ! isset ( $options ['harvard_format'] ) && ! isset ( $options ['simple_format'] )) {
		$ieee_format = 1;
		$options ['ieee_format'] = 1;
		$options ['harvard_format'] = 0;
		$options ['simple_format'] = 0;
	}
		
		
	?>
	
		<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- ///////////////////////////////////ORDINARY METADATA/////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
		
		
		<div class="wkrf-setup">
			
    			<!-- CHECKBOX TO ACTIVATE METADATA OPTION -->
    			<div class="wkrf-metadata-form margin-top col-xl-10 col-xl-offset-2  col-md-10 col-md-offset-2  col-xs-12 left input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-metadata_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-metadata_enable" name="<?php echo $this->plugin_name; ?>[metadata_enable]" value="1" <?php checked($metadata_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Add this metadata to my website\'s head', $this->plugin_name); ?></span>
    	        </label>
    			</div>
    			<div class="wkrf-metadata-form col-xl-10 col-xl-offset-2  col-md-10 col-md-offset-2  col-xs-12 left input-group input-group-sm" >
        				<label for="<?php echo $this->plugin_name; ?>-metadata_posts_enable">
    	           			 <input type="checkbox" id="<?php echo $this->plugin_name; ?>-metadata_posts_enable" name="<?php echo $this->plugin_name; ?>[metadata_posts_enable]" value="1" <?php checked($metadata_posts_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Add this metadata to my posts', $this->plugin_name); ?></span>
    	        </label>
    			</div>
		</div>
		<hr>
	
		
		
    	
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- ///////////////////////////////////FORMATOS//////////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	
    	
    	<?php /**
    	<h2><?php echo __("Formatos")?></h2>
    	
    	
    	<div class="wkrf-setup">
	    	<fieldset>
	    		<h3><?php echo __("IEEE Referencing System")?></h3>
	    		<legend class="screen-reader-text">
	    			<span><?php echo __('Seleccionar formato de referencias IEEE')?></span>
	    		</legend>
	    		<input type="radio" id="<?php echo $this->plugin_name; ?>-ieee_format" 
	    					name="<?php echo $this->plugin_name; ?>[ieee_format]"
	    					<?php checked($ieee_format, 1)?> 
	    					onclick="updateIeeeFormat(this);" 
	    		/>
	    		<label for="<?php echo $this->plugin_name; ?>-ieee_format">
	    			<?php echo __(' [1] "Term", <i>Wikidata</i>, 2015. [Online]. 
										Available: <a href="https://www.wikidata.org/wiki/Q2013">
										https://www.wikidata.org/wiki/Q2013</a>.
										Accessed: 24-jul-2017', $this->plugin_name); ?>
				</label>
	    		
	    	</fieldset>
	    	
	    	<fieldset>
	    		<h3><?php echo __("Harvard Referencing System")?></h3>
	    		<legend class="screen-reader-text">
	    			<span><?php echo __('Seleccionar formato de referencias Harvard')?></span>
	    		</legend>
	    		<input type="radio" id="<?php echo $this->plugin_name; ?>-harvard_format"
	    					name="<?php echo $this->plugin_name; ?>[harvard_format]" 
	    					<?php checked($harvard_format, 1)?> 
	    					onclick="updateHarvardFormat(this);"
	    		/>
	    		<label for="<?php echo $this->plugin_name; ?>-harvard_format">
	    			<?php echo __('  "Term", <i>Wikidata</i>, Wikimedia Foundation. 19 September 2017.
										Viewed 21 September 2017, 
										from <a href="https://www.wikidata.org/wiki/Q2013">
										https://www.wikidata.org/wiki/Q2013</a>.'
										, $this->plugin_name); ?>
				</label>
	    		
	    	</fieldset>
	    	
	
	    	<fieldset>
	    		<h3><?php echo __("Simple")?></h3>
	    		<legend class="screen-reader-text">
	    			<span><?php echo __('Seleccionar formato de referencias simple')?></span>
	    		</legend>
	    		<input type="radio" id="<?php echo $this->plugin_name; ?>-simple_format" 
	    					name="<?php echo $this->plugin_name; ?>[simple_format]"
	    					<?php checked($simple_format, 1)?> 
	    					onclick="updateSimpleFormat(this);" 
	    		/>
	    		<label for="<?php echo $this->plugin_name; ?>-simple_format">
	    			<?php echo __(' "Term", 
									<a href="https://www.wikidata.org/wiki/Q2013">
									https://www.wikidata.org/wiki/Q2013</a>.
									Accessed: 24-jul-2017', $this->plugin_name); ?>
				</label>
	    		
	    	</fieldset>
	    	<br>
	    	<hr>
    	</div>
    	*/?>

		<div class="wkrf-setup">
			<?php submit_button('Save all changes', 'primary', 'submit', TRUE); ?>
		</div>
		
	</form>

</div>





<div class="wrap">
	<fieldset>
		<legend class="screen-reader-text"><span><?php _e('Edit post', $this->plugin_name)?></span></legend>
		
	</fieldset>

</div>