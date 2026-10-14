@extends('layouts.onlineAdmissionTestNav')
@section('content')

<style>
@media print {
    @page { margin: 6mm 8mm; }
    .no-print { display: none !important; }
    header, footer, nav { display: none !important; }
    .contentwraper { padding: 0 !important; margin: 0 !important; }
    body { margin: 0 !important; padding: 0 !important; }
    .admit-box { border: 1.5px solid #000 !important; box-shadow: none !important; max-width: 100% !important; margin: 0 !important; }
    .admit-header { padding: 8px 12px !important; }
    .admit-header h5 { font-size: 13px !important; }
    .admit-header p { font-size: 11px !important; }
    .section-title { padding: 3px 8px !important; font-size: 11px !important; }
    .admit-table td { padding: 3px 8px !important; font-size: 11px !important; }
    .enroll-badge { font-size: 13px !important; padding: 3px 12px !important; }
    .px-4 { padding-left: 10px !important; padding-right: 10px !important; }
    .py-3 { padding-top: 6px !important; padding-bottom: 6px !important; }
    .photo-box { width: 85px !important; height: 100px !important; }
    ol li { font-size: 10.5px !important; margin-bottom: 1px !important; }
}
.admit-box { max-width: 820px; margin: 0 auto; border: 2px solid #1a7a3c; }
.admit-header { background: #1a7a3c; color: #fff; padding: 14px 20px; }
.admit-header h5, .admit-header p { margin: 0; }
.section-title { background: #f0f8f0; font-weight: 700; border-bottom: 1px solid #ccc; padding: 6px 12px; font-size: 13px; letter-spacing: .4px; }
.admit-table td { padding: 6px 12px; font-size: 13px; vertical-align: middle; }
.admit-table .label { width: 42%; font-weight: 600; background: #f9f9f9; }
.photo-box { width: 110px; height: 130px; border: 2px solid #555; object-fit: cover; }
.enroll-badge { background: #1a7a3c; color: #fff; font-size: 15px; font-weight: 700; padding: 6px 20px; border-radius: 4px; display: inline-block; letter-spacing: 1px; }
</style>

<div class="row mb-3 no-print">
    <div class="col-12">
        <div class="pageheader pb-2">
            <h4>Admit Card / प्रवेश पत्र
                <div class="float-end d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-success btn-sm rounded-pill">
                        <i class="fa fa-print"></i> Print / प्रिंट करें
                    </button>
                    <a href="{{ route('onlineAdmissionTest.dashboard') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                        <i class="fa fa-arrow-left"></i> Dashboard
                    </a>
                </div>
            </h4>
        </div>
    </div>
</div>

<div class="card admit-box shadow">
    <div class="card-body p-0">

        {{-- Header --}}
        <div class="admit-header d-flex align-items-center gap-3">
            <img src="{{ asset('onlineAdmission_storage/images/dash-logo.png') }}" style="height:60px; background:#fff; padding:3px; border-radius:4px;">
            <div>
                <h5 class="fw-bold">Department of Sports, Government of Uttar Pradesh</h5>
                <p style="font-size:13px; opacity:.9;">खेल विभाग, उत्तर प्रदेश सरकार</p>
                <p class="fw-bold mb-0" style="font-size:15px; letter-spacing:.5px;">ADMIT CARD / प्रवेश पत्र — Sports College Admission Trial</p>
            </div>
        </div>

        {{-- Enroll No & Photo Row --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3 border-bottom" style="background:#fffdf0;">
            <div>
                <div class="text-muted small mb-1">Enrollment No. / नामांकन संख्या</div>
                <span class="enroll-badge">{{ $data->enroll_no ?? 'N/A' }}</span>
                <div class="mt-2 text-muted small">Application No.: <strong>{{ $data->application_no ?? 'N/A' }}</strong></div>
            </div>
            <div class="text-center">
                @if(!empty($data->photo) && file_exists(public_path('onlineAdmission_storage/images/' . $data->photo)))
                    <img src="{{ asset('onlineAdmission_storage/images/' . $data->photo) }}" class="photo-box" alt="Applicant Photo">
                @else
                    <div class="photo-box d-flex align-items-center justify-content-center text-muted" style="font-size:11px;">No Photo</div>
                @endif
                <div class="text-muted small mt-1">Applicant Photo</div>
            </div>
        </div>

        {{-- Applicant Details --}}
        <div class="section-title">Applicant Details / आवेदक विवरण</div>
        <table class="table table-bordered admit-table mb-0">
            <tr>
                <td class="label">Full Name / पूरा नाम</td>
                <td><strong>{{ $data->fullname ?? 'N/A' }}</strong></td>
                <td class="label">Date of Birth / जन्मतिथि</td>
                <td>{{ $data->dob ? date('d-m-Y', strtotime($data->dob)) : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Mobile No. / मोबाइल</td>
                <td>{{ $data->mobile ?? 'N/A' }}</td>
                <td class="label">Email / ईमेल</td>
                <td>{{ $data->email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Category / वर्ग</td>
                <td>{{ $data->category ?? 'N/A' }}</td>
                <td class="label">District / जनपद</td>
                <td>{{ $data->district_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Sport / खेल</td>
                <td>{{ $data->sport_name ?? 'N/A' }}</td>
                <td class="label">Class / कक्षा</td>
                <td>{{ $data->admission_seeking ? 'Class ' . $data->admission_seeking . 'th' : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Sub-Sport / उप खेल</td>
                <td colspan="3">{{ $data->sub_sport_name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Address / पता</td>
                <td colspan="3">
                    @php
                        $address = collect([
                            $data->p_gram ?? null,
                            $data->p_post ?? null,
                            $data->district_name ?? null,
                            $data->state_name ?? null,
                            $data->p_pin ? 'PIN: ' . $data->p_pin : null,
                        ])->filter()->implode(', ');
                    @endphp
                    {{ $address ?: 'N/A' }}
                </td>
            </tr>
        </table>

        {{-- Trial Venue Details --}}
        <div class="section-title mt-0">Trial Venue Details / चयन परीक्षा स्थल विवरण</div>
        <table class="table table-bordered admit-table mb-0">
            @if($divisionName)
            <tr>
                <td class="label">Division / मंडल</td>
                <td colspan="3"><strong>{{ $divisionName }}</strong></td>
            </tr>
            @endif
            <tr>
                <td class="label">Venue / स्थल</td>
                <td colspan="3">
                    @if(!empty($data->trial_location))
                        <strong>{{ $data->trial_location }}</strong>
                    @else
                        <span class="text-muted">To be announced / शीघ्र घोषित होगा</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Trial Date / परीक्षा तिथि</td>
                <td colspan="3">
                    @if(!empty($data->trial_from_date) && $data->trial_from_date !== '0000-00-00')
                        @php
                            $from = \Carbon\Carbon::parse($data->trial_from_date);
                            $to   = (!empty($data->trial_to_date) && $data->trial_to_date !== '0000-00-00' && $data->trial_to_date !== $data->trial_from_date)
                                    ? \Carbon\Carbon::parse($data->trial_to_date) : null;
                        @endphp
                        <strong>{{ $from->format('jS F Y') }}{{ $to ? ' & ' . $to->format('jS F Y') : '' }}</strong>
                    @else
                        <span class="text-muted">To be announced / शीघ्र घोषित होगा</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Reporting Time / समय</td>
                <td colspan="3">
                    @if(!empty($data->trial_time))
                        <strong>{{ $data->trial_time }}</strong>
                    @else
                        <span class="text-muted">To be announced / शीघ्र घोषित होगा</span>
                    @endif
                </td>
            </tr>
        </table>

        {{-- Payment Confirmation --}}
        @if($payment)
        <div class="section-title">Payment Confirmation / भुगतान पुष्टि</div>
        <table class="table table-bordered admit-table mb-0">
            <tr>
                <td class="label">Paid Amount / भुगतान राशि</td>
                <td>₹ {{ number_format($payment->paidAmount, 2) }}</td>
                <td class="label">Payment Date / भुगतान तिथि</td>
                <td>{{ $payment->transDate ? date('d-m-Y', strtotime($payment->transDate)) : 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Transaction ID</td>
                <td>{{ $payment->SabPaisaTxnId ?? 'N/A' }}</td>
                <td class="label">Status / स्थिति</td>
                <td><span class="badge bg-success">{{ $payment->status }}</span></td>
            </tr>
        </table>
        @endif

        {{-- Instructions --}}
        <div class="section-title">Important Instructions / महत्वपूर्ण निर्देश</div>
        <div class="px-4 py-3" style="font-size:12.5px;">
            <ol class="mb-0 ps-3">
                <li>Candidates must carry this Admit Card on the day of the trial. / अभ्यर्थी को चयन परीक्षा के दिन यह प्रवेश पत्र अनिवार्य रूप से लाना होगा।</li>
                <li>Original documents must be brought for verification. / मूल दस्तावेज सत्यापन के लिए अवश्य लाएं।</li>
                <li>Candidates must report 30 minutes before the scheduled time. / अभ्यर्थी निर्धारित समय से 30 मिनट पहले उपस्थित हों।</li>
                <li>Mobile phones and electronic devices are not allowed inside the trial venue. / परीक्षा स्थल के अंदर मोबाइल फोन एवं इलेक्ट्रॉनिक उपकरण वर्जित हैं।</li>
            </ol>
        </div>

        {{-- Footer --}}
        <div class="text-center border-top py-3 px-4" style="font-size:11.5px; color:#555;">
            This is a computer-generated Admit Card and does not require a physical signature.<br>
            यह एक कंप्यूटर जनित प्रवेश पत्र है, इस पर भौतिक हस्ताक्षर की आवश्यकता नहीं है।
        </div>

    </div>
</div>

@endsection
