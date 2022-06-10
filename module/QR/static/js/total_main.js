
var num = Math.random(); var data = { labels: ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J"], datasets: [ { data: [ Math.random(), Math.random(), Math.random(), Math.random(), Math.random(), Math.random(), Math.random(), Math.random(), Math.random(), Math.random() ], backgroundColor: [ "#f79546", "#9bba57", "#4f81bb", "#5f497a", "#a94069", "#ff5f34", "#41774e", "#003663", "#49acc5", "#c0504e" ], borderWidth: 0, label: "Dataset 1" }] };
let loading_box = document.getElementById('loadingBox');

let all_stat_pie_chart_data = null; // 전체 통계 pie chart data
let part_stat_column_chart_data = [['분야별', '전체', '정상입실', '격리실']]; // 분야별 통계 column chart data
let place_stat_column_chart_data = [['장소별', '전체', '정상입실', '격리실']]; // 장소별 통계 column chart data
window.onload = function(){

    loading_box.classList.remove('d-none');
    callApi('GET', null , BASE_URL+'total/getMainData/'+RS_ID, (res_data)=>{
        loading_box.classList.add('d-none');

        switch(res_data.code){
            case 200 :
                drawStat(res_data.data); 
            break;
        }
    });
}

const drawStat = (data)=>{
    // console.log(data.place_stat);

    all_stat_pie_chart_data = [
        ['미응시 ('+(parseInt(data.total_stat.all) - parseInt(data.total_stat.active))+'명)', parseInt(data.total_stat.all) - parseInt(data.total_stat.active)],
        ['정상입실 ('+(parseInt(data.total_stat.active) - parseInt(data.total_stat.iso))+'명)', parseInt(data.total_stat.active) - parseInt(data.total_stat.iso)],
        ['격리실 ('+parseInt(data.total_stat.iso)+'명)', parseInt(data.total_stat.iso)],
    ];

    // console.log(Object.keys(data.part_stat));

    Object.keys(data.part_stat).forEach(function(key, index){
        // console.log(data.part_stat[key]);
        let each_part_stat_data = [ '' , data.part_stat[key].all.length, 0 , 0 ];
        if(data.part_stat[key].active){ 
            each_part_stat_data[2] = data.part_stat[key].active.length;
            if(data.part_stat[key].iso){
                each_part_stat_data[2] = (data.part_stat[key].active.length - data.part_stat[key].iso.length); 
            }
        }
        if(data.part_stat[key].iso){ each_part_stat_data[3] = data.part_stat[key].iso.length; };
        each_part_stat_data[0] = key + "("+(each_part_stat_data[2] + each_part_stat_data[3])+"/"+each_part_stat_data[1] +") " + ((each_part_stat_data[2] + each_part_stat_data[3])/each_part_stat_data[1]*100).toFixed(1) + "%";
        part_stat_column_chart_data.push(each_part_stat_data);  
    });

    let class_stat_box = document.getElementById('placeClassChartBox');
    
    Object.keys(data.place_stat).forEach(function(key, index){
        // console.log(data.place_stat[key].all);
        let each_place_stat_data = [ '' , data.place_stat[key].all, 0 , 0 ]; 
        // console.log(each_place_stat_data);
        if(data.place_stat[key].active){ 
            each_place_stat_data[2] = data.place_stat[key].active;
            if(data.place_stat[key].iso){
                each_place_stat_data[2] = (data.place_stat[key].active - data.place_stat[key].iso); 
            }
        };
        if(data.place_stat[key].iso){ each_place_stat_data[3] = data.place_stat[key].iso; };
        each_place_stat_data[0] = data.place_stat[key].p_name + "("+(each_place_stat_data[2] + each_place_stat_data[3])+"/"+ each_place_stat_data[1] +") " + ((each_place_stat_data[2] + each_place_stat_data[3])/each_place_stat_data[1]*100).toFixed(1) + "%";
        place_stat_column_chart_data.push(each_place_stat_data);  
        
        console.log(each_place_stat_data);
        let iso_message_html = '';
        if(each_place_stat_data[3] > 0){
            iso_message_html = `<br><br><small class="text-danger">! 격리실 운영</small>`;
        }

        let each_class_box = document.createElement('div');
        let box_html = `
            <button class="btn btn-primary m-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#placeDetailBox${key}" aria-controls="offcanvasRight">${data.place_stat[key].p_name}</button>
            <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="placeDetailBox${key}" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header pb-0">
                <h6 class="fw-bold" id="offcanvasRightLabel">
                - ${data.place_stat[key].p_name} (<label class="text-primary">전체 : ${each_place_stat_data[1]} </label> <label class="text-success">정상입실 : ${each_place_stat_data[2]}</label> <label class="text-danger">격리 : ${each_place_stat_data[3]}</label>) / 응시율 : ${((each_place_stat_data[2] + each_place_stat_data[3])/each_place_stat_data[1]*100).toFixed(1)}%
                ${iso_message_html}
                </h6>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">`;
        box_html += `<ul class="list-group list-group-flush">`;
        Object.keys(data.place_stat[key].class).forEach(function(key2, index2){
            let class_counts = {all:data.place_stat[key].class[key2].all.length, active : 0 , iso : 0};

            if(data.place_stat[key].class[key2].active) { 
                class_counts.active = data.place_stat[key].class[key2].active.length; 
                if(data.place_stat[key].class[key2].iso){
                    class_counts.active = (data.place_stat[key].class[key2].active.length - data.place_stat[key].class[key2].iso.length); 
                }

            }
            if(data.place_stat[key].class[key2].iso) { class_counts.iso = data.place_stat[key].class[key2].iso.length; }
            
            box_html += `<li class="list-group-item"><b>${key2} 고사실 </b>(<label class="text-primary">전체 : ${class_counts.all}</label> <label class="text-success">정상입실 : ${class_counts.active}</label>`;
            if(class_counts.iso){
                box_html += ` <label class="text-danger">격리 : ${class_counts.iso}</label>)</li>`;
            }else{
                box_html += `)</li>`;
            }
        });
        box_html += `</ul>`;
        box_html += `</div></div>`;
        each_class_box.innerHTML= box_html;
        class_stat_box.appendChild(each_class_box);


    });
    // data.part_stat.forEach(element => {
    // });

    //전체통계 그리기
    const total_stat = data.total_stat;
    
    const active_per = ((parseInt(total_stat.active) / parseInt(total_stat.all)) * 100 ).toFixed(2);
    let total_stat_text_box = document.querySelector('#allStatBox div:nth-child(1)');
    total_stat_text_box.innerHTML = `<p class="text-primary mb-0">전체 : `+total_stat.all+`명</p>
                                    <p class="text-success mb-0 fw-bold">입실 : `+total_stat.active+`명 (`+active_per+`%)</p>
                                    <p class="text-success mb-0">정상입실 : `+(parseInt(total_stat.active) - parseInt(total_stat.iso))+`명 (`+(((parseInt(total_stat.active) - parseInt(total_stat.iso)) / parseInt(total_stat.all)) * 100 )+`%)</p>
                                    <p class="text-danger mb-0">격리 : `+total_stat.iso+`명 (`+((parseInt(total_stat.iso) / parseInt(total_stat.all)) * 100 )+`%)</p>`;
    let total_stat_graph_box = document.querySelector('#allStatBox div:nth-child(2)');
    // total_stat_graph_box.className = 'col-5 ';
    total_stat_graph_box.innerHTML = `<div class="progress mt-4" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: `+active_per+`%;" aria-valuenow="`+active_per+`" aria-valuemin="0" aria-valuemax="100">`+active_per+`%</div>
                                    </div>`;
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChartPie);



    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawChartColumnPart);

    google.charts.load('current', {'packages':['corechart', 'bar']});
    google.charts.setOnLoadCallback(drawChartColumnPlace);
}






