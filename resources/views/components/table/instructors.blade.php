<div class="card ">
  <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
          <h5 class="card-title">Instructors</h5>
          <button type="button" class="btn btn-primary">Export</button>
      </div>
      <!-- Table with hoverable rows -->
      <div class="table-responsive">
          <table class="table table-hover">
              <thead>
                  <tr>
                      <th scope="col">#</th>
                      <th scope="col">Name</th>
                      <th scope="col">Username</th>
                      <th scope="col">Email</th>
                      <th scope="col">Department</th>
                      <th scope="col">Phone</th>
                      <th scope="col">Birthdate</th>
                      <th scope="col" class="text-center">Action</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                      <th scope="row">1</th>
                      <td>Brandon Jacob</td>
                      <td>f123</td>
                      <td>f123@buksu.edu.ph</td>
                      <td>COT</td>
                      <td>09123456789</td>
                      <td>02-05-2003</td>
                      <td>
                          <div class="d-flex justify-content-center">
                              <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                  data-bs-target="#updateModal">
                                  Edit
                              </button>
                              <div class="mx-1"></div>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                  data-bs-target="#deleteModal">
                                  Delete
                              </button>
                              {{-- <div class="mx-1"></div>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                  data-bs-target="#archiveModal">
                                  Archive
                              </button>
                              <div class="mx-1"></div>
                              <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                  data-bs-target="#restoreModal">
                                  Restore
                              </button> --}}
                          </div>
                      </td>
                  </tr>
                  <tr>
                    <th scope="row">2</th>
                    <td>Klevie Jun Caseres</td>
                    <td>f123</td>
                    <td>f111@buksu.edu.ph</td>
                    <td>COT</td>
                    <td>09123456789</td>
                    <td>02-05-2003</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#updateModal">
                                Edit
                            </button>
                            <div class="mx-1"></div>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal">
                                Delete
                            </button>
                            {{-- <div class="mx-1"></div>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#archiveModal">
                                Archive
                            </button>
                            <div class="mx-1"></div>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#restoreModal">
                                Restore
                            </button> --}}
                        </div>
                    </td>
                </tr>
              </tbody>
          </table>
      </div>
      <!-- End Table with hoverable rows -->
  </div>
</div>
