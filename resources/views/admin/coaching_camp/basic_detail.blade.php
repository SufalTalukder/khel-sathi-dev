@extends('layouts/admin_layout')
@section('content')

    <style>
        .nowraptd {
            white-space: nowrap;
        }

        .dn {
            display: none;
        }
    </style>

    <div class="container-fluid pagecontentbody">
        <div class="pagebody removebg-color">
            <div class="row">
                <div class="col-12">
                    <div class="pageheader" id="menu-margin">
                        <h4 class="mb-0">
                            Application Preview
                            <button type="button" data-print="modal"
                                class="btn btn-sm  btn-outline-primary ms-2 float-end rounded-pill"
                                onclick="PrintDoc()"><span class="icons icon-printer"></span> Print</button>
                            <a href="{{ route('coaching_camp_list') }}"
                                class="btn btn-outline-danger btn-sm backbtn float-end rounded-pill"><span
                                    class="icons icon-arrow-left"></span>Back to List</a>
                        </h4>
                    </div>
                    <div class="bhoechie-tab-container">
                        <div class="form-scroll">
                            <div class="nano-content">
                                <div class="card">
                                    <div class="card-body">
                                        <div id="prodiv" class="container-fluid">
                                            <table class="dn table" style="width: 100%; margin-bottom: 5px;">
                                                <tr>
                                                    <td colspan="2" align="center" style="position: relative; border: 0; padding-bottom: 5px;">
                                                        <div style="border-bottom: 0px solid #000; padding-bottom: 2vw; position: relative;">
                                                            <img src="{{ asset('public/coaching_camp/images/logo.png') }}"
                                                                style="position: absolute; width: 80px; top: 5px; left: 0;" />
                                                            <div class="text-center">
                                                                <div style="font-size: 2vw; font-weight: bold;">Khel Sathi Portal/खेल साथी पोर्टल</div>
                                                                <div style="font-size: 1.5vw; font-weight: bold;">
                                                                    Department of Sports, Government of Uttar Pradesh<br>
                                                                    खेल विभाग, उत्तर प्रदेश सरकार
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: left; font-size: 12px; padding-top: 5px;"></td>
                                                    <td style="text-align: right; font-size: 12px; padding-top: 5px;">
                                                        <b>Print Date :</b>{{ dmy(now()) }}
                                                    </td>
                                                </tr>
                                            </table>

                                            <?php $srNo = 1; ?>

                                            <table class="table table-bordered" style="width: 100%;">
                                                <tr class="bg-light">
                                                    <th colspan="5"><strong class="text-dark">Registration Details/पंजीकरण का विवरण</strong></th>
                                                </tr>
                                                <tr>
                                                    <td width="20%"><strong>{{ $srNo++ }}. Application No./आवेदन सं.</strong></td>
                                                    <td width="20%">{{ $applicationview->application_no }}</td>
                                                    <td rowspan="6" colspan="2" width="20%" class="text-center">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <div class="mb-2" style="text-align:center">
                                                                <img src="{{ asset('public/coaching_camp/profile_picture') }}/{{ $applicationview->profile_picture }}"
                                                                    class="img-fluid" style="max-height: 150px; display:block; margin:0 auto;" />
                                                            </div>
                                                            <div style="text-align:center">
                                                                <img src="{{ asset('public/coaching_camp/signature') }}/{{ $applicationview->signature }}"
                                                                    style="height: 50px; display:block; margin:15px auto 0;" />
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Name/नाम</strong></td>
                                                    <td>{{ $applicationview->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Father's Name/पिता का नाम</strong></td>
                                                    <td>{{ $applicationview->father_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Mother's Name/माता का नाम</strong></td>
                                                    <td>{{ $applicationview->mother_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Email/ईमेल</strong></td>
                                                    <td>{{ $applicationview->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Date of Birth/जन्मतिथि</strong></td>
                                                    <td >{{ $applicationview->dob ? dmy($applicationview->regdob) : '' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Gender/लिंग</strong></td>
                                                    <td>{{ $applicationview->gender==1 ? "Male" : "Female" }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Mobile Number/मोबाइल नंबर</strong></td>
                                                    <td>{{ $applicationview->mobile }}</td>
                                                    <td><strong>{{ $srNo++ }}. Sport's Name/खेल का नाम</strong></td>
                                                    <td>{{$applicationview->sports_name}}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Aadhaar Number/आधार नं0</strong></td>
                                                    <td>{{ $applicationview->aadhar }}</td>
                                                    <td><strong>{{ $srNo++ }}. Stadium Name/स्टेडियम का नाम</strong></td>
                                                    <td>{{ $applicationview->stadium_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Category/वर्ग</strong></td>
                                                    <td>{{ $applicationview->category }}</td>
                                                    <td><strong>{{ $srNo++ }}. User Type</strong></td>
                                                    <td colspan="2">{{ $applicationview->user_type==1? 'Trainee':'Amateur' }}</td>

                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Religion/धर्म</strong></td>
                                                    <td>{{ $applicationview->religion }}</td>
                                                    @if($applicationview->user_type==2)
                                                        <td><strong>{{ $srNo++ }}. Time Slot Preference/समय स्लॉट की प्राथमिकता</strong></td>
                                                        <td>{{ $applicationview->time_slot_pref==''? 'Not Filled' : $applicationview->time_slot_pref }}</td>
                                                    @endif

                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Height (in Centimetre)/लंबाई (सेंटीमीटर में)</strong></td>
                                                    <td>{{ $applicationview->height }} CM</td>
                                                    <td><strong>{{ $srNo++ }}. Weight (in kg)/वजन (किलोग्राम में)</strong></td>
                                                    <td colspan="2">{{ $applicationview->weight }} KG</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Is applicant suffering from Skin Disease/Fits/Other Disease?<br>क्या आवेदक चर्म रोग/मिर्गी/अन्य किसी रोग से ग्रसित है?</strong></td>
                                                    <td>{{ $applicationview->is_applicant_suffering_disease == 1 ? 'Yes' : 'No' }}</td>
                                                    <td rowspan="2" colspan="3" class="text-center">
                                                        @if ($applicationview->is_applicant_suffering_disease == 1)
                                                            <div class="text-center">
                                                                <strong>{{ $srNo++ }}. Medical Certificate</strong>
                                                                <div class="mt-2">
                                                                    <img src="{{ asset('public/coaching_camp/medical_certificate') }}/{{ $applicationview->medical_certificate }}"
                                                                        class="img-fluid" style="max-height: 200px;" />
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Visible Identification Mark/पहचान चिह्न</strong></td>
                                                    <td>{{ $applicationview->visible_identification_mark }}</td>

                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $srNo++ }}. Aadhaar Card/आधार कार्ड</strong></td>
                                                    <td colspan="4">
                                                        @if (!empty($applicationview->aadhar_card_photo))
                                                            <a href="{{ asset('public/coaching_camp/aadhar_card_photo/' . $applicationview->aadhar_card_photo) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                                                View Aadhar Card
                                                                <i class="bi bi-download ms-1"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr class="bg-light">
                                                    <th colspan="5"><strong>Address Details / पते का विवरण</strong></th>
                                                </tr>
                                                <tr>
                                                    <th colspan="5">Permanent Address/स्थायी पता</th>
                                                </tr>
                                                <tr>
                                                    <td><strong>1. Address/पता</strong></td>
                                                    <td colspan="4">{{ $applicationview->permanent_address }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>2. District/ज़िला</strong></td>
                                                    <td>{{ districtName($applicationview->permanent_district) }}</td>
                                                    <td><strong>3. State/राज्य</strong></td>
                                                    <td colspan="2">Uttar Pradesh</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>4. Pincode/पिन कोड</strong></td>
                                                    <td>{{ $applicationview->permanent_pin }}</td>
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="5">Correspondence Address / पत्राचार हेतु पता</th>
                                                </tr>
                                                <tr>
                                                    <td><strong>1. Address/पता</strong></td>
                                                    <td colspan="4">{{ $applicationview->correspondence_address }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>2. District/ज़िला</strong></td>
                                                    <td>{{ districtName($applicationview->correspondence_district) }}</td>
                                                    <td><strong>3. State/राज्य</strong></td>
                                                    <td colspan="2">{{ stateName($applicationview->correspondence_state) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>4. Pincode/पिन कोड</strong></td>
                                                    <td>{{ $applicationview->correspondence_pin }}</td>
                                                    <td></td>
                                                    <td colspan="2"></td>
                                                </tr>

                                                <tr class="bg-light">
                                                    <th colspan="5"><strong>घोषणा/Declaration</strong></th>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" id="onlineChecked"
                                                                @if ($applicationview->final_submit == 1) checked disabled @endif>
                                                            <label class="form-check-label fw-bold" for="onlineChecked">
                                                                I Agree / मैं सहमत हूं
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5">
                                                        <p class="mb-0">
                                                            I declare that I will undergo training in discipline, following all the rules of the stadium.
                                                            I will not have any objection to termination of my membership in the stadium for any kind of
                                                            indiscipline and I will be personally responsible for any injury etc. caused during the training
                                                            session and no claim will be made on the Sports Department for any kind of compensation.
                                                        </p>
                                                        <p class="mb-0">
                                                            मै घोषणा करता/करती हूँ कि स्टेडियम के सभी नियमों का पालन करते हुये अनुशासन में रहकर प्रशिक्षण
                                                            प्राप्त करूँगा/करूँगी। किसी भी प्रकार की अनुशासनहीनता पर मेरे स्टेडियम में सदस्यता समाप्त करने पर
                                                            मुझे किसी भी प्रकार की आपत्ति नहीं होगी तथा प्रशिक्षण सत्र में चोट इत्यादि लगने पर उसका में स्वयं
                                                            उत्तरदायी होऊँगा/होऊँगी तथा खेल विभाग पर किसी प्रकार की पूर्ति हेतु दावा नही किया जायेगा।
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>

                                            <div class="row mt-4">
                                                <div style="width:50%; float:left;">
                                                    Date / दिनांक : {{ dmy(now()) }}
                                                </div>

                                                <div style="width:50%; float:right; text-align:right;">
                                                    <img src="{{ asset('public/coaching_camp/signature') }}/{{ $applicationview->signature }}"
                                                        style="height: 50px; display:block; margin:15px 0 0;" />
                                                    Player's Signature / खिलाड़ी का हस्ताक्षर
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center">
                        @if ($applicationview->form_status === null)
                            <div class="col-md-2 d-grid">
                                <button class="btn btn-outline-info rounded-pill" id="onlineAdmissionFinal">Update Status </button>
                            </div>
                        @elseif($applicationview->form_status == 2)
                            <div class="col-md-6 my-3">
                                <div class="alert alert-danger border-danger mb-3">
                                    <h6 class="fw-bold mb-1"><i class="fa fa-times-circle"></i> Membership Cancelled</h6>
                                    @if(!empty($applicationview->cancel_reason))
                                        <div class="p-2 bg-white rounded border">
                                            <strong>Reason:</strong> {{ $applicationview->cancel_reason }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($applicationview->form_status == 0)
                            <div class="col-md-6 my-3">
                                <div class="alert alert-danger border-danger mb-3">
                                    <h6 class="fw-bold mb-1"><i class="fa fa-times-circle"></i> Application Rejected</h6>
                                    @if(!empty($applicationview->remark))
                                        <div class="p-2 bg-white rounded border">
                                            <strong>Reason:</strong> {{ $applicationview->remark }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @elseif($applicationview->form_status == 3)
                            <div class="col-md-6 my-3">
                                <div class="alert alert-danger border-danger mb-3">
                                    <h6 class="fw-bold mb-1"><i class="fa fa-times-circle"></i> Membership has Expired</h6>
                                </div>
                            </div>
                        @elseif($applicationview->form_status == 1 && $applicationview->payment_status_coaching_fee == 1 && $applicationview->active==1)
                            <div class="col-md-2 d-grid">
                                <button class="btn btn-outline-danger rounded-pill" id="cancelReg">Cancel Membership</button>
                            </div>
                        @elseif($applicationview->form_status == 1 && $applicationview->payment_status_coaching_fee == 1 && $applicationview->payment_status != 1)
                            <div class="col-md-2 d-grid">
                                <button class="btn btn-outline-success rounded-pill" id="activateMember">Activate Membership</button>
                            </div>
                        @elseif($applicationview->form_status == 2)
                            <div class="col-md-2 d-grid">
                                <span class="btn btn-outline-danger rounded-pill" >Membership has been Cancelled</span>
                            </div>
                        @elseif($applicationview->form_status == 0)
                            <div class="col-md-2 d-grid">
                                <span class="btn btn-outline-danger rounded-pill" >Application has been Rejected</span>
                            </div>
                        @elseif($applicationview->form_status == 3)
                            <div class="col-md-2 d-grid">
                                <span class="btn btn-outline-danger rounded-pill">Membership has been Expired</span>
                            </div>
                        @endif
                    </div>
                    <div class="row mt-2">
                        <div class="container-fluid">
                            @if(isset($payment_responses))
                                <div class="card">
                                    <div class="card-header">
                                        <h4>Challan Details</h4>
                                    </div>
                                    <div class="card-body ">
                                        <table class="table table-bordered datatable" id="dataTable_player">
                                            <thead>
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Challan No</th>
                                                    <th>Download Challan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($payment_responses as $presponse)
                                                    @php $sno=1 @endphp
                                                    <tr>
                                                        <td>{{$sno++}}</td>
                                                        <td>{{$presponse->date}}</td>
                                                        <td>{{$presponse->amount}}</td>
                                                        <td>{{$presponse->challan_no}}</td>
                                                        <td><a target="_blank" href="admin/admin_coaching_camp_challan/{{$presponse->challan_no}}"><i class="fa fa-eye"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->


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


        $('#onlineAdmissionFinal').click(function () {

            $('#onlineFinalWarning').modal('toggle');

        });
        $('#cancelReg').click(function () {

            $('#cancelRegModal').modal('toggle');

        });
        $('#activateMember').click(function () {

            $('#activateModal').modal('toggle');

        });
        // const ajaxUrl = "{{ url('') }}";

        $(document).ready(function () {

            // Show/hide payment input based on selection
            $('input[name="status"]').change(function () {
                const selectedStatus = $('input[name="status"]:checked').val();
                if (selectedStatus === '1') {
                    $('#paymentAmountWrapper').removeClass('d-none');
                } else {
                    $('#paymentAmountWrapper').addClass('d-none');
                }
            });

            // Submit handler
            $('#decisionForm').on('submit', function (e) {
                e.preventDefault();

                const status = $('input[name="status"]:checked').val();
                const paymentAmount = $('#paymentAmount').val().trim();
                const protsahan_amount = $('#protsahantAmount').val().trim();
                const remark = $('#remark').val().trim();

                // Basic validation
                if (typeof status === 'undefined') {
                    alert("Please select Accept or Reject.");
                    return;
                }

                if (remark === '') {
                    alert("Please enter a remark.");
                    return;
                }

                if (status === '1' && (paymentAmount === '' || isNaN(paymentAmount) || parseFloat(paymentAmount) <= 0)) {
                    alert("Please enter a valid payment amount.");
                    return;
                }

                const actionUrl = ajaxUrl + "/admin/coaching_camp/applicationUpdateStatus/{{ $applicationview->id }}";

                $.ajax({
                    type: "POST", // use POST if modifying data
                    url: actionUrl,
                    data: {
                        status: status,
                        payment_amount: status === '1' ? paymentAmount : '',
                        protsahan_amount: protsahan_amount,
                        remark: remark,
                    },
                    success: function (res) {
                        if (res.error === false) {
                            success(res.msg); // or custom success handler
                            window.location.href = res.url;
                        } else {
                            error(res.msg); // or custom error handler
                        }
                    },
                    error: function () {
                        error("Something went wrong. Please try again.");
                    }
                });

            });
            $('#cancelRegistration').on('submit', function (e) {
                e.preventDefault();

                const cancel_reason = $('#cancel_reason').val().trim();

                if (cancel_reason === '') {
                    alert("Please enter Cancel Reason.");
                    return;
                }

                const actionUrl = ajaxUrl + "/admin/coaching_camp/cancelRegistration/{{ $applicationview->id }}";

                $.ajax({
                    type: "POST", // use POST if modifying data
                    url: actionUrl,
                    data: {
                        status: 2,
                        cancel_reason: cancel_reason,
                    },
                    success: function (res) {
                        if (res.error === false) {
                            success(res.msg); // or custom success handler
                            window.location.href = res.url;
                        } else {
                            error(res.msg); // or custom error handler
                        }
                    },
                    error: function () {
                        error("Something went wrong. Please try again.");
                    }
                });

            });
            $('#activate').on('submit', function (e) {
                e.preventDefault();

                const payment_date = $('#payment_date').val().trim();
                const validity = $('#validity').val().trim();
                const membership_type = $('#membership_type').val().trim();
                const protsahan_fees = $('#protsahan_fees').val().trim();
                const district_id = $('#stadium_district').val().trim();
                const stadium_id = $('#stadium').val().trim();
                const coaching_fees = $('#coaching_fees').val().trim();

                if (payment_date === '' || payment_date === '0000-00-00') {
                    alert("Please enter Payment Date.");
                    return;
                }
                if (protsahan_fees === '' ) {
                    alert("Please enter Protsahan Samiti Fees.");
                    return;
                }


                const actionUrl = ajaxUrl + "/admin/coaching_camp/activateMembership/{{ $applicationview->id }}";

                $.ajax({
                    type: "POST", // use POST if modifying data
                    url: actionUrl,
                    data: {
                        validity: validity,
                        payment_date: payment_date,
                        membership_type: membership_type,
                        protsahan_fees: protsahan_fees,
                        coaching_fees: coaching_fees,
                        stadium_id: stadium_id,
                        district_id: district_id,
                    },
                    success: function (res) {
                        if (res.error === false) {
                            success(res.msg); // or custom success handler
                            window.location.href = res.url;
                        } else {
                            error(res.msg); // or custom error handler
                        }
                    },
                    error: function () {
                        error("Something went wrong. Please try again.");
                    }
                });

            });
        });
    </script>

@endsection
@push('custom-scripts')

    <div class="modal fade" id="onlineFinalWarning" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Update Status and Payment Amount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="decisionForm">

                        <!-- Accept / Reject Radios -->
                        <div class="mb-3">
                            <label class="form-label">Status<span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input class="form-check-input decision-check" type="radio" id="acceptRadio" name="status"
                                    value="1">
                                <label class="form-check-label" for="acceptRadio">Accept</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input decision-check" type="radio" id="rejectRadio" name="status"
                                    value="0">
                                <label class="form-check-label" for="rejectRadio">Reject</label>
                            </div>
                        </div>

                        <!-- Payment Amount Field -->
                        <div class="mb-2 d-none" id="paymentAmountWrapper">
                            <div>
                                <label for="form_fees" class="form-label">Form Fees(Annual) </label>
                                <input type="number" class="form-control" id="form_fees" name="form_fees" min="1" value="10" readonly>
                            </div>
                            <div>
                                <label for="paymentAmount" class="form-label">Rajkosh Amount<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="paymentAmount" name="payment_amount"
                                    placeholder="Enter Rajkosh Amount" min="1">
                            </div>
                            <div>
                                <label for="protsahantAmount" class="form-label">Protsahan Samiti Fees<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="protsahantAmount" name="protsahan_amount"
                                    placeholder="Enter Protsahan Fees">
                            </div>
                        </div>

                        <!-- Remark Field -->
                        <div class="mb-3">
                            <label for="remark" class="form-label">Remark<span class="text-danger">*</span></label>
                            <textarea class="form-control" id="remark" name="remark" rows="3"
                                placeholder="Enter remark..."></textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-outline-danger rounded-pill"
                                id="final_submit">Submit</button>
                            <button type="button" class="btn btn-danger rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="cancelRegModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Cancel Membership</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="cancelRegistration">

                        <!-- Remark Field -->
                        <div class="mb-3">
                            <label for="cancel_reason" class="form-label">Reason</label>
                            <select id="cancel_reasons" name="cancel_reason" class="form-select">
                                <option value="" disabled selected>Select Reason</option>
                                <option value="Indiscipline">Indiscipline</option>
                                <option value="Absent for more than 15 days">Absent for more than 15 days</option>
                                <option value="Property Damage">Property Damage</option>
                                <option value="Others">Others</option>
                            </select>
                            <textarea class="form-control" id="cancel_reason" name="cancel_reason" rows="3"
                                placeholder="Enter cancel_reason..."
                                data-name="cancel_reason"
                                style="display:none;">
                            </textarea>
                        </div>

                        <!-- Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-danger rounded-pill"
                                id="final_submit">Cancel Membership</button>
                            <button type="button" class="btn btn-outline-danger rounded-pill"
                                data-bs-dismiss="modal">Close</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="activateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">Activate Membership</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="activate">
                        <input type="text" name="district_id" id="stadium_district" class="form-control d-none" value="{{$applicationview->stadium_district}}">
                        <input type="text" name="stadium_id" id="stadium" class="form-control d-none" value="{{$applicationview->stadium}}">
                        <input type="text" name="coaching_fees" id="coaching_fees" class="form-control d-none" value="{{$applicationview->payment_amount_coaching}}">

                        <!-- Remark Field -->
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Protsahan Samiti Payment Date</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="membership_type" class="form-label">Membership Type</label>
                            <select name="membership_type" id="membership_type" disabled class="form-select">
                                <option class="form-control" value="0" @if($applicationview->user_type==2){{"selected"}} @endif>Monthly</option>
                                <option class="form-control" value="1" @if($applicationview->user_type==1){{"selected"}} @endif>Yearly</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="validity" class="form-label">Validity</label>
                            <input type="date" name="validity" id="validity" disabled value="{{$validity}}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="protsahan_fees" class="form-label">Protsahan Samiti Fees (Rs.)</label>
                            <input type="number" name="protsahan_fees" id="protsahan_fees" class="form-control" placeholder="Enter Protsahan Samiti Fees">
                        </div>

                        <!-- Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-outline-danger rounded-pill"
                                id="final_submit">Activate</button>
                            <button type="button" class="btn btn-danger rounded-pill"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
    $('#cancel_reasons').on('change', function () {
        if ($(this).val() === 'Others') {
            $('#cancel_reason')
                .show()
                .attr('name', 'cancel_reason')
                .attr('required', true);
            $(this).removeAttr('name');
        } else {
            $('#cancel_reason')
                .hide()
                .val('')
                .removeAttr('required')
                .removeAttr('name');

            $(this).attr('name', 'cancel_reason');
        }
    });
</script>


@endpush
