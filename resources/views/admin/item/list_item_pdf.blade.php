<!DOCTYPE html>
<html>

<head>
    <title>Khel Sathi</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .tblbrdr {
            width: 100%;
            border-collapse: collapse;
        }

        .tblbrdr tr td {
            border: 1px solid #000;
            font-size: 14px;
        }

        .subheading {
            color: #000;
            margin: 10px 0px 10px 0px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
            font-size: 16px;
            font-weight: 600;
        }

        @page {
            size: A4 landscape;
            margin: 15pt 15pt 15pt 15pt;
        }

        /* ===== FONT: NotoSansDevanagari for Hindi support ===== */
        * {
            font-family: 'NotoSansDevanagari', sans-serif !important;
        }

        body {
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        h1,
        h2,
        h5,
        h6 {
            font-weight: bolder;
            line-height: 0.85;
            font-family: 'NotoSansDevanagari', sans-serif;
        }

        table tr td {
            font-family: 'NotoSansDevanagari', sans-serif;
            font-weight: normal;
            font-size: 10pt;
        }

        table tr th {
            font-family: 'NotoSansDevanagari', sans-serif;
            font-weight: bold;
        }

        .table tr td,
        .table tr th {
            border: 1px solid #000;
            padding: 5px;
            font-family: 'NotoSansDevanagari', sans-serif;
        }
    </style>
</head>

<body>
    <div id="prodiv" style="width: 100%; margin: 0 auto;">
        <div
            style="padding: 0 15px 3px; margin-bottom: 10px; margin-top:10px; border-bottom: 2px solid #000; position: relative; line-height: 1;">
            <img src="{{ asset('') }}/assets_admin/images/logo.png"
                style="width: 75px; height: auto; position: absolute; top: 0px; left: 20px;">
            <h1 style="text-align: center; font-size: 20pt; margin: 0px 0px 0px 0px; padding: 0px 0 0; color: #000;">
                Sports Directorate, Uttar Pradesh
            </h1>
            <h2 style="text-align: center; margin:3px 0px 10px 0px; font-size:11pt; padding: 0px; color:#000;">
                Khel Bhawan, Hazratganj, Lucknow, Uttar Pradesh 226001
            </h2>
            <h5 style="text-align: center; margin:0px 0px 0px 0px; font-size:16pt; padding: 0px; color:#000;">
                Khel Sathi Portal
            </h5>
        </div>
        <h6 style="text-align: center; margin:0px 0px 0px 0px; font-size:14pt; padding: 0px; color:#000; ">
            Inventory Management for Sports Infrastructure, Instruments, Gadgets, Tools etc
        </h6>
        <h6
            style="text-align: center; margin:0px 0px 15px 0px; font-size:12pt; padding: 0px; color:#000; text-decoration:underline;">
            List of Inventory Items
        </h6>
        <div style="width: 100%; font-size: 9pt; margin-bottom: 6px; overflow: hidden;">
            <span style="float: left;"><b>Report Period : 01-04-2023 To {{ dmy(now()) }}</b></span>
            <span style="float: right; text-align: right;">
                <b>Report Code :</b> IN002 &nbsp;&nbsp;
                <b>Report Generated On :</b> {{ now()->format('d-m-Y h:i A') }}
            </span>
            <div style="clear: both;"></div>
        </div>

        <table class="table" border="1" cellspacing="0" cellpadding="5" style="width: 100%;">
            <thead>
                <tr>
                    <th width="6%">S.No.</th>
                    <th width="20%">Category</th>
                    <th width="20%">Sub Category</th>
                    <th width="20%">Name of Item</th>
                    <th width="20%">Code of Item</th>
                    <th width="20%">Stock Quantity</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $rowCounts = 0;
                    $row_count = 15;
                @endphp
                @if (count($all_item) > 0)
                    @foreach ($all_item as $key => $item)
                        @php
                            $rowCounts++;
                            $pageBreak = 'na';
                            if ($rowCounts > $row_count) {
                                $rowCounts = 1;
                                $pageBreak = '';
                            }
                        @endphp

                        <tr style="@if ($rowCounts == $row_count) page-break-after: always @endif">
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->subcategory }}</td>
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->item_code }}</td>
                            <td>{{ stock_item($item->id, 1) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11">
                            <h4 style="text-align: center;"> No records Found!.</h4>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</body>

</html>
