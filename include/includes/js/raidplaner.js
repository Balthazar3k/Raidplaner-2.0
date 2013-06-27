(function(a){function d(b){var c=b||window.event,d=[].slice.call(arguments,1),e=0,f=!0,g=0,h=0;return b=a.event.fix(c),b.type="mousewheel",c.wheelDelta&&(e=c.wheelDelta/120),c.detail&&(e=-c.detail/3),h=e,c.axis!==undefined&&c.axis===c.HORIZONTAL_AXIS&&(h=0,g=-1*e),c.wheelDeltaY!==undefined&&(h=c.wheelDeltaY/120),c.wheelDeltaX!==undefined&&(g=-1*c.wheelDeltaX/120),d.unshift(b,e,g,h),(a.event.dispatch||a.event.handle).apply(this,d)}var b=["DOMMouseScroll","mousewheel"];if(a.event.fixHooks)for(var c=b.length;c;)a.event.fixHooks[b[--c]]=a.event.mouseHooks;a.event.special.mousewheel={setup:function(){if(this.addEventListener)for(var a=b.length;a;)this.addEventListener(b[--a],d,!1);else this.onmousewheel=d},teardown:function(){if(this.removeEventListener)for(var a=b.length;a;)this.removeEventListener(b[--a],d,!1);else this.onmousewheel=null}},a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})})(jQuery)
//location.reload()
// Seiten Manipulation
$(document).ready( function() {

	$('[id^=colorpicker], [name*=farbe], [name*=Farbe]').each(function(){

		var myColor = $(this);
		
		$(this).ColorPicker({

			onChange: function(hsb, hex, rgb, el) {
				$( myColor ).val('#' + hex).css({ border: '5px solid #' + hex, borderRadius: '5px' });
			},
			
			onBeforeShow: function (hex) {
				$(this).ColorPickerSetColor(this.value);
			}
	
		}).live('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
	});

	var stat = true;
	$('[timeout]').each(function(i){
		var time = $(this).attr('timeout');
		
		if( time == 0 )
			stat = false;
			
		if( time > 0 ){
			$(this).delay(time).slideToggle(1000,function(){ 
				$(this).remove();
			});
		}
	});
	
	$("input[setDefault]").live('click', function(e){
		var checkbox = $(this).attr("setDefault");
		var defaultCheckbox = $(checkbox);
		if( defaultCheckbox.val() == 0 ){
			defaultCheckbox.val(1);
		}else if(defaultCheckbox.val() == 1){
			defaultCheckbox.val(0);
		}
		
	});
	
	$("a[json]").live('click', function(event){
		event.preventDefault();
		var json_url = $(this).attr('href');
		var new_action = $(this).attr('json');
		$.getJSON( json_url, function(json){
			
			$('form').attr('action', new_action).find('[type=submit]').val('Speichern');
			
			$.each( json, function( $key, $value )
			{	$("[name="+$key+"]").val( $value );
			});
		});
	});
	
	$("select[name=img]").live('change', function(){
		if( $(this).val() == 'upload' ){
			$(this).after('<input type="file" name="imgUpload">');
		}
	});
	

	 $(".sortable tbody").sortable({ 
			revert: true,
			cursor: 'move',
			items: "tr:not(.noMove)", 
			stop: function(event, ui){ $(".sortable input:submit").css({ border: '2px solid #FF0000', borderRadius: '5px' }).val("Sortierung Speichern"); }
	});
    // $( ".sortable tr" ).disableSelection();
	 
	$(".class td:even").addClass("Cnorm");
	$(".class td:odd").addClass("Cmite");
		
	$("input[goto]").live('click', function(event){
		event.preventDefault();
		var id = $(this).attr('goto');
		$(id).slideToggle();	
	});
	
	$("div[slide]").css("cursor", "pointer").live('click', function(){
		var slide = $(this).attr("slide");
		$("." + slide).slideToggle();
	});
	
	$("select[goto]").live("change", function(){
		var val = '';
		var url = $(this).attr('goto');
		var reload = $(this).attr('reload');
		if( url.indexOf('::') != -1 ){
			var get = url.split('::');
			url = get[0];
			val += get[1];
		}
			
		val += '&val=' + $(this).val();
		console.log(val);
		$.post( url, val, function(data){
			$(this).dialogMsg( data, 3000, true);
		});
	});
	
	$("select[name^=goto]").live("change", function(e){
		var url = $(this).attr('name');
		url = url.replace('goto:', '');
		$.post( url, 'rank=' + $(this).val(), function(data){
			$(this).dialogMsg( data, 1500, true);
		});
	});
	
	$("a[href*='#arrPrint']").live('click', function(){
		$($(this).attr('href') + " div").slideToggle();
	});
	
	$("div[href]").css('cursor','pointer').click(function(event){
		event.preventDefault();
		var url = $(this).attr('href');
		window.location.href = url;
	});
	
	$("#battleNet").live("blur", function(){
		var name = $(this).val();
		if( name != '' && name.length > 2 )
		{	var realm = $("input[name=realm]").val();
			$.getJSON("index.php?chars-battleNet&name="+name+"&realm="+realm, function(data){
				if( data.find != false )
				{	
					if(typeof data.status != 'undefined' )
					{	$(this).dialogMsg( data.status, 10000);
					}else
					{	$.post("index.php?chars", "spz=1&kid=" + data.klassen, function(data2)
						{ 	$("#Spezialiesierung").html(data2); 
							$("#Spezialiesierung select").fadeIn();
							$.each( data, function( $key, $value )
							{	$("[name="+$key+"]").val( $value );
							});
						});
					}
				}
			});
		}
	});
	
	$("select[name=klassen]").live('change',function(){
	 	var $klassID = $(this).val();
		$.post("index.php?chars", "spz=1&kid=" + $klassID, function(data){ 
			$("#Spezialiesierung, .Spezialiesierung").html(data);
		});
	});
	
	$("#radio0").live("click", function(e){
		$("span#vonbis").fadeOut();
	});
		
	$("#radio1, #radio2").live("click", function(e){
		$("span#vonbis").fadeIn();
	});
	
	tooltip($("[tooltip]"));
	
	var jQueryActions = function(){
	
		$( "div[progressbar]").each( function(){
			var value = parseInt($(this).attr('progressbar'));
			var maxValue = parseInt($(this).attr('max'));
			$(this).progressbar({  value: value, max: maxValue  });
		});
	
		$("div#accordion").accordion({ header: "h3", autoHeight: false });
		$("select[autoSelect]").each(function(){ $(this).val($(this).attr("autoSelect")); });
		
		$("#s1, #s2").each(function()
		{	var name = $(this).attr("name");
			$(this).attr("disabled", "disabled");
			$("[name="+name+"]").val( $(this).val() );
		});
		
		
		
		$( "#radio" ).buttonset();
		
			// Datepicker
		$("#datepicker, #datepicker2").datepicker({
				dateFormat: "dd.mm.yy",
				dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
				monthNamesShort: ["Jan","Feb","M&auml;r","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"],
				changeMonth: true,
				changeYear: true
		});
		
		$("#applyDatepicker").datepicker({
				dateFormat: "yy-mm-dd",
				defaultDate: "-" + $("#applyDatepicker").attr('maxDate') + "Y",
				maxDate: "-" + $("#applyDatepicker").attr('maxDate') + "Y",
				yearRange: "c-20:c+10",
				dayNamesMin: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"],
				monthNamesShort: ["Jan","Feb","M&auml;r","Apr","Mai","Jun","Jul","Aug","Sep","Okt","Nov","Dez"],
				changeMonth: true,
				changeYear: true
		});
		
		$("div.erfolg, div.achtung").delay(2500).slideUp();
		
		$( "a.button").button();
	}
	
	jQueryActions();
	
	//Formulare
	var ajaxForm = function(event){
		event.preventDefault();
		var obj = $(this);
		$(this).dialogMsg("<img align='center' src='include/raidplaner/images/icons/loader.gif'>", 0, false);
		var url = $(this).attr('action'); 						// Wohin die Daten gesendet werden!
		var res = $(this).serialize();							// Formular Daten umwandeln!
		var cal = 	function(data){ 							// Callback
						$.fancybox.close();						// Wenn Fancybox offen ist Schlieﬂen!
						//obj.attrOptions(data);
						obj.dialogMsg( data, 2500, true).delay(2501).attrOptions(data);
					}
					
		$.post( url, res, cal, 'html');    
	}
	
	// Schickt standart Formulare ab
	$("form#standart").live('submit', ajaxForm);
	
	//KalenderInhalt
	$("table .kalenderInhalt")
		.live("mouseenter", function(){ $(this).find("#kalenderAction").fadeIn();})
		.live("mouseleave", function(){ $(this).find("#kalenderAction").fadeOut();});
		
	//KalenderFancybox
	$('a[kalender]').live("click", function(event){
		event.preventDefault();
		var href = $(this).attr('href');
		var post = $(this).attr('kalender');
		$.fancybox({
			href: href,
			ajax : {
				type	: "POST",
				data	: post
			}
		});
	});
		
	
	//fancybox
	$('a[fancybox=inline]').live('click', function(e){
		e.preventDefault();
		$.fancybox({
			'href'				: $(this).attr('href'),
			'width'				: '100%',
			'height'			: '100%',
			'autoScale'     	: true,
			'transitionIn'		: 'none',
			'transitionOut'		: 'none',
			'type'				: 'inline',
			onComplete			: function(){ jQueryActions(); }
		});
	});
	
	//Fancybox Gruppen Bilder
	$("a.group").fancybox({
	  'transitionIn'	:	'elastic',
	  'transitionOut'	:	'elastic',
	  'speedIn'			:	600, 
	  'speedOut'		:	1000, 
	  'overlayShow'	:	false
	});
	
	// <a> Tag zu einem $_POST
	$('a[post]').live('click', function(event){
		event.preventDefault();
		$(this).dialogMsg("<img align='center' src='include/raidplaner/images/icons/loader.gif'>", 0, false);
		var url = $(this).attr('href');
		var post = $(this).attr('post');
		var obj = $(this);
		$.post( url, post, function(data)
		{	$(this).dialogMsg(data, 3000, true);
			obj.attrOptions(data);
		});
	});
	
	//Dialog Erstellen
	$("<div id=\"dialog\"></div>").css({display: 'none'}).appendTo('body');
	var $dialog = $("#dialog");
	
	//
	$("a[dialog]").live("click", function(event){
		event.preventDefault();
		var id = $(this).attr("dialog");
		var title = $(id).attr('title');
		
		$dialog.html( $(id).html() );

		$dialog.dialog({
			title: title,
			autoOpen: false,
			height: "auto",
			width: "auto",
			show: "fade",
			hide: "fade",
			modal: true
		});
		
		$dialog.dialog("open");
	});
	
	// Confirm Dialog
	$("a[confirm]").live("click", function(e){ 
		e.preventDefault();
		var obj = $(this);
		var confirmString = $(this).attr('confirm');
		var confirmUrl = $(this).attr('href');
		var perm = $(this).attr('perm');
		
		if( confirmString == 'confirm' )
			confirmString = $(this).next().html();
		
		$dialog.html(confirmString);
		$dialog.dialog({
			zIndex: 2000,
			title: 'Best&auml;tigen!',
			autoOpen: false,
			width: 650,
			show: "fade",
			hide: "fade",
			modal: true,
			buttons: {
				"Ja" 	: function(){ 
							var callback = function(data)
							{ 	obj.attrOptions(data);
								$dialog.dialog('close');
								$(this).dialogMsg(data, 3000, true);
							}
							
							$.post(confirmUrl, perm, callback, "html");
						}, 
				"Nein"	: function(){ $(this).dialog("close"); }
			}
		});

		$dialog.dialog('open'); 
	});
	
	$("#stdWheel").live("mousewheel", function(event, delta){
		event.preventDefault();
		var now = parseInt($(this).val());
		
		if( delta > 0 && now != 23 )
		{	$(this).val(now+1);
		}else if( delta == -1 && now != 0)
		{	$(this).val(now-1);
		}else if( now == 23 )
		{	$(this).val('00');
		}else if( now == 0 )
		{	$(this).val(23);
		}
		
	});
	
	$("#minWheel").live("mousewheel", function(event, delta){
		event.preventDefault();
		var now = parseInt($(this).val());
		
		if( delta > 0 && now != 55 )
		{	$(this).val(now+5);
		}else if( delta == -1 && now != 0)
		{	$(this).val(now-5);
		}else if( now == 55 )
		{	$(this).val('00');
		}else if( now == 0 )
		{	$(this).val(55);
		}
		
	});
	
	$("#intWheel").live("mousewheel", function(event, delta){
		event.preventDefault();
		var now = parseInt($(this).val());
		var max = parseInt($(this).attr('maxvalue'));
		
		if( delta > 0 && now != max )
		{	$(this).val(now+1);
		}else if( delta == -1 && now != 0)
		{	$(this).val(now-1);
		}
	});
	
	$("input#timeWheel").live("keyup", function(event){
		event.preventDefault();
		var val = $(this).val();
		if( val.length == 2 && event.keyCode != 8 ) // keyCode 8 = Lˆschen Taste
		{	$(this).val(val+":");
		}
	});
	
	$("#timeWheel").live("mousewheel", function(event, delta){
		event.preventDefault();
		var now = $(this).val();
		var now = now.split(":");
		
		std = parseInt( now[0] );
		min = parseInt( now[1] );
		
		if( delta > 0 && min != 45 )
		{	min = min + 15;
		}else if( delta > 0 && std == 23 )
		{	std = '00';
		}else if( delta > 0 && min == 45 )
		{	std = std + 1;
			min = '00';
		}
		
		if( delta == -1 && min != 00 )
		{	min = min - 15;
			if( min == 0 )
				min = "00";
				
		}else if( delta == -1 && std == 0 )
		{	std = 23;
		}else if( delta == -1 && min == 0 )
		{	std = std - 1;
			min = 45;
		}
			
		$(this).val(std+":"+min);
		
	});
	
	var $klasse = $("select[name=klassen]");
	var $klassID = '';
	var $klassCOLOR;
	$klasse.live('change',function(){
	 	$klassID = $(this).val();
		$klassCOLOR = $("select[name=klassen] option:selected").attr("color");
		$("#fancybox-content").animate({borderColor: $klassCOLOR}, 2000);
		$.post("index.php?chars", "spz=1&kid=" + $klassID, function(data){ $("#Spezialiesierung").html(data); $("#Spezialiesierung select").fadeIn(); });
	});
	
	$('div[imgSize]').each(function(i){
		var obj = $(this);
		obj.html("<img  align='center' src='include/raidplaner/images/icons/loader.gif'>");
		var str = $(this).attr('imgSize');
		str = str.split(',');
		$.post( str[0], '', function(data){
			var con = $(data).find(str[1]);
			obj.html( con.width() + "x" + con.height() );
		});
	});
});

// eigenes jQuery Plugin
jQuery.fn.extend({
	simpleInsert: function(myValue){
	  return this.each(function(i) {
		if (document.selection) {
		  this.focus();
		  sel = document.selection.createRange();
		  sel.text = myValue;
		  this.focus();
		}
		else if (this.selectionStart || this.selectionStart == '0') {
		  var startPos = this.selectionStart;
		  var endPos = this.selectionEnd;
		  var scrollTop = this.scrollTop;
		  this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
		  this.focus();
		  this.selectionStart = startPos + myValue.length;
		  this.selectionEnd = startPos + myValue.length;
		  this.scrollTop = scrollTop;
		} else {
		  this.value += myValue;
		  this.focus();
		}
	  })
	},
	
	dialogMsg: function( data, timeout, button, after)
	{	$("div#dialogMsg").dialog('close').remove();
	
		var dialogMsg = $("<div id=\"dialogMsg\">"+data+"</div>").hide().appendTo('body');
		dialogMsg.html(data);
		dialogMsg.dialog(
		{	title: 'Status',
			autoOpen: true,
			width: 650,
			minHeight: 50,
			zIndex: 2000,
			show: "fade",
			hide: "fade",
			modal: true
		});
		
		if(button)
		{	dialogMsg.dialog(
			{	buttons: { "Weiter" : function(){ $(this).dialog("close"); } }
			});
		}
		
		if(typeof timeout != 'undefined' || timeout != 0)
			setTimeout(function(){ dialogMsg.dialog('close'); }, timeout);
			
		return this;
	},
	
	countdown: function(cd)
	{	return this.each(function(i)
		{	var sec = cd / 1000;
			//var iss = this.html('123');
			console.log('Countdown.', cd, ', Sec.', sec, ', Now.', iss );
			if(typeof iss == 'undefined' || iss == '')
			{	this.html(sec);
			}
			
			if( iss > sec )
			{	this.html( (iss-1) );
				setTimeout(function(){ this.countdown(cd) }, 1000);
				console.log('Countdown', cd, ', Sec.', sec, ', Now', iss );
			}
		})
	},
	
	attrOptions: function( oldData )
	{	//console.log( this.html() );
	
		// ENTFERNEN MIT remove attr, erwartet jQuery Selector
		//#####################################################
		var remove = this.attr('remove');
		//console.log( "remove:", this.attr('remove') );
		if(typeof remove != 'undefined' || remove != '' ){
			$(remove).fadeOut().remove();
		}
		
		// content reload attr, erwartet jQuery Selector
		//#####################################################

		var reload = this.attr('reload');
		//console.log( "reload:",  reload );
		
		if(typeof reload != 'undefined'){
			console.log("indexOf", reload.indexOf(',') );
			if( reload.indexOf(',') != -1 ){
				reload = reload.split(',');
				
				if(typeof oldData == 'undefined'){
					$.post( location, "", function(data){
						for( i in reload )
						{	var neu = $(data).find(reload[i]);
							$(reload[i]).replaceWith(neu);
						}
					});
					console.log("Multi");
				}else{
					for( i in reload )
					{	var neu = $(oldData).find(reload[i]);
						$(reload[i]).replaceWith(neu);
					}
					
					console.log("Multi OLD");
				}
			}else{
				if(typeof oldData == 'undefined'){
					console.log("Singel", reload);
					$(reload).load(location + " " + reload);
				}else{
					neu = $(oldData).find(reload);
					console.log("Singel OLD", reload, neu);
					$(reload).replaceWith(neu);
				}
			}
		}
		
		// Seite NeuLaden, erwartet url oder leeren string
		//#####################################################
		var page = this.attr('page');
		//console.log( "page:",  this.attr('page') );
		if(typeof page != 'undefined' ){
			if( page == 'reload' || page ==  '' ){
				window.location.reload();
			}else if( page != '' ){
				window.location = page;
			}
		}
		
	}
});

function tooltip(obj){
	if(!obj.length) return;
	$('body').append('<div id="tooltip" class="tooltip"></div>');
	var tooltip = $('#tooltip');
	tooltip.css({display:'none', position: 'absolute', zIndex: 5000, opacity: 0.95});
	var res;
	obj	.live("mouseenter", function()
				{	res = $(this).attr('tooltip');
					if( res.indexOf("ajax") != -1 )
					{	var url = res.replace("ajax", "");
						//console.log("AJAX Tooltip: ", url );
						tooltip.html("Loading...")
						$.get(url, function(data){ tooltip.html(data) });
					}else
					{	var html = $(res).html();
						tip = (res.indexOf('#') == -1 ? res : html);
						tooltip.html(tip);
					}
					tooltip.stop(true, true).delay(50).fadeIn('slow').dequeue();
				})
		.live("mouseleave", function()
				{	tooltip.stop(true, true).fadeOut('slow', function(){ tooltip.css("opacity", "0.95") });
				})
		.live("mousemove", function(e){ tooltip.css({top:e.pageY+20, left:e.pageX+20});});
		//tooltip.hover(function(){tooltip.stop(true, true).fadeOut('slow');}) 
}

function eachObject( obj ){
	for( i in obj )
	{	console.log( obj[i] );
	}
}