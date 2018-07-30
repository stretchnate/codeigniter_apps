var Report = {
    fetchSpent: function(id) {
        $.post('/ajax/Report/fetchSpent/', {account_id: id}, function(result) {
            if(result.success) {
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(Report.drawPie({
                    col1_type:'string',
                    col1_heading:'Category',
                    col2_type:'float',
                    col2_heading:'Spent',
                    raw_data:result.data,
                    options:{'title':'',
                        'width':400,
                        'height':300}
                }));
            } else {

            }
        }, 'json')
    },

    drawPie: function(config) {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn(config.col1_type, config.col1_heading);
        data.addColumn(config.col2_type, config.col2_heading);
        rows = [];
        for(i in config.raw_data) {
            rows[i] = [
                config.raw_data[i]['category'],
                config.raw_data[i]['amount']
            ];
        }
        data.addRows(rows);

        // Set chart options
        var options = config.options;

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart'));
        chart.draw(data, options);
    }
};