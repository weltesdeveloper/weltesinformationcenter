/*!
 * Power Table v1.1 (http://rcpyksl.com/table)
 * Copyright 2014
 */
$.fn.powertable = function(setdef){
	var defaults = {
		ajaxseturl : "0",
		ajaxsetmethod : "POST",
		sortclass : "text-success",
		headclass : "text-danger",
		showsize : "1000",
		loadmoresize : "10"
		};
		var setdef = $.extend(defaults, setdef);
		return this.each(function(){
		//preload
		$("<style type='text/css'> .powsericon { cursor : pointer; } .powsericon2 { cursor:pointer; } </style>").appendTo("head");
		$("thead th").addClass(setdef.headclass);
		var totalsize=setdef.showsize;
		var totalsize2;
		//indexing
		var maxth=0;
		var unitext=new Array();
		var lastsearch=new Array();
		var thindex=$(this).find("th");
		$.each(thindex, function( i, val ) {
		$(this).attr("uni",i);
		unitext[i]=$(this).html();
		lastsearch[i]=$(this).html();
		maxth++;
		});
		var tbodytrindex=$(this).find("tbody tr");
		$.each(tbodytrindex, function( i, val ) {
		$(this).attr("unictr",i);
		var newtdindex=$(this).find("td");
		$.each(newtdindex, function( i2, val2 ) {
		$(this).attr("unictr",i);
		});
		});
		var tbodyindex=$(this).find("tbody td,tfoot td");
		$.each(tbodyindex, function( i, val ) {
		var newi=i%maxth;
		$(this).attr("unic",newi);
		$(this).find("span").attr("unic",newi);
		});
		
		//search settings
		var powerfind=$(this).find("th[search='1']");
		var powerfindnum=powerfind.html();
		
		$.each(powerfind, function( i, val ) {
		$(this).html($(this).html()+"<span class=\"glyphicon glyphicon-search powsericon\" style='float:right;' uni='"+$(this).attr("uni")+"'></span>");
		});
		
		$(document).on("click",".powsericon",function(){
		var unis=$(this).attr("uni");
		var powsericonsel=$("th[uni=\""+unis+"\"]");
		powsericonsel.html("<input type=text class=\"form-control input-sm powserval uni"+unis+"\" placeholder=\""+lastsearch[unis]+"\" value=\""+lastsearch[unis]+"\" unicod=\""+unis+"\">");
		$(".uni"+unis+"").focus();
		});
		
		$(document).on("keyup",".powserval",function(e){
		var code = e.keyCode || e.which; 
		if (code == 13) { $(this).blur(); }
		var powuni=$(this).attr("unicod");
		var powseaval=$(this).val();
		var powseaplace=$("td[unic=\""+powuni+"\"]");
		$("tbody tr").hide();
		$.each(powseaplace, function( i, val ) {
		var yenithishtml=$(this).html().toUpperCase();
		if (yenithishtml.search(powseaval.toUpperCase())>-1) {
		var openunictr=$(this).attr("unictr");
		$("tbody tr[unictr=\""+openunictr+"\"]").show(); 
		}
		});
		if ($("#powerresultsize").val()>0) { 
		$("tbody tr:visible").slice($("#powerresultsize").val(),100000).hide(); }
		else {
		$("tbody tr:visible").slice(setdef.showsize,100000).hide(); 
		}
		powertotal();
		pagination();
		});
		
		$(document).on("blur",".powserval",function(e){
		var oldunicod=$(this).attr("unicod");
		if ($(this).val()!="") { 
		var oldplaceholder=$(this).val();
		lastsearch[oldunicod]=oldplaceholder; 
		} else { 
		var oldplaceholder=unitext[oldunicod];
		lastsearch[oldunicod]=unitext[oldunicod]; 
		}		
		$("thead th[uni=\""+oldunicod+"\"]").html(oldplaceholder+"<span class=\"glyphicon glyphicon-search powsericon\" style='float:right;' uni='"+oldunicod+"'></span>");
		$("thead th[uni=\""+oldunicod+"\"][sort=\"1\"]").append("<span class=\"glyphicon glyphicon-sort powsersorticon powsericon2\" style='float:right; padding-right:7px;' uni='"+oldunicod+"'></span>");
		});
		
		//sort settings
		var powerfind2=$(this).find("th[sort='1']");
		var powerfindnum2=powerfind2.html();
		var serilis=new Array();
		var newserilis;
		var lasttik="-1";
		var sortby="0";
		$.each(powerfind2, function( i, val ) {
		var elemsearch=$(this).attr("search"); if (isNaN(elemsearch)) { elemsearch=0; }
		var elemsort=$(this).attr("sort");  if (isNaN(elemsort)) { elemsort=0; }
		var elemfilter=$(this).attr("filter");  if (isNaN(elemfilter)) { elemfilter=0; }
		var elemtotal=parseInt(elemsearch)+parseInt(elemsort)+parseInt(elemfilter);
		if (elemtotal==1 && elemsort==1) {
		$(this).html($(this).html()+"<span class=\"glyphicon glyphicon-sort powsersorticon\" style='float:right; padding-right:7px;' uni='"+$(this).attr("uni")+"'></span>");
		} else { 
		$(this).html($(this).html()+"<span class=\"glyphicon glyphicon-sort powsersorticon powsericon2\" style='float:right; padding-right:7px;' uni='"+$(this).attr("uni")+"'></span>");
		}
		});
		function isInt(n) {
		return n % 1 === 0;
		} 
		function isFloat(n) {
		return n === +n && n !== (n|0);
		}
		function powerSort(a, b) { //return (a[1]-b[1]);
		if (isInt(a[1]) || isFloat(a[1])) { return (a[1]-b[1]); } 
		else { return (a[1] < b[1] ? -1 : (a[1] > b[1] ? 1 : 0)); }
		}
		$(document).on("click",".powsericon2",function(){
		var unis=$(this).attr("uni");
		$("thead th").removeClass(setdef.sortclass).addClass(setdef.headclass);
		$(".powsersorticon").removeClass("glyphicon-arrow-up glyphicon-arrow-down glyphicon-sort").addClass("glyphicon-sort");
		$("thead th[uni=\""+unis+"\"]").removeClass(setdef.headclass).addClass(setdef.sortclass);
		if (lasttik==unis) { lasttik="-1"; var sortby=1; } 
		else { 
		lasttik=unis; var sortby="0"; }
		var powsericonsel=$("td[unic=\""+unis+"\"]");
		$.each(powsericonsel, function( i, val ) {
		serilis[i]=[$(this).attr("unictr"),$(this).html()];
		//$(this).attr("aaa",$(this).html()+"8975"+$(this).attr("unictr"));
		});
		serilis.sort(powerSort);
		//alert(serilis);
		if (sortby=="1") { serilis.reverse(); $(".powsersorticon[sort!='1'][uni=\""+unis+"\"]").removeClass("glyphicon-arrow-down  glyphicon-sort").addClass("glyphicon-arrow-up"); } 
		else { $(".powsersorticon[sort!='1'][uni=\""+unis+"\"]").removeClass("glyphicon-arrow-up  glyphicon-sort").addClass("glyphicon-arrow-down"); } 

		
		$("thead th").removeClass("glyphicon-arrow-down").removeClass("glyphicon-arrow-up").removeClass("glyphicon-sort");
		$.each(serilis, function( i, val ) {
		//var serinos=val.split("¿¿");
		var serinos=val;
		newserilis=newserilis+"<tr unictr=\""+serinos[0]+"\">"+$("tbody tr[unictr=\""+serinos[0]+"\"]").html()+"</tr>";
		});
		$("tbody").html("").append(newserilis);
		newserilis="";
		
		if ($("#powerresultsize").val()>0) { 
		$("tbody tr:visible").slice($("#powerresultsize").val(),1000).hide(); }
		else {
		$("tbody tr:visible").slice(setdef.showsize,100000).hide(); 
		}
		powertotal();
		pagination();
		});
		
		//input settings
		$(document).on("click","tbody td[input=\"1\"]",function(){
		if ($(this).find("input").length) { return false; }
		$(this).html("<input type=\"text\" class=\"form-control\" ajax-id=\""+$(this).attr("ajax-id")+"\" id=\"nowplayingg\" unic=\""+$(this).attr("unic")+"\"  unictr=\""+$(this).attr("unictr")+"\" placeholder=\""+$(this).html()+"\" value=\""+$(this).html()+"\">");
		$("#nowplayingg").focus();
		});
		$(document).on("blur","#nowplayingg",function(){
		$("tbody td[unic=\""+$(this).attr("unic")+"\"][unictr=\""+$(this).attr("unictr")+"\"]").html($(this).val());
		if (setdef.ajaxseturl!=0) { 
		$.ajax({
		type: setdef.ajaxsetmethod,
		data : { "value" :  $(this).val() , "id" :  $(this).attr("ajax-id") } ,
		url: setdef.ajaxseturl,
		success: function(msg){ ajaxsetfunction(msg); }
		});
		}
		});
		$(document).on("keydown","#nowplayingg",function(e){
		var code = e.keyCode || e.which; 
		if (code == 13) { $(this).blur(); }
		if (code == 9)  { 
		if ($("td[unic=\""+$(this).attr("unic")+"\"][unictr=\""+$(this).attr("unictr")+"\"]").next().closest("td[input=\"1\"]").length!=0) { 
		$("td[unic=\""+$(this).attr("unic")+"\"][unictr=\""+$(this).attr("unictr")+"\"]").next().closest("td[input=\"1\"]").click(); }
		else { var newwtos=parseInt($(this).attr("unictr"))+parseInt(1); 
		$("tbody td[unictr=\""+newwtos+"\"]").first().click(); }
		}
		});
		
		//quickly headmenu
		var powerfind3=$(this).find("thead th");
		$.each(powerfind3, function( i, val ) {
		var elemsearch=$(this).attr("search"); if (isNaN(elemsearch)) { elemsearch=0; }
		var elemsort=$(this).attr("sort");  if (isNaN(elemsort)) { elemsort=0; }
		var elemfilter=$(this).attr("filter");  if (isNaN(elemfilter)) { elemfilter=0; }
		var elemtotal=parseInt(elemsearch)+parseInt(elemsort)+parseInt(elemfilter);
		if (elemtotal==1) {
		if (elemsearch==1) { 
		$(this).addClass("powsericon");
		}
		if (elemsort==1) { 
		$(this).addClass("powsericon2");
		}
		}
		});
		
		
		//display results
		$("tfoot td span[showresult=\"1\"]").html("<select id=\"powerresultsize\" style=\"border:0; background-color:transparent; font-weight:bold;\"><option value=10>10</option><option value=25>25</option><option value=50>50</option><option value=100>100</option><option value=500>500</option><option value=1000>1000</option></select>");
		$(function(){
		if ($("#powerresultsize").val()>"0") {  
		$("#powerresultsize").val(setdef.showsize);
		}
		$("tbody tr").hide();
		$("tbody tr").slice(0,+setdef.showsize).show();
		powertotal();
		pagination();
		});
		
		$(document).on("change","#powerresultsize",function(){
		$("tbody tr").hide();
		$("tbody tr").slice(0,+$(this).val()).show();
		powertotal();
		setdef.showsize=$(this).val();
		pagination();
		});
		
		//loadmore
		if ($("tfoot td span[loadmore=\"1\"]").length=="1") {
		if ($("tfoot td span[showresult=\"1\"]").length=="1" && $("tfoot td span[pagination=\"1\"]").length=="1") {
		alert('Loadmore not used with showresult or pagination.');
		} else {
		$(document).on("click","span[loadmore=\"1\"]",function(){
		totalsize2=parseInt(totalsize)+parseInt(setdef.loadmoresize);
		$("tbody tr").slice(totalsize,totalsize2).show(); 
		totalsize=totalsize2;
		if (totalsize2>=$("tbody tr").length) {
		$(this).hide();
		}
		powertotal();
		pagination();
		});
		}
		}
		
		//pagination
		if ($("tfoot td span[pagination=\"1\"]").length=="1") {
		if ($("tfoot td span[loadmore=\"1\"]").length=="1") {
		alert('Pagination not used with loadmore.');
		} else {
		pagination();
		}
		}
		$(document).on("click","tfoot td span[pagination=\"1\"] li a",function(){
		var currentactive=$("ul li[class=\"active\"] a").html();
		if ($(this).html()=="«") { 
		if (parseInt(currentactive)-1==0) {
		return false;
		} else { 
		var newpdr=parseInt(currentactive)-1;
		}
		} else if ($(this).html()=="»") { 
		var datasizepower=$("tbody tr").length;
		var pagesizepower=Math.ceil(datasizepower/setdef.showsize);
		if (parseInt(currentactive)+1>pagesizepower) {
		return false;
		} else {
		var newpdr=parseInt(currentactive)+1;
		}
		} else { 
		var newpdr=$(this).html();
		}
		$("ul li").removeClass("active");
		$("ul li[class2=\"pgr"+newpdr+"\"]").addClass("active");
		var pageresult2=newpdr*setdef.showsize;
		var pageresult=pageresult2-parseInt(setdef.showsize);
		$("tbody tr").hide();
		$("tbody tr").slice(pageresult,pageresult2).show();
		powertotal();
		});
		
		
		
		//functions
		function powertotal() {
		var toplar=0;
		var totalsize=$("tfoot td span[total=\"1\"]");
		$.each(totalsize, function( i, val ) {
		var totalsize2=$("tbody td[unic=\""+$(this).attr("unic")+"\"]:visible");
		$.each(totalsize2, function( i, val ) {
		toplar=toplar+parseFloat($(this).html());
		});
		$(this).html(Math.round(toplar * 100) / 100);
		toplar=0;
		});
		}
		function pagination() { 
		var prevadd="<li><a href=\"#\">&laquo;</a></li>";
		var nextadd="<li><a href=\"#\">&raquo;</a></li>";
		var pagedata="<li class=\"active\"><a href=\"#\">1</a></li>";
		var datasize=$("tbody tr").length;
		var pagesize=Math.ceil(datasize/setdef.showsize);
		for (var i=1;i<=pagesize;i++) {
		if (i==1) { 
		pagedata="<li class=\"active\" class2=\"pgr1\"><a href=\"#\">1</a></li>";
		} else {
		pagedata=pagedata+"<li class2=\"pgr"+i+"\"><a href=\"#\">"+i+"</a></li>";
		}
		}
		$("tfoot td span[pagination=\"1\"]").html("");
		$("tfoot td span[pagination=\"1\"]").append("<ul class=\"pagination pagination-sm\" style=\"margin:0;\">"+prevadd+pagedata+nextadd+"</ul>");  
		}
		//create table and complete
		$("tbody td[input=\"1\"]").css({"cursor":"pointer"});
		$(this).addClass("table table-hover table-striped table-bordered");
	});
	
}