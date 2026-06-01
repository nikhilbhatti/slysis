<?= $this->extend('backend/layout/pages-layout') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
/* Responsive Improvements */
@media (max-width: 768px) {

    .card-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .card-header h4 {
        font-size: 18px;
    }

    .card-header .btn {
        width: 100%;
    }

    .table td {
        font-size: 13px;
    }

    .action-btn-group {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 5px;
    }

    .action-btn-group .btn {
        padding: 4px 8px;
        font-size: 12px;
    }

    .status-form {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: center;
    }

    .status-form select,
    .status-form button {
        width: 100%;
    }

    .modal-dialog {
        margin: 10px;
    }
}
</style>

<div class="container-fluid mt-4">

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Centers Management</h4>
            <a href="<?= base_url('superadmin/add-center') ?>" class="btn btn-light btn-sm">
                Add New Center
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table id="centersTableModified" class="table table-bordered table-hover nowrap" width="100%">
                    <thead class="thead-light">
                        <tr>
                            <th>Center Name</th>
                            <th>Center Code</th>
                            <th>Email & Phone</th>
                            <th>Status</th>
                            <th class="no-export text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($centers as $center): ?>
                        <tr>
                            <td><?= esc($center['center_name']) ?></td>
                            <td><?= esc($center['center_code']) ?></td>
                            <td>
                                <?= esc($center['email']) ?><br>
                                <?= esc($center['phone']) ?>
                            </td>
                            <td>
                                <form action="<?= base_url('superadmin/update-center-status/'.$center['id']) ?>" 
                                      method="POST" 
                                      class="status-form">
                                    <?= csrf_field() ?>
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="1" <?= $center['status']==1?'selected':'' ?>>Active</option>
                                        <option value="0" <?= $center['status']==0?'selected':'' ?>>Inactive</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Update
                                    </button>
                                </form>
                            </td>

                            <td class="text-center">
                                <div class="action-btn-group">

                                    <button type="button"
                                            class="btn btn-sm btn-info"
                                            data-toggle="modal"
                                            data-target="#permModal<?= $center['id'] ?>">
                                        <i class="fa fa-lock"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-dark"
                                            data-toggle="modal"
                                            data-target="#msgModal<?= $center['id'] ?>">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>

                                    <a href="<?= base_url('superadmin/edit-center/'.$center['id']) ?>"
                                       class="btn btn-sm btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    <a href="<?= base_url('superadmin/delete-center/'.$center['id']) ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Delete this center?')">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </div>
                            </td>
                        </tr>

                        <!-- Permission Modal -->
                        <div class="modal fade" id="permModal<?= $center['id'] ?>">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title">Permissions - <?= esc($center['center_name']) ?></h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>

                                    <form action="<?= base_url('superadmin/save-permissions/'.$center['id']) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <div class="modal-body">

                                            <?php 
                                            $modules = ['Students','Courses','Enrollments','Profile','Settings'];
                                            $db = \Config\Database::connect();
                                            $existingPerms = $db->table('center_permissions')
                                                                ->where('center_id',$center['id'])
                                                                ->get()->getResultArray();
                                            $activeModules = array_column($existingPerms,'module_name');
                                            ?>

                                            <div class="row">
                                            <?php foreach($modules as $module): ?>
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input type="checkbox"
                                                               class="form-check-input"
                                                               name="permissions[]"
                                                               value="<?= $module ?>"
                                                               <?= in_array($module,$activeModules)?'checked':'' ?>>
                                                        <label class="form-check-label">
                                                            <?= $module ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary btn-block">
                                                Save Permissions
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Message Modal -->
                        <div class="modal fade" id="msgModal<?= $center['id'] ?>">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title">Send Message</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>

                                    <form action="<?= base_url('superadmin/send-center-msg') ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="center_id" value="<?= $center['id'] ?>">
                                        <div class="modal-body">
                                            <textarea name="message"
                                                      class="form-control"
                                                      rows="4"
                                                      required></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success btn-block">
                                                Send
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function(){

    if ($.fn.DataTable.isDataTable('#centersTableModified')) {
        $('#centersTableModified').DataTable().destroy();
    }

    $('#centersTableModified').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        order: [[0,'asc']],
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Excel',
                exportOptions: { columns: ':not(.no-export)' }
            },
            {
                extend: 'pdfHtml5',
                text: 'PDF',
                exportOptions: { columns: ':not(.no-export)' }
            }
        ]
    });

});
</script>

<?= $this->endSection() ?>
