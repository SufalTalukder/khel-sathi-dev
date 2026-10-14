@extends('layouts/admin_layout')
@section('content')
    <style>
        .nowraptd {
            white-space: nowrap;
        }

        .dn {
            display: none;
        }

        .hide-panel {
            background: #f7f7f7;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px dashed #ccc;
        }
    </style>

    @php
        $isEdit = isset($ed_data->id);
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="pageheader" id="menu-margin">
                <h4 class="mb-0">मा0 सांसद एवं विधान मण्डल के मा0 सदस्यों के पत्रों पर प्रभावी एवं निश्चित कार्यवाही का
                    प्रभावी अनुश्रवण करने विषयक सूचना
                    <a title="Print" class="btn btn btn-outline-success float-end" data-print="modal" onclick="PrintDoc()"><i
                            class="icons icon-printer"></i> प्रिंट</a>
                    <button onclick="PrintExce('Information Honorable')"
                        class="btn btn btn-outline-primary float-end">Excel</button>
                </h4>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="letter_quant" value="0">
                    @if (Auth::guard('admin')->user()->admin_role != 1 && Auth::guard('admin')->user()->admin_role != 18)
                    <form action="{{ url('admin/information/information_honorable') }}" class="needs-validation"
                        id="reload_two" novalidate method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="row">

                                    @if (isset($ed_data->id))
                                        <input type="hidden" value="{{ $ed_data->id }}" name="id">
                                    @endif
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="placeholder" for="year">वर्ष <span class="text-danger">*</span></label>
                                            <select name="year" required class="form-control form-select">
                                                <option value=''>--select--</option>
                                                @for ($i = date('Y'); $i >= 2025; $i--)
                                                    {
                                                    <option @if (isset($ed_data->year) && $ed_data->year == $i) selected @endif
                                                        value={{ $i }}>{{ $i }}</option>
                                                    }
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label class="placeholder" for="month_name">माह <span class="text-danger">*</span></label>
                                            <select name="month_name" id="month_name" class="form-control form-select"
                                                required>
                                                <option value=''>--select--</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 1) selected @endif value='1'>
                                                    Janaury</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 2) selected @endif value='2'>
                                                    February</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 3) selected @endif value='3'>
                                                    March</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 4) selected @endif value='4'>
                                                    April</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 5) selected @endif value='5'>May
                                                </option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 6) selected @endif value='6'>
                                                    June</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 7) selected @endif value='7'>
                                                    July</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 8) selected @endif value='8'>
                                                    August</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 9) selected @endif value='9'>
                                                    September</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 10) selected @endif value='10'>
                                                    October</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 11) selected @endif value='11'>
                                                    November</option>
                                                <option @if (isset($ed_data->month_name) && $ed_data->month_name == 12) selected @endif value='12'>
                                                    December</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            @if(Auth::guard('admin')->user()->admin_role!=9)
                                <div class="col-md-3 mb-3">
                                    <div class="form-group">
                                        <label class="form-label" for="district_name">जनपद का नाम <span class="text-danger">*</span></label>
                                        <select name="district_name" required id="district_name"
                                            class="form-control form-select">
                                            <option value="">--select--</option>
                                            @foreach ($districts as $key => $district)
                                                <option value="{{ $district->id }}"
                                                    @if (isset($ed_data->district_id) && $ed_data->district_id == $district->id) selected @endif>
                                                    {{ $district->city }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="placeholder" for="letter_received">क्या वर्तमान माह मे कोई पत्र प्राप्त हुए हैं?<span
                                        class="text-danger">*</span></label>
                                    <select name="letter_received" required id="letter_received"
                                        class="form-control form-select">
                                        <option value="">--select--</option>
                                        <option value="1">हाँ</option>
                                        <option value="2">नहीं</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 d-none" id="letter_count">
                                <div class="form-group mb-3">
                                    <label class="placeholder" for="letter_quant">प्राप्त पत्रों की संख्या<span
                                        class="text-danger">*</span></label>
                                    <input class="form-control" type="number" name="letter_quant" id="letter_quant">
                                </div>
                            </div>
                            <div class="container-fluid d-none" id="information_template">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="s_no">S.No.</label>
                                            <input class="form-control" type="text" name="s_no" id="s_no" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="placeholder" for="name_of_honorable">मा. सांसद/विधान मण्डल सदस्य का नाम <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="name_of_honorable" pattern="^[A-Za-z -]+$" maxlength="150"
                                                name="name_of_honorable[]"
                                                @if (isset($ed_data->name_of_honorable)) value="{{ $ed_data->name_of_honorable }}" @endif
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-3">
                                            <label class="placeholder" for="date_of_receipt_of_letter">पत्र प्राप्त होने की तिथि </label>
                                            <input type="date" id="date_of_receipt_of_letter" name="date_of_receipt_of_letter[]"
                                                @if (isset($ed_data->date_of_receipt_of_letter)) value="{{ ymd($ed_data->date_of_receipt_of_letter) }}" @endif
                                                class="form-control " data-language="en">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="placeholder" for="subject_of_letter">पत्र का विषय <span class="text-danger">*</span></label>
                                            <input type="text" id="subject_of_letter" pattern="^[A-Za-z -]+$" maxlength="255"
                                                name="subject_of_letter[]"
                                                @if (isset($ed_data->subject_of_letter)) value="{{ $ed_data->subject_of_letter }}" @endif
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="placeholder" for="details_of_action_taken">कृत कार्यवाही का विवरण <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" id="details_of_action_taken" pattern="^[A-Za-z -]+$"
                                                maxlength="255" name="details_of_action_taken[]"
                                                @if (isset($ed_data->details_of_action_taken)) value="{{ $ed_data->details_of_action_taken }}" @endif
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="placeholder" for="date_of_informing_honble_mp">मा. सदस्य को कृत कार्यवाही से अवगत कराने की तिथि
                                            </label>
                                            <input type="date" id="date_of_informing_honble_mp"
                                                name="date_of_informing_honble_mp[]"
                                                @if (isset($ed_data->date_of_informing_honble_mp)) value="{{ ymd($ed_data->date_of_informing_honble_mp) }}" @endif
                                                class="form-control " data-language="en">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label class="placeholder" for="honble_mp_action_taken">मा. सदस्य को कृत कार्यवाही से अवगत न कराये जाने का
                                                कारण<span class="text-danger">*</span></label>
                                            <input type="text" id="honble_mp_action_taken" pattern="^[A-Za-z -]+$"
                                                maxlength="255" name="honble_mp_action_taken[]"
                                                @if (isset($ed_data->honble_mp_action_taken)) value="{{ $ed_data->honble_mp_action_taken }}" @endif
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="information_containers" class="container-fluid"></div>
                        </div>
                        <div class="bhoechie-footer">
                            <div class="row justify-content-center">
                                <div class="col-md-2 d-grid">
                                    <button id="reset" type="reset"
                                        class="btn btn-outline-danger rounded-pill">रीसेट</button>
                                </div>
                                <div class="col-md-2 d-grid">
                                    <button type="submit" class="btn  btn-outline-info rounded-pill">सुनिश्चित
                                        करे</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif

                    @if (!$isEdit)
                        <div class="container removebg-color"
                            style="background: #f0e7eb;padding: 10px;margin-bottom: 10px;">
                            <form action="{{ url('admin/information/information_honorable') }}" class="needs-validation"
                                method="get" novalidate autocomplete="off">
                                <div class="pageheaderr">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label class="form-label" style="margin-bottom: 0;" for="division_name">Division</label>
                                            <select name="division_name" id="division_name"
                                                class="form-control form-select">
                                                <option value="">--select--</option>
                                                @foreach ($division as $key => $division)
                                                    <option
                                                        {{ $division->id == request()->input('division_name') ? 'selected' : '' }}
                                                        value="{{ $division->id }}" data-badge="">
                                                        {{ $division->division_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" style="margin-bottom: 0;" for="district_name">District</label>
                                            <input type="hidden" id="district_id"
                                                value="{{ request()->input('district_name') }}">
                                            <select name="district_name" id="district_name"
                                                class="form-control form-select">
                                                <option value="">--select--</option>
                                                @foreach ($districts as $key => $district)
                                                    <option
                                                        {{ $district->id == request()->input('district_name') ? 'selected' : '' }}
                                                        value="{{ $district->id }}" data-badge="">{{ $district->city }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if(Auth::guard('admin')->user()->admin_role == 1)
                                            <div class="col-md-2">
                                                <input type="radio" name="unavailable" id="unavailable" class="form-radio mt-2">
                                                <label class="form-label" style="margin-bottom: 0;" for="unavailable">Unavailable</label>
                                            </div>
                                        @endif
                                        <div class="col-md-2">
                                            <label class="form-label" style="margin-bottom: 0;" for="from_date">From Date</label>
                                            <input type="month" name="from_date" class="form-control" id="from_date"
                                                value="{{ $from_date ?? '' }}" />
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label" style="margin-bottom: 0;" for="to_date">To Date</label>
                                            <input type="month" name="to_date" class="form-control" id="to_date"
                                                value="{{ $to_date ?? '' }}" />
                                        </div>
                                        {{-- <div class="col-md-2">
                                            <label for="not filled" class="form-label">Not Filled</label>

                                        </div> --}}
                                        <!-- <div class="col-md-3">
                                                                                                                                        <label class="form-label" style="margin-bottom: 0;">From Date</label>
                                                                                                                                    <input type="text" name="from_date" id="from_date" value="{{ request()->input('from_date') }}" placeholder="DD/MM/YYYY" class="form-control dateTime" />
                                                                                                                                    </div>
                                                                                                                                    <div class="col-md-3">
                                                                                                                                        <label class="form-label" style="margin-bottom: 0;">To Date</label>
                                                                                                                                        <input type="text" name="to_date" id="to_date" value="{{ request()->input('to_date') }}" placeholder="DD/MM/YYYY" class="form-control dateTime" />
                                                                                                                                    </div> -->
                                        <div class="col-md-1 custom-buton" style="padding-top: 1.4rem;">
                                            <button class="btn btn-primary btn-sm" type="submit" title="Search">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-1 custom-buton" style="padding-top: 1.4rem;">
                                            <a class="btn btn-primary btn-sm"
                                                href="{{ url('admin/information/information_honorable') }}"><i
                                                    class="fas fa-refresh"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive" id="prodiv">
                                    <table border="0" cellspacing="0" cellpadding="4" width="100%"
                                        style="border-collapse:collapse;">
                                        <thead class="dn">
                                            <tr>
                                                <th colspan="2">
                                                    <div
                                                        style="padding: 0 15px 3px; margin-bottom: 10px;  position: relative;">
                                                        {{-- <img src="{{url('admin/images/logo.png')}}" style="width: 75px; height: auto; position: absolute; top: 0px; left: 20px;"> --}}
                                                        <h1
                                                            style="text-align: center; font-size: 20pt; margin: 0px 0px 0px 0px; padding: 0px 0 0; color: #383838; font-weight: bold;">
                                                            खेल विभाग,उत्तर प्रदेश सरकार
                                                        </h1>
                                                        <h2
                                                            style="text-align: center; margin:0px 0px 0px 0px; font-size:14pt; padding: 0px; color:#383838; font-weight: bold;">
                                                            मा0 सांसद एवं विधान मण्डल के मा0 सदस्यों के पत्रों पर प्रभावी
                                                            एवं
                                                            निश्चित कार्यवाही का प्रभावी अनुश्रवण करने विषयक सूचना
                                                        </h2>
                                                    </div>
                                                    {{-- <h6 style="text-align: center; margin:10px 0px 15px 0px; font-size:12pt; padding: 0px; color:#383838; font-weight: bold; text-decoration:underline;">
                                                विभागाध्यक्ष- निदेषक, खेल, खेल निदेषालय, उ0प्र0, लखनऊ
                                            </h6> --}}
                                                </th>
                                            </tr>
                                            <tr>
                                                <th style="font-size: 10pt; text-align: left;">
                                                    <?php
                                                        if(request()->input('from_date')!=null){
                                                            $fromDateParts = explode('-', request()->input('from_date'));
                                                            $fromYear = $fromDateParts[0];
                                                            $fromMonth = (int) $fromDateParts[1];
                                                        }
                                                        if(request()->input('to_date')!=null){
                                                            $toDateParts = explode('-', request()->input('to_date'));
                                                            $toYear = $toDateParts[0];
                                                            $toMonth = (int) $toDateParts[1];
                                                        }
                                                    ?>
                                                    <b>Report Period :</b>
                                                    @if(isset($pending_districts))
                                                        {{isset($toMonth)? month_name($toMonth) : month_name(date('m'))}}, {{ isset($toYear) ? $toYear : date('Y') }}
                                                    @else
                                                        {{ isset($fromMonth) ? month_name($fromMonth) : month_name(date('m')) }}
                                                        -
                                                        {{ isset($fromYear) ? $fromYear : date('Y') }} to
                                                        {{ isset($toMonth) ? month_name($toMonth) : month_name(date('m')) }} -
                                                        {{ isset($toYear) ? $toYear : date('Y') }}
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="2">
                                                    @if(isset($pending_districts))
                                                        <h4 style="text-align: center; margin:0px 0px 0px 0px; font-size:14pt; padding: 0px; color:#383838; font-weight: bold;">
                                                            जनपद जिनके द्वारा उक्त माह की आख्या नहीं भरी गई है।
                                                        </h4>
                                                    @endif
                                                    <div class="table-responsive">
                                                        <table
                                                            class="table table-bordered table-hover bg-white datatable mb-3"
                                                            id="dataTable" cellpadding="3">
                                                            <thead>
                                                                @if(isset($pending_districts))
                                                                    <tr>
                                                                        <th>क्र.सं.</th>
                                                                        <th>मण्डल का नाम</th>
                                                                        <th>जनपद का नाम</th>
                                                                    </tr>
                                                                @else
                                                                    <tr role="row">
                                                                        <th>क्र.सं.</th>
                                                                        <th>मण्डल का नाम</th>
                                                                        <th>जनपद का नाम</th>
                                                                        <th>माह</th>
                                                                        <th>वर्ष</th>
                                                                        <th>मा. सांसद/विधान मण्डल सदस्य का नाम</th>
                                                                        <th>पत्र प्राप्त होने की तिथि</th>
                                                                        <th>पत्र का विषय</th>
                                                                        <th>कृत कार्यवाही का विवरण</th>
                                                                        <th>मा. सदस्य को कृत कार्यवाही से अवगत कराने की तिथि
                                                                        </th>
                                                                        <th>मा. सदस्य को कृत कार्यवाही से अवगत न कराये जाने का
                                                                            कारण
                                                                        </th>
                                                                        <th>जमा करने की तिथि</th>
                                                                        <th class="noprint">Action</th>
                                                                    </tr>
                                                                @endif
                                                            </thead>
                                                            <tbody>
                                                                @if(isset($pending_districts))
                                                                    @php $i=1 @endphp
                                                                    @foreach($pending_districts as $item)
                                                                        <tr>
                                                                            <td>{{$i++}}</td>
                                                                            <td>{{$item->division_name}}</td>
                                                                            <td>{{$item->city}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                @else
                                                                    @foreach ($information_honorable as $key => $item)
                                                                        @if($item->data<=1)
                                                                            @if($item->data==0)
                                                                                <tr>
                                                                                    <td>{{ $key + 1 }}</td>
                                                                                    <td>
                                                                                        <?php $division_id = DB::table('hostel_div_district_mapping')->where('district_id', $item->district_id)->first(); ?>
                                                                                        @if ($division_id)
                                                                                            {{ divisionName($division_id->division_id) }}
                                                                                        @else
                                                                                            NA
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ districtName($item->district_id) }}</td>
                                                                                    <td>{{ month_name($item->month_name) }}</td>
                                                                                    <td>{{ $item->year }}</td>
                                                                                    <td colspan=6>उक्त माह मे कोई पत्र प्राप्त नहीं हुए।</td>
                                                                                    <td>
                                                                                        {{ date('d-m-Y', strtotime(isset($item->created_at))) ? date('d-m-Y', strtotime($item->created_at)) : '-' }}
                                                                                    </td>
                                                                            @elseif($item->data==1)
                                                                                <tr>
                                                                                    <td>{{ $key + 1 }}</td>
                                                                                    <td>
                                                                                        <?php $division_id = DB::table('hostel_div_district_mapping')->where('district_id', $item->district_id)->first(); ?>
                                                                                        @if ($division_id)
                                                                                            {{ divisionName($division_id->division_id) }}
                                                                                        @else
                                                                                            NA
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ districtName($item->district_id) }}</td>
                                                                                    <td>{{ month_name($item->month_name) }}</td>
                                                                                    <td>{{ $item->year }}</td>
                                                                                    <td>{{ repairHindi($item->name_of_honorable) }}</td>
                                                                                    <td>
                                                                                        @if ($item->date_of_receipt_of_letter)
                                                                                            {{ dmy($item->date_of_receipt_of_letter) }}
                                                                                        @else
                                                                                            NA
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ repairHindi($item->subject_of_letter) }}</td>
                                                                                    <td>{{ repairHindi($item->details_of_action_taken) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($item->date_of_informing_honble_mp)
                                                                                            {{ dmy($item->date_of_informing_honble_mp) }}
                                                                                        @else
                                                                                            NA
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ repairHindi($item->honble_mp_action_taken) }}
                                                                                    </td>
                                                                                    <td>
                                                                                        {{ date('d-m-Y', strtotime(isset($item->created_at))) ? date('d-m-Y', strtotime($item->created_at)) : '-' }}
                                                                                    </td>
                                                                            @endif
                                                                            <td class="text-center noprint">
                                                                                @php

                                                                                    $adminRole = Auth::guard('admin')->user()
                                                                                        ->admin_role;

                                                                                    $scheduledDate = DB::table(
                                                                                        'auto_lock_master',
                                                                                    )
                                                                                        ->where(
                                                                                            'auto_lock_type',
                                                                                            'information_honorable',
                                                                                        )
                                                                                        ->where('auto_lock_status', 1)
                                                                                        ->value('schedule_auto_lock_date');

                                                                                    $withinScheduleDateOrNot = false;
                                                                                    if (!empty($scheduledDate)) {
                                                                                        $withinScheduleDateOrNot = now()->lte(
                                                                                            \Carbon\Carbon::parse(
                                                                                                $scheduledDate,
                                                                                            )->endOfDay(),
                                                                                        );
                                                                                    }

                                                                                    $isLocked =
                                                                                        isset($item->lock_status) &&
                                                                                        $item->lock_status == 1;
                                                                                @endphp

                                                                                @if ($adminRole == 1)
                                                                                    @if ($isLocked)
                                                                                        {{-- Admin 1: Locked → show Unlock --}}
                                                                                        <a class="btn btn-sm btn-dark"
                                                                                            href="{{ route('information_honorable', $item->id) }}">
                                                                                            <i class="fa fa-edit"></i>
                                                                                        </a>

                                                                                        <a class="btn btn-sm btn-danger"
                                                                                            href="{{ route('delete_inf', [$item->id, 'information_honorable']) }}"
                                                                                            onclick="return confirm('Are you sure you want to delete?')">
                                                                                            <i class="fa fa-trash"></i>
                                                                                        </a>

                                                                                        <a class="btn btn-sm btn-info"
                                                                                            href="{{ route('unlock_information_honorable', [$item->id, 'information_honorable']) }}"
                                                                                            onclick="return confirm('Are you sure you want to unlock?')">
                                                                                            <i class="fa fa-lock"></i>
                                                                                        </a>
                                                                                    @else
                                                                                        {{-- Admin 1: Unlocked → show Edit, Delete, Lock --}}
                                                                                        <a class="btn btn-sm btn-dark"
                                                                                            href="{{ route('information_honorable', $item->id) }}">
                                                                                            <i class="fa fa-edit"></i>
                                                                                        </a>

                                                                                        <a class="btn btn-sm btn-danger"
                                                                                            href="{{ route('delete_inf', [$item->id, 'information_honorable']) }}"
                                                                                            onclick="return confirm('Are you sure you want to delete?')">
                                                                                            <i class="fa fa-trash"></i>
                                                                                        </a>

                                                                                        <a class="btn btn-sm btn-info"
                                                                                            href="{{ route('lock_information_honorable', [$item->id, 'information_honorable']) }}"
                                                                                            onclick="return confirm('Are you sure you want to lock?')">
                                                                                            <i class="fa fa-unlock"></i>
                                                                                        </a>
                                                                                    @endif
                                                                                @elseif ($adminRole == 2 || $adminRole == 9)
                                                                                    @if ($withinScheduleDateOrNot)
                                                                                        @if (!$isLocked)
                                                                                            {{-- Admin 2/9: Within 5 days, unlocked → show Edit, Delete, Lock --}}
                                                                                            <a class="btn btn-sm btn-dark"
                                                                                                href="{{ route('information_honorable', $item->id) }}">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>

                                                                                            <a class="btn btn-sm btn-danger"
                                                                                                href="{{ route('delete_inf', [$item->id, 'information_honorable']) }}"
                                                                                                onclick="return confirm('Are you sure you want to delete?')">
                                                                                                <i class="fa fa-trash"></i>
                                                                                            </a>

                                                                                            <a class="btn btn-sm btn-info"
                                                                                                href="{{ route('lock_information_honorable', [$item->id, 'information_honorable']) }}"
                                                                                                onclick="return confirm('Are you sure you want to lock? Further, data cannot be edited!')">
                                                                                                <i class="fa fa-unlock"></i>
                                                                                            </a>
                                                                                        @else
                                                                                            {{-- Admin 2/9: Within 5 days, but locked → disabled lock --}}
                                                                                            <a class="btn btn-sm btn-info disabled"
                                                                                                href="javascript:void(0)">
                                                                                                <i class="fa fa-lock"></i>
                                                                                            </a>
                                                                                        @endif
                                                                                    @else
                                                                                        {{-- Beyond 5 days, locked or not unlocked by admin 1 → disabled lock --}}
                                                                                        <a class="btn btn-sm btn-info disabled"
                                                                                            href="javascript:void(0)">
                                                                                            <i class="fa fa-lock"></i>
                                                                                        </a>
                                                                                    @endif
                                                                                @else
                                                                                    {{-- All other roles → always disabled lock --}}
                                                                                    <a class="btn btn-sm btn-info disabled"
                                                                                        href="javascript:void(0)">
                                                                                        <i class="fa fa-lock"></i>
                                                                                    </a>
                                                                                @endif
                                                                            </td>
                                                                        </tr>

                                                                        @elseif($item->data>1)
                                                                            @php
                                                                                $names=explode("`",$item->name_of_honorable);
                                                                                $receive_dates=explode("`",$item->date_of_receipt_of_letter);
                                                                                $subjects=explode("`",$item->subject_of_letter);
                                                                                $details=explode("`",$item->details_of_action_taken);
                                                                                $inform_dates=explode("`",$item->date_of_informing_honble_mp);
                                                                                $actions=explode("`",$item->honble_mp_action_taken);

                                                                            @endphp
                                                                            @for($i=0;$i<($item->data);$i++)
                                                                                <tr>
                                                                                @if($i==0)
                                                                                    <td rowspan={{$item->data}}>{{ $key + 1 }}</td>
                                                                                    <td rowspan={{$item->data}}>
                                                                                        <?php $division_id = DB::table('hostel_div_district_mapping')->where('district_id', $item->district_id)->first(); ?>
                                                                                        @if ($division_id)
                                                                                            {{ divisionName($division_id->division_id) }}
                                                                                        @else
                                                                                            NA
                                                                                        @endif
                                                                                    </td>
                                                                                    <td rowspan={{$item->data}}>{{ districtName($item->district_id) }}</td>
                                                                                    <td rowspan={{$item->data}}>{{ month_name($item->month_name) }}</td>
                                                                                    <td rowspan={{$item->data}}>{{ $item->year }}</td>
                                                                                @endif
                                                                                    <td>{{ repairHindi($names[$i])}}</td>
                                                                                    <td>{{ dmy($receive_dates[$i])}}</td>
                                                                                    <td>{{ repairHindi($subjects[$i])}}</td>
                                                                                    <td>{{ repairHindi($details[$i])}}</td>
                                                                                    <td>{{ dmy($inform_dates[$i])}}</td>
                                                                                    <td>{{ repairHindi($actions[$i])}}</td>
                                                                                @if($i==0)
                                                                                    <td rowspan="{{$item->data}}">
                                                                                        {{ date('d-m-Y', strtotime(isset($item->created_at))) ? date('d-m-Y', strtotime($item->created_at)) : '-' }}
                                                                                    </td>
                                                                                    <td rowspan="{{$item->data}}" class="text-center noprint">
                                                                                        @php

                                                                                            $adminRole = Auth::guard('admin')->user()
                                                                                                ->admin_role;

                                                                                            $scheduledDate = DB::table(
                                                                                                'auto_lock_master',
                                                                                            )
                                                                                                ->where(
                                                                                                    'auto_lock_type',
                                                                                                    'information_honorable',
                                                                                                )
                                                                                                ->where('auto_lock_status', 1)
                                                                                                ->value('schedule_auto_lock_date');

                                                                                            $withinScheduleDateOrNot = false;
                                                                                            if (!empty($scheduledDate)) {
                                                                                                $withinScheduleDateOrNot = now()->lte(
                                                                                                    \Carbon\Carbon::parse(
                                                                                                        $scheduledDate,
                                                                                                    )->endOfDay(),
                                                                                                );
                                                                                            }

                                                                                            $isLocked =
                                                                                                isset($item->lock_status) &&
                                                                                                $item->lock_status == 1;
                                                                                        @endphp

                                                                                        @if ($adminRole == 1)
                                                                                            @if ($isLocked)
                                                                                                {{-- Admin 1: Locked → show Unlock --}}
                                                                                                <a class="btn btn-sm btn-dark"
                                                                                                    href="{{ route('information_honorable', $item->id) }}">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                                </a>

                                                                                                <a class="btn btn-sm btn-danger"
                                                                                                    href="{{ route('delete_inf', [$item->id, 'information_honorable']) }}"
                                                                                                    onclick="return confirm('Are you sure you want to delete?')">
                                                                                                    <i class="fa fa-trash"></i>
                                                                                                </a>

                                                                                                <a class="btn btn-sm btn-info"
                                                                                                    href="{{ route('unlock_information_honorable', [$item->id, 'information_honorable']) }}"
                                                                                                    onclick="return confirm('Are you sure you want to unlock?')">
                                                                                                    <i class="fa fa-lock"></i>
                                                                                                </a>
                                                                                            @else
                                                                                                {{-- Admin 1: Unlocked → show Edit, Delete, Lock --}}
                                                                                                <a class="btn btn-sm btn-dark"
                                                                                                    href="{{ route('information_honorable', $item->id) }}">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                                </a>

                                                                                                <a class="btn btn-sm btn-danger"
                                                                                                    href="{{ route('delete_inf', [$item->id, 'information_honorable']) }}"
                                                                                                    onclick="return confirm('Are you sure you want to delete?')">
                                                                                                    <i class="fa fa-trash"></i>
                                                                                                </a>

                                                                                                <a class="btn btn-sm btn-info"
                                                                                                    href="{{ route('lock_information_honorable', [$item->id, 'information_honorable']) }}"
                                                                                                    onclick="return confirm('Are you sure you want to lock?')">
                                                                                                    <i class="fa fa-unlock"></i>
                                                                                                </a>
                                                                                            @endif
                                                                                        @elseif ($adminRole == 2 || $adminRole == 9)
                                                                                            @if ($withinScheduleDateOrNot)
                                                                                                @if (!$isLocked)
                                                                                                    {{-- Admin 2/9: Within 5 days, unlocked → show Edit, Delete, Lock --}}
                                                                                                    <a class="btn btn-sm btn-dark"
                                                                                                        href="{{ route('information_honorable', $item->id) }}">
                                                                                                        <i class="fa fa-edit"></i>
                                                                                                    </a>

                                                                                                    <a class="btn btn-sm btn-danger"
                                                                                                        href="{{ route('delete_inf', [$item->id, 'information_honorable']) }}"
                                                                                                        onclick="return confirm('Are you sure you want to delete?')">
                                                                                                        <i class="fa fa-trash"></i>
                                                                                                    </a>

                                                                                                    <a class="btn btn-sm btn-info"
                                                                                                        href="{{ route('lock_information_honorable', [$item->id, 'information_honorable']) }}"
                                                                                                        onclick="return confirm('Are you sure you want to lock? Further, data cannot be edited!')">
                                                                                                        <i class="fa fa-unlock"></i>
                                                                                                    </a>
                                                                                                @else
                                                                                                    {{-- Admin 2/9: Within 5 days, but locked → disabled lock --}}
                                                                                                    <a class="btn btn-sm btn-info disabled"
                                                                                                        href="javascript:void(0)">
                                                                                                        <i class="fa fa-lock"></i>
                                                                                                    </a>
                                                                                                @endif
                                                                                            @else
                                                                                                {{-- Beyond 5 days, locked or not unlocked by admin 1 → disabled lock --}}
                                                                                                <a class="btn btn-sm btn-info disabled"
                                                                                                    href="javascript:void(0)">
                                                                                                    <i class="fa fa-lock"></i>
                                                                                                </a>
                                                                                            @endif
                                                                                        @else
                                                                                            {{-- All other roles → always disabled lock --}}
                                                                                            <a class="btn btn-sm btn-info disabled"
                                                                                                href="javascript:void(0)">
                                                                                                <i class="fa fa-lock"></i>
                                                                                            </a>
                                                                                        @endif
                                                                                    </td>

                                                                                @endif
                                                                            </tr>
                                                                            @endfor
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
@push('custom-scripts')
    <script>
        $('#from_date, #to_date').change(function() {
            // 1. Check if "unavailable" is actually selected
            if ($('#unavailable').is(':checked')) {

                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                // 2. Only validate if BOTH dates have been filled out
                if (from_date !== '' && to_date !== '') {
                    if (from_date !== to_date) {
                        alert("Please select the same From Date and To Date to fetch unavailable records.");

                        // Optional: Clear the input that was just changed so they have to fix it
                        $(this).val('');
                    }
                }
            }
        });

        // You should also check this when they click the checkbox itself!
        $('#unavailable').change(function() {
            if ($(this).is(':checked')) {
                var from_date = $('#from_date').val();
                var to_date = $('#to_date').val();

                if (from_date !== '' && to_date !== '' && from_date !== to_date) {
                    alert("Please select the same From Date and To Date to fetch unavailable records.");
                    // Uncheck the box since the dates don't match
                    $(this).prop('checked', false);
                }
            }
        });
        function PrintDoc() {
            $('#dataTable').DataTable().destroy();
            var toPrint = document.getElementById('prodiv');

            var popupWin = window.open('', '_blank',
                'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');

            popupWin.document.open();

            popupWin.document.write(
                '<html><title>::Preview::</title><head><style>body{font-family:Arial} .noprint{display: none;} table{width:100%; border-collapse:collapse;} .table tr th, .table tr td{border:1px solid #000; padding:3px 5px; font-size: 12px;} .table > thead > tr > th{background-color: #eee;}</style></head><body onload="window.print()">'
            )

            popupWin.document.write(toPrint.innerHTML);
            popupWin.document.write('</body></html>');
            popupWin.document.close();

            $('#dataTable').DataTable();
        }
    </script>
    <script type="text/javascript">
        $('input[name="identified_the_department"]').click(function() {
            var identified_the_department = $(this).val();
            if (identified_the_department == 1) {
                $('#district_name_selected').show();
                $('#tehsil_name_selected').hide();
                $("#district_name").prop('required', true);
                $("#tehsil_name").prop('required', false);
            } else if (identified_the_department == 2) {
                $('#tehsil_name_selected').show();
                $('#district_name_selected').hide();
                $("#district_name").prop('required', false);
                $("#tehsil_name").prop('required', true);
                //$("#um_land_remarks").prop('required', false);
            }
        });
        $('#letter_received').click(function() {
            var letter_received = $(this).val();
            if (letter_received == 1) {
                $('#letter_count').removeClass('d-none');
            } else if (letter_received == 2) {
                $('#letter_count').addClass('d-none');
            }
        });
        $('#letter_quant').on('change', function () {

            let letter_quant = parseInt($(this).val()) || 0;
            let container = $('#information_containers');
            let template = $('#information_template');

            // Remove previously generated containers
            container.empty();

            if (letter_quant <= 0) {
                return;
            }
            for (let i = 1; i <= letter_quant; i++) {
                let clone = template.clone();

                // Remove duplicate ID
                clone.removeAttr('id');

                // Make visible
                clone.removeClass('d-none');

                // Clear input values
                clone.find('input').val('');

                // Reset select values
                clone.find('select').val('');
                clone.find('#s_no').val(i);
                // Optional: store number
                clone.attr('data-index', i);

                container.append(clone);
            }
        });
        // $('#letter_quant').click(function(){
        //     var letter_quant = $(this).val();

        //     let clone=$("#information_container").clone();
        //     // Clear input values
        //     clone.find("input").val("");
        //     // Reset select values
        //     clone.find("select").val("");
        //     // Remove duplicate ID
        //     // Append cloned container
        //     $("#information_container").parent().append(clone);
        // });
    </script>
@endpush
