<script>
import { Bar } from "vue-chartjs";
export default {
  extends: Bar,
  props: {
    chartData: {
      type: Object
    }
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
                stacked: true,
                ticks: {
                  beginAtZero: true,
                  min: 0,
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