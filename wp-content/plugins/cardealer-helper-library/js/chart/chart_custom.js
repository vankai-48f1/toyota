draw_lead_graph();
function draw_lead_graph( feed = '' ) {
	if(typeof lead_chart == 'undefined') return;
	if( feed != '') lead_chart = feed;
	var chartData = jQuery.parseJSON( lead_chart );
	var color = Chart.helpers.color;
	var barChartData = {
		labels: [chartData.chart_type],
		datasets: [{
			label: chartData.rmi_result[0].lead_type,
			backgroundColor: '#e05d6f',
			borderColor: '#e05d6f',
			borderWidth: 1,
			data: [
				chartData.rmi_result[0].total
			]
		},
		{
			label: chartData.mno_result[0].lead_type,
			backgroundColor: '#418bca',
			borderColor: '#418bca',
			borderWidth: 1,
			data: [
				chartData.mno_result[0].total
			]
		},
		{
			label: chartData.std_result[0].lead_type,
			backgroundColor: '#16a085',
			borderColor: '#16a085',
			borderWidth: 1,
			data: [
				chartData.std_result[0].total
			]
		},
		{
			label: chartData.fni_result[0].lead_type,
			backgroundColor: '#565666',
			borderColor: '#565666',
			borderWidth: 1,
			data: [
				chartData.fni_result[0].total
			]
		}]
	};
	var ctx = document.getElementById("lead_canvas").getContext("2d");
	if (typeof window.myBar !== "undefined") {
		window.myBar.destroy();
	}
	window.myBar = new Chart(ctx, {
		type: 'bar',
		data: barChartData,
		options: {
			responsive: true,
			legend: {
				position: 'top',
			},
			scales: {
				yAxes: [{
					ticks: {
						beginAtZero: true
					}
				}]
			}
		}
	});
}

jQuery(document).on('change', '#display_by', function() {
	var leadForm = jQuery('#lead_form').val();
	var display_by = jQuery('#display_by').val();
	jQuery.ajax({
		url			: ajaxurl,
		type		: 'post',
		dataType	: 'json',
		data		: 'action=get_chart_data&leadForm='+ leadForm +'&display_by='+ display_by,
		beforeSend	: function() {
			jQuery('#display_by').after('<i class="fas fa-sync-alt fa-spin pgs_graph_loader"></i>');
		},
		success		: function( result ){
			if( result.status == 1 ){
				jQuery('.pgs_graph_loader').remove();
				draw_lead_graph( result.chart_feed );
			} else {
				jQuery('div#container').html(result.msg);
			}
		},
		error		: function( result ){
			jQuery('.pgs_graph_loader').remove();
			jQuery('div#container').addClass('error_msg');
			jQuery('div#container').html(result.msg);
		}
	});
});


/*
* Google Analytics
*/

(function(w,d,s,g,js,fs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
}(window,document,'script'));

