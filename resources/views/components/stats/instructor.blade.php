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
            new ApexCharts(document.querySelector("#reportsChart"), {
              series: [{
                name: 'Instructor 1',
                data: [31, 40, 28, 51, 42, 82, 56],
              }, {
                name: 'Instructor 2',
                data: [11, 32, 45, 32, 34, 52, 41]
              }, {
                name: 'Instructor 3',
                data: [15, 11, 32, 18, 9, 24, 11]
              }, {
                name: 'Instructor 4',
                data: [20, 12, 32, 34, 10, 27, 12]
              }, {
                name: 'Instructor 5',
                data: [16, 9, 5, 10, 7, 2, 1]
              }],
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
                type: 'datetime',
                categories: ["2024-01-19T00:00:00.000Z", "2024-02-19T01:30:00.000Z", "2024-03-19T02:30:00.000Z", "2024-04-19T03:30:00.000Z", "2024-05-19T04:30:00.000Z", "2024-06-19T05:30:00.000Z", "2024-07-19T06:30:00.000Z"]
              },
              tooltip: {
                x: {
                  format: 'dd/MM/yy HH:mm'
                },
              }
            }).render();
          });
        </script>
        <!-- End Line Chart -->

      </div>

    </div>
  </div>