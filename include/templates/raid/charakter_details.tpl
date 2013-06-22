<div class="inset" style="border-color: {$char.color}; background:#090502 url('{$char.img}') no-repeat left top;">
	<div class="CharakterDetailsName">
		<div class="font-effect-3d" style="color: {$char.color};">{$char.name} {$char.level}</div>
	</div>
	<table width="100%" border="0" cellspacing="1" cellpadding="5" class="border">
		<tr class="Cnorm">
		  <td width="40%" align="right">User: </td>
		  <td><a href="index.php?user-details-{$char.user}">{$char.username}</a></td>
		</tr>
		
		<tr class="Cnorm">
		  <td width="40%" align="right">Gildenrang: </td>
		  <td>{$char.rechtname}</td>
		</tr>
		
		<tr class="Cnorm">
		  <td width="40%" align="right">Realm: </td>
		  <td>{$char.realm}</td>
		</tr>
		
		<tr class="Cnorm">
		  <td align="right">Rasse: </td>
		  <td>{$char.rassen}</td>
		</tr>
		
		<tr class="Cnorm">
		  <td align="right">Klasse: </td>
		  <td>{$char.klassen}</td>
		</tr>
		
		<tr class="Cnorm">
		  <td align="right">Spezialiesirung 1/2: </td>
		  <td>{$char.s1} / {$char.s2}</td>
		</tr>
		
		<tr class="Cnorm">
		  <td align="right">Teamspeak: </td>
		  <td>{if {$char.teamspeak} == 1}Vorhanden{else}<span style="color: red;">nicht Vorhanden</span>{/if}</td>
		</tr>
		
		{if $char.warum != ''}
		<tr class="Cnorm">
		  <td align="right">Bewerbung: </td>
		  <td>{$char.warum}</td>
		</tr>
		{/if}
		
		{if $zeit|count != 0 }
			<tr class="Chead" style="text-shadow: none;">
			  <td colspan="2" align="center">an den Folgenden Zeiten kann <span style="color: {$char.color};">{$char.name}</span> mit Raiden</td>
			</tr>
			
			
			{foreach $zeit as $i }
			<tr class="Cmite">
			  <td align="right">{$i.info}:</td>
			  <td>von {$i.start} bis {$i.ende} Uhr</td>
			</tr>
			{/foreach}
		{/if}
		
		{if $twink|count != 0 }
			<tr class="Chead" style="text-shadow: none;">
			  <td colspan="2" align="center">weitere Charaktere von <span style="color: {$char.color};">{$char.name}</span></td>
			</tr>
			
			
			{foreach $twink as $i}
			<tr>
			  <td align="right" style="color: {$i.color}; text-shadow: none;"><b>{$i.rassen} {$i.klassen}:</b></td>
			  <td class="Cmite"><a href="index.php?chars-show-{$i.id}">{$i.name}</a> {$i.level}</td>
			</tr>
			{/foreach}
		{/if}
		
		<tr>
		  <td colspan="2" align="right">
		  
			{* eigentümer wenn nicht ab rang offizier *}
			{if $smarty.session.authid == $char.user || $smarty.session.charrang >= 13}<a href="index.php?chars-updateBattleNet" post="id={$char.id}">{'refresh'|icon}</a>{/if}
			
			{* eigentümer wenn nicht ab rang offizier *}
			{if $smarty.session.authid == $char.user}
				<a href="index.php?chars-edit-{$char.id}" fancybox="inline">{'smart'|icon}</a>
			{elseif $smarty.session.charrang >= 13}
				<a href="admin.php?chars-details-{$char.id}">{'smart'|icon}</a>
			{/if}
			
			{* ab rang offizier *}
			{perm authmod=removeCharakter}<a href="index.php?chars-del-{$char.id}" confirm="{$char.name} wirklich L&ouml;schen?">{'cancel'|icon}</a>{/perm}
			
			<a href="{$char.realm}/{$char.name}/simple" target="_blank" tooltip="Arsenal-Link">{'forward'|icon}</a>
		  </td>
		</tr>
	</table>
</div>
