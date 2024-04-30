<div class="col-xxl-8 col-md-12">
    <div class="card">

      <div class="filter">
        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
          <li class="dropdown-header text-start">
            <h6>Filter</h6>
          </li>

          <li><a class="dropdown-item" href="#">Today</a></li>
          <li><a class="dropdown-item" href="#">This Month</a></li>
          <li><a class="dropdown-item" href="#">This Year</a></li>
        </ul>
      </div>

      <div class="card-body mb-4">
        <h5 class="card-title">Instructor's Overview</span></h5>

        <!-- Line Chart -->
        <div id="reportsChart"></div>

        <script>
          document.addEventListener("DOMContentLoaded", () => {
              const chartData = @json($formattedChartData);
      
              // Extract unique months from the chart data
              const uniqueMonths = [...new Set(chartData.flatMap(item => Object.keys(item.data)))];
              
              // Sort unique months chronologically
              const sortedMonths = uniqueMonths.sort((a, b) => new Date(a) - new Date(b));
      
              // Sort instructors based on total data
              chartData.sort((a, b) => {
                  const totalDataA = Object.values(a.data).reduce((acc, cur) => acc + cur, 0);
                  const totalDataB = Object.values(b.data).reduce((acc, cur) => acc + cur, 0);
                  return totalDataB - totalDataA;
              });
      
              new ApexCharts(document.querySelector("#reportsChart"), {
                  series: chartData.map(item => ({
                      name: item.name,
                      data: sortedMonths.map(month => item.data[month] || 0) // Fill missing data with 0
                  })),
                  chart: {
                      height: 350,
                      type: 'area',
                      toolbar: {
                          show: false
                      },
                  },
                  markers: {
                      size: 4
                  },
                  colors: ['#4154f1', '#2eca6a', '#ff771d'],
                  fill: {
                      type: "gradient",
                      gradient: {
                          shadeIntensity: 1,
                          opacityFrom: 0.3,
                          opacityTo: 0.4,
                          stops: [0, 90, 100]
                      }
                  },
                  dataLabels: {
                      enabled: false
                  },
                  stroke: {
                      curve: 'smooth',
                      width: 2
                  },
                  xaxis: {
                      categories: sortedMonths.map(month => month.replace(/(\d{4})-(\d{2})/, '$2 $1')) // Format month and year
                  },
                  tooltip: {
                      x: {
                          format: 'MMM yyyy' // Format for displaying month and year only
                      },
                      y: {
                          formatter: function (value, { series, seriesIndex, dataPointIndex, w }) {
                              // Get the instructor name
                              const instructorName = w.globals.seriesNames[seriesIndex];
                              return `${instructorName}: ${value}`; // Display tooltip with instructor name
                          }
                      }
                  }
              }).render();
          });
      </script>
      
      
        
        
        <!-- End Line Chart -->

      </div>

    </div>
  </div>