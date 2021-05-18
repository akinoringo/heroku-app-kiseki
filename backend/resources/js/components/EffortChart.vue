<template>
	<div>
		<label>
			<input type="radio" v-model="chartType" value="1">積み上げた回数
		</label>
		<label class="ml-2">
			<input type="radio" v-model="chartType" value="2">積み上げた時間
		</label>		
		<bar-chart 
			:chartData="countData" ref="countChart" v-show="chartType === '1' ">
		</bar-chart>		
		<bar-chart 
			:chartData="timeData" ref="timeChart" v-show="chartType === '2' ">
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
    userid: ''
	},
	data() {
		return {
			apiEffortData: {},
			countData: {},
			timeData: {},
			chartType: "1",
			id: this.userid,
			goalsTitle: [],
			countdatasets: [],
			timedatasets: [],
			color: ["#EE3768", "#03D1Aa", "#d3d3d3"]
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
				labels: this.apiEffortData.daysOnWeek,
				datasets: this.countdatasets,
			});			
			this.timeData = Object.assign({}, this.timeData, {
				labels: this.apiEffortData.daysOnWeek,
				datasets: this.timedatasets,
			});
			this.$nextTick(() => {
				this.$refs.countChart.renderBarChart();
				this.$refs.timeChart.renderBarChart();
			});
		},
		setDatasets() {
			this.goalsTitle = this.apiEffortData.goalsTitle;
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
	}
};
</script>