 jQuery(document).ready(function($){
 $(".struktura-portfelya table tbody tr").last().addClass("last-child");
});
function hide_id(nodeId)
{
    var node = document.getElementById(nodeId);
    if (node)
    {
        node.className=node.className.replace('showed', '');
        node.className += ' hidden';
    }
}

function show_id(nodeId)
{
    var node = document.getElementById(nodeId);
    if (node)
    {
        node.className=node.className.replace('hidden', '');
        node.className += ' showed';
    }
}

function toggle_id(nodeId)
{
    var node = document.getElementById(nodeId);
    if (node)
    {
        if (node.className.indexOf('hidden') != -1)
            node.className=node.className.replace('hidden', 'showed')
        else if (node.className.indexOf('showed') != -1)
            node.className=node.className.replace('showed', 'hidden')
        else node.className += ' showed';
     }
}

function setPrintCSS(isPrint) {
  if (document.getElementsByTagName)
      x = document.getElementsByTagName('link');
  else
  {
      return;
  }
  for (var i=0;i<x.length;i++) {
      if(x[i].title == 'printview'){x[i].disabled = !isPrint;}
      if(x[i].title == 'screenview'){x[i].disabled = isPrint;}
  }
}

function validateForm(e) {

var f = '#' + $(e).attr('id');
var elem = $(f + " input, " + f + ' textarea,' + f + ' select,');
var err = "";
for (var i=0; i<elem.length; i++) {
    if ($(elem[i]).hasClass("required") && $(elem[i]).get(0).tagName == 'INPUT')
	if ($(elem[i]).val() == "")
    {
		err += "<li><strong>"+ $(elem[i]).attr("placeholder") +"</strong> - обязательны для заполнения</li>";
		$(elem[i]).addClass('error');
    }
	 else
	 {
		$(elem[i]).removeClass('error'); 
	 }
    if ($(elem[i]).hasClass("required") && $(elem[i]).get(0).tagName == 'TEXTAREA')
		if ($(elem[i]).val() == "")
		{
			err += "<li><strong>"+ $(elem[i]).attr("placeholder") +"</strong> - обязательны для заполнения</li>";
			$(elem[i]).addClass('error');
	   }
	  else
	   {
		$(elem[i]).removeClass('error');  
	  } 

	if ($(elem[i]).hasClass("email") && $(elem[i]).val() !== "" )
    {
		if (!checkEmail($(elem[i]).val()) )
		{
		err += "<li><strong>"+ $(elem[i]).attr("placeholder") +"</strong> - должен быть действителен</li>";
		$(elem[i]).addClass('error');
	}
  else
  {
     $(elem[i]).removeClass('error');
  }

 }

 if ($(elem[i]).hasClass("required") && $(elem[i]).get(0).tagName == 'SELECT' && (elem[i].selectedIndex <= 0))
    {
      err += "<li><strong>"+ $(elem[i]).attr("placeholder") +"</strong> - обязательно нужно выбрать</li>";
   $(elem[i].parentNode).addClass('error');
    }
 else {
   $(elem[i].parentNode).removeClass('error');
  }


 if ($(elem[i]).hasClass("phone") && $(elem[i]).val() !== "" )
    {
  if (!checkPhone($(elem[i]).val()) )
  {
		  err += "<li><strong>"+ $(elem[i]).attr("placeholder") +"</strong> - must be valid</li>";
    $(elem[i]).addClass('error');
    $(elem[i]).removeClass('ok');
		}
		else
		{
     $(elem[i]).addClass('ok');
		}

	}

 

  }
  


  if (err != "") {
    err = "<span>Вы забыли или не правильно ввели одно или несколько полей. Пожалуйста, исправьте эти ошибки:</span><ul>" + err + "</ul>";
    $("#fill_form").html(err).addClass("visible");
    return false;
  }
  else return true;
}




/* --========================--*/
function checkEmail(e)
{
 ok = "1234567890qwertyuiop[]asdfghjklzxcvbnm.@-_QWERTYUIOPASDFGHJKLZXCVBNM";

 for(i=0; i < e.length ;i++)
  if(ok.indexOf(e.charAt(i))<0)
   return (false);

 if (document.images)
 {
  re = /(@.*@)|(\.\.)|(^\.)|(^@)|(@$)|(\.$)|(@\.)/;
  re_two = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  if (!e.match(re) && e.match(re_two))
   return true;
  else
   return false;

 }
 return true;

}

function checkPhone(e)
{
	if(e.match(/^\+?[0-9\- ]{5,}$/))
		return true
	else
   		return false;
}

function elementSupportsAttribute(element,attribute) {
	var test = document.createElement(element);
	if (attribute in test) {
		return true;
	} else {
		return false;
	}
}

