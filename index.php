<?php
    include 'ajax/head_left.php';
    include "connect/COMMON.php";
    date_default_timezone_set("Asia/Bangkok");

    if($EmpID_Login == ""){
        header("location:./chart.php");
    }

    //สำหรับ where ใน biz จริงมา
    function getAllLineInProduct($dbname)
    {
        $myfunction = new suchin_class();
        $myfunction->host = '43.72.52.25';
        $myfunction->user = 'EMS';
        $myfunction->password = 'Ems@SttMFD';
        $myfunction->CnndB();
        
        $bizs = ['IM','CL']; // อย่าลืมปิดถ้าเปิดล้างแล้ว
        // $bizs = $dbname; //ถ้าเปิดให้ใส่ value ว่า value[] และเปิด biz_fullValue กับ biz_value ใน for ด้วย
        $count = 0;
        foreach ($bizs as $key => $value) {
            $count = $count+1;
            $biz_value = $value; // อย่าลืมปิดถ้าเปิดล้างแล้ว
            // $biz_fullValue = $value[name];
            // $biz_value = substr($biz_fullValue,4);

            if($count==1){
                $str = $str."
                    AND [Biz_Code] = '".$biz_value."' 
                ";
            }else{
                $str = $str."
                OR [Biz_Code] = '".$biz_value."' ";
            }
        }
        $sql="
            SELECT
                 [Biz_Code] 
                ,[Line_Name]
                ,[Current_Model]
            FROM [EMS].[EMS].[HMSMonitorRefresh]
            WHERE NOT[Current_Model] ='' AND [Current_Model] IS NOT NULL
            ".$str."
        ";
    
        $getdata = "";
        $myfunction->result_array = '';
        $myfunction->getdb($sql, 'mssql');
        $getdata = $myfunction->result_array;
        return $getdata;
    }

    function getDBName()
    {
        $myfunction = new suchin_class();
        $myfunction->host = '43.72.52.14';
        $myfunction->user = 'MFD';
        $myfunction->password = 'Mfd@SttMFD';
        $myfunction->CnndB();

        //สำหรับ DB 43.72.52.14
        $sql="
            SELECT name FROM sys.databases
            WHERE name LIKE 'AI%' 
        ";

        ////สำหรับ DB 43.72.52.25
        // $sql="
        //     SELECT name FROM sys.databases
        //     WHERE name LIKE 'DAI_%' AND NOT name = 'DAI_AI_CENTER'
        // ";

        $getdata = "";
        $myfunction->result_array = '';
        $myfunction->getdb($sql, 'mssql');
        $getdata = $myfunction->result_array;

        return $getdata;
    }
    
    function getAllLineInAiDB ()
    {
        $myfunction = new suchin_class();

        $myfunction->host = '43.72.52.14';
        $myfunction->user = 'MFD';
        $myfunction->password = 'Mfd@SttMFD';
        $myfunction->CnndB();

        $count = 0;
        
         $sql="
            SELECT 
                [Biz_Code]
                ,[Line_Name]
                ,[Current_Model]
            FROM [DAI_AI_CENTER].[dbo].[DAI_Model_list]
        ";

        $getdata = "";
        $myfunction->result_array = '';
        $myfunction->getdb($sql, 'mssql');
        $getdata = $myfunction->result_array;
        return $getdata;
    }
    
    function insertDB($array)
    {
        foreach($array as $value){
            $myfunction = new suchin_class();
            $myfunction->host = '43.72.52.14';
            $myfunction->user = 'MFD';
            $myfunction->password = 'Mfd@SttMFD';
            $myfunction->CnndB();
            
            $sql ="
                INSERT INTO [DAI_AI_CENTER].[dbo].[DAI_Model_list]
                    (
                         [Biz_Code]
                        ,[Line_Name]
                        ,[Current_Model]
                    )
                VALUES ('".$value[Biz_Code]."','".$value[Line_Name]."', '".$value[Current_Model]."');
            ";

            $myfunction->exec($sql, 'mssql');
         
        }
    }
    
    $dbname = getDBName();
    $Original = getAllLineInProduct($dbname);
    $ToDBAI = getAllLineInAiDB();
    // print_r('<pre/>');
    // print_r($Original);

    foreach ($Original as $key=>$val)
    {
        $temp[$val[Biz_Code].$val[Line_Name].$val[Current_Model]] = Array('Biz_Code'=>trim($val[Biz_Code]),'Line_Name'=>trim($val[Line_Name]),'Current_Model'=>trim($val[Current_Model]));
        // $val[Biz_Code].$val[Line_Name].$val[Current_Model]
        
    }
    foreach ($ToDBAI as $key=>$val)
    {
        $temp_DBAI[] = Array('Biz_Code'=>trim($val[Biz_Code]),'Line_Name'=>trim($val[Line_Name]),'Current_Model'=>trim($val[Current_Model]));
        // $val[Biz_Code].$val[Line_Name].$val[Current_Model]
        
    }

    foreach($ToDBAI as $key => $val) 
    {
        $val[Biz_Code] = trim($val[Biz_Code]);
        $val[Line_Name] = trim($val[Line_Name]);
        $val[Current_Model] = trim($val[Current_Model]);
    }



    
    $result = array();

    foreach ($temp as $item) {
        $found = false;
        foreach ($temp_DBAI as $to_dbai_item) {
            if ($item['Biz_Code'] == $to_dbai_item['Biz_Code'] &&
                $item['Line_Name'] == $to_dbai_item['Line_Name'] &&
                $item['Current_Model'] == $to_dbai_item['Current_Model']) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            $result[] = $item;
        }
    }
    // print_r('<pre>');
    // print_r($result);
    // print_r(count($result));
    // print_r('</pre>');

    if(count($result) != 0)
    {
        insertDB($result);
    }



    // die;




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




