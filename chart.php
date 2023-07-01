<?php
    include 'ajax/head_left.php';
    include "connect/COMMON.php";
    date_default_timezone_set("Asia/Bangkok");




?>

<style>
.Choose_OT_Date {
    border: 10px solid #f2bb59;
    background: #fff9c5;
    cursor: pointer;
}
.Not_Choose_OT_Date {
    border: 10px solid #ffffff;
    background: #f3f3f3;
    cursor: pointer;
}
.border_ras {
    border-radius: 30px;
}
.border_header {
    border-radius: 26px 26px 0px 0px;
}
.btn_new {
    border-radius:30px;

}

.translate-rotate {
    background-color: gold;
    transform: translateX(180px) rotate(90deg);
}
input[type=range][orient=vertical]
{
    writing-mode: bt-lr; /* IE */
    -webkit-appearance: slider-vertical; /* Chromium */
    width: 8px;
    padding: 0 5px;
    transform: rotate(180deg);
    
}


/* Important part */
.modal-body{
    height: 80vh;
    overflow-y: auto !important;
}

.loader-me{
  width: 50px;
  height:50px;
  position:relative;
  align-items: center;
}

.loader-me div{
  position:absolute;
  width: 35%;
  height: 35%;
  border-radius:5px;
  animation: load 2s infinite ease-in-out;
}

.loader-me div:nth-of-type(1){
  background-color:#B22727;
}

.loader-me div:nth-of-type(2){
  background-color:#EE5007;
  animation-delay:0.5s;
}

.loader-me div:nth-of-type(3){
  background-color:#F8CB2E;
  animation-delay:1s;
}
.loader-me div:nth-of-type(4){
  background-color:#006E7F;
  animation-delay:1.5s;
}

@keyframes load {
  0% {
    transform: translate(0%);
    border-radius: 50%;
  }

  25% {
    transform: translate(200%) rotate(45deg);
    border-radius: 0%;
  }

  50% {
    transform: translate(200%, 200%);
    border-radius: 50%;
  }

  75% {
    transform: translate(0, 200%) rotate(-45deg);
    border-radius: 0%;
  }
  100% {
    transform: translate(0%);
    border-radius: 50%;
  }
}

#chart_box::-webkit-scrollbar {
    display: none !important;
}

#chart_box {
  -ms-overflow-style: none !important;  /* IE and Edge */
  scrollbar-width: none !important;  /* Firefox */
  /* overflow: hidden !important;
  overflow-y: hidden !important;
  overflow-x: hidden !important; */
}

#chart_box_main::-webkit-scrollbar {
    display: none !important;
}

#chart_box_main {
  -ms-overflow-style: none !important;  /* IE and Edge */
  scrollbar-width: none !important;  /* Firefox */
  /* overflow: hidden !important;
  overflow-y: hidden !important;
  overflow-x: hidden !important; */
}
.border_bolid{
    border-width:2px !important;
}
.boder_bolid {

}
.circles-text-aunz{
    font-size:34px !important
}
.fontgrey {
    color: #6c757d !important;
    font-size: 30px !important;
    
}


