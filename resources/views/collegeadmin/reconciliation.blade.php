@extends('layouts/admin_layout')
@section('content')

<div class="pageheader" id="menu-margin">
    <h4 class="mb-0">
        Payment Reconciliation
        <a href="{{ url('collegeadmin/dashboard') }}" class="btn btn-outline-danger btn-sm backbtn float-end me-2">
            <span class="icons icon-arrow-left"></span>Back/पीछे
        </a>
    </h4>
</div>

{{-- Stat Cards --}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="intentbtn yellowcolor">
                    <span class="intenticon">
                        <i class="fa fa-clock-o" style="font-size:28px; color:#fff;"></i>
                    </span>
                    <span class="intentdata">
                        <b class="intentno" id="stat-initiated">{{ $stats['initiated'] }}</b>
                        Initiated (Pending)
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="intentbtn box8color">
                    <span class="intenticon">
                        <i class="fa fa-check-circle" style="font-size:28px; color:#fff;"></i>
                    </span>
                    <span class="intentdata">
                        <b class="intentno">{{ $stats['success'] }}</b>
                        Gateway SUCCESS
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="intentbtn pinkcolor">
                    <span class="intenticon">
                        <i class="fa fa-times-circle" style="font-size:28px; color:#fff;"></i>
                    </span>
                    <span class="intentdata">
                        <b class="intentno">{{ $stats['failed'] }}</b>
                        Gateway FAILED
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="intentbtn bluecolor">
                    <span class="intenticon">
                        <i class="fa fa-users" style="font-size:28px; color:#fff;"></i>
                    </span>
                    <span class="intentdata">
                        <b class="intentno">{{ $stats['paid'] }}</b>
                        Registrations Paid
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Run Now --}}
<div class="card mt-3">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3">
            <p class="mb-0">
                The scheduler runs automatically every <strong>15 minutes</strong> via Windows Task Scheduler.
                Use the button below to run it immediately against the SabPaisa gateway.
            </p>
            <button id="btn-run" class="btn btn-primary btn-sm flex-shrink-0" onclick="runNow()">
                <i class="fa fa-refresh me-1"></i><span id="btn-text">Run Reconciliation Now</span>
            </button>
        </div>
        <div id="run-output" class="mt-3 p-3 rounded"
             style="display:none; background:#1e1e1e; color:#d4d4d4; font-family:monospace; font-size:13px; white-space:pre-wrap; border:1px solid #444;">
        </div>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="card mt-3" id="recentTable">
    <div class="card-body">
        <div class="table-responsive table-bordred">
            <table id="reconTable" class="table table_new table-bordred table-hover bg-white">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>App No</th>
                        <th>Amount</th>
                        <th>Mode</th>
                        <th>Gateway Status</th>
                        <th>Reg. Paid</th>
                        <th>Trans Date</th>
                        <th>Message</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="Search name..."></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="Search mobile..."></th>
                        <th><input type="text" class="form-control form-control-sm" placeholder="Search app no..."></th>
                        <th></th>
                        <th>
                            <select class="form-control form-control-sm">
                                <option value="">All</option>
                                <option>UPI</option>
                                <option>BHIM UPI QR</option>
                                <option>Debit Card</option>
                                <option>Rupay Card</option>
                                <option>Net Banking</option>
                            </select>
                        </th>
                        <th>
                            <select class="form-control form-control-sm">
                                <option value="">All</option>
                                <option>SUCCESS</option>
                                <option>FAILED</option>
                                <option>INITIATED</option>
                            </select>
                        </th>
                        <th>
                            <select class="form-control form-control-sm">
                                <option value="">All</option>
                                <option>Yes</option>
                                <option>No</option>
                            </select>
                        </th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->fullname }}</td>
                        <td>{{ $row->mobile }}</td>
                        <td>{{ $row->application_no }}</td>
                        <td>₹{{ $row->paidAmount ?? $row->amount }}</td>
                        <td>{{ $row->paymentMode ?? '—' }}</td>
                        <td>
                            @php $s = strtoupper($row->status ?? '') @endphp
                            @if($s === 'SUCCESS')
                                <span style="background:#28a745;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;font-weight:600;">SUCCESS</span>
                            @elseif($s === 'FAILED')
                                <span style="background:#dc3545;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;font-weight:600;">FAILED</span>
                            @elseif($s === 'INITIATED')
                                <span style="background:#ffa508;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;font-weight:600;">INITIATED</span>
                            @elseif($s)
                                <span style="background:#6c757d;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;font-weight:600;">{{ $s }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($row->reg_payment_status == 1)
                                <span style="background:#28a745;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;font-weight:600;">Yes</span>
                            @else
                                <span style="background:#6c757d;color:#fff;padding:3px 8px;border-radius:4px;font-size:12px;font-weight:600;">No</span>
                            @endif
                        </td>
                        <td>{{ $row->transDate ?? '—' }}</td>
                        <td style="max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"
                            title="{{ $row->SabPaisaMessage }}">
                            {{ $row->SabPaisaMessage ?? '—' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
$(document).ready(function () {
    var table = $('#reconTable').DataTable({
        orderCellsTop: true,
        fixedHeader: true,
        initComplete: function () {
            var api = this.api();
            $('#reconTable thead tr:eq(1) th').each(function (i) {
                var cell = this;
                $('input, select', cell).on('keyup change clear', function () {
                    if (api.column(i).search() !== this.value) {
                        api.column(i).search(this.value).draw();
                    }
                });
            });
        }
    });
});

function runNow() {
    var btn   = document.getElementById('btn-run');
    var label = document.getElementById('btn-text');
    var out   = document.getElementById('run-output');

    btn.disabled = true;
    label.textContent = 'Running…';
    out.style.display = 'block';
    out.textContent   = 'Connecting to SabPaisa gateway…\n';

    $.ajax({
        url   : '{{ route("payment.reconciliation.run") }}',
        method: 'POST',
        data  : { _token: '{{ csrf_token() }}', limit: 50 },
        success: function(res) {
            out.innerHTML = res.output || 'Done.';
            document.getElementById('stat-initiated').textContent = '…';
            setTimeout(function() { location.reload(); }, 2000);
        },
        error: function(xhr) {
            out.textContent = 'Error: ' + xhr.statusText;
        },
        complete: function() {
            btn.disabled = false;
            label.textContent = 'Run Reconciliation Now';
        }
    });
}
</script>
@endpush
