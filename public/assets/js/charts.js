// Setup module
// ------------------------------

var EchartsLines = function() {


    //
    // Setup module components
    //

    // Line charts
    var _lineChartExamples = function() {
        if (typeof echarts == 'undefined') {
            console.warn('Warning - echarts.min.js is not loaded.');
            return;
        }


        var line_stacked_element = document.getElementById('line_stacked');
        var columns_clustered_element = document.getElementById('columns_clustered');
        var pie_timeline_element = document.getElementById('pie_timeline');

        
        // Доходы работников
        // Stacked lines chart
        if (line_stacked_element) {

            // Initialize chart
            var line_stacked = echarts.init(line_stacked_element);


            //
            // Chart config
            //

            // Options
            line_stacked.setOption({

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 0,
                    right: 20,
                    top: 35,
                    bottom: 0,
                    containLabel: true
                },

                // Add legend
                legend: {
                    data: ['Иванов', 'Петров', 'Сидоров', 'Тимошенко', 'Порошенко'],
                    itemHeight: 8,
                    itemGap: 20
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    data: [
                    'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь',
                    ],
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    }
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        color: '#333'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                        }
                    }
                }],

                // Add series
                series: [
                {
                    name: 'Иванов',
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    symbolSize: 7,
                    data: [120, 132, 101, 134, 90, 230, 210, 120, 132, 101, 134, 90],
                    itemStyle: {
                        normal: {
                            borderWidth: 2
                        }
                    }
                },
                {
                    name: 'Петров',
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    symbolSize: 7,
                    data: [220, 182, 191, 234, 290, 330, 310, 220, 182, 191, 234, 290],
                    itemStyle: {
                        normal: {
                            borderWidth: 2
                        }
                    }
                },
                {
                    name: 'Сидоров',
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    symbolSize: 7,
                    data: [150, 232, 201, 154, 190, 330, 410, 232, 201, 154, 190, 330],
                    itemStyle: {
                        normal: {
                            borderWidth: 2
                        }
                    }
                },
                {
                    name: 'Тимошенко',
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    symbolSize: 7,
                    data: [320, 332, 301, 334, 390, 330, 320, 332, 301, 334, 390, 330],
                    itemStyle: {
                        normal: {
                            borderWidth: 2
                        }
                    }
                },
                {
                    name: 'Порошенко',
                    type: 'line',
                    stack: 'Total',
                    smooth: true,
                    symbolSize: 7,
                    data: [820, 932, 901, 934, 1290, 1330, 1320, 932, 901, 934, 1290, 1330],
                    itemStyle: {
                        normal: {
                            borderWidth: 2
                        }
                    }
                }
                ]
            });
        }


        // Количество выполненных нарядов
        // Stacked clustered columns
        if (columns_clustered_element) {

            // Initialize chart
            var columns_clustered = echarts.init(columns_clustered_element);


            //
            // Chart config
            //

            // Options
            columns_clustered.setOption({

                // Define colors
                color: ['#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80'],

                // Global text styles
                textStyle: {
                    fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                    fontSize: 13
                },

                // Chart animation duration
                animationDuration: 750,

                // Setup grid
                grid: {
                    left: 0,
                    right: 5,
                    top: 55,
                    bottom: 0,
                    containLabel: true
                },

                // Add legend
                legend: {
                    data: [
                        'За последний месяц','За последний год','За всё время','',
                    ],
                    itemHeight: 2,
                    itemGap: 8,
                    textStyle: {
                        padding: [0, 10]
                    }
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(0,0,0,0.75)',
                    padding: [10, 15],
                    textStyle: {
                        fontSize: 13,
                        fontFamily: 'Roboto, sans-serif'
                    }
                },

                // Horizontal axis
                xAxis: [
                    {
                        type: 'category',
                        data: ['Иванов', 'Петров', 'Сидоров', 'Тимошенко', 'Порошенко'],
                        axisLabel: {
                            color: '#333'
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        splitLine: {
                            show: true,
                            lineStyle: {
                                color: '#eee',
                                type: 'dashed'
                            }
                        }
                    },
                    {
                        type: 'category',
                        axisLine: {show:false},
                        axisTick: {show:false},
                        axisLabel: {show:false},
                        splitArea: {show:false},
                        splitLine: {show:false},
                        data: ['Иванов', 'Петров', 'Сидоров', 'Тимошенко', 'Порошенко']
                    }
                ],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    axisLabel: {
                        color: '#333',
                        formatter: '{value}'
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#999'
                        }
                    },
                    splitLine: {
                        lineStyle: {
                            color: ['#eee']
                        }
                    },
                    splitArea: {
                        show: true,
                        areaStyle: {
                            color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                        }
                    }
                }],

                // Add series
                series: [
                    {
                        name: 'За последний месяц',
                        type: 'bar',
                        z: 1,
                        xAxisIndex: 1,
                        itemStyle: {
                            normal: {
                                color: '#E57373',
                                label: {
                                    show: true,
                                    padding: 5,
                                    position: 'top'
                                }
                            }
                        },
                        data: [6, 8, 5, 7, 8]
                    },
                    {
                        name: 'За последний год',
                        type: 'bar',
                        z: 1,
                        xAxisIndex: 1,
                        itemStyle: {
                            normal: {
                                color: '#81C784',
                                label: {
                                    show: true,
                                    padding: 5,
                                    position: 'top'
                                }
                            }
                        },
                        data: [54, 75, 49, 91, 130]
                    },
                    {
                        name: 'За всё время',
                        type: 'bar',
                        z: 1,
                        xAxisIndex: 1,
                        itemStyle: {
                            normal: {
                                color: '#64B5F6',
                                label: {
                                    show: true,
                                    padding: 5,
                                    position: 'top'
                                }
                            }
                        },
                        data: [220, 300, 250, 300, 200]
                    }
                ]
            });
        }


        // Статистика по количеству заказанных услуг на СТО по разделам
        // Timeline
        if (pie_timeline_element) {

            // Initialize chart
            var pie_timeline = echarts.init(pie_timeline_element);


            //
            // Chart config
            //

            var idx = 1;

            // Options
            pie_timeline.setOption({

                // Add timeline
                timeline: {
                    axisType: 'category',
                    left: 0,
                    right: 0,
                    bottom: 0,
                    label: {
                        normal: {
                            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                            fontSize: 11
                        }
                    },
                    data: [
                        '2018-01-01', '2018-02-01', '2018-03-01', '2018-04-01', '2018-05-01',
                        { name:'2018-06-01', symbol: 'emptyStar2', symbolSize: 8 },
                        '2018-07-01', '2018-08-01', '2018-09-01', '2018-10-01', '2018-11-01',
                        { name:'2018-12-01', symbol: 'star2', symbolSize: 8 }
                    ],
                    autoPlay: true,
                    playInterval: 3000
                },

                options: [
                    {

                        // Colors
                        color: [
                            '#2ec7c9','#b6a2de','#5ab1ef','#ffb980','#d87a80',
                            '#8d98b3','#e5cf0d','#97b552','#95706d','#dc69aa',
                            '#07a2a4','#9a7fd1','#588dd5','#f5994e','#c05050',
                            '#59678c','#c9ab00','#7eb00a','#6f5553','#c14089'
                        ],

                        // Global text styles
                        textStyle: {
                            fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                            fontSize: 13
                        },

                        // Add title
                        title: {
                            text: 'Заказанные услуги',
                            subtext: 'Основана на количестве заказанных услуг на СТО по разделам',
                            left: 'center',
                            textStyle: {
                                fontSize: 17,
                                fontWeight: 500
                            },
                            subtextStyle: {
                                fontSize: 12
                            }
                        },

                        // Add tooltip
                        tooltip: {
                            trigger: 'item',
                            backgroundColor: 'rgba(0,0,0,0.75)',
                            padding: [10, 15],
                            textStyle: {
                                fontSize: 13,
                                fontFamily: 'Roboto, sans-serif'
                            },
                            formatter: '{a} <br/>{b}: {c} ({d}%)'
                        },

                        // Add legend
                        legend: {
                            orient: 'vertical',
                            top: 'center',
                            left: 0,
                            data: ['Разборка-Сборка','Электрика','Слесарка','Рихтовка','Покраска','Детэйлинг'],
                            itemHeight: 8,
                            itemWidth: 8
                        },

                        // Add series
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            center: ['50%', '50%'],
                            radius: '60%',
                            itemStyle: {
                                normal: {
                                    borderWidth: 1,
                                    borderColor: '#fff'
                                }
                            },
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    },
                    {
                        series: [{
                            name: 'Услуга',
                            type: 'pie',
                            data: [
                                {value: idx * 128 + 80, name: 'Разборка-Сборка'},
                                {value: idx * 64 + 160, name: 'Электрика'},
                                {value: idx * 32 + 320, name: 'Слесарка'},
                                {value: idx * 16 + 640, name: 'Рихтовка'},
                                {value: idx * 16 + 640, name: 'Покраска'},
                                {value: idx++ * 8 + 1280, name: 'Детэйлинг'}
                            ]
                        }]
                    }
                ]
            });
        }


        //
        // Resize charts
        //

        // Resize function
        var triggerChartResize = function() {

            line_stacked_element && line_stacked.resize();
            columns_clustered_element && columns_clustered.resize();
            pie_timeline_element && pie_timeline.resize();

        };

        // On sidebar width change
        $(document).on('click', '.sidebar-control', function() {
            setTimeout(function () {
                triggerChartResize();
            }, 0);
        });

        // On window resize
        var resizeCharts;
        window.onresize = function () {
            clearTimeout(resizeCharts);
            resizeCharts = setTimeout(function () {
                triggerChartResize();
            }, 200);
        };
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _lineChartExamples();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    EchartsLines.init();
});

