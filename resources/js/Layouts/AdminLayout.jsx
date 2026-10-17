import React, { useState, useEffect } from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function AdminLayout({ children, menu: propMenu, routes: propRoutes }) {
    const { auth, menu: sharedMenu } = usePage().props;
    const { url } = usePage();
    const admin = auth?.admin;

    const [sidebarOpen, setSidebarOpen] = useState(true);
    const [profileOpen, setProfileOpen] = useState(false);
    const [expandedMenus, setExpandedMenus] = useState({});
    const [isFeedbackModalOpen, setIsFeedbackModalOpen] = useState(false);
    const [feedbackText, setFeedbackText] = useState('');
    const [feedbackToast, setFeedbackToast] = useState(null);

    const handleFeedbackSubmit = (e) => {
        e.preventDefault();
        setIsFeedbackModalOpen(false);
        setFeedbackText('');
        setFeedbackToast('Thank you for your feedback! Your suggestion has been recorded.');
        setTimeout(() => setFeedbackToast(null), 4000);
    };

    const menuItems = (propMenu && propMenu.length > 0) ? propMenu : (sharedMenu || []);

    // Check if a page URL matches current active URL
    const isUrlActive = (pageUrl) => {
        if (!pageUrl) return false;
        let cleanPageUrl = pageUrl;
        try {
            if (cleanPageUrl.startsWith('http://') || cleanPageUrl.startsWith('https://')) {
                cleanPageUrl = new URL(cleanPageUrl).pathname;
            }
        } catch (e) { }

        const normPage = cleanPageUrl.replace(/^\/+|\/+$/g, '').toLowerCase();
        const currentPath = (url || (typeof window !== 'undefined' ? window.location.pathname : ''))
            .split('?')[0]
            .replace(/^\/+|\/+$/g, '')
            .toLowerCase();

        if (!normPage || !currentPath) return false;
        if (normPage === currentPath) return true;
        if (currentPath.includes('module-manager') && (normPage.includes('module-manager') || normPage.includes('module_manager'))) {
            return true;
        }
        return currentPath.endsWith(normPage) || normPage.endsWith(currentPath);
    };

    // Auto-expand parent module if current URL matches one of its pages
    useEffect(() => {
        menuItems.forEach((mod) => {
            if (mod.pages && mod.pages.some((p) => isUrlActive(p.page_url))) {
                setExpandedMenus((prev) => ({ ...prev, [mod.id]: true }));
            }
        });
    }, [menuItems, url]);

    // Toggle menu dropdown
    const toggleSubmenu = (menuId) => {
        setExpandedMenus((prev) => ({
            ...prev,
            [menuId]: !prev[menuId],
        }));
    };

    // Close profile dropdown when clicking outside
    useEffect(() => {
        const handleClickOutside = (e) => {
            if (profileOpen && !e.target.closest('.ks-profile-dropdown-container')) {
                setProfileOpen(false);
            }
        };
        document.addEventListener('click', handleClickOutside);
        return () => document.removeEventListener('click', handleClickOutside);
    }, [profileOpen]);

    // Format last login
    const formatLastLogin = (dateStr) => {
        if (!dateStr) return 'N/A';
        try {
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            const day = String(d.getDate()).padStart(2, '0');
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const month = months[d.getMonth()];
            const year = d.getFullYear();
            let hours = d.getHours();
            const minutes = String(d.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            const strHours = String(hours).padStart(2, '0');
            return `${day} ${month} ${year} ${strHours}:${minutes} ${ampm}`;
        } catch {
            return dateStr;
        }
    };

    // Clean FontAwesome icon mapping for each module to prevent corrupted legacy glyphs
    const getModuleIcon = (moduleName, defaultIcon) => {
        const name = (moduleName || '').toLowerCase();
        if (name.includes('user')) return 'fa fa-users';
        if (name.includes('recruitment')) return 'fa fa-user-plus';
        if (name.includes('master')) return 'fa fa-sliders';
        if (name.includes('position')) return 'fa fa-trophy';
        if (name.includes('financial')) return 'fa fa-handshake-o';
        if (name.includes('award') || name.includes('laxman') || name.includes('laxmibai')) return 'fa fa-star';
        if (name.includes('hostel')) return 'fa fa-building';
        if (name.includes('admission')) return 'fa fa-graduation-cap';
        if (name.includes('stock')) return 'fa fa-archive';
        if (name.includes('eklavya') || name.includes('kosh')) return 'fa fa-shield';
        if (name.includes('message')) return 'fa fa-bullhorn';
        if (name.includes('honorable') || name.includes('information')) return 'fa fa-info-circle';
        if (name.includes('calendar')) return 'fa fa-calendar';
        if (name.includes('facility') || name.includes('booking')) return 'fa fa-map-marker';
        if (name.includes('institution') || name.includes('registration')) return 'fa fa-university';
        if (defaultIcon && defaultIcon.startsWith('fa ')) return defaultIcon;
        return 'fa fa-folder-open-o';
    };

    const usernameDisplay = admin?.username
        ? admin.username.replace(/_/g, ' ')
        : 'Admin';


    // Current formatted date for top bar
    const currentDateFormatted = new Date().toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    });

    return (
        <div className="ks-admin-body">
            {/* Top Navigation Bar */}
            <header className={`ks-modern-header ${!sidebarOpen ? 'fullwidth' : ''}`}>
                <div className="d-flex align-items-center gap-3">
                    <button
                        type="button"
                        className="ks-toggle-btn"
                        onClick={() => setSidebarOpen(!sidebarOpen)}
                        aria-label="Toggle Navigation Sidebar"
                        title={sidebarOpen ? 'Collapse Sidebar' : 'Expand Sidebar'}
                    >
                        <i className={`fa ${sidebarOpen ? 'fa-bars' : 'fa-indent'}`}></i>
                    </button>

                    <div>
                        <div className="d-flex align-items-center gap-2">
                            <h5 className="mb-0 fw-bold text-dark" style={{ fontSize: '1.05rem', letterSpacing: '-0.01em' }}>
                                Department of Sports
                            </h5>
                            <span className="ks-header-badge-official d-none d-md-inline-flex">
                                Govt. of Uttar Pradesh
                            </span>
                        </div>
                        <div className="d-flex align-items-center gap-1 text-muted" style={{ fontSize: '0.74rem' }}>
                            <span>Portal Overview</span>
                            <span>/</span>
                            <span className="text-primary fw-semibold">Directorate Dashboard</span>
                        </div>
                    </div>
                </div>

                {/* Right Top Actions */}
                <div className="d-flex align-items-center gap-3">
                    {/* Date Pill */}
                    <div className="d-none d-lg-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill border" style={{ fontSize: '0.78rem', color: '#475569' }}>
                        <i className="fa fa-calendar-o text-primary"></i>
                        <span className="fw-semibold">{currentDateFormatted}</span>
                    </div>

                    {/* Profile Dropdown Container */}
                    <div className="ks-profile-dropdown-container position-relative">
                        <button
                            type="button"
                            className="ks-header-profile-btn border-0"
                            onClick={() => setProfileOpen(!profileOpen)}
                            aria-expanded={profileOpen}
                        >
                            <div className="ks-user-avatar-badge" style={{ width: '34px', height: '34px', fontSize: '0.8rem' }}>
                                {usernameDisplay.substring(0, 2).toUpperCase()}
                            </div>
                            <div className="d-none d-sm-block text-start me-1">
                                <div className="fw-bold text-dark" style={{ fontSize: '0.82rem', lineHeight: 1.2 }}>
                                    {usernameDisplay}
                                </div>
                                <div className="text-muted" style={{ fontSize: '0.7rem' }}>
                                    {admin?.designation || 'Directorate'}
                                </div>
                            </div>
                            <i className="fa fa-angle-down text-muted ms-1" style={{ fontSize: '0.8rem' }}></i>
                        </button>

                        {/* Modern Glass Dropdown Menu */}
                        {profileOpen && (
                            <div className="ks-profile-dropdown-card">
                                <div className="p-3 border-bottom" style={{ background: 'linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%)' }}>
                                    <div className="d-flex align-items-center gap-2 mb-2">
                                        <div className="ks-user-avatar-badge" style={{ width: '40px', height: '40px' }}>
                                            {usernameDisplay.substring(0, 2).toUpperCase()}
                                        </div>
                                        <div>
                                            <h6 className="mb-0 fw-bold text-dark text-capitalize">{usernameDisplay}</h6>
                                            <span className="badge bg-primary-subtle text-primary fw-semibold" style={{ fontSize: '0.7rem' }}>
                                                {admin?.designation || 'Directorate Admin'}
                                            </span>
                                        </div>
                                    </div>
                                    <div className="p-2 bg-white rounded border" style={{ fontSize: '0.72rem', color: '#64748b', lineHeight: 1.4 }}>
                                        <div className="fw-semibold text-dark mb-1">
                                            <i className="fa fa-clock-o text-primary me-1"></i>
                                            Last Login / अंतिम लॉगिन:
                                        </div>
                                        <div>{formatLastLogin(admin?.last_login)}</div>
                                    </div>
                                </div>

                                <div className="p-2">
                                    <a
                                        href={propRoutes?.adminprofile || '/admin/profile'}
                                        className="d-flex align-items-center gap-2 px-3 py-2 rounded text-decoration-none text-dark hover-bg-light"
                                        style={{ fontSize: '0.82rem', fontWeight: 600 }}
                                    >
                                        <i className="fa fa-user-circle text-primary" style={{ width: '18px' }}></i>
                                        <span>My Profile / प्रोफ़ाइल</span>
                                    </a>
                                    <a
                                        href={propRoutes?.adminChangePassword || '/admin/change-password'}
                                        className="d-flex align-items-center gap-2 px-3 py-2 rounded text-decoration-none text-dark hover-bg-light"
                                        style={{ fontSize: '0.82rem', fontWeight: 600 }}
                                    >
                                        <i className="fa fa-key text-warning" style={{ width: '18px' }}></i>
                                        <span>Change Password / पासवर्ड बदलें</span>
                                    </a>
                                    <div className="border-top my-1"></div>
                                    <Link
                                        href={propRoutes?.signOuts || '/khelsathi-department/logout'}
                                        className="d-flex align-items-center gap-2 px-3 py-2 rounded text-decoration-none text-danger hover-bg-danger-light"
                                        style={{ fontSize: '0.82rem', fontWeight: 700 }}
                                    >
                                        <i className="fa fa-power-off text-danger" style={{ width: '18px' }}></i>
                                        <span>Logout / लॉगआउट</span>
                                    </Link>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </header>

            {/* Left Modern Sidebar */}
            <aside className={`ks-modern-sidebar ${!sidebarOpen ? 'closed' : ''}`}>
                {/* Logo & Portal Branding */}
                <div className="ks-sidebar-header">
                    <img
                        src="/assets_admin/images/logo.png"
                        alt="UP Sports Logo"
                        className="ks-sidebar-logo"
                    />
                    <div>
                        <h4 className="ks-sidebar-brand-title">Khel Sathi Portal</h4>
                        <span className="ks-sidebar-brand-sub">Department of Sports, UP</span>
                    </div>
                </div>

                {/* User Status Card */}
                <div className="ks-sidebar-user-pill">
                    <div className="ks-user-avatar-badge">
                        {usernameDisplay.substring(0, 2).toUpperCase()}
                    </div>
                    <div className="ks-user-info-text">
                        <div className="ks-user-name">{usernameDisplay}</div>
                        <span className="ks-user-status-dot">
                            Active Admin
                        </span>
                    </div>
                </div>

                {/* Sidebar Navigation */}
                <div className="ks-sidebar-nav-scroll">
                    <div className="ks-nav-group-label">Core Navigation</div>

                    {(() => {
                        const isDashboard = (url || '').includes('/dashboard');
                        return (
                            <Link
                                href="/khelsathi-department/dashboard"
                                className={`ks-nav-item-btn ${isDashboard ? 'active' : ''}`}
                            >
                                <div className="d-flex align-items-center">
                                    <span className="ks-nav-icon-box">
                                        <i className="fa fa-th-large"></i>
                                    </span>
                                    <span>Dashboard</span>
                                </div>
                                <i className="fa fa-angle-right" style={{ fontSize: '0.8rem', opacity: 0.7 }}></i>
                            </Link>
                        );
                    })()}

                    <div className="ks-nav-group-label mt-2">Department Modules</div>

                    {/* Dynamic Modules Menu with FontAwesome icons */}
                    {menuItems.map((module) => {
                        const isExpanded = !!expandedMenus[module.id];
                        const iconClass = getModuleIcon(module.module_name, module.menu_icon);
                        const hasPages = module.pages && module.pages.length > 0;
                        const hasActiveChild = module.pages && module.pages.some((p) => isUrlActive(p.page_url));

                        return (
                            <div key={module.id} className="mb-1">
                                <button
                                    type="button"
                                    onClick={() => toggleSubmenu(module.id)}
                                    className={`ks-nav-item-btn ${isExpanded ? 'bg-white-10 text-white' : ''} ${hasActiveChild ? 'has-active-child' : ''}`}
                                >
                                    <div className="d-flex align-items-center" style={{ minWidth: 0 }}>
                                        <span className="ks-nav-icon-box">
                                            <i className={iconClass}></i>
                                        </span>
                                        <span className="text-truncate" title={module.module_name}>
                                            {module.module_name}
                                        </span>
                                    </div>
                                    {hasPages && (
                                        <i
                                            className={`fa fa-chevron-${isExpanded ? 'down' : 'right'}`}
                                            style={{ fontSize: '0.7rem', opacity: 0.6, transition: 'transform 0.2s' }}
                                        ></i>
                                    )}
                                </button>

                                {isExpanded && hasPages && (
                                    <ul className="ks-submenu-tree">
                                        {module.pages.map((page) => {
                                            const isPageActive = isUrlActive(page.page_url);

                                            return (
                                                <li key={page.id} className="mb-1">
                                                    <Link
                                                        href={page.page_url}
                                                        className={`ks-submenu-link ${isPageActive ? 'active' : ''}`}
                                                        title={page.page_name}
                                                    >
                                                        <i
                                                            className={`fa ${isPageActive ? 'fa-dot-circle-o text-info' : 'fa-circle-o'}`}
                                                            style={{ fontSize: '0.62rem', opacity: isPageActive ? 1 : 0.6 }}
                                                        ></i>
                                                        <span className="text-truncate">{page.page_name}</span>
                                                        {isPageActive && (
                                                            <span className="ms-auto ks-active-bullet" title="Current Active Page"></span>
                                                        )}
                                                    </Link>
                                                </li>
                                            );
                                        })}
                                    </ul>
                                )}
                            </div>
                        );
                    })}
                </div>

                {/* Sidebar Footer Logout */}
                <div className="ks-sidebar-footer">
                    <Link
                        href={propRoutes?.signOuts || '/khelsathi-department/logout'}
                        className="ks-sidebar-logout-btn"
                    >
                        <i className="fa fa-power-off"></i>
                        <span>Secure Logout / लॉगआउट</span>
                    </Link>
                </div>
            </aside>

            {/* Main Content Area */}
            <main className={`ks-admin-content ${!sidebarOpen ? 'fullwidth' : ''}`}>
                <div className="container-fluid px-0" style={{ flex: 1 }}>
                    {children}
                </div>

                {/* Official Department Footer (Matching Login Page) */}
                <footer className={`ks-admin-footer ${!sidebarOpen ? 'fullwidth' : ''}`} role="contentinfo">
                    <div>
                        Copyright &copy; {new Date().getFullYear()} Department of Sports, Government of Uttar Pradesh / खेल विभाग, उत्तर प्रदेश सरकार. All Rights Reserved.
                    </div>
                </footer>
            </main>

            {/* Floating Feedback Pill (Matching Reference Design) */}
            <button
                type="button"
                className="ks-floating-feedback-btn"
                onClick={() => setIsFeedbackModalOpen(true)}
                title="Send quick feedback"
                aria-label="Send portal feedback"
            >
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                <span>Feedback</span>
            </button>

            {/* Portal Feedback Modal */}
            {isFeedbackModalOpen && (
                <div
                    className="modern-dialog-overlay"
                    onClick={(e) => {
                        if (e.target === e.currentTarget) setIsFeedbackModalOpen(false);
                    }}
                >
                    <div className="modern-dialog-content">
                        <div className="modern-dialog-header">
                            <h5 className="modern-dialog-title">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                                <span>Portal Feedback</span>
                            </h5>
                            <button
                                type="button"
                                className="modern-dialog-close"
                                onClick={() => setIsFeedbackModalOpen(false)}
                            >
                                &times;
                            </button>
                        </div>

                        <form onSubmit={handleFeedbackSubmit}>
                            <div className="modern-dialog-body">
                                <p style={{ fontSize: '0.8125rem', color: '#71717a', marginBottom: '0.75rem' }}>
                                    How can we improve the Department of Sports portal? Please share your feedback or suggestions.
                                </p>
                                <textarea
                                    className="form-control form-control-sm"
                                    rows="4"
                                    placeholder="Tell us what you think or report an issue..."
                                    value={feedbackText}
                                    onChange={(e) => setFeedbackText(e.target.value)}
                                    required
                                    style={{ fontSize: '0.8125rem', resize: 'vertical' }}
                                />
                            </div>

                            <div className="modern-dialog-footer">
                                <button
                                    type="button"
                                    className="modern-btn-outline"
                                    onClick={() => setIsFeedbackModalOpen(false)}
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    className="modern-btn-primary-dark"
                                >
                                    Submit Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* Feedback Success Toast */}
            {feedbackToast && (
                <div className="ks-toastr-container">
                    <div className="ks-toastr-item success">
                        <div className="ks-toastr-icon">
                            <i className="fa fa-check-circle"></i>
                        </div>
                        <div className="ks-toastr-content">
                            <h6 className="ks-toastr-title">Feedback Sent</h6>
                            <p className="ks-toastr-msg">{feedbackToast}</p>
                        </div>
                        <button
                            type="button"
                            className="ks-toastr-close"
                            onClick={() => setFeedbackToast(null)}
                        >
                            &times;
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
