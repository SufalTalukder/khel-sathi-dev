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

        @page {
            size: A4 landscape;
            margin: 15pt 15pt 25pt 15pt;
        }
    </style>
</head>

<body>
    <div id="prodiv" style="width: 100%; margin: 0 auto;">

        {{-- Header as plain DIVs — NOT inside <table><thead> --}}
        <div style="padding: 0 15px 3px; margin-bottom: 6px; border-bottom: 2px solid #000; text-align: center;">
            <img src="{{ asset('') }}/assets_admin/images/logo.png" style="width: 60px; height: auto;">
            <h1 style="font-size: 18pt; margin: 4px 0 0; padding: 0; color: #000;">Sports Directorate, Uttar Pradesh</h1>
            <h2 style="font-size: 10pt; margin: 2px 0 4px; padding: 0; color: #000;">Khel Bhawan, Hazratganj, Lucknow,
                Uttar Pradesh 226001</h2>
            <h5 style="font-size: 14pt; margin: 0; padding: 0; color: #000;">Khel Sathi Portal</h5>
        </div>

        <h6 style="text-align: center; margin: 4px 0 0; font-size: 12pt; padding: 0; color: #000;">
            Registration of Pvt. Coaching Academies / Associations ,Gyms, Swimming Pools
        </h6>
        <h6
            style="text-align: center; margin: 2px 0 4px; font-size: 11pt; padding: 0; color: #000; text-decoration: underline;">
            {{$namee}}
        </h6>

        <div style="width: 100%; font-size: 9pt; margin-bottom: 6px; overflow: hidden;">
            <span style="float: left;"><b>Report Period : 01-04-2023 To {{ dmy(now()) }}</b></span>
            <span style="float: right; text-align: right;">
                <b>Report Code :</b> RS001 &nbsp;&nbsp;
                <b>Report Generated On :</b> {{ now()->format('d-m-Y h:i A') }}
            </span>
            <div style="clear: both;"></div>
        </div>

        {{-- Data table — standalone, no wrapping outer table --}}
        <table class="table" border="1" cellspacing="0" cellpadding="5" style="width: 100%;">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Application No.</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Institution Name</th>
                    <th>Email ID</th>
                    <th>Mobile No.</th>
                    <th>Sports Name</th>
                    <th style="text-align:center;">Status of Application</th>
                </tr>
            </thead>
            <tbody>
                @if (count($application) > 0)
                    @foreach ($application as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->application_no }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->designation }}</td>
                            <td>{{ $item->institute_name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->mobile }}</td>
                            <td>{{ sport_name($item->sport_id) }}</td>
                            <td style="text-align:center;">
                                @if ($item->final_submit == 1 && $item->query_status == 2)
                                    Re-Submitted
                                @elseif($item->final_submit == 1 && $item->query_status == 1)
                                    Query Marked
                                @elseif ($item->status == 1 && $item->final_submit == 1)
                                    Accepted
                                @elseif ($item->status == 2 && $item->final_submit == 1)
                                    Rejected
                                @else
                                    Pending
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" style="text-align: center;"><b>No records Found!</b></td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
</body>

</html>