if(typeof ga_chart !== 'undefined') {
	var ga_chart_data = jQuery.parseJSON( ga_chart );
	gapi.analytics.ready(function() {
		//Google Analytics Overview
		if( jQuery('#google-analytics').length ){
			jQuery.getJSON( "https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A"+ ga_chart_data.view_id +"&start-date="+ ga_chart_data.start_date +"&end-date="+ ga_chart_data.end_date +"&metrics=ga%3Ausers%2Cga%3Apageviews&dimensions=ga%3Adate&access_token=" + ga_chart_data.access_token, function( data ) {
				if( data.totalResults > 0 ) {
					var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November","December"];
					var label = [];
					var pageviews = [];
					var users = [];
					var chartLabel = ['Users', 'Pageviews'];
					jQuery.each( data.rows, function( key, value ) {
						var date = new Date( value[0].slice(0,4)+'-'+value[0].slice(4,6)+'-'+value[0].slice(6,8) );
						var month = monthNames[ date.getMonth() ];
						var day = ('0' + date.getDate()).slice(-2);

						label[key] = day + ' ' + month;
						users[key] = value[1];
						pageviews[key] = value[2];
					});
					draw_chart( 'line', chartLabel, [ users, pageviews ], 'google-analytics', label );
				} else {
					jQuery('#google-analytics').remove();
				}
			});
		}

		//Device Chart
		if( jQuery('#device-chart-container').length ){
			jQuery.getJSON( "https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A"+ ga_chart_data.view_id +"&start-date="+ ga_chart_data.start_date +"&end-date="+ ga_chart_data.end_date +"&metrics=ga%3Apageviews&dimensions=ga%3AdeviceCategory&access_token=" + ga_chart_data.access_token, function( data ) {
				if( data.totalResults > 0 ) {
					var devices = [];
					var pageviews = [];
					var chartLabel = ['Device Usage'];
					jQuery.each( data.rows, function( key, device ) {
						devices[key] = toTitleCase(device[0]);
						pageviews[key] = device[1];
					});
					draw_chart( 'pie', chartLabel, [pageviews], 'device-chart-container', devices );
				} else {
					jQuery('#device-chart-container').parent().remove();
				}
			});
		}

		//Mobile Chart
		if( jQuery('#mobile-device-container').length ){
			jQuery.getJSON( "https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A"+ ga_chart_data.view_id +"&start-date="+ ga_chart_data.start_date +"&end-date="+ ga_chart_data.end_date +"&metrics=ga%3Apageviews&dimensions=ga%3AmobileDeviceInfo&access_token=" + ga_chart_data.access_token, function( data ) {
				if( data.totalResults > 0 ) {
					var deviceName = [];
					var pageviews = [];
					var chartLabel = ['Mobile Devices'];
					jQuery.each( data.rows, function( key, device ) {
						deviceName[key] = toTitleCase(device[0]);
						pageviews[key] = device[1];
					});
					draw_chart( 'pie', chartLabel, [pageviews], 'mobile-device-container', deviceName );
				} else {
					jQuery('#mobile-device-container').parent().remove();
				}
			});
		}

		//Age Chart
		if( jQuery('#age-chart-container').length ){
			jQuery.getJSON( "https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A"+ ga_chart_data.view_id +"&start-date="+ ga_chart_data.start_date +"&end-date="+ ga_chart_data.end_date +"&metrics=ga%3Apageviews&dimensions=ga%3AuserAgeBracket&access_token=" + ga_chart_data.access_token, function( data ) {
				if( data.totalResults > 0 ) {
					var deviceName = [];
					var pageviews = [];
					var chartLabel = ['Ages'];
					jQuery.each( data.rows, function( key, device ) {
						deviceName[key] = toTitleCase(device[0]);
						pageviews[key] = device[1];
					});
					draw_chart( 'pie', chartLabel, [pageviews], 'age-chart-container', deviceName );
				} else {
					jQuery('#age-chart-container').parent().remove();
				}
			});
		}

		//Gender Chart
		if( jQuery('#gender-chart-container').length ){
			jQuery.getJSON( "https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A"+ ga_chart_data.view_id +"&start-date="+ ga_chart_data.start_date +"&end-date="+ ga_chart_data.end_date +"&metrics=ga%3Apageviews&dimensions=ga%3AuserGender&access_token=" + ga_chart_data.access_token, function( data ) {
				if( data.totalResults > 0 ) {
					var deviceName = [];
					var pageviews = [];
					var chartLabel = ['Ages'];
					jQuery.each( data.rows, function( key, device ) {
						deviceName[key] = toTitleCase(device[0]);
						pageviews[key] = device[1];
					});
					draw_chart( 'pie', chartLabel, [pageviews], 'gender-chart-container', deviceName );
				} else {
					jQuery('#gender-chart-container').parent().remove();
				}
			});
		}

		//Browser Chart
		if( jQuery('#browser-pie-chart-container').length ){
			jQuery.getJSON( "https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A"+ ga_chart_data.view_id +"&start-date="+ ga_chart_data.start_date +"&end-date="+ ga_chart_data.end_date +"&metrics=ga%3Apageviews&dimensions=ga%3Abrowser&access_token=" + ga_chart_data.access_token, function( data ) {
				if( data.totalResults > 0 ) {
					var deviceName = [];
					var pageviews = [];
					var chartLabel = ['Ages'];
					jQuery.each( data.rows, function( key, device ) {
						deviceName[key] = toTitleCase(device[0]);
						pageviews[key] = device[1];
					});
					draw_chart( 'pie', chartLabel, [pageviews], 'browser-pie-chart-container', deviceName );
				} else {
					jQuery('#browser-pie-chart-container').remove();
				}
			});
		}
	});
}

