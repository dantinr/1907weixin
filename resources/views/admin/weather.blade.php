@extends('layouts.admin')

@section('content')
	<h4>一周气温展示</h4>
	
	城市：<input type="text" name="city"> 
	<input type="button" value='搜索' id='search'> (城市名可以为拼音和汉字)
	
    <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
    <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
    <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
	<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <script>
        
    </script>


	<script type="text/javascript">
		$("#search").on("click",function(){
			//获取城市名
			var city = $('[name="city"]').val();
			if(city == ""){
				alert("请输入城市名");
				return;
			}
			//alert(city);
			//向后台发送ajax请求
			$.ajax({
				url:"{{url('admin/getWeather')}}",  //请求地址
				type:"GET",  //请求类型GET POST
				data:{city:city},  //传输的数据
				dataType:"json", //返回的数据类型
				success:function(res){  //成功执行的方法
					//展示图表天气数据
					var chart = Highcharts.chart('container', {
					    chart: {
					        type: 'columnrange', // columnrange 依赖 highcharts-more.js
					        inverted: true
					    },
					    title: {
					        text: '每月温度变化范围'
					    },
					    subtitle: {
					        text: '2009 挪威某地'
					    },
					    xAxis: {
					        categories: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
					    },
					    yAxis: {
					        title: {
					            text: '温度 ( °C )'
					        }
					    },
					    tooltip: {
					        valueSuffix: '°C'
					    },
					    plotOptions: {
					        columnrange: {
					            dataLabels: {
					                enabled: true,
					                formatter: function () {
					                    return this.y + '°C';
					                }
					            }
					        }
					    },
					    legend: {
					        enabled: false
					    },
					    series: [{
					        name: '温度',
					        data: [
					            [-9.7, 9.4],
					            [-8.7, 6.5],
					            [-3.5, 9.4],
					            [-1.4, 19.9],
					            [0.0, 22.6],
					            [2.9, 29.5],
					            [9.2, 30.7],
					            [7.3, 26.5],
					            [4.4, 18.0],
					            [-3.1, 11.4],
					            [-5.2, 10.4],
					            [-13.5, 9.8]
					        ]
					    }]
					});
				}
			})
		})
	</script>
@endsection

