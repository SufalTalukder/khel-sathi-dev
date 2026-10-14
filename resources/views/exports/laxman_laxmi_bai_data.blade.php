<!DOCTYPE html>
<html>

<head>

    <title>Khel Sathi</title>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h5,
        h6 {
            font-weight: bold;
            line-height: 1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table tr td,
        .table tr th {
            border: 1px solid #000;
            padding: 5px;
            font-size: 9pt;
        }

        th {
            background: #f2f2f2;
        }

        @page {
            size: A4 landscape;
            margin: 15pt 15pt 25pt 15pt;
        }
    </style>

</head>

<body>

    <div id="prodiv" style="width: 100%; margin: 0 auto;">

        <div
            style="padding: 0 15px 3px;
            margin-bottom: 6px;
            border-bottom: 2px solid #000;
            text-align: center;">

            <img src="{{ public_path('assets_admin/images/logo.png') }}" style="width: 60px; height: auto;">

            <h1
                style="font-size: 18pt;
                margin: 4px 0 0;
                padding: 0;
                color: #000;">

                Sports Directorate,
                Uttar Pradesh

            </h1>

            <h2
                style="font-size: 10pt;
                margin: 2px 0 4px;
                padding: 0;
                color: #000;">

                Khel Bhawan,
                Hazratganj,
                Lucknow,
                Uttar Pradesh 226001

            </h2>

            <h5
                style="font-size: 14pt;
                margin: 0;
                padding: 0;
                color: #000;">

                Khel Sathi Portal

            </h5>

        </div>

        <h6
            style="text-align: center;
            margin:0;
            font-size:14pt;
            padding:0;
            color:#000;">

            {{ $data['module_name'] }}

        </h6>

        <h6
            style="text-align: center;
            margin:0 0 15px 0;
            font-size:12pt;
            padding:0;
            color:#000;
            text-decoration:underline;">

            {{ $pdfTitle }}

        </h6>

        <div
            style="width: 100%;
            font-size: 9pt;
            margin-bottom: 6px;
            overflow: hidden;">

            <span style="float: left;">

                <b>
                    Report Period :
                    01-04-2023 To {{ date('d-m-Y') }}
                </b>

            </span>

            <span style="float: right; text-align: right;">

                <b>Report Code :</b>
                {{ $data['report_code'] }}

                &nbsp;&nbsp;

                <b>Report Generated On :</b>
                {{ now()->format('d-m-Y h:i A') }}

            </span>

            <div style="clear: both;"></div>

        </div>

        <table class="table">

            <thead>

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

                </tr>

            </thead>

            <tbody>

                @php
                    $rowCounts = 0;
                @endphp

                @if (count($laxmanList) > 0)

                    @foreach ($laxmanList as $key => $item)
                        @php

                            $rowCounts++;

                        @endphp

                        <tr @if ($rowCounts == $data['report_count']) style="page-break-after: always;" @endif>

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
                                    Accepted
                                @elseif ($item->form_status == 2)
                                    Rejected
                                @else
                                    Pending
                                @endif

                            </td>

                            <td>
                                {{ $item->amount_release ?? '-' }}
                            </td>

                        </tr>

                        @php

                            if ($rowCounts == $data['report_count']) {
                                $rowCounts = 0;
                            }

                        @endphp
                    @endforeach
                @else
                    <tr>

                        <td colspan="9" style="text-align:center;">

                            No Records Found!

                        </td>

                    </tr>

                @endif

            </tbody>

        </table>

    </div>

</body>

</html>
