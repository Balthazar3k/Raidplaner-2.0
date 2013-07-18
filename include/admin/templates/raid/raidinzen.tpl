<form method="post" action="admin.php?raidinzen-create" enctype="multipart/form-data">
	<input type="hidden" name="id" value="NULL">
	<table width="30%" cellpadding="2" cellspacing="1" border="0" class="border left">
		<tr class="Chead">
			<th colspan="2">Neuen Dungeon Erstellen/Bearbeiten</th>
		</tr>
		<tr class="Cnorm">
			<td align="right">Alias:</td>
			<td><input type="text" name="alias"></td>
		</tr>
		<tr class="Cmite">
			<td align="right">Dungeon Name:</td>
			<td><input type="text" name="name"></td>
		</tr>
		<tr class="Cnorm">
			<td align="right">Level:</td>
			<td><input id="intWheel" type="text" name="level" value="{'maxlevel'|cfg}" maxvalue="{'maxlevel'|cfg}" size="2"></td>
		</tr>
		<tr class="Cmite">
			<td align="right">Max. Spieler:</td>
			<td><input id="intWheel" type="text" name="size" value="5" size="2"></td>
		</tr>
		<tr class="Cnorm">
			<td align="right">Image:</td>
			<td>{html_options name=img options=$images}</td>
		</tr>
		<tr class="Cdark">
			<td align="right" valign="top">Beschreibung:</td>
			<td><textarea name="info" rows="5"></textarea></td>
		</tr>
		<tr class="Chead">
			<th colspan="2" align="right"><input type="submit" value="Erstellen"></th>
		</tr>
	</table>
</form>

<table width="68%" cellpadding="3" cellspacing="1" border="0" class="border left">
	<tr class="Chead noMove">
		<th align="center">#id</th>
		<th align="center">Alias</th>
		<th>Name</th>
		<th align="center">Level</th>
		<th align="center">Spieler</th>
		<th align="center">Bild</th>
		<th align="center">info</th>
		<th align="right">Optionen</th>
	</tr>
	{foreach $inzen as $i}
	<tr id="inzen{$i.id}" class="Cmite ubuntu">
		<td align="center">{$i.id}</td>
		<td align="center"><b>{$i.alias}{$i.size}</b></td>
		<td align="center">{$i.name}</td>
		<td align="center">{$i.level}</td>
		<td align="center">{$i.size}</td>
		<td align="center">
			{if $i.img==''}
			{'false'|icon}
			{else}
			<b>
				<a class="group" rel="group1" href="{$i.img}" title="{$i.alias} | {$i.name} (L{$i.level})">
					{if file_exists($i.img)}
					{'1'|icon}
					{else}
					{'!'|icon}
					{/if}
				</a>
			</b>
			{/if}
		</td>
		<td align="center">{$i.info}</td>
		<td align="right">
			<a href="admin.php?raidinzen-jsonData-{$i.id}" json="admin.php?raidinzen-update">{'smart'|icon}</a>
			<a href="admin.php?raidinzen-remove" confirm="confirm" perm="action={{$i|json_encode}|urlencode}" remove="#inzen{$i.id}">{'cancel'|icon}</a>
			<confirm>{status id=2}Wollen Sie wirklich den Dungeon "<b>{$i.alias} - {$i.name}</b>" l&ouml;schen?{/status}</confirm>
		</td>
	</tr>
	{/foreach}
	<tr class="Cdark ubuntu">
		<td colspan="8">Weitere Optionen</td>
	</tr>
	<tr class="Cnorm ubuntu">
		<td colspan="8" align="right">
			<a class="button" href="admin.php?raidinzen-removeAll" confirm="confirm">alle Dungeons l&ouml;schen</a><confirm>{status id=2}Wollen Sie wirklich alle Dungeons <b>unwiederruflich</b> l&ouml;schen?{/status}</confirm>
		</td>
	</tr>
	<tr class="Cdark ubuntu">
		<td colspan="8">Information</td>
	</tr>
	<tr class="Cnorm ubuntu">
		<td colspan="8">
			Es sind {$images|count-2} Bilder auf dem Server. <br />
			Wenn man unter den Bilder den OK Button anklick, bekommt man das Bild angezeigt! <br />
			{foreach from=$images key=key item=item}
				{if file_exists($key)}
				<a class="group" rel="group2" href="{$key}" title="{$item}">{$item|truncate:3:''}</a>
				{/if}
			{/foreach}
		</td>
	</tr>
</table>
<br style="clear: both;" />
