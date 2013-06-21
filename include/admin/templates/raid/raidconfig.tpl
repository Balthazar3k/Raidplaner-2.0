<style>
	{literal}
	.viereck { float: left; margin: 0 0.5% 0.5% 0; padding: 0.5%; border: 1px solid rgba( 0, 0, 0, 0.5); width: 48%; border-radius: 3px; box-shadow: 3px 3px 1px rgba( 0, 0, 0, 0.5); }
	.form { text-align: center; }
	.form input>:first-child{ font-weight: bold; border-color:red; }
	.form input, .form select{ border-radius: 3px; margin: auto; width: 90px; }
	button{ background: transparent; border: none; cursor: pointer; }
	{/literal}
</style>

<script>
	{literal}
	$(document).ready(function(){
		$('div [slider]').each( function(){
			$(this).slider({
				value: $(this).attr("value"),
				min: 0,
				max: 5,
				step: 1,
				slide: function( event, ui ) {
					$($(this).attr('slider')).html( ui.value );
					$($(this).attr('slider')+"h").html( ui.value );
					$($(this).attr('slider')+"a").val( ui.value );
				},
				stop: function( event, ui ) {
					$(this).submit();
				}
			});
		});
	});
	{/literal}
</script>


<div class="viereck Cdark">
	<div id="accordion">
		<h3>
			<a href="#">Rekrutieren!</a>
		</h3>
		<div><span id="reloadRekrutieren">{getFile file='include/boxes/rekrutieren.php'}</span></div>
		{foreach $rekrutieren as $i}<h3>
			<a href="#">
				<span>
					<b><img class="borderRadius3" align="absmiddle" src="include/raidplaner/images/klassen/class_{$i.id}.jpg"> {$i.klassen}</b> - 
					<span id="s1{$i.id}h">{$i.rs1b}</span>, <span id="s2{$i.id}h">{$i.rs2b}</span>, <span id="s3{$i.id}h">{$i.rs3b}</span>
				</span>
			</a>
		</h3>
		<div>
			<fieldset class="borderRadius5up CharakterKlassenBackground">
				<span class="shadowb" style="color: {$i.color};"><span id="s1{$i.id}">{$i.rs1b}</span>x {$i.s1b} {$i.klassen}</span><br /><br />
				<form id="standart" action="admin.php?raidconfig-rekrutieren" reload="#reloadRekrutieren">
					<input type="hidden" name="update" value="prefix_raid_klassen" />
					<input type="hidden" name="id" value="{$i.id}" />
					<input id="s1{$i.id}a" type="hidden" name="rs1b" value="{$i.rs1b}" />
					<div slider="#s1{$i.id}" value="{$i.rs1b}"></div>
					<div class="hide"><input type="submit" value="Speichern"></div>
				</form>
			</fieldset>
			<fieldset class="CharakterKlassenBackground">
				<span class="shadowb" style="color: {$i.color};"><span id="s2{$i.id}">{$i.rs2b}</span>x {$i.s2b} {$i.klassen}</span><br /><br />
				<form id="standart" action="admin.php?raidconfig-rekrutieren" reload="#reloadRekrutieren">
					<input type="hidden" name="update" value="prefix_raid_klassen" />
					<input type="hidden" name="id" value="{$i.id}" />
					<input id="s2{$i.id}a" type="hidden" name="rs2b" value="{$i.rs2b}" />
					<div slider="#s2{$i.id}" value="{$i.rs2b}"></div>
					<div class="hide"><input type="submit" value="Speichern"></div>
				</form>
			</fieldset>
			<fieldset class="borderRadius5bo CharakterKlassenBackground">
				<span class="shadowb" style="color: {$i.color};"><span id="s3{$i.id}">{$i.rs3b}</span>x {$i.s3b} {$i.klassen}</span><br /><br />
				<form id="standart" action="admin.php?raidconfig-rekrutieren" reload="#reloadRekrutieren">
					<input type="hidden" name="update" value="prefix_raid_klassen" />
					<input type="hidden" name="id" value="{$i.id}" />
					<input id="s3{$i.id}a" type="hidden" name="rs3b" value="{$i.rs3b}" />
					<div slider="#s3{$i.id}" value="{$i.rs3b}"></div>
					<div class="hide"><input type="submit" value="Speichern"></div>
				</form>
			</fieldset>
		</div>{/foreach}
	</div>