function ScrollUp()
{
  $('html,body').animate({scrollTop: 190}, 1000);
}

/* *********************************
        ON LOAD
********************************* */
$(function(){
  $(':input[required]').addClass("required").removeAttr('required');
	if(document.getElementById('contact-form') )
	{
		if (!elementSupportsAttribute('input','placeholder') && !(jQuery.browser.safari && (navigator.appVersion.indexOf('3.') != -1)))
		{

			var el = $(" input, select, textarea");
			for (var i=0; i<el.length; i++) {
			    if ($(el[i]).val() == "")
			    {
				  $(el[i]).addClass('placeholder');
			    }

				$(el[i]).blur(function() {
					if ($(this).val() =='')
					{
						$(this).addClass('placeholder');
					}
					else
					{
						$(this).removeClass('placeholder');
					}
				 })
				$(el[i]).focus(function() {
						$(this).removeClass('placeholder');

			   })				 
			}
		}
	}

})


jQuery(document).ready(function() {

	if(document.getElementById('vashe-imya')){
	 document.getElementById('vashe-imya').onblur=function(){if(this.value!=''){this.style.backgroundPosition='0 -100px';} else {this.style.backgroundPosition='10px 10px';}};
	 document.getElementById('vashe-imya').onfocus=function(){this.style.backgroundPosition='0 -100px';};
	 document.getElementById('vash-email').onblur=function(){if(this.value!=''){this.style.backgroundPosition='0 -100px';}  else {this.style.backgroundPosition='10px 10px';}} ;
	 document.getElementById('vash-email').onfocus=function(){this.style.backgroundPosition='0 -100px';};
	 if (document.getElementById('kapcha')) {document.getElementById('kapcha').onblur=function(){if(this.value!=''){this.style.backgroundPosition='0 -100px';}  else {this.style.backgroundPosition='10px 10px';}} ;}
	 if (document.getElementById('kapcha')) {document.getElementById('kapcha').onfocus=function(){this.style.backgroundPosition='0 -100px';};}
	 document.getElementById('vash-vopros').onblur=function(){if(this.value!=''){this.style.backgroundPosition='0 -1000px';}  else {this.style.backgroundPosition='10px 10px';}} ;
	 document.getElementById('vash-vopros').onfocus=function(){this.style.backgroundPosition='0 -1000px';};

	 
	 };


	if(document.getElementById('email')){
	 document.getElementById('email').onblur=function(){if(this.value!=''){this.style.backgroundPosition='0 -100px';} else {this.style.backgroundPosition='0 0';}};
	 document.getElementById('email').onfocus=function(){this.style.backgroundPosition='0 -100px';};
};
	if(document.getElementById('theme')){
	 document.getElementById('theme').onblur=function(){if(this.value!=''){this.style.backgroundPosition='0 -100px';}  else {this.style.backgroundPosition='0 0';}} ;
	document.getElementById('theme').onfocus=function(){this.style.backgroundPosition='0 -100px';};
	 
	 };
	if(document.getElementById('description')){
	 document.getElementById('description').onblur=function(){if(this.value!=''){this.style.backgroundPosition='0 -1000px';}  else {this.style.backgroundPosition='0 0';}} ;
	 document.getElementById('description').onfocus=function(){this.style.backgroundPosition='0 -1000px';};
};	 

 }); 
	


// Verify
function checkEmails(e)
{
 ok = "1234567890qwertyuiop[]asdfghjklzxcvbnm.@-_QWERTYUIOPASDFGHJKLZXCVBNM";

 for(i=0; i < e.length ;i++)
  if(ok.indexOf(e.charAt(i))<0)
   return (false);

 if (document.images)
 {
  re = /(@.*@)|(\.\.)|(^\.)|(^@)|(@$)|(\.$)|(@\.)/;
  re_two = /^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
  if (!e.match(re) && e.match(re_two))
   x
  else
   return false;

 }
 return true;

}

function validateForm(){

  var err='';

  if (document.getElementById("email").value == ""){
    err = err + 'Введите e-mail';
  }
  if (document.getElementById('email').value != '')
 {
  if (!checkEmail(document.getElementById('email').value))
  {
   err = err + 'Некорректный e-mail';
  }
 }

  if(err==""){
  	//document.getElementById("schedule_showing_2").submit();
    return true;
  }
  else{
  	err = '<span>'+err+'</span>';
  	document.getElementById("check_error").innerHTML = err;
    self.location.href='#check_error';
    return false;
  }
}
//


