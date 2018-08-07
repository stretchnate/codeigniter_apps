google.charts.load('current', {'packages':['corechart']});
var Report = {
    fetchSpent: function(id) {
        $.post('/ajax/Report/fetchSpent/', {account_id: id}, function(result) {
            if(result.success) {
                google.charts.setOnLoadCallback(Report.drawPie({
                    col1_type:'string',
                    col1_heading:'Category',
                    col2_type:'string',
                    col2_heading:'Spent',
                    raw_data:result.data,
                    options:{
                        'title':'Money Spent per Category'
                    }
                }));
            } else {

            }
        }, 'json')
    },

    drawPie: function(config) {
        // Create the data table.
        rows = [];
        for(i in config.raw_data) {
            rows[i] = [
                config.raw_data[i]['category'],
                parseFloat(config.raw_data[i]['amount'])
            ];
        }
        rows.unshift([config.col1_heading, config.col2_heading]);
        var data = new google.visualization.arrayToDataTable(rows);

        // Set chart options
        var options = config.options;

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart'));
        chart.draw(data, options);
    }
};