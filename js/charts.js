google.charts.load('current', {'packages': ['corechart']});

google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var base_data = JSON.parse($('#chart_data_month').html());
    var data = google.visualization.arrayToDataTable(base_data);
    var options = {
        title: 'Заявки на місяць',
        legend: {position: 'bottom'}
    };
    var chart = new google.visualization.LineChart(document.getElementById('month_chart'));
    chart.draw(data, options);

    var base_data2 = JSON.parse($('#chart_data_3_month').html());
    var data2 = google.visualization.arrayToDataTable(base_data2);
    var options2 = {
        title: 'Заявки на 3 місяці',
        legend: {position: 'bottom'}
    };
    var chart2 = new google.visualization.LineChart(document.getElementById('3_month_chart'));

    chart2.draw(data2, options2);
}