.bg-warning{
    background-color: #457b9d !important;
}
.bg-primary{
    background-color: #ff7f51 !important;

}

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12" style="overflow: hidden !important;">

        <div class="main-panel h-100" id="chart_box_main" style="overflow: hidden !important;"  >
		<div class="content"  style="overflow: hidden !important;" >


        
				<div class="panel-header" style="background-color:#57aca6; overflow: hidden !important;">
					<div class="page-inner py-4">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold mb-4" style="font-size: 3.5em;">Chart</h2>
							</div>
						</div>
					</div>
				</div>

                <div class="mt-4 " id="chart_box" style="overflow: hidden !important;">
                    <div class="card border_ras">
                        <div class="card-body">
                            

                            <div class="row-md-12  shadow  my-4 ">
                                <div class="row-md-12 px-3 py-1  pt-2 bg-primary">
                                        <h2 class="text-center text-white">TODAY</h2>
                                </div>
                                <div class="row ">
                                    <canvas class = "col-md-6" id="doughnutChartToday"></canvas>
                                    <canvas class= "col-md-6" id="multipleBarChartToday"></canvas>
                                            
                                </div>
                            </div>
                     
                            
                            <div class="row-md-12 shadow my-4 ">
                                <div class="row-md-12 px-3 py-1 pt-2 bg-warning">
                                        <h2 class="text-center text-white">All Day</h2>
                                </div>
                                <div class="row ">
                                    <canvas class = "col-md-6" id="doughnutChartAll"></canvas>
                                    <canvas class= "col-md-6" id="multipleBarChartAll"></canvas>
                                            
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row-md-12 shadow my-5 ">
                                        <div class="row-md-12 px-3 py-1 bg-primary">
                                            <h2 class="text-center text-white">Request ALL</h2>
                                        </div>
                                        <div class="row py-4 text-center d-flex align-items-center justify-content-center">
                                            <div class="col">
                                                <div  class ="row-md-2 px-2" id="task-waiting-all"></div>
                                                <h2>Waiting</h2>
                                            </div>
                                            <div class="col">
                                                <div  class="row-md-2 px-2" id="task-process-all"></div>
                                                <h2>Process</h2>
                                            </div>
                                            <div class="col">
                                                <div  class="row-md-2 px-2" id="task-complete-all"></div>    
                                                <h2>Success</h2>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="row-md-12 shadow  my-5 ">
                                        <div class="row-md-12 px-3 py-1 bg-warning ">
                                            <h2 class="text-center text-white">Request Today</h2>
                                        </div>
                                        <div class="row py-4 text-center d-flex align-items-center justify-content-center">
                                            <div class="col">
                                                <div  class ="row-md-2 px-2" id="task-waiting-today"></div>
                                                <h2>Waiting</h2>
                                            </div>
                                            <div class="col">
                                                <div  class="row-md-2 px-2" id="task-process-today"></div>
                                                <h2>Process</h2>
                                            </div>
                                            <div class="col">
                                                <div  class="row-md-2 px-2" id="task-complete-today"></div>    
                                                <h2>Success</h2>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php include 'ajax/Footer.php'; ?>
                    </div>
                </div>
            </div>    
        </div>	
        </div>
    </div>

