<form id="standart" method="post" action="index.php?chars-editChar-{$char.id}">
	<div id="step1">
		<table width="600" cellspacing="1" cellpadding="5" border="0" class="border">
			<tr class="Chead" >
				<th colspan="2">Charakter "{$char.name}" bearbeiten</th>
			</tr>
			<tr class="Cmite">
				<td align="right" width="150">Realm:</td>
				<td>
				{if $allgAr.otherrealm}
					<input name="realm" type="text" value="{'realm'|cfg}"/>
				{else}
					<input name="realm" type="hidden" value="{'realm'|cfg}"/>
					{'realm'|cfg}
				{/if}
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Name & Level:</td>
				<td>
					<input name="name" type="text" value="{$char.name}" readonly="readonly"/>
					<input id="intWheel" name="level" type="text" value="{$char.level}" size="3" maxlength="3" maxvalue="{'maxlevel'|cfg}" style="text-align: center;" tooltip="LEVEL (Scroll mit der Maus um den Wert zu ver&auml;ndern!"/>
				</td>
			</tr>

			<tr class="Cmite">
				<td align="right" width="150">Rassen:</td>
				<td>
					{html_options name='rassen' options=$rassen selected=$char.rassen}
				</td>
			</tr>
			<tr class="Cnorm">
				<td align="right">Klasse:</td>
				<td>
					{html_options name='klassen' options=$klassen selected=$char.klassen}
				</td>
			</tr>
			<tr class="Cmite">
				<td align="right">
					Spezialiesierung:
					<input id="s1" type="hidden" name="s1" value="{$char.s1}" />
					<input id="s2" type="hidden" name="s2" value="{$char.s2}" />
				</td>
				<td id="Spezialiesierung">{$spz}</td>
			</tr>
		
			<tr class="Cnorm">
				<td align="right"  width="150">Teamspeak:</td>
				<td>
					<label><input type="radio" id="ts1" name="teamspeak" value="1" {if $char.teamspeak == 1}checked="checked"{/if}>Ja</label>
					<label><input type="radio" id="ts2" name="teamspeak" value="0" {if $char.teamspeak == 0}checked="checked"{/if}>Nein</label>
				</td>
			</tr>
			
			{if {'charakterzeiten'|cfg} == '2' || ($smarty.session.charzeiten == 0 and {'charakterzeiten'|cfg} == 1 ) || ($smarty.session.charzeiten > 0 and {'charakterzeiten'|cfg} == 1 ) }
			
				<tr class="Cdark">
					<th colspan="2" align="center">zu welchen Raid Zeiten k&ouml;nnen Sie teilnehmen?</th>
				</tr>
				
				<tr class="Cmite">
					<td align="right">{'info}</td>
					<td>Die Raidzeiten sind Pflicht angaben, wenn Sie keine Zeit ausw&auml;hlen, k&ouml;nnen Sie sich zu keinem Raid/Event Anmelden!</td>
				</tr>
			
		
				{foreach $time as $i}
					<tr class="Cnorm">
						<td align="right"><input type="checkbox" name="time[]" value="{$i.id}" {$i.checked}></td>
						<td>{$i.info}: von {$i.start} bis {$i.ende}</td>
					</tr>
				{/foreach}
			{/if}
		
			<tr class="Cdark">
				<th align="right">
					<input type="hidden" name="avatar" value="{$char.avatar}" />
					<input type="hidden" name="img" value="{$char.img}" />
				</th>
				<th>
					<input type="Submit" value="Charakter {$char.name} Speichern">
				</th>
			</tr>
		</table>
	</div>
</form>
<script type="text/javascript">
$("select[autoSelect]").each(function(){ $(this).val($(this).attr("autoSelect")); });
</script>