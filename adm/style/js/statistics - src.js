; (function ($, window, document) {
	// do stuff here and use $, window and document safely
	// https://www.phpbb.com/community/viewtopic.php?p=13589106#p13589106
	var time = 59;
	function progress() {
		var element = $('#circle');
		element.prop('title', time + ' seconds until refresh');
		//element.html(time);
		if (time === 0) {
			clearInterval(interval);
			element.removeClass("fa-circle-o-notch");
			element.addClass("fa-refresh");
			$.ajax({
				url: window.location.href + "&table=true",
				context: document.getElementById("statistics_table"),
				error: function (e, text, ee) {
					element.css("display", "none");
					if (text == "timeout") {
						$("#LoadErrorTimeout").css("display", "inline-block");
						$("#LoadError").css("display", "block");
					} else {
						$("#LoadPageError").css("display", "inline-block");
						$("#LoadError").css("display", "block");
					}
				},
				success: function (s, x) {
					time = 59;
					element.removeClass("fa-refresh");
					element.addClass("fa-circle-o-notch");
					interval = setInterval(progress, 1000);
					$(this).html(s);
				}
			});
		}
		time--;
	}
	if ($('#circle').length) var interval = setInterval(progress, 1000);
	
	$("a.simpledialog").simpleDialog({
	    opacity: 0.1,
	    width: '650px',
		height: '600px'
	});
	
	if (typeof stats !== 'undefined')
	{
		$(function () {
			$('#chart').highcharts({
				chart: {
					type: 'column'
				},
				title: {
					text: ''
				},
				subtitle: {
					text: title
				},
				xAxis: {
					categories: dates,
					labels: {
						enabled: labelenabled
					}
				},
				yAxis: {
					min: 0,
					title: {
						text: ytitle
					}
				},
				credits: {
					enabled: false,
					text: 'ForumHulp.com',
					href: 'http://forumhulp.com'
				},
				legend: {
					enabled: false,
				},
				tooltip: {
					headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
					pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
						'<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
					footerFormat: '</table>',
					shared: true,
					useHTML: true
				},
				plotOptions: {
					column: {
						pointPadding: 0.2,
						borderWidth: 0
					}
				},
				series: stats,
				lang: {
				   dayKey: 'Monthly overview',
				   monthKey: 'Yearly overview',
				   yearKey: 'All years overview'
				},
				exporting: {
					buttons: {
						dayButton: {
							enabled: btnena,
							x: -40,
							onclick: function () {
								$.ajax({
									url: window.location.href + "&t=0&table=true",
									type:'get',
									dataType: "json",
								
									success : function (s, x) {
										var chart = $('#chart').highcharts();
										
										chart.setTitle(null, { text: s[2].title });
										$('#table_title').text(s[2].descr + s[2].title);
										for (var i = 0; i < s[0].length; i++)
										{
											chart.series[i].setData(s[0][i].data, false);
										}
										chart.xAxis[0].setCategories(s[1].data, true);
										
										var uri = $("#prev").attr("href");
										uri = updateQueryStringParameter(uri, 't', 0);
										var CurrentDate = new Date(s[2].year, s[2].month, 1);
										CurrentDate.setMonth(CurrentDate.getMonth() - 1);
										uri = updateQueryStringParameter(uri, 'm', CurrentDate.getMonth());
										uri = updateQueryStringParameter(uri, 'y', CurrentDate.getFullYear());
										$('#prev').attr('href', uri);
										$('#prev').show();

										var uri = $("#next").attr("href");
										uri = updateQueryStringParameter(uri, 't', 0);
										CurrentDate.setMonth(CurrentDate.getMonth() + 2);
										uri = updateQueryStringParameter(uri, 'm', CurrentDate.getMonth());
										uri = updateQueryStringParameter(uri, 'y', CurrentDate.getFullYear());
										$('#next').attr('href', uri);
										$('#next').show();
									}
								});
							},
							symbol: 'circle',
							 _titleKey: "dayKey"
						},
						
						monthButton: {
							enabled: btnena,
							x: -65,
							onclick: function () {
								$.ajax({
									url: window.location.href + "&t=1&table=true",
									type:'get',
									dataType: "json",
								
									success : function (s, x) {
										var chart = $('#chart').highcharts();
										
										chart.setTitle(null, { text: s[2].title });
										$('#table_title').text(s[2].descr + s[2].title);
										for (var i = 0; i < s[0].length; i++)
										{
											chart.series[i].setData(s[0][i].data, false);
										}
										chart.xAxis[0].setCategories(s[1].data, true);
										
										var uri = $("#prev").attr("href");
										uri = updateQueryStringParameter(uri, 't', 1);
								
										var CurrentDate = new Date(s[2].year, s[2].month, 1);
										CurrentDate.setYear(CurrentDate.getFullYear() - 1);
										uri = updateQueryStringParameter(uri, 'y', CurrentDate.getFullYear());
										uri = removeURLParameter(uri, 'm');
										$('#prev').attr('href', uri);
										$('#prev').show();

										var uri = $("#next").attr("href");
										uri = updateQueryStringParameter(uri, 't', 1);
										CurrentDate.setYear(CurrentDate.getFullYear() + 2);
										uri = updateQueryStringParameter(uri, 'y', CurrentDate.getFullYear());
										uri = removeURLParameter(uri, 'm');
										$('#next').attr('href', uri);
										$('#next').show();
									}
								});
							},
							symbol: 'circle',
							 _titleKey: "monthKey"
						},
					
						yearButton: {
							enabled: btnena,
							x: -90,
							onclick: function () {
								$.ajax({
									url: window.location.href + "&t=2&table=true",
									type:'get',
									dataType: "json",

									success : function (s, x) {
										var chart = $('#chart').highcharts();
										chart.setTitle(null, { text: s[2].title });
										$('#table_title').text(s[2].descr + s[2].title);
										
										for (var i = 0; i < s[0].length; i++)
										{
											chart.series[i].setData(s[0][i].data, false);
										}
										chart.xAxis[0].setCategories(s[1].data, true);
										
										$('#prev').hide();
										$('#next').hide();
									}
								});
							},
							symbol: 'circle',
							_titleKey: 'yearKey',
						}
					}
				}				
			});
		});	
	}
})(jQuery, window, document);

function updateQueryStringParameter(uri, key, value) {
  var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
  var separator = uri.indexOf('?') !== -1 ? "&" : "?";
  if (uri.match(re)) {
	  return uri.replace(re, '$1' + key + "=" + value + '$2');
  }
  else {
    return uri + separator + key + "=" + value;
  }
}

function removeURLParameter(url, parameter) {
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        for (var i= pars.length; i-- > 0;) {    
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        return url;
    } else {
        return url;
    }
}
