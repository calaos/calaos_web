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
      type: 'spline',
    },
    title: {
      text: 'Temperature',
    },
    xAxis: {
      type: 'datetime'
    },
    yAxis: {
      title: {
        text: '°C'
      },
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'top',
      x: -10,
      y: 100,
      borderWidth: 0
    },
    series: []
  };


  chart = new Highcharts.Chart(options);



  $('#reportrange').daterangepicker(
  {
    ranges: {
      'Today': ['today', 'today'],
      'Yesterday': ['yesterday', 'yesterday'],
      'Last 7 Days': [Date.today().add({ days: -6 }), 'today'],
      'Last 30 Days': [Date.today().add({ days: -29 }), 'today'],
      'This Month': [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
      'Last Month': [Date.today().moveToFirstDayOfMonth().add({ months: -1 }), Date.today().moveToFirstDayOfMonth().add({ days: -1 })]
    },
    opens: 'left',
    format: 'dd/MM/yyyy',
    separator: ' to ',
    startDate: Date.today().add({ days: -29 }),
   endDate: Date.today(),
   minDate: '01/01/2000',
   maxDate: '31/12/2013',
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
      options.series[0] = json;
      console.log(json);
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
