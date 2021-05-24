<script>
import { Bar } from "vue-chartjs";
export default {
  extends: Bar, 
  props: {
    chartData: {
      type: Object
    },
    ylabel: String,
  }, 
  methods: {
    renderBarChart() {
      this.renderChart(
        this.chartData,
        {
          scales: {
            xAxes: [
              {
                stacked: true
              }
            ],
            yAxes: [
              {
                scaleLabel: {
                  display: true,
                  labelString: this.ylabel,
                  fontSize: 16,
                },
                stacked: true,
                ticks: {
                  beginAtZero: true,
                  min: 0,
                  suggestedMax: 8,
                }
              }
            ]
          }
        }
      );
    }
  },
};
</script>