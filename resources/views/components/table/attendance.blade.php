<?php
function getProgressBarClass($remark, $status)
{
    switch ($status) {
        case 'In':
            return $remark === '100%' ? 'bg-success' : 'bg-warning text-dark';
        case '0%':
            return 'bg-danger';
        case 'Absent':
            return 'bg-danger';
        case 'Late':
            return 'bg-warning text-dark';
        case 'Out':
            return 'bg-secondary';
        default:
            return 'bg-medium';
    }
}

function getProgressBarWidth($remark, $status)
{
    if ($status === 'Absent' || $status === 'Out') {
        return '100%';
    } else {
        return (int) $remark ? (int) $remark . '%' : '0%';
    }
}

function getStatusBadgeClass($status)
{
    switch ($status) {
        case 'In':
            return 'bg-success';
        case 'Out':
            return 'bg-secondary';
        case 'Late':
            return 'bg-warning text-dark';
        case 'Absent':
            return 'bg-danger';
        default:
            return 'bg-medium text-dark';
    }
}

$data = [
    [
        'id' => 'f12123',
        'name' => 'Brandon Jacob',
        'room' => 'Comlab 1',
        'subject' => 'IAS 2',
        'schedule' => '7:30 - 10:00 AM (M, TH)',
        'remark' => '100%',
        'status' => 'In',
        'time' => '7:30 AM',
        'date' => '01-26-2024',
    ],
    [
        'id' => 'f13123',
        'name' => 'John Doe',
        'room' => 'Comlab 5',
        'subject' => 'Capstone 2',
        'schedule' => '10:00 AM - 12:00 PM (M, TH)',
        'remark' => '50%',
        'status' => 'Late',
        'time' => '10:00 AM',
        'date' => '01-26-2024',
    ],
    [
        'id' => 'f22123',
        'name' => 'Alice Smith',
        'room' => 'Comlab 3',
        'subject' => 'Multimedia Systems',
        'schedule' => '12:30 PM - 3:00 PM (M, TH)',
        'remark' => '50%',
        'status' => 'In',
        'time' => '12:30 PM',
        'date' => '01-26-2024',
    ],
    [
        'id' => 'f12223',
        'name' => 'Bob Johnson',
        'room' => 'Comlab 2',
        'subject' => 'Elective 5: Web System and Technologies',
        'schedule' => '3:00 PM - 5:30 PM (M, TH)',
        'remark' => '0%',
        'status' => 'Absent',
        'time' => '3:00 PM',
        'date' => '01-26-2024',
    ],
    [
        'id' => 'f11123',
        'name' => 'Eve Davis',
        'room' => 'Comlab 4',
        'subject' => 'ADET',
        'schedule' => '12:30 PM - 5:30 PM (W)',
        'remark' => '5:30 PM',
        'status' => 'Out',
        'time' => '5:30 PM',
        'date' => '01-26-2024',
    ],
];
?>

<div class="card ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title">Attendance</h5>
            <button type="button" class="btn btn-primary">Export</button>
        </div>
        <!-- Table with hoverable rows -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Room</th>
                        <th scope="col">Subject</th>
                        <th scope="col">Schedule</th>
                        <th scope="col">Remarks</th>
                        <th scope="col">Status</th>
                        <th scope="col">Time</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $index => $row): ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['room']; ?></td>
                        <td><?php echo $row['subject']; ?></td>
                        <td><?php echo $row['schedule']; ?></td>
                        <td>
                            <div class="progress mt-1-5">
                                <div class="progress-bar <?php echo getProgressBarClass($row['remark'], $row['status']); ?>" role="progressbar"
                                    style="width: <?php echo getProgressBarWidth($row['remark'], $row['status']); ?>" aria-valuenow="<?php echo (int) $row['remark']; ?>"
                                    aria-valuemin="0" aria-valuemax="100">
                                    <span><?php echo $row['remark']; ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge rounded-pill <?php echo getStatusBadgeClass($row['status']); ?>"><?php echo $row['status']; ?></span>
                        </td>
                        <td><?php echo $row['time']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <!-- End Table with hoverable rows -->
    </div>
</div>
