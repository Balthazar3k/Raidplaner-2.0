<table id="eventZeiten" width="10%" border="0" cellspacing="1" cellpadding="2" class='border'>
	<tr class="Chead">
		<th>*anz</th>
		<th>info</th>
		<th>Invite</th>
		<th>Pull</th>
		<th>Ende</th>
		<th>Close</th>
		<th></th>
	</tr>
	<tr class="Cdark">
		<td colspan="7">bearbeiten der EventZeiten</td>
	</tr>
	
	<form id="standart" class="reloadRaidZeiten" name="form" method="post" action="admin.php?raidzeiten-update">
	{foreach $row as $val}
	<tr class="Cmite" id="eventZeitRemove{$val.id}">
		<td align="center" width="20" class="Cdark"><b>{$val.anz}<b></td>
		<td>
			<input type="hidden" name="id[]" value="{$val.id}"">
			<input type="text" name="info[]" value="{$val.info}" size="40">
		</td>
		<td><input id="timeWheel" type="text" name="start[]" value="{$val.start}" size="5"></td>
		<td><input id="timeWheel" type="text" name="begin[]" value="{$val.begin}" size="5"></td>
		<td><input id="timeWheel" type="text" name="ende[]" value="{$val.ende}" size="5"></td>
		<td><input id="intWheel" type="text" name="sperre[]" value="{$val.sperre}" size="2"></td>
		<td><a href="admin.php?raidzeiten-delete" confirm="Wirklich L&ouml;schen?" perm="id={$val.id}" remove="#eventZeitRemove{$val.id}">{'cancel'|icon}</a></td>
	</tr>
	{/foreach}
	<tr class="Cdark">
		<td colspan="7" align="right"><input type="Submit" value="Speichern"></td>
	</tr>
	</form>
	
	<tr class="Chead">
		<th>*</th>
		<th>info</th>
		<th>Invite</th>
		<th>Pull</th>
		<th>Ende</th>
		<th>Close</th>
		<th></th>
	</tr>
	<tr class="Cdark">
		<td colspan="7">neue Eventzeit eintragen | Uhrzeit kann mit dem Mausrad eingestellt werden</td>
	</tr>
	<tr class="Cmite">
		<form id="standart" name="form" method="post" action="admin.php?raidzeiten-create" refresh="admin.php?raidzeiten">
		<td></td>
		<td><input type="text" name="info" value="" size="40"></td>
		<td><input id="timeWheel" type="text" name="start" value="18:00" size="5"></td>
		<td><input id="timeWheel" type="text" name="begin" value="18:00" size="5"></td>
		<td><input id="timeWheel" type="text" name="ende" value="18:00" size="5"></td>
		<td><input type="text" name="sperre" value="2" size="2"></td>
		<td></td>
	</tr>
	<tr class="Cdark">
		<td colspan="7" align="right"><input type="Submit" value="Speichern"></td>
		</form>
	</tr>
</table>
<p>
	*anz: Spieler die um diese Zeit k&ouml;nnen.
</p>
