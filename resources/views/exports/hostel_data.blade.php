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
            table-layout: fixed;
        }

        .table tr td,
        .table tr th {
            border: 1px solid #000;
            word-wrap: break-word;
            overflow-wrap: break-word;
            font-size: 9pt;
        }

        @page {
            size: A4 landscape;
            margin: 15pt 25pt 25pt 15pt;
        }

        .table td {
            word-break: break-all;
        }
    </style>
</head>

<body>
    <div id="prodiv" style="width: 100%; margin: 0 auto; padding-right:15px;">
        <div style="padding: 0 15px 3px; margin-bottom: 6px; border-bottom: 2px solid #000; text-align: center;">
            <img src="{{ asset('') }}/assets_admin/images/logo.png" style="width: 60px; height: auto;">
            <h1 style="font-size: 18pt; margin: 4px 0 0; padding: 0; color: #000;">Sports Directorate,
                Uttar Pradesh</h1>
            <h2 style="font-size: 10pt; margin: 2px 0 4px; padding: 0; color: #000;">Khel Bhawan,
                Hazratganj, Lucknow, Uttar Pradesh 226001</h2>
            <h5 style="font-size: 14pt; margin: 0; padding: 0; color: #000;">Khel Sathi Portal</h5>
        </div>
        <h6 style="text-align: center; margin:0px 0px 0px 0px; font-size:14pt; padding: 0px; color:#000; ">
            {{ $data['module_name'] }}
        </h6>
        <h6
            style="text-align: center; margin:0px 0px 15px 0px; font-size:12pt; padding: 0px; color:#000; text-decoration:underline;">
            {{ $pdfTitle }}
        </h6>
        <div style="width: 100%; font-size: 9pt; margin-bottom: 6px; overflow: hidden;">
            <span style="float: left;"><b>Report Period : 01-04-2023 To {{ dmy(now()) }}</b></span>
            <span style="float: right; text-align: right;">
                <b>Report Code :</b> RS001 &nbsp;&nbsp;
                <b>Report Generated On :</b> {{ now()->format('d-m-Y h:i A') }}
            </span>
            <div style="clear: both;"></div>
        </div>

        <table class="table" border="1" cellspacing="0" cellpadding="5" style="width: 100%;">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Application No.</th>
                    <th style="max-width:10%">Applicant’s Name</th>
                    <th style="max-width:12%;">Email ID</th>
                    <th>Mobile No.</th>
                    <th>Gender</th>
                    <th>District</th>
                    <th>Sports</th>
                    @if (isset($checkk) && $checkk === 1)
                        <th>Sub Sport</th>
                        <th>Application Status</th>
                        <th>Payment Status</th>
                    @else
                        <th>Total Marks</th>
                        <th class="text-center">Approved</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @php
                    $rowCounts = 0;
                @endphp
                @if (count($hostelList) > 0)
                    @foreach ($hostelList as $key => $item)
                        @php
                            $rowCounts++;
                            $pageBreak = 'na';
                            if ($rowCounts > $data['report_count']) {
                                $rowCounts = 1;
                                $pageBreak = '';
                            }
                        @endphp

                        <tr style="@if ($rowCounts == $data['report_count']) page-break-after: always @endif">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->application_no }}</td>
                            <td>{{ ucwords(strtolower($item->name)) }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->mobile }}</td>
                            <td>
                                @if ($item->gender == 1)
                                    Male
                                @else
                                    Female
                                @endif
                            </td>
                            <td>{{ districtName($item->district_id) }} </td>
                            <td> {{ sport_name_hostel($item->sports) }} </td>
                            @if (isset($checkk) && $checkk === 1)
                                <td>
                                    @if ($item->sub_sport_type)
                                        {{ sub_sport_name($item->sub_sport_type) }}
                                    @else
                                        NA
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == 3)
                                        Pending
                                    @elseif ($item->status == 1)
                                        Provisionally Accepted
                                    @else
                                        Rejected
                                    @endif
                                </td>
                                <td>
                                    @if ($item->payment_status == 1 || $item->payment_status == 2 || $item->payment_status == 3)
                                        @if ($item->payment_status == 1)
                                            Pending
                                        @elseif ($item->payment_status == 2)
                                            Success
                                            <br>
                                            {{ dmy($item->payment_allotment_fee_date != '' ? $item->payment_allotment_fee_date : $item->payment_date) }}
                                            <br>
                                            10 INR
                                        @elseif ($item->payment_status == 3)
                                            Fail
                                        @endif
                                    @endif
                                </td>
                            @else
                                <td>{{ isset($item->total_mark) ? number_format($item->total_mark, 2) : '-' }}</td>
                                <td>
                                    @if ($item->final_list_approved == 1)
                                        <div class="btn btn-sm btn-primary">Approved </div>
                                    @else
                                        <input class="form-check-input approvedcheck" type="checkbox"
                                            value="{{ $item->id }}" id="flexCheckDefault">
                                        <label class="form-label" for="flexCheckDefault">
                                            Approve
                                        </label>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="10">
                            <h4 style="text-align: center;"> No records Found!.</h4>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
