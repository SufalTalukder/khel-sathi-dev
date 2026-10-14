import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import CryptoJS from 'crypto-js';

export default function Login({ Code1 = 25, Code2 = 14, capchaCode = 39 }) {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [captcha, setCaptcha] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [currentCode1, setCurrentCode1] = useState(Code1);
    const [currentCode2, setCurrentCode2] = useState(Code2);
    const [currentCapchaCode, setCurrentCapchaCode] = useState(capchaCode);
    const [loading, setLoading] = useState(false);
    const [showModal, setShowModal] = useState(false);

    // Refresh Captcha via API or client fallback
    const refreshCaptcha = async () => {
        try {
            const res = await window.axios.get('/khelsathi-department/refresh-captcha');
            if (res.data) {
                setCurrentCode1(res.data.Code1);
                setCurrentCode2(res.data.Code2);
                setCurrentCapchaCode(res.data.capchaCode);
            }
        } catch (err) {
            const c1 = Math.floor(Math.random() * 89) + 11;
            const c2 = Math.floor(Math.random() * 89) + 11;
            setCurrentCode1(c1);
            setCurrentCode2(c2);
            setCurrentCapchaCode(c1 + c2);
        }
        setCaptcha('');
    };

    // AES-128-ECB Encryption (Matches backend openssl_decrypt key)
    const encryptAES = (plainText) => {
        const encryptionKey = '2b7e151628aed2a6abf7158809cf4f3c';
        const keyHex = CryptoJS.enc.Hex.parse(encryptionKey);
        const encrypted = CryptoJS.AES.encrypt(plainText, keyHex, {
            mode: CryptoJS.mode.ECB,
        });
        return encrypted.toString();
    };

    const handleLoginSubmit = async (e) => {
        e.preventDefault();

        if (!email.trim()) {
            toastr.error('Please Enter Registered Email ID. / कृपया पंजीकृत ईमेल आईडी भरें।');
            return;
        }

        if (!password) {
            toastr.error('Please Enter Password. / कृपया पासवर्ड भरें।');
            return;
        }

        if (!captcha) {
            toastr.error('Please Enter Captcha. / कृपया कैप्चा भरें।');
            return;
        }

        if (parseInt(captcha) !== parseInt(currentCapchaCode)) {
            toastr.error('Please Enter Valid Captcha. / कृपया सही कैप्चा भरें।');
            refreshCaptcha();
            return;
        }

        setLoading(true);

        try {
            const encryptedEmail = encryptAES(email.trim().toLowerCase());
            const encryptedPassword = encryptAES(password);

            const payload = {
                email: encryptedEmail,
                password: encryptedPassword,
                captcha: captcha,
                capchaCode: currentCapchaCode,
                type: 1,
                form_type: 1,
            };

            const response = await window.axios.post('/admin/login', payload);

            if (response.data && response.data.error) {
                toastr.error(response.data.msg || 'Invalid Credentials. / कृपया सही लॉगिन विवरण भरें।');
                refreshCaptcha();
            } else if (response.data && response.data.error === false) {
                toastr.success(response.data.msg || 'Login successful! Redirecting...');
                const targetRoute = response.data.url || response.data.route;
                setTimeout(() => {
                    const redirectUrl = targetRoute && (targetRoute.includes('/admin/dashboard') || targetRoute.includes('/khelsathi-department/dashboard') || targetRoute.includes('/inertia/admin/dashboard'))
                        ? '/khelsathi-department/dashboard'
                        : (targetRoute && targetRoute.startsWith('/khelsathi-department') ? targetRoute : '/khelsathi-department/dashboard');

                    if (redirectUrl.startsWith('/khelsathi-department') || redirectUrl.startsWith('/inertia')) {
                        router.visit(redirectUrl, { replace: true });
                    } else if (targetRoute) {
                        window.location.href = targetRoute;
                    } else {
                        router.visit('/khelsathi-department/dashboard', { replace: true });
                    }
                }, 600);
            }
        } catch (error) {
            toastr.error(error.response?.data?.msg || 'Something went wrong during login. Please try again.');
            refreshCaptcha();
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="ks-login-wrapper">
            <Head title="Admin Login" />

            <main className="ks-main-container">
                <div className="ks-login-grid">
                    {/* Left Brand & Artwork Hero */}
                    <div className="ks-brand-sidebar">
                        <div className="ks-brand-header">
                            <img
                                src="/assets_admin/images/logo.png"
                                alt="Department of Sports Logo"
                                className="ks-brand-logo"
                            />
                            <h1 className="ks-portal-title">
                                Khel Sathi Portal
                                <span style={{ display: 'block', fontSize: '1.25rem', fontWeight: 700, color: '#f97316', marginTop: '0.2rem' }}>
                                    खेल साथी पोर्टल
                                </span>
                            </h1>
                            <h2 className="ks-dept-title">
                                Department of Sports, Government of Uttar Pradesh
                                <span style={{ display: 'block', fontSize: '0.95rem', fontWeight: 600, color: '#16a34a', marginTop: '0.15rem' }}>
                                    खेल विभाग, उत्तर प्रदेश सरकार
                                </span>
                            </h2>
                            <p className="ks-dept-subtitle">
                                Nomination Form to Seek Reward from Government of UP
                                <br />
                                उ0प्र0 सरकार से पुरस्कार प्राप्त करने हेतु नामांकन प्रपत्र
                            </p>
                        </div>

                        {/* Sports Artwork Section - Placed cleanly below header without text overlap */}
                        <div className="ks-art-wrapper">
                            <img
                                src="/assets_admin/images/output-onlinepngtools-2.png"
                                alt="Uttar Pradesh Sports Illustration"
                                className="ks-sports-art"
                            />
                            <div>
                                <button
                                    type="button"
                                    className="ks-guidelines-trigger"
                                    onClick={() => setShowModal(true)}
                                >
                                    <i className="fas fa-info-circle"></i>
                                    <span>Portal Guidelines & Awards / दिशानिर्देश</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {/* Right Login Form Card */}
                    <div className="ks-form-column">
                        <div className="ks-form-header">
                            <div className="ks-badge-official">
                                <span className="ks-badge-dot"></span>
                                Official Login Portal
                            </div>
                            <h2 className="ks-form-title">
                                Applicant's Login
                                <span style={{ display: 'block', fontSize: '1.15rem', color: '#64748b', fontWeight: 600, marginTop: '0.2rem' }}>
                                    आवेदक का लॉगिन
                                </span>
                            </h2>
                            <p className="ks-form-register-link">
                                Don't have an account?{' '}
                                <a href="/signUp">Register Here</a>
                                <span style={{ display: 'block', fontSize: '0.82rem', marginTop: '0.2rem' }}>
                                    अकाउंट नहीं है? <a href="/signUp">अभी पंजीकरण करें</a>
                                </span>
                            </p>
                        </div>

                        <form onSubmit={handleLoginSubmit} noValidate>
                            {/* Email Input */}
                            <div className="ks-form-group">
                                <label htmlFor="email" className="ks-label">
                                    <span>1. Registered Email ID <span style={{ color: '#ef4444' }}>*</span></span>
                                    <span style={{ color: '#94a3b8', fontWeight: 500 }}>पंजीकृत ईमेल आईडी</span>
                                </label>
                                <div className="ks-input-wrapper">
                                    <i className="fas fa-envelope ks-input-icon"></i>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        className="ks-input"
                                        placeholder="name@example.com"
                                        value={email}
                                        onChange={(e) => setEmail(e.target.value.toLowerCase())}
                                        maxLength={50}
                                        required
                                    />
                                </div>
                            </div>

                            {/* Password Input */}
                            <div className="ks-form-group" style={{ marginBottom: '0.75rem' }}>
                                <label htmlFor="password-field" className="ks-label">
                                    <span>2. Password <span style={{ color: '#ef4444' }}>*</span></span>
                                    <span style={{ color: '#94a3b8', fontWeight: 500 }}>पासवर्ड</span>
                                </label>
                                <div className="ks-input-wrapper">
                                    <i className="fas fa-lock ks-input-icon"></i>
                                    <input
                                        type={showPassword ? 'text' : 'password'}
                                        id="password-field"
                                        name="password"
                                        className="ks-input"
                                        placeholder="••••••••"
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        required
                                    />
                                    <button
                                        type="button"
                                        className="ks-toggle-pwd"
                                        onClick={() => setShowPassword(!showPassword)}
                                        title={showPassword ? 'Hide Password' : 'Show Password'}
                                    >
                                        <i className={`fas ${showPassword ? 'fa-eye-slash' : 'fa-eye'}`}></i>
                                    </button>
                                </div>
                            </div>

                            {/* Forgot Password Link */}
                            <div className="ks-forgot-pwd">
                                <a href="/forgot">
                                    Forgot Password? / पासवर्ड भूल गए?
                                </a>
                            </div>

                            {/* Captcha Section */}
                            <div className="ks-form-group" style={{ marginBottom: '1.5rem' }}>
                                <label className="ks-label">
                                    <span>3. Solve Captcha <span style={{ color: '#ef4444' }}>*</span></span>
                                    <span style={{ color: '#94a3b8', fontWeight: 500 }}>कैप्चा हल करें</span>
                                </label>
                                <div className="ks-captcha-row">
                                    <div className="ks-captcha-badge">
                                        {currentCode1} + {currentCode2}
                                    </div>
                                    <button
                                        type="button"
                                        className="ks-captcha-refresh"
                                        onClick={refreshCaptcha}
                                        title="Generate New Captcha"
                                    >
                                        <i className="fas fa-sync-alt"></i>
                                    </button>
                                    <div className="ks-captcha-input-box">
                                        <input
                                            type="text"
                                            id="captcha"
                                            name="captcha"
                                            className="ks-captcha-input"
                                            placeholder="Result ?"
                                            value={captcha}
                                            onChange={(e) => setCaptcha(e.target.value)}
                                            required
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Submit Button */}
                            <button
                                type="submit"
                                className="ks-submit-btn"
                                disabled={loading}
                            >
                                {loading ? (
                                    <>
                                        <span className="spinner-border spinner-border-sm me-2" role="status"></span>
                                        <span>Logging in...</span>
                                    </>
                                ) : (
                                    <>
                                        <span>Login / लॉगिन करें</span>
                                        <i className="fas fa-arrow-right" style={{ fontSize: '0.9rem' }}></i>
                                    </>
                                )}
                            </button>
                        </form>
                    </div>
                </div>
            </main>

            {/* Official Footer */}
            <div className="ks-footer" role="contentinfo">
                <div>
                    Copyright &copy; {new Date().getFullYear()} Department of Sports, Government of Uttar Pradesh / खेल विभाग, उत्तर प्रदेश सरकार. All Rights Reserved.
                </div>
            </div>

            {/* Guidelines & Awards Modal */}
            {showModal && (
                <div
                    className="modal fade show d-block"
                    tabIndex="-1"
                    style={{ backgroundColor: 'rgba(15, 23, 42, 0.65)', backdropFilter: 'blur(4px)', zIndex: 1050 }}
                >
                    <div className="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                        <div className="modal-content" style={{ borderRadius: '1.25rem', border: 'none', boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)' }}>
                            <div className="modal-header" style={{ borderBottom: '1px solid #e2e8f0', padding: '1.25rem 1.75rem' }}>
                                <h5 className="modal-title fw-bold" style={{ color: '#1e3a8a' }}>
                                    <i className="fas fa-award text-warning me-2"></i>
                                    Khel Sathi Portal - Guidelines & Awards
                                </h5>
                                <button
                                    type="button"
                                    className="btn-close"
                                    onClick={() => setShowModal(false)}
                                    aria-label="Close"
                                ></button>
                            </div>
                            <div className="modal-body p-4">
                                <div className="row g-4">
                                    <div className="col-md-6 border-end pe-md-4">
                                        <h4 className="text-primary mb-3" style={{ fontWeight: 700 }}>About Khel Sathi Portal</h4>
                                        <p style={{ lineHeight: '1.6', color: '#475569' }}>
                                            Khel Sathi is the official web portal governed by the Department of Sports, Government of Uttar Pradesh, providing seamless departmental services online to sportspersons who are natives of Uttar Pradesh.
                                        </p>
                                        <h6 className="fw-bold text-dark mt-4 mb-2">Available Honors & Awards:</h6>
                                        <ul style={{ color: '#475569', lineHeight: '1.8' }}>
                                            <li><strong>Laxman Award:</strong> For distinguished male sportspersons</li>
                                            <li><strong>Rani Laxmibai Award:</strong> For distinguished female sportspersons</li>
                                            <li><strong>Prize Money:</strong> For 1st, 2nd, and 3rd position championship winners</li>
                                        </ul>
                                    </div>
                                    <div className="col-md-6 ps-md-4">
                                        <h4 className="text-success mb-3" style={{ fontWeight: 700 }}>खेल साथी पोर्टल के बारे में</h4>
                                        <p style={{ lineHeight: '1.6', color: '#475569' }}>
                                            खेल साथी, खेल विभाग, उत्तर प्रदेश सरकार द्वारा संचालित आधिकारिक वेब पोर्टल है। यह उत्तर प्रदेश के मूल निवासी खिलाड़ियों एवं लाभार्थियों को डिजिटल माध्यम से सुविधाएं प्रदान करता है।
                                        </p>
                                        <h6 className="fw-bold text-dark mt-4 mb-2">प्रमुख पुरस्कार एवं सम्मान:</h6>
                                        <ul style={{ color: '#475569', lineHeight: '1.8' }}>
                                            <li><strong>लक्ष्मण पुरस्कार:</strong> उत्कृष्ट पुरुष खिलाड़ियों हेतु</li>
                                            <li><strong>रानी लक्ष्मीबाई पुरस्कार:</strong> उत्कृष्ट महिला खिलाड़ियों हेतु</li>
                                            <li><strong>पुरस्कार राशि:</strong> प्रथम, द्वितीय एवं तृतीय स्थान विजेताओं हेतु</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div className="modal-footer" style={{ borderTop: '1px solid #e2e8f0', padding: '1rem 1.75rem' }}>
                                <button
                                    type="button"
                                    className="btn btn-secondary px-4 fw-semibold"
                                    style={{ borderRadius: '0.5rem' }}
                                    onClick={() => setShowModal(false)}
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}