@extends('layouts/coaching_camp_auth')
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
                        <a href="{{ route('coaching_camp_dashboard') }}" class="btn btn-danger btn-sm backbtn float-end rounded-pill">
                            <span class="icons icon-arrow-left"></span>Back to Dashboard
                        </a>
                    </h4>
                </div>
                <div class="bhoechie-tab-container">
                    <div class="row justify-content-center">
                        <div class="nano-content col-3">
                            <div class="card">
                                <div class="card-body">
                                    <div id="prodiv" class="container-fluid id-card" style="
                                        width:98.5mm;
                                        height:67mm;
                                        border:1px solid #000;
                                        padding:2mm;
                                        box-sizing:border-box;
                                        overflow:hidden;
                                        font-size:small;
                                    ">
                                    <style>
                                        @page {
                                            size: 98.5mm 67mm;
                                            margin: 0;
                                        }

                                        .id-card {
                                            width: 98.5mm;
                                            height: 67mm;
                                            box-sizing: border-box;
                                            padding: 4mm;
                                            border: 1px solid #000;
                                            font-size: 10px;
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
                                                                Department of Sports,Uttar Pradesh<br>
                                                                {{$idcard->stadium_name}}
                                                            </b>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>

                                        <table cellpadding="2" cellspacing="0" width="100%" style="margin-top:5px;">
                                            <tr>
                                                <td width="25%"><strong>Name:</strong></td>
                                                <td width="50%">{{ Auth::guard('CoachingCamp')->user()->name }}</td>

                                                <!-- Right Side Photo & Signature -->
                                                <td rowspan="10" width="25%" align="center" valign="top">

                                                    <strong>Validity: </strong>31-12-2026
                                                    {{-- {{ dmy($idcard->valid_from) }} to {{ dmy($idcard->valid_upto) }} --}}

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
                                                <td><strong>Father:</strong></td>
                                                <td>{{ $idcard->father_name }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>DOB:</strong></td>
                                                <td>{{ Auth::guard('CoachingCamp')->user()->dob ? dmy(Auth::guard('CoachingCamp')->user()->dob) : '' }}</td>
                                            </tr>

                                            <tr>
                                                <td><strong>Mobile:</strong></td>
                                                <td>{{ Auth::guard('CoachingCamp')->user()->mobile }}</td>
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
                                            </tr> --}}
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-scripts')
@endpush
