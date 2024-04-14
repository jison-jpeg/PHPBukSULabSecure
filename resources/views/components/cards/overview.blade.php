<div class="tab-pane fade show active profile-overview" id="profile-overview" role="tabpanel">

    <h5 class="card-title">Overview</h5>

    <section class="section dashboard">
      <div class="row">

        <!-- Classes Card -->
        <div class="col-xxl-4 col-md-4 col-sm-6">
          <div class="card info-card sales-card">

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

            <div class="card-body">
              <h5 class="card-title"><a href="{{ route('schedules.user', ['id' => $user->id]) }}">Schedules</a></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cart"></i>
                </div>
                <div class="ps-3">
                  <h6>
                    {{$schedulesCount ?? '0'}}
                  </h6>
                  <span class="text-muted small pt-2 ps-1">total</span>

                </div>
              </div>
            </div>

          </div>
        </div><!-- End Classes Card -->

        <!-- Students Card -->
        <div class="col-xxl-4 col-md-4 col-sm-6">
          <div class="card info-card revenue-card">

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

            <div class="card-body">
              <h5 class="card-title"><a href="{{ route('user.students', ['id' => $user->id]) }}">Students</a></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="ps-3">
                  <h6>
                    {{$studentsCount ?? '0'}}
                  </h6>
                  <span class="text-muted small pt-2 ps-1">total</span>

                </div>
              </div>
            </div>

          </div>
        </div><!-- End Studenst Card -->

        <!-- Subjects Card -->
        <div class="col-xxl-4 col-md-4">

          <div class="card info-card customers-card">

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

            <div class="card-body">
              <h5 class="card-title"><a href="{{ route('subjects.user', ['id' => $user->id]) }}">Subjects</a></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-people"></i>
                </div>
                <div class="ps-3">
                  <h6>
                    {{$subjectsCount ?? '0'}}
                  </h6>
                  <span class="text-muted small pt-2 ps-1">total</span>

                </div>
              </div>

            </div>
          </div>

        </div>
        <!-- End Customers Card -->


        

        
      </div>
    </section>

</div>
