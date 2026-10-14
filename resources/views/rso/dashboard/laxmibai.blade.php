@extends('layouts/admin_layout')

@section('content')
    <div class="pageheader" id="menu-margin">

        <h4 class="mb-0">

            List of Rani Laxmi Bai Applicant

            <a class="btn btn-sm btn-success float-end" onclick="ExportToExcel('xlsx')">

                <i class="fa fa-file-excel"></i>
                Export to Excel

            </a>

            <a class="btn btn-sm btn-outline-danger float-end me-2" target="_blank"
                href="{{ url('admin/laxmibai/exportPdf') }}">

                <i class="fa fa-file-pdf"></i>
                Export to PDF

            </a>

        </h4>

    </div>

    <div class="card">

        <div class="card-body">

            <form method="GET" action="{{ url('/admin/laxmibai') }}">

                <div class="row">

                    <div class="col-md-3">

                        <label for="sport_id">
                            Sport
                        </label>

                        <select class="form-select" id="sport_id" name="sport_id">

                            <option value="all">
                                --All--
                            </option>

                            @foreach ($sports as $sport)
                                <option value="{{ $sport->id }}"
                                    {{ request()->sport_id == $sport->id ? 'selected' : '' }}>

                                    {{ $sport->name }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label for="status_filter">
                            Application Status
                        </label>

                        <select class="form-select" id="status_filter" name="status_filter">

                            <option value="all"
                                {{ request()->status_filter == 'all' || request()->status_filter == '' ? 'selected' : '' }}>

                                --All--

                            </option>

                            <option value="0" {{ request()->status_filter === '0' ? 'selected' : '' }}>

                                Pending

                            </option>

                            <option value="1" {{ request()->status_filter == '1' ? 'selected' : '' }}>

                                Accepted

                            </option>

                            <option value="2" {{ request()->status_filter == '2' ? 'selected' : '' }}>

                                Rejected

                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label for="city_filter">
                            District
                        </label>

                        <select class="form-select" id="city_filter" name="city_filter">

                            <option value="all">
                                --All--
                            </option>

                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"
                                    {{ request()->city_filter == $city->id ? 'selected' : '' }}>

                                    {{ $city->city }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label for="from_date">
                            From Date
                        </label>

                        <input readonly type="text" name="from_date" id="from_date" class="form-control dateTime"
                            placeholder="From Date" value="{{ request()->from_date }}">

                    </div>

                    <div class="col-md-2">

                        <label for="to_date">
                            To Date
                        </label>

                        <input readonly type="text" name="to_date" id="to_date" class="form-control dateTime"
                            placeholder="To Date" value="{{ request()->to_date }}">

                    </div>

                    <div class="col-md-1">

                        <label>&nbsp;</label>

                        <button type="button" class="btn btn-info w-100" onclick="resetButton()">

                            Reset

                        </button>

                    </div>

                </div>

                <div class="row mt-3">

                    <div class="col-md-12 text-end">

                        <label>

                            Search:

                            <input type="search" placeholder="Search..." value="{{ request()->search }}" name="search"
                                id="table_search" class="table-form-control">

                        </label>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped" id="dataTable">

                    <thead class="table-dark">

                        <tr>

                            <th>S.No.</th>
                            <th>Application No.</th>
                            <th>Applicant Name</th>
                            <th>Email ID</th>
                            <th>District</th>
                            <th>Sport Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>View</th>
                            <th>Trail</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse ($details as $key => $item)
                            <tr>

                                <td>
                                    {{ $key + 1 }}
                                </td>

                                <td>
                                    {{ $item->application_no ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->fullname ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->email ?? '-' }}
                                </td>

                                <td>
                                    {{ districtName($item->permanent_district) ?? '-' }}
                                </td>

                                <td>
                                    {{ $item->sportName ?? '-' }}
                                </td>

                                <td>
                                    {{ date('d-m-Y', strtotime($item->created_at)) }}
                                </td>

                                <td>

                                    @if ($item->form_status == 1)
                                        <span class="badge bg-success">
                                            Accepted
                                        </span>
                                    @elseif ($item->form_status == 2)
                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>
                                    @endif

                                </td>

                                <td>
                                    {{ $item->amount_release ?? '-' }}
                                </td>

                                <td class="text-center">

                                    <a class="btn btn-sm btn-dark"
                                        href="{{ route('laxmibai_view', $item->application_no) }}">

                                        <i class="fa fa-eye"></i>

                                    </a>

                                </td>

                                <td class="text-center">

                                    <a class="btn btn-sm btn-danger"
                                        href="{{ url('/admin/trail/' . $item->user_id . '/2') }}">

                                        <i class="fa fa-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="11" class="text-center">

                                    No Records Found

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection

<!-- Release Button -->
<div class="modal fade" id="releasebtn1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Amount Release
                </h5>

            </div>

            <form action="{{ route('award_released_amount') }}" method="post">

                @csrf

                <div class="modal-body">

                    <div class="form-group">

                        <label for="amount">
                            Release Amount in Rupees
                        </label>

                        <input type="hidden" class="financial_released" name="id" value="" />

                        <input type="hidden" name="form_type" value="2">

                        <input type="number" class="form-control" min="0" required name="amount_release"
                            id="release">

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-info">

                        Yes

                    </button>

                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">

                        No

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Forward Button -->
<div class="modal fade" id="forwardedbtn1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    Forward to Directorate
                </h5>

            </div>

            <form action="{{ route('award_forward_directorate') }}" method="post" enctype="multipart/form-data">

                @csrf

                <div class="modal-body">

                    <input type="hidden" class="financial_forward" name="id" value="" />

                    <input type="hidden" name="form_type" value="2">

                    <input type="hidden" class="application_no" name="application_no" value="" />

                    <div class="form-group">

                        <label class="placeholder">
                            Remark
                            <span class="text-danger">*</span>
                        </label>

                        <textarea class="form-control" required name="remark" id="is_mark_query" cols="95" rows="2"></textarea>

                    </div>

                    <div class="form-group mt-2">

                        <label>

                            Upload Relevant application verification document

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <input type="file" required name="verification_document" class="form-control"
                                onchange="getfileext(this.value,10)" id="File10">

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <input type="submit" class="btn btn-info" value="Yes">

                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">

                        No

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@push('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#sport_id').change(function() {
                $(this).closest('form').submit();
            });

            $('#status_filter').change(function() {
                $(this).closest('form').submit();
            });

            $('#city_filter').change(function() {
                $(this).closest('form').submit();
            });

            $('#from_date').change(function() {
                $(this).closest('form').submit();
            });

            $('#to_date').change(function() {
                $(this).closest('form').submit();
            });

            $('#table_search').keyup(function() {

                clearTimeout($.data(this, 'timer'));

                let wait = setTimeout(function() {

                    $('#table_search').closest('form').submit();

                }, 500);

                $(this).data('timer', wait);

            });

        });

        function resetButton() {
            window.location.href = "{{ url('/admin/laxmibai') }}";
        }

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('dataTable');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('List of Laxman Award Applicants.' + (type || 'xlsx')));
        }
    </script>
@endpush
