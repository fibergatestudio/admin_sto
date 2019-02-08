/*Сводная таблица*/

( function( $ ) {
    $(document).ready(function (){

        dataTableJson.sort(function(a,b){
            return new Date(a.date) - new Date(b.date);
        });

        //Корректируем под имеющиеся данные блок периода таблицы
        var start = 0;
        var finish = 0;
        var html = '';
        for (var i = 0; i < dataTableJson.length; i++){
            start = new Date(dataTableJson[0].date).getFullYear();
            finish = new Date(dataTableJson[dataTableJson.length - 1].date).getFullYear();
        }
        for (var i = start; i <= finish; i++) {
            html += `<option value="`+i+`">`+i+`</option>`;
        }
        $("#fromYear").html(html);
        $("#untilYear").html(html);

        //Наполняем таблицу
        $('#makeTable').click(function (){

            var fromMonth = $("#fromMonth").val();
            var fromYear = $("#fromYear").val();    
            var untilMonth = $("#untilMonth").val();
            var untilYear = $("#untilYear").val();
            var fromDate = new Date(fromYear, fromMonth, 1 );
            var untilDate = new Date(untilYear, untilMonth, 1);
            
            //проверяем период
            var daysPeriod = Math.ceil((untilDate.getTime() - fromDate.getTime()) / (1000 * 3600 * 24));
            var yearsPeriod = ' ' + fromYear + 'г. - ' + untilYear + 'г.';
            if (daysPeriod < 28) {
                alert("Выберите корректный период (больше месяца) !");
                return 0;
            }  
            
            var html = '';
            var nameArr = [];
            var summaryTable = {};

            //собираем объект данных под заданный период
            for (var i = 0; i < dataTableJson.length; i++){
                if ((new Date(dataTableJson[i].date).getTime()) >= fromDate && (new Date(dataTableJson[i].date).getTime()) <= untilDate) {
                    if (nameArr.indexOf(dataTableJson[i].name) === -1) {
                        nameArr.push(dataTableJson[i].name);
                        summaryTable[dataTableJson[i].name] = {};
                        if (dataTableJson[i].wrongdoing === 'Опоздание') {
                            summaryTable[dataTableJson[i].name] = {
                                lateArrival : 1,
                                fine : 0
                            };
                        }
                        else if(dataTableJson[i].wrongdoing === 'Штраф') {
                            summaryTable[dataTableJson[i].name] = {
                                lateArrival : 0,
                                fine : 1
                            };
                        }                   
                    }
                    else{
                        var lateArrival = parseInt(summaryTable[dataTableJson[i].name].lateArrival);
                        var fine = parseInt(summaryTable[dataTableJson[i].name].fine);
                        if (dataTableJson[i].wrongdoing === 'Опоздание') {
                            lateArrival++;
                            summaryTable[dataTableJson[i].name] = {
                                lateArrival : lateArrival,
                                fine : fine
                            };
                        }
                        else if(dataTableJson[i].wrongdoing === 'Штраф') {
                            fine++;
                            summaryTable[dataTableJson[i].name] = {
                                lateArrival : lateArrival,
                                fine : fine
                            };
                        } 
                    }
                }
            }

            //собираем таблицу
            for (var prop in summaryTable) {
                html += `<tr>
                        <td>`+ prop +`</td>
                        <td>`+ summaryTable[prop].lateArrival +`</td>
                        <td>`+ summaryTable[prop].fine +`</td>         
                        </tr>`;
            }

            $('#summary-table > tbody').html(html);
        });

    });
} )( jQuery );