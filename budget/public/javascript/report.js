google.charts.load('current', {'packages':['corechart']});
var Report = {
    config:null,
    fetchSpent: function(id, name) {
        $.post('/ajax/Report/fetchSpent/', {account_id: id}, function(result) {
            if(result.success) {
                Report.config = {
                    col1_type:'string',
                    col1_heading:'Category',
                    col2_type:'string',
                    col2_heading:'Spent',
                    raw_data:result.data,
                    options:{
                        'title':name+' Spent per Category (last 30 days)',
                        'width':'100%',
                        'height':'100%'
                    }
                };
                google.charts.setOnLoadCallback(Report.drawPie());
            } else {

            }
        }, 'json')
    },

    drawPie: function() {
        // Create the data table.
        rows = [];
        for(i in Report.config.raw_data) {
            rows[i] = [
                Report.config.raw_data[i]['category'],
                parseFloat(Report.config.raw_data[i]['amount'])
            ];
        }
        rows.unshift([Report.config.col1_heading, Report.config.col2_heading]);
        var data = new google.visualization.arrayToDataTable(rows);

        // Set chart options
        var options = Report.config.options;

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart'));
        chart.draw(data, options);
    }
};

$(window).resize(function() {
    Report.drawPie();
})