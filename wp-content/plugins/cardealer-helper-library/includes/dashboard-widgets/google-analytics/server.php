<html>
<body>
<script>
(function(w,d,s,g,js,fs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
}(window,document,'script'));
</script>

<div id="chart-1-container"></div>
<div id="chart-2-container"></div>
<div id="chart-3-container"></div>

<script>
gapi.analytics.ready(function() {
  /**
   * Authorize the user with an access token obtained server side.
   */
  gapi.analytics.auth.authorize({
    'serverAuth': {
      'access_token': 'ya29.Ell8BN6LIbpQnP0hHjB9Q69SvAgiqslRakA7sShgTxvv0LaOiQevZsbuzor2Mjo0m9jcYmw53IrOW8FUaq5S3EIb8W8oFf8gVL-qatDe2HswtK22wZvSlrO0GA'
    }
  });
  
  /**
   * Creates a new DataChart instance showing sessions over the past 30 days.
   * It will be rendered inside an element with the id "chart-1-container".
   */
  var dataChart1 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:154218288', // <-- Replace with the ids value for your view.
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
      'metrics': 'ga:sessions,ga:users',
      'dimensions': 'ga:date'
    },
    chart: {
      'container': 'chart-1-container',
      'type': 'LINE',
      'options': {
        'width': '100%'
      }
    }
  });
  dataChart1.execute();
  /**
   * Creates a new DataChart instance showing top 5 most popular demos/tools
   * amongst returning users only.
   * It will be rendered inside an element with the id "chart-3-container".
   */
  var dataChart2 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:154218288', // <-- Replace with the ids value for your view.
      'start-date': '30daysAgo',
      'end-date': 'today',
      'metrics': 'ga:pageviews',
      'dimensions': 'ga:pagePathLevel1',
      'sort': '-ga:pageviews',
      'filters': 'ga:pagePathLevel1!=/',
      'max-results': 7
    },
    chart: {
      'container': 'chart-2-container',
      'type': 'PIE',
      'options': {
        'width': '100%',
        'pieHole': 4/9,
      }
    }
  });
  dataChart2.execute();
  
  
  /**
   * Create a table chart showing top browsers for users to interact with.
   * Clicking on a row in the table will update a second timeline chart with
   * data from the selected browser.
   */
	var dataChart3 = new gapi.analytics.googleCharts.DataChart({
    query: {
      'ids': 'ga:154218288', // <-- Replace with the ids value for your view.
      'start-date': '30daysAgo',
      'end-date': 'today',
      'metrics': 'ga:pageviews',
      'dimensions': 'ga:pagePathLevel1',
      'sort': '-ga:pageviews',
      'filters': 'ga:pagePathLevel1!=/',
      'max-results': 7
    },
    chart: {
      type: 'TABLE',
      container: 'chart-3-container',
      options: {
        width: '100%'
      }
    }
  });
  dataChart3.execute();
});
</script>

</body>
</html>