$(function() 
  {
		if (document.getElementById('tabs'))
		{
			$( "#tabs" ).tabs();
		}
		
	if (document.getElementById('piegrafic'))
	{
		
				
			Raphael.fn.pieChartC = function (cx, cy, r, values, labels, stroke, c) {
				var paper = this,
					rad = Math.PI / 180,
					chart = this.set();
				function sector(cx, cy, r, startAngle, endAngle, params) {
			//        console.log(params.fill);
					var x1 = cx + r * Math.cos(-startAngle * rad),
						x2 = cx + r * Math.cos(-endAngle * rad),
						y1 = cy + r * Math.sin(-startAngle * rad),
						y2 = cy + r * Math.sin(-endAngle * rad);
					return paper.path(["M", cx, cy, "L", x1, y1, "A", r, r, 0, +(endAngle - startAngle > 180), 0, x2, y2, "z"]).attr(params);
				}
				var angle = 0,
					total = 0,
					start = 0,
					process = function (j) {
						var value = values[j],
							angleplus = 360 * value / total,
							popangle = angle + (angleplus / 2),
			//                color = Raphael.hsb(start, .75, 1),				
							color = c[j],
							ms = 500,
							delta = 30,
			//                bcolor = Raphael.hsb(start, 1, 1),				
							bcolor = c[j],
							p = sector(cx, cy, r, angle, angle + angleplus, {fill: "90-" + bcolor + "-" + color, stroke: stroke, "stroke-width": 0}),
						   txt = paper.text(cx + (r + delta + 55) * Math.cos(-popangle * rad), cy + (r + delta + 25) * Math.sin(-popangle * rad), labels[j]).attr();
						p.mouseover(function () {
							p.stop().animate({transform: "s1.1 1.1 " + cx + " " + cy}, ms, "elastic");
							txt.stop().animate({opacity: 1}, ms, "elastic");
						}).mouseout(function () {
							p.stop().animate({transform: ""}, ms, "elastic");
			                txt.stop().animate({opacity: 0}, ms);
						});						
						angle += angleplus;
						chart.push(p);
						chart.push(txt);
						start += .1;
					};
				for (var i = 0, ii = values.length; i < ii; i++) {
					total += values[i];
				}
				for (i = 0; i < ii; i++) {
					process(i);
				}
				return chart;
			};

			$(function () {
				var values = [],
					labels = [];
					
				
				$("#piegrafic tr").each(function () {
					values.push(parseInt($("td", this).text(), 10));
					labels.push($("th", this).text());
				});
;	

				var c = ['#4883c2', '#73b48d', '#d2c876', '#f98d8d', '#d261b6'];
					


				$("table").hide();
				Raphael("piegrafic", 700, 450).pieChartC(255, 255, 120, values, labels, '#9851a0', c);

			});
	}		
	
});
/** content-text **/	
function blockHeight()
 
  {
   if ($('.content_form_ans').length == 0) {
    var sh = $(".s_right").height();
    var ch = $(".page-content").height(); 
    if (sh > ch)
    {
     $('.s_right').css("max-height" , $('.page-content').height());

    } 
   };
  
  };
		
$(function(){
function tableHeight()
		{
			$('table.grand-statistika tr:gt(10), #content_tab_1 table.table-calc tr:gt(10), t#content_tab_2 table.table-calc tr:gt(10),#content_tab_2 table.table-calc tr:gt(10), #content_tab_3 table.table-calc tr:gt(10) ').addClass('none');
		};
$(".to-content").click(function() {
	$("body").toggleClass("main");
	if ($(this).hasClass("close"))
	{
		$("#slider").animate({ opacity: "show" }, "hide");
		$('a.to-content').removeClass('close');	

	}
	else
	{
		$("#slider").animate({ opacity: "hide" }, "slow");
	 	$(this).addClass('close');		 
	}
    return false;			
});

setTimeout(function () { 
	blockHeight();	
	tableHeight();	
}, 500);	
	

$(".show_more").click(function() {
	$('table.grand-statistika tr.none').fadeIn("normal");
	$('div.show_more').fadeOut();
});


$('#content_tab_1 div.show_more').click(function(){
        $('#content_tab_1  table.table-calc tr.none').removeClass('none');
  $('div.show_more').fadeOut();
    });

$('#content_tab_2 div.show_more').click(function(){
        $('#content_tab_2  table.table-calc tr.none').removeClass('none');
  $('div.show_more').fadeOut();
    });



$('#content_tab_3 div.show_more').click(function(){
        $('#content_tab_3  table.table-calc tr.none').removeClass('none');
  $('div.show_more').fadeOut();
    });

});
var val_bank;