function draw_chart( type= 'line', chartLabel= [], chartFeed= [], elementId = 'google-analytics', label ) {
	if( chartFeed.length > 0 )
	{
		var datasets = [];
		var colorsArray = ['#e05d6f', '#418bca', '#16a085', '#E4E5E7', '#D54E21', '#3F4E62', '#B18F6A', '#44B272', '#2046F2', '#FFB400', '#FF5733', '#922B21', '#C0392B', '#9B59B6', '#2980B9', '#3498DB', '#17A589', '#F39C12', '#7B7D7D', '#839192', '#34495E', '#F5B041', '#99A3A4', '#5F6A6A', '#5499C7'];
		jQuery.each( chartFeed, function( key, feed ) {
			var color = [];
			if( type == 'pie' )
			{
				jQuery.each( feed, function( a, b ) {
					color[a] = colorsArray[a];
				});
			} else {
				color[0] = colorsArray[key];
			}
			datasets[key] = {
								label : chartLabel[key],
								backgroundColor : color,
								borderColor: color,
								data : feed,
								fill : false
							};
		});
	} else {
		return;
	}

	var config = {
		type: type,
		data: {
			labels: label,
			datasets: datasets
		},
		options: {
			responsive: true,
			title:{
				display:false,
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			}
		}
	};
	 var ctx = document.getElementById( elementId ).getContext("2d");
	 window.myLine = new Chart(ctx, config);
}

// Convert text to Camel Case
function toTitleCase(str) {
    return str.replace(/(?:^|\s)\w/g, function(match) {
        return match.toUpperCase();
    });
}

// Dashboard Goal scripts
jQuery(document).ready( function(){
	if(typeof ga_goal !== 'undefined') {
		var ga_goal_data = jQuery.parseJSON( ga_goal );
		var opts = {
			  angle: 0.11, // The span of the gauge arc
			  lineWidth: 0.44, // The line thickness
			  radiusScale: 0.50, // Relative radius
			  pointer: {
				length: 0.66, // // Relative to gauge radius
				strokeWidth: 0.050, // The thickness
				color: '#000000' // Fill color
			  },
			  limitMax: false,     // If false, max value increases automatically if value > maxValue
			  limitMin: false,     // If true, the min value of the gauge will be fixed
			  colorStart: '#6FADCF',   // Colors
			  colorStop: '#8FC0DA',    // just experiment with them
			  strokeColor: '#E0E0E0',  // to see which ones work best for you
			  generateGradient: true,
			  highDpiSupport: true     // High resolution support
			};

		var goals_data = ga_goal_data.goal_ids;
		jQuery(goals_data).each( function( index, element ){
			var target = document.getElementById('goal-'+ element.id); // your canvas element
			var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
			gauge.maxValue = 100; // set max gauge value
			gauge.setMinValue( 0 );  // Prefer setter over gauge.minValue = 0
			gauge.animationSpeed = 11; // set animation speed (32 is default value)
			gauge.set( element.goal_rate ); // set actual value
		});

	}

	if( jQuery( ".goal-content" ).length ) {
		jQuery( ".goal-content" ).dialog({
		  autoOpen: false,
		  show: {
			effect: "blind",
			duration: 1000
		  },
		  hide: {
			effect: "explode",
			duration: 1000
		  }
		});
		jQuery( ".cd_dialog" ).on( "click", function( event ) {
			event.preventDefault();
			var dialog = jQuery(this).attr('data-id');
			jQuery( '#' + dialog ).dialog( "open" );
			/* Add pgs_dialog class to parent div of dialog to differenciate from other dialogs for close button issue */
			if ( jQuery('.ui-dialog').attr('aria-describedby') == dialog ) jQuery('.ui-dialog').addClass('pgs_goal_dialog');
		});
	}
});
