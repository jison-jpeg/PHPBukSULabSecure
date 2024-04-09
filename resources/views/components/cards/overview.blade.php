<div class="tab-pane fade show active profile-overview" id="profile-overview" role="tabpanel">

    <h5 class="card-title">Overview</h5>
    {{-- Instructors Overview of Subjects, Students, etc --}}
    <div class="container">
        <div class="row">
            <!-- Pie Chart -->
            <div id="pieChart" style="min-height: 400px;" class="echart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                echarts.init(document.querySelector("#pieChart")).setOption({
                  title: {
                    text: 'User Statistics',
                    subtext: 'Data',
                    left: 'center'
                  },
                  tooltip: {
                    trigger: 'item'
                  },
                  legend: {
                    orient: 'vertical',
                    left: 'left'
                  },
                  series: [{
                    name: 'Access From',
                    type: 'pie',
                    radius: '50%',
                    data: [{
                        value: 15,
                        name: 'Present'
                      },
                      {
                        value: 5,
                        name: 'Late'
                      },
                      {
                        value: 1,
                        name: 'Absent'
                      },
                    ],
                    emphasis: {
                      itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                      }
                    }
                  }]
                });
              });
            </script>
            <!-- End Pie Chart -->
        </div>
    </div>

    <h5 class="card-title">Statistics</h5>

    <div class="row">
        <div class="col-lg-3 col-md-4 label ">Total Students</div>
        <div class="col-lg-9 col-md-8">50</div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Company</div>
        <div class="col-lg-9 col-md-8">Lueilwitz, Wisoky and Leuschke</div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Job</div>
        <div class="col-lg-9 col-md-8">Web Designer</div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Country</div>
        <div class="col-lg-9 col-md-8">USA</div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Address</div>
        <div class="col-lg-9 col-md-8">A108 Adam Street, New York, NY 535022</div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Phone</div>
        <div class="col-lg-9 col-md-8">(436) 486-3538 x29071</div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 label">Email</div>
        <div class="col-lg-9 col-md-8">k.anderson@example.com</div>
    </div>
</div>
