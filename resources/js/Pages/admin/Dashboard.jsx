import React, { useState, useMemo } from 'react';
import { Head } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';

export default function Dashboard(props) {
    const {
        admin,
        direct,
        financial,
        monthly,
        laxman,
        ranilaxmibai,
        position,
        onlineAdmission,
        hostelapplicant,
        players,
        coaches,
        broadcast_list = [],
        eklavya,
        coaching_camp,
        menu = [],
        announcements = [],
        routes = {},
    } = props;

    // Search filter for summary table
    const [tableSearch, setTableSearch] = useState('');

    // Broadcast Modal State
    const [showBroadcastModal, setShowBroadcastModal] = useState(broadcast_list && broadcast_list.length > 0);
    const [activeBroadcast, setActiveBroadcast] = useState(null);
    const [broadcastLoading, setBroadcastLoading] = useState(false);

    // Announcement Drawer State
    const [announcementOpen, setAnnouncementOpen] = useState(true);
    const [announcementClosed, setAnnouncementClosed] = useState(false);

    // Compute Role Visibility flags matching original logic
    const role = Number(admin?.admin_role);
    let is_directorate = 0;
    let is_rso = 0;
    let is_associate = 0;
    let is_recruitment = 0;
    let is_college = 0;
    let is_hostel = 0;

    if (role === 1 || role === 18 || role === 5) {
        is_directorate = 1;
        is_rso = 0;
        is_associate = 1;
        is_recruitment = 1;
        is_college = 0;
        is_hostel = 1;
    } else if (role === 2 || role === 9) {
        is_directorate = 1;
        is_rso = 1;
        is_associate = 0;
        is_recruitment = 0;
        is_college = 0;
        is_hostel = 0;
    } else if (role === 3) {
        is_directorate = 1;
        is_rso = 0;
        is_associate = 1;
        is_recruitment = 1;
        is_college = 0;
        is_hostel = 0;
    } else if (role === 6) {
        is_directorate = 1;
        is_rso = 0;
        is_associate = 0;
        is_recruitment = 0;
        is_college = 1;
        is_hostel = 0;
    } else if (role === 11 || role === 8) {
        is_directorate = 3;
        is_rso = 0;
        is_associate = 0;
        is_recruitment = 0;
        is_college = 0;
        is_hostel = 0;
    } else if (role === 17) {
        is_directorate = 1;
        is_rso = 0;
        is_associate = 1;
        is_recruitment = 1;
        is_college = 0;
    }

    // Print functionality matching blade PrintIt
    const handlePrint = () => {
        const toPrint = document.getElementById('prodiv');
        if (!toPrint) return;

        const popupWin = window.open(
            '',
            '_blank',
            'left=100,top=100,width=1100,height=600,toolbar=0,scrollbars=1,status=0,resizable=1'
        );

        if (!popupWin) return;

        popupWin.document.open();
        popupWin.document.write(`
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Department of Sports - Module Summary Overview</title>
                    <style>
                        body { font-family: 'Segoe UI', Arial, sans-serif; padding: 25px; color: #1e293b; }
                        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                        table tr th, table tr td { border: 1px solid #cbd5e1; padding: 8px 12px; font-size: 13px; }
                        th { background-color: #f8fafc; text-align: left; font-weight: 700; color: #334155; }
                        td.text-center, th.text-center { text-align: center; }
                        a { text-decoration: none; color: #0284c7; font-weight: 700; }
                        .print-header { text-align: center; border-bottom: 2px solid #0284c7; padding-bottom: 12px; margin-bottom: 20px; }
                    </style>
                </head>
                <body onload="window.print()">
                    <div class="print-header">
                        <div style="font-size: 20px; font-weight: 800; color: #0f172a;">Khel Sathi Portal / खेल साथी पोर्टल</div>
                        <div style="font-size: 14px; font-weight: 600; color: #475569; margin-top: 4px;">Department of Sports, Government of Uttar Pradesh / खेल विभाग, उत्तर प्रदेश सरकार</div>
                        <h4 style="margin-top: 8px; font-size: 16px; color: #0284c7;">Module Summary Overview - ${admin?.username || 'Admin'}</h4>
                    </div>
                    ${toPrint.innerHTML}
                </body>
            </html>
        `);
        popupWin.document.close();
    };

    // View broadcast detail handler
    const viewBroadcast = async (broadcastId) => {
        setBroadcastLoading(true);
        try {
            const resp = await fetch(`/admin/changeBroadcastStatus/${broadcastId}`);
            const data = await resp.json();
            if (!data.error) {
                setActiveBroadcast(data);
            } else if (window.toastr) {
                window.toastr.error(data.msg || 'Unable to fetch broadcast details');
            }
        } catch (err) {
            console.error('Broadcast fetch error:', err);
        } finally {
            setBroadcastLoading(false);
        }
    };

    // 12 Professional KPI Cards Configuration
    const kpiCards = [
        {
            id: 'direct_recruitment',
            visible: is_directorate === 3 || (is_recruitment === 1 && is_directorate === 1 && role !== 17),
            title: 'Direct Recruitment',
            hindi: 'सीधी भर्ती',
            total: direct?.[0]?.total ?? 0,
            url: routes.direct_rect || '#',
            iconClass: 'ks-icon-blue',
            icon: 'fa fa-user-plus',
        },
        {
            id: 'financial_assistance',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            title: 'Financial Assistance',
            hindi: 'वित्तीय सहायता',
            total: financial?.[0]?.total ?? 0,
            url: routes.financial_assis || '#',
            iconClass: 'ks-icon-indigo',
            icon: 'fa fa-handshake-o',
        },
        {
            id: 'monthly_pension',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            title: 'Monthly Pension',
            hindi: 'मासिक पेंशन',
            total: monthly?.[0]?.total ?? 0,
            url: routes.monthly_pension || '#',
            iconClass: 'ks-icon-teal',
            icon: 'fa fa-calendar-check-o',
        },
        {
            id: 'laxman_award',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            title: 'Laxman Award',
            hindi: 'लक्ष्मण पुरस्कार',
            total: laxman?.[0]?.total ?? 0,
            url: routes.laxman || '#',
            iconClass: 'ks-icon-amber',
            icon: 'fa fa-trophy',
        },
        {
            id: 'ranilaxmibai_award',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            title: 'Rani Laxmibai Award',
            hindi: 'रानी लक्ष्मीबाई पुरस्कार',
            total: ranilaxmibai?.[0]?.total ?? 0,
            url: routes.laxmibai || '#',
            iconClass: 'ks-icon-rose',
            icon: 'fa fa-star',
        },
        {
            id: 'prize_money',
            visible: (is_directorate === 1 && is_rso === 1) || is_associate === 1,
            title: 'Prize Money',
            hindi: 'पुरस्कार राशि',
            total: position?.[0]?.total ?? 0,
            url: routes.award || '#',
            iconClass: 'ks-icon-orange',
            icon: 'fa fa-certificate',
        },
        {
            id: 'eklavya_krida',
            visible: (is_directorate === 1 && is_rso === 1) || is_associate === 1,
            title: 'Eklavya Krida Kosh',
            hindi: 'एकलव्य क्रीड़ा कोष',
            total: (eklavya?.[0]?.total_pending || 0) + (eklavya?.[0]?.total_accepted || 0) + (eklavya?.[0]?.total_rejected || 0),
            url: routes.eklavya_krida || '#',
            iconClass: 'ks-icon-emerald',
            icon: 'fa fa-shield',
        },
        {
            id: 'online_admission',
            visible: !!(onlineAdmission && is_directorate === 1 && (is_college === 1 || role === 1 || role === 18)),
            title: 'Online Admission',
            hindi: 'ऑनलाइन प्रवेश आवेदक',
            total: onlineAdmission?.[0]?.total ?? 0,
            url: routes.collegeadmindashboard || '#',
            iconClass: 'ks-icon-purple',
            icon: 'fa fa-graduation-cap',
        },
        {
            id: 'hostel_applicant',
            visible: role === 1 || role === 18,
            title: 'Hostel Applicant',
            hindi: 'छात्रावास आवेदक',
            total: hostelapplicant?.[0]?.total ?? 0,
            url: routes.hostelList || '#',
            iconClass: 'ks-icon-blue',
            icon: 'fa fa-building',
        },
        {
            id: 'player_applicant',
            visible: is_directorate !== 3 && is_college !== 1 && role !== 17,
            title: 'Player Applicant',
            hindi: 'खिलाड़ी आवेदक',
            total: players?.[0]?.total ?? 0,
            url: routes.admin_player || '#',
            iconClass: 'ks-icon-rose',
            icon: 'fa fa-user',
        },
        {
            id: 'coach_applicant',
            visible: is_directorate !== 3 && is_college !== 1,
            title: 'Coach Applicant',
            hindi: 'प्रशिक्षक आवेदक',
            total: coaches?.[0]?.total ?? 0,
            url: routes.admin_coach || '#',
            iconClass: 'ks-icon-teal',
            icon: 'fa fa-users',
        },
        {
            id: 'coaching_camp',
            visible: role === 1 || role === 18,
            title: 'Athlete Camp Training',
            hindi: 'प्रशिक्षण शिविर पंजीकरण',
            total: coaching_camp?.[0]?.total ?? 0,
            url: routes.coaching_camp_list || '#',
            iconClass: 'ks-icon-emerald',
            icon: 'fa fa-futbol-o',
        },
    ];

    // Module Summary Table Rows Configuration
    const summaryRows = [
        {
            id: 'direct_recruitment',
            visible: ((is_recruitment === 1 && is_directorate === 1) || is_directorate === 3) && role !== 17,
            name: 'Direct Recruitment',
            hindi: 'सीधी भर्ती',
            icon: 'fa-user-plus',
            total: direct?.[0]?.total ?? 0,
            totalUrl: routes.direct_rect || '#',
            forwarded: direct?.[0]?.total_app_forward ?? 0,
            forwardedUrl: `${routes.direct_rect_spe}/3`,
            pending: direct?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.direct_rect_spe}/0`,
            accepted: direct?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.direct_rect_spe}/1`,
            rejected: direct?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.direct_rect_spe}/2`,
        },
        {
            id: 'financial_assistance',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            name: 'Financial Assistance',
            hindi: 'वित्तीय सहायता',
            icon: 'fa-handshake-o',
            total: financial?.[0]?.total ?? 0,
            totalUrl: routes.financial_assis || '#',
            forwarded: financial?.[0]?.total_app_forward ?? 0,
            forwardedUrl: `${routes.financial_assis_spe}/3`,
            pending: financial?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.financial_assis_spe}/0`,
            accepted: financial?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.financial_assis_spe}/1`,
            rejected: financial?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.financial_assis_spe}/2`,
        },
        {
            id: 'monthly_pension',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            name: 'Monthly Pension',
            hindi: 'मासिक पेंशन',
            icon: 'fa-calendar-check-o',
            total: monthly?.[0]?.total ?? 0,
            totalUrl: routes.monthly_pension || '#',
            forwarded: monthly?.[0]?.total_app_forward ?? 0,
            forwardedUrl: `${routes.monthly_pension_spe}/3`,
            pending: monthly?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.monthly_pension_spe}/0`,
            accepted: monthly?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.monthly_pension_spe}/1`,
            rejected: monthly?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.monthly_pension_spe}/2`,
        },
        {
            id: 'laxman_award',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            name: 'Nomination for Laxman Award',
            hindi: 'लक्ष्मण पुरस्कार नामांकन',
            icon: 'fa-trophy',
            total: laxman?.[0]?.total ?? 0,
            totalUrl: routes.laxman || '#',
            forwarded: laxman?.[0]?.total_app_forward ?? 0,
            forwardedUrl: `${routes.laxman_spe}/3`,
            pending: laxman?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.laxman_spe}/0`,
            accepted: laxman?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.laxman_spe}/1`,
            rejected: laxman?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.laxman_spe}/2`,
        },
        {
            id: 'ranilaxmibai_award',
            visible: is_directorate === 1 && is_rso === 0 && is_college === 0,
            name: 'Nomination for Rani Laxmibai Award',
            hindi: 'रानी लक्ष्मीबाई पुरस्कार नामांकन',
            icon: 'fa-star',
            total: ranilaxmibai?.[0]?.total ?? 0,
            totalUrl: routes.laxmibai || '#',
            forwarded: ranilaxmibai?.[0]?.total_app_forward ?? 0,
            forwardedUrl: `${routes.laxmibai_spe}/3`,
            pending: ranilaxmibai?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.laxmibai_spe}/0`,
            accepted: ranilaxmibai?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.laxmibai_spe}/1`,
            rejected: ranilaxmibai?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.laxmibai_spe}/2`,
        },
        {
            id: 'prize_money',
            visible: (is_directorate === 1 && is_rso === 1) || is_associate === 1,
            name: 'Nomination for Prize Money',
            hindi: 'पुरस्कार राशि नामांकन',
            icon: 'fa-certificate',
            total: position?.[0]?.total ?? 0,
            totalUrl: routes.award || '#',
            forwarded: position?.[0]?.total_app_forward ?? 0,
            forwardedUrl: `${routes.award_spe}/3`,
            pending: position?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.award_spe}/0`,
            accepted: position?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.award_spe}/1`,
            rejected: position?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.award_spe}/2`,
        },
        {
            id: 'eklavya_krida',
            visible: (is_directorate === 1 && is_rso === 1) || is_associate === 1,
            name: 'Eklavya Krida Kosh',
            hindi: 'एकलव्य क्रीड़ा कोष',
            icon: 'fa-shield',
            total: eklavya?.[0]?.total ?? 0,
            totalUrl: routes.eklavya_krida || '#',
            forwarded: 0,
            forwardedUrl: `${routes.eklavya_krida_spe}/3`,
            pending: eklavya?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.eklavya_krida_spe}/0`,
            accepted: eklavya?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.eklavya_krida_spe}/1`,
            rejected: eklavya?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.eklavya_krida_spe}/2`,
        },
        {
            id: 'online_admission',
            visible: !!(onlineAdmission && is_directorate === 1 && (is_college === 1 || role === 1 || role === 18)),
            name: 'Online Admission Applicant',
            hindi: 'ऑनलाइन प्रवेश आवेदक',
            icon: 'fa-graduation-cap',
            total: onlineAdmission?.[0]?.total ?? 0,
            totalUrl: routes.collegeadmindashboard || '#',
            forwarded: 0,
            forwardedUrl: null,
            pending: onlineAdmission?.[0]?.total_pending ?? 0,
            pendingUrl: routes.filterpending || '#',
            accepted: onlineAdmission?.[0]?.total_accepted ?? 0,
            acceptedUrl: routes.filteraccepted || '#',
            rejected: onlineAdmission?.[0]?.total_rejected ?? 0,
            rejectedUrl: routes.filterrejected || '#',
        },
        {
            id: 'hostel_applicant',
            visible: role === 1 || role === 18,
            name: 'Hostel Applicant',
            hindi: 'छात्रावास आवेदक',
            icon: 'fa-building',
            total: hostelapplicant?.[0]?.total ?? 0,
            totalUrl: routes.hostelList || '#',
            forwarded: 0,
            forwardedUrl: null,
            pending: hostelapplicant?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.hostelList}/3`,
            accepted: hostelapplicant?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.hostelList}/1`,
            rejected: hostelapplicant?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.hostelList}/2`,
        },
        {
            id: 'player_applicant',
            visible: is_directorate !== 3 && is_college !== 1 && role !== 17,
            name: 'Player Applicant',
            hindi: 'खिलाड़ी आवेदक',
            icon: 'fa-user',
            total: players?.[0]?.total ?? 0,
            totalUrl: routes.admin_player || '#',
            forwarded: 0,
            forwardedUrl: null,
            pending: players?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.admin_player_status}/1`,
            accepted: players?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.admin_player_status}/3`,
            rejected: players?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.admin_player_status}/2`,
        },
        {
            id: 'coach_applicant',
            visible: is_directorate !== 3 && is_college !== 1,
            name: 'Coach Applicant',
            hindi: 'प्रशिक्षक आवेदक',
            icon: 'fa-users',
            total: coaches?.[0]?.total ?? 0,
            totalUrl: routes.admin_coach || '#',
            forwarded: 0,
            forwardedUrl: null,
            pending: coaches?.[0]?.total_pending ?? 0,
            pendingUrl: `${routes.admin_coach_status}/1`,
            accepted: coaches?.[0]?.total_accepted ?? 0,
            acceptedUrl: `${routes.admin_coach_status}/3`,
            rejected: coaches?.[0]?.total_rejected ?? 0,
            rejectedUrl: `${routes.admin_coach_status}/2`,
        },
        {
            id: 'coaching_camp',
            visible: role === 1 || role === 18,
            name: 'Athlete Registration For Training',
            hindi: 'प्रशिक्षण शिविर पंजीकरण',
            icon: 'fa-futbol-o',
            total: coaching_camp?.[0]?.total ?? 0,
            totalUrl: routes.coaching_camp_list || '#',
            forwarded: 0,
            forwardedUrl: null,
            pending: coaching_camp?.[0]?.total ?? 0,
            pendingUrl: null,
            accepted: coaching_camp?.[0]?.total_accepted ?? 0,
            acceptedUrl: null,
            rejected: coaching_camp?.[0]?.total_rejected ?? 0,
            rejectedUrl: null,
        },
    ];

    // Filter visible summary rows based on search
    const filteredRows = useMemo(() => {
        return summaryRows
            .filter((row) => row.visible)
            .filter((row) => {
                if (!tableSearch.trim()) return true;
                const query = tableSearch.toLowerCase();
                return (
                    row.name.toLowerCase().includes(query) ||
                    (row.hindi && row.hindi.toLowerCase().includes(query))
                );
            });
    }, [summaryRows, tableSearch]);

    const activeKpiCards = kpiCards.filter((card) => card.visible);

    return (
        <AdminLayout menu={menu} routes={routes}>
            <Head title="Admin Dashboard - Department of Sports, Uttar Pradesh" />

            {/* Clean Professional Dashboard Header */}
            <div className="ks-dash-header">
                <div>
                    <h4 className="ks-dash-title">
                        Dashboard <span className="fw-normal text-muted" style={{ fontSize: '1.05rem' }}>/ डैशबोर्ड</span>
                    </h4>
                    <p className="ks-dash-subtitle">
                        Official Administration Overview • Department of Sports, Government of Uttar Pradesh
                    </p>
                </div>
                <div className="d-flex align-items-center gap-2">
                    <span className="badge bg-light text-secondary border px-3 py-2 fw-semibold" style={{ fontSize: '0.8rem' }}>
                        <i className="fa fa-cubes text-primary me-1"></i> {activeKpiCards.length} Modules Active
                    </span>
                    <button
                        type="button"
                        className="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 px-3 py-1-5 shadow-sm fw-semibold"
                        onClick={handlePrint}
                        title="Print Dashboard Report"
                    >
                        <i className="fa fa-print"></i>
                        <span>Print</span>
                    </button>
                </div>
            </div>

            {/* Professional Compact Metric Cards Grid */}
            <div className="row g-2 mb-3">
                {activeKpiCards.map((card) => (
                    <div key={card.id} className="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <a href={card.url} className="ks-metric-card" title={`${card.title} (${card.hindi})`}>
                            <div className={`ks-metric-icon ${card.iconClass}`}>
                                <i className={card.icon}></i>
                            </div>
                            <div className="ks-metric-content">
                                <div className="ks-metric-value">{card.total}</div>
                                <div className="ks-metric-label">{card.title}</div>
                                <div className="ks-metric-hindi">{card.hindi}</div>
                            </div>
                        </a>
                    </div>
                ))}
            </div>

            {/* Professional Data Table Card */}
            <div className="ks-data-card">
                <div className="ks-data-card-header">
                    <h5 className="ks-data-card-title">
                        <i className="fa fa-list-alt text-primary"></i>
                        <span>Module Summary Overview</span>
                        <span className="badge bg-light text-muted border ms-1" style={{ fontSize: '0.72rem' }}>
                            {filteredRows.length} Modules
                        </span>
                    </h5>

                    <div className="d-flex align-items-center gap-2">
                        {/* Live Filter Search */}
                        <div className="position-relative">
                            <i
                                className="fa fa-search position-absolute text-muted"
                                style={{ left: '10px', top: '50%', transform: 'translateY(-50%)', fontSize: '0.75rem' }}
                            ></i>
                            <input
                                type="text"
                                className="ks-table-search-input"
                                placeholder="Filter modules..."
                                value={tableSearch}
                                onChange={(e) => setTableSearch(e.target.value)}
                            />
                        </div>

                        {/* Print Button */}
                        <button
                            type="button"
                            className="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1 px-3 fw-semibold"
                            onClick={handlePrint}
                            title="Print Table"
                        >
                            <i className="fa fa-print"></i>
                            <span>Print</span>
                        </button>
                    </div>
                </div>

                {/* Printable Data Table */}
                <div className="table-responsive" id="prodiv">
                    <table id="dataTable" className="table ks-table-pro mb-0">
                        <thead>
                            <tr>
                                <th className="ps-3" style={{ minWidth: '220px' }}>
                                    Module Name / मॉड्यूल नाम
                                </th>
                                <th className="text-center">Total Received</th>
                                <th className="text-center">Forwarded</th>
                                <th className="text-center">Pending</th>
                                <th className="text-center">Accepted</th>
                                <th className="text-center pe-3">Rejected</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filteredRows.length > 0 ? (
                                filteredRows.map((row) => (
                                    <tr key={row.id}>
                                        <td className="ps-3 py-2-5">
                                            <div className="d-flex align-items-center gap-2">
                                                <i className={`fa ${row.icon} text-muted`} style={{ width: '16px', fontSize: '0.85rem' }}></i>
                                                <div>
                                                    <span className="fw-semibold text-dark" style={{ fontSize: '0.85rem' }}>
                                                        {row.name}
                                                    </span>
                                                    <span className="text-muted ms-2" style={{ fontSize: '0.72rem' }}>
                                                        ({row.hindi})
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        {/* Total Received */}
                                        <td className="text-center">
                                            {row.totalUrl ? (
                                                <a href={row.totalUrl} className="ks-num-link">
                                                    {row.total}
                                                </a>
                                            ) : (
                                                <span className="fw-bold text-dark">{row.total}</span>
                                            )}
                                        </td>

                                        {/* Forwarded */}
                                        <td className="text-center">
                                            {row.forwarded > 0 ? (
                                                row.forwardedUrl ? (
                                                    <a href={row.forwardedUrl} className="fw-semibold text-primary text-decoration-none">
                                                        {row.forwarded}
                                                    </a>
                                                ) : (
                                                    <span className="fw-semibold text-primary">{row.forwarded}</span>
                                                )
                                            ) : (
                                                <span className="ks-num-zero">0</span>
                                            )}
                                        </td>

                                        {/* Pending */}
                                        <td className="text-center">
                                            {row.pending > 0 ? (
                                                row.pendingUrl ? (
                                                    <a href={row.pendingUrl} className="ks-badge-pending">
                                                        {row.pending}
                                                    </a>
                                                ) : (
                                                    <span className="ks-badge-pending">{row.pending}</span>
                                                )
                                            ) : (
                                                <span className="ks-num-zero">0</span>
                                            )}
                                        </td>

                                        {/* Accepted */}
                                        <td className="text-center">
                                            {row.accepted > 0 ? (
                                                row.acceptedUrl ? (
                                                    <a href={row.acceptedUrl} className="ks-badge-accepted">
                                                        {row.accepted}
                                                    </a>
                                                ) : (
                                                    <span className="ks-badge-accepted">{row.accepted}</span>
                                                )
                                            ) : (
                                                <span className="ks-num-zero">0</span>
                                            )}
                                        </td>

                                        {/* Rejected */}
                                        <td className="text-center pe-3">
                                            {row.rejected > 0 ? (
                                                row.rejectedUrl ? (
                                                    <a href={row.rejectedUrl} className="ks-badge-rejected">
                                                        {row.rejected}
                                                    </a>
                                                ) : (
                                                    <span className="ks-badge-rejected">{row.rejected}</span>
                                                )
                                            ) : (
                                                <span className="ks-num-zero">0</span>
                                            )}
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="6" className="text-center py-4 text-muted">
                                        <i className="fa fa-search fa-lg mb-1 text-secondary opacity-50 d-block"></i>
                                        <span className="fw-semibold">No matching modules found</span>
                                        <div className="small text-muted">Try clearing your search query "{tableSearch}"</div>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Announcements Floating Drawer */}
            {announcements && announcements.length > 0 && !announcementClosed && (
                <div
                    className="chatbox chatbox22"
                    style={{
                        position: 'fixed',
                        bottom: '36px',
                        right: '24px',
                        width: '320px',
                        zIndex: 1045,
                        backgroundColor: '#ffffff',
                        borderRadius: '8px',
                        boxShadow: '0 8px 24px rgba(15, 23, 42, 0.15)',
                        border: '1px solid #e2e8f0',
                        overflow: 'hidden',
                        transition: 'all 0.25s ease',
                    }}
                >
                    <div
                        className="chatbox__title d-flex justify-content-between align-items-center p-2-5 px-3"
                        style={{
                            background: '#be185d',
                            color: '#ffffff',
                            cursor: 'pointer',
                        }}
                        onClick={() => setAnnouncementOpen(!announcementOpen)}
                    >
                        <h6 className="mb-0 text-white fw-bold d-flex align-items-center gap-2" style={{ fontSize: '0.85rem' }}>
                            <i className="fa fa-bullhorn"></i> Announcements
                        </h6>
                        <button
                            type="button"
                            className="btn btn-link p-0 text-white opacity-75 hover-opacity-100"
                            onClick={(e) => {
                                e.stopPropagation();
                                setAnnouncementClosed(true);
                            }}
                            title="Close"
                        >
                            <i className="fa fa-times"></i>
                        </button>
                    </div>

                    {announcementOpen && (
                        <>
                            <div className="chatbox__body p-3" style={{ maxHeight: '240px', overflowY: 'auto' }}>
                                {announcements.map((item) => (
                                    <div key={item.id} className="mb-2 pb-2 border-bottom">
                                        <div className="d-flex align-items-center gap-2 mb-1" style={{ fontSize: '11px', color: '#be185d' }}>
                                            <i className="fa fa-calendar"></i>
                                            <span>{item.start_date}</span>
                                            <span>|</span>
                                            <strong className="text-capitalize">{item.created_by_name}</strong>
                                        </div>
                                        <p className="mb-0 text-dark" style={{ fontSize: '12px', lineHeight: 1.4 }}>
                                            {item.announcement}
                                        </p>
                                    </div>
                                ))}
                            </div>
                            <div className="panel-footer text-end p-2 border-top bg-light">
                                <a
                                    href={routes.announcement || '/admin/announcement'}
                                    className="btn btn-sm text-white px-3 fw-semibold"
                                    style={{ background: '#be185d', fontSize: '12px', borderRadius: '4px' }}
                                >
                                    View All
                                </a>
                            </div>
                        </>
                    )}
                </div>
            )}

            {/* Latest Broadcasts Modal */}
            {showBroadcastModal && broadcast_list && broadcast_list.length > 0 && (
                <div
                    className="modal fade show d-block"
                    tabIndex="-1"
                    style={{ backgroundColor: 'rgba(15, 23, 42, 0.6)', zIndex: 1060 }}
                >
                    <div className="modal-dialog modal-lg modal-dialog-centered">
                        <div className="modal-content shadow border-0" style={{ borderRadius: '8px', overflow: 'hidden' }}>
                            <div className="modal-header border-bottom py-2-5 px-3 bg-light">
                                <h6 className="modal-title fw-bold text-dark d-flex align-items-center gap-2" style={{ fontSize: '0.95rem' }}>
                                    <i className="fa fa-envelope-o text-primary"></i> Latest Department Broadcasts
                                </h6>
                                <button
                                    type="button"
                                    className="btn-close"
                                    onClick={() => setShowBroadcastModal(false)}
                                    aria-label="Close"
                                ></button>
                            </div>
                            <div className="modal-body p-3">
                                {activeBroadcast && (
                                    <div className="p-3 mb-3 bg-light rounded border">
                                        <div className="d-flex justify-content-between align-items-center mb-2">
                                            <h6 className="mb-0 fw-bold text-dark">{activeBroadcast.data?.subject}</h6>
                                            <button
                                                type="button"
                                                className="btn btn-sm btn-link text-muted p-0"
                                                onClick={() => setActiveBroadcast(null)}
                                            >
                                                <i className="fa fa-close"></i>
                                            </button>
                                        </div>
                                        <div
                                            className="text-secondary mb-2"
                                            style={{ fontSize: '13px' }}
                                            dangerouslySetInnerHTML={{ __html: activeBroadcast.data?.message || '' }}
                                        />
                                        {activeBroadcast.attachment && activeBroadcast.attachment.length > 0 && (
                                            <div className="d-flex flex-wrap gap-2 pt-2 border-top">
                                                {activeBroadcast.attachment.map((att, idx) => (
                                                    <a
                                                        key={idx}
                                                        href={`/mail/attachment/${att.attachment}`}
                                                        download
                                                        className="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1"
                                                    >
                                                        <i className="fa fa-paperclip"></i>
                                                        <span>Download Attachment</span>
                                                    </a>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                )}

                                <ol className="olcards list-unstyled p-0 mb-0">
                                    {broadcast_list.map((item) => (
                                        <li
                                            key={item.idd || item.id}
                                            className="p-2-5 mb-2 rounded border bg-white d-flex justify-content-between align-items-center hover-shadow transition"
                                        >
                                            <div>
                                                <h6 className="mb-1 fw-bold text-dark" style={{ fontSize: '0.9rem' }}>{item.subject}</h6>
                                                <p className="mb-1 text-muted" style={{ fontSize: '12px' }}>
                                                    {item.message ? item.message.substring(0, 100) + '...' : ''}
                                                </p>
                                                <small className="text-secondary" style={{ fontSize: '11px' }}>
                                                    {item.created_on || ''}
                                                </small>
                                            </div>
                                            <button
                                                type="button"
                                                className="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center"
                                                style={{ width: '32px', height: '32px' }}
                                                onClick={() => viewBroadcast(item.idd || item.id)}
                                                title="View Broadcast"
                                                disabled={broadcastLoading}
                                            >
                                                <i className="fa fa-eye"></i>
                                            </button>
                                        </li>
                                    ))}
                                </ol>
                            </div>
                            <div className="modal-footer border-top bg-light py-2 px-3">
                                <button
                                    type="button"
                                    className="btn btn-secondary btn-sm px-3"
                                    onClick={() => setShowBroadcastModal(false)}
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
