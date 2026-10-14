@extends('layouts/coaching_camp_auth')
@section('content')
    <div class="container-fluid pagecontentbody">
        <div class="pagebody removebg-color">
            <div class="row mt-5">
                <div class="col-12">
                    <div class="pageheader" id="menu-margin">
                        <h4 class="mb-0"> Application Form/आवेदन फार्म </h4>
                    </div>
                </div>

                <form action="{{ route('coaching_camp_application_form_store') }}" id="formsubsmit" method="post"
                    class="needs-validation" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <div class="bhoechie-tab-content">


                            <div class="form-scroll">
                                <div class="nano-content">

                                    <fieldset>
                                        <?php $srno = 1; ?>
                                        <legend>Registration Details/पंजीकरण का विवरण</legend>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">
                                                        {{ $srno++ }}. Stadium District / स्टेडियम का जनपद<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select form-control"
                                                        aria-label="Default select example" name="stad_district" required id="stad_district">
                                                        <option value="">Select District</option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->id }}"
                                                                @if (isset($applicationview->stad_city) && $applicationview->stad_city == $city->id) selected @endif>
                                                                {{ $city->city }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">
                                                        {{ $srno++ }}. Stadium Name / स्टेडियम का नाम<span
                                                            class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select form-control"
                                                        aria-label="Default select example" name="stadium" required id="stadium">
                                                        <option value="">Select Stadium</option>
                                                        @foreach ($stadium as $item)
                                                            <option value="{{ $item->id }}"
                                                                @if (isset($applicationview->stadium) && $applicationview->stadium == $item->id) selected @endif>
                                                                {{ $item->studium_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Sport's Name / खेल का नाम<span class="text-danger">*</span></label>
                                                    <select id="sportname" class="form-select form-control" name="sport" placeholder="Select Sport" required>
                                                        <option value="">Select Sport/खेल चुनें</option>
                                                        @foreach ($sport as $item)
                                                            <option value="{{ $item->id }}" {{ ($applicationview->sport ?? '') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            @php
                                                $selectedSport = $applicationview->sport ?? null;

                                                $subSports = $selectedSport
                                                    ? sub_sport_type($selectedSport)
                                                    : collect();

                                                $hasSubSports = count($subSports) > 0;
                                            @endphp
                                            <div class="col-md-3" id="sub_sport_div" style="{{ $hasSubSports ? '' : 'display:none' }}">
                                                <label class="placeholder">Sub Sport/उप खेल <span class="text-danger" id="sub_sport_required_star" style="{{ $hasSubSports ? '' : 'display:none' }}">*</span></label>
                                                <select class="form-control form-select" name="sub_type" id="sub_type" {{ $hasSubSports ? 'required' : '' }} required>
                                                    <option value="">Select/चुनें</option>
                                                    @foreach($subSports as $sub)
                                                        <option value="{{ $sub->id }}" {{ ($basic_detail->sub_sport_type ?? '') == $sub->id ? 'selected' : '' }}>{{ $sub->sub_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if(Auth::guard('CoachingCamp')->user()->user_type==2)
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="placeholder">{{ $srno++ }}. Time Slot Preference
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="time_slot_pref" id="timeSlot" class="form-control form-select">
                                                            <option value="1" {{ ($basic_detail->time_slot_pref ?? '') == 1 ? 'selected' : '' }}>Morning</option>
                                                            <option value="2" {{ ($basic_detail->time_slot_pref ?? '') == 2 ? 'selected' : '' }}>Evening</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group mb-3">
                                                        <label class="placeholder">{{ $srno++ }}. Membership Type Preference
                                                            <span class="text-danger">*</span>
                                                        </label>
                                                        <select name="mem_type_pref" id="memType" class="form-control form-select">
                                                            <option value="1" {{ ($basic_detail->mem_type_pref ?? '') == 1 ? 'selected' : '' }}>Monthly</option>
                                                            <option value="2" {{ ($basic_detail->mem_type_pref ?? '') == 2 ? 'selected' : '' }}>Quarterly</option>
                                                            <option value="3" {{ ($basic_detail->mem_type_pref ?? '') == 3 ? 'selected' : '' }}>Half Yearly</option>
                                                            <option value="4" {{ ($basic_detail->mem_type_pref ?? '') == 4 ? 'selected' : '' }}>Yearly</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Email / ईमेल <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="email " readonly
                                                        value="{{ Auth::guard('CoachingCamp')->user()->email  }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Name / नाम<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="name" readonly
                                                        value="{{ Auth::guard('CoachingCamp')->user()->name }}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Gender / लिंग
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    {{-- <input type="radio" name="gender" id="gender" value="1" readonly {{Auth::guard('CoachingCamp')->user()->gender==1 ? "checked" : ''}}>Male
                                                    <input type="radio" name="gender" id="gender" value="2" readonly {{Auth::guard('CoachingCamp')->user()->gender==2 ? "checked" : '' }}>Female --}}
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1" {{Auth::guard('CoachingCamp')->user()->gender==1 ? "checked" : ''}}>
                                                        <label class="form-check-label" for="inlineRadio1">Male</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="2" {{Auth::guard('CoachingCamp')->user()->gender==2 ? "checked" : '' }}>
                                                        <label class="form-check-label" for="inlineRadio2">Female</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Father's Name / पिता का
                                                        नाम<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="father_name"
                                                        @if (isset($applicationview->father_name)) value="{{ $applicationview->father_name }}" @endif
                                                        onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'
                                                        pattern="^[A-Za-z -]+$" maxlength="255" required />
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Mother's Name/ माता का
                                                        नाम<span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="mother_name"
                                                        @if (isset($applicationview->mother_name)) value="{{ $applicationview->mother_name }}" @endif
                                                        onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'
                                                        pattern="^[A-Za-z -]+$" maxlength="255" required />
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Date of Birth /
                                                        जन्मतिथि <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" readonly name="dob"
                                                        max="{{ date('Y-m-d') }}"
                                                        value="{{ Auth::guard('CoachingCamp')->user()->dob }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Mobile Number/मोबाइल नंबर
                                                       <span class="text-danger">*</span></label>

                                                    <input type="text" class="form-control" readonly
                                                        value="{{ Auth::guard('CoachingCamp')->user()->mobile }}"
                                                        maxlength="10" />
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Aadhaar Number / आधार
                                                        नं.<span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" readonly
                                                        value="{{ Auth::guard('CoachingCamp')->user()->aadhar }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <?php
                                                $bloodGroup = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
                                                ?>
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Blood Group / ब्लड
                                                        ग्रुप<span class="text-danger">*</span></label>

                                                    <select name="blood_group" class="form-control" required>
                                                        <option value="">Select Blood Group</option>
                                                        @foreach ($bloodGroup as $item)
                                                            <option value="{{ $item }}"
                                                                {{ old('blood_group', $applicationview?->blood_group) == $item ? 'selected' : '' }}>
                                                                {{ $item }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Religion / धर्म<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select form-control w-100"
                                                        aria-label="Default select example" name="religion" required>
                                                        <option value="">Select Religion</option>
                                                        <option value="Hindu"
                                                            @if (isset($applicationview->religion) && $applicationview->religion == 'Hindu') selected @endif>Hindu</option>
                                                        <option value="Muslim"
                                                            @if (isset($applicationview->religion) && $applicationview->religion == 'Muslim') selected @endif>Muslim
                                                        </option>
                                                        <option value="Christian"
                                                            @if (isset($applicationview->religion) && $applicationview->religion == 'Christian') selected @endif>Christian
                                                        </option>
                                                        <option value="Sikh"
                                                            @if (isset($applicationview->religion) && $applicationview->religion == 'Sikh') selected @endif>Sikh</option>
                                                        <option value="Buddha"
                                                            @if (isset($applicationview->religion) && $applicationview->religion == 'Buddha') selected @endif>Buddha
                                                        </option>
                                                        <option value="Jain"
                                                            @if (isset($applicationview->religion) && $applicationview->religion == 'Jain') selected @endif>Jain</option>
                                                        <option value="Other"
                                                            @if (isset($applicationview->religion) && $applicationview->religion == 'Other') selected @endif>Other
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-2">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Caste / जाति<span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="caste" required
                                                        @if (isset($applicationview->caste)) value="{{ $applicationview->caste }}" @endif
                                                        onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'
                                                        pattern="^[A-Za-z -]+$" maxlength="255" />
                                                </div>
                                            </div> --}}
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Category/वर्ग<span
                                                            class="text-danger">*</span></label>

                                                    <select required name="category"
                                                        class="form-select form-control w-100" name="category" required>
                                                        <option value="">Select</option>
                                                        <option value="General"
                                                            @if (isset($applicationview->category) && $applicationview->category == 'General') selected @endif>General
                                                        </option>
                                                        <option value="OBC"
                                                            @if (isset($applicationview->category) && $applicationview->category == 'OBC') selected @endif>OBC</option>
                                                        <option
                                                            value="SC"@if (isset($applicationview->category) && $applicationview->category == 'SC') selected @endif>
                                                            SC</option>
                                                        <option
                                                            value="ST"@if (isset($applicationview->category) && $applicationview->category == 'ST') selected @endif>
                                                            ST</option>
                                                        <option
                                                            value="Other"@if (isset($applicationview->category) && $applicationview->category == 'Other') selected @endif>
                                                            Other</option>
                                                    </select>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Height (in
                                                        Centimetre)/लंबाई (सेंटीमीटर
                                                        में)<span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="height" name="height" max="400" step="0.1"
                                                        value="{{ $applicationview?->height }}" required />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Weight (in kg)/वजन
                                                        (किलोग्राम में)<span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="weight" name="weight" max="250" step="0.01"
                                                        value="{{ $applicationview?->weight }}" required />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group mb-3">
                                                    <label class="placeholder">{{ $srno++ }}. Visible Identification
                                                        Mark/पहचान चिह्न
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        name="visible_identification_mark"
                                                        value="{{ $applicationview?->visible_identification_mark }}"
                                                        required />
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="form-check mt-4">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="is_applicant_suffering_disease"
                                                        id="is_applicant_suffering_disease" value="1"
                                                        {{ old('is_applicant_suffering_disease', $applicationview?->is_applicant_suffering_disease) ? 'checked' : '' }}
                                                         />
                                                    <label class="placeholder form-check-label">{{ $srno++ }}. Is
                                                        applicant suffering
                                                        from Skin
                                                        Disease/Fits/Other Disease?/क्या आवेदक चर्म रोग/मिर्गी/अन्य किसी रोग
                                                        से ग्रसित है?</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3 medical_certificate-div" id="medical_certificate-div">
                                                <div class="form-group">
                                                    <label>{{ $srno++ }}.Medical Certificate / चिकित्सीय प्रमाण पत्र <span
                                                            class="text-danger">*</span></label>
                                                    @if (!empty($applicationview->medical_certificate))
                                                        <div class="profpic mb-2">
                                                            <img class="img-fluid"
                                                                src="{{ asset('public/coaching_camp/medical_certificate/' . $applicationview->medical_certificate) }}"
                                                                alt="Medical Certificate">
                                                        </div>
                                                    @endif

                                                    <input type="file" class="form-control"
                                                        onchange="getfileext111(this, 21)" id="medical_certificate"
                                                        name="medical_certificate"
                                                        @if (empty($applicationview->medical_certificate)) required @endif>
                                                </div>
                                                <span class="note">File Format/फाइल का प्रारूप:
                                                PDF | Max File Size/फाइल का अधिकतम साइज़: 2
                                                MB</span>
                                            </div>
                                        </div>
                                    </fieldset>


                                    <fieldset> <?php $srNo = 1; ?>
                                        <legend> Address Details / पते का विवरण</legend>
                                        <div class="row">

                                            <div class="col-md-12">

                                                <p><b>Permanent Address / स्थायी पता</b></p>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. Address/पता
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="permanent_address" id="permanent_address" required
                                                                @if (isset($applicationview->permanent_address)) value="{{ $applicationview->permanent_address }}" @endif>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. State/राज्य
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select form-control w-100"
                                                                name="permanent_state" id="permanent_state" required>
                                                                <option value="">Select</option>
                                                                @foreach ($states as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if (isset($applicationview->correspondence_state) && $applicationview->correspondence_state == $item->id) selected @endif>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. District/ज़िला
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select form-control w-100"
                                                                name="permanent_district" required
                                                                id="permanent_district">
                                                                <option value="">Select</option>
                                                                @foreach ($districts as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if (isset($applicationview->permanent_district) && $applicationview->permanent_district == $item->id) selected @endif>
                                                                        {{ $item->city }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. Pincode/पिन
                                                                कोड <span class="text-danger">*</span></label>
                                                            <input type="number" min="100000" max="999999"
                                                                class="form-control" name="permanent_pin"
                                                                id="permanent_pin" required
                                                                @if (isset($applicationview->permanent_pin)) value="{{ $applicationview->permanent_pin }}" @endif>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>

                                            <div class="col-md-12 text-center mt-3 mb-3">
                                                <small class="fw-normal bg-light rounded-pill fs-6 border p-2">
                                                    <input type="checkbox" name="" value="yes" id="myCheck"
                                                        onchange="myFunction()" />
                                                    Same as above</small>
                                            </div>


                                            <div class="col-md-12">
                                                <?php $srNo = 1; ?>
                                                <p><b>Correspondence Address / पत्राचार हेतु पता</b></p>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. Address/पता
                                                                <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control"
                                                                name="correspondence_address" id="correspondence_address"
                                                                required
                                                                @if (isset($applicationview->correspondence_address)) value="{{ $applicationview->correspondence_address }}" @endif>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. State/राज्य
                                                                <span class="text-danger">*</span></label>
                                                            <select class="form-select form-control w-100"
                                                                onchange="get_city(this.value)" id="correspondence_state"
                                                                name="correspondence_state" required
                                                                id="correspondence_state">
                                                                <option value="">Select</option>
                                                                @foreach ($states as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if (isset($applicationview->correspondence_state) && $applicationview->correspondence_state == $item->id) selected @endif>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. District/ज़िला
                                                                <span class="text-danger">*</span></label>
                                                            <input type="hidden" id="district3"
                                                                @if (isset($applicationview->correspondence_district)) value="{{ $applicationview->correspondence_district }}" @endif>
                                                            <select class="form-select form-control w-100" id="cdistrict"
                                                                name="correspondence_district" required>
                                                                <option value="">Select </option>


                                                                @foreach ($districtall as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if (isset($applicationview->permanent_district) && $applicationview->permanent_district == $item->id) selected @endif>
                                                                        {{ $item->city }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>



                                                    <div class="col-md-2">
                                                        <div class="form-group mb-3">
                                                            <label class="placeholder">{{ $srNo++ }}. Pincode/पिन
                                                                कोड <span class="text-danger">*</span></label>
                                                            <input class="form-control" type="number" min="100000"
                                                                max="999999" name="correspondence_pin"
                                                                id="correspondence_pin" required
                                                                @if (isset($applicationview->correspondence_pin)) value="{{ $applicationview->correspondence_pin }}" @endif>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>




                                    <!--fieldset>
                                                                                                          <legend>Instructor recommendation / प्रशिक्षक की संस्तुति</legend>

                                                                                          <textarea class="form-control mb-3" rows="3"></textarea>

                                                                                                        </fieldset-->
                                    <fieldset>
                                        <?php $srNo = 1; ?>
                                        <legend>Upload/दस्तावेज़</legend>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ $srNo++ }}. Aadhar Card/ आधार कार्ड
                                                        <span class="text-danger">*</span>
                                                    </label>

                                                    <input type="file" class="form-control"
                                                        onchange="getfileextPdf(this, 21)" id="Filepdf"
                                                        name="aadhar_card_photo" accept=".pdf"
                                                        @if (empty($applicationview->aadhar_card_photo)) required @endif>
                                                    <div class="my-2">
                                                        <a id="view_aadhaar" class="btn btn-info btn-sm" style="@if(empty($applicationview->aadhar_card_photo)){{'display:none;'}}@endif" href="@if (!empty($applicationview->aadhar_card_photo)){{ asset('public/coaching_camp/aadhar_card_photo/' . $applicationview->aadhar_card_photo) }} @else {{'#'}} @endif"
                                                            target="_blank">
                                                            View Uploaded Aadhar Card (PDF)
                                                        </a>
                                                    </div>
                                                </div>
                                                <span class="note">File Format/फाइल का प्रारूप:
                                                PDF | Max File Size/फाइल का अधिकतम साइज़: 2
                                                MB</span>

                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ $srNo++ }}. Applicant's Photo/
                                                        आवेदक की फोटो<span class="text-danger">*</span></label>
                                                    <div class="profpic"> <img id="profile_preview"
                                                            @if (isset($applicationview->profile_picture)) src="{{ asset('public/coaching_camp/profile_picture') }}/{{ $applicationview->profile_picture }}" @else src="{{ asset('public/coaching_camp/images/profile2.jpg') }}" @endif
                                                            class="img-fluid"> </div>
                                                    <input type="file" class="form-control"
                                                        onchange="getfileext111(this,21)" id="File21"
                                                        @if (!isset($applicationview->profile_picture)) required @endif
                                                        name="profile_picture">
                                                </div>
                                                <span class="note">File Format/फाइल का प्रारूप:
                                                JPEG/JPG/PNG | Max File Size/फाइल का अधिकतम साइज़: 2
                                                MB</span>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>{{ $srNo++ }}. Signature of Applicant/आवेदक के हस्ताक्षर <span
                                                            class="text-danger">*</span></label>
                                                    <div class="sign"> <img id="signature_preview"
                                                            @if (isset($applicationview->signature)) src="{{ asset('public/coaching_camp/signature') }}/{{ $applicationview->signature }}" @else src="{{ asset('public/coaching_camp') }}/images/signature.png" @endif
                                                            class="img-fluid"> </div>
                                                    <input type="file" class="form-control"
                                                        onchange="getfileext111(this,22)" id="File22"
                                                        @if (!isset($applicationview->signature)) required @endif
                                                        name="signature">
                                                </div>
                                                <span class="note">File Format/फाइल का प्रारूप:
                                                JPEG/JPG/PNG | Max File Size/फाइल का अधिकतम साइज़: 2
                                                MB</span>
                                            </div>
                                            @if($age<18)
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>{{ $srNo++ }}. Parent's Signature/
                                                            आभिभावक के हस्ताक्षर <span class="text-danger">*</span></label>
                                                        <div class="profpic"> <img id="parent_sign_preview"
                                                                @if (isset($applicationview->parent_sign)) src="{{ asset('public/coaching_camp/parent_sign') }}/{{ $applicationview->parent_sign }}" @else src="{{ asset('public/coaching_camp') }}/images/signature.png" @endif
                                                                class="img-fluid"> </div>
                                                        <input type="file" class="form-control"
                                                            onchange="getfileext111(this,23)" id="File23"
                                                            @if (!isset($applicationview->parent_sign)) required @endif
                                                            name="parent_sign">
                                                    </div>
                                                    <span class="note">File Format/फाइल का प्रारूप:
                                                    JPEG/JPG/PNG | Max File Size/फाइल का अधिकतम साइज़: 2
                                                    MB</span>
                                                </div>
                                            @endif



                                        </div>
                                    </fieldset>

                                    <div class="bhoechie-footer">
                                        <div class="row justify-content-center">

                                            <div class="col-md-2 d-grid"> <button type="submit"
                                                    class="btn btn-danger rounded-pill">Save & Proceed</button> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')

    <script>
        document.getElementById('weight').addEventListener('input', function(e) {
            let value = e.target.value;

            // Check if a decimal exists
            if (value.includes('.')) {
                let parts = value.split('.');
                // If the decimal part is longer than 2 digits, truncate it
                if (parts[1].length > 2) {
                    e.target.value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            }
        });
        document.getElementById('height').addEventListener('input', function(e) {
            let value = e.target.value;

            // Check if a decimal exists
            if (value.includes('.')) {
                let parts = value.split('.');
                // If the decimal part is longer than 2 digits, truncate it
                if (parts[1].length > 2) {
                    e.target.value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            }
        });
        $('#File21').on('change', function () {
            if (this.files && this.files[0]) {
                $('#profile_preview').attr(
                    'src',
                    URL.createObjectURL(this.files[0])
                );
            }
        });

        $('#File22').on('change', function () {
            if (this.files && this.files[0]) {
                $('#signature_preview').attr(
                    'src',
                    URL.createObjectURL(this.files[0])
                );
            }
        });

        $('#File23').on('change', function () {
            if (this.files && this.files[0]) {
                $('#parent_sign_preview').attr(
                    'src',
                    URL.createObjectURL(this.files[0])
                );
            }
        });
        $('#Filepdf').on('change', function () {

            const file = this.files[0];

            if (file) {
                const fileURL = URL.createObjectURL(file);

                $('#view_aadhaar')
                    .attr('href', fileURL)
                    .show();
            } else {
                $('#view_aadhaar').hide();
            }

        });

        $("#formsubsmit").submit(function(e) {

            e.preventDefault();
            if ($("#formsubsmit")[0].checkValidity() === false) {
                e.stopPropagation();
            } else {
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(res) {
                        if (res.error == false) {
                            success(res.msg);

                            window.location.href = res.url;


                        } else {
                            error(res.msg);
                        }
                    },
                });
            }
            $("#formsubsmit").addClass("was-validated");
        });

        function get_city(value) {


            let h_city = $("#district3").val();
            let same_city = $("#permanent_district").val();
            let option = `<option value=''>Select </option>`;
            $.ajax({
                type: "POST",
                url: "{{ url('get_city') }}",
                data: {
                    value
                },

                success: function(response) {
                    response.forEach((item) => {
                        if (same_city && $("#myCheck").prop('checked') == true) {
                            option +=
                                `<option value="${item.id}" ${item.id==same_city?'selected':''} ${item.id==h_city?'selected':''}>${item.city}</option>`;
                        } else {
                            option +=
                                `<option value="${item.id}"  ${item.id == h_city ? 'selected':''} >${item.city}</option>`;
                        }


                    });
                    $("#cdistrict").empty();
                    $("#cdistrict").append(option);
                }
            });
        }



        // function myFunction() {
        //     var checkBox = document.getElementById("myCheck");

        //     if (checkBox.checked == true) {





        //         document.getElementById("correspondence_address").value = document.getElementById("permanent_address")
        //             .value;
        //         document.getElementById("correspondence_pin").value = document.getElementById("permanent_pin").value;

        //         document.getElementById("correspondence_state").value = +document.getElementById("permanent_state").value;
        //         get_city(document.getElementById("permanent_state").value);

        //         document.getElementById("cdistrict").value = document.getElementById("permanent_district").value;

        //     } else {
        //         document.getElementById("correspondence_address").value = " ";
        //         document.getElementById("correspondence_pin").value = " ";

        //         document.getElementById("correspondence_state").value = " ";
        //         document.getElementById("cdistrict").value = " ";

        //     }
        // }
        function syncAddress() {
            if (!document.getElementById("myCheck").checked) {
                return;
            }

            document.getElementById("correspondence_address").value =
                document.getElementById("permanent_address").value;

            document.getElementById("correspondence_pin").value =
                document.getElementById("permanent_pin").value;

            document.getElementById("correspondence_state").value =
                document.getElementById("permanent_state").value;

            document.getElementById("cdistrict").value =
                document.getElementById("permanent_district").value;
        }

        function myFunction() {
            const checked = document.getElementById("myCheck").checked;

            const fields = [
                "correspondence_address",
                "correspondence_pin",
                "correspondence_state",
                "cdistrict"
            ];

            if (checked) {

                syncAddress();

                fields.forEach(id => {
                    document.getElementById(id).readOnly = true;
                });

            } else {

                fields.forEach(id => {
                    document.getElementById(id).readOnly = false;
                });

                document.getElementById("correspondence_address").value = "";
                document.getElementById("correspondence_pin").value = "";
                document.getElementById("correspondence_state").value = "";
                document.getElementById("cdistrict").value = "";
            }
        }

        // Auto-sync when permanent address fields change
        document.getElementById("permanent_address").addEventListener("input", syncAddress);
        document.getElementById("permanent_pin").addEventListener("input", syncAddress);
        document.getElementById("permanent_state").addEventListener("change", syncAddress);
        document.getElementById("permanent_district").addEventListener("change", syncAddress);






        function getfileext111(value, id) {


            var fileExtension = ["jpeg", "jpg", 'png'];
            var file_size = value.files[0].size;
            var filevalue = value.value;
            if (
                $.inArray(filevalue.split(".").pop().toLowerCase(), fileExtension) == -1
            ) {
                $("#File" + id).val("");
                $("#sign").attr("src", "");
                error("Please Upload File in jpg jpeg Format.");
            } else if (file_size > 2000000) {
                $("#File" + id).val("");
                $("#sign").attr("src", "");
                error("File Size should not exceed 2 Mb.");
            }
        }

        function getfileextPdf(value, id) {
            var fileExtension = ['pdf'];
            var file_size = value.files[0].size;
            var filevalue = value.value;
            if (
                $.inArray(filevalue.split(".").pop().toLowerCase(), fileExtension) == -1
            ) {
                $("#File" + id).val("");
                $("#sign").attr("src", "");
                error("Please Upload File in jpg jpeg Format.");
            } else if (file_size > 2000000) {
                $("#File" + id).val("");
                $("#sign").attr("src", "");
                error("File Size should not exceed 2 Mb.");
            }
        }
        $(document).ready(function() {
            function toggleMedicalCertificate() {

                if ($('#is_applicant_suffering_disease').is(':checked')) {

                    $('.medical_certificate-div').show();

                    // has $applicationview->medical_certificate
                    if (!$('#medical_certificate').val() && !{{ isset($applicationview->medical_certificate) ? 'true' : 'false' }}) {
                        $('#medical_certificate').prop('required', true);
                    }
                } else {
                    $('.medical_certificate-div').hide();
                    $('#medical_certificate').prop('required', false);
                }
            }

            toggleMedicalCertificate();

            $('#is_applicant_suffering_disease').on('change', toggleMedicalCertificate);
        });
        const allSubSports = @json($allSubSports);
        function getSubSportsForSport(sportId) {
            return allSubSports.filter(function(item) {
                return item.sport_id == sportId;
            });
        }
        $("#sportname").on("change", function () {

            let sportId = $(this).val();

            if (!sportId) {

                $("#sub_sport_div").hide();
                $("#sub_sport_required_star").hide();

                $("#sub_type")
                    .html('<option value="">Select/चुनें</option>')
                    .removeAttr("required");

                return;
            }

            let subs = getSubSportsForSport(sportId);

            if (subs.length > 0) {

                let options = '<option value="">Select/चुनें</option>';

                subs.forEach(function (sub) {
                    options += `<option value="${sub.id}">${sub.sub_type}</option>`;
                });

                $("#sub_type").html(options);

                $("#sub_sport_div").show();
                $("#sub_sport_required_star").show();
                $("#sub_type").attr("required", true);

            } else {

                $("#sub_sport_div").hide();
                $("#sub_sport_required_star").hide();

                $("#sub_type")
                    .html('<option value="">Select/चुनें</option>')
                    .removeAttr("required");
            }
        });
        $("#stad_district").on("change", function () {

            let district_id = $(this).val();

            if (!district_id) {
                $("#stadium").html(
                    '<option value="">Select Stadium/स्टेडियम चुनें</option>'
                );
                return;
            }

            $.ajax({
                url: "{{ url('/coaching_camp/get-stadiums') }}/" + district_id,
                type: "GET",
                dataType: "json",

                success: function (stads) {

                    let options =
                        '<option value="">Select Stadium/स्टेडियम चुनें</option>';

                    if (stads.length > 0) {

                        stads.forEach(function (stad) {
                            options += `
                                <option value="${stad.id}">
                                    ${stad.studium_name}
                                </option>
                            `;
                        });
                    }

                    $("#stadium").html(options);
                },

                error: function (xhr) {
                    console.error("Error fetching stadiums:", xhr.responseText);

                    $("#stadium").html(
                        '<option value="">Select Stadium/स्टेडियम चुनें</option>'
                    );
                }
            });
        });
        $(document).ready(function () {
            $("#sportname").trigger("change");
        });
    </script>
@endpush