</div>


<div class="viereck Cdark">
	<div id="accordion">
	
		<h3><a href="#">Einstellungen</a></h3>
		<div>die Einstellungen befinden sich <a href="{$ilchRaidConfig}">hier</a>, im reiter <b>Raidplaner</b>.
		</div>
		
		<h3><a href="#">ExtraKonfiguration</a></h3>
		<div>
			<table cellpadding="2" cellspacing="1" border="0" class="border sortable" align="center">
				<form action="admin.php?raidconfig-createConfig" method="post" class="standart imgSubmit">
					<tr class="Chead noMove">
						<td>{'forward'|icon}</td>
						<td align="right">{html_options name='type' options=$config_type}</td>
						<td><input type="text" name="key" value="" tooltip="Key" /></td>
						<td><input type="text" name="value" value="" tooltip="Value" /></td>
						<td><a href="#" onclick="$('.imgSubmit').submit()">{'add'|icon}</a></td>
					</tr>
				</form>
			</table>
			
			<form id="standart" action="admin.php?raidconfig-updateConfig" method="post">
				<table cellpadding="2" cellspacing="1" border="0" class="border sortable" align="center">
					{foreach $config as $i}
					<tr class="Cnorm removeConfig_{$i.id}">
						<td>{'grey'|icon}</td>
						<td>{html_options name='type[]' options=$config_type selected=$i.type}</td>
						<td>
							<input type="hidden" name="id[]" value="{$i.id}" />
							<input type="text" name="key[]" value="{$i.key}" tooltip="Key" />
						</td>
						<td><input type="text" name="value[]" value="{$i.value}" tooltip="Value" /></td>
						<td>
							<a href="admin.php?raidconfig-removeConfig" perm="id={$i.id}"confirm="Wirklich L&ouml;schen?" remove=".removeConfig_{$i.id}">{'cancel'|icon}</a>
						</td>
					</tr>
					{/foreach}
					<tr>
						<td colspan="5" align="right"><input type="submit" value="Speichern" /></td>
					</tr>
				</table>
			</form>
		</div>
		
		<h3><a href="#">Klassen</a></h3>
		<div class="form" align="center">
			
			<form id="standart" action="admin.php?raidconfig-updateConfig" method="post">
				
					{foreach $rekrutieren as $i}
					<span id="klassen_{$i.id}">
						
						<input type="hidden" name="id[]" value="{$i.id}" />
						<input type="text" name="klassen[]" value="{$i.klassen}" />
						<input type="text" name="s1b[]" value="{$i.s1b}" />
						<input type="text" name="s2b[]" value="{$i.s2b}" />
						<input type="text" name="s3b[]" value="{$i.s3b}" />
						<input id="colorpicker_{$i.id}" type="text" name="color[]" value="{$i.color}" size="7" maxlength="7" />
						<a href="admin.php?raidconfig-removeKlassen" perm="id={$i.id}" confirm="confirm" remove="#klassen_{$i.id}">{'cancel'|icon}</a>
						<confirm>
							{status id=2}
								Wollen sie wirklich <b>{$i.klassen}</b> L&ouml;schen?<br />
								Bitte denken Sie daran das es alle Charakter einstellungen verwirft, und es zu Fehlern kommen k&ouml;nnte!
							{/status}
						</confirm>
							
						<hr>
					</span>
					{/foreach}
					<input type="submit" value="Speichern" /></td>
			</form>
			
		</div>
		
	</div>
</div>
<br class="clear" />