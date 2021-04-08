jQuery(document).ready(function () {

    $.ajax({
        url: 'https://www.google.com/jsapi?callback',
        cache: true,
        dataType: 'script',
        success: function () {
            // google.charts.load('current', {packages:["orgchart"]});
            google.load('visualization', '1', {
                packages: ['corechart', "orgchart"], 'callback': function () {
                    $.ajax({
                        type: "POST",
                        // dataType: "html",
                        url: 'chart/operations',
                        success: function (data) {
                            data = JSON.parse(data);
                            let data_chart = google.visualization.arrayToDataTable(data);
                            let options = {
                                title: 'Операції за місяць',
                                legend: {position: 'bottom'},
                                colors: ['green', 'red', 'blue']
                            };
                            let chart = new google.visualization.LineChart(document.getElementById('operations_month_chart'));

                            chart.draw(data_chart, options);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        // dataType: "html",
                        url: 'chart/operations/true',
                        success: function (data) {
                            data = JSON.parse(data);
                            let data_chart = google.visualization.arrayToDataTable(data);
                            let options = {
                                title: 'Плановані операції на місяць',
                                legend: {position: 'bottom'},
                                colors: ['green', 'red', 'blue']
                            };
                            let chart = new google.visualization.LineChart(document.getElementById('planned_operations_month_chart'));

                            chart.draw(data_chart, options);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        // dataType: "html",
                        url: 'chart/structure',
                        success: function (data) {
                            data = JSON.parse(data);
                            let data_chart = google.visualization.arrayToDataTable(data);
                            data_chart.addColumn('string', 'Name');
                            data_chart.addColumn('string', 'Position');
                            data_chart.addColumn('string', 'ToolTip');
                            let chart = new google.visualization.OrgChart(document.getElementById('structure_chart_div'));
                            // Draw the chart, setting the allowHtml option to true for the tooltips.
                            chart.draw(data_chart, {'allowHtml': true});
                        }
                    });

                    $.ajax({
                        type: "POST",
                        // dataType: "html",
                        // url: 'chart/accruals',
                        url: 'chart/operations',
                        success: function (data) {
                            data = JSON.parse(data);
                            let data_chart = google.visualization.arrayToDataTable(data);
                            // let data_chart = [];
                            let options = {
                                title: 'Нарахування за місяць',
                                legend: {position: 'bottom'},
                                colors: ['green', 'red', 'blue']
                            };
                            let chart = new google.visualization.LineChart(document.getElementById('accruals_month_chart'));

                            chart.draw(data_chart, options);
                        }
                    });

                    $.ajax({
                        type: "POST",
                        // dataType: "html",
                        // url: 'chart/accruals/true',
                        url: 'chart/operations/true',
                        success: function (data) {
                            data = JSON.parse(data);
                            let data_chart = google.visualization.arrayToDataTable(data);
                            let options = {
                                title: 'Плановані нарахування за місяць',
                                legend: {position: 'bottom'},
                                colors: ['green', 'red', 'blue']
                            };
                            let chart = new google.visualization.LineChart(document.getElementById('planned_accruals_month_chart'));

                            chart.draw(data_chart, options);
                        }
                    });

                }
            });
            return true;
        }
    });
});

