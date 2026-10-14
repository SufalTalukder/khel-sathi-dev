@extends('layouts/admin_layout')
@section('content')

    <style>
        table.dataTable>thead>tr>th:not(.sorting_),
        table.dataTable>thead>tr>td:not(.sorting_) {
            padding-right: 0;
        }

        table.table-bordered.dataTable tbody th,
        table.table-bordered.dataTable tbody td {
            border-color: #000;
        }

        .table>:not(caption)>*>* {
            padding: 0.1rem;
        }

        table.table-bordered.dataTable th,
        table.table-bordered.dataTable td {}

        div#DataTables_Table_0_filter input {
            border: 1px solid #ced4da;
        }

        table th {
            color: #000 !important;
            font-weight: 700;
            background: #dbdfe3 !important;
            border-color: #000;
        }

        .table>:not(:last-child)>:last-child>* {
            border-bottom: 1px solid #000000;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .border {
            border: 2px solid #000000 !important;
        }

        .dn {
            display: none;
        }
    </style>
<script type="text/javascript" src="{{ asset('js/auto_score.js') }}"></script>

    <div class="pageheader" id="menu-margin">
        <h4 class="mb-0">



            {{--  change on basis of subSport and gender --}}





            {{-- <a href="{{route('collegeadmintrialresultreport')}}"  class="btn btn-sm  btn-outline-primary ms-2 float-end " ><span class="icons icon-list"></span>Trial Result Report</a> --}}
            <a href="{{ url('collegeadmin/dashboard') }}" class="btn btn-outline-danger btn-sm backbtn float-end  m-0"><span
                    class="icons icon-arrow-left"></span>Back/पीछे</a>


        </h4>


    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('collegeadminfiltertrialListtwo') }}" method="post">
                @csrf
                <div class="row">
                    @if (Auth::guard('admin')->user()->admin_role == 1 ||
                            Auth::guard('admin')->user()->admin_role == 6 ||
                            Auth::guard('admin')->user()->admin_role == 19)
                        <div class="mb-3 col-md-2">
                            <div class="form-group">
                                <label>Division</label>
                                <select class="form-select" name="division_id" style="width: 100%;">
                                    <option value="">Select Division</option>
                                    @foreach ($division as $item)
                                        <option value="{{ $item->id }}"
                                            @if (isset($filterData)) @if ($filterData['division_id'] == $item->id) selected @endif
                                            @endif >{{ $item->division_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif


                    <div class="mb-3 col-md-2">
                        <div class="form-group">
                            <label>Admission Seeking</label>
                            <select class="form-select" name="admission_seeking" style="width: 100%;">
                                <option value="">Select</option>

                                <option value="6" @if (request('admission_seeking') == '6') selected @endif>6th</option>
                                <option value="9" @if (request('admission_seeking') == '9') selected @endif>9th</option>
                                <option value="11" @if (request('admission_seeking') == '11') selected @endif>11th</option>
                            </select>
                        </div>
                    </div>



                    <div class="mb-3 col-md-2">
                        <div class="form-group">
                            <label>Name of The Sport</label>
                            <select class="form-select" name="sport" id="sport" onchange="sporttype(this.value)"
                                data-gender="@if (isset($filterData)) {{ $filterData['gender'] }} @endif"
                                data-subsport="@if (isset($filterData)) {{ $filterData['subSport'] }} @endif">
                                <option value="">select</option>
                                @foreach ($sports as $sport)
                                    <option value="{{ $sport->id }}"
                                        @if (isset($filterData)) @if ($filterData['sport_id'] == $sport->id) selected @endif
                                        @endif >{{ $sport->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 col-md-3">
                        <div class="form-group">
                            <label>Name of The Sub Sport</label>
                            <select class="form-select" id="subtype" name="subsport">
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 col-md-2">
                        <div class="form-group">
                            <label>Gender</label>
                            <select class="form-select" name="gender" style="width: 100%;"
                                data-gender="@if (isset($filterData)) {{ $filterData['gender'] }} @endif"
                                data-division="@if (isset($filterData)) {{ $filterData['division_id'] }} @endif">
                                <option>select</option>
                                <option value="1"
                                    @if (isset($filterData)) @if ($filterData['gender'] == 1) selected @endif
                                    @endif>Male</option>
                                <option value="2"
                                    @if (isset($filterData)) @if ($filterData['gender'] == 2) selected @endif
                                    @endif>Female</option>
                                <option value="3"
                                    @if (isset($filterData)) @if ($filterData['gender'] == 3) selected @endif
                                    @endif>Male & Female</option>
                            </select>
                        </div>
                    </div>



                    {{-- <div class="mb-3 col-md-2" id="gender_result" style="">
                        <div class="form-group">
                            <label>Gender Filter For Trial</label>
                            <select class="form-select" name="gender_result" id="gender_resultt" style="width: 100%;">
                                <option value="">Select </option>

                                <option value="1" {{ request()->input('gender_result') == 1 ? 'selected' : '' }}>Male
                                </option>
                                <option value="2" {{ request()->input('gender_result') == 2 ? 'selected' : '' }}>
                                    Female</option>
                            </select>
                        </div>
                    </div> --}}






                    <div class="mb-3 col-md-2">
                        <div class="form-group d-grid">
                            <label>&nbsp;</label>
                            <button class="btn btn-primary" type="submit">Filter Trial List</button>
                            <button class="btn btn-secondary form-group mt-4" type="button"
                                onclick="PrintDoc()">Print to Pdf</button>
                            <button class="btn btn-success form-group mt-4" type="button"
                                onclick="ExportToExcell('xlsx')">Export to Excel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if ($trialType != 18)


        <div class="card">

            <div class="card-body input_new">

                <!--h2 class="text-center  mb-2"><input type="text"   placeholder="Sports College, Lucknow" style="
    width: 100%;
    font-weight: 700;
">
    </h2>
       <h4 class="text-center mb-2">Preliminary Selection Exam 2022-23</h4>
       <h5 class="text-center mb-2">Name of The Game - Cricket (<input type="text"   placeholder="Batsman" style="
    width: 160px;
    font-weight: 700;
">)&nbsp;&nbsp;&nbsp;&nbsp; Category - Boys


        </h5-->


                <div class="table-responsive">
                    <table class="table text-center table-bordered" style="
    font-size: smaller;
">
                        <thead>
                            <tr>
                                <th rowspan="3" valign="top">क्रस </th>
                                <th rowspan="3" valign="top">फार्म सं0</th>
                                <th valign="top">उम्मीदवार का नाम</th>
                                <th valign="top">आयु </th>
                                <th rowspan="3" valign="top">
                                    लिंग </th>

                                <th rowspan="3" valign="top">
                                    कक्षा जिसके लिए <br> प्रवेश चाह रहे हैं </th>
                                <th colspan="12" valign="top">शारीरिक परीक्षा<span lang="en"></span> <br>
                                    पूर्णांक- 50</th>
                                {{-- change on basis of subSport and gender --}}
                                @if($trialType==9||$trialType==15||$trialType==19)
                                    <th rowspan="3" colspan="3" valign="top">इवेंट</th>
                                @endif

                                @if ($trialType == 1)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 2)
                                    <th colspan="8" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 3)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 4)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 5)
                                    <th colspan="5" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 6)
                                    <th colspan="9" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 7)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 8)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 9)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 10)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 11)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 12)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 13)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 14)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 15)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 16)
                                    <th colspan="9" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 17)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 19)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 21)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 23)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @elseif ($trialType == 31)
                                    <th colspan="7" valign="top">खेल परीक्षा<br>
                                        पूर्णांक - 50</th>
                                @endif

                                {{-- End change on basis of subSport and gender --}}
                                <th rowspan="3" valign="top">कुल प्रा० पूर्ण०<br>
                                    100<br>
                                </th>
                                <th rowspan="3" valign="top">अभ्यु०<br>
                                </th>
                                <th rowspan="3" valign="top"> एक्शन </th>
                                <th rowspan="3" valign="top"> द्वारा </th>
                                <th rowspan="3" valign="top"> दिनांक </th>
                            </tr>
                            <tr>
                                <th rowspan="2" valign="top">&nbsp; </th>
                                <th rowspan="2" valign="top">&nbsp; </th>
                                <th colspan="2" valign="top">100मी<br>
                                    10 अंक</th>
                                <th colspan="2" valign="top">800मी0<br>
                                    10 अंक </th>
                                <th colspan="2" valign="top">ब्रॉड जम्प<br>
                                    10 अंक </th>
                                <th colspan="2" valign="top">शटल रन<br>
                                    10 अंक </th>
                                <th colspan="2" valign="top">बाल थ्रो<br>
                                    10 अंक </th>
                                <th rowspan="2" valign="top">कुल प्रा० - 50<br>
                                </th>
                                <th rowspan="2" valign="top">एक्शन </th>
                                {{-- change on basis of subSport and gender --}}





                                @if ($trialType == 1)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 2)
                                    <th colspan="5" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 3)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 4)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 5)
                                    <th colspan="2" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 6)
                                    <th colspan="6" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 7)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 8)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 9)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 10)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 11)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 12)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 13)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 14)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 15)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 16)
                                    <th colspan="6" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 17)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 19)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 21)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 23)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                    @elseif ($trialType == 31)
                                    <th colspan="4" valign="top">स्किल टेस्ट<br>
                                        (पूर्ण० - 30)</th>
                                    <th valign="top">&nbsp;
                                @endif

                                {{-- End change on basis of subSport and gender --}}
                                </th>
                                <th rowspan="2" valign="top"><span
                                        jsaction="blur:Om5fgd; click:JUJgG; focus:kFg5W; mouseout:Om5fgd; mouseover:kFg5W;XIxNK:LOG0D;w02ePb:RzCLcc"
                                        jsname="gm7qse" data-term-type="tl" role="button" tabindex="0"
                                        data-sl="hi" data-tl="en">खेल</span> टे०
                                    पूर्ण० <br>
                                    20 </th>
                                <th rowspan="2" valign="top">प्रा०<br>
                                    50 </th>
                            </tr>
                            <tr>
                                <th valign="top">स०</th>
                                <th valign="top">अं०</th>
                                <th valign="top">स०</th>
                                <th valign="top">अं०</th>
                                <th valign="top">दू०</th>
                                <th valign="top" class="border-dark">अं०</th>
                                <th valign="top">स०</th>
                                <th valign="top">अं०</th>
                                <th valign="top">दू०</th>
                                <th valign="top">अं०</th>
                                {{-- change on basis of subSport and gender --}}





                                @if ($trialType == 1)
                                    <th valign="top"> हिट<br>
                                        7.5 </th>
                                    <th valign="top"> पुश <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top">स्कूप<br>
                                        7.5 </th>
                                    <th valign="top">
                                        <p>ड्रिब्लिंग<br>
                                            7.5 </p>
                                    </th>
                                @elseif ($trialType == 2)
                                    <th valign="top"> किक<br>
                                        6 </th>
                                    <th valign="top"> पैड <br>
                                        6 <br>
                                    </th>
                                    <th valign="top">स्टॉप<br>
                                        6 </th>
                                    <th valign="top">
                                        <p>हाई
                                            पुश <br>
                                            6 </p>
                                    </th>
                                    <th valign="top">
                                        <p> हिमात<br>
                                            6 </p>
                                    </th>
                                @elseif ($trialType == 3)
                                    <th valign="top">हाई
                                        सर्विस/डबल सर्विस/टॉस<br>

                                        7.5 </th>
                                    <th valign="top"> स्मैश<br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top">ड्राप<br>
                                        7.5 </th>
                                    <th valign="top">
                                        <p>बैकहैंड<br>
                                            7.5 </p>
                                    </th>
                                @elseif ($trialType == 4)
                                    <th valign="top">अंडर
                                        हैंड <br>
                                        7.5 </th>
                                    <th valign="top"> उप्पेर
                                        हैंड<br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top">सर्विस<br>
                                        7.5 </th>
                                    <th valign="top">
                                        <p>स्मैश <br>
                                            7.5 </p>
                                    </th>
                                @elseif ($trialType == 5)
                                    <th valign="top">ग्रा॰ पो॰
                                        (फेस<br>
                                        टू फेस/बैक पो॰) <br>
                                        15 </th>
                                    <th valign="top"> स्टै॰ पो॰
                                        (फ्रन्ट पो॰/
                                        बैक पो॰) <br>
                                        15 <br>
                                    </th>
                                @elseif ($trialType == 6)
                                    <th valign="top"> फ्री
                                        स्टा॰ <br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> बैक
                                        स्ट्रो<br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> बे्रस्ट
                                        स्ट्रो<br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> बटर
                                        फ्लाइ <br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> ग्लाइडिंग<br>
                                        5 </th>
                                    <th valign="top"> स्टार्ट <br>
                                        5 </th>
                                @elseif ($trialType == 7)
                                    <th valign="top"> ग्रिप <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> डाइव <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> पैच <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> किक/Kick <br>
                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 8)
                                    <th valign="top"> किक <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> ड्रिब/टेक्ल <br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> हेड <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> कान्ट्रो/पैड/<br>
                                        <br>
                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 9)
                                    <th valign="top"> एप्रोच <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> टे॰आ॰ <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> एक्शन <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> लैण्डिंग
                                        <br>
                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 10)
                                    <th valign="top"> ग्रिप/स्टान्स <br>
                                        बैकलिफ्ट

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> बाल
                                        सेलेक/
                                        <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> फ्रन्ट
                                        फुट/
                                        बैक
                                        फुट <br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> फ्रन्ट
                                        फुट/बैक
                                        फुट
                                        ड्रा0/ <br>

                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 11)
                                    <th valign="top"> रनअप/एक्शन/फालोथ्रू/<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> स्विंग/
                                        स्पिन/<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> लाइन
                                        लेन्थ/<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> स्पीड/फ्लाइट/ <br>

                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 12)
                                    <th valign="top"> स्टम्पिंग<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> गैदरिंग<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> आफ
                                        स्टम्पिंग <br>
                                        गैदरिंग<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> आन
                                        स्टम्पिंग <br>
                                        गैदरिंग<br>

                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 13)
                                    <th valign="top"> रेड/
                                        Raid <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> किक/स्किल/

                                    </th>
                                    <th valign="top"> कवरिंग

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> पकड
                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 14)
                                    <th valign="top"> स्टै॰
                                        वर्क
                                        थ्रो

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> हिप,
                                        लेग, हैण्ड <br>
                                        टै॰
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> थ्रो
                                        का
                                        काउ॰ <br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top">
                                        <p>थ्रो
                                            का काप्बी॰<br>

                                            7.5 <br>
                                        </p>
                                    </th>
                                @elseif ($trialType == 15)
                                    <th valign="top"> स्टान्स<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> स्टार्ट<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> एक्शन<br>

                                        7.5 <br>
                                    </th>
                                    <th valign="top"> फिनिश<br>

                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 16)
                                    <th valign="top"> फ्लोर
                                        एक्स॰ <br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> पामे
                                        हार्स <br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> रिंग

                                        5 <br>
                                    </th>
                                    <th valign="top"> वाल्विंग
                                        हार्स <br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> पैरे
                                        बार <br>
                                        5 <br>
                                    </th>
                                    <th valign="top"> हारि॰
                                        बार<br>
                                        5 <br>
                                    </th>
                                @elseif ($trialType == 17)
                                    <th valign="top"> बैल॰
                                        बी॰
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> अन
                                        इवन बार <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> फ्लोर
                                        एक्स॰ <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> वाल्विंग
                                        हार्स <br>
                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 19)
                                    <th valign="top"> स्टान्स<br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> एक्शन <br>
                                        <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> एक्जी0 <br>
                                        <br>
                                        7.5 <br>
                                    </th>
                                    <th valign="top"> फोएथू

                                        7.5 <br>
                                    </th>
                                @elseif ($trialType == 21)
                                    <th valign="top">
                                        पनचिन्ग पैड
                                        <br>7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        शैडो बॉक्सिंग
                                        <br>7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        स्किप
                                        7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        स्पारिंग
                                        <br>7.5
                                        <br>
                                    </th>
                                @elseif ($trialType == 23)
                                    <th valign="top">
                                        कैच/पास

                                        <br>7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        डिबलिंग

                                        <br>7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        स्टेण्डिंगशाट
                                        <br>
                                        7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        जम्पशाट


                                        <br>7.5
                                        <br>
                                    </th>
                                @elseif ($trialType == 31)
                                    <th valign="top">
                                        स्नैच

                                        <br>7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        पावर स्नैच

                                        <br>7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        क्लीन एण्ड जर्क
                                        <br>
                                        7.5
                                        <br>
                                    </th>
                                    <th valign="top">
                                        पावर क्लीन


                                        <br>7.5
                                        <br>
                                    </th>
                                @endif




                                {{-- End change on basis of subSport and gender --}}
                                <th valign="top">
                                    <p>प्रा०<br>

                                        30 </p>
                                </th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($applicants as $key => $applicant)
                                <tr>
                                    <form action="{{ route('trialListApplicantStore') }}" method="POST"
                                        id="form_{{ $applicant->application_no }}" class="needs-validation applicantData"
                                        novalidate>

                                        @if (isset($applicant->application_no))
                                            <?php $trial = trialData($applicant->application_no, $filterData['subSport'], 2); ?>
                                            @if (isset($trial))
                                            @endif
                                        @endif
                                        <td valign="bottom">
                                            {{ $key + 1 }}
                                        </td>
                                        <td valign="top">
                                            {{ $applicant->application_no }}
                                        </td>
                                        <td valign="top">
                                            {{ $applicant->fullname }}
                                        </td>
                                        <td valign="top">
                                            {{ get_age($applicant->dob, '2026-04-01') }}
                                        </td>
                                        <td valign="top">
                                            @if ($applicant->gender == 1)
                                                Male
                                            @else
                                                Female
                                            @endif
                                        </td>
                                        <td valign="top"> {{ $applicant->admission_seeking }}</td>

                                        <td valign="top">
                                            <input type="hidden" name="application_no"
                                                value="{{ $applicant->application_no }}">
                                            <input type="hidden" name="applicant_id"
                                                value="{{ $applicant->register_id }}">
                                            <input type="hidden" name="gender" value="{{ $applicant->gender }}">
                                            <input type="hidden" name="sport_id" value="{{ $filterData['sport_id'] }}">
                                            <input type="hidden" name="subsport_id"
                                                value="{{ $filterData['subSport'] }}">
                                            <input type="hidden" name="trial_type" value="2">

                                            <input type="number" name="hundred_mt_time"
                                                        id="hundtime{{ $applicant->application_no }}"
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->hundred_mt_time : '' }}"
                                                        oninput="hundred_mt_score({{ $applicant->application_no }},{{ $applicant->gender }},{{ $applicant->admission_seeking }})"
                                                        required
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" name="hundred_mt_mark" max="10"
                                                        id="hund{{ $applicant->application_no }}"
                                                        oninput="allAdd({{ $applicant->application_no }})" required
                                                        @if (isset($trial))  @endif readonly
                                                        value="{{ isset($trial) ? (float)$trial->hundred_mt_mark : '' }}"
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td class="d-flex"><input type="number" name="eight_hundred_mt_time"
                                                        id="eighttime{{ $applicant->application_no }}" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->eight_hundred_mt_time : '' }}"
                                                        oninput="eight_mt_score({{ $applicant->application_no }},{{ $applicant->gender }},{{ $applicant->admission_seeking }})"
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">

                                                </td>

                                                <td><input type="number" name="eight_hundred_mt_mark" max="10"
                                                        id="eight{{ $applicant->application_no }}"
                                                        oninput="allAdd({{ $applicant->application_no }})" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->eight_hundred_mt_mark : '' }}"
                                                        readonly
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" name="broad_jump_distance" max="10"
                                                        id="jumpdist{{ $applicant->application_no }}" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->broad_jump_distance : '' }}"
                                                        oninput="jumpdist_score({{ $applicant->application_no }},{{ $applicant->gender }},{{ $applicant->admission_seeking }})"
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" name="broad_jump_mark" max="10"
                                                        id="jump{{ $applicant->application_no }}"
                                                        oninput="allAdd({{ $applicant->application_no }})" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->broad_jump_mark : '' }}"
                                                        readonly
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" name="shuttle_run_time" max="10"
                                                        id="shuttletime{{ $applicant->application_no }}" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->shuttle_run_time : '' }}"
                                                        oninput="shuttletime_score({{ $applicant->application_no }},{{ $applicant->gender }},{{ $applicant->admission_seeking }})"
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" name="shuttle_run_mark" max="10"
                                                        id="shuttle{{ $applicant->application_no }}"
                                                        oninput="allAdd({{ $applicant->application_no }})" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->shuttle_run_mark : '' }}"
                                                        readonly
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" name="ball_throw_distance" max="10"
                                                        id="balldist{{ $applicant->application_no }}" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->ball_throw_distance : '' }}"
                                                        oninput="balldist_score({{ $applicant->application_no }},{{ $applicant->gender }},{{ $applicant->admission_seeking }})"
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" name="ball_throw_mark" max="10"
                                                        id="ball{{ $applicant->application_no }}"
                                                        oninput="allAdd({{ $applicant->application_no }})" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->ball_throw_mark : '' }}"
                                                        readonly
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>
                                                <td><input type="number" readonly name="physical_total_mark"
                                                        max="50" id="phy{{ $applicant->application_no }}" required
                                                        @if (isset($trial)) readonly @endif
                                                        value="{{ isset($trial) ? (float)$trial->physical_total_mark : '' }}"
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
		">
                                                </td>
                                                <td><input type="checkbox"
                                                        @if (isset($trial)) disabled checked @endif
                                                        id="check{{ $applicant->application_no }}"
                                                        onChange="myfunction({{ $applicant->application_no }})"
                                                        style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
		">
                                                </td>



                                    </form>

                                    {{-- change on basis of subSport and gender --}}

                                    @if ($trialType == 1)
                                        <form action="{{ route('collegeadminhockeytrialList') }}"
                                            id="hockey_{{ $applicant->application_no }}"
                                            class="needs-validation hockeyData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialhockey = trialhockeyData($applicant->application_no, 2); ?>
                                                @if (isset($trialhockey))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="hit_mark"
                                                    @if (isset($trialhockey)) readonly  value="{{ $trialhockey->hit_mark }}" @endif
                                                    oninput="hockeyskillTotal({{ $applicant->application_no }})"
                                                    id="hit_mark{{ $applicant->application_no }}"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>
                                            <td><input type="number" name="push_mark"
                                                    @if (isset($trialhockey)) readonly  value="{{ $trialhockey->push_mark }}" @endif
                                                    id="push_mark{{ $applicant->application_no }}"
                                                    oninput="hockeyskillTotal({{ $applicant->application_no }})"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>
                                            <td><input type="number" name="scoop_mark"
                                                    @if (isset($trialhockey)) readonly value="{{ $trialhockey->scoop_mark }}" @endif
                                                    id="scoop_mark{{ $applicant->application_no }}"
                                                    oninput="hockeyskillTotal({{ $applicant->application_no }})"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>
                                            <td><input type="number" name="dribbling_mark"
                                                    @if (isset($trialhockey)) readonly value="{{ $trialhockey->dribbling_mark }}" @endif
                                                    id="dribbling_mark{{ $applicant->application_no }}"
                                                    oninput="hockeyskillTotal({{ $applicant->application_no }})"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>

                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialhockey)) value="{{ $trialhockey->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 2px solid #868686;
						">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialhockey)) readonly   value="{{ $trialhockey->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialhockey)) readonly   value="{{ $trialhockey->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 2px solid #868686;
						">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialhockey)) readonly value="{{ $trialhockey->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 2px solid #868686;
							">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialhockey)) readonly value="{{ $trialhockey->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>
                                            <td><input type="checkbox" id="checkhockey{{ $applicant->application_no }}"
                                                    @if (isset($trialhockey)) checked disabled @endif
                                                    onChange="hockeyData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>

                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ rsoName($trialhockey->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ dmy($trialhockey->date) }}
                                                @endif
                                            </td>
                                        </form>



                                        </td>
                                    @elseif ($trialType == 2)
                                        <form action="{{ route('collegeadminhockeykeepertrialList') }}"
                                            id="hockeykeeper_{{ $applicant->application_no }}"
                                            class="needs-validation hockeykeeperData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialhockeykeeper = trialhockeykeeperData($applicant->application_no, 2); ?>
                                                @if (isset($trialhockeykeeper))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="kick_mark"
                                                    @if (isset($trialhockeykeeper)) readonly  value="{{ $trialhockeykeeper->kick_mark }}" @endif
                                                    oninput="hockeykeeperskillTotal({{ $applicant->application_no }})"
                                                    id="kick_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="pad_mark"
                                                    @if (isset($trialhockeykeeper)) readonly  value="{{ $trialhockeykeeper->pad_mark }}" @endif
                                                    id="pad_mark{{ $applicant->application_no }}"
                                                    oninput="hockeykeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="stop_mark"
                                                    @if (isset($trialhockeykeeper)) readonly value="{{ $trialhockeykeeper->stop_mark }}" @endif
                                                    id="stop_mark{{ $applicant->application_no }}"
                                                    oninput="hockeykeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="high_push_mark"
                                                    @if (isset($trialhockeykeeper)) readonly value="{{ $trialhockeykeeper->high_push_mark }}" @endif
                                                    id="high_push_mark{{ $applicant->application_no }}"
                                                    oninput="hockeykeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>
                                            <td><input type="number" name="himmat_mark"
                                                    @if (isset($trialhockeykeeper)) readonly value="{{ $trialhockeykeeper->himmat_mark }}" @endif
                                                    id="himmat_mark{{ $applicant->application_no }}"
                                                    oninput="hockeykeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
						width: 50px;
						font-weight: 400;
						background: none;
						border: 1px solid #cdcdcd;
						">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialhockeykeeper)) value="{{ $trialhockeykeeper->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialhockeykeeper)) readonly   value="{{ $trialhockeykeeper->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialhockeykeeper)) readonly   value="{{ $trialhockeykeeper->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialhockeykeeper)) readonly value="{{ $trialhockeykeeper->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialhockeykeeper)) readonly value="{{ $trialhockeykeeper->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkhockeykeeper{{ $applicant->application_no }}"
                                                    @if (isset($trialhockeykeeper)) checked disabled @endif
                                                    onChange="hockeykeeperData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>


                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ rsoName($trialhockeykeeper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ dmy($trialhockeykeeper->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 3)
                                        <form action="{{ route('collegeadminbadmintontrialList') }}"
                                            id="badmin_{{ $applicant->application_no }}"
                                            class="needs-validation badminData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialbadminton = trialBadmintonData($applicant->application_no, 2); ?>
                                                @if (isset($trialbadminton))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="high_double_service_mark"
                                                    @if (isset($trialbadminton)) readonly  value="{{ $trialbadminton->high_double_service_mark }}" @endif
                                                    oninput="badskillTotal({{ $applicant->application_no }})"
                                                    id="high_double_service_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="smash_mark"
                                                    @if (isset($trialbadminton)) readonly  value="{{ $trialbadminton->smash_mark }}" @endif
                                                    id="smash_mark{{ $applicant->application_no }}"
                                                    oninput="badskillTotal({{ $applicant->application_no }})"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="drop_mark"
                                                    @if (isset($trialbadminton)) readonly value="{{ $trialbadminton->drop_mark }}" @endif
                                                    id="drop_mark{{ $applicant->application_no }}"
                                                    oninput="badskillTotal({{ $applicant->application_no }})"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="backhand_mark"
                                                    @if (isset($trialbadminton)) readonly value="{{ $trialbadminton->backhand_mark }}" @endif
                                                    id="backhand_mark{{ $applicant->application_no }}"
                                                    oninput="badskillTotal({{ $applicant->application_no }})"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialbadminton)) value="{{ $trialbadminton->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 2px solid #868686;
							">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialbadminton)) readonly   value="{{ $trialbadminton->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialbadminton)) readonly   value="{{ $trialbadminton->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 2px solid #868686;
							">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialbadminton)) readonly value="{{ $trialbadminton->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 2px solid #868686;
							">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialbadminton)) readonly value="{{ $trialbadminton->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="checkbox" id="checkbad{{ $applicant->application_no }}"
                                                    @if (isset($trialbadminton)) checked disabled @endif
                                                    onChange="badminData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>

                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ rsoName($trialbadminton->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ dmy($trialbadminton->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 4)
                                        <form action="{{ route('collegeadminvolleyBalltrialList') }}"
                                            id="volleyball_{{ $applicant->application_no }}"
                                            class="needs-validation volleyballData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialvolleyball = trialvolleyballData($applicant->application_no, 2); ?>
                                                @if (isset($trialvolleyball))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="under_hand_mark"
                                                    @if (isset($trialvolleyball)) readonly  value="{{ $trialvolleyball->under_hand_mark }}" @endif
                                                    oninput="volleyballskillTotal({{ $applicant->application_no }})"
                                                    id="under_hand_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="upper_hand_mark"
                                                    @if (isset($trialvolleyball)) readonly  value="{{ $trialvolleyball->upper_hand_mark }}" @endif
                                                    id="upper_hand_mark{{ $applicant->application_no }}"
                                                    oninput="volleyballskillTotal({{ $applicant->application_no }})"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="service_mark"
                                                    @if (isset($trialvolleyball)) readonly value="{{ $trialvolleyball->service_mark }}" @endif
                                                    id="service_mark{{ $applicant->application_no }}"
                                                    oninput="volleyballskillTotal({{ $applicant->application_no }})"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="smash_mark"
                                                    @if (isset($trialvolleyball)) readonly value="{{ $trialvolleyball->smash_mark }}" @endif
                                                    id="smash_mark{{ $applicant->application_no }}"
                                                    oninput="volleyballskillTotal({{ $applicant->application_no }})"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialvolleyball)) value="{{ $trialvolleyball->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 2px solid #868686;
							">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialvolleyball)) readonly   value="{{ $trialvolleyball->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialvolleyball)) readonly   value="{{ $trialvolleyball->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 2px solid #868686;
							">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialvolleyball)) readonly value="{{ $trialvolleyball->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 2px solid #868686;
							">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialvolleyball)) readonly value="{{ $trialvolleyball->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkvolleyball{{ $applicant->application_no }}"
                                                    @if (isset($trialvolleyball)) checked disabled @endif
                                                    onChange="volleyballData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
							width: 50px;
							font-weight: 400;
							background: none;
							border: 1px solid #cdcdcd;
							">
                                            </td>


                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ rsoName($trialvolleyball->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ dmy($trialvolleyball->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 5)
                                        <form action="{{ route('collegeadminkustitrialList') }}"
                                            id="kusti_{{ $applicant->application_no }}"
                                            class="needs-validation kustiData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialkusti = trialkustiData($applicant->application_no, 2); ?>
                                                @if (isset($trialkusti))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="ground_position_mark"
                                                    @if (isset($trialkusti)) readonly  value="{{ $trialkusti->ground_position_mark }}" @endif
                                                    oninput="kustiskillTotal({{ $applicant->application_no }})"
                                                    id="ground_position_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="text" pattern="^(\d{0,2}\\.)?\d{1,2}$"
                                                    onkeypress="return " name="front_position_back_position_mark"
                                                    @if (isset($trialkusti)) readonly  value="{{ $trialkusti->front_position_back_position_mark }}" @endif
                                                    id="front_position_back_position_mark{{ $applicant->application_no }}"
                                                    oninput="kustiskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>

                                            <td><input type="text" pattern="^(\d{0,2}\\.)?\d{1,2}$"
                                                    name="test_score_mark" readonly
                                                    @if (isset($trialkusti)) value="{{ $trialkusti->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialkusti)) readonly   value="{{ $trialkusti->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialkusti)) readonly   value="{{ $trialkusti->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialkusti)) readonly value="{{ $trialkusti->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialkusti)) readonly value="{{ $trialkusti->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="checkbox" id="checkkusti{{ $applicant->application_no }}"
                                                    @if (isset($trialkusti)) checked disabled @endif
                                                    onChange="kustiData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>


                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ rsoName($trialkusti->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ dmy($trialkusti->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 6)
                                        <form action="{{ route('collegeadminswimmingtrialList') }}"
                                            id="swimming_{{ $applicant->application_no }}"
                                            class="needs-validation swimmingData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialswimming = trialswimmingData($applicant->application_no, 2); ?>
                                                @if (isset($trialswimming))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="free_stroke_mark"
                                                    @if (isset($trialswimming)) readonly  value="{{ $trialswimming->free_stroke_mark }}" @endif
                                                    oninput="swimmingskillTotal({{ $applicant->application_no }})"
                                                    id="free_stroke_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="back_stroke_mark"
                                                    @if (isset($trialswimming)) readonly  value="{{ $trialswimming->back_stroke_mark }}" @endif
                                                    id="back_stroke_mark{{ $applicant->application_no }}"
                                                    oninput="swimmingskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="breast_stroke_mark"
                                                    @if (isset($trialswimming)) readonly  value="{{ $trialswimming->breast_stroke_mark }}" @endif
                                                    id="breast_stroke_mark{{ $applicant->application_no }}"
                                                    oninput="swimmingskillTotal({{ $applicant->application_no }})"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>
                                            <td><input type="number" name="butter_fly_mark"
                                                    @if (isset($trialswimming)) readonly  value="{{ $trialswimming->butter_fly_mark }}" @endif
                                                    id="butter_fly_mark{{ $applicant->application_no }}"
                                                    oninput="swimmingskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="glaiding_mark"
                                                    @if (isset($trialswimming)) readonly value="{{ $trialswimming->glaiding_mark }}" @endif
                                                    id="glaiding_mark{{ $applicant->application_no }}"
                                                    oninput="swimmingskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="start_mark"
                                                    @if (isset($trialswimming)) readonly value="{{ $trialswimming->start_mark }}" @endif
                                                    id="start_mark{{ $applicant->application_no }}"
                                                    oninput="swimmingskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialswimming)) value="{{ $trialswimming->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialswimming)) readonly   value="{{ $trialswimming->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialswimming)) readonly   value="{{ $trialswimming->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialswimming)) readonly value="{{ $trialswimming->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialswimming)) readonly value="{{ $trialswimming->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkswimming{{ $applicant->application_no }}"
                                                    @if (isset($trialswimming)) checked disabled @endif
                                                    onChange="swimmingData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>


                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ rsoName($trialswimming->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ dmy($trialswimming->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 7)
                                        <form action="{{ route('collegeadminfootballkeepertrialList') }}"
                                            id="footballkeeper_{{ $applicant->application_no }}"
                                            class="needs-validation footballkeeperData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialfootballkeeper = trialfootballkeeperData($applicant->application_no, 2); ?>
                                                @if (isset($trialfootballkeeper))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="grip_mark"
                                                    @if (isset($trialfootballkeeper)) readonly  value="{{ $trialfootballkeeper->grip_mark }}" @endif
                                                    oninput="footballkeeperskillTotal({{ $applicant->application_no }})"
                                                    id="grip_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="dive_mark"
                                                    @if (isset($trialfootballkeeper)) readonly  value="{{ $trialfootballkeeper->dive_mark }}" @endif
                                                    id="dive_mark{{ $applicant->application_no }}"
                                                    oninput="footballkeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="patch_mark"
                                                    @if (isset($trialfootballkeeper)) readonly value="{{ $trialfootballkeeper->patch_mark }}" @endif
                                                    id="patch_mark{{ $applicant->application_no }}"
                                                    oninput="footballkeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="kick_mark"
                                                    @if (isset($trialfootballkeeper)) readonly value="{{ $trialfootballkeeper->kick_mark }}" @endif
                                                    id="kick_mark{{ $applicant->application_no }}"
                                                    oninput="footballkeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialfootballkeeper)) value="{{ $trialfootballkeeper->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialfootballkeeper)) readonly   value="{{ $trialfootballkeeper->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialfootballkeeper)) readonly   value="{{ $trialfootballkeeper->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialfootballkeeper)) readonly value="{{ $trialfootballkeeper->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialfootballkeeper)) readonly value="{{ $trialfootballkeeper->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkfootballkeeper{{ $applicant->application_no }}"
                                                    @if (isset($trialfootballkeeper)) checked disabled @endif
                                                    onChange="footballkeeperData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>

                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ rsoName($trialfootballkeeper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ dmy($trialfootballkeeper->date) }}
                                                @endif
                                            </td>
                                        </form>


                                        </td>
                                    @elseif ($trialType == 8)
                                        <form action="{{ route('collegeadminfootballtrialList') }}"
                                            id="football_{{ $applicant->application_no }}"
                                            class="needs-validation footballData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialfootball = trialfootballData($applicant->application_no, 2); ?>
                                                @if (isset($trialfootball))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="kick_mark"
                                                    @if (isset($trialfootball)) readonly  value="{{ $trialfootball->kick_mark }}" @endif
                                                    oninput="footballskillTotal({{ $applicant->application_no }})"
                                                    id="kick_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="dribble_tackle_mark"
                                                    @if (isset($trialfootball)) readonly  value="{{ $trialfootball->dribble_tackle_mark }}" @endif
                                                    id="dribble_tackle_mark{{ $applicant->application_no }}"
                                                    oninput="footballskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="head_mark"
                                                    @if (isset($trialfootball)) readonly value="{{ $trialfootball->head_mark }}" @endif
                                                    id="head_mark{{ $applicant->application_no }}"
                                                    oninput="footballskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="control_pad_mark"
                                                    @if (isset($trialfootball)) readonly value="{{ $trialfootball->control_pad_mark }}" @endif
                                                    id="control_pad_mark{{ $applicant->application_no }}"
                                                    oninput="footballskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialfootball)) value="{{ $trialfootball->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialfootball)) readonly   value="{{ $trialfootball->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialfootball)) readonly   value="{{ $trialfootball->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialfootball)) readonly value="{{ $trialfootball->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialfootball)) readonly value="{{ $trialfootball->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkfootball{{ $applicant->application_no }}"
                                                    @if (isset($trialfootball)) checked disabled @endif
                                                    onChange="footballData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ rsoName($trialfootball->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ dmy($trialfootball->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 9)
                                        <form action="{{ route('collegeadminathleticsjumpertrialList') }}"
                                            id="athleticsjumper_{{ $applicant->application_no }}"
                                            class="needs-validation athleticsjumperData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialathleticsjumper = trialathleticsjumperData($applicant->application_no, 2); ?>
                                                @if (isset($trialathleticsjumper))
                                                @endif
                                            @endif
                                            <td colspan="3">
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <select name="event" id="event">
                                                    @if($applicant->admission_seeking==6)
                                                        <option value="Long Jump" @if(@isset($trialathleticsjumper) && $trialathleticsjumper->event=="Long Jump") selected @endif>Long Jump</option>
                                                    @elseif($applicant->admission_seeking==9)
                                                        <option value="High Jump" @if(@isset($trialathleticsjumper) && $trialathleticsjumper->event=="High Jump") selected @endif>High Jump</option>
                                                        <option value="Long Jump" @if(@isset($trialathleticsjumper) && $trialathleticsjumper->event=="Long Jump") selected @endif>Long Jump</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="approach_mark"
                                                    @if (isset($trialathleticsjumper)) readonly  value="{{ $trialathleticsjumper->approach_mark }}" @endif
                                                    oninput="athleticsjumperskillTotal({{ $applicant->application_no }})"
                                                    id="approach_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="t_a_mark"
                                                    @if (isset($trialathleticsjumper)) readonly  value="{{ $trialathleticsjumper->t_a_mark }}" @endif
                                                    id="t_a_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsjumperskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="action_mark"
                                                    @if (isset($trialathleticsjumper)) readonly value="{{ $trialathleticsjumper->action_mark }}" @endif
                                                    id="action_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsjumperskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="landing_mark"
                                                    @if (isset($trialathleticsjumper)) readonly value="{{ $trialathleticsjumper->landing_mark }}" @endif
                                                    id="landing_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsjumperskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialathleticsjumper)) value="{{ $trialathleticsjumper->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialathleticsjumper)) readonly   value="{{ $trialathleticsjumper->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialathleticsjumper)) readonly   value="{{ $trialathleticsjumper->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialathleticsjumper)) readonly value="{{ $trialathleticsjumper->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialathleticsjumper)) readonly value="{{ $trialathleticsjumper->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkathleticsjumper{{ $applicant->application_no }}"
                                                    @if (isset($trialathleticsjumper)) checked disabled @endif
                                                    onChange="athleticsjumperData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>

                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ rsoName($trialathleticsjumper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ dmy($trialathleticsjumper->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 10)
                                        <form action="{{ route('collegeadmincricketbatsmantrialList') }}"
                                            id="cricketbatsman_{{ $applicant->application_no }}"
                                            class="needs-validation cricketbatsmanData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialcricketbatsman = trialcricketbatsmanData($applicant->application_no, 2); ?>
                                                @if (isset($trialcricketbatsman))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="grip_stance_backlift_mark"
                                                    @if (isset($trialcricketbatsman)) readonly  value="{{ $trialcricketbatsman->grip_stance_backlift_mark }}" @endif
                                                    oninput="cricketbatsmanskillTotal({{ $applicant->application_no }})"
                                                    id="grip_stance_backlift_mark{{ $applicant->application_no }}"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>
                                            <td><input type="number" name="ball_select_mark"
                                                    @if (isset($trialcricketbatsman)) readonly  value="{{ $trialcricketbatsman->ball_select_mark }}" @endif
                                                    id="ball_select_mark{{ $applicant->application_no }}"
                                                    oninput="cricketbatsmanskillTotal({{ $applicant->application_no }})"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>
                                            <td><input type="number" name="front_foot_back_foot_mark"
                                                    @if (isset($trialcricketbatsman)) readonly value="{{ $trialcricketbatsman->front_foot_back_foot_mark }}" @endif
                                                    id="front_foot_back_foot_mark{{ $applicant->application_no }}"
                                                    oninput="cricketbatsmanskillTotal({{ $applicant->application_no }})"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>
                                            <td><input type="number" name="front_foot_back_foot_drive_mark"
                                                    @if (isset($trialcricketbatsman)) readonly value="{{ $trialcricketbatsman->front_foot_back_foot_drive_mark }}" @endif
                                                    id="front_foot_back_foot_drive_mark{{ $applicant->application_no }}"
                                                    oninput="cricketbatsmanskillTotal({{ $applicant->application_no }})"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialcricketbatsman)) value="{{ $trialcricketbatsman->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 2px solid #868686;
				">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialcricketbatsman)) readonly   value="{{ $trialcricketbatsman->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialcricketbatsman)) readonly   value="{{ $trialcricketbatsman->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 2px solid #868686;
				">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialcricketbatsman)) readonly value="{{ $trialcricketbatsman->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 2px solid #868686;
				">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialcricketbatsman)) readonly value="{{ $trialcricketbatsman->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkcricketbatsman{{ $applicant->application_no }}"
                                                    @if (isset($trialcricketbatsman)) checked disabled @endif
                                                    onChange="cricketbatsmanData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
				width: 50px;
				font-weight: 400;
				background: none;
				border: 1px solid #cdcdcd;
				">
                                            </td>

                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ rsoName($trialcricketbatsman->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ dmy($trialcricketbatsman->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 11)
                                        <form action="{{ route('collegeadmincricketballertrialList') }}"
                                            id="cricketballer_{{ $applicant->application_no }}"
                                            class="needs-validation cricketballerData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialcricketballer = trialcricketballerData($applicant->application_no, 2); ?>
                                                @if (isset($trialcricketballer))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="runup_action_followthrough_mark"
                                                    @if (isset($trialcricketballer)) readonly  value="{{ $trialcricketballer->runup_action_followthrough_mark }}" @endif
                                                    oninput="cricketballerskillTotal({{ $applicant->application_no }})"
                                                    id="runup_action_followthrough_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="swing_spin_mark"
                                                    @if (isset($trialcricketballer)) readonly  value="{{ $trialcricketballer->swing_spin_mark }}" @endif
                                                    id="swing_spin_mark{{ $applicant->application_no }}"
                                                    oninput="cricketballerskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="line_length_mark"
                                                    @if (isset($trialcricketballer)) readonly value="{{ $trialcricketballer->line_length_mark }}" @endif
                                                    id="line_length_mark{{ $applicant->application_no }}"
                                                    oninput="cricketballerskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="speed_flight_mark"
                                                    @if (isset($trialcricketballer)) readonly value="{{ $trialcricketballer->speed_flight_mark }}" @endif
                                                    id="speed_flight_mark{{ $applicant->application_no }}"
                                                    oninput="cricketballerskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialcricketballer)) value="{{ $trialcricketballer->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialcricketballer)) readonly   value="{{ $trialcricketballer->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialcricketballer)) readonly   value="{{ $trialcricketballer->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialcricketballer)) readonly value="{{ $trialcricketballer->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialcricketballer)) readonly value="{{ $trialcricketballer->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkcricketballer{{ $applicant->application_no }}"
                                                    @if (isset($trialcricketballer)) checked disabled @endif
                                                    onChange="cricketballerData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ rsoName($trialcricketballer->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ dmy($trialcricketballer->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 12)
                                        <form action="{{ route('collegeadmincricketkeepertrialList') }}"
                                            id="cricketkeeper_{{ $applicant->application_no }}"
                                            class="needs-validation cricketkeeperData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialcricketkeeper = trialcricketkeeperData($applicant->application_no, 2); ?>
                                                @if (isset($trialcricketkeeper))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="stumping_mark"
                                                    @if (isset($trialcricketkeeper)) readonly  value="{{ $trialcricketkeeper->stumping_mark }}" @endif
                                                    oninput="cricketkeeperskillTotal({{ $applicant->application_no }})"
                                                    id="stumping_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="gathering_mark"
                                                    @if (isset($trialcricketkeeper)) readonly  value="{{ $trialcricketkeeper->gathering_mark }}" @endif
                                                    id="gathering_mark{{ $applicant->application_no }}"
                                                    oninput="cricketkeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="off_stumping_gathering_mark"
                                                    @if (isset($trialcricketkeeper)) readonly value="{{ $trialcricketkeeper->off_stumping_gathering_mark }}" @endif
                                                    id="off_stumping_gathering_mark{{ $applicant->application_no }}"
                                                    oninput="cricketkeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="on_stumping_gathering_mark"
                                                    @if (isset($trialcricketkeeper)) readonly value="{{ $trialcricketkeeper->on_stumping_gathering_mark }}" @endif
                                                    id="on_stumping_gathering_mark{{ $applicant->application_no }}"
                                                    oninput="cricketkeeperskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialcricketkeeper)) value="{{ $trialcricketkeeper->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialcricketkeeper)) readonly   value="{{ $trialcricketkeeper->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialcricketkeeper)) readonly   value="{{ $trialcricketkeeper->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialcricketkeeper)) readonly value="{{ $trialcricketkeeper->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialcricketkeeper)) readonly value="{{ $trialcricketkeeper->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkcricketkeeper{{ $applicant->application_no }}"
                                                    @if (isset($trialcricketkeeper)) checked disabled @endif
                                                    onChange="cricketkeeperData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ rsoName($trialcricketkeeper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ dmy($trialcricketkeeper->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 13)
                                        <form action="{{ route('collegeadminkabadditrialList') }}"
                                            id="kabaddi_{{ $applicant->application_no }}"
                                            class="needs-validation kabaddiData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialkabaddi = trialkabaddiData($applicant->application_no, 2); ?>
                                                @if (isset($trialkabaddi))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="raid_mark"
                                                    @if (isset($trialkabaddi)) readonly  value="{{ $trialkabaddi->raid_mark }}" @endif
                                                    oninput="kabaddiskillTotal({{ $applicant->application_no }})"
                                                    id="raid_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="kick_skill_mark"
                                                    @if (isset($trialkabaddi)) readonly  value="{{ $trialkabaddi->kick_skill_mark }}" @endif
                                                    id="kick_skill_mark{{ $applicant->application_no }}"
                                                    oninput="kabaddiskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="covering_mark"
                                                    @if (isset($trialkabaddi)) readonly value="{{ $trialkabaddi->covering_mark }}" @endif
                                                    id="covering_mark{{ $applicant->application_no }}"
                                                    oninput="kabaddiskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="pakad_mark"
                                                    @if (isset($trialkabaddi)) readonly value="{{ $trialkabaddi->pakad_mark }}" @endif
                                                    id="pakad_mark{{ $applicant->application_no }}"
                                                    oninput="kabaddiskillTotal({{ $applicant->application_no }})"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialkabaddi)) value="{{ $trialkabaddi->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialkabaddi)) readonly   value="{{ $trialkabaddi->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialkabaddi)) readonly   value="{{ $trialkabaddi->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialkabaddi)) readonly value="{{ $trialkabaddi->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 2px solid #868686;
			">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialkabaddi)) readonly value="{{ $trialkabaddi->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkkabaddi{{ $applicant->application_no }}"
                                                    @if (isset($trialkabaddi)) checked disabled @endif
                                                    onChange="kabaddiData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
			width: 50px;
			font-weight: 400;
			background: none;
			border: 1px solid #cdcdcd;
			">
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ rsoName($trialkabaddi->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ dmy($trialkabaddi->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 14)
                                        <form action="{{ route('collegeadminjudotrialList') }}"
                                            id="judo_{{ $applicant->application_no }}"
                                            class="needs-validation judoData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialjudo = trialjudoData($applicant->application_no, 2); ?>
                                                @if (isset($trialjudo))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="straight_work_throw_mark"
                                                    @if (isset($trialjudo)) readonly  value="{{ $trialjudo->straight_work_throw_mark }}" @endif
                                                    oninput="judoskillTotal({{ $applicant->application_no }})"
                                                    id="straight_work_throw_mark{{ $applicant->application_no }}"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 1px solid #cdcdcd;
        ">
                                            </td>
                                            <td><input type="number" name="hip_leg_hand_techniquec_mark"
                                                    @if (isset($trialjudo)) readonly  value="{{ $trialjudo->hip_leg_hand_techniquec_mark }}" @endif
                                                    id="hip_leg_hand_techniquec_mark{{ $applicant->application_no }}"
                                                    oninput="judoskillTotal({{ $applicant->application_no }})"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 1px solid #cdcdcd;
        ">
                                            </td>
                                            <td><input type="number" name="throw_count_mark"
                                                    @if (isset($trialjudo)) readonly value="{{ $trialjudo->throw_count_mark }}" @endif
                                                    id="throw_count_mark{{ $applicant->application_no }}"
                                                    oninput="judoskillTotal({{ $applicant->application_no }})"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 1px solid #cdcdcd;
        ">
                                            </td>
                                            <td><input type="number" name="throw_combination_mark"
                                                    @if (isset($trialjudo)) readonly value="{{ $trialjudo->throw_combination_mark }}" @endif
                                                    id="throw_combination_mark{{ $applicant->application_no }}"
                                                    oninput="judoskillTotal({{ $applicant->application_no }})"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 1px solid #cdcdcd;
        ">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialjudo)) value="{{ $trialjudo->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 2px solid #868686;
        ">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialjudo)) readonly   value="{{ $trialjudo->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 1px solid #cdcdcd;
        ">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialjudo)) readonly   value="{{ $trialjudo->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 2px solid #868686;
        ">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialjudo)) readonly value="{{ $trialjudo->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 2px solid #868686;
        ">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialjudo)) readonly value="{{ $trialjudo->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 1px solid #cdcdcd;
        ">
                                            </td>
                                            <td><input type="checkbox" id="checkjudo{{ $applicant->application_no }}"
                                                    @if (isset($trialjudo)) checked disabled @endif
                                                    onChange="judoData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
        width: 50px;
        font-weight: 400;
        background: none;
        border: 1px solid #cdcdcd;
        ">
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ rsoName($trialjudo->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ dmy($trialjudo->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 15)
                                        <form action="{{ route('collegeadminathleticsrunnertrialList') }}"
                                            id="athleticsrunner_{{ $applicant->application_no }}"
                                            class="needs-validation athleticsrunnerData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialathleticsrunner = trialathleticsrunnerData($applicant->application_no, 2); ?>
                                                @if (isset($trialathleticsrunner))
                                                @endif
                                            @endif

                                            <td colspan="3">
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <select name="event" id="event">
                                                    @if($applicant->admission_seeking==6)
                                                        <option value="60 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="60 Metre Run") selected @endif>60 Metre Run</option>
                                                        <option value="200 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="200 Metre Run") selected @endif>200 Metre Run</option>
                                                        <option value="400 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="400 Metre Run") selected @endif>400 Metre Run</option>
                                                        <option value="600 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="600 Metre Run") selected @endif>600 Metre Run</option>
                                                        <option value="Race Walking" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="Race Walking") selected @endif>Race Walking</option>
                                                    @elseif($applicant->admission_seeking==9)
                                                        <option value="100 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="100 Metre Run") selected @endif>100 Metre Run</option>
                                                        <option value="200 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="200 Metre Run") selected @endif>200 Metre Run</option>
                                                        <option value="400 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="400 Metre Run") selected @endif>400 Metre Run</option>
                                                        <option value="800 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="800 Metre Run") selected @endif>800 Metre Run</option>
                                                        <option value="Race Walking" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="Race Walking") selected @endif>Race Walking</option>
                                                    @elseif($applicant->admission_seeking=11)
                                                        <option value="800 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="800 Metre Run") selected @endif>800 Metre Run</option>
                                                        <option value="1500 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="1500 Metre Run") selected @endif>1500 Metre Run</option>
                                                        <option value="3000 Metre Run" @if(@isset($trialathleticsrunner) && $trialathleticsrunner->event=="3000 Metre Run") selected @endif>3000 Metre Run</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="stance_mark"
                                                    @if (isset($trialathleticsrunner)) readonly  value="{{ $trialathleticsrunner->stance_mark }}" @endif
                                                    oninput="athleticsrunnerskillTotal({{ $applicant->application_no }})"
                                                    id="stance_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="start_mark"
                                                    @if (isset($trialathleticsrunner)) readonly  value="{{ $trialathleticsrunner->start_mark }}" @endif
                                                    id="start_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsrunnerskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="action_mark"
                                                    @if (isset($trialathleticsrunner)) readonly value="{{ $trialathleticsrunner->action_mark }}" @endif
                                                    id="action_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsrunnerskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="finish_mark"
                                                    @if (isset($trialathleticsrunner)) readonly value="{{ $trialathleticsrunner->finish_mark }}" @endif
                                                    id="finish_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsrunnerskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialathleticsrunner)) value="{{ $trialathleticsrunner->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialathleticsrunner)) readonly   value="{{ $trialathleticsrunner->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialathleticsrunner)) readonly   value="{{ $trialathleticsrunner->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialathleticsrunner)) readonly value="{{ $trialathleticsrunner->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialathleticsrunner)) readonly value="{{ $trialathleticsrunner->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkathleticsrunner{{ $applicant->application_no }}"
                                                    @if (isset($trialathleticsrunner)) checked disabled @endif
                                                    onChange="athleticsrunnerData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ rsoName($trialathleticsrunner->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ dmy($trialathleticsrunner->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 16)
                                        <form action="{{ route('collegeadmingymnasticboystrialList') }}"
                                            id="gymnasticboys_{{ $applicant->application_no }}"
                                            class="needs-validation gymnasticboysData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialgymnasticboys = trialgymnasticboysData($applicant->application_no, 2); ?>
                                                @if (isset($trialgymnasticboys))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="floor_exercise_mark"
                                                    @if (isset($trialgymnasticboys)) readonly  value="{{ $trialgymnasticboys->floor_exercise_mark }}" @endif
                                                    oninput="gymnasticboysskillTotal({{ $applicant->application_no }})"
                                                    id="floor_exercise_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="pommel_horse_mark"
                                                    @if (isset($trialgymnasticboys)) readonly  value="{{ $trialgymnasticboys->pommel_horse_mark }}" @endif
                                                    id="pommel_horse_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticboysskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="ring_mark"
                                                    @if (isset($trialgymnasticboys)) readonly value="{{ $trialgymnasticboys->ring_mark }}" @endif
                                                    id="ring_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticboysskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>

                                            <td><input type="number" name="vaulving_horse_mark"
                                                    @if (isset($trialgymnasticboys)) readonly  value="{{ $trialgymnasticboys->vaulving_horse_mark }}" @endif
                                                    id="vaulving_horse_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticboysskillTotal({{ $applicant->application_no }})"
                                                    style="
                        width: 50px;
                        font-weight: 400;
                        background: none;
                        border: 1px solid #cdcdcd;
                        ">
                                            </td>
                                            <td><input type="number" name="parallel_bar_mark"
                                                    @if (isset($trialgymnasticboys)) readonly value="{{ $trialgymnasticboys->parallel_bar_mark }}" @endif
                                                    id="parallel_bar_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticboysskillTotal({{ $applicant->application_no }})"
                                                    style="
                        width: 50px;
                        font-weight: 400;
                        background: none;
                        border: 1px solid #cdcdcd;
                        ">
                                            </td>
                                            <td><input type="number" name="horizontal_bar_mark"
                                                    @if (isset($trialgymnasticboys)) readonly value="{{ $trialgymnasticboys->horizontal_bar_mark }}" @endif
                                                    id="horizontal_bar_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticboysskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialgymnasticboys)) value="{{ $trialgymnasticboys->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialgymnasticboys)) readonly   value="{{ $trialgymnasticboys->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialgymnasticboys)) readonly   value="{{ $trialgymnasticboys->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialgymnasticboys)) readonly value="{{ $trialgymnasticboys->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialgymnasticboys)) readonly value="{{ $trialgymnasticboys->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkgymnasticboys{{ $applicant->application_no }}"
                                                    @if (isset($trialgymnasticboys)) checked disabled @endif
                                                    onChange="gymnasticboysData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ rsoName($trialgymnasticboys->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ dmy($trialgymnasticboys->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 17)
                                        <form action="{{ route('collegeadmingymnasticgirlstrialList') }}"
                                            id="gymnasticgirls_{{ $applicant->application_no }}"
                                            class="needs-validation gymnasticgirlsData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialgymnasticgirls = trialgymnasticgirlsData($applicant->application_no, 2); ?>
                                                @if (isset($trialgymnasticgirls))
                                                @endif
                                            @endif
                                            <td>
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="balancing_beam_mark"
                                                    @if (isset($trialgymnasticgirls)) readonly  value="{{ $trialgymnasticgirls->balancing_beam_mark }}" @endif
                                                    oninput="gymnasticgirlsskillTotal({{ $applicant->application_no }})"
                                                    id="balancing_beam_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="uneven_bar_mark"
                                                    @if (isset($trialgymnasticgirls)) readonly  value="{{ $trialgymnasticgirls->uneven_bar_mark }}" @endif
                                                    id="uneven_bar_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticgirlsskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="floor_exercise_mark"
                                                    @if (isset($trialgymnasticgirls)) readonly value="{{ $trialgymnasticgirls->floor_exercise_mark }}" @endif
                                                    id="floor_exercise_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticgirlsskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="vaulving_horse_mark"
                                                    @if (isset($trialgymnasticgirls)) readonly value="{{ $trialgymnasticgirls->vaulving_horse_mark }}" @endif
                                                    id="vaulving_horse_mark{{ $applicant->application_no }}"
                                                    oninput="gymnasticgirlsskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialgymnasticgirls)) value="{{ $trialgymnasticgirls->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialgymnasticgirls)) readonly   value="{{ $trialgymnasticgirls->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialgymnasticgirls)) readonly   value="{{ $trialgymnasticgirls->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialgymnasticgirls)) readonly value="{{ $trialgymnasticgirls->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialgymnasticgirls)) readonly value="{{ $trialgymnasticgirls->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkgymnasticgirls{{ $applicant->application_no }}"
                                                    @if (isset($trialgymnasticgirls)) checked disabled @endif
                                                    onChange="gymnasticgirlsData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>

                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ rsoName($trialgymnasticgirls->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ dmy($trialgymnasticgirls->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 19)
                                        <form action="{{ route('collegeadminathleticsthrowertrialList') }}"
                                            id="athleticsthrower_{{ $applicant->application_no }}"
                                            class="needs-validation athleticsthrowerData" novalidate method="post">

                                            @if (isset($applicant->application_no))
                                                <?php $trialathleticsthrower = trialathleticsthrowerData($applicant->application_no, 2); ?>
                                                @if (isset($trialathleticsthrower))
                                                @endif
                                            @endif
                                            <td colspan="3">
                                                <input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <select name="event" id="event">
                                                    @if($applicant->admission_seeking==6)
                                                        <option value="Javelin Throw"  @if(@isset($trialathleticsthrower) && $trialathleticsthrower->event=="Javelin Throw") selected @endif>Javelin Throw</option>
                                                        <option value="Shotput Throw"  @if(@isset($trialathleticsthrower) && $trialathleticsthrower->event=="Shotput Throw") selected @endif>Shotput Throw</option>
                                                    @elseif($applicant->admission_seeking==9)
                                                        <option value="Javelin Throw"  @if(@isset($trialathleticsthrower) && $trialathleticsthrower->event=="Javelin Throw") selected @endif>Javelin Throw</option>
                                                        <option value="Shotput Throw"  @if(@isset($trialathleticsthrower) && $trialathleticsthrower->event=="Shotput Throw") selected @endif>Shotput Throw</option>
                                                        <option value="Discuss Throw"  @if(@isset($trialathleticsthrower) && $trialathleticsthrower->event=="Disucs Throw") selected @endif>Discus Throw</option>
                                                        <option value="Hammer Throw"  @if(@isset($trialathleticsthrower) && $trialathleticsthrower->event=="Hammer Throw") selected @endif>Hammer Throw</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="stance_mark"
                                                    @if (isset($trialathleticsthrower)) readonly  value="{{ $trialathleticsthrower->stance_mark }}" @endif
                                                    oninput="athleticsthrowerskillTotal({{ $applicant->application_no }})"
                                                    id="stance_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="action_mark"
                                                    @if (isset($trialathleticsthrower)) readonly  value="{{ $trialathleticsthrower->action_mark }}" @endif
                                                    id="action_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsthrowerskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="execution_mark"
                                                    @if (isset($trialathleticsthrower)) readonly value="{{ $trialathleticsthrower->execution_mark }}" @endif
                                                    id="execution_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsthrowerskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="follow_throw_mark"
                                                    @if (isset($trialathleticsthrower)) readonly value="{{ $trialathleticsthrower->follow_throw_mark }}" @endif
                                                    id="follow_throw_mark{{ $applicant->application_no }}"
                                                    oninput="athleticsthrowerskillTotal({{ $applicant->application_no }})"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialathleticsthrower)) value="{{ $trialathleticsthrower->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialathleticsthrower)) readonly   value="{{ $trialathleticsthrower->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="number" readonly name="sport_test_mark"
                                                    @if (isset($trialathleticsthrower)) readonly   value="{{ $trialathleticsthrower->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="number" readonly
                                                    name="total_obtain_mark"@if (isset($trialathleticsthrower)) readonly value="{{ $trialathleticsthrower->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 2px solid #868686;
					">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialathleticsthrower)) readonly value="{{ $trialathleticsthrower->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkathleticsthrower{{ $applicant->application_no }}"
                                                    @if (isset($trialathleticsthrower)) checked disabled @endif
                                                    onChange="athleticsthrowerData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
					width: 50px;
					font-weight: 400;
					background: none;
					border: 1px solid #cdcdcd;
					">
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ rsoName($trialathleticsthrower->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ dmy($trialathleticsthrower->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 21)
                                        <form action="{{ route('collegeadminboxingtrialList') }}"
                                            id="boxing_{{ $applicant->application_no }}"
                                            class="needs-validation boxingData" novalidate method="post">
                                            @if (isset($applicant->application_no))
                                                <?php $trialboxing = trialBoxingData($applicant->application_no, 2); ?>
                                                @if (isset($trialboxing))
                                                @endif
                                            @endif
                                            <td><input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="punching_pad_mark"
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->punching_pad_mark }}" @endif
                                                    oninput="boxskillTotal({{ $applicant->application_no }})"
                                                    id="punching_pad_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="shadow_boxing_mark"
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->shadow_boxing_mark }}" @endif
                                                    id="shadow_boxing_mark{{ $applicant->application_no }}"
                                                    oninput="boxskillTotal({{ $applicant->application_no }})"
                                                    style="

                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="skypink_mark"
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->skypink_mark }}" @endif
                                                    id="skypink_mark{{ $applicant->application_no }}"
                                                    oninput="boxskillTotal({{ $applicant->application_no }})"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="sparring_mark"
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->sparring_mark }}" @endif
                                                    id="sparring_mark{{ $applicant->application_no }}"
                                                    oninput="boxskillTotal({{ $applicant->application_no }})"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialboxing)) value="{{ $trialboxing->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="sport_test_mark" readonly
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="number" name="total_obtain_mark" readonly
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialboxing)) readonly value="{{ $trialboxing->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkboxing{{ $applicant->application_no }}"
                                                    @if (isset($trialboxing)) checked disabled @endif
                                                    onChange="boxingData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
    width: 50px;
    font-weight: 400;
    background: none;
    border: 1px solid #cdcdcd;
    ">
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ rsoName($trialboxing->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ dmy($trialboxing->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 23)
                                        <form action="{{ route('collegeadminhandballtrialList') }}"
                                            id="handball_{{ $applicant->application_no }}"
                                            class="needs-validation handballData" novalidate method="post">
                                            @if (isset($applicant->application_no))
                                                <?php $trialhandball = trialHandballData($applicant->application_no, 2); ?>
                                                @if (isset($trialhandball))
                                                @endif
                                            @endif
                                            <td><input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="catch_pass_mark"
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->catch_pass_mark }}" @endif
                                                    oninput="handballskillTotal({{ $applicant->application_no }})"
                                                    id="catch_pass_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="dribbling_mark"
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->dribbling_mark }}" @endif
                                                    id="dribbling_mark{{ $applicant->application_no }}"
                                                    oninput="handballskillTotal({{ $applicant->application_no }})"
                                                    style="

                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="standing_shot_mark"
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->standing_shot_mark }}" @endif
                                                    id="standing_shot_mark{{ $applicant->application_no }}"
                                                    oninput="handballskillTotal({{ $applicant->application_no }})"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="jumpshot_mark"
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->jumpshot_mark }}" @endif
                                                    id="jumpshot_mark{{ $applicant->application_no }}"
                                                    oninput="handballskillTotal({{ $applicant->application_no }})"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialhandball)) value="{{ $trialhandball->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="sport_test_mark" readonly
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="number" name="total_obtain_mark" readonly
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialhandball)) readonly value="{{ $trialhandball->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkhandball{{ $applicant->application_no }}"
                                                    @if (isset($trialhandball)) checked disabled @endif
                                                    onChange="handballData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
    width: 50px;
    font-weight: 400;
    background: none;
    border: 1px solid #cdcdcd;
    ">
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ rsoName($trialhandball->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ dmy($trialhandball->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @elseif ($trialType == 31)
                                        <form action="{{ route('collegeadminweightliftingtrialList') }}"
                                            id="weight_{{ $applicant->application_no }}"
                                            class="needs-validation weightData" novalidate method="post">
                                            @if (isset($applicant->application_no))
                                                <?php $trialweightlifting = trialWeightliftingData($applicant->application_no, 2); ?>
                                                @if (isset($trialweightlifting))
                                                @endif
                                            @endif
                                            <td><input type="hidden" name="application_no"
                                                    value="{{ $applicant->application_no }}">
                                                <input type="hidden" name="applicant_id"
                                                    value="{{ $applicant->register_id }}">
                                                <input type="hidden" name="sport_id"
                                                    value="{{ $filterData['sport_id'] }}">
                                                <input type="hidden" name="trial_type" value="2">
                                                <input type="number" name="snatch_mark"
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->snatch_mark }}" @endif
                                                    oninput="weightskillTotal({{ $applicant->application_no }})"
                                                    id="snatch_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="power_snatch_mark"
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->power_snatch_mark }}" @endif
                                                    id="power_snatch_mark{{ $applicant->application_no }}"
                                                    oninput="weightskillTotal({{ $applicant->application_no }})"
                                                    style="

                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="clean_and_jerk_mark"
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->clean_and_jerk_mark }}" @endif
                                                    id="clean_and_jerk_mark{{ $applicant->application_no }}"
                                                    oninput="weightskillTotal({{ $applicant->application_no }})"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="power_clean_push_jerk_mark"
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->power_clean_push_jerk_mark }}" @endif
                                                    id="power_clean_push_jerk_mark{{ $applicant->application_no }}"
                                                    oninput="weightskillTotal({{ $applicant->application_no }})"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="test_score_mark" readonly
                                                    @if (isset($trialweightlifting)) value="{{ $trialweightlifting->test_score_mark }}" @endif
                                                    id="test_score_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="number" name="game_technique"
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->game_technique }}" @endif
                                                    oninput="mainTotal({{ $applicant->application_no }})"
                                                    id="game_technique{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="number" name="sport_test_mark" readonly
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->sport_test_mark }}" @endif
                                                    id="sport_test_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="number" name="total_obtain_mark" readonly
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->total_obtain_mark }}" @endif
                                                    id="total_obtain_mark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 2px solid #868686;
                    ">
                                            </td>
                                            <td><input type="text" name="remark"
                                                    @if (isset($trialweightlifting)) readonly value="{{ $trialweightlifting->remark }}" @endif
                                                    id="remark{{ $applicant->application_no }}"
                                                    style="
                    width: 50px;
                    font-weight: 400;
                    background: none;
                    border: 1px solid #cdcdcd;
                    ">
                                            </td>
                                            <td><input type="checkbox"
                                                    id="checkweight{{ $applicant->application_no }}"
                                                    @if (isset($trialweightlifting)) checked disabled @endif
                                                    onChange="weightData({{ $applicant->application_no }})"
                                                    {{ $applicant->application_no }}
                                                    style="
    width: 50px;
    font-weight: 400;
    background: none;
    border: 1px solid #cdcdcd;
    ">
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ rsoName($trialweightlifting->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ dmy($trialweightlifting->date) }}
                                                @endif
                                            </td>
                                        </form>
                                    @endif

                                    {{-- End change on basis of subSport and gender --}}


                                </tr>
                            @endforeach

                        </tbody>
                    </table>



                    <div id="prodiv" class="dn">
                        <table class="dn" style="width: 100%; margin-bottom: 5px;" border="0" id="dataTable">
                            <tbody>
                                <tr>

                                    <td colspan="2" align="center" style="position: relative; border: 0; padding-bottom: 5px;">
                                        <div style="border-bottom: 0px solid #000; ">
                                            <img src="{{ url('admin') }}/images/logo.png" style="position: absolute; width: 70px; top: 5px; left: 0;">
                                            <div style="font-size: 25px; font-weight: bold;">
                                                Khel Sathi Portal
                                            </div>
                                            <div style="font-size: 18px; font-weight: bold;">
                                                Sports College Final Trial Result
                                            </div>
                                            <div style="font-size: 18px; font-weight: bold;">

                                                @if ($trialType == 1)
                                                Hockey Player @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial List

                                                @elseif ($trialType == 2)
                                                Hockey Goalkeeper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 3)
                                                Badminton @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List


                                                @elseif ($trialType == 4)
                                                Volleyball @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 5)
                                                Wrestling @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 6)
                                                Swimming @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 7)
                                                Football Goalkeeper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List


                                                @elseif ($trialType == 8)
                                                Football Player @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List


                                                @elseif ($trialType == 9)
                                                Athletics Jumper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 10)
                                                Cricket Batsman @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 11)
                                                Cricket Bowler @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 12)
                                                Cricket Wicketkeeper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 13)
                                                Kabaddi @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 14)
                                                Judo @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 15)
                                                Athletics Runner @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 16)
                                                Gymnastic Male Trial  List

                                                @elseif ($trialType == 17)
                                                Gymnastic Female Trial  List

                                                @elseif ($trialType == 18)
                                                Trial  List

                                                @elseif ($trialType == 19)
                                                Athletics Thrower @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

                                                @elseif ($trialType == 21   )
                                                Boxing @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List
                                                @elseif ($trialType == 21)
                                                Basketball @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List
                                                @elseif ($trialType == 22)
                                                Table Tennis @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List
                                                @elseif ($trialType == 23)
                                                Handball @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List
                                                @elseif ($trialType == 24)
                                                Archery @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List
                                                @elseif ($trialType == 31)
                                                Weightlifting @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List


                                                @endif

                                                <br>


                                            </div>

                                        </div>
                                    </td>


                                </tr>
                                <tr>
                                    <td style="text-align: left; font-size: 12px; padding-top: 5px;"></td>
                                    <td style="text-align: right; font-size: 12px; padding-top: 5px;"><b>Print Date :</b> {{ Carbon\Carbon::parse(date('Y-m-d') )->format('d/m/Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="dataTablee" class="table text-center table-bordered" style="font-size: smaller;">
                            <thead>
                                <tr>
                                    <th rowspan="3" valign="top">क्रस </th>
                                    <th rowspan="3" valign="top">फार्म सं0</th>
                                    <th valign="top">उम्मीदवार का नाम</th>
                                    <th valign="top">आयु </th>
                                    <th rowspan="3" valign="top">
                                        लिंग </th>

                                    <th rowspan="3" valign="top">
                                        कक्षा जिसके लिए <br> प्रवेश चाह रहे हैं </th>
                                    <th colspan="12" valign="top">शारीरिक परीक्षा</span> <br>
                                        पूर्णांक - 50</th>
                                    {{-- change on basis of subSport and gender --}}
                                    @if($trialType==9||$trialType==15||$trialType==19)
                                        <th rowspan="3" colspan="3" valign="top">Event/इवेंट</th>
                                    @endif
                                    @if ($trialType == 1)
                                        <th colspan="7" valign="top">खेल परीक्षा<br>
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 2)
                                        <th colspan="8" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 3)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 4)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50/<br>
                                        </th>
                                    @elseif ($trialType == 5)
                                        <th colspan="5" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 6)
                                        <th colspan="9" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 7)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 8)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 9)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 10)
                                        <th colspan="7" valign="top">खेल परीक्षा<br>
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 11)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 12)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50
                                        </th>
                                    @elseif ($trialType == 13)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50
                                        </th>
                                    @elseif ($trialType == 14)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50
                                        </th>
                                    @elseif ($trialType == 15)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50
                                        </th>
                                    @elseif ($trialType == 16)
                                        <th colspan="9" valign="top">खेल परीक्षा
                                            पूर्णांक - 50
                                        </th>
                                    @elseif ($trialType == 17)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50
                                        </th>
                                    @elseif ($trialType == 19)
                                        <th colspan="7" valign="top">खेल परीक्षा
                                            पूर्णांक - 50
                                        </th>
                                    @elseif ($trialType == 21)
                                        <th colspan="7" valign="top">खेल परीक्षा<br>
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 23)
                                        <th colspan="7" valign="top">खेल परीक्षा<br>
                                            पूर्णांक - 50</th>
                                    @elseif ($trialType == 31)
                                        <th colspan="7" valign="top">खेल परीक्षा<br>
                                            पूर्णांक - 50</th>
                                    @endif
                                    {{-- End change on basis of subSport and gender --}}
                                    <th rowspan="3" valign="top">कुल प्रा० <br>
                                        पूर्ण० - 100<br>
                                    </th>
                                    <th rowspan="3" valign="top">अभ्यु०</th>
                                    <th rowspan="3" valign="top"> एक्शन </th>
                                    <th rowspan="3" valign="top"> द्वारा </th>
                                    <th rowspan="3" valign="top"> दिनांक </th>
                                </tr>
                                <tr>
                                    <th rowspan="2" valign="top">&nbsp; </th>
                                    <th rowspan="2" valign="top">&nbsp; </th>
                                    <th colspan="2" valign="top">100मी<br>
                                        10 अंक</th>
                                    <th colspan="2" valign="top">800मी0<br>
                                        10अंक </th>
                                    <th colspan="2" valign="top">ब्रॉड जम्प<br>
                                        10अंक </th>
                                    <th colspan="2" valign="top">शटल रन<br>
                                        10अंक </th>
                                    <th colspan="2" valign="top">बाल थ्रो<br>
                                        10अंक </th>
                                    <th rowspan="2" valign="top">कुल प्रा/<br>
                                        <br>
                                    </th>
                                    <th rowspan="2" valign="top">एक्शन </th>
                                    {{-- change on basis of subSport and gender --}}

                                    @if ($trialType == 1)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 2)
                                        <th colspan="5" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 3)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 4)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 5)
                                        <th colspan="2" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 6)
                                        <th colspan="6" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 7)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 8)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 9)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 10)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 11)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 12)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 13)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 14)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 15)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 16)
                                        <th colspan="6" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 17)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;</th>
                                    @elseif ($trialType == 19)
                                        <th colspan="4" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                        <th valign="top">&nbsp;
                                    @elseif ($trialType == 21)
                                        <th colspan="5" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                    @elseif ($trialType == 23)
                                        <th colspan="5" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                    @elseif ($trialType == 31)
                                        <th colspan="5" valign="top">स्किल टेस्ट<br>
                                            (पूर्ण० - 30)</th>
                                    @endif
                                    {{-- End change on basis of subSport and gender --}}
                                    </th>
                                    <th rowspan="2" valign="top"><span
                                            jsaction="blur:Om5fgd; click:JUJgG; focus:kFg5W; mouseout:Om5fgd; mouseover:kFg5W;XIxNK:LOG0D;w02ePb:RzCLcc"
                                            jsname="gm7qse" data-term-type="tl" role="button" tabindex="0"
                                            data-sl="hi" data-tl="en">खेल</span><br>
                                        टे० <br>
                                        पूर्ण० <br>
                                        20 </th>
                                    <th rowspan="2" valign="top">प्रा०<br>
                                        50 </th>
                                </tr>
                                <tr>
                                    <th valign="top">स०</th>
                                    <th valign="top">अं०</th>
                                    <th valign="top">स०</th>
                                    <th valign="top">अं०</th>
                                    <th valign="top">दू०</th>
                                    <th valign="top" class="border-dark">अं०</th>
                                    <th valign="top">स०</th>
                                    <th valign="top">अं०</th>
                                    <th valign="top">दू०</th>
                                    <th valign="top">अं०</th>
                                    {{-- change on basis of subSport and gender --}}
                                    @if ($trialType == 1)
                                        <th valign="top"> हिट
                                            7.5 </th>
                                        <th valign="top"> पुश
                                            7.5 <br>
                                        </th>
                                        <th valign="top">स्कूप
                                            7.5 </th>
                                        <th valign="top">
                                            <p>ड्रिब्लिंग/ <br>
                                                Dribbling <br>
                                                7.5 </p>
                                        </th>
                                    @elseif ($trialType == 2)
                                        <th valign="top"> किक
                                            6 </th>
                                        <th valign="top"> पैड
                                            6 <br>
                                        </th>
                                        <th valign="top">स्टॉप
                                            6 </th>
                                        <th valign="top">
                                            <p>हाई <br>
                                                पुश
                                                6 </p>
                                        </th>
                                        <th valign="top">
                                            <p> हिमात
                                                6 </p>
                                        </th>
                                    @elseif ($trialType == 3)
                                        <th valign="top">हाई <br>
                                            सर्विस/ <br>
                                            डबल <br>
                                            सर्विस <br>
                                            /टॉस
                                            7.5 </th>
                                        <th valign="top"> स्मैश

                                            7.5 <br>
                                        </th>
                                        <th valign="top">ड्राप
                                            7.5 </th>
                                        <th valign="top">
                                            <p>बैकहैंड
                                                7.5 </p>
                                        </th>
                                    @elseif ($trialType == 4)
                                        <th valign="top">अंडर<br>
                                            हैंड
                                            7.5 </th>
                                        <th valign="top"> उप्पेर <br>
                                            हैंड

                                            7.5 <br>
                                        </th>
                                        <th valign="top">सर्विस
                                            7.5 </th>
                                        <th valign="top">
                                            <p>स्मैश

                                                7.5 </p>
                                        </th>
                                    @elseif ($trialType == 5)
                                        <th valign="top">ग्रा॰ पो॰ <br>
                                            (फेस<br>
                                            टू फेस/बैक <br>
                                            पो॰) <br>
                                            15 </th>
                                        <th valign="top"> स्टै॰ पो॰<br>
                                            (फ्रन्ट पो॰/<br>
                                            बैक पो॰) <br>
                                            15 <br>
                                        </th>
                                    @elseif ($trialType == 6)
                                        <th valign="top"> फ्री <br>
                                            स्टा॰
                                            5 <br>
                                        </th>
                                        <th valign="top"> बैक<br>
                                            स्ट्रो
                                            5 <br>
                                        </th>
                                        <th valign="top"> बे्रस्ट <br>
                                            स्ट्रो
                                            5 <br>
                                        </th>
                                        <th valign="top"> बटर <br>
                                            फ्लाइ
                                            5 <br>
                                        </th>
                                        <th valign="top"> ग्लाइडिंग
                                            5 </th>
                                        <th valign="top"> स्टार्ट
                                            5 </th>
                                    @elseif ($trialType == 7)
                                        <th valign="top"> ग्रिप
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> डाइव
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> पैच
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> किक
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 8)
                                        <th valign="top"> किक
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> ड्रिब/ <br>
                                            टेक्ल <br>

                                            7.5 <br>
                                        </th>
                                        <th valign="top"> हेड
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> कान्ट्रो/ <br>
                                            पैड
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 9)
                                        <th valign="top"> एप्रोच <br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> टे॰आ॰<br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> एक्शन <br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> लैण्डिंग <br>
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 10)
                                        <th valign="top"> ग्रिप/ <br>
                                            स्टान्स <br>
                                            बैकलिफ्ट

                                            7.5 <br>
                                        </th>
                                        <th valign="top"> बाल <br>
                                            सेलेक
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> फ्रन्ट <br>
                                            फुट/ <br>
                                            बैक <br>
                                            फुट <br>

                                            7.5 <br>
                                        </th>
                                        <th valign="top"> फ्रन्ट <br>
                                            फुट/ <br>
                                            बैक <br>
                                            फुट <br>
                                            ड्रा0
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 11)
                                        <th valign="top"> रनअप/<br>
                                            एक्शन/<br>
                                            फालोथ्रू
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> स्विंग/<br>
                                            स्पिन/<br>

                                            7.5 <br>
                                        </th>
                                        <th valign="top"> लाइन <br>
                                            लेन्थ<br>

                                            7.5 <br>
                                        </th>
                                        <th valign="top"> स्पीड/<br>
                                            फ्लाइट<br>

                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 12)
                                        <th valign="top"> स्टम्पिंग
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> गैदरिंग
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> आफ <br>
                                            स्टम्पिंग <br>
                                            गैदरिंग
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> आन <br>
                                            स्टम्पिंग <br>
                                            गैदरिंग/
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 13)
                                        <th valign="top"> रेड
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> किक/ <br>
                                            स्किल
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> कवरिंग
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> पकड़
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 14)
                                        <th valign="top"> स्टै॰ <br>
                                            वर्क <br>
                                            थ्रो
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> हिप, <br>
                                            लेग, <br>
                                            हैण्ड <br>
                                            टै॰<br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> थ्रो <br>
                                            का <br>
                                            काउ॰
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> थ्रो <br>
                                            का <br>
                                            काप्बी॰
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 15)
                                        <th valign="top"> स्टान्स <br>

                                            7.5 <br>
                                        </th>
                                        <th valign="top"> स्टार्ट <br>

                                            7.5 <br>
                                        </th>
                                        <th valign="top"> एक्शन<br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> फिनिश <br>
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 16)
                                        <th valign="top"> फ्लोर <br>
                                            एक्स॰ <br>
                                            5 <br>
                                        </th>
                                        <th valign="top"> पामे <br>
                                            हार्स <br>
                                            5 <br>
                                        </th>
                                        <th valign="top"> रिंग<br>
                                            5 <br>
                                        </th>
                                        <th valign="top"> वाल्विंग<br>
                                            हार्स <br>
                                            5 <br>
                                        </th>
                                        <th valign="top"> पैरे <br>
                                            बार <br>
                                            5 <br>
                                        </th>
                                        <th valign="top"> हारि॰<br>
                                            बार <br>
                                            5 <br>
                                        </th>
                                    @elseif ($trialType == 17)
                                        <th valign="top"> बैल॰ <br>
                                            बी॰
                                            <br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> अन<br>
                                            इवन <br>
                                            बार<br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> फ्लोर <br>
                                            एक्स॰ <br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> वाल्विंग <br>
                                            हार्स<br>
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 19)
                                        <th valign="top"> स्टान्स <br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> एक्शन <br>
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> एक्जी0
                                            7.5 <br>
                                        </th>
                                        <th valign="top"> फोएथू <br>
                                            7.5 <br>
                                        </th>
                                    @elseif ($trialType == 21)
                                        <th valign="top">
                                            पनचिन्ग पैड
                                            <br>7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            शैडो बॉक्सिंग
                                            <br>7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            स्किप
                                            7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            स्पारिंग
                                            <br>7.5
                                            <br>
                                        </th>
                                    @elseif ($trialType == 23)
                                        <th valign="top">
                                            कैच/पास

                                            <br>7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            डिबलिंग

                                            <br>7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            स्टेण्डिंगशाट
                                            <br>
                                            7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            जम्पशाट


                                            <br>7.5
                                            <br>
                                        </th>
                                    @elseif ($trialType == 31)
                                        <th valign="top">
                                            स्नैच

                                            <br>7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            पावर स्नैच

                                            <br>7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            क्लीन एण्ड जर्क
                                            <br>
                                            7.5
                                            <br>
                                        </th>
                                        <th valign="top">
                                            पावर क्लीन
                                            <br>7.5
                                        </th>
                                    @endif
                                    {{-- End change on basis of subSport and gender --}}
                                    <th valign="top">
                                        <p>प्रा०/<br>
                                            <br>
                                            30
                                        </p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applicants as $key => $applicant)
                                    <tr>
                                        @if (isset($applicant->application_no))
                                            <?php $trial = trialData($applicant->application_no, $filterData['subSport'], 2); ?>
                                            @if (isset($trial))
                                            @endif
                                        @endif
                                        <td valign="bottom"> {{ $key + 1 }} </td>
                                        <td valign="top"> {{ $applicant->application_no }} </td>
                                        <td valign="top"> {{ $applicant->fullname }} </td>
                                        <td valign="top">{{ get_age($applicant->dob, '2025-04-01') }} </td>
                                        <td valign="top">
                                            @if ($applicant->gender == 1)
                                                Male
                                            @else
                                                Female
                                            @endif
                                        </td>
                                        <td valign="top"> {{ $applicant->admission_seeking }}</td>

                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->hundred_mt_time }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->hundred_mt_mark }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->eight_hundred_mt_time }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->eight_hundred_mt_mark }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->broad_jump_distance }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->broad_jump_mark }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->shuttle_run_time }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->shuttle_run_mark }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->ball_throw_distance }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->ball_throw_mark }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                {{ $trial->physical_total_mark }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($trial))
                                                checked
                                            @endif
                                        </td>
                                        {{-- change on basis of subSport and gender --}}
                                        @if ($trialType == 1)
                                            @if (isset($applicant->application_no))
                                                <?php $trialhockey = trialhockeyData($applicant->application_no, 2); ?>
                                                @if (isset($trialhockey))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->hit_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->push_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->scoop_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->dribbling_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ $trialhockey->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ rsoName($trialhockey->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockey))
                                                    {{ dmy($trialhockey->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 2)
                                            @if (isset($applicant->application_no))
                                                <?php $trialhockeykeeper = trialhockeykeeperData($applicant->application_no, 2); ?>
                                                @if (isset($trialhockeykeeper))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->kick_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->pad_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->stop_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->high_push_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->himmat_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ $trialhockeykeeper->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ rsoName($trialhockeykeeper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhockeykeeper))
                                                    {{ dmy($trialhockeykeeper->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 3)
                                            @if (isset($applicant->application_no))
                                                <?php $trialbadminton = trialBadmintonData($applicant->application_no, 2); ?>
                                                @if (isset($trialbadminton))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->high_double_service_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->smash_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->drop_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->backhand_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ $trialbadminton->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ rsoName($trialbadminton->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialbadminton))
                                                    {{ dmy($trialbadminton->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 4)
                                            @if (isset($applicant->application_no))
                                                <?php $trialvolleyball = trialvolleyballData($applicant->application_no, 2); ?>
                                                @if (isset($trialvolleyball))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->under_hand_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->upper_hand_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->service_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->smash_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ $trialvolleyball->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ rsoName($trialvolleyball->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialvolleyball))
                                                    {{ dmy($trialvolleyball->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 5)
                                            @if (isset($applicant->application_no))
                                                <?php $trialkusti = trialkustiData($applicant->application_no, 2); ?>
                                                @if (isset($trialkusti))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ $trialkusti->ground_position_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ $trialkusti->front_position_back_position_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ $trialkusti->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ $trialkusti->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ $trialkusti->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ $trialkusti->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ $trialkusti->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ rsoName($trialkusti->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkusti))
                                                    {{ dmy($trialkusti->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 6)
                                            @if (isset($applicant->application_no))
                                                <?php $trialswimming = trialswimmingData($applicant->application_no, 2); ?>
                                                @if (isset($trialswimming))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->free_stroke_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->back_stroke_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->breast_stroke_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->butter_fly_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->glaiding_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->start_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ $trialswimming->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ rsoName($trialswimming->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialswimming))
                                                    {{ dmy($trialswimming->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 7)
                                            @if (isset($applicant->application_no))
                                                <?php $trialfootballkeeper = trialfootballkeeperData($applicant->application_no, 2); ?>
                                                @if (isset($trialfootballkeeper))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->grip_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->dive_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->patch_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->kick_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ $trialfootballkeeper->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ rsoName($trialfootballkeeper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootballkeeper))
                                                    {{ dmy($trialfootballkeeper->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 8)
                                            @if (isset($applicant->application_no))
                                                <?php $trialfootball = trialfootballData($applicant->application_no, 2); ?>
                                                @if (isset($trialfootball))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->kick_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->dribble_tackle_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->head_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->control_pad_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ $trialfootball->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ rsoName($trialfootball->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialfootball))
                                                    {{ dmy($trialfootball->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 9)
                                            @if (isset($applicant->application_no))
                                                <?php $trialathleticsjumper = trialathleticsjumperData($applicant->application_no, 2); ?>
                                                @if (isset($trialathleticsjumper))
                                                @endif
                                            @endif
                                            <td colspan="3">
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->event }}
                                                @endif</td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->approach_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->t_a_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->action_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->landing_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ $trialathleticsjumper->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ rsoName($trialathleticsjumper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsjumper))
                                                    {{ dmy($trialathleticsjumper->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 10)
                                            @if (isset($applicant->application_no))
                                                <?php $trialcricketbatsman = trialcricketbatsmanData($applicant->application_no, 2); ?>
                                                @if (isset($trialcricketbatsman))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->grip_stance_backlift_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->ball_select_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->front_foot_back_foot_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->front_foot_back_foot_drive_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ $trialcricketbatsman->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ rsoName($trialcricketbatsman->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketbatsman))
                                                    {{ dmy($trialcricketbatsman->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 11)
                                            @if (isset($applicant->application_no))
                                                <?php $trialcricketballer = trialcricketballerData($applicant->application_no, 2); ?>
                                                @if (isset($trialcricketballer))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->runup_action_followthrough_mark }}"
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->swing_spin_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->line_length_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->speed_flight_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ $trialcricketballer->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ rsoName($trialcricketballer->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketballer))
                                                    {{ dmy($trialcricketballer->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 12)
                                            @if (isset($applicant->application_no))
                                                <?php $trialcricketkeeper = trialcricketkeeperData($applicant->application_no, 2); ?>
                                                @if (isset($trialcricketkeeper))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->stumping_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->gathering_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->off_stumping_gathering_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->on_stumping_gathering_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ $trialcricketkeeper->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ rsoName($trialcricketkeeper->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialcricketkeeper))
                                                    {{ dmy($trialcricketkeeper->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 13)
                                            @if (isset($applicant->application_no))
                                                <?php $trialkabaddi = trialkabaddiData($applicant->application_no, 2); ?>
                                                @if (isset($trialkabaddi))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->raid_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->kick_skill_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->covering_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->pakad_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ $trialkabaddi->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ rsoName($trialkabaddi->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialkabaddi))
                                                    {{ dmy($trialkabaddi->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 14)
                                            @if (isset($applicant->application_no))
                                                <?php $trialjudo = trialjudoData($applicant->application_no, 2); ?>
                                                @if (isset($trialjudo))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->straight_work_throw_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->hip_leg_hand_techniquec_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->throw_count_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->throw_combination_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ $trialjudo->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ rsoName($trialjudo->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialjudo))
                                                    {{ dmy($trialjudo->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 15)
                                            @if (isset($applicant->application_no))
                                                <?php $trialathleticsrunner = trialathleticsrunnerData($applicant->application_no, 2); ?>
                                                @if (isset($trialathleticsrunner))
                                                @endif
                                            @endif
                                            <td colspan="3">
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->event }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->stance_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->start_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->action_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->finish_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ $trialathleticsrunner->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ rsoName($trialathleticsrunner->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsrunner))
                                                    {{ dmy($trialathleticsrunner->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 16)
                                            @if (isset($applicant->application_no))
                                                <?php $trialgymnasticboys = trialgymnasticboysData($applicant->application_no, 2); ?>
                                                @if (isset($trialgymnasticboys))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->floor_exercise_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->pommel_horse_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->ring_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->vaulving_horse_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->parallel_bar_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->horizontal_bar_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ $trialgymnasticboys->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ rsoName($trialgymnasticboys->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticboys))
                                                    {{ dmy($trialgymnasticboys->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 17)
                                            @if (isset($applicant->application_no))
                                                <?php $trialgymnasticgirls = trialgymnasticgirlsData($applicant->application_no, 2); ?>
                                                @if (isset($trialgymnasticgirls))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->balancing_beam_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->uneven_bar_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->floor_exercise_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->vaulving_horse_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ $trialgymnasticgirls->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ rsoName($trialgymnasticgirls->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialgymnasticgirls))
                                                    {{ dmy($trialgymnasticgirls->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 19)
                                            @if (isset($applicant->application_no))
                                                <?php $trialathleticsthrower = trialathleticsthrowerData($applicant->application_no, 2); ?>
                                                @if (isset($trialathleticsthrower))
                                                @endif
                                            @endif
                                            <td colspan="3">
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->event }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->stance_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->action_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->execution_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->follow_throw_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ $trialathleticsthrower->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ rsoName($trialathleticsthrower->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialathleticsthrower))
                                                    {{ dmy($trialathleticsthrower->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 21)
                                            @if (isset($applicant->application_no))
                                                <?php $trialboxing = trialBoxingData($applicant->application_no, 2); ?>
                                                @if (isset($trialboxing))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->punching_pad_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->shadow_boxing_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->skypink_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->sparring_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ $trialboxing->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ rsoName($trialboxing->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialboxing))
                                                    {{ dmy($trialboxing->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 23)
                                            @if (isset($applicant->application_no))
                                                <?php $trialhandball = trialHandballData($applicant->application_no, 2); ?>
                                                @if (isset($trialhandball))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->catch_pass_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->dribbling_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->standing_shot_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->jumpshot_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ $trialhandball->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ rsoName($trialhandball->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialhandball))
                                                    {{ dmy($trialhandball->date) }}
                                                @endif
                                            </td>
                                        @elseif ($trialType == 31)
                                            @if (isset($applicant->application_no))
                                                <?php $trialweightlifting = trialWeightliftingData($applicant->application_no, 2); ?>
                                                @if (isset($trialweightlifting))
                                                @endif
                                            @endif
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->snatch_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->power_snatch_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->clean_and_jerk_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->power_clean_push_jerk_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->test_score_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->game_technique }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->sport_test_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->total_obtain_mark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ $trialweightlifting->remark }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    checked
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ rsoName($trialweightlifting->addedby) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($trialweightlifting))
                                                    {{ dmy($trialweightlifting->date) }}
                                                @endif
                                            </td>
                                        @endif
                                        {{-- End change on basis of subSport and gender --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>

    @endif

    <input type="hidden" name="trial_typpe" id="trial_typpe"
        value="@if ($trialType == 1)
        Hockey Player @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial List

        @elseif ($trialType == 2)
        Hockey Goalkeeper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 3)
        Badminton @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 4)
        Volleyball @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 5)
        Wrestling @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 6)
        Swimming @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 7)
        Football Goalkeeper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 8)
        Football Player @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 9)
        Athletics Jumper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 10)
        Cricket Batsman @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 11)
        Cricket Bowler @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 12)
        Cricket Wicketkeeper @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 13)
        Kabaddi @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 14)
        Judo @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 15)
        Athletics Runner @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 16)
        Gymnastic Male Trial  List

        @elseif ($trialType == 17)
        Gymnastic Female Trial  List

        @elseif ($trialType == 18)
        Trial  List

        @elseif ($trialType == 19)
        Athletics Thrower @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 21   )
        Boxing @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 21)
        Basketball @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 22)
        Table Tennis @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 23)
        Handball @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 24)
        Archery @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List

        @elseif ($trialType == 31)
        Weightlifting @if (isset($filterData)) @if ($filterData['gender'] == 1) Male @elseif ($filterData['gender'] == 2) Female @endif @endif Trial  List


        @endif">

