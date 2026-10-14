@extends('layouts/coaching_camp')
@section('content')
    <div class="pt-4 pb-5 login-form">
        <div class="text-center text-md-center mt-md-0">
            <h3 class="mb-0"> Applicant’s Registration/आवेदक का पंजीकरण</h3>
            <div class="d-flex justify-content-center align-items-center mt-2"><span class="fw-normal"> <a
                        href="{{ route('coaching_camp_login') }}" class="fw-bold text-primary">Already Registered ? Click Here
                        to Login<br>
                        पहले से ही पंजीकृत? लॉगिन करने हेतु यहां क्लिक करें</a></span> </div>
        </div>
        <form action="{{ route('coaching_camp_regsiter_store') }}" id="submitform" class="mt-2 needs-validation" novalidate
            method="post">
            @csrf
            <div class="alert alert-success bg-transparent fade show p-2 mb-2" role="alert">
                <ul class="mb-0">
                    <li>Information entered here cannot be modified after submitting. / यहाँ दर्ज की गई जानकारी जमा करने के
                        बाद संशोधित नहीं की जा सकेगी।</li>
                </ul>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group row required mb-2" aria-required="true">
                        <label class="col-form-label col-lg-6" for="firstName">1. Name / नाम<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" value="{{ old('name') }}" id="firstName" required
                                name="name" placeholder="Full Name"
                                onkeypress='return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))'
                                pattern="^[A-Za-z -]+$" maxlength="255">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row required mb-2" aria-required="true">
                        <label class="col-form-label col-lg-6" for="gender">2. Gender / लिंग<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1">
                                <label class="form-check-label" for="inlineRadio1">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="2">
                                <label class="form-check-label" for="inlineRadio2">Female</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row required mb-2" aria-required="true">
                        <label class="col-form-label col-lg-6" for="email">2. ईमेल पता / Email ID<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="email"
                                oninput="this.value = this.value.toLowerCase();" required
                                pattern="^[^ ]+@[^ ]+\.[a-z]{2,6}$" name="email" placeholder="Email ID" maxlength="50">
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-12">
                    <div class="form-group row required mb-2" aria-required="true">
                        <label class="col-form-label col-lg-5" for="email">2. ईमेल पता / Email ID</label>
                        <div class="col-lg-6 float-end">
                            <input type="email" class="form-control" id="email" required pattern="^[^ ]+@[^ ]+\.[a-z]{2,6}$"  name="email" placeholder="Email ID" maxlength="255">

                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-12">
                    <div class="form-group row required  mb-2" aria-required="true">
                        <label class="col-form-label col-lg-6" for="mobileNumber">3. मोबाइल नंबर/Mobile Number<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <div class="input-group">
                                <div class="input-group-prepend"> <span class="input-group-text">+91</span> </div>
                                <input type="text" class="form-control" id="mobileNumber" name="mobile"required
                                    placeholder="Enter Mobile Number " maxlength="10"
                                    minlength="10"oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    pattern="[6-9][0-9]{9}$">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row required  mb-2" aria-required="true">
                        <label class="col-form-label col-lg-6" for="dob">4. Date of Birth/ जन्म तिथि <span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="date" class="form-control" id="dob" name="dob" required min="1926-01-01"
                   max="@php echo date('Y-m-d'); @endphp"
                                placeholder="Select date of birth">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row required  mb-2" aria-required="true">
                        <label class="col-form-label col-lg-6" for="aadhaar">4. Aadhaar Number / आधार नं0<span
                                class="text-danger">*</span></label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" id="aadhaar" name="aadhar" required
                                maxlength="12" minlength="12" pattern="[0-9]{12}"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');" placeholder="Enter Aadhaar No.">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row required mb-2" aria-required="true">
                        <label for="exampleFormControlInput2 " for="new_password" class="col-form-label col-md-6">5.
                            Password/पासवर्ड
                            <span class="text-danger">*</span></label>
                        <div class="mb-3 col-lg-6 d-flex">
                            <span class="input-group-text" id="basic-addon1"><span
                                    class="icons icon-lock-open"></span></span>
                            <input id="password" name="new_password" maxlength="15" autocomplete="new-password"
                                required type="password" class="form-control input-md" data-placement="bottom"
                                data-toggle="popover" data-container="body" type="button" data-html="true">
                            <span class="input-group-text toggle-password fa fa-eye" toggle="#password"></span>
                            {{-- <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group row required  mb-2" aria-required="true">
                        <label for="exampleFormControlInput3" class="col-form-label col-lg-6">6. Re-enter Password/पासवर्ड
                            पुनः भरें
                            <span class="text-danger">*</span></label>
                        <div class="mb-3 col-md-6 d-flex">
                            <span class="input-group-text" id="basic-addon1"><span
                                    class="icons icon-lock-open"></span></span>
                            <input type="password" name="confirm_password" maxlength="15" id="confirm-password"
                                autocomplete="new-password" required class="form-control" id="password-field2">
                            <span class="input-group-text toggle-password fa fa-eye" toggle="#confirm-password"></span>
                        </div>
                        <span class="text-danger" id="mismatch"></span>
                    </div>
                </div>
                <div id="popover-password">
                    <p>Password Strength: <span id="result"> </span></p>
                    <ul class="list-unstyled">
                        <li class=""><span class="low-upper-case"><i class="fa fa-file-text"
                                    aria-hidden="true"></i></span> 01 Lower and 01 Upper Case Character/01 अपर व 01 लोअर
                            केस का वर्ण</li>
                        <li class=""><span class="one-number"><i class="fa fa-file-text"
                                    aria-hidden="true"></i></span> 01 Numeric Character/01 संख्यात्मक वर्ण</li>
                        <li class=""><span class="one-special-char"><i class="fa fa-file-text"
                                    aria-hidden="true"></i></span> 01 Special Character/01 विशेष वर्ण</li>
                        <li class=""><span class="eight-character"><i class="fa fa-file-text"
                                    aria-hidden="true"></i></span> At least 08 Characters/न्यूनतम 08 वर्ण</li>
                    </ul>
                </div>
                <div class="col-lg-12">
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-5 col-6">
                                <div class="captcha">
                                    <label>7. Captcha/कैप्चा<span class="text-danger">*</span></label>
                                    <span class="unselectable"
                                        id="p_refresh">{{ $Code1 }}+{{ $Code2 }}</span>
                                    <input name="captchacode" type="hidden"
                                        class="form-control captchacode unselectable h4" value="{{ $Code1 + $Code2 }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-md-1 col-1 refresh">
                                <div class="cp_refresh"><a href="javascript:void(0)" class="refresh4554554"
                                        title="Refresh Captcha"><span class="fas fa-redo"></span></a></div>
                            </div>
                            <div class="col-md-6 col-5">
                                <label>8. Enter Captcha/कैप्चा भरें<span class="text-danger">*</span></label>
                                <input name="captcha" type="text" placeholder="Enter Captcha" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group mb-2">
                <div class="row">
                    <div class="col-md-12 d-grid"> <button type="submit" class="btn btn-danger rounded-pill"><i
                                class="fa fa-user-plus"></i> Register </button> </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('custom-scripts')
    <script>
        function getNativeOfup(value) {
            if (value == 2) {
                $("#nativealert").modal("toggle");
                $(".btn-outline-danger,.rounded-pill").prop("disabled", true);
            } else {
                $(".btn-outline-danger,.rounded-pill").prop("disabled", false);
            }
        }

        $(".refresh4554554").on("click", function() {
            $.ajax({
                type: "GET",
                url: ajaxUrl + "/coaching_camp/cp_refresh/",
                dataType: "json",
                success: function(res) {
                    console.log(res)
                    $("#p_refresh").html(res.Code1 + '+' + res.Code2);
                    $(".captchacode").val(res.Code1 + res.Code2);
                    // $(".refreshc").val(res.capchaCode);
                },
            });
        });

        $("#submitform").submit(function(e) {
            e.preventDefault();
            if ($("#submitform")[0].checkValidity() === false) {
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
            $("#submitform").addClass("was-validated");
        });
    </script>
    <script>
        function check_password(value) {
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,10}$/gm;
            if (!regex.test(value))
                alert(
                    'Minimum eight and maximum 15 characters, at least one uppercase letter, one lowercase letter, one number and one special character'
                );
        }
    </script>
    <script>
        window.onload = () => {
            $('#password').keyup(function() {
                var password = $('#password').val();
                $('#change_password1').attr('disabled', true);
                if (password == '') {
                    // $('#result').removeClass()
                    // $('#password-strength').removeClass();

                }
                if (checkStrength(password) == false) {

                    $('#change_password1').attr('disabled', true);
                }
            });

            $('#confirm-password').keyup(function() {
                var password = $('#password').val();

                if (password !== $('#confirm-password').val()) {
                    $("#mismatch").text('Password Mismatched');
                    $('#change_password1').attr('disabled', true);
                } else {
                    $("#mismatch").text('');
                    $('#change_password1').attr('disabled', false);
                }
            });

            function checkStrength(password) {
                var strength = 0;
                if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
                    strength += 1;
                    $('.low-upper-case').addClass('text-success');
                    $('.low-upper-case i').removeClass('fa-file-text').addClass('fa-check');
                    $('#popover-password-top').addClass('hide');


                } else {
                    $('.low-upper-case').removeClass('text-success');
                    $('.low-upper-case i').addClass('fa-file-text').removeClass('fa-check');
                    $('#popover-password-top').removeClass('hide');
                }

                if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)) {
                    strength += 1;
                    $('.one-number').addClass('text-success');
                    $('.one-number i').removeClass('fa-file-text').addClass('fa-check');
                    $('#popover-password-top').addClass('hide');

                } else {
                    $('.one-number').removeClass('text-success');
                    $('.one-number i').addClass('fa-file-text').removeClass('fa-check');
                    $('#popover-password-top').removeClass('hide');
                }
                if (password.match(/([@,$,!,%,*,?,&,#])/)) {
                    strength += 1;
                    $('.one-special-char').addClass('text-success');
                    $('.one-special-char i').removeClass('fa-file-text').addClass('fa-check');
                    $('#popover-password-top').addClass('hide');

                } else {
                    $('.one-special-char').removeClass('text-success');
                    $('.one-special-char i').addClass('fa-file-text').removeClass('fa-check');
                    $('#popover-password-top').removeClass('hide');
                }

                if (password.length > 7) {
                    strength += 1;
                    $('.eight-character').addClass('text-success');
                    $('.eight-character i').removeClass('fa-file-text').addClass('fa-check');
                    $('#popover-password-top').addClass('hide');

                } else {
                    $('.eight-character').removeClass('text-success');
                    $('.eight-character i').addClass('fa-file-text').removeClass('fa-check');
                    $('#popover-password-top').removeClass('hide');
                }

                if (strength < 2) {
                    $('#result').removeClass()
                    $('#password-strength').addClass('progress-bar-danger');

                    $('#result').addClass('text-danger').text('Very Weak');
                    $('#password-strength').css('width', '10%');
                } else if (strength == 2) {
                    $('#result').addClass('good');
                    $('#password-strength').removeClass('progress-bar-danger');
                    $('#password-strength').addClass('progress-bar-warning');
                    $('#result').addClass('text-warning').text('Weak')
                    $('#password-strength').css('width', '60%');
                    return 'Weak'
                } else if (strength == 4) {
                    $('#result').removeClass()
                    $('#result').addClass('strong');
                    $('#password-strength').removeClass('progress-bar-warning');
                    $('#password-strength').addClass('progress-bar-success');
                    $('#result').addClass('text-success').text('Strength');
                    $('#password-strength').css('width', '100%');

                    return 'Strong'
                }
            }
        }

        $('#email').on('input', function() {
            $(this).val($(this).val().toLowerCase());
        });
    </script>
@endpush
