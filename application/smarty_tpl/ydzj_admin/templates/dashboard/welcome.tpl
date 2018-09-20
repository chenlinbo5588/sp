{include file="common/main_header_navs.tpl"}
  {config_load file="wuye.conf"}
  {form_open(site_url($uri_string),'id="formSearch" method="get"')}
  	 <input type="hidden" name="page" value="{$currentPage}"/>
    <table class="tb-type1 noborder search" >
		<tr>
		  <td>请选择小区:</td>
        	<td colspan="2">
        		<select name="resident_id">
        			{foreach from=$residentList item=item}
        			<option value="{$item['id']}" {if $residentId == $item['id']}selected{/if}>{$item['name']|escape}</option>
        			{/foreach}
        		</select>
	         </td>
		  <td colspan="2"><input type="submit" class="msbtn" name="tijiao" value="查询"/></td>
		</tr>
	</table>
    <div id="top" style="width: 600px;height:400px;"></div>
    <div id="middle" style="width: 600px;height:400px;"></div>
    <div id="month" style="width: 600px;height:400px;"></div>
    <div id="year" style="width: 600px;height:400px;"></div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('top'));

        // 指定图表的配置项和数据
		option = {
		    title:{
	        	text: '{$residentName}' + '前七天报表'
	    	},
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['物业费','能耗费']
		    },
	        grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '3%',
		        containLabel: true
		    },
		    xAxis: {
		        type: 'category',
		        data: {$dateJson},
		    },
		    yAxis : [{
			    type : 'value',
			    axisLabel : {
				    formatter: function(value){
				    return value+"%";}
			    }
		    }],
		    series: [
		    {
		    	name:'物业费',
		        data: {$wuyeDatejson},
		        type: 'line'
		    },
	        {
	        	name:'能耗费',
		        data:  {$nenghaoDatejson},
		        type: 'line'
		    },
		    ]
		};
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
        <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('middle'));

        // 指定图表的配置项和数据
		option = {
		    title: {
	        	text:'{$residentName}' + '周报表'
	    	},
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['物业费','能耗费']
		    },
	        grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '3%',
		        containLabel: true
		    },
		    xAxis: {
		        type: 'category',
		         data: {$weekJson},
		    },
		    yAxis : [{
			    type : 'value',
			    axisLabel : {
				    formatter: function(value){
				    return value+"%";}
			    }
		    }],
		    series: [
		    {
		    	name:'物业费',
		        data: {$wuyeWeekjson},
		        type: 'line'
		    },
	        {
	        	name:'能耗费',
		        data:  {$nenghaoWeekjson},
		        type: 'line'
		    },
		    ]
		};
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
        <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('month'));

        // 指定图表的配置项和数据
		option = {
		    title: {
	        	text: '{$residentName}' + '月报表'
	    	},
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['物业费','能耗费']
		    },
	        grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '3%',
		        containLabel: true
		    },
		    xAxis: {
		        type: 'category',
		        data: {$monthJson},
		    },
		    yAxis : [{
			    type : 'value',
			    axisLabel : {
				    formatter: function(value){
				    return value+"%";}
			    }
		    }],
		    series: [
		    {
		    	name:'物业费',
		        data: {$wuyeMonthjson},
		        type: 'line'
		    },
	        {
	        	name:'能耗费',
		        data:  {$nenghaoMonthjson},
		        type: 'line'
		    },
		    ]
		};
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('year'));

        // 指定图表的配置项和数据
		option = {
		    title: {
	        	text: '{$residentName}' + '年度报表'
	    	},
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:['物业费','能耗费']
		    },
	        grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '3%',
		        containLabel: true
		    },
		    xAxis: {
		        type: 'category',
		        data: {$yearJson},
		    },
		    yAxis : [{
			    type : 'value',
			    axisLabel : {
				    formatter: function(value){
				    return value+"%";}
			    }
		    }],
		    series: [
		    {
		    	name:'物业费',
		        data: {$wuyeYearjson},
		        type: 'line'
		    },
	        {
	        	name:'能耗费',
		        data:  {$nenghaoYearjson},
		        type: 'line'
		    },
		    ]
		};
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
{include file="common/main_footer.tpl"}