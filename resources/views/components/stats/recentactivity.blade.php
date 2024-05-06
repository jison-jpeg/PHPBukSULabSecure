<div class="col-xxl-4 col-md-12">
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

    <div class="card-body">
        <h5 class="card-title">Recent Activity <span>| Today</span></h5>

        <div class="activity">
            @foreach ($logs as $log)
                <div class="activity-item d-flex">
                    <div class="activite-label">{{ $log->formatted_time_diff }}</div>
                    @php
                        $badgeColor = '';
                        switch ($log->action) {
                            case 'CREATE':
                                $badgeColor = 'success';
                                break;
                            case 'OUT':
                                $badgeColor = 'warning';
                                break;
                            case 'DELETE':
                                $badgeColor = 'danger';
                                break;
                            case 'UPDATE':
                                $badgeColor = 'info';
                                break;
                            default:
                                $badgeColor = 'primary';
                                break;
                        }
                    @endphp
                    <i class='bi bi-circle-fill activity-badge text-{{ $badgeColor }} align-self-start'></i>
                    <div class="activity-content">
                        {!! nl2br(e($log->description)) !!}
                    </div>
                </div>
            @endforeach
        </div>
        

    </div>
</div>
</div>
