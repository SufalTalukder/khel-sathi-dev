@extends('layouts/admin_layout')
@section('content')
    <style>
        .nowraptd {
            white-space: nowrap;
        }

        .dn {
            display: none;
        }
    </style>
    <style>
        .hide-panel {
            background: #f7f7f7;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px dashed #ccc;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="pageheader" id="menu-margin">
                <h4 class="mb-0">Notices</h4>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('admin/notices/add-notice') }}" class="needs-validation" id="reload_two" novalidate method="post" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <fieldset>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Notice Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title" placeholder="Notice Title" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="placeholder">Notice Type <span
                                                class="text-danger">*</span></label>
                                        <select name="notice_type" id="notice_type" class="form-control form-select" required>
                                            <option value='' selected disabled>Select</option>
                                            <option value='1'>Public Notice</option>
                                            <option value='2'>Government Orders</option>
                                            <option value='3'>Office Documents</option>
                                            <option value='4'>Yearly Book</option>
                                            <option value='5'>Budget Book</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6" id="thumbnail_div" style="display:none;">
                                    <div >
                                        <label class="placeholder">Thumbnail Image<span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="thumbnail" class="form-control" accept="image/png, image/gif, image/jpeg">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="placeholder">File Date <span class="text-danger">*</span></label>
                                        <input type="date" name="file_date" id="file_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="placeholder">File <span class="text-danger">*</span></label>
                                        <input type="file" name="notice" id="notice" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label class="placeholder">Description <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="bhoechie-footer">
                                <div class="row justify-content-center">
                                    <div class="col-md-2 d-grid">
                                        <button id="reset" type="reset"
                                            class="btn btn-outline-danger rounded-pill">रीसेट</button>
                                    </div>
                                    <div class="col-md-2 d-grid">
                                        <button type="submit" class="btn  btn-outline-info rounded-pill">सुनिश्चित
                                            करे</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body" id="prodiv">
                    <table style="width: 100%;" class="dn">
                        <tr>
                            <td align="center" style="position: relative; border: 0; padding-bottom: 5px;">
                                <div style="border-bottom: 0px solid #000; padding-bottom: 2vw;">
                                    <div style="font-size: 18px; font-weight: bold;">
                                        Department of Sports
                                    </div>
                                    <div style="font-size: 14px; font-weight: bold;">
                                        Government of Uttar Pradesh
                                    </div>
                                    <div style="font-size: 18px; font-weight: bold;">
                                        {{-- Sport College online application form --}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered datatable" id="dataTable_player">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Notice</th>
                                <th>Notice Type</th>
                                <th>File Date</th>
                                <th>Upload Date</th>
                                <th class="text-center" style="width:10%;">Notice Status</th>
                                <th>Action</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>


                        @foreach($notices as $key=>$item)


                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->title}}</td>
                                <td>@if($item->notice_type==1)
                                        <p>Public Notice</p>
                                    @elseif($item->notice_type==2)
                                        <p>Government Orders</p>
                                    @elseif($item->notice_type==3)
                                        <p>Office Documents</p>
                                    @elseif($item->notice_type==4)
                                        <p>Yearly Book</p>
                                    @elseif($item->notice_type==5)
                                        <p>Budget Book</p>
                                    @endif
                                </td>
                                <td>{{$item->file_date}}</td>
                                <td>{{$item->created_at}}</td>
                                <td>@if($item->status==1) Active @else Disabled @endif</td>
                                <td>
                                    {{-- <a class="btn btn-sm btn-info" href="#"><i class="fa fa-pencil"></i>Edit </a> --}}
                                    <button id="change_status" class="btn btn-sm {{$item->status==1 ? 'btn-danger' : 'btn-success'}}" onclick="change_status({{$item->status==1?0:1}},{{$item->id}})" >@if($item->status==1) Disable @else Enable @endif</button>
                                </td>
                                <td><a href="{{asset('')}}/uploads/notices/{{$item->file_name}}" target="_blank"><i class="fa fa-eye"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
@push('custom-scripts')
<script>
    $(document).ready(function () {
        function toggleThumbnailField() {
            let noticeType = $('#notice_type').val();

            if (noticeType == 4 || noticeType == 5) {
                $('#thumbnail_div').show();
            } else {
                $('#thumbnail_div').hide();
            }
        }
        $('#notice_type').on('change', toggleThumbnailField);

        toggleThumbnailField();


    });
    // function change_status($status,$id){
    window.change_status = function(status, id) {
            const actionUrl = ajaxUrl + "/admin/notices/change-status/";
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: {
                        status: status,
                        id: id,
                    },
                    success: function (res) {
                        if (res.error === false) {
                            success(res.msg); // or custom success handler
                            window.location.reload();
                        } else {
                            error(res.msg); // or custom error handler
                        }
                    },
                    error: function () {
                        error("Something went wrong. Please try again.");
                    }
                });
        }

</script>
@endpush
