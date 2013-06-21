<form method="post" action="admin.php?raidrecht-create">
	<table cellpadding="2" cellspacing="1" border="0" class="border left">
		<tr class="Chead">
			<th colspan="2">{'add'|icon} Neues Modul/Recht Regestrieren!</th>
		</tr>
		<tr class="Cnorm">
			<td>Name:</td>
			<td><input type="text" name="name"></td>
		</tr>
		<tr class="Cnorm">
			<td>Seite o. Recht:</td>
			<td><input type="text" name="url"></td>
		</tr>
		<tr class="Cnorm">	
			<td>gshow/ashow/fright:</td>
			<td>
				<input type="checkbox" value="1" name="gshow">
				<input type="checkbox" value="1" name="ashow">
				<input type="checkbox" value="1" name="fright">
			</td>
		</tr>
		<tr class="Cnorm">	
			<td>Men&uuml; oder Recht:</td>
			<td>{html_options name="menu" options=$menu}</td>
		</tr>
		<tr class="Cdark">
			<td colspan="2" align="right">
				<input type="hidden" name="pos" size="2" value="{$data|count}">
				<input type="Submit" value="Speichern" />
			</td>
		</tr>
		<tr class="Cmite">
			<td colspan="2">
				{'grey'|icon} <b>Drag&Drop Sortable</b><br />
				{'cancel'|icon} <b>Nur was &auml;ndern, wenn Sie wissen was Sie da tun!</b><br />
				{'info'|icon} *g/a/f-show/right, <a href='http://www.ilch.de/doku-entwickler11i.html#module' target="_blank">Anleitung</a><br />
				{'info'|icon} <b>Der eintrag "Rechte" sollte nicht geändert werden!</b><br />
			</td>
		</tr>
	</table>
</form>



<form id="standart" method="post" action="admin.php?raidrecht-update">
	<table cellpadding="2" cellspacing="1" border="0" class="border sortable left">
		<tr class="Chead noMove">
			<th ></th>
			<th>Name</th>
			<th>Seite</th>
			<th align="center">g/a/f</th>
			<th>Men&uuml;</th>
			<th width="10" class="hide">pos</th>
			<th width="10"></th>
		</tr>
		<tr class="Cdark noMove">
			<td colspan="7" align="right"><input type="submit" value="Speichern" /></td>
		</tr>
		{foreach $data as $i}
		<tr id="module{$i.id}" class="{if $i.menu == 'Raidplaner'}Chead{else}Cnorm{/if}">
			<td>
				{'grey'|icon}
				<input type="hidden" name="id[]" value="{$i.id}" />
			</td>
			<td><input type="text" name="name[]" value="{$i.name}" /></td>
			<td><input type="text" name="url[]" value="{$i.url}"></td>
			<td align="center" nowrap>
				<input type="checkbox" setDefault="#setDefault-1-{$i.id}" value="1" {if $i.gshow == 1}checked="checked"{/if}>
				<input type="checkbox" setDefault="#setDefault-2-{$i.id}" value="1" {if $i.ashow == 1}checked="checked"{/if}>
				<input type="checkbox" setDefault="#setDefault-3-{$i.id}" value="1" {if $i.fright == 1}checked="checked"{/if}>
				
				<input id="setDefault-1-{$i.id}" type="hidden" value="{$i.gshow}" name="gshow[]">
				<input id="setDefault-2-{$i.id}" type="hidden" value="{$i.ashow}" name="ashow[]">
				<input id="setDefault-3-{$i.id}" type="hidden" value="{$i.fright}" name="fright[]">
			</td>
			<td align="center">{html_options name="menu[]" options=$menu selected=$i.menu}</td>
			<td class="hide"><input type="text" name="pos[]" size="2" value="{$i.pos}"></td>
			<td width="10">
				<a href="admin.php?raidrecht-remove" perm="user={$smarty.session.authname}&id={$i.id}" remove="#module{$i.id}" confirm="confirm">{'cancel'|icon}</a>
				<confirm>{status id=2}Wollen sie wirklich <b>{$i.name}</b> entfernen?<br />das L&ouml;schen dieser Einstellung kann Probleme mit den bereits vergebenen Rechten der Charaktere veruhrsachen!{/status}</confirm>
			</td>
		</tr>
		{/foreach}
		<tr class="Cdark noMove">
			<td colspan="7" align="right"><input type="submit" value="Speichern" /></td>
		</tr>
		<tr class="Chead">
			<td colspan="7" align="right"></td>
		</tr>
	</table>
</form>

<br style="clear: both;"/>