function updateResultTable(type_page,n)
{

	var add = '';
	switch (n)
	{
		case 2: add = '_'; break;
		case 3: add = '__'; break;
	}


	$('#content_tab_' + n + ' table.table-calc tbody tr').removeAttr("style");	
	
	if (type_page=="torgovue") {	
		val_bank = $('select#n_bonds' + add).val();
		if (val_bank!=''){
			$('#content_tab_' + n + ' table.table-calc tbody tr td:nth-child(2)').each(function (){
				if ($(this).html() != val_bank)
					$(this).parent().css('display','none');
			});
		}

		val_curr = $('select#value' + add).val();
		if (val_curr!=''){
			$('#content_tab_' + n + ' table.table-calc tbody tr td:nth-child(3)').each(function (){
				if ($(this).html() != val_curr)
					$(this).parent().css('display','none');
			});
		}

		val_curr = $('select#value2' + add).val();
		if (val_curr!=''){
			$('#content_tab_' + n + ' table.table-calc tbody tr td:nth-child(7)').each(function (){
				if ($(this).html() != val_curr)
					$(this).parent().css('display','none');
			});
		}
	}
	else {
		val_bank = $('select#n_bonds' + add).val();
		if (val_bank!=''){
			$('#content_tab_' + n + ' table.table-calc tbody tr td:first-child').each(function (){
				if ($(this).html() != val_bank)
					$(this).parent().css('display','none');
			});
		}

		val_curr = $('select#value' + add).val();
		if (val_curr!=''){
			$('#content_tab_' + n + ' table.table-calc tbody tr td:nth-child(4)').each(function (){
				if ($(this).html() != val_curr)
					$(this).parent().css('display','none');
			});
		}

		val_rating = $('select#value2' + add).val();
		if (val_rating!=''){
			var z = val_rating.split(' ');
			var span;
			switch (z[0])
			{
				case "S&P": span = 1; break;
				case "Moody's": span = 2; break;
				case "Fitch": span = 3; break;
			}
			$('#content_tab_' + n + ' table.table-calc tbody tr td:nth-child(12) div span:nth-child(' + span + ')').each(function (){
				if ($(this).html() != z[1])
					$(this).parent().parent().parent().css('display','none');
			});
		}	
	}
}

$(function(){
	
  if ($(".section-kalendar-statistiki").length){
    if ($("select[name='euro_recommendation']").length) {
		$("select#n_bonds").change(function(){updateResultTable('torgovue',1)});
		$("select#value").change(function(){updateResultTable('torgovue',1)});
		$("select#value2").change(function(){updateResultTable('torgovue',1)});
	
		$("select#n_bonds_").change(function(){updateResultTable('torgovue',2)});
		$("select#value_").change(function(){updateResultTable('torgovue',2)});
		$("select#value2_").change(function(){updateResultTable('torgovue',2)});

		$("select#n_bonds__").change(function(){updateResultTable('torgovue',3)});
		$("select#value__").change(function(){updateResultTable('torgovue',3)});
		$("select#value2__").change(function(){updateResultTable('torgovue',3)});
	}
	else {
		$("select#n_bonds").change(function(){updateResultTable('emitentu',1)});
		$("select#value").change(function(){updateResultTable('emitentu',1)});
		$("select#value2").change(function(){updateResultTable('emitentu',1)});
	
		$("select#n_bonds_").change(function(){updateResultTable('emitentu',2)});
		$("select#value_").change(function(){updateResultTable('emitentu',2)});
		$("select#value2_").change(function(){updateResultTable('emitentu',2)});

		$("select#n_bonds__").change(function(){updateResultTable('emitentu',3)});
		$("select#value__").change(function(){updateResultTable('emitentu',3)});
		$("select#value2__").change(function(){updateResultTable('emitentu',3)});	
	}
  }	
})



$(function () {
	$('.icon-service').click(function() {
		var descId = 'sd-' + $(this).attr('id').split('-')[1];
		if ($(this).hasClass('service-active')) {
			$('#' + descId).slideUp('fast');
			$(this).removeClass('service-active');
		} else {
			$('.icon-service').removeClass('service-active');
			$(this).addClass('service-active');
			$('.service-description').slideUp('fast');
			$('#' + descId).slideDown('fast');
		}
		return false;
	});
});	

$(function () {
	$('.icon-service2').click(function() {
		var descId = 'sd-' + $(this).attr('id').split('-')[1];
		if ($(this).hasClass('service-active2')) {
			$('#' + descId).slideUp('fast');
			$(this).removeClass('service-active2');
		} else {
			$('.icon-service2').removeClass('service-active2');
			$(this).addClass('service-active2');
			$('.service-description2').slideUp('fast');
			$('#' + descId).slideDown('fast');
		}
		return false;
	});
});