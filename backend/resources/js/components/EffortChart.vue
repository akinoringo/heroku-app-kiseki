<template>
	<div>
    <div class="text-center mb-2">
      <input type="date" v-model="startdate" placeholder="20210924">
      ~
      <input type="date" v-model="enddate" placeholder="20210924">
      <button type="submit" @click="rerender" class="btn btn-sm btn-info text-white">表示</button>
    </div>		
    <div class="text-center">
			<label>
				<input type="radio" v-model="chartType" value="1">積み上げた回数
			</label>
			<label class="ml-2">
				<input type="radio" v-model="chartType" value="2">積み上げた時間
			</label>	    	
    </div>	
		<ul>
			<li class="text-danger" v-for="error in errorsStartdate" :key="error">
				{{ error }}
			</li>			
		</ul>	
		<ul>
			<li class="text-danger" v-for="error in errorsEnddate" :key="error">
				{{ error }}
			</li>			
		</ul>			    		
		<bar-chart 
			:chartData="countData" ylabel="積み上げ回数 (回)" ref="countChart" v-show="chartType === '1' ">
		</bar-chart>		
		<bar-chart 
			:chartData="timeData" ylabel="積み上げ時間 (時間)" ref="timeChart" v-show="chartType === '2' ">
		</bar-chart>	
	</div>
</template>

<script>
import BarChart from './BarChart'
export default {
	components: {
		BarChart
	},
	props: {
    userid: '',
	},
	data() {
		return {
			startdate: "",
			enddate: "",
			errorsStartdate: [],
			errorsEnddate: [],
			apiEffortData: {},
			countData: {},
			timeData: {},
			chartType: "1",
			id: this.userid,
			goalsTitle: [],
			countdatasets: [],
			timedatasets: [],
			color: ["#EE3768", "#03D1Aa", "#d3d3d3", "blue", "yellow"]
		};
	},
	mounted() {
		this.$http.get(`/${this.id}/effortgraph`).then(responce => {
			this.apiEffortData = responce.data;
			this.setDatasets();
			this.setChart();			
		});
	},
	methods: {
		setChart() {
			this.countData = Object.assign({}, this.countData, {
				labels: this.apiEffortData.daysOnWeekFormated,
				datasets: this.countdatasets,
			});			
			this.timeData = Object.assign({}, this.timeData, {
				labels: this.apiEffortData.daysOnWeekFormated,
				datasets: this.timedatasets,
			});
			this.$nextTick(() => {
				this.$refs.countChart.renderBarChart();
				this.$refs.timeChart.renderBarChart();
			});
		},
		setDatasets() {
			this.goalsTitle = this.apiEffortData.goalsTitle;
			this.timedatasets = [];
			this.countdatasets = [];
			for (let i = 0; i<this.apiEffortData.goalsTitle.length; i++){
				this.timedatasets.push({
					label: this.goalsTitle[i],
					backgroundColor: this.color[i],
					data: this.apiEffortData.effortsTimeTotalOnWeek[i]
				});
			}
			for (let i = 0; i<this.apiEffortData.goalsTitle.length; i++){
				this.countdatasets.push({
					label: this.goalsTitle[i],
					backgroundColor: this.color[i],
					data: this.apiEffortData.effortsCountOnWeek[i]
				});
			}			
		},
		rerender() {
			this.$refs.countChart.$data._chart.destroy();
			this.$refs.timeChart.$data._chart.destroy();		
			this.$http
				.get(`/${this.id}/effortgraph`, {
					params: 
						{
							startdate: this.startdate, 
							enddate: this.enddate,
						}
				})
				.then(responce => {
					if (responce.data.result === false) {
						this.errorsStartdate = responce.data.errors['startdate'];
						this.errorsEnddate = responce.data.errors['enddate'];
					} else {
						this.errorsStartdate = [];
						this.errorsEnddate = [];
						this.apiEffortData = responce.data;
						this.setDatasets();
						this.setChart();						
					}		
				});				
		},
	}
};
</script>