</style>

    <div class="main-panel h-100">
		<div class="content">


        
				<div class="panel-header" style="background-color:#57aca6">
					<div class="page-inner py-4">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold mb-4" style="font-size: 3.5em;">Data Confirmation</h2>
							</div>
						</div>
					</div>
				</div>

                <div class="mt-4 px-3">
                    <div class="card border_ras">
                        <div class="card-body">
                            <div class='row d-flex align-items-center justify-content-center'>

                                <div class="form-group">
                                    <label for="pillSelect">Biz</label>
                                    <select onchange ="searchDataLine()" class="form-control input-pill" id="pillSelectBiz">
                                    <option id="selectBizOption">...</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="pillSelect">Line</label>
                                    <select onchange ="searchDataModel()" class="form-control input-pill" id="pillSelectLine">
                                    <option id="selectLineOption">DEBUG</option>
                                    <!-- <option id="selectLineOption">select line</option> -->
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="pillSelect">Model</label>
                                    <select class="form-control input-pill" id="pillSelectModel" required>
                                        <!-- <option id="selectModelOption">select model</option> -->
                                        <option id="selectModelOption">TK-Top</option>
                                    </select>
                                </div>


                                <!-- <div class="form-group">
                                    <label for="pillSelect">Date</label> -->
                                    <!-- <input placeholder="Date" data-provide="datepicker"  type="text" class="form-control input-pill" id="datepicker" name="datepicker" value="<?php echo date("Y-m-d")?>" required> -->
                                    <!-- <input placeholder="Date" data-provide="datepicker"  type="text" class="form-control input-pill" id="datepicker" name="datepicker" value="2022-12-15" required>
                                </div>

                                <div class="col-md-2 form-group">
                                    <label for="pillSelect">Time Start</label>
                                    <div class="input-group clockpicker">
                                        <input type="text" class="form-control input-pill"  id="timestart" value="00:00">
                                    </div>

                                </div>

                                <div class="col-md-2 form-group">
                                    <label for="pillSelect">Time End</label>
                                    <div class="input-group clockpicker">
                                        <input type="text" class="form-control input-pill"  id="timeend" value="18:00">
                                    </div>
                                </div> -->
                                
                                <div class="form-group pt-4">
                                    <button onclick="searchData()" class="btn btn-primary border_ras"><i class='fas fa-search'></i></button>
                                </div>
                            </div>  

                        </div>
                    </div>
                </div>

				
                <div class="page-inner h-100 mt-4 p-5">
                    <div class="card border_ras">

                        
                        
                        <div class="card-body" id="tableShowData">
                        <table class="table table-hover " id="tableData">
                        <thead>
                            
                            <tr>
                            <th>Select</th>
                            <th>NO.</th>
                            <th>Image</th>
                            <th>Model</th>
                            <th>Job</th>
                            <th>Date</th>
                            <th>Edit</th>
                            <th>OK</th>
                            <th>NG</th>
                            </tr>
                        </thead>

                        </table>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">EDIT PICTURE SERIE: </h5>
                                <button type="button" id = "closebuttonx" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body text-center">
                                <div class="col">
                                    <div class="row">
                                        <div class="col-md-8 pb-4">
                                            <!-- <img  class="img-fluid" id="scream" width="100%" height="100%" src="https://www.w3schools.com/graphics/pic_the_scream.jpg" alt="The Scream" > -->
                                            <canvas id="myCanvas" class='shadow' width="300" height="300" style="border-color:pink;border-width:7px;">

                                                <img hidden  class="img-fluid shadow" id="scream" width="100%" height="100%" src="https://www.w3schools.com/graphics/pic_the_scream.jpg" alt="The Scream" >
                                            </canvas>
                                        </div>
                                        <div class ='col-md-2 mt-5'>
                                            <input orient="vertical" type="range" id="position_y" name="volume" 
                                                    min="0" max="300" oninput="change_y()">
                                            <label for="volume">Volume Y</label>
                                        </div>

                                        <div class ='col-md-2 mt-5'>
                                            <input orient="vertical" type="range" id="position_y_length" name="volume" 
                                                min="0" max="300" oninput="change_y_length()">
                                            <label for="volume">length Y</label>
                                        </div>
                                    </div>  
                                    <div class="row mb-4">
                                        <div class='col-md-6'>
                                            <input type="range" id="position_x" name="volume"
                                                min="0" max="300" oninput="change_x()">
                                            <label for="volume">Volume X</label>
                                        </div>
                                        <div class="col-md-6">
                                            <input   type="range" id="position_x_length" name="volume" 
                                                min="0" max="300" oninput="change_x_length()">
                                            <label for="volume">length X</label>
                                        </div>
                                    </div>
                                    <div class="row" id="tableShowValueModal">
                                        <table class="table table-bordered table-head-bg-info table-bordered-bd-info">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">X center</th>
                                                            <th scope="col">Y center</th>
                                                            <th scope="col">Width</th>
                                                            <th scope="col">Height</th>
                                                        </tr>
                                                    </thead>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </table>
                                    </div>
                                </div>
                            </div> 

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" onclick="saveEdit()" >Save changes</button>
                            </div>     
                        </div>
                    </div>
                </div>

                <?php include 'ajax/Footer.php'; ?> 
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

        $('#tableData').DataTable({
            // order: [[4, '']],
        });

        var dateNow = new Date();

        $('#datepicker').datepicker({
            defaultDate:dateNow,
            format: 'yyyy-mm-dd',
        
        });


        //ค่าที่แสดงผล
        var x =20;
        var y = 20;
        var w = 30;
        var h = 30;
        //ค่าจริงที่ได้รับจาก DB
        var xr = 0;
        var yr = 0;
        var wr = 0;
        var hr = 0;

        //ที่เก็บ ID ช่วงคราว
        var IDkeep = '';
        var linekeep = '';
        
        var c = document.getElementById("myCanvas");
        
        var ctx = c.getContext("2d");
        var img = document.getElementById("scream");

        
        let lineselect,modelselect,dateselect,imgname,Xcenterselect,Ycenterselect,widthselect,heightselect




        function loadcanvas(id,Xcenter,Ycenter,width,height,img)
        {

            var c2 = document.getElementById(img);
            var img_load = document.getElementById('img'+img);
            console.log(img_load);
            var ctx2 = c2.getContext("2d");
            var scale = 100;
            var wt = width;
            var ht = height;
            var xt = Xcenter;
            var yt = Ycenter;
            var wt_normal = parseFloat(wt)*scale;
            var ht_normal = parseFloat(ht)*scale;
            var xt_normal = (parseFloat(xt)*scale)-(parseFloat(wt_normal)/2);
            var yt_normal = (parseFloat(yt)*scale)-(parseFloat(ht_normal)/2);

             //ค่าที่แสดงผล
            x = xt_normal;
            y = yt_normal;
            w = wt_normal;
            h = ht_normal;
            //ค่าจริงที่ได้รับจาก DB
            xr = xt;
            yr = yt;
            wr = wt;
            hr = ht;

            ctx2.drawImage(img_load,0,0,scale,scale);
            ctx2.strokeStyle = "#00FF00";
            ctx2.strokeRect(x, y, w, h);
            ctx2.stroke();
        }

        function click_edit(id,Xcenter,Ycenter,width,height,imgname,line,model,date)
        {

            // lineselect = line;
            // modelselect = model;
            // dateselect = date;
            // imgnameselect = imgname;
            // Xcenterselect = Xcenter;
            // Ycenterselect = Ycenter;
            // widthselect = width;
            // heightselect = height;
            
            linekeep = line;
            
            // 'http://43.72.52.14/DAI/'+line+'/'+model+'/'+date+'/'+value.ImageName+
            document.getElementById("scream").src = 'http://43.72.52.14/DAI/'+line+'/'+model+'/'+date+'/'+imgname;
            var img = document.getElementById("scream")
            var width_imgsize = img.width;
            var height_imgsize = img.height;
            var wt = width;
            var ht = height;
            var xt = Xcenter;
            var yt = Ycenter;
            var wt_normal = parseFloat(wt)*300;
            var ht_normal = parseFloat(ht)*300;
            var xt_normal = (parseFloat(xt)*300)-(parseFloat(wt_normal)/2);
            var yt_normal = (parseFloat(yt)*300 )-(parseFloat(ht_normal)/2);
            //ค่าที่แสดงผล
            x = xt_normal;
            y = yt_normal;
            w = wt_normal;
            h = ht_normal;
            //ค่าจริงที่ได้รับจาก DB
            xr = xt;
            yr = yt;
            wr = wt;
            hr = ht;
            //ที่เก็บ ID ช่วงคราว
            IDkeep = id;

            var position_x = document.getElementById("position_x").value=x;
            var position_y = document.getElementById("position_y").value=y;
            var mag_x = document.getElementById("position_x_length").value=w;
            var mag_y = document.getElementById("position_y_length").value=h;

            ctx.clearRect(0,0,width_imgsize,height_imgsize);
            ctx.drawImage(img,0,0,300,300);
            ctx.strokeStyle = "#00FF00";
            ctx.strokeRect(x, y, w, h);
            ctx.stroke();
            // ctx.drawImage(img,img.width,img.height);
            // ctx.drawImage(img,img,width_imgsize,height_imgsize,width_imgsize,height_imgsize);

            var showTable = '';
            showTable += '<table class="table table-bordered table-head-bg-info table-bordered-bd-info">';
                showTable += '<thead>';
                    showTable += '<tr>';
                        showTable += '<th scope="col">X center</th>';
                        showTable += '<th scope="col">Y center</th>';
                        showTable += '<th scope="col">Width</th>';
                        showTable += '<th scope="col">Height</th>';
                    showTable += '</tr>';
                showTable += '</thead>';
                showTable += '<tr>';
                    showTable += '<td>'+parseFloat(xr).toFixed(3)+'</td>';
                    showTable += '<td>'+parseFloat(yr).toFixed(3)+'</td>';
                    showTable += '<td>'+parseFloat(wr).toFixed(3)+'</td>';
                    showTable += '<td>'+parseFloat(hr).toFixed(3)+'</td>';
                showTable += '</tr>';
            showTable += '</table>';
            showTable += '<p id="debug">TestDebugs<p>'
            document.getElementById('tableShowValueModal').innerHTML = showTable;
            document.getElementById('exampleModalLongTitle').innerHTML = 'EDIT PICTURE SERIE: '+imgname;

            // alert('x,y,w,h: '+xt+','+yt+','+wt+','+ht);

            

        }

        // รอ event ที่เกิดจากเม้าส์
        var rectStartX, rectStartY;

        function getPosition( element ) {
            var rect = element.getBoundingClientRect();
            return {
                x: rect.left,
                y: rect.top
            };
        }

        // // ปิดเมื่อเม้าส์คลิ๊กบน canvas
        // c.addEventListener("mousedown", function(e) {
        //     rectStartX = e.clientX;
        //     rectStartY = e.clientY;
        // });



        // // ลากและย้ายกรอบเมื่อเม้าส์ย้าย
        // c.addEventListener("mousemove", function(e) {
        //     document.getElementById("scream").src = 'http://43.72.52.14/DAI/'+lineselect+'/'+modelselect+'/'+dateselect+'/'+imgnameselect;
        //     var img = document.getElementById("scream")
        //     if (rectStartX && rectStartY) {
        //         // let pos = getPosition(c)
        //         // alert(pos.x)
        //         // alert(pos.y)

        //         document.getElementById('debug').innerHTML = e.clientX+''+e.clientY;

        //         ctx.clearRect(0,0,300,300);
        //         ctx.drawImage(img,0,0,300,300);
        //         ctx.strokeStyle = "#00FF00";
        //         ctx.strokeRect(x, y, w, h);
        //         ctx.stroke();

                
        //     }
        // });

        // // ปลดล็อคตำแหน่งเริ่มต้นเมื่อเม้าส์ปล่อย
        // c.addEventListener("mouseup", function(e) {
            
        //     rectStartX = 0;
        //     rectStartY = 0;
        // });



        


        function change_x()
        {
            var position_x = document.getElementById("position_x").value;
            // console.log('x: ',position_x);
            // console.log('w: ',w);
            
            if((parseFloat(position_x)+parseFloat(w))<=300){
                ctx.clearRect(0,0, 300, 300);
                ctx.drawImage(img,0,0,300,300);
                ctx.strokeStyle = "#00FF00";
                ctx.strokeRect(position_x, y, w, h);
                x = position_x;
                var position_x_max = document.getElementById("position_x").max=300-w;
                var mag_x = document.getElementById("position_x_length").max=300-x;
                
                xr = ((parseFloat(x)+(parseFloat(w)/2))/300);

                var showTable = '';
                showTable += '<table class="table table-bordered table-head-bg-info table-bordered-bd-info">';
                    showTable += '<thead>';
                        showTable += '<tr>';
                            showTable += '<th scope="col">X center</th>';
                            showTable += '<th scope="col">Y center</th>';
                            showTable += '<th scope="col">Width</th>';
                            showTable += '<th scope="col">Height</th>';
                        showTable += '</tr>';
                    showTable += '</thead>';
                    showTable += '<tr>';
                        showTable += '<td>'+parseFloat(xr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(yr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(wr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(hr).toFixed(3)+'</td>';
                    showTable += '</tr>';
                showTable += '</table>';
                document.getElementById('tableShowValueModal').innerHTML = showTable;

                ctx.stroke();
            }

        }

        function change_x_length()
        {
            var mag_x = document.getElementById("position_x_length").value;
            // console.log('x: ',x);
            // console.log('w: ',mag_x);

            if((parseFloat(mag_x)+parseFloat(x))<=300){
                ctx.clearRect(0,0, 300, 300);
                ctx.drawImage(img,0,0,300,300);
                ctx.strokeStyle = "#00FF00";
                ctx.strokeRect(x, y, mag_x, h);
                w = mag_x;
                var mag_x = document.getElementById("position_x_length").max=300-x;
                var position_x_max = document.getElementById("position_x").max=300-w;
                
                wr = (parseFloat(w)/300);
                xr = ((parseFloat(x)+(parseFloat(w)/2))/300);

                var showTable = '';
                showTable += '<table class="table table-bordered table-head-bg-info table-bordered-bd-info">';
                    showTable += '<thead>';
                        showTable += '<tr>';
                            showTable += '<th scope="col">X center</th>';
                            showTable += '<th scope="col">Y center</th>';
                            showTable += '<th scope="col">Width</th>';
                            showTable += '<th scope="col">Height</th>';
                        showTable += '</tr>';
                    showTable += '</thead>';
                    showTable += '<tr>';
                        showTable += '<td>'+parseFloat(xr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(yr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(wr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(hr).toFixed(3)+'</td>';
                    showTable += '</tr>';
                showTable += '</table>';
                document.getElementById('tableShowValueModal').innerHTML = showTable;

                ctx.stroke();
            }

        }

        function change_y()
        {
            var position_y = document.getElementById("position_y").value;
            // console.log('y: ',position_y);
            // console.log('h: ',);
            
            if((parseFloat(position_y)+parseFloat(h))<=300){
                ctx.clearRect(0,0, 300, 300);
                ctx.drawImage(img,0,0,300,300);
                ctx.strokeStyle = "#00FF00";
                ctx.strokeRect(x,position_y, w, h);
                y = position_y;
                var position_y_max = document.getElementById("position_y").max=300-h;
                var mag_y = document.getElementById("position_y_length").max=300-y;
                
                yr = ((parseFloat(y)+(parseFloat(h)/2))/300);

                var showTable = '';
                showTable += '<table class="table table-bordered table-head-bg-info table-bordered-bd-info">';
                    showTable += '<thead>';
                        showTable += '<tr>';
                            showTable += '<th scope="col">X center</th>';
                            showTable += '<th scope="col">Y center</th>';
                            showTable += '<th scope="col">Width</th>';
                            showTable += '<th scope="col">Height</th>';
                        showTable += '</tr>';
                    showTable += '</thead>';
                    showTable += '<tr>';
                        showTable += '<td>'+parseFloat(xr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(yr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(wr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(hr).toFixed(3)+'</td>';
                    showTable += '</tr>';
                showTable += '</table>';
                document.getElementById('tableShowValueModal').innerHTML = showTable;

                ctx.stroke();
            }

        }

        function change_y_length()
        {
            var mag_y = document.getElementById("position_y_length").value;
            // console.log('y: ',y);
            // console.log('h: ',mag_y);

            if((parseFloat(mag_y)+parseFloat(y))<=300){
                ctx.clearRect(0,0, 300, 300);
                ctx.drawImage(img,0,0,300,300);
                ctx.strokeStyle = "#00FF00";
                ctx.strokeRect(x, y, w, mag_y);
                h = mag_y;
                var mag_y = document.getElementById("position_y_length").max=300-y;
                var position_y_max = document.getElementById("position_y").max=300-h;
                
                hr = (parseFloat(h)/300);
                yr = ((parseFloat(y)+(parseFloat(h)/2))/300);

                var showTable = '';
                showTable += '<table class="table table-bordered table-head-bg-info table-bordered-bd-info">';
                    showTable += '<thead>';
                        showTable += '<tr>';
                            showTable += '<th scope="col">X center</th>';
                            showTable += '<th scope="col">Y center</th>';
                            showTable += '<th scope="col">Width</th>';
                            showTable += '<th scope="col">Height</th>';
                        showTable += '</tr>';
                    showTable += '</thead>';
                    showTable += '<tr>';
                        showTable += '<td>'+parseFloat(xr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(yr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(wr).toFixed(3)+'</td>';
                        showTable += '<td>'+parseFloat(hr).toFixed(3)+'</td>';
                    showTable += '</tr>';
                showTable += '</table>';
                document.getElementById('tableShowValueModal').innerHTML = showTable;

                ctx.stroke();
            }else{
                console.log('ERROR');
            }
        }

        function saveEdit(){
            var cx = ((parseFloat(x)+(parseFloat(w)/2))/300);
            var a1 = parseInt(w)/2;
            var a2 = parseInt(x)+parseInt(a1);
            var a3 = parseInt(a2)/3;
            var ccx = parseFloat(cx).toFixed(6)

            var cy = ((parseFloat(y)+(parseFloat(h)/2))/300);
            // var ccy = ParseFloat(cy,6);
            var ccy = parseFloat(cy).toFixed(6)
            // console.log('center y: '+ccy);

            var wp = (parseFloat(w)/300);
            var wpp = parseFloat(wp).toFixed(6)
            // console.log('width: '+wpp);

            var hp = (parseFloat(h)/300);
            var hpp = parseFloat(hp).toFixed(6)
            // console.log('heigth: '+hpp);

            swal({
                title: 'ยืนยันการบันทึกข้อมูล?',
                text: "ต้องการบันทึกข้อมูลหรือไม่",
                type: 'warning',
                buttons:{
                    confirm: {
                        text : 'บันทึก',
                        className : 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        className: 'btn btn-danger'
                    }
                }
            }).then((save) => {
                if (save) {
                    
                    $.ajax({
                        type: "GET",
                        url: "ajax/ImageSize.php",
                        async: false,
                        cache: false,
                        data:{
                            id:IDkeep,
                            Xcenter:parseFloat(ccx).toFixed(6),
                            Ycenter:parseFloat(ccy).toFixed(6),
                            Width:parseFloat(wpp).toFixed(6),
                            Height:parseFloat(hpp).toFixed(6),
                        },
                        success: function(result){
                            // alert(result);
                            // console.log(result);
                            swal("บันทึกข้อมูลเรียบร้อย", {
                                icon: "success",
                                buttons : {
                                    confirm : {
                                        className: 'btn btn-success'
                                    }
                                }
                            });
                            selectBotton(IDkeep,1,linekeep);
                            searchData();
                            $('#exampleModalCenter').modal('hide');
                        }
                    });
                    
                } else {
                    swal.close();
                }
            });

        }

        
        var getLine ;
        
        searchDataBiz();

        function searchDataBiz(){
            var line = '';
            var myid = '<?php echo $EmpID_Login; ?>'

            $.ajax({
                type: "GET",
                url: "ajax/SearchLine.php",
                async: false,
                cache: false,
                data : {myid:myid},
                success: function(result){
                    // alert(result);
                    console.log(result);
                    
                    
                    getLine = JSON.parse(result);
                  
                    // line += '<option >select line</option>';
                    for (const [key,value] of Object.entries(getLine)) {
                        line += '<option  value ="'+key+'">'+key+'</option>';
                    }
                    document.getElementById('pillSelectBiz').innerHTML = line;
                    
                }
            });
        }

        function searchDataLine(){
            var biz = document.getElementById('pillSelectBiz').value;
            var line = '';

            if(biz!='select biz'){
                line += '<option>select line</option>';
            }

            for(const [key,value] of Object.entries(getLine[biz])){
                // console.log(key);
                line += '<option  value ="'+key+'">'+key+'</option>';
            }
            document.getElementById('pillSelectLine').innerHTML = line;
        }
        
        function searchDataModel(){
            var biz = document.getElementById('pillSelectBiz').value;
            var line = document.getElementById('pillSelectLine').value;
            var model = '';
            
            if(line!='select line'){
                model += '<option>select model</option>';
            }

            for(const [key,value] of Object.entries(getLine[biz])){
                for(const [key_ingetLine,value_ingetLine] of Object.entries(value)){
                    if(key==line){
                        model += '<option  value ="'+value_ingetLine+'">'+value_ingetLine+'</option>';
                    }
                    
                }
                
            }
            document.getElementById('pillSelectModel').innerHTML = model;
        }


        async function searchData (){
            var biz = document.getElementById("pillSelectBiz").value;
            var line = document.getElementById("pillSelectLine").value;
            var model = document.getElementById("pillSelectModel").value;
            // var date = document.getElementById("datepicker").value;
            // var start = document.getElementById("timestart").value;
            // var stop = document.getElementById("timeend").value;

            // console.log(start);

            var data = '';
            // if(biz=='select biz' || line=='select line' || line=='' || model=='' || model=='select model' || date=='' || start=='' || stop=='')
            if(biz=='select biz' || line=='select line' || line=='' || model=='' || model=='select model'){
                swal("ข้อมูลไม่ครบ!", "กรุณากรอกข้อมูลให้ครบ", {
                    icon : "warning",
                    buttons: {
                        confirm: {
                            className : 'btn btn-warning'
                        }
                    },
                });
            }else{
                document.getElementById("tableShowData").innerHTML = '<div class="h-100 d-flex align-items-center justify-content-center"><div class="loader-me">  <div></div><div></div><div></div><div></div></div></div>';
                const myTimeout = setTimeout(()=>{
                    $.ajax({
                        type: "GET",
                        url: "ajax/SearchDataInput.php",
                        async: true,
                        cache: false,
                        data:{
                            biz:biz,
                            line:line,
                            model:model,
                            // date:date,
                            // start:start,
                            // stop:stop,
                        },
                        success: function(result){
                            var count = 0;
                            try
                            {
                                getdata = JSON.parse(result);
                                var jsonString = JSON.stringify(getdata);

                                data += '<table class="table table-hover" id="tableData">';
                                    data += '<thead>';
                                        data += '<tr>';
                                            
                                            data += '<th scope="col">NO.</th>';
                                            data += '<th scope="col">Image</th>';
                                            data += '<th scope="col">Model</th>';
                                            data += '<th scope="col">Job</th>';
                                            data += '<th scope="col">Date</th>';
                                            data += '<th scope="col">Edit</th>';
                                            data += '<th scope="col">OK</th>';
                                            data += '<th scope="col">NG</th>';
                                            data += '<th scope="col">Select</th>';
                                        data += '</tr>';
                                    data += '</thead>';
                                    data += '<tbody>';
                                        for (const [key,value] of Object.entries(getdata)) {
                                            count += 1;
                                            data += '<tr>';
                                                // data += '<td>';

              
                                                    
                                                // data += '</td>';
                                                data += '<td>'+count+'</td>';
                                                data += '<td>';
                                                    data += '<canvas class="mt-3" id="'+value.ID+'" width="100" height="100" style="border-color:pink;border-width:7px;">';
                                                    // http://43.72.52.14/DAI/DEBUG/TK-Top/2022-12-16/02-084635.jpg
                                                    // $pathServer = 'http://43.72.52.14/DAI/'+line+'/'+model+'/'+date+'/'+value.ImageName;
                                                        data += '<img  id ="img'+value.ID+'"  onerror="this.src=\''+'assets/img/noimg.jpg'+'\'" onload="loadcanvas('+value.ID+','+value.Xcenter+','+value.Ycenter+','+value.Width+','+value.Height+','+value.ID+')" src="http://43.72.52.14/DAI/'+line+'/'+model+'/'+date+'/'+value.ImageName+'" alt="">';
                                                        // data += '<img  id ="img'+value.ID+'" onload="loadcanvas('+value.ID+','+value.Xcenter+','+value.Ycenter+','+value.Width+','+value.Height+','+value.ID+')" src="./TK-Top/'+value.ImageName+'" alt="">';    
                                                    data += '</canvas>';
                                                data += '<p>'+value.EFcard+'</p>';
                                                data += '</td>';
                                                data += '<td>'+value.Model+'</td>';
                                                data += '<td>'+value.Job+'</td>';
                                                data += '<td>'+value.DateTime+'</td>';
                                                data += '<td><button type="button" class="btn btn-secondary btn_new" data-toggle="modal" data-target="#exampleModalCenter" onclick="click_edit('+value.ID+','+value.Xcenter+','+value.Ycenter+','+value.Width+','+value.Height+',\''+value.ImageName+'\',\''+line+'\',\''+model+'\',\''+date+'\')"><i class="fas fa-edit"></i> edit</button></td>';
                                                if(value.Judge==0){
                                                    data += '<td class="table-success"><a herf="#" onclick="checkUp('+value.ID+','+value.Judge+')" ><i class="far fa-square fa-lg"></i></a></td>';
                                                    data += '<td <tr class="table-danger"><a herf="#" ><i class="far fa-check-square fa-lg"></i></a></td>';
                                                }else if(value.Judge==1){
                                                    data += '<td class="table-success"><a herf="#" ><i class="far fa-check-square fa-lg"></i></a></td>';
                                                    data += '<td <tr class="table-danger"><a herf="#" onclick="checkUp('+value.ID+','+value.Judge+')" ><i class="far fa-square fa-lg"></i></a></td>';
                                                }

                                                data += '<td>';
                                                    data += '<div id ="buttonSelect'+value.ID+'" class="btn-group" role="group" aria-label="Basic checkbox toggle button group">';
                                                    if(!value.Update_By )
                                                    {
                                                        data += '<button class="btn btn-primary" onclick="selectBotton('+value.ID+',1,\''+line+'\')"> <i class="fas fa-check"></i></button>';
                                                    }
                                                    else{
                                                        data += '<button class="btn btn-danger" onclick="selectBotton('+value.ID+',0,\''+line+'\')"> <i class="fas fa-times"></i></button>';

                                                    }
                                                    
                                                   
                                                    data += '</div>';
                                                data += '</td>';      
                                        }

                                        
                                            // data += '<td></td>';
                                        data += '</tr>';
                                    data += '</tbody>';
                                data += '</table>';
                                document.getElementById("tableShowData").innerHTML = data;
                    
                            }
                            catch(e)
                            {
                                data += '<table class="table table-hover" id="tableData">';
                                    data += '<thead>';
                                        data += '<tr>';
                                            data += '<th scope="col">NO.</th>';
                                            data += '<th scope="col">Image</th>';
                                            data += '<th scope="col">Model</th>';
                                            data += '<th scope="col">Job</th>';
                                            data += '<th scope="col">Date</th>';
                                            data += '<th scope="col">Edit</th>';
                                            data += '<th scope="col">OK</th>';
                                            data += '<th scope="col">NG</th>';
                                        data += '</tr>';
                                    data += '</thead>';
                                    data += '<tbody>';
                                    data += '</tbody>';
                                data += '</table>';
                                // document.getElementById("tableShowData").innerHTML = "<p class='text-center'>No data available in table</p>";
                                document.getElementById("tableShowData").innerHTML = data;
                                
                            }

                            setTimeout(()=>{$('#tableData').DataTable({})},1000);
                    
                        }
                    });
                
                
                    
                
                },1200);
                
            }

        }

        function selectBotton(id,status,line){
            //status ==1  เช็ค หมายความใน ดาต้าเบสเก็บรหัสพนักงาน
            //status == 0 หมายความว่า ดาต้าเบส เก็บค่าเป็น NULL
           
            // document.getElementById("buttonSelect").innerHTML = data;
            var EmpID = <?php echo $EmpID_Login?>;
            $.ajax({
                type: "GET",
                url: "ajax/SelectUpdate.php",
                async: true,
                cache: false,
                data: {
                    id:id,
                    EmpID:EmpID,
                    status:status,
                },
                success: function(result){
                    // alert(result);
                    if(status==1)
                    {
                      document.getElementById("buttonSelect"+id).innerHTML = '<button class="btn btn-danger" onclick="selectBotton('+id+',0)"> <i class="fas fa-times"></i></button>';
                    }
                    else{
                        document.getElementById("buttonSelect"+id).innerHTML = '<button class="btn btn-primary" onclick="selectBotton('+id+',1)"> <i class="fas fa-check"></i></button>';

                    }
                }
            });
        }

        function checkUp(getID,Judge){
            var id = getID;
            var Judge = Judge;

            swal({
                title: 'ยืนยันที่จะเปลี่ยนแปลงค่า?',
                text: "กรุณาตรวจสอบให้แน่ใจก่อนการเปลี่ยนค่า!",
                type: 'warning',
                buttons:{
                    confirm: {
                        text : 'ยืนยัน!',
                        className : 'btn btn-success'
                    },
                    cancel: {
                        visible: true,
                        className: 'btn btn-danger'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    $.ajax({
                type: "GET",
                url: "ajax/CheckOKNG.php",
                async: false,
                cache: false,
                data:{
                    id:id,
                    Judge:Judge,
                },
                success: function(result){
                    searchData();
                }
            });
                    swal({
                        title: 'สำเร็จ!',
                        text: 'เปลี่ยนแปลงค่าเรียบร้อยแล้ว.',
                        type: 'success',
                        buttons : {
                            confirm: {
                                className : 'btn btn-success'
                            }
                        }
                    })
                } else {
                    swal.close();
                }
            })
        }


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