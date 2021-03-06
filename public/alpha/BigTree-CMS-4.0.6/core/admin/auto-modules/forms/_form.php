<div class="container">
	<form method="post" action="<?=$bigtree["form_root"]?>process/" enctype="multipart/form-data" class="module" id="auto_module_form">
		<input type="hidden" id="preview_field" name="_bigtree_preview" />
		<input type="hidden" name="MAX_FILE_SIZE" value="<?=BigTree::uploadMaxFileSize()?>" />
		<input type="hidden" name="_bigtree_post_check" value="success" />
		<?
			if (isset($bigtree["entry"])) {
		?>
		<input type="hidden" name="id" value="<?=htmlspecialchars($bigtree["edit_id"])?>" />
		<?
			}	
			if (isset($_GET["view_data"])) {
		?>
		<input type="hidden" name="_bigtree_return_view_data" value="<?=htmlspecialchars($_GET["view_data"])?>" />
		<?	
			}
		?>
		<section>
			<p class="error_message" style="display: none;">Errors found! Please fix the highlighted fields before submitting.</p>
			<?
				if ($_SESSION["bigtree_admin"]["post_max_hit"]) {
					unset($_SESSION["bigtree_admin"]["post_max_hit"]);
			?>
			<p class="warning_message">The file(s) uploaded exceeded the web server's maximum upload size. If you uploaded multiple files, try uploading one at a time.</p>
			<?
				}
				$bigtree["datepickers"] = array();
				$bigtree["timepickers"] = array();
				$bigtree["datetimepickers"] = array();
				$bigtree["html_fields"] = array();
				$bigtree["simple_html_fields"] = array();
				$bigtree["tabindex"] = 1;

				foreach ($bigtree["form"]["fields"] as $key => $resource) {
					if (is_array($resource)) {
						$field = array();
						// Leaving some variable settings for backwards compatibility — removing in 5.0
						$field["title"] = $title = $resource["title"];
						$field["subtitle"] = $subtitle = $resource["subtitle"];
						$field["key"] = $key;
						$field["value"] = $value = isset($bigtree["entry"][$key]) ? $bigtree["entry"][$key] : "";
						$field["id"] = uniqid("field_");
						$field["tabindex"] = $tabindex = $bigtree["tabindex"];
						$field["options"] = $options = $resource;

						// Setup Validation Classes
						$label_validation_class = "";
						$field["required"] = false;
						if (isset($resource["validation"]) && $resource["validation"]) {
							if (strpos($resource["validation"],"required") !== false) {
								$label_validation_class = ' class="required"';
								$field["required"] = true;
							}
						}

						// Give many to many its information
						if ($resource["type"] == "many-to-many") {
							$field["value"] = isset($bigtree["many-to-many"][$key]) ? $bigtree["many-to-many"][$key]["data"] : false;
						}

						$field_type_path = BigTree::path("admin/form-field-types/draw/".$resource["type"].".php");
						
						if (file_exists($field_type_path)) {
			?>
			<fieldset>
				<?
					if ($field["title"] && $resource["type"] != "checkbox") {
				?>
				<label<?=$label_validation_class?>><?=$field["title"]?><? if ($field["subtitle"]) { ?> <small><?=$field["subtitle"]?></small><? } ?></label>
				<?
					}
					include $field_type_path;
				?>
			</fieldset>
			<?
							$bigtree["tabindex"]++;
						}
					}
				}

				if ($bigtree["form"]["tagging"]) {
			?>
			<div class="tags" id="bigtree_tag_browser">
				<fieldset>
					<label>Tags<span></span></label>
					<ul id="tag_list">
						<? foreach ($bigtree["tags"] as $tag) { ?>
						<li><input type="hidden" name="_tags[]" value="<?=$tag["id"]?>" /><a href="#"><?=$tag["tag"]?><span>x</span></a></li>
						<? } ?>
					</ul>
					<input type="text" name="tag_entry" id="tag_entry" />
					<ul id="tag_results" style="display: none;"></ul>
				</fieldset>
			</div>
			<script>
				BigTreeTagAdder.init("bigtree_tag_browser");
			</script>
			<?
				}
			?>
		</section>
		<footer>
			<? if (isset($bigtree["related_view"]) && $bigtree["related_view"]["preview_url"]) { ?>
			<a class="button save_and_preview" href="#">
				<span class="icon_small icon_small_computer"></span>
				Save &amp; Preview
			</a>
			<? } ?>
			<input type="submit" class="button<? if ($bigtree["access_level"] != "p") { ?> blue<? } ?>" tabindex="<?=$bigtree["tabindex"]?>" value="Save" name="save" />
			<input type="submit" class="button blue" tabindex="<?=($bigtree["tabindex"] + 1)?>" value="Save & Publish" name="save_and_publish" <? if ($bigtree["access_level"] != "p") { ?>style="display: none;" <? } ?>/>
		</footer>
	</form>
</div>
<?
	if (count($bigtree["html_fields"]) || count($bigtree["simple_html_fields"])) {
		$bigtree["js"][] = "tiny_mce/tiny_mce.js";

		if (count($bigtree["html_fields"])) {
			include BigTree::path("admin/layouts/_tinymce_specific.php");
		}
		if (count($bigtree["simple_html_fields"])) {
			include BigTree::path("admin/layouts/_tinymce_specific_simple.php");
		}
	}
?>
<script>
	<?
		foreach ($bigtree["datepickers"] as $id) {
	?>
	$("#<?=$id?>").datepicker({ duration: 200, showAnim: "slideDown" });
	<?
		}

		foreach ($bigtree["timepickers"] as $id) {
	?>
	$("#<?=$id?>").timepicker({ duration: 200, showAnim: "slideDown", ampm: true, hourGrid: 6, minuteGrid: 10 });
	<?
		}
		
		foreach ($bigtree["datetimepickers"] as $id) {
	?>
	$("#<?=$id?>").datetimepicker({ duration: 200, showAnim: "slideDown", ampm: true, hourGrid: 6, minuteGrid: 10 });
	<?
		}
	?>
	
	new BigTreeFormValidator("#auto_module_form");
	
	$(".save_and_preview").click(function() {
		$("#preview_field").val("true");
		$(this).parents("form").submit();

		return false;
	});

	<? if ($bigtree["access_level"] == "p" || !$bigtree["edit_id"]) { ?>
	$(".gbp_select").change(function() {
		access_level = $(this).find("option").eq($(this).get(0).selectedIndex).attr("data-access-level");
		if (access_level == "p") {
			$("input[name=save]").removeClass("blue");
			$("input[name=save_and_publish]").show();
		} else {
			$("input[name=save]").addClass("blue");
			$("input[name=save_and_publish]").hide();
		}
	});
	$(window).load(function() {
		$(".gbp_select").trigger("change");
	});
	<? } ?>
</script>