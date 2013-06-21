<form method="post" action="admin.php?raidgruppen-create" enctype="multipart/form-data">
	<input type="hidden" name="id" value="NULL">
	<table width="30%" cellpadding="2" cellspacing="1" border="0" class="border left">
		<tr class="Chead">
			<th colspan="2">Neue Gruppe Erstellen</th>
		</tr>
		<tr class="Cmite">
			<td align="right">Name:</td>
			<td><input type="text" name="name"></td>
		</tr>
		<tr class="Cnorm">
			<td align="right">Leader:</td>
			<td>{html_options name=leader options=$leader}</td>
		</tr>
		<tr class="Cmite">
			<td align="right">Sichtbar ab:</td>
			<td>{html_options name=recht options=$recht}</td>
		</tr>
		<tr class="Cnorm">
			<td align="right">Image:</td>
			<td>{html_options name=img options=$images}</td>
		</tr>
		<tr class="Cdark">
			<td align="right" valign="top">Beschreibung:</td>
			<td><textarea name="beschreibung" rows="5"></textarea></td>
		</tr>
		<tr class="Chead">
			<th colspan="2" align="right"><input type="submit" value="Erstellen"></th>
		</tr>
	</table>
</form>

<form id="standart" method="post" action="admin.php?raidgruppen-sortable">
	<table width="68%" cellpadding="0" cellspacing="1" border="0" class="border left sortable">
		<tr class="Chead noMove">
			<th colspan="2">Neue Gruppe Erstellen</th>
		</tr>
		{foreach $grp as $i}
		<tr id="gruppen{$i.id}" class="Cmite gruppen" style="background: url({$i.img}) no-repeat center center;" tooltip="#gruppenToolTip{$i.id}">
			<td>
				<h1>{$i.name}</h1>
				<div class="gruppenDetails">
					<div class="left">{$i.date|date_format:"%A %d.%m.%Y"}</div>
					<div class="right">
						<a href="admin.php?raidgruppen-jsonData-{$i.id}" json="admin.php?raidgruppen-update">{'smart'|icon}</a>
						<a href="admin.php?raidgruppen-remove" confirm="Wollen Sie wirklich diese Gruppe L&ouml;Schen?" perm="name={$i.name}&img={$i.img}" remove="#gruppen{$i.id}">{'cancel'|icon}</a>
					</div>
					<input type="hidden" name="pos[]" value="{$i.id}" />
					<br style="clear: both;" />
				</div>
				<div class="hide" id="gruppenToolTip{$i.id}">
					<table border="0">
						<tr>
							<td align="right">Leader:</td>
							<td align="left">{$i.leader}</td>
						</tr>
						<tr>
							<td align="right">Sichtbar ab:</td>
							<td align="left">{$i.rechtname}</td>
						</tr>
						<tr>
							<td align="right">Erstellt von:</td>
							<td align="left">{$i.ersteller}</td>
						</tr>
						<tr>
							<td align="right">am:</td>
							<td align="left">{$i.date|date_format:"%A %d.%m.%Y"}</td>
						</tr>
						<tr>
							<td colspan="2">{$i.beschreibung}</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
		{/foreach}
		<tr class="Chead noMove">
			<td colspan="2" align="right"><input type="submit" value="Speichern" /></td>
		</tr>
	</table>
</form>
<br style="clear: both;" />