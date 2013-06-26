<a class="button" href="index.php?chars-newchar" fancybox="inline">{'add'|icon} Neuer Charakter</a>
{perm authmod=removeCharakter}<a class="button" href="admin.php?chars-removeAll" confirm="confirm">{'cancel'|icon} alle Charaktere l&ouml;schen</a><confirm>{status id=2}Wollen Sie wirklich alle Charatere <b>unwiederruflich</b> l&ouml;schen?{/status}</confirm>{/perm}
<a class="button" href="#" dialog="#loadGuild">{'smart'|icon} loadGuild</a>

<div id="loadGuild" title="Lade Gilde von battle.net" class="hide">
	<form name="form" method="post" action="admin.php?chars-loadGuild">
		<table border="0" cellspacing="1" cellpadding="2" class="border">
			<tr class="Chead">
				<th colspan="2">{'add'|icon} Gilde von Battle.net Laden</th>
			</tr>
			<tr class="Cnorm">
				<td width="100">Realm:</td>
				<td width="160"><input type="text" name="realm" value="{'realm'|cfg}">
			</tr>
			<tr class="Cnorm">
				<td>Gilde:</td>
				<td><input type="text" name="guild">
			</tr>
			<tr class="Cdark">
				<td></td>
				<td><input type="submit" value="GuildLoad"></td>
			</tr>
		</table>
	</form>
</div>

<form id="form" name="form" method="post" action="admin.php?chars">
	<table width="100%" border="0" cellspacing="1" cellpadding="5" class="border">
	  <tr>
		<td class="Cnorm" align="center">
		  <select name="from" id="from">
			<option value="a.name" selected="selected" align="center">Name</option>
			<option value="d.name" align="center">User</option>
		  </select>
		 </td>
		<td class="Cnorm" align="center"><input type="text" name="search" id="search" /></td>
		<td class="Cdark" align="center"><input type="submit" name="button" id="button" value="Suchen" />
		  <input type="hidden" name="TRUE" id="TRUE" /></td>
		<td class="Cmite" align="center">
			{foreach $klassen as $i}<input type="image" name="klassen[{$i.id}]" src="include/raidplaner/images/klassen/class_{$i.id}.jpg" tooltip="{$i.klassen} Filtern" />{/foreach}
		</td>
		<td class="Cdark" align="center"><input type="button" value="Reset" onClick="window.location.href = 'admin.php?chars';"/></td>
	  </tr>
	</table>
</form>
<br />

<table width="100%" border="0" cellspacing="1" cellpadding="2" class="border">
  <tr class="Chead">
    <th>&nbsp;</th>
    <th>[ Level ] Name</th>
    <th>[ Alter ] User</th>
    <th>Rang</th>
    <th>Skillung</th>
    <th>hinzugef&uuml;gt</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  {foreach $char as $i }
  <tr class="Cnorm" id="charakter{$i.id}">
    <td width="1"><img src="include/raidplaner/images/klassen/class_{$i.kid}.jpg" /></td>
    <td>[{$i.level}] <b>{$i.name}</b></td>
    <td>
		{if $i.gebdatum != ''}[{$i.gebdatum|age}] {else}[00] {/if}
		
		{if $i.uid == 0 }
			<select id="combobox" name="changeUser" goto="admin.php?chars-changeUser::id={$i.id}&olduid={$i.uid}&rang={$i.rank}" reload="reload">
				<option><b>nicht Zugewiesen!</b></option>
				{html_options values=$user.id output=$user.name selected=$i.user}
			</select>
		{else}
			<b><a href="admin.php?user-1-{$i.uid}">{$i.username}</a></b>
		{/if}
	</td>
    <td width="1%">
	{if $i.uid != 0 }
		{assign var='goto' value="goto:admin.php?chars-changeRank-{$i.id}-{$i.uid}"}
		{html_options name={$goto} options=$rechte selected=$i.rank}
	{else}
		kein User, kein Rang
	{/if}
	</td>
    <td nowrap="nowrap" align="center" class="CharakterKlassenBackground" style="color: {$i.color}; font-weight: bold;">{$i.s1} {if $i.s2 != ''}/ {$i.s2}{/if}</td>
    <td nowrap="nowrap" tooltip="am: {$i.regist|date_format:'%d.%m.%Y'}">{$i.regist|ago}</td>
    <td width="1">{perm authmod='editCharakter'}<a href="admin.php?chars-details-{$i.id}">{'smart'|icon}</a>{/perm}</td>
    <td width="1">{perm authmod='removeCharakter'}<a href="index.php?chars-remove-{$i.id}" confirm="confirm" remove="#charakter{$i.id}">{'cancel'|icon}</a><confirm>{status id=2}m&ouml;chten sie "{$i.name}" wirklich <b>unwiederruflich</b> L&ouml;schen?{/status}</confirm>{/perm}</td>
  </tr>
  {/foreach}
</table>