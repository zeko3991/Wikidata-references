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
	
	
	//Utilities class instance 
	require_once ('wikidata-references-admin-utilities.php');
	$utilities = new Wikidata_References_Utilities ();
	
	// Grab all tags
	$tags = get_tags ();
	// Grab all setup options
	$options = get_option ( $this->plugin_name );
	
	//metadata options
	$author = isset($options['author_meta']) ? $options['author_meta'] : null;
	$copyright = isset($options['copyright_meta']) ? $options['copyright_meta'] : null;
	$subject = isset($options['subject_meta']) ? $options['subject_meta'] : null;
	$description = isset($options['description_meta']) ? $options['description_meta'] : null;
	$keywords = isset($options['keywords_meta']) ? $options['keywords_meta'] : null;
	
	
	$metadata_enable = isset($options['metadata_enable']) ? $options['metadata_enable'] : null;
	$tag_title_link_enable = isset($options['tag_title_link_enable']) ? $options['tag_title_link_enable'] : null;
	$metadata_posts_enable = isset($options['metadata_posts_enable']) ? $options['metadata_posts_enable'] : null;
	$metadata_tags_enable = isset($options['metadata_tags_enable']) ? $options['metadata_tags_enable'] : null;
	
	// Wiki references options
	$references_by_tag = isset($options ['references_by_tag']) ? $options ['references_by_tag']: null;
	$references_footnote = isset($options ['references_footnote']) ? $options ['references_footnote'] : null;
	

	
	// array of tags-wiki ids values
	$wikidata_ids_by_tags = array ();
	$wikidata_descriptions_by_tags = array ();
	
	foreach ( $tags as $elem ) {
		// $name = wkrf_sanitize_tag_name($elem->name);
		$name = $utilities->wkrf_sanitize_tag_name ( $elem->name );
		if (isset ( $options ['tag-' . $name] )) {
			$wikidata_ids_by_tags ['tag-' . $name] = $options ['tag-' . $name];
		}
		
		if (isset ( $options ['description-' . $name] )) {
			$wikidata_descriptions_by_tags ['description-' . $name] = $options ['description-' . $name];
		}
	}
	
	
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
			<h2 class="wkrf-setup-title"><?php echo _e("Metadata")?></h2> 	
    		 	<!-- AUTHOR -->
    			<div class="wkrf-metadata-form col-xl-12  col-md-12  col-xs-12 left input-group input-group-sm"  >
        				<label class="col-md-3 col-xs-12 " >Author</label>
        				<input id="<?php echo $this->plugin_name.'_author_meta';?>" type="text" class="col-md-4 col-xs-12"
        					   name="<?php echo $this->plugin_name; ?>[author_meta]" 
        					   title="<?php echo _("metadata Author's name"); ?>"
        					   placeholder="author"
        					   value="<?php if(isset($author)){ echo $author; } ?>">				
    			</div>
    			<!-- COPYRIGHT -->
    			<div class="wkrf-metadata-form col-xl-12  col-md-12  col-xs-12 left input-group input-group-sm"  >
        				<label class="col-md-3 col-xs-12 " >Copyright</label>
        				<input id="<?php echo $this->plugin_name.'_copyright_meta';?>" type="text" class="col-md-4 col-xs-12"
        					   name="<?php echo $this->plugin_name; ?>[copyright_meta]" 
        					   title="<?php echo _("metadata copyright information"); ?>"
        					   value="<?php if(isset($copyright)){ echo $copyright; } ?>"
        					   placeholder="copyright information">				
    			</div>
    			<!-- SUBJECT -->
    			<div class="wkrf-metadata-form col-xl-12  col-md-12  col-xs-12 left input-group input-group-sm"  >
        				<label class="col-md-3 col-xs-12 " >Subject</label>
        				<input id="<?php echo $this->plugin_name.'_subject_meta';?>" type="text" class="col-md-4 col-xs-12"
        					   name="<?php echo $this->plugin_name; ?>[subject_meta]" 
        					   title="<?php echo _("metadata website's subject"); ?>"
        					   placeholder="website's subject"
        					   value="<?php if(isset($subject)){ echo $subject; } ?>">				
    			</div>
    			<!-- DESCRIPTION -->
    			<div class="wkrf-metadata-form col-xl-12  col-md-12  col-xs-12 left input-group input-group-sm" >
        				<label class="col-md-3 col-xs-12 " >Description</label>
        				<input id="<?php echo $this->plugin_name.'_description_meta';?>" type="text" class="col-md-4 col-xs-12"
        					   name="<?php echo $this->plugin_name; ?>[description_meta]" 
        					   title="<?php echo _("metadata web description"); ?>"
        					   placeholder="description"
        					   value="<?php if(isset($description)){ echo $description; } ?>">
    			</div>
    			<!-- KEYWORDS -->
    			<div class="wkrf-metadata-form col-xl-12  col-md-12  col-xs-12 left input-group input-group-sm" >
        				<label class="col-md-3 col-xs-12 " >Keywords</label>
        				<input id="<?php echo $this->plugin_name.'_keywords_meta';?>" type="text" class="col-md-4 col-xs-12"
        					   name="<?php echo $this->plugin_name; ?>[keywords_meta]" 
        					   title="<?php echo _("keywords of your web"); ?>"
        					   placeholder="keywords, separated by commas"
        					   value="<?php if(isset($keywords)){ echo $keywords; } ?>">
    			</div>
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
    	<!-- ///////////////////////////////////ASSOCIATIONS//////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
		
		<div class="wkrf-setup">
		
		<h2 class="wkrf-setup-title"><?php echo _e("Wikidata Associations")?></h2>
	    <span class="help-block"><?php echo __("Set a Wikidata ID to your tags and categories") ?></span>  
	    <p>
			 
		<?php 
			foreach ($tags as $elem){
				$name = $utilities->wkrf_sanitize_tag_name($elem->name);
				$search_name = $utilities->wkrf_sanitize_search_term($elem->name);
				$tag_link = get_tag_link($elem->term_id);
				$tag_id = $this->plugin_name.'_tag_'.$name;
				$tag_name = $this->plugin_name.'[tag-'.$name.']';
				$description_id = $this->plugin_name.'_description_'.$name;
				$description_name = $this->plugin_name.'[description-'.$name.']';
				
				//$tag_post_value = get_post_meta($post->ID, '_'.$name, true);
				wp_nonce_field( 'save_'.$name, $name.'_nonce');
				?>
				
		   <div class="wkrf-tag-form col-xl-4 col-md-4 col-xs-12 left input-group input-group-sm"  >
				<label class="col-md-7 col-xs-7" ><a target="_blank" href="<?php echo $tag_link; ?>" > <?php echo $elem->name; ?></a> </label>
				<!-- WIKI ID -->
				<input id="<?php echo $tag_id; ?>"
					name="<?php echo $tag_name; ?>"
					type="text" class="col-md-4 col-xs-4" placeholder="Wikidata ID#"
					title="<?php if(isset($wikidata_descriptions_by_tags['description-'.$name])){ 
						echo $wikidata_descriptions_by_tags['description-'.$name]; }
						else{ echo ""; }; ?>"
					value="<?php if(isset($wikidata_ids_by_tags['tag-'.$name])){ echo $wikidata_ids_by_tags['tag-'.$name]; } ?>">
				<!-- WIKI DESCRIPTION -->
				<input id="<?php echo $description_id; ?>"
					name="<?php echo $description_name; ?>"
					type="hidden" 
					value= "<?php if(isset($wikidata_descriptions_by_tags['description-'.$name])){ 
						echo $wikidata_descriptions_by_tags['description-'.$name]; }
						else{ echo ""; }; ?>">
				<span  title="Look for a wikidata item related to the term <?php echo $elem->name;?>" 
					class="input-group-addon wkrf-association-icon " style="cursor:pointer" 
					onclick="wkrf_modal_selection('<?php echo $search_name; ?>', '<?php echo $tag_id; ?>')">
						<i class="fa fa-search"></i>
				</span>
			</div>
				
			
				<?php 
			}
				?>
				<!-- CHECKBOX TO ACTIVATE METADATA OPTION -->
					
    			<div class="wkrf-metadata-form margin-top col-xl-10 col-xl-offset-2  col-md-10 col-md-offset-2  col-xs-12 left input-group input-group-sm" >
        			<label for="<?php echo $this->plugin_name; ?>-tag-title-link-checkbox">
    	                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-tag_title_link_enable" name="<?php echo $this->plugin_name; ?>[tag_title_link_enable]" value="1" <?php checked($tag_title_link_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Add a link to wikidata on tag archive\'s title', $this->plugin_name); ?></span>
    	       		</label>
    			</div>
    			<div class="wkrf-metadata-form col-xl-10 col-xl-offset-2  col-md-10 col-md-offset-2  col-xs-12 left input-group input-group-sm" >
        			<label for="<?php echo $this->plugin_name; ?>-metadata_tags_enable">
    	                <input type="checkbox" id="<?php echo $this->plugin_name; ?>-metadata_tags_enable" name="<?php echo $this->plugin_name; ?>[metadata_tags_enable]" value="1" <?php checked($metadata_tags_enable, 1); ?> />
    	            	<span><?php esc_attr_e('Add wikidata link to tag\'s metadata', $this->plugin_name); ?></span>
    	       		</label>
    			</div>
    	
		</div>
		<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<!-- ///////////////////////////////////REFERENCIAS///////////////////////////////////// -->
    	<!-- /////////////////////////////////////////////////////////////////////////////////// -->
    	<?php /**
    	
		<div class="wkrf-setup">
		<br>
		<hr>
		<h2 class="wkrf-setup-title"><?php echo __("Referencias")?></h2>
		<!-- Activates/deactivates references by tag -->
    	
    	<fieldset>
	        <legend class="screen-reader-text">
	            <span><?php __("Activar referencias por etiquetas del post")?></span>
	        </legend>
	        <label for="<?php echo $this->plugin_name; ?>-references_by_tag">
	            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-references_by_tag" name="<?php echo $this->plugin_name; ?>[references_by_tag]" value="1" <?php checked($references_by_tag, 1); ?> />
	            <span><?php esc_attr_e('Activar referencias por etiquetas del post', $this->plugin_name); ?></span>
	        </label>
    	</fieldset>
    	
    	<!--  Activates/deactivates footnotes -->
    	<fieldset>
	        <legend class="screen-reader-text">
	            <span><?php __("Activar notas al pie")?></span>
	        </legend>
	        <label for="<?php echo $this->plugin_name; ?>-references_footnote">
	            <input type="checkbox" id="<?php echo $this->plugin_name; ?>-references_footnote" name="<?php echo $this->plugin_name; ?>[references_footnote]" value="1" <?php checked($references_footnote, 1); ?> />
	            <span><?php esc_attr_e('Activar notas al pie', $this->plugin_name); ?></span>
	        </label>
    	</fieldset>
    	<br>
    	<hr>
    	</div>
    	
    	*/?>
    	
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


<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<!-- ///////////////////////////////////MODAL WINDOW//////////////////////////////////// -->
<!-- /////////////////////////////////////////////////////////////////////////////////// -->
<!-- The Modal -->
<div class="wrap">
	
	<div id="wkrf-modal-window" class="modal">
	  <!-- Modal content -->
	  <div id="wkrf-modal-window-content" class="modal-content  col-md-8 col-md-offset-2 col-xs-10 col-xs-offset-1">
	    <span id="wkrf-close" class="close col-md-12" onclick="wkrf_modal_selection_close()">&times;</span>
	    <!-- <p>Some text in the Modal..</p> -->
		    <div class="wkrf-modal-list-header col-md-12 row">
		    	<div class="col-md-3 col-xs-3"><h6>Tag name</h6></div>	
		    	<div class="col-md-2 col-xs-2"><h6>Wikidata ID#</h6></div>	 
		    	<div class="col-md-7 col-xs-7"><h6>Description </h6></div>   
		    </div>

		
	  </div>
	
	</div>
</div>



<div class="wrap">
	<fieldset>
		<legend class="screen-reader-text"><span><?php _e('Edit post', $this->plugin_name)?></span></legend>
		
	</fieldset>

</div>