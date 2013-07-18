<form method="post" action="admin.php?raidstatus-create" enctype="multipart/form-data">
	<input type="hidden" name="id" value="NULL">
	<table width="40%" cellpadding="2" cellspacing="1" border="0" class="border left ubuntu">
		<tr class="Chead">
			<th colspan="2">Neuen Dungeon Erstellen/Bearbeiten</th>
		</tr>
		{if is_admin()}
		<tr class="Cnorm">
			<td align="right">Status Typ:</td>
			<td>{html_options name=sid options=$sid}</td>
		</tr>
		{else}
		<tr class="Cnorm">
			<td align="right">Status Typ:</td>
			<td>nur Admin <input type"hidden" name="sid" value="sid" /></td>
		</tr>
		{/if}
		<tr class="Cmite">
			<td align="right">Status Nachricht:</td>
			<td><input type="text" name="status"></td>
		</tr>
		<tr class="Cnorm">
			<td align="right">Farbe:</td>
			<td><input id="colorpicker" type="text" name="color"></td>
		</tr>
		<tr class="Cdark">
			<td align="right" valign="top">Style:</td>
			<td><textarea style="width: 100%; font-size: 10px; font-family: ubuntu, Tahoma;" name="style" rows="5"></textarea></td>
		</tr>
		<tr class="Chead">
			<th colspan="2" align="right"><input type="submit" value="Erstellen"></th>
		</tr>
	</table>
</form>

<table width="58%" cellpadding="3" cellspacing="1" border="0" class="border left">
	<tr class="Chead noMove">
		<th align="center">#id</th>
		<th align="center">Nachricht</th>
		<th align="center" colspan="2">Beispiele</th>
		<th align="center">Typ</th>
		<th align="right">Optionen</th>
	</tr>
	{assign var="kat" value=""}
	{foreach $status as $i}
	{if $kat != $i.sid}
	{assign var="kat" value=$i.sid}
	<tr class="Cdark ubuntu">
		<td colspan="6"><b>{$i.sid}</b></td>
	</tr>
	{/if}
	<tr id="inzen{$i.id}" class="Cmite ubuntu">
		<td align="center">{$i.id}</td>
		<td align="left">{$i.status}</td>
		<td align="center" style="background-color: {$i.color};"></td>
		<td align="center" style="{$i.style}">{$i.status}</td>
		<td align="center">{$i.sid}</td>
		<td align="right">
			<a href="admin.php?raidstatus-jsonData-{$i.id}" json="admin.php?raidstatus-update">{'smart'|icon}</a>
		</td>
	</tr>
	{/foreach}
	<tr class="Cdark ubuntu">
		<td colspan="6">Information</td>
	</tr>
	<tr class="Cnorm ubuntu">
		<td colspan="6">
			&Auml;nderungen k&ouml;nnten zu Fehlern f&uuml;hren!
		</td>
	</tr>
</table>
<br style="clear: both;" />
