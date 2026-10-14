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
            <div class="col-md-12 pageheader pb-2">
                <div class="row">
                    <div class="col-md-10">
                        <h4 class="mb-0">Stadium Sports Mapping</h4>
                    </div>
                    <div class="col-md-2 d-grid">

                    </div>
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
                                            <select name="division_id" id="dropdownid" class="form-select">
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
                                            <select name="district_id" id="dropdownid" class="form-select">
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
                                            @foreach ($stadiums as $key => $item)
                                                <option value="{{ $item->id }}"
                                                    {{ request()->input('stadium') == $item->id ? 'selected' : '' }}
                                                    data-badge=""> {{ $item->studium_name }}</option>
                                            @endforeach
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
                    <table class="table table-bordered datatable text-center" id="dataTable_player">

                        <thead>
                            <tr>

                                <th rowspan="2" class="serial">
                                    S.No.
                                </th>

                                <th rowspan="2">
                                    Sport
                                </th>

                                <th colspan="2">
                                    प्रशिक्षार्थी
                                </th>

                                <th colspan="2">
                                    शौकिया
                                </th>

                            </tr>

                            <tr>

                                <th>Boys</th>
                                <th>Girls</th>

                                <th>Boys</th>
                                <th>Girls</th>

                            </tr>

                        </thead>


                        <tbody id="sportsTableBody">
                            @php $sno=1; @endphp
                            @foreach($sports as $item)

                                <tr>
                                    <input type="hidden" name="sport_id" value="{{$item->id}}">
                                    <input type="hidden" name="stadium_id" value="{{$stadium}}">
                                    <td class="serial">{{$sno++}}</td>

                                    <td class="sport-name">
                                        <div class="sport-wrapper">
                                            <span class="sport-title">
                                                {{$item->name}}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="center">
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </td>

                                    <td class="center">
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </td>

                                    <td class="center">
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </td>

                                    <td class="center">
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </td>

                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
                <div class="table-footer">

                    <div class="footer-actions">

                        <button class="btn btn-primary"
                                onclick="saveChanges()">
                             Save Changes
                        </button>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function saveChanges() {
        alert(
            "Sports configuration saved successfully."
        );
    }

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
        popupWin.document.write('<html><title>::Preview::</title><head><style>body{font-family:Arial} .noprint{display: none;} table{width:100%; border-collapse:collapse;} .table tr th, .table tr td{border:1px solid #000; padding:3px 5px; font-size: 12px; text-align: left;} th.table-warning{background-color: #dbdbdb;} .table-warning h3{margin: 0;}</style></head><body onload="window.print()">')
        popupWin.document.write(toPrint.innerHTML);
        popupWin.document.write('</body></html>');
        popupWin.document.close();
        // $('#dataTable_player').DataTable();
    }
</script>
<script type="text/javascript" src="{{ asset('js/xlsx.full.min.js') }}"></script>

<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('dataTable_player_dashboard');
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
</script>

@endsection
@push('custom-scripts')