function drawChartPie() {

var data = new google.visualization.DataTable();
data.addColumn('string', 'Topping');
data.addColumn('number', 'Slices');
data.addRows(all_stat_pie_chart_data);
var options = {
    // 'title':'How Much Pizza I Ate Last Night',
    // 'width':500,
    'height':200,
    'colors':['#0d6efd','#198754','#dc3545'],
    // 'is3D':true,
};
var chart = new google.visualization.PieChart(document.getElementById('allStatPieBox'));
chart.draw(data, options);
}

function drawChartColumnPart() {
    var data = google.visualization.arrayToDataTable(part_stat_column_chart_data);
    // var view = new google.visualization.DataView(data);
    // view.setColumns([
    //     0, 
    //     1,
    //     { calc: "stringify",
    //     sourceColumn: 1,
    //     type: "string",
    //     role: "annotation" },
    //     2,
    //     3
    // ]);

    var options = {
    //   chart: {
    //     title: 'Company Performance',
    //     subtitle: 'Sales, Expenses, and Profit: 2014-2017',
    //   }
        'colors':['#0d6efd','#198754','#dc3545'],
    };

    var chart = new google.charts.Bar(document.getElementById('partStatBarChartBox'));

    // chart.draw(view, google.charts.Bar.convertOptions(view , options));
    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
function drawChartColumnPlace() {
    var data = google.visualization.arrayToDataTable(place_stat_column_chart_data);
    // var view = new google.visualization.DataView(data);
    // view.setColumns([
    //     0, 
    //     1,
    //     { calc: "stringify",
    //     sourceColumn: 1,
    //     type: "string",
    //     role: "annotation" },
    //     2,
    //     3
    // ]);

    var options = {
    //   chart: {
    //     title: 'Company Performance',
    //     subtitle: 'Sales, Expenses, and Profit: 2014-2017',
    //   }
        'colors':['#0d6efd','#198754','#dc3545'],
    };

    var chart = new google.charts.Bar(document.getElementById('placeStatBarChartBox'));

    // chart.draw(view, google.charts.Bar.convertOptions(view , options));
    chart.draw(data, google.charts.Bar.convertOptions(options));
  }