<?
	$groups = $admin->getModuleGroups();
	foreach ($groups as &$group) {
		$group["modules"] = $admin->getModulesByGroup($group["id"]);
	}
	
	$ungrouped_modules = $admin->getModulesByGroup(0);

	foreach ($groups as $g) {
		if (count($g["modules"])) {
?>
<div class="table">
	<summary>
		<a href="<?=$developer_root?>foundry/package/choose-files/group/<?=$g["id"]?>/" class="icon_small icon_small_export"></a>
		<h2><?=$g["name"]?></h2>
	</summary>
	<header>
		<span class="developer_modules_name">Module Name</span>
		<span class="view_action" style="width: 120px;">Actions</span>
	</header>
	<ul id="group_<?=$g["id"]?>">
		<? foreach ($g["modules"] as $item) { ?>
		<li id="row_<?=$item["id"]?>">
			<section class="developer_modules_name">
				<span class="icon_sort"></span>
				<a href="<?=$section_root?>edit/<?=$item["id"]?>/"><?=$item["name"]?></a>
			</section>
			<section class="view_action">
				<a href="<?=$developer_root?>foundry/package/choose-files/module/<?=$item["id"]?>/" class="icon_export"></a>
			</section>
			<section class="view_action">
				<a href="<?=$section_root?>edit/<?=$item["id"]?>/" class="icon_edit"></a>
			</section>
			<section class="view_action">
				<a href="<?=$section_root?>delete/<?=$item["id"]?>/" class="icon_delete"></a>
			</section>
		</li>
		<? } ?>
	</ul>
</div>

<script>
	$("#group_<?=$g["id"]?>").sortable({ axis: "y", containment: "parent", handle: ".icon_sort", items: "li", placeholder: "ui-sortable-placeholder", tolerance: "pointer", update: function() {
		$.ajax("<?=ADMIN_ROOT?>ajax/developer/order-modules/", { type: "POST", data: { sort: $("#group_<?=$g["id"]?>").sortable("serialize") } });
	}});
</script>
<?
		}
	}
	
	if (count($ungrouped_modules)) {
?>
<div class="table">
	<summary>
		<h2>Ungrouped Modules</h2>
	</summary>
	<header>
		<span class="developer_modules_name">Module Name</span>
		<span class="view_action" style="width: 120px;">Actions</span>
	</header>
	<ul id="group_0">
		<? foreach ($ungrouped_modules as $item) { ?>
		<li id="row_<?=$item["id"]?>">
			<section class="developer_modules_name">
				<span class="icon_sort"></span>
				<a href="<?=$section_root?>edit/<?=$item["id"]?>/"><?=$item["name"]?></a>
			</section>
			<section class="view_action">
				<a href="<?=$developer_root?>foundry/package/choose-files/module/<?=$item["id"]?>/" class="icon_export"></a>
			</section>
			<section class="view_action">
				<a href="<?=$section_root?>edit/<?=$item["id"]?>/" class="icon_edit"></a>
			</section>
			<section class="view_action">
				<a href="<?=$section_root?>delete/<?=$item["id"]?>/" class="icon_delete"></a>
			</section>
		</li>
		<? } ?>
	</ul>
</div>
<?
	}
?>

<script>
	$("#group_0").sortable({ axis: "y", containment: "parent", handle: ".icon_sort", items: "li", placeholder: "ui-sortable-placeholder", tolerance: "pointer", update: function() {
		$.ajax("<?=ADMIN_ROOT?>ajax/developer/order-modules/", { type: "POST", data: { sort: $("#group_0").sortable("serialize") } });
	}});

	$(".icon_delete").click(function() {
		new BigTreeDialog("Delete Module",'<p class="confirm">Are you sure you want to delete this module?',$.proxy(function() {
			document.location.href = $(this).attr("href");
		},this),"delete",false,"OK");
		
		return false;
	});
</script>