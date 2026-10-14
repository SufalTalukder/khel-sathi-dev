<!doctype html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Tiro+Devanagari+Sanskrit:ital@0;1&display=swap');

        .hi {
            font-family: 'Tiro Devanagari Sanskrit', serif;
        }

        .en {
            font-family: 'Open Sans', sans-serif;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            font-size: 10px;
            margin: 0px;
            padding: 0px;
        }

        .tbl-brd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tbl-brd-table th,
        .tbl-brd-table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        .tbl-brd-table th {
            font-weight: bold;
            background: #f3f3f3;
        }
    </style>
    <div class="container-fluid pagecontentbody">
        <div class="tab-content">
            <div id="prodiv" style="margin: 0 auto;width: 100%;font-size: 14px;">
                <div class="receipt-main" id="printableArea">

                    <br>
                    <table width="100%" border="1" class="receipt-header" style="border: 0;">
                    <tbody>
                        <tr>
                            <td class="receipt-left" style="width: 80px; border: 0;">
                                <img class="img-responsive" alt="iamgurdeeposahan" src="{{ asset('') }}/assets_admin/images/logo.png" style="width: 60px;"/>

                            </td>
                            <td style="border: 0; text-align: center; padding-right: 90px;">
                                <h2 style="margin: 0 0 5px;">Khel Sathi Portal</h2>
                                <p style="margin: 0;"> Government of Uttar Pradesh</p>

                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                    <table width="100%" border="0" style="font-size:12px; margin-bottom:15px;">
                        <tbody>

                            <tr>
                                <td colspan="4">
                                    <div class="receipt-left">
                                        <h4 align="center" style="font-size:16px; margin-bottom:15px;">
                                            Payment Receipt of Sports Training
                                        </h4>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td width="15%"><b>Name</b></td>
                                <td width="35%"><b>: &nbsp;</b>{{ $userDetails->name }}</td>

                                <td><b>Challan No.</b></td>
                                <td><b>: &nbsp;</b>{{ $userDetails->Depchallan }}</td>

                            </tr>

                            <tr>
                                <td width="15%"><b>Email</b></td>
                                <td width="35%"><b>: &nbsp;</b>{{ $userDetails->email }}</td>

                                <td><b>Mobile No.</b></td>
                                <td><b>: &nbsp;</b>+91 {{ $userDetails->mobile }}</td>
                            </tr>

                            <tr>
                                <td><b>Application No.</b></td>
                                <td>
                                    <b>: &nbsp;</b>
                                    {{ !empty($userDetails->application_no) ? $userDetails->application_no : 'N/A' }}
                                </td>

                                <td><b>Sport</b></td>
                                <td>
                                    <b>: &nbsp;</b>
                                    {{ sport_name($userDetails->sport) }}
                                </td>
                            </tr>

                            <tr>
                                <td><b>Aadhaar No.</b></td>
                                <td>
                                    <b>: &nbsp;</b>
                                    {{ !empty($userDetails->aadhar) ? $userDetails->aadhar : 'N/A' }}
                                </td>

                                <td><b>Bank Name</b></td>
                                <td>
                                    <b>: &nbsp;</b>
                                    State Bank Of India
                                </td>
                            </tr>

                            <tr>
                                <td><b>Payment Date</b></td>
                                <td>
                                    <b>: &nbsp;</b>
                                    {{ date('d-m-Y', strtotime($userDetails->payment_date_coaching_fee)) }}
                                </td>

                                <td><b>Bank Reference No:</b></td>
                                <td>
                                    <b>: &nbsp;</b>
                                    {{ !empty($userDetails->ref_no) ? $userDetails->ref_no : 'N/A' }}
                                </td>
                            </tr>

                        </tbody>
                    </table>


                    <table width="100%" class="tbl-brd tbl-brd-table">
                        <thead>
                            <tr>
                                <th width="10%" align="center">Sr. No.</th>
                                <th align="left">Payment Particulars</th>
                                <th width="20%" align="right">Amount (Rs.)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if($userDetails->paid_amount>10)
                                <tr>
                                    <td align="center">1</td>
                                    <td>
                                        Coaching Camp Fee
                                        @if (isset($userDetails->fee_type))
                                            ({{ $userDetails->fee_type }})
                                        @endif
                                    </td>
                                    <td align="right">
                                        {{ $userDetails->payment_amount_coaching }}
                                    </td>
                                </tr>
                            @endif
                            @if($userDetails->paid_amount==$userDetails->payment_amount_coaching+10)
                                <tr>
                                    <td align="center">2</td>
                                    <td>
                                        Application Fee (Yearly)
                                    </td>
                                    <td align="right">
                                        10
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td colspan="2" align="right">
                                    <strong>Total Amount</strong>
                                </td>
                                <td align="right">
                                    <strong>
                                        Rs. {{ $userDetails->paid_amount }}
                                    </strong>
                                </td>
                            </tr>

                        </tbody>
                    </table>


                    <div style="margin-top:20px;">
                        <strong>
                            This is a computer-generated receipt and does not require a signature.
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
