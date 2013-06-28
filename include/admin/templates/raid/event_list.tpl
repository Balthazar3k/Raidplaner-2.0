<table width="100%" border="0" cellspacing="1" cellpadding="2" class='border'>
	<tr>
		<td class="Cdark" colspan="7">
			{perm authmod=createEvent}<a class="button" href="admin.php?raid-create" fancybox="inline">Event Erstellen</a>{/perm}
			<a class="button" href="admin.php?raid-kalender" kalender="">Kalender Ansicht</a>
			<div style="float: right;">
				{perm authmod=removeEvent}
					<a class="button" href="admin.php?raid-delAll" confirm="ACHTUNG! Es werden alle Events und Anmeldungen aus der Datenbank unwiederruflich gel&ouml;scht! Wollen sie wirklich die Aktion ausf&uuml;hren?">alle Events L&ouml;schen</a>
				{/perm}
			</div>
		</td>
	</tr>
	<tr>
		<td class="Cnorm" colspan="7">
			
		</td>
	</tr>
	<tr>
		<th class="Chead" align="center">#id</th>
		<th class="Chead">Event Datum</th>
		<th class="Chead" align="center">Dungeon</th>
		<th class="Chead" align="center">Leader</th>
		<th class="Chead" align="center">Gruppe</th>
		<th class="Chead" align="center">Status</th>
		<th class="Chead" width="1">Options</th>
	</tr>
	{foreach $events as $i}
	<tr>
		<td class="Cnorm statusMsg" align="center" style="background-color: {$i.color}; text-shadow: 1px 1px 0px rgba( 255, 255, 255, 0.5);">{$i.id}</td>
		<td class="Cnorm">
			
				<div style="float:left; width: 30px;"><b>{$i.inv|date_format:"%a"}</div> 
				<div style="float:left;">{$i.inv|date_format:"%d.%m.%Y"}</b> <span class="ubuntu">({$i.info})</span></div>
				<br style="clear: both;" />
				<span class="small ubuntu">Invite: {$i.start} | Pull: {$i.begin} | Ende: {$i.ende}</span>
			
		</td>
		<td class="Cnorm" align="center">{$i.inzen}</td>
		<td class="Cnorm" align="center">{$i.leader}</td>
		<td class="Cnorm" align="center">{$i.grpname}</td>
		<td class="Cnorm statusMsg" align="center" style="background-color: {$i.color}; text-shadow: 1px 1px 0px rgba( 255, 255, 255, 0.5);">{$i.statusmsg}</td>
		<td class="Cnorm" align="center">
			{'smart'|icon}
			{'cancel'|icon}
		</td>
	</tr>
	{/foreach}
	<tr>
		<td width="50%" class="Cmite" colspan="7"></td>
	</tr>
</table>
{debug}