@endsection
@push('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/xlsx.full.min.js') }}"></script>

    <script>
        function sporttype(sport) {
            var subsports_id = $("#sport").attr('data-subsport');
            var gender = $("#sport").attr('data-gender');


            $.ajax({
                type: "POST",
                url: "{{ url('collegeadmin/get_subsport') }}",
                data: {
                    sport
                },
                success: function(response) {

                    var d = $('select[name="subsport"]').empty();
                    $('select[name="subsport"]').append(
                        '<option value="">Select Sub Sport</option>');
                    $.each(response.sub_type, function(key, value) {
                        $('select[name="subsport"]').append(
                            `<option  ${value.id==subsports_id?'selected':''} value="${value.id}"> ${value.sub_type} </option>`
                            );
                    });



                    if (response.gender == 3) {
                        var c = $('select[name="gender"]').empty();
                        $('select[name="gender"]').append(
                            '<option value="">Select Gender</option>');
                        $('select[name="gender"]').append(
                            `<option value="1" ${gender == 1 ?'selected':''} >Male</option>`);
                        $('select[name="gender"]').append(
                            `<option value="2" ${gender == 2 ?'selected':''} >Female</option>`);
                    } else if (response.gender == 1) {
                        var c = $('select[name="gender"]').empty();
                        $('select[name="gender"]').append(
                            '<option value="">Select Gender</option>');
                        $('select[name="gender"]').append(
                            `<option value="1" ${gender == 1 ?'selected':''}>Male</option>`);
                    } else if (response.gender == 2) {
                        var c = $('select[name="gender"]').empty();
                        $('select[name="gender"]').append(
                            '<option value="">Select Gender</option>');
                        $('select[name="gender"]').append(
                            `<option value="2" ${gender == 2 ?'selected':''} >Female</option>`);
                    } else if (response.gender == 4) {
                        var c = $('select[name="gender"]').empty();
                        $('select[name="gender"]').append(
                            '<option value="">Select Gender</option>');
                        $('select[name="gender"]').append(
                            `<option value="1" ${gender == 1 ?'selected':''} >Male</option>`);
                        $('select[name="gender"]').append(
                            `<option value="2" ${gender == 2 ?'selected':''} >Female</option>`);
                    } else {
                        var c = $('select[name="gender"]').empty();
                        $('select[name="gender"]').append(
                            '<option value="">Select Gender</option>');
                    };
                    // }
                    // if (response.gender == 3) {
                    // 	$( 'select[name="gender"]' ).append(
                    // 	'<option value="">Select Gender</option>
                    // 	<option value="3">Male & Female</option>' );
                    // } else if (response.gender == 1) {
                    // 	$( 'select[name="gender"]' ).append(
                    // 	'<option value="">Select Gender</option>
                    // 	<option value="1">Male</option>' );
                    //     }
                    // 	else if (response.gender == 2) {
                    // 	$( 'select[name="gender"]' ).append(
                    // 	'<option value="">Select Gender</option>
                    // 	<option value="2">Female</option>' );
                    //     }
                    // 	else if (response.gender == 4) {
                    // 	$( 'select[name="gender"]' ).append(
                    // 	'<option value="">Select Gender</option>
                    // 	<option value="1">Male</option>
                    // 	<option value="2">Female</option>' );
                    //     };


                }
            })
        }


        // function hundred_mt_score(application_no, gender, age) {


        //     var sportt = $(`#sport`).val();


        //     allAdd(application_no)
        // }





        // function eight_mt_score(application_no, gender, age) {

        //     var sportt = $(`#sport`).val();
        //     eighttime_new = parseFloat(+$(`#eighttime${application_no}`).val());


        //     allAdd(application_no)
        // }





        // function jumpdist_score(application_no, gender, age) {



        //     jumpdist = $(`#jumpdist${application_no}`).val()
        //     var sportt = $(`#sport`).val();

        //     allAdd(application_no)
        // }





        // function shuttletime_score(application_no, gender, age) {

        //     var sportt = $(`#sport`).val();
        //     shuttletime = $(`#shuttletime${application_no}`).val();

        //     allAdd(application_no)
        // }







        // function balldist_score(application_no, gender, age) {

        //     var sportt = $(`#sport`).val();
        //     balldist = $(`#balldist${application_no}`).val()
        //     allAdd(application_no)


        // }
        function hundred_mt_score(application_no, gender, class_no) {
            const value = parseFloat($(`#hundtime${application_no}`).val()) || 0;
            const classGroup = class_no;
            const genderKey = gender == 1 || gender == 'Male' ? 'boys' : 'girls';
            const score = getScoreFromRanges(
                value,
                scoreChart[genderKey][classGroup].hundred,
                true
            );

            $(`#hund${application_no}`).val(score);

            allAdd(application_no);
        }

        function eight_mt_score(application_no, gender, class_no) {
            const value = parseFloat($(`#eighttime${application_no}`).val()) || 0;
            const classGroup = class_no;
            const genderKey = gender == 1 || gender == 'Male' ? 'boys' : 'girls';
            const score = getScoreFromRanges(
                value,
                scoreChart[genderKey][classGroup].eight,
                true
            );

            $(`#eight${application_no}`).val(score);

            allAdd(application_no);
        }

        function jumpdist_score(application_no, gender, class_no) {
            const value = parseFloat($(`#jumpdist${application_no}`).val()) || 0;
            const classGroup = class_no;
            const genderKey = gender == 1 || gender == 'Male' ? 'boys' : 'girls';

            const score = getScoreFromRanges(
                value,
                scoreChart[genderKey][classGroup].jump
            );

            $(`#jump${application_no}`).val(score);

            allAdd(application_no);
        }

        function shuttletime_score(application_no, gender, class_no) {
            const value = parseFloat($(`#shuttletime${application_no}`).val()) || 0;
            const classGroup = class_no;
            const genderKey = gender == 1 || gender == 'Male' ? 'boys' : 'girls';

            const score = getScoreFromRanges(
                value,
                scoreChart[genderKey][classGroup].shuttle,
                true
            );

            $(`#shuttle${application_no}`).val(score);

            allAdd(application_no);
        }

        function balldist_score(application_no, gender, class_no) {
            const value = parseFloat($(`#balldist${application_no}`).val()) || 0;
            const classGroup = class_no;
            const genderKey = gender == 1 || gender == 'Male' ? 'boys' : 'girls';

            const score = getScoreFromRanges(
                value,
                scoreChart[genderKey][classGroup].ball
            );

            $(`#ball${application_no}`).val(score);

            allAdd(application_no);
        }

        function allAdd(application_no) {
            const hundred = parseFloat($(`#hund${application_no}`).val()) || 0;
            const eight = parseFloat($(`#eight${application_no}`).val()) || 0;
            const jump = parseFloat($(`#jump${application_no}`).val()) || 0;
            const shuttle = parseFloat($(`#shuttle${application_no}`).val()) || 0;
            const ball = parseFloat($(`#ball${application_no}`).val()) || 0;

            const total = hundred + eight + jump + shuttle + ball;

            $(`#phy${application_no}`).val(total);
        }

        function hockeyskillTotal(application_no) {

            const hit =
                parseFloat($(`#hit_mark${application_no}`).val()) || 0;

            const push =
                parseFloat($(`#push_mark${application_no}`).val()) || 0;

            const scoop =
                parseFloat($(`#scoop_mark${application_no}`).val()) || 0;

            const dribbling =
                parseFloat($(`#dribbling_mark${application_no}`).val()) || 0;

            const total =
                hit + push + scoop + dribbling;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function hockeykeeperskillTotal(application_no) {

            const kick_mark =
                parseFloat($(`#kick_mark${application_no}`).val()) || 0;

            const pad_mark =
                parseFloat($(`#pad_mark${application_no}`).val()) || 0;

            const stop_mark =
                parseFloat($(`#stop_mark${application_no}`).val()) || 0;

            const high_push_mark =
                parseFloat($(`#high_push_mark${application_no}`).val()) || 0;

            const himmat_mark =
                parseFloat($(`#himmat_mark${application_no}`).val()) || 0;

            const total =
                kick_mark + pad_mark + stop_mark + high_push_mark + himmat_mark;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function badskillTotal(application_no) {

            const highDoubleService =
                parseFloat($(`#high_double_service_mark${application_no}`).val()) || 0;

            const smash =
                parseFloat($(`#smash_mark${application_no}`).val()) || 0;

            const drop =
                parseFloat($(`#drop_mark${application_no}`).val()) || 0;

            const backhand =
                parseFloat($(`#backhand_mark${application_no}`).val()) || 0;

            const total =
                highDoubleService +
                smash +
                drop +
                backhand;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function volleyballskillTotal(application_no) {

            const underHand =
                parseFloat($(`#under_hand_mark${application_no}`).val()) || 0;

            const upperHand =
                parseFloat($(`#upper_hand_mark${application_no}`).val()) || 0;

            const service =
                parseFloat($(`#service_mark${application_no}`).val()) || 0;

            const smash =
                parseFloat($(`#smash_mark${application_no}`).val()) || 0;

            const total =
                underHand +
                upperHand +
                service +
                smash;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function kustiskillTotal(application_no) {

            const groundPosition =
                parseFloat($(`#ground_position_mark${application_no}`).val()) || 0;

            const frontBackPosition =
                parseFloat($(`#front_position_back_position_mark${application_no}`).val()) || 0;

            const total =
                groundPosition +
                frontBackPosition;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function swimmingskillTotal(application_no) {

            const freeStroke =
                parseFloat($(`#free_stroke_mark${application_no}`).val()) || 0;

            const backStroke =
                parseFloat($(`#back_stroke_mark${application_no}`).val()) || 0;

            const breastStroke =
                parseFloat($(`#breast_stroke_mark${application_no}`).val()) || 0;

            const butterFly =
                parseFloat($(`#butter_fly_mark${application_no}`).val()) || 0;

            const glaiding =
                parseFloat($(`#glaiding_mark${application_no}`).val()) || 0;

            const start =
                parseFloat($(`#start_mark${application_no}`).val()) || 0;

            const total =
                freeStroke +
                backStroke +
                breastStroke +
                butterFly +
                glaiding +
                start;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function footballkeeperskillTotal(application_no) {

            const grip =
                parseFloat($(`#grip_mark${application_no}`).val()) || 0;

            const dive =
                parseFloat($(`#dive_mark${application_no}`).val()) || 0;

            const patch =
                parseFloat($(`#patch_mark${application_no}`).val()) || 0;

            const kick =
                parseFloat($(`#kick_mark${application_no}`).val()) || 0;

            const total =
                grip +
                dive +
                patch +
                kick;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function footballskillTotal(application_no) {

            const kick =
                parseFloat($(`#kick_mark${application_no}`).val()) || 0;

            const dribbleTackle =
                parseFloat($(`#dribble_tackle_mark${application_no}`).val()) || 0;

            const head =
                parseFloat($(`#head_mark${application_no}`).val()) || 0;

            const controlPad =
                parseFloat($(`#control_pad_mark${application_no}`).val()) || 0;

            const total =
                kick +
                dribbleTackle +
                head +
                controlPad;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function athleticsrunnerskillTotal(application_no) {

            const stance =
                parseFloat($(`#stance_mark${application_no}`).val()) || 0;

            const start =
                parseFloat($(`#start_mark${application_no}`).val()) || 0;

            const action =
                parseFloat($(`#action_mark${application_no}`).val()) || 0;

            const finish =
                parseFloat($(`#finish_mark${application_no}`).val()) || 0;

            const total =
                stance +
                start +
                action +
                finish;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function athleticsjumperskillTotal(application_no) {

            const approach =
                parseFloat($(`#approach_mark${application_no}`).val()) || 0;

            const takeoffAction =
                parseFloat($(`#t_a_mark${application_no}`).val()) || 0;

            const action =
                parseFloat($(`#action_mark${application_no}`).val()) || 0;

            const landing =
                parseFloat($(`#landing_mark${application_no}`).val()) || 0;

            const total =
                approach +
                takeoffAction +
                action +
                landing;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function athleticsthrowerskillTotal(application_no) {

            const stance =
                parseFloat($(`#stance_mark${application_no}`).val()) || 0;

            const action =
                parseFloat($(`#action_mark${application_no}`).val()) || 0;

            const execution =
                parseFloat($(`#execution_mark${application_no}`).val()) || 0;

            const followThrow =
                parseFloat($(`#follow_throw_mark${application_no}`).val()) || 0;

            const total =
                stance +
                action +
                execution +
                followThrow;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function cricketbatsmanskillTotal(application_no) {

            const gripStanceBacklift =
                parseFloat($(`#grip_stance_backlift_mark${application_no}`).val()) || 0;

            const ballSelect =
                parseFloat($(`#ball_select_mark${application_no}`).val()) || 0;

            const frontFootBackFoot =
                parseFloat($(`#front_foot_back_foot_mark${application_no}`).val()) || 0;

            const frontFootBackFootDrive =
                parseFloat($(`#front_foot_back_foot_drive_mark${application_no}`).val()) || 0;

            const total =
                gripStanceBacklift +
                ballSelect +
                frontFootBackFoot +
                frontFootBackFootDrive;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function cricketballerskillTotal(application_no) {

            const runupActionFollowthrough =
                parseFloat($(`#runup_action_followthrough_mark${application_no}`).val()) || 0;

            const swingSpin =
                parseFloat($(`#swing_spin_mark${application_no}`).val()) || 0;

            const lineLength =
                parseFloat($(`#line_length_mark${application_no}`).val()) || 0;

            const speedFlight =
                parseFloat($(`#speed_flight_mark${application_no}`).val()) || 0;

            const total =
                runupActionFollowthrough +
                swingSpin +
                lineLength +
                speedFlight;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function cricketkeeperskillTotal(application_no) {

            const stumping =
                parseFloat($(`#stumping_mark${application_no}`).val()) || 0;

            const gathering =
                parseFloat($(`#gathering_mark${application_no}`).val()) || 0;

            const offStumpingGathering =
                parseFloat($(`#off_stumping_gathering_mark${application_no}`).val()) || 0;

            const onStumpingGathering =
                parseFloat($(`#on_stumping_gathering_mark${application_no}`).val()) || 0;

            const total =
                stumping +
                gathering +
                offStumpingGathering +
                onStumpingGathering;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function kabaddiskillTotal(application_no) {

            const raid =
                parseFloat($(`#raid_mark${application_no}`).val()) || 0;

            const kickSkill =
                parseFloat($(`#kick_skill_mark${application_no}`).val()) || 0;

            const covering =
                parseFloat($(`#covering_mark${application_no}`).val()) || 0;

            const pakad =
                parseFloat($(`#pakad_mark${application_no}`).val()) || 0;

            const total =
                raid +
                kickSkill +
                covering +
                pakad;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function judoskillTotal(application_no) {

            const straightWorkThrow =
                parseFloat($(`#straight_work_throw_mark${application_no}`).val()) || 0;

            const hipLegHandTechnique =
                parseFloat($(`#hip_leg_hand_techniquec_mark${application_no}`).val()) || 0;

            const throwCount =
                parseFloat($(`#throw_count_mark${application_no}`).val()) || 0;

            const throwCombination =
                parseFloat($(`#throw_combination_mark${application_no}`).val()) || 0;

            const total =
                straightWorkThrow +
                hipLegHandTechnique +
                throwCount +
                throwCombination;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function gymnasticboysskillTotal(application_no) {

            const floorExercise =
                parseFloat($(`#floor_exercise_mark${application_no}`).val()) || 0;

            const pommelHorse =
                parseFloat($(`#pommel_horse_mark${application_no}`).val()) || 0;

            const ring =
                parseFloat($(`#ring_mark${application_no}`).val()) || 0;

            const vaulvingHorse =
                parseFloat($(`#vaulving_horse_mark${application_no}`).val()) || 0;

            const parallelBar =
                parseFloat($(`#parallel_bar_mark${application_no}`).val()) || 0;

            const horizontalBar =
                parseFloat($(`#horizontal_bar_mark${application_no}`).val()) || 0;

            const total =
                floorExercise +
                pommelHorse +
                ring +
                vaulvingHorse +
                parallelBar +
                horizontalBar;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function gymnasticgirlsskillTotal(application_no) {

            const balancingBeam =
                parseFloat($(`#balancing_beam_mark${application_no}`).val()) || 0;

            const unevenBar =
                parseFloat($(`#uneven_bar_mark${application_no}`).val()) || 0;

            const floorExercise =
                parseFloat($(`#floor_exercise_mark${application_no}`).val()) || 0;

            const vaulvingHorse =
                parseFloat($(`#vaulving_horse_mark${application_no}`).val()) || 0;

            const total =
                balancingBeam +
                unevenBar +
                floorExercise +
                vaulvingHorse;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function boxskillTotal(application_no) {

            const punchingPad =
                parseFloat($(`#punching_pad_mark${application_no}`).val()) || 0;

            const shadowBoxing =
                parseFloat($(`#shadow_boxing_mark${application_no}`).val()) || 0;

            const skypink =
                parseFloat($(`#skypink_mark${application_no}`).val()) || 0;

            const sparring =
                parseFloat($(`#sparring_mark${application_no}`).val()) || 0;

            const total =
                punchingPad +
                shadowBoxing +
                skypink +
                sparring;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function handballskillTotal(application_no) {

            const catchPass =
                parseFloat($(`#catch_pass_mark${application_no}`).val()) || 0;

            const dribbling =
                parseFloat($(`#dribbling_mark${application_no}`).val()) || 0;

            const standingShot =
                parseFloat($(`#standing_shot_mark${application_no}`).val()) || 0;

            const jumpShot =
                parseFloat($(`#jumpshot_mark${application_no}`).val()) || 0;

            const total =
                catchPass +
                dribbling +
                standingShot +
                jumpShot;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function weightskillTotal(application_no) {

            const snatch =
                parseFloat($(`#snatch_mark${application_no}`).val()) || 0;

            const powerSnatch =
                parseFloat($(`#power_snatch_mark${application_no}`).val()) || 0;

            const cleanAndJerk =
                parseFloat($(`#clean_and_jerk_mark${application_no}`).val()) || 0;

            const powerCleanPushJerk =
                parseFloat($(`#power_clean_push_jerk_mark${application_no}`).val()) || 0;

            const total =
                snatch +
                powerSnatch +
                cleanAndJerk +
                powerCleanPushJerk;

            $(`#test_score_mark${application_no}`).val(total);

            mainTotal(application_no);
        }

        function mainTotal(application_no) {

            const physical =
                parseFloat($(`#phy${application_no}`).val()) || 0;

            const testScore =
                parseFloat($(`#test_score_mark${application_no}`).val()) || 0;

            const gameTechnique =
                parseFloat($(`#game_technique${application_no}`).val()) || 0;

            const sportTotal =
                testScore + gameTechnique;

            const grandTotal =
                physical + sportTotal;

            $(`#sport_test_mark${application_no}`).val(sportTotal);

            $(`#total_obtain_mark${application_no}`).val(grandTotal);
        }

        function boxingData(val) {

            if ($(`#checkboxing${val}`).is(':checked')) {

                $(`#boxing_${val}`).trigger('submit');

            }
        }

        $(`.boxingData`).submit(function(e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),

                contentType: false,
                cache: false,
                processData: false,

                success: function(res) {

                    if (res.error == false) {

                        success(res.msg);

                        $(`#punching_pad_mark${res.application_no}`).attr('readonly', true);

                        $(`#shadow_boxing_mark${res.application_no}`).attr('readonly', true);

                        $(`#skypink_mark${res.application_no}`).attr('readonly', true);

                        $(`#sparring_mark${res.application_no}`).attr('readonly', true);

                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);

                        $(`#game_technique${res.application_no}`).attr('readonly', true);

                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);

                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);

                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkbox${res.application_no}`).attr('disabled', true);

                    } else {

                        error(res.msg);

                        $(`#checkboxing${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });

        function handballData(val) {

            if ($(`#checkhandball${val}`).is(':checked')) {

                $(`#handball_${val}`).trigger('submit');

            }
        }

        $(`.handballData`).submit(function(e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),

                contentType: false,
                cache: false,
                processData: false,

                success: function(res) {

                    if (res.error == false) {

                        success(res.msg);

                        $(`#catch_pass_mark${res.application_no}`).attr('readonly', true);

                        $(`#dribbling_mark${res.application_no}`).attr('readonly', true);

                        $(`#standing_shot_mark${res.application_no}`).attr('readonly', true);

                        $(`#jumpshot_mark${res.application_no}`).attr('readonly', true);

                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);

                        $(`#game_technique${res.application_no}`).attr('readonly', true);

                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);

                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);

                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkhandball${res.application_no}`).attr('disabled', true);

                    } else {

                        error(res.msg);

                        $(`#checkhandball${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });

        function weightData(val) {
            if ($(`#checkweight${val}`).is(':checked')) {

                $(`#weight_${val}`).trigger('submit');

            }
        }

        $(`.weightData`).submit(function(e) {

            e.preventDefault();

            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),

                contentType: false,
                cache: false,
                processData: false,

                success: function(res) {

                    if (res.error == false) {

                        success(res.msg);

                        $(`#snatch_mark${res.application_no}`).attr('readonly', true);

                        $(`#power_snatch_mark${res.application_no}`).attr('readonly', true);

                        $(`#clean_and_jerk_mark${res.application_no}`).attr('readonly', true);

                        $(`#power_clean_push_jerk_mark${res.application_no}`).attr('readonly', true);

                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);

                        $(`#game_technique${res.application_no}`).attr('readonly', true);

                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);

                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);

                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkweight${res.application_no}`).attr('disabled', true);

                    } else {

                        error(res.msg);

                        $(`#checkweight${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });

        function badminData(val) {
            if ($(`#checkbad${val}`).is(':checked')) {

                $(`#badmin_${val}`).trigger('submit');

            }


        }

        $(`.badminData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#high_double_service_mark${res.application_no}`).attr('readonly', true);
                        $(`#smash_mark${res.application_no}`).attr('readonly', true);
                        $(`#drop_mark${res.application_no}`).attr('readonly', true);
                        $(`#backhand_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkbad${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkbad${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });



        function athleticsthrowerData(val) {
            if ($(`#checkathleticsthrower${val}`).is(':checked')) {

                $(`#athleticsthrower_${val}`).trigger('submit');

            }


        }

        $(`.athleticsthrowerData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#stance_mark${res.application_no}`).attr('readonly', true);
                        $(`#execution_mark${res.application_no}`).attr('readonly', true);
                        $(`#action_mark${res.application_no}`).attr('readonly', true);
                        $(`#follow_throw_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkathleticsthrower${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkathleticsthrower${res.application_no}`).prop("checked", false);
                    }
                },
            });
        });


        function kustiData(val) {
            if ($(`#checkkusti${val}`).is(':checked')) {

                $(`#kusti_${val}`).trigger('submit');

            }


        }

        $(`.kustiData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#ground_position_mark${res.application_no}`).attr('readonly', true);
                        $(`#front_position_back_position_mark${res.application_no}`).attr('readonly',
                            true);

                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkkusti${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkkusti${res.application_no}`).prop("checked", false);
                    }
                },
            });
        });




        function gymnasticgirlsData(val) {
            if ($(`#checkgymnasticgirls${val}`).is(':checked')) {

                $(`#gymnasticgirls_${val}`).trigger('submit');

            }


        }

        $(`.gymnasticgirlsData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#balancing_beam_mark${res.application_no}`).attr('readonly', true);
                        $(`#uneven_bar_mark${res.application_no}`).attr('readonly', true);
                        $(`#floor_exercise_mark${res.application_no}`).attr('readonly', true);
                        $(`#vaulving_horse_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkgymnasticgirls${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkgymnasticgirls${res.application_no}`).prop("checked", false);
                    }
                },
            });
        });



        function gymnasticboysData(val) {
            if ($(`#checkgymnasticboys${val}`).is(':checked')) {

                $(`#gymnasticboys_${val}`).trigger('submit');

            }


        }

        $(`.gymnasticboysData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#floor_exercise_mark${res.application_no}`).attr('readonly', true);
                        $(`#pommel_horse_mark${res.application_no}`).attr('readonly', true);
                        $(`#ring_mark${res.application_no}`).attr('readonly', true);
                        $(`#vaulving_horse_mark${res.application_no}`).attr('readonly', true);
                        $(`#parallel_bar_mark${res.application_no}`).attr('readonly', true);
                        $(`#horizontal_bar_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkgymnasticboys${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkgymnasticboys${res.application_no}`).prop("checked", false);
                    }
                },
            });
        });



        function judoData(val) {
            if ($(`#checkjudo${val}`).is(':checked')) {

                $(`#judo_${val}`).trigger('submit');

            }


        }

        $(`.judoData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#straight_work_throw_mark${res.application_no}`).attr('readonly', true);
                        $(`#hip_leg_hand_techniquec_mark${res.application_no}`).attr('readonly', true);
                        $(`#throw_count_mark${res.application_no}`).attr('readonly', true);
                        $(`#throw_combination_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkjudo${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkjudo${res.application_no}`).prop("checked", false);
                    }
                },
            });
        });





        function volleyballData(val) {
            if ($(`#checkvolleyball${val}`).is(':checked')) {

                $(`#volleyball_${val}`).trigger('submit');

            }


        }

        $(`.volleyballData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#under_hand_mark${res.application_no}`).attr('readonly', true);
                        $(`#upper_hand_mark${res.application_no}`).attr('readonly', true);
                        $(`#service_mark${res.application_no}`).attr('readonly', true);
                        $(`#smash_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkvolleyball${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkvolleyball${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });





        function footballkeeperData(val) {
            if ($(`#checkfootballkeeper${val}`).is(':checked')) {

                $(`#footballkeeper_${val}`).trigger('submit');

            }


        }

        $(`.footballkeeperData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#grip_mark${res.application_no}`).attr('readonly', true);
                        $(`#dive_mark${res.application_no}`).attr('readonly', true);
                        $(`#patch_mark${res.application_no}`).attr('readonly', true);
                        $(`#kick_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkfootballkeeper${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkfootballkeeper${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });


        function footballData(val) {
            if ($(`#checkfootball${val}`).is(':checked')) {

                $(`#football_${val}`).trigger('submit');

            }


        }

        $(`.footballData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#kick_mark${res.application_no}`).attr('readonly', true);
                        $(`#dribble_tackle_mark${res.application_no}`).attr('readonly', true);
                        $(`#head_mark${res.application_no}`).attr('readonly', true);
                        $(`#control_pad_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkfootball${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkfootball${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });



        function hockeyData(val) {
            if ($(`#checkhockey${val}`).is(':checked')) {

                $(`#hockey_${val}`).trigger('submit');

            }


        }

        $(`.hockeyData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#hit_mark${res.application_no}`).attr('readonly', true);
                        $(`#push_mark${res.application_no}`).attr('readonly', true);
                        $(`#dribbling_mark${res.application_no}`).attr('readonly', true);
                        $(`#scoop_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkhockey${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkhockey${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });


        function kabaddiData(val) {
            if ($(`#checkkabaddi${val}`).is(':checked')) {

                $(`#kabaddi_${val}`).trigger('submit');

            }


        }

        $(`.kabaddiData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#raid_mark${res.application_no}`).attr('readonly', true);
                        $(`#kick_skill_mark${res.application_no}`).attr('readonly', true);
                        $(`#covering_mark${res.application_no}`).attr('readonly', true);
                        $(`#pakad_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkkabaddi${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkkabaddi${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });


        function hockeykeeperData(val) {

            if ($(`#checkhockeykeeper${val}`).is(':checked')) {

                $(`#hockeykeeper_${val}`).trigger('submit');

            }


        }

        $(`.hockeykeeperData`).submit(function(e) {

            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#kick_mark${res.application_no}`).attr('readonly', true);
                        $(`#pad_mark${res.application_no}`).attr('readonly', true);
                        $(`#high_push_mark${res.application_no}`).attr('readonly', true);
                        $(`#stop_mark${res.application_no}`).attr('readonly', true);
                        $(`#himmat_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkhockeykeeper${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkhockeykeeper${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });




        function swimmingData(val) {

            if ($(`#checkswimming${val}`).is(':checked')) {

                $(`#swimming_${val}`).trigger('submit');

            }


        }

        $(`.swimmingData`).submit(function(e) {

            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#free_stroke_mark${res.application_no}`).attr('readonly', true);
                        $(`#back_stroke_mark${res.application_no}`).attr('readonly', true);
                        $(`#breast_stroke_markk${res.application_no}`).attr('readonly', true);
                        $(`#butter_fly_mark${res.application_no}`).attr('readonly', true);
                        $(`#glaiding_mark${res.application_no}`).attr('readonly', true);
                        $(`#start_mark${res.application_no}`).attr('readonly', true);
                        $(`#himmat_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkswimming${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkswimming${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });



        function athleticsrunnerData(val) {
            if ($(`#checkathleticsrunner${val}`).is(':checked')) {

                $(`#athleticsrunner_${val}`).trigger('submit');

            }


        }

        $(`.athleticsrunnerData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#stance_mark${res.application_no}`).attr('readonly', true);
                        $(`#start_mark${res.application_no}`).attr('readonly', true);
                        $(`#action_mark${res.application_no}`).attr('readonly', true);
                        $(`#finish_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkathleticsrunner${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkathleticsrunner${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });



        function athleticsjumperData(val) {
            if ($(`#checkathleticsjumper${val}`).is(':checked')) {

                $(`#athleticsjumper_${val}`).trigger('submit');

            }


        }

        $(`.athleticsjumperData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#approach_mark${res.application_no}`).attr('readonly', true);
                        $(`#t_a_mark${res.application_no}`).attr('readonly', true);
                        $(`#action_mark${res.application_no}`).attr('readonly', true);
                        $(`#landing_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkathleticsjumper${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkathleticsjumper${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });




        function cricketbatsmanData(val) {
            if ($(`#checkcricketbatsman${val}`).is(':checked')) {

                $(`#cricketbatsman_${val}`).trigger('submit');

            }


        }

        $(`.cricketbatsmanData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#grip_stance_backlift_mark${res.application_no}`).attr('readonly', true);
                        $(`#ball_select_mark${res.application_no}`).attr('readonly', true);
                        $(`#front_foot_back_foot_mark${res.application_no}`).attr('readonly', true);
                        $(`#front_foot_back_foot_drive_mark${res.application_no}`).attr('readonly',
                            true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkcricketbatsman${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkcricketbatsman${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });


        function cricketkeeperData(val) {
            if ($(`#checkcricketkeeper${val}`).is(':checked')) {

                $(`#cricketkeeper_${val}`).trigger('submit');

            }


        }

        $(`.cricketkeeperData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#stumping_mark${res.application_no}`).attr('readonly', true);
                        $(`#gathering_mark${res.application_no}`).attr('readonly', true);
                        $(`#off_stumping_gathering_mark${res.application_no}`).attr('readonly', true);
                        $(`#on_stumping_gathering_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkcricketkeeper${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkcricketkeeper${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });





        function cricketballerData(val) {
            if ($(`#checkcricketballer${val}`).is(':checked')) {

                $(`#cricketballer_${val}`).trigger('submit');

            }


        }

        $(`.cricketballerData`).submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);

                        $(`#runup_action_followthrough_mark${res.application_no}`).attr('readonly',
                            true);
                        $(`#swing_spin_mark${res.application_no}`).attr('readonly', true);
                        $(`#line_length_mark${res.application_no}`).attr('readonly', true);
                        $(`#speed_flight_mark${res.application_no}`).attr('readonly', true);
                        $(`#test_score_mark${res.application_no}`).attr('readonly', true);
                        $(`#game_technique${res.application_no}`).attr('readonly', true);
                        $(`#sport_test_mark${res.application_no}`).attr('readonly', true);
                        $(`#total_obtain_mark${res.application_no}`).attr('readonly', true);
                        $(`#remark${res.application_no}`).attr('readonly', true);

                        $(`#checkcricketballer${res.application_no}`).attr('disabled', true);


                    } else {
                        error(res.msg);
                        $(`#checkcricketballer${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });



        function myfunction(val) {
            if ($(`#check${val}`).is(':checked')) {
                var form = '#form_' + val;
                $(form).trigger('submit');

            };




        }
        $(".applicantData").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: new FormData(this),
                //dataType: "json",
                contentType: false,
                cache: false,
                processData: false,
                success: function(res) {
                    if (res.error == false) {


                        success(res.msg);
                        $(`#hund${res.application_no}`).attr('readonly', true);
                        $(`#eight${res.application_no}`).attr('readonly', true);
                        $(`#jump${res.application_no}`).attr('readonly', true);
                        $(`#shuttle${res.application_no}`).attr('readonly', true);
                        $(`#ball${res.application_no}`).attr('readonly', true);
                        $(`#hundtime${res.application_no}`).attr('readonly', true);
                        $(`#eighttime${res.application_no}`).attr('readonly', true);
                        $(`#jumpdist${res.application_no}`).attr('readonly', true);
                        $(`#shuttletime${res.application_no}`).attr('readonly', true);
                        $(`#balldist${res.application_no}`).attr('readonly', true);
                        $(`#check${res.application_no}`).attr('disabled', true);

                    } else {
                        error(res.msg);
                        $(`#check${res.application_no}`).prop("checked", false);

                    }
                },
            });
        });


        function PrintDoc() {

            var toPrint = document.getElementById('prodiv');

            var popupWin = window.open('', '_blank',
                'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

            popupWin.document.open();

            var trial_typpe = $(`#trial_typpe`).val();

            popupWin.document.write(
                `<html><title>${trial_typpe}</title><head><style>body{font-family:Arial} .noprint{display: none;} table{width:100%; border-collapse:collapse;} .table tr th, .table tr td{border:1px solid #000; padding:3px 5px; font-size: 12px; text-align: left;} th.table-warning{background-color: #dbdbdb;} .table-warning h3{margin: 0;}</style></head><body onload="window.print()">`
                )

            popupWin.document.write(toPrint.innerHTML);

            popupWin.document.write('</body></html>');

            popupWin.document.close();

        }




        function ExportToExcell(type, fn, dl) {
            var elt = document.getElementById('dataTablee');
            var wb = XLSX.utils.table_to_book(elt, {
                sheet: "sheet1"
            });
            return dl ?
                XLSX.write(wb, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                }) :
                XLSX.writeFile(wb, fn || ('Trial Report.' + (type || 'xlsx')));
        }
    </script>
@endpush
