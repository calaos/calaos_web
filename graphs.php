<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script src="assets/js/highcharts.js"></script>
<script src="assets/js/exporting.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="http://twitter.github.com/bootstrap/assets/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" media="all" href="assets/css/daterangepicker.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/date.js"></script>
<script type="text/javascript" src="assets/js/daterangepicker.js"></script>
<script type="text/javascript">
$(function () {

  var chart;

                var options = {
                    chart: {
                        renderTo: 'container',
                        defaultSeriesType: 'spline',
                        zoomType: 'x'
                    },
                    title: {
                        text: 'Temperatures',
                        x: -20 //center
                    },
                    rangeSelector : {
                        buttons : [{
                          type : 'hour',
                          count : 1,
                          text : '1h'
                        },{
                          type : 'hour',
                          count : 3,
                          text : '3h'
                        },{
                          type : 'hour',
                          count : 6,
                          text : '6h'
                        },{
                          type : 'hour',
                          count : 9,
                          text : '9h'
                        },{
                          type : 'hour',
                          count : 12,
                          text : '12h'
                        },{
                          type : 'all',
                          count : 1,
                          text : 'All'
                        }],
                        selected : 5,
                        inputEnabled : false
                    },
                    xAxis: {
                        type: 'datetime',
                        //tickInterval: 3600 * 1000, // one hour
                        tickWidth: 0,
                        gridLineWidth: 1,
                        maxZoom: 12 * 3600000, // 12 hours
                        //labels: {
                        //    align: 'center',
                        //    x: -3,
                        //    y: 20,
                        //    formatter: function() {
                        //        return Highcharts.dateFormat('%d.%m.%y', this.value) + '<br/>' + Highcharts.dateFormat('%H:%M',this.value) ;
                        //    }
                        //}
                        
                    },
                    yAxis: { // Primary yAxis,
                        title: {
                            text: 'Temperature',
                        },
                        min: 0,
                        labels: {
                            formatter: function() {
                                return this.value + ' °C';
                            },
                            style: {
                                color: '#808080'
                            }
                        }
                    },            
                    legend: {
                        backgroundColor: '#FFFFFF',
                        reversed: false
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 4,
                            states: {
                                hover: {
                                    lineWidth: 5
                                }
                            },
                            marker: {
                                enabled: false,
                                states: {
                                    hover: {
                                        enabled: true,
                                        symbol: 'circle',
                                        radius: 8,
                                        lineWidth: 1
                                    }
                                }
                            }
                        }
                    },
                    series:  {
                        type: 'spline',
                        color: 'blue',
                        name: 'input_10',
                        yAxis: 0
                    },
                    //exporting: {
                    //    url: 'http://export.highcharts.com/index-utf8-encode.php'
                    //}
                }




  chart = new Highcharts.Chart(options);

  $('#reportrange').daterangepicker(
  {
    ranges: {
      'Today': [Date.today(), Date.today().add({ days: 1 })],
      'Yesterday': [Date.today().add({ days: -1 }), Date.today()],
      'Last 7 Days': [Date.today().add({ days: -6 }), Date.today().add({days: 1})],
      'Last 30 Days': [Date.today().add({ days: -29 }), Date.today().add({days: +1})],
      'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth().add({days: 1})],
      'Last Month': [Date.today().moveToFirstDayOfMonth().add({ months: -1 }), Date.today().moveToFirstDayOfMonth().add({ days: -1 })]
    },
    opens: 'left',
    format: 'dd/MM/yyyy',
    separator: ' to ',
    startDate: Date.today().add({ days: -29 }),
   endDate: Date.today(),
   minDate: '01/01/2000',
   maxDate: Date.today().add({days: 1}),
   locale: {
      applyLabel: 'Submit',
      fromLabel: 'From',
      toLabel: 'To',
      customRangeLabel: 'Custom Range',
      daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
      monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
      firstDay: 1
    },
    showWeekNumbers: true,
    buttonClasses: ['btn-danger'],
   dateLimit: false
  }, 

  function(start, end) {
    $('#reportrange span').html(start.toString('MMMM d, yyyy') + ' - ' + end.toString('MMMM d, yyyy'));

    url = "cgi/graph.cgi?probe=input_10&precision=h&start=" + (start.getTime() / 1000) + "&stop=" + (end.getTime() / 1000); 
    console.log(url);

    $.getJSON(url, function(json) {
      if (chart.series.length) {
        chart.series[0].remove();
      }
      chart.addSeries(json);


      console.log(Date.today() + "   " +  Date.today().add({ days: 1 }));

    });
  }


);

  //Set the initial state of the picker label
  $('#reportrange span').html(Date.today().add({ days: -29 }).toString('MMMM d, yyyy') + ' - ' + Date.today().toString('MMMM d, yyyy'));

});
</script>


<div class="well">

  <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
    <i class="icon-calendar icon-large"></i>
    <span></span> <b class="caret" style="margin-top: 8px"></b>
  </div>
  <div id="container"></div>
</div>
