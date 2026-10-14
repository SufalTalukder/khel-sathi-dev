@extends( 'layouts/admin_layout' )
@section( 'content' )

<style>
    .nowraptd {
        white-space: nowrap;
    }
    .dn{display: none;}
</style>

<div class="container-fluid pagecontentbody">
    <div class="tab-content">
        <div class="pagebody removebg-color">
            {{-- <div class="col-md-12 pageheader pb-2">
                <div class="row">
                    <div class="col-md-10">
                        <h4 class="mb-0">Athlete Registration for Training</h4>
                    </div>
                    <div class="col-md-2 d-grid">

                    </div>
                </div>
            </div> --}}
            <div class="col-12">
                <div class="pageheader" id="menu-margin">
                    <h4 class="mb-0">Athlete Registration For Training
                        <a title="Print" class="btn btn btn-outline-success float-end" data-print="modal" onclick="PrintDoc()"><i
                                class="icons icon-printer"></i> प्रिंट</a>
                        <button onclick="ExportToExcel('Athlete Applicant Report')"
                            class="btn btn btn-outline-primary float-end">Excel</button>
                    </h4>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <form class="row" method="post" action="{{ route('coaching_camp_list') }}">
                            @csrf
                            @if(Auth::guard('admin')->user()->admin_role==1)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="district">Division</label>
                                        <div id="list1" class="dropdown-check-list">
                                            <select name="division_id" id="division_id" class="form-select">
                                                <option value="">Select Division</option>
                                                @foreach ($divisions as $key => $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ request()->input('division_id') == $item->id ? 'selected' : '' }}
                                                        data-badge=""> {{ $item->division_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(Auth::guard('admin')->user()->admin_role==1 || Auth::guard('admin')->user()->admin_role==2)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="district">District</label>
                                        <div id="list1" class="dropdown-check-list">
                                            <select name="district_id" id="district_id" class="form-select">
                                                <option value="">Select District</option>
                                                @foreach ($districts as $key => $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ request()->input('district_id') == $item->id ? 'selected' : '' }}
                                                        data-badge=""> {{ $item->city }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="stadium">Stadium</label>
                                    <div class="dropdown-check-list">
                                        <select name="stadium" id="stadium" class="form-select">
                                            <option value="">Select Stadium</option>
                                            @foreach ($stadiums as $key => $iten)
                                                <option value="{{ $iten->id }}"
                                                    {{ request()->input('stadium') == $iten->id ? 'selected' : '' }}
                                                    data-badge=""> {{ $iten->studium_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="sport">Sport's Name</label>
                                    <div id="list1" class="dropdown-check-list">
                                        @php
                                            $abc = request()->input('subsport');
                                        @endphp


                                        <select name="sport_id" id="sport" onchange="sporttype(this.value)" class="form-select"
                                            data-subsport={{ $abc }}>
                                            <option value="" data-badge="">Select Sport Name</option>
                                            @foreach ($sports as $key => $sport)
                                                <option value="{{ $sport->id }}"
                                                    {{ request()->input('sport_id') == $sport->id ? 'selected' : '' }}
                                                    data-badge=""> {{ $sport->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="subsport">Sub Sport </label>
                                    <div id="list1" class="dropdown-check-list">
                                        <select name="subsport" id="dropdownid" class="form-select">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <div class="dropdown-check-list">
                                        <select name="gender" id="gender" class="form-select">
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label for="reset">&nbsp;</label>
                                <button type="submit" class="btn btn-info  btn-block">
                                    Submit
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="card-body" id="prodiv">
                    <table style="width: 100%;" class="dn">
                        <tr>
                            <td align="center" style="position: relative; border: 0; padding-bottom: 5px;">
                                <div style="border-bottom: 0px solid #000; padding-bottom: 2vw;">
                                    <!-- <img src="{{ url('onlineAdmission') }}/images/logo.png" style="position: absolute; width: 70px; top: 5px; left: 0;"/> -->
                                    <!-- <img src="images/logo.png" style="position: absolute; width: 70px; top: 5px; left: 0;"/> -->
                                    <div style="font-size: 18px; font-weight: bold;">
                                        <!-- Department of Sports -->
                                        Khel Sathi Portal
                                    </div>
                                    <div style="font-size: 14px; font-weight: bold;">
                                        Government of Uttar Pradesh
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <table class="table table-bordered datatable" id="dataTable">

                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Application No.</th>
                                <th>Applicant Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Date of Birth</th>
                                <th>Stadium</th>
                                <th>Sport</th>
                                {{-- <th>Sub Sport</th> --}}
                                <th>User Type</th>
                                <th>Form Submission Date</th>
                                <th class="text-center" style="width:10%;">Application Status</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>


                        @foreach($applicationview as $key=>$item)


                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->application_no}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->mobile}}</td>
                                <td>{{dmy($item->regdob)}}</td>
                                <td>{{$item->studium_name}}</td>




                                <td>
                                  @php

                                        $userType = $item->user_type==1? 'Trainee' : 'Amateur';

                                    @endphp
                                    {{ $item->sports_name}}
                                </td>
                                {{-- <td>{{}}</td> --}}
                                <td>{{$userType}}</td>
                                <td>{{dmy($item->created)}}</td>
                                <td class="text-center">
                                    @if($item->form_status === null)
                                        <span class="badge bg-primary rounded-pill">Pending Approval</span>

                                    @elseif ($item->form_status == 2)
                                        <span class="badge bg-danger rounded-pill">Membership Cancelled</span>
                                    @elseif ($item->form_status == 3)
                                        <span class="badge bg-danger rounded-pill">Membership Expired</span>
                                    @elseif($item->form_status == 0)
                                        <span class="badge bg-danger rounded-pill">Application Rejected </span>
                                    @elseif ($item->form_status == 1)
                                        @if ($item->payment_status_coaching_fee == 1 && $item->validity>=date('Y-m-d') && $item->active == 1 )
                                            <span class="badge bg-success rounded-pill">Active</span>
                                        @elseif ($item->payment_status_coaching_fee == 1 && $item->active == 0 && $item->payment_amount_coaching == null)
                                            <span class="badge bg-warning rounded-pill">Activation Pending</span>
                                        @elseif ($item->payment_status_coaching_fee == 1 && $item->payment_status == null )
                                            <span class="badge bg-info rounded-pill">Coaching Fees Paid</span>
                                            <span class="badge bg-info rounded-pill">Protsahan Samiti Fees Pending</span>
                                        @elseif ($item->payment_status_coaching_fee == 1 && $item->validity<date('Y-m-d') )
                                            <span class="badge bg-warning rounded-pill">Validity Expired</span>
                                        @else
                                            <span class="badge bg-success rounded-pill">Application Approved</span> <br>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('coaching_camp_view_details', $item->basic_id) }}" target="_blank"><i class="fa fa-eye"></i>
                                    @if ($item->payment_status_coaching_fee == 1 && $item->validity>=date('Y-m-d') && $item->active == 1 )
                                        <a target="_blank" href="{{ route('coaching_camp_id_card', $item->user_id) }}"><i class="fa fa-id-card"></i>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showMe(e) {
        var t = e.value;
        e.value = t.indexOf(".") >= 0 ? t.slice(0, t.indexOf(".") + 2) : t;
    }


    function PrintDoc() {
        // $('#dataTable_player').DataTable().destroy();
        var toPrint = document.getElementById('player_prodiv');
        //alert(toPrint);
        var popupWin = window.open('', '_blank', 'left=100,top=100,width=1100,height=600,tollbar=0,scrollbars=1,status=0,resizable=1');
        popupWin.document.open();
        popupWin.document.write('<html><title>::Preview:Athlete Registration For Training::</title><head><style>body{font-family:Arial} .noprint{display: none;} table{width:100%; border-collapse:collapse;} .table tr th, .table tr td{border:1px solid #000; padding:3px 5px; font-size: 12px; text-align: left;} th.table-warning{background-color: #dbdbdb;} .table-warning h3{margin: 0;}</style></head><body onload="window.print()">')
        popupWin.document.write(toPrint.innerHTML);
        popupWin.document.write('</body></html>');
        popupWin.document.close();
        // $('#dataTable_player').DataTable();
    }
</script>
<script type="text/javascript" src="{{ asset('js/xlsx.full.min.js') }}"></script>

<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('dataTable');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('Applicant Report.' + (type || 'xlsx')));
    }
    function sporttype(sport) {
		var subsports_id = $("#sport").attr('data-subsport');
		$.ajax({
			type: "POST",
			url: "{{url('collegeadmin/get_subsport')}}",
			data: {
				sport
			},
			success: function(response) {

				var d = $('select[name="subsport"]').empty();
				$('select[name="subsport"]').append(
					'<option value="">Select Sub Sport</option>');
				$.each(response.sub_type, function(key, value) {
					$('select[name="subsport"]').append(
						`<option  ${value.id==subsports_id?'selected':''}   value="${value.id}"> ${value.sub_type} </option>`);
				});
			}
		})
	}
    $("#division_id").on("change", function () {
        var division = $(this).val();
        var district = $('#district_id').val();
        var stadium = $('#stadium').val();
        if (division) {
            $.ajax({
                type: "GET",
                url: ajaxUrl + "/getCitybyDiv/" + division + '/'+ district,
                success: function (html) {
                    $("#district_id").html(html);
                },
            });
            $.ajax({
                type: "GET",
                url: ajaxUrl + "/getStadiumbyDiv/" + division + '/'+ district + '/'+ stadium,
                success: function (html) {
                    $("#stadium").html(html);
                },
            });
        }
    });
    $("#district_id").on("change", function () {
        var division = $('#division_id').val();
        var district = $('#district_id').val();
        var stadium = $('#stadium').val();
        // if (division) {
            $.ajax({
                type: "GET",
                url: ajaxUrl + "/getStadiumbyDiv/" + division + '/'+ district + '/'+ stadium,
                success: function (html) {
                    $("#stadium").html(html);
                },
            });
        // }
    });
</script>

@endsection
@push('custom-scripts')
