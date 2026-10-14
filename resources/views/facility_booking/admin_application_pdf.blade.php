<!DOCTYPE html>
<html>

<head>
    <title>Khel Sathi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @font-face {
            font-family: 'Open Sans', sans-serif;
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        table tr td,
        table tr th {
            font-family: 'Open Sans', sans-serif;
            font-weight: normal;
            font-size: 10pt;
        }

        table tr th {
            font-weight: bold;
        }

        h1,
        h2,
        h5,
        h6 {
            font-weight: bolder;
            line-height: 0.85;
            font-family: 'Open Sans', sans-serif;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table tr td,
        .data-table tr th {
            border: 1px solid #000;
            padding: 5px;
            font-family: 'Open Sans', sans-serif;
            font-size: 10pt;
        }

        .report-header {
            width: 100%;
            margin-bottom: 10px;
        }

        .logo-title-block {
            padding: 0 15px 3px;
            margin-bottom: 10px;
            margin-top: 10px;
            border-bottom: 2px solid #000;
            position: relative;
            line-height: 1;
            overflow: hidden;
            /* clearfix for float */
        }

        .meta-row {
            width: 100%;
            margin-bottom: 10px;
        }

        @page {
            size: A4 landscape;
            margin: 10pt;
        }
    </style>
</head>

<body>
    <div id="prodiv" style="width: 100%; margin: 0 auto;">

        {{-- ===== REPORT HEADER (outside any table thead so it renders once) ===== --}}
        <div class="logo-title-block">
            <img src="{{ asset('') }}/assets_admin/images/logo.png" style="width:75px; float:left;">
            <h1 style="text-align: center; font-size: 20pt; margin: 0; padding: 0; color: #000;">
                Sports Directorate, Uttar Pradesh
            </h1>
            <h2 style="text-align: center; margin: 3px 0 10px 0; font-size: 11pt; padding: 0; color: #000;">
                Khel Bhawan, Hazratganj, Lucknow, Uttar Pradesh 226001
            </h2>
            <h5 style="text-align: center; margin: 0; font-size: 16pt; padding: 0; color: #000;">
                Khel Sathi Portal
            </h5>
        </div>

        <h6 style="text-align: center; margin: 0; font-size: 14pt; padding: 0; color: #000;">
            Booking of Facilities (Stadium, Guest Houses, Gyms and Swimming Pools)
        </h6>
        <h6
            style="text-align: center; margin: 0 0 15px 0; font-size: 12pt; padding: 0; color: #000; text-decoration: underline;">
            List of Applicants who applied for
            @if (last(request()->segments()) == 1)
                Swimming Pool (Mini)
            @elseif (last(request()->segments()) == 2)
                Guest Room
            @elseif (last(request()->segments()) == 3)
                Swimming Pool (Adult)
            @elseif (last(request()->segments()) == 4)
                Gymnasium
            @elseif (last(request()->segments()) == 5)
                Stadium
            @endif Booking
        </h6>

        <table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin-bottom: 8px;">
            <tr>
                <td style="font-size: 10pt; text-align: left;">
                    <b>Report Period : 01-04-2023 To {{ dmy(now()) }}</b>
                </td>
                <td style="text-align: right; font-size: 10pt;">
                    <b>Report Generated On : </b>{{ now()->format('d-m-Y h:i A') }}
                </td>
            </tr>
        </table>

        {{-- ===== DATA TABLE (simple table, no thead repeat tricks) ===== --}}
        <table class="data-table" border="1" cellspacing="0" cellpadding="5" style="width: 100%;">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Application No.</th>
                    <th>Service Type</th>
                    <th>Applicant Name</th>
                    <th>Email ID</th>
                    <th style="text-align:center;">Mobile No.</th>
                    <th style="text-align:center;">Status of Application</th>
                    <th style="text-align:center;">Payment Status</th>
                </tr>
            </thead>
            <tbody>
                @if (count($application) > 0)
                    @foreach ($application as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->application_no }}</td>
                            <td>
                                @if ($item->service == 1)
                                    Swimming Pool (Mini)
                                @elseif ($item->service == 2)
                                    Guest Room
                                @elseif ($item->service == 3)
                                    Swimming Pool (Adult)
                                @elseif ($item->service == 4)
                                    Gymnasium
                                @elseif ($item->service == 5)
                                    Stadium
                                @endif
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td style="text-align:center;">{{ $item->mobile }}</td>
                            <td style="text-align:center;">
                                @if ($item->status == 1 && $item->final_submit == 1 && $item->query_status != 1 && $item->query_status != 2)
                                    Accepted
                                @elseif ($item->status == 2 && $item->final_submit == 1)
                                    Rejected
                                @elseif ($item->final_submit == 1 && $item->query_status == 2)
                                    Re-Submitted
                                @elseif ($item->final_submit == 1 && $item->query_status == 1)
                                    Query Marked
                                @else
                                    Pending
                                @endif
                            </td>
                            <td style="text-align:center;">
                                @if ($item->status == 1 && $item->payment_status == 1)
                                    Success<br>
                                    {{ dmy($item->payment_date) }}<br>
                                    <strong>{{ $item->amount_to_be_paid }} INR.</strong>
                                @elseif ($item->status == 1 && $item->payment_status != 1)
                                    @if ($item->fee_exemption_status == 1)
                                        Fee Exemption
                                    @else
                                        Pending<br>
                                        <strong>{{ $item->amount_to_be_paid }} INR.</strong>
                                    @endif
                                @else
                                    NA
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" style="text-align:center;">
                            <h4>No records Found!</h4>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
</body>

</html>
