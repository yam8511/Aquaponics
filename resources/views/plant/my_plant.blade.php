@extends('layouts.plant')

@section('content')
	<div class="w3-display-middle" style="position:fixed; z-index: 9999;" id="loading"><i class="fa fa-spinner fa-spin w3-text-teal w3-jumbo"></i></div>
	<p id="other" style="text-align:center;"></p>
	<div id="showmsg" align="center">
	<h2>your plants <input  type="image"  name="submit_Btn"  id="submit_Btn" data-toggle="modal" data-target="#myModal_teach" img src="{{ url('img/536217.jpg') }}" style="width:30px;height:30px;"></h2>

		<!-- Trigger the modal with a button -->
		@foreach($myPlants as $index => $myPlant)
			<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal{{ $myPlant->id }}">{{ $myPlant->plantname }}</button>
			<!-- Modal for plants-->
			<div class="modal fade" id="myModal{{ $myPlant->id }}" role="dialog">
			<div class="modal-dialog">
		
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">種植時間</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="start">開始時間：</label>
							<input class="form-control" id="time_start" readonly value="{{ $myPlant->startdate }}">
						
							<label for="end">結束時間</label>
							<input id="end{{ $myPlant->id }}" class="form-control" type="date" value="{{ $myPlant->enddate or '' }}" onchange="getDate('end{{ $myPlant->id }}')">
        					
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-default" onclick="add_endtime({{ $myPlant->id }})">送出</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
						</div>
					</div>
				</div>
			</div>
			</div>
		@endforeach
	</div>

	@include('plant.gallery')

    
    <div class="modal fade" id="myModal_teach" role="dialog">
			<div class="modal-dialog">
		
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Q&A</h4>
					</div>
					<div class="modal-body"  style="text-align:left;">
						<p>Q：要怎麼結束種植？</p>
						<p>A：按下按鈕後，填入結束種植日期</p>
						<hr>
						<p>Q：要怎麼增加植物</p>
						<p>A：回到plant library即可增加植物</p>
						<hr>
						<p>Q：這些照片是什麼時候的照片？</p>
						<p>A：滑鼠移到照片上就可以知道囉！</p>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
						</div>
						
					</div>
				</div>
			</div>
		</div>


<script>
	$('document').ready(function () {
	    $('#loading').fadeOut();
    });

    var date;
    function getDate(id){
        date = document.getElementById('end'+id).value;
        //alert(date);
    }
    
    /**
     * 當點擊加入植物後，使用Ajax去新增my_plants資料
     */
    function add_endtime(plant_id) {
        //var date = document.getElementById('bookdate').value;
        getDate(plant_id);
        console.log(date);
      $.ajax({
          type: 'POST',
          url: '{{ url("endPlant") }}',
          dataType: 'json',
          data: {
              myPlantId: plant_id,
              endDate: date,
              _token: '{{ csrf_token() }}'
          },
          success: function(jData) {
              if (jData.result == 'ok')
                  setMessage('success', jData.msg);
              else
                  setMessage('error', jData.msg);
          },
          error: function(error) {
          	console.log(error);
              setMessage('error', '加入結束時間的Ajax 發生錯誤');
              $('body').html(error.responseText);
          }
      });
    }

    /**
     * 當使用ajax時, 成功或失敗都可以呼叫此function
     * 以顯示成功或錯誤訊息, 過數秒後消失
     */
    function setMessage(status = '', message = '') {
        $('#other').text(message);
        if (status == 'success') {
            $('#other').addClass('w3-text-green');
            $('#other').removeClass('w3-text-red');
        } else if (status == 'error') {
            $('#other').addClass('w3-text-red');
            $('#other').removeClass('w3-text-green');
        } else {
            $('#other').removeClass('w3-text-red');
            $('#other').removeClass('w3-text-green');
        }
        setTimeout(function(){
          $('#other').text('');
        }, 6000);
    }
    
</script>
@endsection