</div>	
	
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>

	<!-- jQuery UI -->
	<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


	<!-- Chart JS -->
	<script src="assets/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Bootstrap Notify -->
	<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- jQuery Vector Maps -->
	<script src="assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
	<script src="assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

	<!-- Sweet Alert -->
	<script src="assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Atlantis JS -->
	<script src="assets/js/atlantis.min.js"></script>

	<!-- Atlantis DEMO methods, don't include it in your project! -->
    <!-- <script src="assets/js/setting-demo.js"></script> -->

    <script src="assets/js/login.js"></script>
    <script src="assets/js/logout.js"></script>

    <script src="assets/js/bootstrap-datepicker.min.js"></script>


    <!-- timeslects -->
    <!-- <script src="assets/js/gijgo.min.js"></script> -->

    
    <script>
    // let data_global_ok_ng 
    let multipleBarChartAll = document.getElementById('multipleBarChartAll').getContext('2d');
    let doughnutChartAll = document.getElementById('doughnutChartAll').getContext('2d');

    let multipleBarChartToday = document.getElementById('multipleBarChartToday').getContext('2d');
    let doughnutChartToday = document.getElementById('doughnutChartToday').getContext('2d');


    showChartBarAll();
    showChartDonutAll();
    showChartBarToday();
    showChartDonutToday();
    RequestTaskAll();
    RequestTaskToday();
    
    
    function showChartBarAll(){
        $.ajax({
             type: "GET",
             url: "ajax/GetTableName.php",
             async: false,
             cache: false,
             data: {
                is_Day:"0",
            },
             success: function(result){
                let data_global_ok_ng = JSON.parse(result);
                // console.log(data_global_ok_ng);
                let countOK_All = 0;
                let countNG_All = 0;

                for (const [key,value] of Object.entries(data_global_ok_ng.OKData)){
                    countOK_All=countOK_All+value;
                    // console.log(countOK_All);
                }
                for (const [key,value] of Object.entries(data_global_ok_ng.NGData)){
                    countNG_All=countNG_All+value;
                    // console.log(countNG_All);
                }
                
                /////////////////////////
                var myMultipleBarChart = new Chart(multipleBarChartAll, {
                    type: 'bar',
                    data: {
                        labels: data_global_ok_ng.labels,
                        datasets : [{
                            label: "OK",
                            backgroundColor: '#52b788',
                            borderColor: '#52b788',
                            data: data_global_ok_ng.OKData,
                        },{
                            label: "NG",
                            backgroundColor: '#ffc9b9',
                            borderColor: '#ffc9b9',
                            data: data_global_ok_ng.NGData,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position : 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'OK NG BY LINE'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        }
                    }
                });
             }
           });
    }

    
    function showChartDonutAll(){
        $.ajax({
             type: "GET",
             url: "ajax/GetTableName.php",
             async: false,
             cache: false,
             data: {
                is_Day:"0",
            },
             success: function(result){
                console.log(result);
                let data_global_ok_ng = JSON.parse(result);
                // console.log(data_global_ok_ng);
                let countOK_All = 0;
                let countNG_All = 0;

                for (const [key,value] of Object.entries(data_global_ok_ng.OKData)){
                    countOK_All=countOK_All+value;
                    // console.log(countOK_All);
                }
                for (const [key,value] of Object.entries(data_global_ok_ng.NGData)){
                    countNG_All=countNG_All+value;
                    // console.log(countNG_All);
                }
                

                var myDoughnutChart = new Chart(doughnutChartAll, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [countOK_All,countNG_All],
                            backgroundColor: ['#52b788','#ffc9b9']
                        }],

                        labels: [
                        'OK',
                        'NG'
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend : {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'OK NG ALL'
                        },
                   
                        layout: {
                            padding: {
                                left: 20,
                                right: 20,
                                top: 20,
                                bottom: 20
                            }
                        }
                    }
                });
                //////////////////////////////
             }
           });
    }

    
    function showChartBarToday(){
        $.ajax({
             type: "GET",
             url: "ajax/GetTableName.php",
             async: false,
             cache: false,
             data: {
                is_Day:"1",
            },
             success: function(result){
                let data_global_ok_ng = JSON.parse(result);
                console.log(result);
                let countOK_All = 0;
                let countNG_All = 0;

                for (const [key,value] of Object.entries(data_global_ok_ng.OKData)){
                    countOK_All=countOK_All+value;
                    // console.log(countOK_All);
                }
                for (const [key,value] of Object.entries(data_global_ok_ng.NGData)){
                    countNG_All=countNG_All+value;
                    // console.log(countNG_All);
                }
                
                /////////////////////////
                var myMultipleBarChartToday = new Chart(multipleBarChartToday, {
                    type: 'bar',
                    data: {
                        labels: data_global_ok_ng.labels,
                        datasets : [{
                            label: "OK",
                            backgroundColor: '#52b788',
                            borderColor: '#52b788',
                            data: data_global_ok_ng.OKData,
                        },{
                            label: "NG",
                            backgroundColor: '#ffc9b9',
                            borderColor: '#ffc9b9',
                            data: data_global_ok_ng.NGData,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            position : 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'OK NG BY LINE TODAY'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                            }],
                            yAxes: [{
                                stacked: true
                            }]
                        }
                    }
                });
             }
           });
    }

    function showChartDonutToday(){
        $.ajax({
             type: "GET",
             url: "ajax/GetTableName.php",
             async: true,
             cache: false,
             data: {
                is_Day:"1",
            },
             success: function(result){
                let data_global_ok_ng = JSON.parse(result);
                // console.log(data_global_ok_ng);
                let countOK_All = 0;
                let countNG_All = 0;

                for (const [key,value] of Object.entries(data_global_ok_ng.OKData)){
                    countOK_All=countOK_All+value;
                    // console.log(countOK_All);
                }
                for (const [key,value] of Object.entries(data_global_ok_ng.NGData)){
                    countNG_All=countNG_All+value;
                    // console.log(countNG_All);
                }
                

                var myDoughnutChart = new Chart(doughnutChartToday, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [countOK_All,countNG_All],
                            backgroundColor: ['#52b788','#ffc9b9']
                        }],

                        labels: [
                        'OK',
                        'NG'
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend : {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'OK NG TODAY'
                        },
                   
                        layout: {
                            padding: {
                                left: 20,
                                right: 20,
                                top: 20,
                                bottom: 20
                            }
                        }
                    }
                });
                //////////////////////////////
             }
           });
    }

    function RequestTaskAll(){
        $.ajax({
             type: "GET",
             url: "ajax/TaskChart.php",
             async: true,
             cache: false,
             data: {
                is_Day:"0",
            },
             success: function(result){
                console.log(result);
                let data_task = JSON.parse(result);
                let waiting = 0;
                let processing = 0;
                let success = 0;
                let total = 0;

                for (const [key,value] of Object.entries(data_task)){
                    // countOK_All=countOK_All+value;
                    if(key==0){
                        waiting=value;
                    }else if(key==1){
                        processing=value;
                    }else if(key==2){
                        success=value;
                    }
                    // console.log(value);
                }
                total = parseInt(waiting) + parseInt(processing) +parseInt(success);
                let waiting_Per = (parseInt(waiting)/parseInt(total))*100;
                let processing_Per = (parseInt(processing)/parseInt(total))*100;
                let success_Per = (parseInt(success)/parseInt(total))*100;
                // console.log('waiting:',waiting);
                // console.log('processing:',processing);
                // console.log('success:',success);
                // console.log(data_task.0);

                Circles.create({
                    id:           'task-waiting-all',
                    radius:       75,
                    value:        waiting_Per,
                    maxValue:     100,
                    width:        7,
                    text:         function(value){return value + '%';},
                    colors:       ['#eee', '#177dff'],
                    duration:     1300,
                    wrpClass:     'circles-wrp',
                    textClass:    'circles-text-aunz',
                    styleWrapper: true,
                    styleText:    true,

                    
                })

                Circles.create({
                    id:           'task-process-all',
                    radius:       75,
                    value:        processing_Per,
                    maxValue:     100,
                    width:        7,
                    text:         function(value){return value + '%';},
                    colors:       ['#eee', '#fdaf4b'],
                    duration:     1300,
                    wrpClass:     'circles-wrp',
                    textClass:    'circles-text-aunz',
                    styleWrapper: true,
                    styleText:    true
                })

                Circles.create({
                    id:           'task-complete-all',
                    radius:       75,
                    value:        success_Per,
                    maxValue:     100,
                    width:        7,
                    text:         function(value){return value + '%';},
                    colors:       ['#eee', '#59d05d'],
                    duration:     1300,
                    wrpClass:     'circles-wrp',
                    textClass:    'circles-text-aunz',
                    styleWrapper: true,
                    styleText:    true
                })

             }
           });
    }

    function RequestTaskToday(){
        $.ajax({
             type: "GET",
             url: "ajax/TaskChart.php",
             async: true,
             cache: false,
             data: {
                is_Day:"1",
            },
             success: function(result){
                
                console.log(result);
                // try{
                    let data_task = JSON.parse(result);
                // }
                // catch{
                //     let data_task = {0:0,1:0,2:0}

                // }
                let waiting = 0;
                let processing = 0;
                let success = 0;
                let total = 0;

                for (const [key,value] of Object.entries(data_task)){
                    // countOK_All=countOK_All+value;
                    if(key==0){
                        waiting=value;
                    }else if(key==1){
                        processing=value;
                    }else if(key==2){
                        success=value;
                    }
                    // console.log(value);
                }
                total = parseInt(waiting) + parseInt(processing) +parseInt(success);
                let waiting_Per = (parseInt(waiting)/parseInt(total))*100;
                let processing_Per = (parseInt(processing)/parseInt(total))*100;
                let success_Per = (parseInt(success)/parseInt(total))*100;
                // console.log('waiting:',waiting);
                // console.log('processing:',processing);
                // console.log('success:',success);
                // console.log(data_task.0);

                Circles.create({
                    id:           'task-waiting-today',
                    radius:       75,
                    value:        waiting_Per,
                    maxValue:     100,
                    width:        7,
                    text:         function(value){return value + '%';},
                    colors:       ['#eee', '#177dff'],
                    duration:     1300,
                    wrpClass:     'circles-wrp',
                    textClass:    'circles-text-aunz',
                    styleWrapper: true,
                    styleText:    true,

                    
                })

                Circles.create({
                    id:           'task-process-today',
                    radius:       75,
                    value:        processing_Per,
                    maxValue:     100,
                    width:        7,
                    text:         function(value){return value + '%';},
                    colors:       ['#eee', '#fdaf4b'],
                    duration:     1300,
                    wrpClass:     'circles-wrp',
                    textClass:    'circles-text-aunz',
                    styleWrapper: true,
                    styleText:    true
                })

                Circles.create({
                    id:           'task-complete-today',
                    radius:       75,
                    value:        success_Per,
                    maxValue:     100,
                    width:        7,
                    text:         function(value){return value + '%';},
                    colors:       ['#eee', '#59d05d'],
                    duration:     1300,
                    wrpClass:     'circles-wrp',
                    textClass:    'circles-text-aunz',
                    styleWrapper: true,
                    styleText:    true
                })

             }
           });
    }

    // Circles.create({
    //     id:           'task-complete',
    //     radius:       75,
    //     value:        80,
    //     maxValue:     100,
    //     width:        7,
    //     text:         function(value){return value + '%';},
    //     colors:       ['#eee', '#177dff'],
    //     duration:     400,
    //     wrpClass:     'circles-wrp',
    //     textClass:    'circles-text',
    //     styleWrapper: true,
    //     styleText:    true
    // })


    </script>

    <script type="text/javascript" src="assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/pagination.js"></script>
    <script type="text/javascript" src="dist/bootstrap-clockpicker.min.js"></script>
    
    <script type="text/javascript">
$('.clockpicker').clockpicker({
	placement: 'top',
	align: 'left',
	donetext: 'Done'
});
</script>
        

</body>

</html>