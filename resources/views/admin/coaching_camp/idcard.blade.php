@extends('layouts/admin_layout')
@section('content')
<div class="container-fluid pagecontentbody">
    <div class="pagebody removebg-color">
        <div class="row mt-5 d-flex">
            <div class="col-12">
                <div class="pageheader" id="menu-margin">
                    <h4 class="mb-0">
                        ID Card
                        <button type="button" data-print="modal" class="btn btn-sm  btn-primary ms-2 float-end rounded-pill" onclick="PrintDoc()">
                            <span class="icons icon-printer"></span> Print
                        </button>
                        <a href="{{ route('coaching_camp_list') }}" class="btn btn-danger btn-sm backbtn float-end rounded-pill">
                            <span class="icons icon-arrow-left"></span>Go Back
                        </a>
                    </h4>
                </div>
                <div class="bhoechie-tab-container">
                    <div class="row justify-content-center">
                        <div class="nano-content">
                            <div id="prodiv" class="container-fluid id-card">
                                <style>
                                    @page{
                                        size:67mm 98.5mm;
                                    }

                                    .id-card {
                                        width: 67mm;
                                        height: 98.5mm;
                                        border: 2px solid #000;
                                        box-sizing: border-box;
                                        padding: 3mm;
                                        overflow: hidden;
                                        background: #fff;
                                    }
                                    @media print {

                                        .id-card {
                                            border: 2px solid #000 !important;
                                            width: 67mm !important;
                                            height: 98.5mm !important;
                                            text-align:center;
                                        }
                                    }
                                </style>

                                <div class="text-center mb-1 justify-content-center">
                                    <img src="{{ asset('public/coaching_camp/images/logo.png') }}" width="35">
                                    <div style="font-size:10px;font-weight:bold;">
                                        Department of Sports, Uttar Pradesh
                                    </div>
                                    <div style="font-size:9px;">
                                       {{$idcard->stadium_name}}
                                    </div>
                                </div>

                                <div class="text-center mb-1">
                                    <img src="{{ asset('public/coaching_camp/profile_picture/'.$idcard->profile_picture) }}" style="
                                        height:22mm;
                                        object-fit:cover;
                                        border:1px solid #000;
                                    ">
                                </div>

                                <table style="width:100%;font-size:9px;">
                                    <tr>
                                        <td><b>Unique ID:</b></td>
                                        <td>{{ $idcard->application_no}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Name:</b></td>
                                        <td>{{ $idcard->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Father:</b></td>
                                        <td>{{ $idcard->father_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>DOB:</b></td>
                                        <td>{{ date('d-m-Y', strtotime($idcard->dob)) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Gender:</b></td>
                                        <td>{{ $idcard->gender==1 ? 'Male' : 'Female' }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Mobile:</b></td>
                                        <td>{{ $idcard->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Sport:</b></td>
                                        <td>@php
                                                $userType = $idcard->user_type==1? 'Trainee' : 'Amateur';
                                            @endphp

                                            {{ $idcard->sports_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Type:</b></td>
                                        <td>{{ $userType }}</td>
                                    </tr>
                                </table>

                                <div style="font-size:9px;margin-top:2mm;">
                                    <b>Address:</b><br>
                                    {{ $idcard->correspondence_address }}, {{ districtName($idcard->correspondence_district) }}, Uttar Pradesh, {{ $idcard->correspondence_pin }}
                                </div>

                                <div class="text-end">
                                    <img src="{{ asset('public/coaching_camp/signature/'.$idcard->signature) }}" style="height:10mm;">
                                </div>

                            </div>
                            {{-- <div id="prodiv" class="container-fluid id-card justify-content-center" >
                            <style>
                                @page {
                                    size: 98.5mm 67mm;
                                    margin: 0;
                                }

                                .id-card {
                                    width: 97.5mm;
                                    height: 66mm;
                                    border: 1px solid #000 !important;
                                    outline: 1px solid #000;
                                    box-sizing: border-box;
                                    -webkit-print-color-adjust: exact;
                                    print-color-adjust: exact;
                                    background: #fff;
                                }

                                @media print {
                                    .id-card {
                                        border: 2px solid #000 !important;
                                        outline: 2px solid #000;
                                    }
                                }
                            </style>
                                <table class="table mb-2 text-center" style="margin-bottom: 0px;">
                                    <tr>
                                        <td colspan="2" align="center" style="position: relative; border: 0; padding-bottom: 5px;">
                                            <div style="border-bottom: 0px solid #000; padding-bottom: 0vw; position: relative;">
                                                <img src="{{ asset('public/coaching_camp/images/logo.png') }}"
                                                    style="position: absolute; width: 40px; top: 1px; left: 0;" />
                                                <div class="text-center">
                                                    <b>
                                                        Department of Sports,Uttar Pradesh <br>
                                                        {{$idcard->stadium_name}}
                                                    </b>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <table cellpadding="1" cellspacing="0" width="100%" style="margin-top:5px;">
                                    <tr>
                                        <td width="25%"><strong>Unique ID:</strong></td>
                                        <td width="50%">{{ $idcard->application_no  }}</td>

                                        <!-- Right Side Photo & Signature -->
                                        <td rowspan="10" width="25%" align="center" valign="top">

                                            <strong>Validity: </strong><br>{{$idcard->validity}}


                                            <br><br>

                                            <img src="{{ asset('public/coaching_camp/profile_picture/'.$idcard->profile_picture) }}"
                                                style="height:50px;">

                                            <br><br>

                                            <img src="{{ asset('public/coaching_camp/signature/'.$idcard->signature) }}"
                                                style="height:30px;">

                                            <br>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Name:</strong></td>
                                        <td{{ $idcard->name }}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Father:</strong></td>
                                        <td>{{ $idcard->father_name }}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>DOB:</strong></td>
                                        <td>{{ $idcard->dob ? dmy($idcard->dob) : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Gender:</strong></td>
                                        <td>{{ $idcard->gender==1 ? 'Male' : 'Female' }}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Mobile:</strong></td>
                                        <td>{{ $idcard->mobile }}</td>
                                    </tr>

                                    <tr>
                                        <td><strong>Sport:</strong></td>
                                        <td>
                                            @php
                                                $sportIds = explode(',', optional($idcard)->sport ?? '');
                                                $sportNames = array_map(function($id) {
                                                    return sport_name($id);
                                                }, $sportIds);
                                            @endphp

                                            {{ implode(', ', $sportNames) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><strong>Permanent Address:</strong></td>
                                        <td>{{ $idcard->correspondence_address }}, {{ districtName($idcard->correspondence_district) }}, Uttar Pradesh, {{ $idcard->correspondence_pin }}</td>
                                    </tr>


                                    {{-- <tr>
                                        <td><strong>Correspondence District/ज़िला</strong></td>
                                        <td>{{ districtName($idcard->correspondence_district) }}</td>
                                    </tr>
                                </table>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-scripts')
<script>
    function PrintDoc() {

        var content = document.getElementById('prodiv').outerHTML;

        var printWindow = window.open('', '_blank');

        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>ID Card</title>

                <style>

                    @page {
                        size: 67mm 98.5mm;
                        margin: 0;
                    }

                    html,
                    body {
                        margin: 0;
                        padding: 0;
                        width: 67mm;
                        height: 98.5mm;
                    }

                    body {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }

                    .id-card {
                        width: 67mm !important;
                        height: 98.5mm !important;
                        border: 2px solid #000 !important;
                        box-sizing: border-box;
                        padding: 3mm;
                        overflow: hidden;
                        background: #fff;
                        font-size: 9px;
                    }

                    img {
                        max-width: 100%;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    @media print {

                        html,
                        body {
                            margin: 0 !important;
                            padding: 0 !important;
                        }

                        .id-card {
                            page-break-inside: avoid;
                        }
                    }

                </style>

            </head>
            <body>

                ${content}

            </body>
            </html>
        `);

        printWindow.document.close();

        printWindow.onload = function () {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }
</script>
@endpush
