<a class="button" href="#" dialog="#createRang">{'add'|icon} Rang {$rang|count} erstellen</a>
<a class="button" href="admin.php?raidrang-removeAllRanks">{'cancel'|icon} alle R&auml;nge entfernen</a>

<br style="clear: both;"/>
<br>
{status id=3}Wenn Charaktere von Battle.net geladen werden und noch einen undefieniert Rang haben werden sie hier <b>rot</b> eingerahmt{/status}
{assign var="width" value="{$charaktere|count}"}

{foreach $charaktere as $i}
	<div style="width: {100/$width}%; border-radius: 5px;" class="raidrangCharakter">
		<span class="CharakterKlassenBackground" style="border: 5px solid {if $i.rangname == ''}rgba(255,0,0,0.8);{else}rgba(0,255,0,0.5);{/if}">
			<div align="center" style="color: {$i.color}"><b><img align="absmiddle" src="include/raidplaner/images/klassen/class_{$i.cid}.jpg" /> {$i.name}</b></div>
			<div align="center" style="font-size: 10px;">Rang: {if $i.rangname == ''}#{$i.rank}{else}({$i.rank}) {$i.rangname|truncate:15}{/if}</div>
		</span>
	</div>
{/foreach}
<br style="clear: both;"/><br />

<div style="width: 99%; overflow: auto; border: 5px solid rgba( 0, 0, 0, 0.1);">
	<form id="standart" method="post" action="admin.php?raidrang-update">
		<table cellpadding="2" cellspacing="1" border="0" class="border left">
			<tr class="Chead">
				<td colspan="{$rang|count}">{'smart'|icon} Bearbeiten der R&auml;nge</td>
			</tr>
			<tr>
				{foreach key=k item=value from=$rang}
				<input type="hidden" name="id[]" value="{$value.id}" />
				<td class="Cdark" id="remove{$value.id}" valign="top">
					<div align="center"><b>Rang: #{$value.id} </b></div>
					<input type="text" name="name[{$value.id}]" value="{$value.name}" class="rang-name" />
					<br />
					{assign var="rechte" value=$value.rechte|trim|json_decode}
					{foreach $recht as $i}
						<div class='{if $i.menu != "Raidplaner"}Cnorm{else}Cmite{/if} rang-rechte-name' style='{if $i.menu == "Raidplaner"}font-weight: bold;{/if}'>
						<label>
							{if is_object($rechte)}
								{if in_array($i.id, $rechte->Raidplaner) || in_array($i.id, $rechte->Permissions)} 
									<input type="checkbox" name="rechte[{$value.id}][{$i.menu}][]" value="{$i.id}" checked="checked">
								{else}
									<input type="checkbox" name="rechte[{$value.id}][{$i.menu}][]" value="{$i.id}">
								{/if}
							{else}
								<input type="checkbox" name="rechte[{$value.id}][{$i.menu}][]" value="{$i.id}">
							{/if}
			
							{$i.name}
						</label>
						</div>
					{/foreach}
					<div align="right">
						{if {$value.id} > 0}
							<a href="admin.php?raidrang-remove" confirm="confirm" perm="id={$value.id}" remove="#remove{$value.id}">{'cancel'|icon}</a>
							<confirm>{status id=2}Wollen Sie wirklich den Rang "{$value.name}" L&ouml;schen?{/status}<br />{status id=3}Wenn Sie diesen Rang l&ouml;schen könnte es zu Problemen der Rechte kommen.{/status}</confirm>
						{/if}
					</div>
				</td>
				{/foreach}
			</tr>
			<tr class="Cdark">
				<td colspan="{$rang|count}" align="right"><input type="submit" value="Speichern"></td>
			</tr>
		</table>
	</form>
	
</div>
<br style="clear: both;"/><br />
{status id=3}Rang 0 ist der H&ouml;chste Rang (bsp. Admin/Gildenmeister/Clanleader) & kann nicht gel&oumlscht werden,<br />der letzte Rang ist Automatisch der Bewerber.<br />Man kann soviele R&auml;nge machen wie man will.{/status}


<div id="createRang" title="{$rang|count}ten Rang erstellen" class="hide">
	<form method="post" action="admin.php?raidrang-create">
		<input type="hidden" name="id" size="2" value="{$rang|count}">
		<table cellpadding="2" cellspacing="1" border="0" class="border left">
			<tr>
				<td class="Cdark">
					<div align="center"><b>Rang: #{$rang|count}</b></div>
					<input type="text" name="name" class="rang-name">
					{foreach $recht as $i}
						<div class='{if $i.menu != "Raidplaner"}Cnorm{else}Cmite{/if} rang-rechte-name' style='{if $i.menu == "Raidplaner"}font-weight: bold;{/if}'>
							<label>
								<input type="checkbox" name="rechte[{$i.menu}][]" value="{$i.id}">
								{$i.name}
							</label>
						</div>
					{/foreach}
				</td>
			</tr>
			<tr class="Cdark">
				<td align="right">
					<input type="Submit" value="Speichern" />
				</td>
			</tr>
		</table>
	</form>
</div>
{debug}
