google.charts.load('current', {'packages': ['corechart']});

google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var base_data = JSON.parse($('#chart_data_week').html());
    var data = google.visualization.arrayToDataTable(base_data);
    var options = {
        title: 'Операції за фінансовий тиждень',
        legend: {position: 'bottom'},
        colors: [ 'green','red', 'blue']
    };
    var chart = new google.visualization.LineChart(document.getElementById('week_chart'));
    chart.draw(data, options);

    var base_data2 = JSON.parse($('#chart_data_month').html());
    var data2 = google.visualization.arrayToDataTable(base_data2);
    var options2 = {
        title: 'Операції за місяць',
        legend: {position: 'bottom'},
        colors: [ 'green','red', 'blue']
    };
    var chart2 = new google.visualization.LineChart(document.getElementById('month_chart'));

    chart2.draw(data2, options2);

    var base_data3 = JSON.parse($('#chart_data_year').html());
    var data3 = google.visualization.arrayToDataTable(base_data3);
    var options3 = {
        title: 'Операції за рік',
        legend: {position: 'bottom'},
        colors: [ 'green','red', 'blue']
    };
    var chart3 = new google.visualization.LineChart(document.getElementById('year_chart'));

    chart3.draw(data3, options3);
}