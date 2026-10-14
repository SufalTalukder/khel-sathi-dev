@extends( 'layouts/coaching_camp_auth' )
@section('content')

    <div class="container-fluid pagecontentbody mt-5">
        <div class="tab-content">
            <div class="removebg-color">
                <div class="col-md-12 pageheader pb-2">
                    <div class="row">
                        <div class="col-md-9">
                            <h4 class="mb-3">Dashboard/डैशबोर्ड</h4>
                        </div>
                        @if($applicationview->status === 0)
                            <div class="container my-3">
                                <div class="alert alert-danger border-danger mb-3">
                                    <h6 class="fw-bold mb-1"><i class="fa fa-times-circle"></i> Application Rejected / आवेदन अस्वीकृत</h6>
                                    <p class="mb-2">Your application has been reviewed and rejected by the authority. Please read the reason below./आपका आवेदन प्राधिकारी द्वारा समीक्षा कर अस्वीकृत किया गया है। कृपया नीचे दिया गया कारण पढ़ें ।</p>
                                    @if(!empty($applicationview->remark))
                                        <div class="p-2 bg-white rounded border">
                                            <strong>Reason/कारण:</strong> {{ $applicationview->remark }}
                                        </div>
                                    @endif
                                    <a href="{{url('coaching_camp/reregister')}}" class="btn btn-info mt-2">Edit and Resubmit the Form / आवेदन पत्र संशोधित कर पुनः जमा करें।</a>
                                </div>
                            </div>
                        @elseif($applicationview->status == 2)
                            <div class="container">
                                <div class="alert alert-danger border-danger mb-3">
                                    <h6 class="fw-bold mb-1"><i class="fa fa-times-circle"></i>Membership Cancelled / सदस्यता निरस्त कर दी गई है।</h6>
                                    <p class="mb-2">Your membership has been revoked. Please read the reason below. / आपकी सदस्यता निरस्त कर दी गई है। कृपया नीचे दिया गया कारण पढ़ें।</p>
                                    @if(!empty($applicationview->cancel_reason))
                                        <div class="p-2 bg-white rounded border">
                                            <strong>Reason/कारण:</strong> {{ $applicationview->cancel_reason }}
                                        </div>
                                    @endif
                                    <a href="{{url('coaching_camp/reregister')}}" class="btn btn-info mt-2">Re-register / पुनः पंजीकरण करें।</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">

                    <div class="card-body">
                        <table class="table table-bordred table-hover bg-white">
                            <thead>
                                <tr class="ellipsis">
                                    <th class="ellipsis">S.No./क्र.सं.</th>
                                    <th class="ellipsis">Application No./आवेदन संख्या</th>
                                    <th class="ellipsis">Name / नाम</th>
                                    <th class="ellipsis">Date of Birth / जन्मतिथि</th>
                                    <th class="ellipsis">Sport's Name / खेल का नाम</th>
                                    <th class="text-center" style="width:10%;">मोबाइल नंबर/Mobile Number</th>
                                    <th class="text-center" style="width:10%;">Status/स्टेटस</th>
                                    <th class="text-center" style="width:10%;">Action/कार्रवाई</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{ ($applicationview->application_no) }}</td>
                                    <td>{{ Auth::guard('CoachingCamp')->user()->name }}</td>
                                    <td>{{ Auth::guard('CoachingCamp')->user()->dob ? dmy(Auth::guard('CoachingCamp')->user()->dob) : '' }}</td>

                                    <td align="center">{{$applicationview->sports_name}}</td>

                                    <td align="center">{{ Auth::guard('CoachingCamp')->user()->mobile }}</td>

                                    {{-- <td class="text-center">

                                        @if ($applicationview->final_submit == 1 && $applicationview->status === 0)
                                            <span class="badge bg-danger rounded-pill">Application Rejected</span>
                                        @elseif ($applicationview->final_submit == 1 && $applicationview->status == 1)
                                            <span class="badge bg-success rounded-pill">Application Accepted</span> <br>
                                            @if ($applicationview->payment_status_coaching_fee == 1)

                                                <span class="badge bg-primary rounded-pill">Success (Registration Fees)</span>
                                            @else
                                                <a href="{{route('coaching_camp_application_coaching_fee')}}" class="btn btn-primary btn-sm">Pay {{$applicationview->payment_amount_coaching}} INR (Coaching Fees)</a>

                                            @endif

                                        @elseif ($applicationview->final_submit == 1 && $applicationview->payment_status == null)
                                            <span class="badge bg-warning rounded-pill">Pending for Approval</span>
                                        @else
                                              <span class="badge bg-success rounded-pill">Application Submitted </span>
                                        @endif

                                      </td> --}}
                                    <td class="text-center">
                                        @if($applicationview->final_submit == null)
                                            <span class="badge bg-danger rounded-pill">Pending Submission</span>
                                        @elseif ($applicationview->final_submit == 1 && $applicationview->status === 0)
                                            <span class="badge bg-danger rounded-pill">Application Rejected</span>
                                        @elseif ($applicationview->final_submit == 1 && $applicationview->status!=null && $applicationview->status == 1)

                                            @if ($applicationview->payment_status_coaching_fee == 1 && $applicationview->validity!=null && $applicationview->validity>=date('Y-m-d') )
                                                <span class="badge bg-success rounded-pill">Active</span>
                                            @elseif ($applicationview->payment_status_coaching_fee == 1 && $applicationview->validity!=null && $applicationview->validity<date('Y-m-d') )
                                                <span class="badge bg-warning rounded-pill">Validity Expired</span>
                                                <a target="_blank" href="{{ route('renew_coaching_camp_fees') }}"
                                                    class="btn btn-info my-2">Pay Coaching Fees INR ( {{$applicationview->payment_amount_coaching}} ) Online</a>
                                            @elseif ($applicationview->payment_status_coaching_fee == 1 && $applicationview->active == 0)
                                                @if($repay==1)
                                                    <span class="badge bg-success rounded-pill">Application Approved</span> <br>
                                                    <a target="_blank" href="{{ route('coaching_camp_fees') }}"
                                                    class="btn btn-info my-2">Pay Coaching Fees INR ( {{$applicationview->payment_amount_coaching}} ) Online</a>
                                                    <p class="">Please Pay Rs.{{$applicationview->protsahan_amount}} as Protsahan Samiti Fees at Stadium</p>
                                                @else
                                                    <span class="badge bg-warning rounded-pill">Activation Pending</span>
                                                @endif
                                            @elseif ($applicationview->payment_status_coaching_fee == 1 && $applicationview->payment_status == null )
                                                <span class="badge bg-info rounded-pill">Coaching Fees Paid</span>
                                                <span class="badge bg-info rounded-pill">Protsahan Samiti Fees Pending</span>
                                                @if($applicationview->protsahan_amount!=null)
                                                    <span class="btn btn-info rounded-pill">Please pay Protsahan Samiti Fees of Rs. {{$applicationview->protsahan_amount}} at Stadium</span>
                                                @endif
                                            @elseif ($applicationview->payment_status_coaching_fee == 1 && $applicationview->payment_amount_coaching == null )
                                                <span class="badge bg-info rounded-pill">Pending Activation</span>
                                            @elseif ($applicationview->status==1 &&  $applicationview->payment_amount_coaching!=null)
                                                @if($repay==1)
                                                    <span class="badge bg-success rounded-pill">Application Approved</span> <br>
                                                    <a target="_blank" href="{{ route('coaching_camp_fees') }}"
                                                    class="btn btn-info my-2">Pay Coaching Fees INR ( {{$applicationview->payment_amount_coaching}} ) Online</a>
                                                    <p class="">Please Pay Rs.{{$applicationview->protsahan_amount}} as Protsahan Samiti Fees at Stadium</p>
                                                @else
                                                    <span class="badge bg-success rounded-pill">Application Approved</span> <br>
                                                    <a target="_blank" href="{{ route('coaching_camp_fees') }}"
                                                    class="btn btn-info my-2">Pay Coaching Fees INR ( {{$applicationview->payment_amount_coaching+10}} ) Online</a>
                                                    <p class="">Please Pay Rs.{{$applicationview->protsahan_amount}} as Protsahan Samiti Fees at Stadium</p>
                                                @endif
                                            @else
                                                <p>Something Went Wrong. Please contact Technical Team</p>
                                            @endif
                                        @elseif ($applicationview->final_submit == 1 && $applicationview->status == 2)
                                            <span class="badge bg-danger rounded-pill">Registration Cancelled</span>
                                        @elseif($applicationview->final_submit==1 && $applicationview->status == null)
                                            <span class="badge bg-success rounded-pill">Application Submitted </span>
                                        @endif
                                    </td>
                                    <td align="center" class="text-center"><a @if ($applicationview->final_submit == 1)
                                        href="{{ route('coaching_camp_application_preview') }}"
                                    @else
                                        href="{{ route('coaching_camp_application_form') }}"
                                    @endif  class="btn btn-sm btn-light" >
                                            <i class="far fa-eye"></i>
                                        </a>
                                        @if ($applicationview->payment_status_coaching_fee == 1 && $applicationview->validity>=date('Y-m-d') && $applicationview->active == 1 )
                                            <a target="_blank" href="{{ route('coaching_id_card', $applicationview->basic_id) }}"><i class="fa fa-id-card"></i>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                    <td><a target="_blank" href="coaching_camp_challan/{{$presponse->challan_no}}"><i class="fa fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        <div class="card text-center">
            <div class="card-header">
                <h4>Payment Status Not Visible? <a href="update_payment_status_coaching_camp"><span class="btn btn-info">Update Payment Status</span></a></h4>
            </div>
        </div>
    </div>
    <div class="modal fade" id="Puprose" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Purpose</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center">data {{$applicationview->validity}}</p>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentnote" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Payment Note</h5>
                    <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Registration Fees - <span class="text-success"><i class="fa fa-rupee-sign"></i>5500.00</span></h3>
                </div>
                <div class="modal-footer">
                    <!--<button type="button" class="btn btn btn-danger"><span class="icons icon-cloud-download"></span> Download</button>-->
                    <button type="button" class="btn btn-danger rounded-pill" data-bs-dismiss="modal">Proceed To Pay</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- InstanceEndEditable -->
