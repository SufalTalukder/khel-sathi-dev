@extends('layouts/admin_layout')
@section('content')
    <style>
        .button.focus {
            outline: none !important;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="pageheader" id="menu-margin">
                <h4 class="mb-0">Auto Lock Master</h4>
            </div>

            {{-- Add Form --}}
            <div class="card mb-3">
                <div class="card-body">
                    <form id="addAutoLockForm" class="needs-validation" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="dropdown-check-list">
                                    <label for="auto_lock_type">Select Sub Module</label>
                                    <select class="js-select2 form-control" name="auto_lock_type" id="auto_lock_type"
                                        required>
                                        <option value="" disabled selected>-- Select --</option>
                                        <option value="information_honorable">
                                            Maananiya Sadasyon ke Patraon ki Soochna
                                        </option>
                                        <option value="nodal_officer">Nodal Officer</option>
                                        <option value="rajasv_praptiya">Rajasv Praptiya</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="dropdown-check-list">
                                    <label for="schedule_auto_lock_date">Schedule Date</label>
                                    <input type="date" class="form-control" name="schedule_auto_lock_date"
                                        id="schedule_auto_lock_date" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="dropdown-check-list">
                                    <label for="auto_lock_status">Status</label>
                                    <select class="form-control" name="auto_lock_status" id="auto_lock_status" required>
                                        <option value="" disabled selected>-- Select --</option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-grid">
                                <label class="form-label">&nbsp;</label>
                                <button class="btn btn-primary form-group" type="button" onclick="submitAddForm()">Submit /
                                    दर्ज करे</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- List Table --}}
            <div class="pageheader" id="menu-margin">
                <h4 class="mb-0">Auto Lock List
                    <a title="Export to Excel" class="btn btn-sm btn-success float-end" onclick="ExportToExcel('xlsx')">
                        <i class="fa fa-file-excel"></i> Export to Excel
                    </a>
                </h4>
            </div>
            <div class="card">
                <div class="card-body">
                    <div id="responsive">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-hover bg-white datatable">
                                <thead>
                                    <tr>
                                        <th width="6%">S.No.</th>
                                        <th width="20%">Auto Lock Type</th>
                                        <th width="25%">Schedule Date</th>
                                        <th width="12%">Status</th>
                                        <th width="10%" class="text-center">Edit</th>
                                        <th width="10%" class="text-center">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($auto_lock_master_data as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $item->auto_lock_type === 'information_honorable' ? 'Maananiya Sadasyon ke Patraon ki Soochna' : ($item->auto_lock_type === 'nodal_officer' ? 'Nodal Officer' : 'Rajasv Praptiya') }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($item->schedule_auto_lock_date)->format('d-m-Y') }}
                                            </td>
                                            <td>
                                                @if ($item->auto_lock_status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-warning pointer bt px-2" href="javascript:void(0)"
                                                    onclick="openEditModal(
                                                        {{ $item->auto_lock_id }},
                                                        '{{ $item->auto_lock_type }}',
                                                        '{{ $item->schedule_auto_lock_date }}',
                                                        '{{ $item->auto_lock_status }}'
                                                    )">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a class="btn btn-sm btn-danger pointer bt px-2" href="javascript:void(0)"
                                                    onclick="deleteAutoLock({{ $item->auto_lock_id }})">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No records found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Update Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Auto Lock</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close_modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="edit_auto_lock_id" name="auto_lock_id">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_auto_lock_type">Select Sub Module</label>
                            <select class="form-control" name="auto_lock_type" id="edit_auto_lock_type">
                                <option value="" disabled>-- Select --</option>
                                <option value="information_honorable">Maananiya Sadasyon ke Patraon ki Soochna</option>
                                <option value="nodal_officer">Nodal Officer</option>
                                <option value="rajasv_praptiya">Rajasv Praptiya</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_schedule_auto_lock_date">Schedule Date</label>
                            <input type="date" class="form-control" id="edit_schedule_auto_lock_date"
                                name="schedule_auto_lock_date">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_auto_lock_status">Status</label>
                            <select class="form-control" name="auto_lock_status" id="edit_auto_lock_status">
                                <option value="" disabled>-- Select --</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" onclick="submitUpdateForm()">Update / अपडेट
                    करे</button>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
    <script src="{{ asset('js/xlsx.full.min.js') }}"></script>

    <script type="text/javascript">
        function showToast(icon, text) {
            $.toast({
                heading: icon.charAt(0).toUpperCase() + icon.slice(1),
                text: text,
                icon: icon,
                loader: true,
                loaderBg: '#fff',
                showHideTransition: 'fade',
                hideAfter: 3000,
                allowToastClose: false,
                position: {
                    right: 2,
                    top: 2
                }
            });
        }

        // Add
        function submitAddForm() {
            var formData = {
                _token: $('input[name="_token"]').val(),
                auto_lock_type: $('#auto_lock_type').val(),
                schedule_auto_lock_date: $('#schedule_auto_lock_date').val(),
                auto_lock_status: $('#auto_lock_status').val(),
            };

            $.ajax({
                type: 'POST',
                url: '{{ route('auto_lock_add') }}',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    if (res.error) {
                        showToast('error', res.msg);
                    } else {
                        showToast('success', res.msg);
                        $('#addAutoLockForm')[0].reset();
                        $('.js-select2').val(null).trigger('change');
                        $('#dataTable').DataTable().destroy();
                        $("#responsive").load(" #responsive", function() {
                            tableRendor();
                        });
                    }
                },
                error: function() {
                    showToast('error', 'Something went wrong. Please try again.');
                }
            });
        }

        // Edit Modal
        function openEditModal(id, type, date, status) {
            $('#edit_auto_lock_id').val(id);
            $('#edit_auto_lock_type').val(type).trigger('change');
            $('#edit_schedule_auto_lock_date').val(date);
            $('#edit_auto_lock_status').val(status);
            $('#exampleModal').modal('show');
        }

        // Update
        function submitUpdateForm() {
            var formData = {
                _token: $('input[name="_token"]').val(),
                auto_lock_id: $('#edit_auto_lock_id').val(),
                auto_lock_type: $('#edit_auto_lock_type').val(),
                schedule_auto_lock_date: $('#edit_schedule_auto_lock_date').val(),
                auto_lock_status: $('#edit_auto_lock_status').val(),
            };

            $.ajax({
                type: 'POST',
                url: '{{ route('auto_lock_update') }}',
                data: formData,
                dataType: 'json',
                success: function(res) {
                    if (res.error) {
                        showToast('error', res.msg);
                    } else {
                        showToast('success', res.msg);
                        $('#exampleModal').modal('hide');
                        $('#edit_auto_lock_type').val(null).trigger('change');
                        $('#dataTable').DataTable().destroy();
                        $("#responsive").load(" #responsive", function() {
                            tableRendor();
                        });
                    }
                },
                error: function() {
                    showToast('error', 'Something went wrong. Please try again.');
                }
            });
        }

        // Delete
        function deleteAutoLock(id) {
            if (!confirm('Are you sure you want to delete?')) return;

            $.ajax({
                type: 'GET',
                url: ajaxUrl + '/admin/auto_lock/delete/' + id,
                dataType: 'json',
                success: function(res) {
                    if (res.error) {
                        showToast('error', res.msg);
                    } else {
                        showToast('success', res.msg);
                        $('#dataTable').DataTable().destroy();
                        $("#responsive").load(" #responsive", function() {
                            tableRendor();
                        });
                    }
                },
                error: function() {
                    showToast('error', 'Something went wrong. Please try again.');
                }
            });
        }

        // Export Excel
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('dataTable');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: 'sheet1'
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('Auto Lock Master.' + (type || 'xlsx')));
        }

        // Select2 init (add)
        $(".js-select2").select2({
            closeOnSelect: true,
            placeholder: "Select",
            allowClear: true,
        });

        // Select2 init (update)
        $("#edit_auto_lock_type").select2({
            closeOnSelect: true,
            placeholder: "Select",
            allowClear: true,
            dropdownParent: $("#exampleModal")
        });

        // Close Modal
        $("#close_modal").on('click', function() {
            $('#exampleModal').modal('hide');
        })
    </script>
@endpush
