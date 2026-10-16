import React, { useState, useMemo, useEffect } from 'react';
import { Head, usePage, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';

export default function ManageModule({ modules = [], icons = [] }) {
    const { flash, errors } = usePage().props;

    // Local modules list for optimistic updates
    const [modulesList, setModulesList] = useState(modules);
    useEffect(() => {
        setModulesList(modules);
    }, [modules]);

    // Toast Notification State
    const [toast, setToast] = useState(null);
    useEffect(() => {
        if (flash?.success) {
            setToast({ type: 'success', text: flash.success });
        } else if (flash?.error) {
            setToast({ type: 'error', text: flash.error });
        }
    }, [flash]);

    // Auto-dismiss toast after 4 seconds
    useEffect(() => {
        if (!toast) return;
        const timer = setTimeout(() => setToast(null), 4000);
        return () => clearTimeout(timer);
    }, [toast]);

    // Auto-open create modal if requested via flash or query
    useEffect(() => {
        if (flash?.openCreateModal) {
            setIsAddModalOpen(true);
        }
    }, [flash]);

    // Search and Filters
    const [searchTerm, setSearchTerm] = useState('');
    const [statusFilter, setStatusFilter] = useState('all');
    const [menuFilter, setMenuFilter] = useState('all');
    const [currentPage, setCurrentPage] = useState(1);
    const [pageSize, setPageSize] = useState(10);

    // Modal States
    const [isAddModalOpen, setIsAddModalOpen] = useState(false);
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);
    const [isDeleteModalOpen, setIsDeleteModalOpen] = useState(false);
    const [deletingModule, setDeletingModule] = useState(null);
    const [isSubmitting, setIsSubmitting] = useState(false);

    // Form States
    const defaultIcon = 'icon icon-grid';
    const [addForm, setAddForm] = useState({
        module_name: '',
        module_url: '',
        menu_order: (modules?.length || 0) + 1,
        is_menu: '1',
        module_status: '1',
        menu_icon: defaultIcon,
    });
    const [addErrors, setAddErrors] = useState({});

    const [editForm, setEditForm] = useState({
        id: null,
        module_name: '',
        module_url: '',
        menu_order: '',
        is_menu: '1',
        module_status: '1',
        menu_icon: defaultIcon,
    });
    const [editErrors, setEditErrors] = useState({});

    // Filtered and Paginated Modules
    const filteredModules = useMemo(() => {
        return (modulesList || []).filter((m) => {
            const matchesSearch =
                searchTerm === '' ||
                (m.module_name || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
                (m.module_url || '').toLowerCase().includes(searchTerm.toLowerCase());

            const matchesStatus =
                statusFilter === 'all' || String(m.module_status) === String(statusFilter);

            const matchesMenu =
                menuFilter === 'all' || String(m.is_menu) === String(menuFilter);

            return matchesSearch && matchesStatus && matchesMenu;
        });
    }, [modulesList, searchTerm, statusFilter, menuFilter]);

    // Pagination Calculation
    const totalPages = Math.ceil(filteredModules.length / pageSize) || 1;
    const paginatedModules = useMemo(() => {
        const start = (currentPage - 1) * pageSize;
        return filteredModules.slice(start, start + pageSize);
    }, [filteredModules, currentPage, pageSize]);

    // Metric Summary Counters
    const stats = useMemo(() => {
        const total = modulesList.length;
        const active = modulesList.filter((m) => Number(m.module_status) === 1).length;
        const inactive = modulesList.filter((m) => Number(m.module_status) === 0).length;
        const inMenu = modulesList.filter((m) => Number(m.is_menu) === 1).length;
        return { total, active, inactive, inMenu };
    }, [modulesList]);

    // Available Icons for Icon Picker (combine DB icons + standard icons)
    const availableIcons = useMemo(() => {
        const set = new Set();
        (icons || []).forEach((i) => i.value && set.add(i.value.trim()));
        [
            'icon icon-grid',
            'icon icon-note',
            'icon icon-globe',
            'icon icon-share-alt',
            'icon icon-eye',
            'icon icon-docs',
            'icon icon-shield',
            'icon icon-graduation',
            'icon icon-bell',
            'icon icon-puzzle',
            'icon icon-folder-alt',
            'icon icon-directions',
            'icon icon-user-follow',
            'icon icon-people',
            'icon icon-organization',
            'icon icon-crop',
            'icon icon-speedometer',
            'fa fa-th-large',
            'fa fa-users',
            'fa fa-cog',
            'fa fa-file-text-o',
            'fa fa-trophy',
            'fa fa-calendar',
        ].forEach((ic) => set.add(ic));
        return Array.from(set);
    }, [icons]);

    // Icon Preview Helper
    const renderIcon = (iconClass) => {
        if (!iconClass) return <i className="fa fa-folder-open-o"></i>;
        if (iconClass.startsWith('fa ') || iconClass.startsWith('fas ') || iconClass.startsWith('far ')) {
            return <i className={iconClass}></i>;
        }
        return <span className={iconClass}></span>;
    };

    // Toggle Status Directly
    const handleToggleStatus = (moduleItem) => {
        const prevStatus = moduleItem.module_status;
        const nextStatus = prevStatus == 1 ? 0 : 1;

        // Optimistic UI Update
        setModulesList((prev) =>
            prev.map((m) => (m.id === moduleItem.id ? { ...m, module_status: nextStatus } : m))
        );

        router.get(
            `/khelsathi-department/module-status/${moduleItem.id}`,
            {},
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    setToast({
                        type: 'success',
                        text: `Module "${moduleItem.module_name}" status changed to ${nextStatus === 1 ? 'Active' : 'Inactive'
                            }.`,
                    });
                },
                onError: () => {
                    // Revert on error
                    setModulesList((prev) =>
                        prev.map((m) =>
                            m.id === moduleItem.id ? { ...m, module_status: prevStatus } : m
                        )
                    );
                    setToast({ type: 'error', text: 'Failed to update module status.' });
                },
            }
        );
    };

    // Open Add Modal
    const handleOpenAdd = () => {
        setAddForm({
            module_name: '',
            module_url: '',
            menu_order: modulesList.length + 1,
            is_menu: '1',
            module_status: '1',
            menu_icon: defaultIcon,
        });
        setAddErrors({});
        setIsAddModalOpen(true);
    };

    // Submit Add Form
    const handleAddSubmit = (e) => {
        e.preventDefault();
        const errs = {};
        if (!addForm.module_name?.trim()) errs.module_name = 'Module name is required';
        if (!addForm.module_url?.trim()) errs.module_url = 'Module URL is required';
        if (addForm.menu_order === '' || isNaN(Number(addForm.menu_order)))
            errs.menu_order = 'Menu order must be a valid number';

        if (Object.keys(errs).length > 0) {
            setAddErrors(errs);
            return;
        }

        setIsSubmitting(true);
        router.post('/khelsathi-department/createModule', addForm, {
            preserveScroll: true,
            onSuccess: () => {
                setIsSubmitting(false);
                setIsAddModalOpen(false);
                setToast({
                    type: 'success',
                    text: `Module "${addForm.module_name}" created successfully.`,
                });
            },
            onError: (serverErrors) => {
                setIsSubmitting(false);
                setAddErrors(serverErrors || {});
                setToast({
                    type: 'error',
                    text: serverErrors?.msg || 'Could not create module. Please check errors.',
                });
            },
        });
    };

    // Open Edit Modal
    const handleOpenEdit = (moduleItem) => {
        setEditForm({
            id: moduleItem.id,
            module_name: moduleItem.module_name || '',
            module_url: moduleItem.module_url || '',
            menu_order: moduleItem.menu_order || 0,
            is_menu: String(moduleItem.is_menu ?? 1),
            module_status: String(moduleItem.module_status ?? 1),
            menu_icon: moduleItem.menu_icon || defaultIcon,
        });
        setEditErrors({});
        setIsEditModalOpen(true);
    };

    // Submit Edit Form
    const handleEditSubmit = (e) => {
        e.preventDefault();
        const errs = {};
        if (!editForm.module_name?.trim()) errs.module_name = 'Module name is required';
        if (!editForm.module_url?.trim()) errs.module_url = 'Module URL is required';
        if (editForm.menu_order === '' || isNaN(Number(editForm.menu_order)))
            errs.menu_order = 'Menu order must be a valid number';

        if (Object.keys(errs).length > 0) {
            setEditErrors(errs);
            return;
        }

        setIsSubmitting(true);
        router.post(`/khelsathi-department/update-module/${editForm.id}`, editForm, {
            preserveScroll: true,
            onSuccess: () => {
                setIsSubmitting(false);
                setIsEditModalOpen(false);
                setToast({
                    type: 'success',
                    text: `Module "${editForm.module_name}" updated successfully.`,
                });
            },
            onError: (serverErrors) => {
                setIsSubmitting(false);
                setEditErrors(serverErrors || {});
                setToast({
                    type: 'error',
                    text: serverErrors?.msg || 'Could not update module. Please check errors.',
                });
            },
        });
    };

    // Open Delete Modal
    const handleOpenDelete = (moduleItem) => {
        setDeletingModule(moduleItem);
        setIsDeleteModalOpen(true);
    };

    // Confirm Delete
    const handleDeleteConfirm = () => {
        if (!deletingModule) return;
        setIsSubmitting(true);
        router.delete(`/khelsathi-department/delete-module/${deletingModule.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                setIsSubmitting(false);
                setIsDeleteModalOpen(false);
                setDeletingModule(null);
                setToast({
                    type: 'success',
                    text: `Module "${deletingModule.module_name}" deleted successfully.`,
                });
            },
            onError: (serverErrors) => {
                setIsSubmitting(false);
                setIsDeleteModalOpen(false);
                setToast({
                    type: 'error',
                    text:
                        serverErrors?.msg ||
                        flash?.error ||
                        'Cannot delete this module because sub-pages or mappings exist.',
                });
            },
        });
    };

    // Client-side Excel / CSV Export
    const handleExportExcel = () => {
        if (!filteredModules || filteredModules.length === 0) {
            setToast({ type: 'warning', text: 'No modules available to export.' });
            return;
        }

        const headers = ['S.No.', 'Module Name', 'Module URL', 'Menu Order', 'Is Menu', 'Status', 'Icon'];
        const rows = filteredModules.map((m, index) => [
            index + 1,
            `"${(m.module_name || '').replace(/"/g, '""')}"`,
            `"${(m.module_url || '').replace(/"/g, '""')}"`,
            m.menu_order || 0,
            m.is_menu == 1 ? 'Yes' : 'No',
            m.module_status == 1 ? 'Active' : 'Inactive',
            `"${(m.menu_icon || '').replace(/"/g, '""')}"`,
        ]);

        const csvContent = '\uFEFF' + [headers.join(','), ...rows.map((e) => e.join(','))].join('\r\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        const dateStr = new Date().toISOString().slice(0, 10);
        link.setAttribute('href', url);
        link.setAttribute('download', `khelsathi_modules_${dateStr}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        setToast({ type: 'success', text: 'Module list successfully exported to Excel.' });
    };

    // Official Government PDF Print Export
    const handleExportPDF = () => {
        const toPrint = document.getElementById('manageModuleTable');
        if (!toPrint) {
            setToast({ type: 'error', text: 'Table content not available for PDF export.' });
            return;
        }

        const popupWin = window.open('', '_blank', 'width=1100,height=850');
        if (!popupWin) {
            setToast({ type: 'warning', text: 'Popup blocked. Please allow popups to export PDF.' });
            return;
        }

        const printDate = new Date().toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });

        // Clone table to prune action column before printing
        const clone = toPrint.cloneNode(true);
        clone.querySelectorAll('th:last-child, td:last-child').forEach((el) => el.remove());

        popupWin.document.open();
        popupWin.document.write(`
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Module Manager Statement - Department of Sports, UP</title>
                    <style>
                        body { font-family: 'Segoe UI', Arial, sans-serif; padding: 25px; color: #0f172a; }
                        .print-header { text-align: center; border-bottom: 2px solid #0c2340; padding-bottom: 12px; margin-bottom: 16px; }
                        .print-state { font-size: 13px; font-weight: 700; color: #166534; text-transform: uppercase; }
                        .print-dept { font-size: 20px; font-weight: 800; color: #0c2340; margin-top: 2px; }
                        .print-sub { font-size: 13px; font-weight: 600; color: #475569; margin-top: 2px; }
                        .print-meta { display: flex; justify-content: space-between; font-size: 11px; color: #64748b; margin-top: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px; }
                        table th, table td { border: 1px solid #94a3b8; padding: 7px 10px; }
                        th { background-color: #0c2340 !important; color: #ffffff !important; text-align: left; font-weight: 700; text-transform: uppercase; font-size: 11px; }
                        th.text-center, td.text-center { text-align: center; }
                        tr:nth-child(even) td { background-color: #f8fafc; }
                        code { background: #f1f5f9; padding: 2px 4px; border-radius: 3px; font-size: 11px; }
                    </style>
                </head>
                <body onload="window.print()">
                    <div class="print-header">
                        <div class="print-state">Department of Sports • Government of Uttar Pradesh / खेल विभाग, उत्तर प्रदेश सरकार</div>
                        <div class="print-dept">Module Manager Register (मॉड्यूल प्रबंधन सूची)</div>
                        <div class="print-sub">Official Navigation & Module Configuration Directory</div>
                        <div class="print-meta">
                            <span><strong>Total Records:</strong> ${filteredModules.length}</span>
                            <span><strong>Generated On:</strong> ${printDate}</span>
                        </div>
                    </div>
                    ${clone.outerHTML}
                </body>
            </html>
        `);
        popupWin.document.close();
    };

    return (
        <AdminLayout>
            <Head title="Module Manager - Department of Sports, Uttar Pradesh" />

            {/* Custom Toast Notification Popup */}
            {toast && (
                <div className="ks-toastr-container">
                    <div className={`ks-toastr-item ${toast.type || 'info'}`}>
                        <div className="ks-toastr-icon">
                            <i
                                className={`fa ${toast.type === 'success'
                                        ? 'fa-check-circle'
                                        : toast.type === 'error'
                                            ? 'fa-exclamation-triangle'
                                            : 'fa-info-circle'
                                    }`}
                            ></i>
                        </div>
                        <div className="ks-toastr-content">
                            <h6 className="ks-toastr-title">
                                {toast.type === 'success'
                                    ? 'Success'
                                    : toast.type === 'error'
                                        ? 'Alert'
                                        : 'Notice'}
                            </h6>
                            <p className="ks-toastr-msg">{toast.text}</p>
                        </div>
                        <button
                            type="button"
                            className="ks-toastr-close"
                            onClick={() => setToast(null)}
                        >
                            &times;
                        </button>
                        <div className="ks-toastr-progress"></div>
                    </div>
                </div>
            )}

            <div className="py-2">
                {/* Header Section */}
                <div className="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div>
                        <div className="d-flex align-items-center gap-2 mb-1">
                            <span className="ks-icon-avatar">
                                <i className="fa fa-cubes"></i>
                            </span>
                            <h4 className="mb-0 fw-bold text-dark" style={{ fontSize: '1.25rem', letterSpacing: '-0.02em' }}>
                                Module Manager / मॉड्यूल प्रबंधन
                            </h4>
                            <span className="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill" style={{ fontSize: '0.74rem', fontWeight: 700 }}>
                                {stats.total} Total
                            </span>
                        </div>
                        <p className="text-muted mb-0" style={{ fontSize: '0.82rem' }}>
                            Configure system modules, sidebar menus, display ordering, and visibility status for portal navigation.
                        </p>
                    </div>

                    {/* Action Buttons */}
                    <div className="d-flex align-items-center gap-2 flex-wrap">
                        <button
                            type="button"
                            onClick={handleExportExcel}
                            className="btn btn-outline-success btn-sm d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3 fw-semibold"
                            style={{ fontSize: '0.82rem' }}
                            title="Export module register to Excel spreadsheet (.csv)"
                        >
                            <i className="fa fa-file-excel-o"></i>
                            <span>Excel</span>
                        </button>
                        <button
                            type="button"
                            onClick={handleExportPDF}
                            className="btn btn-outline-danger btn-sm d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3 fw-semibold"
                            style={{ fontSize: '0.82rem' }}
                            title="Print / Save official PDF report"
                        >
                            <i className="fa fa-file-pdf-o"></i>
                            <span>PDF</span>
                        </button>
                        <button
                            type="button"
                            onClick={handleOpenAdd}
                            className="btn btn-primary btn-sm d-flex align-items-center gap-2 px-3 py-2 shadow-sm rounded-3 fw-bold"
                            style={{
                                fontSize: '0.84rem',
                                background: 'linear-gradient(135deg, #0284c7 0%, #0369a1 100%)',
                                border: 'none',
                            }}
                        >
                            <i className="fa fa-plus-circle"></i>
                            <span>+ Add New Module</span>
                        </button>
                    </div>
                </div>

                {/* Metrics Summary Strip */}
                <div className="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span className="ks-stat-chip">
                        <i className="fa fa-layer-group text-secondary"></i>
                        <span>Total:</span>
                        <strong>{stats.total}</strong>
                    </span>
                    <span className="ks-stat-chip active">
                        <i className="fa fa-check-circle text-success"></i>
                        <span>Active:</span>
                        <strong>{stats.active}</strong>
                    </span>
                    <span className="ks-stat-chip inactive">
                        <i className="fa fa-pause-circle text-warning"></i>
                        <span>Inactive:</span>
                        <strong>{stats.inactive}</strong>
                    </span>
                    <span className="ks-stat-chip info">
                        <i className="fa fa-bars text-info"></i>
                        <span>Visible in Menu:</span>
                        <strong>{stats.inMenu}</strong>
                    </span>
                </div>

                {/* Main CRUD Card */}
                <div className="ks-crud-card">
                    {/* Standardized Table Controls Header */}
                    <div className="ks-crud-header">
                        {/* Top-Left: Rows-per-page Dropdown (defaulting to 10) */}
                        <div className="d-flex align-items-center gap-2">
                            <span className="text-muted" style={{ fontSize: '0.82rem', fontWeight: 600 }}>Show</span>
                            <select
                                className="form-select form-select-sm"
                                style={{ width: '80px', fontSize: '0.82rem' }}
                                value={pageSize}
                                onChange={(e) => {
                                    setPageSize(Number(e.target.value));
                                    setCurrentPage(1);
                                }}
                                aria-label="Rows per page"
                            >
                                <option value={10}>10</option>
                                <option value={25}>25</option>
                                <option value={50}>50</option>
                                <option value={100}>100</option>
                            </select>
                            <span className="text-muted" style={{ fontSize: '0.82rem', fontWeight: 600 }}>entries</span>
                        </div>

                        {/* Top-Right: Status Filter, Menu Filter, and Search Field */}
                        <div className="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                            {/* Status Filter */}
                            <div className="d-flex align-items-center gap-1">
                                <span className="text-muted" style={{ fontSize: '0.78rem', fontWeight: 600 }}>Status:</span>
                                <select
                                    className="form-select form-select-sm"
                                    style={{ fontSize: '0.8rem', width: '120px' }}
                                    value={statusFilter}
                                    onChange={(e) => {
                                        setStatusFilter(e.target.value);
                                        setCurrentPage(1);
                                    }}
                                >
                                    <option value="all">All Status</option>
                                    <option value="1">Active Only</option>
                                    <option value="0">Inactive Only</option>
                                </select>
                            </div>

                            {/* Menu Filter */}
                            <div className="d-flex align-items-center gap-1">
                                <span className="text-muted" style={{ fontSize: '0.78rem', fontWeight: 600 }}>Menu:</span>
                                <select
                                    className="form-select form-select-sm"
                                    style={{ fontSize: '0.8rem', width: '125px' }}
                                    value={menuFilter}
                                    onChange={(e) => {
                                        setMenuFilter(e.target.value);
                                        setCurrentPage(1);
                                    }}
                                >
                                    <option value="all">All Modules</option>
                                    <option value="1">In Menu (Yes)</option>
                                    <option value="2">Hidden (No)</option>
                                </select>
                            </div>

                            {/* Search Input */}
                            <div className="position-relative" style={{ width: '230px' }}>
                                <i
                                    className="fa fa-search position-absolute text-muted"
                                    style={{ left: '10px', top: '50%', transform: 'translateY(-50%)', fontSize: '0.8rem' }}
                                ></i>
                                <input
                                    type="text"
                                    className="form-control form-control-sm ps-4"
                                    placeholder="Search module or URL..."
                                    value={searchTerm}
                                    onChange={(e) => {
                                        setSearchTerm(e.target.value);
                                        setCurrentPage(1);
                                    }}
                                    style={{ fontSize: '0.82rem' }}
                                />
                                {searchTerm && (
                                    <button
                                        type="button"
                                        className="btn btn-sm btn-link text-muted position-absolute end-0 top-50 translate-middle-y p-1 pe-2 text-decoration-none"
                                        onClick={() => {
                                            setSearchTerm('');
                                            setCurrentPage(1);
                                        }}
                                        title="Clear search"
                                    >
                                        &times;
                                    </button>
                                )}
                            </div>

                            {(searchTerm || statusFilter !== 'all' || menuFilter !== 'all') && (
                                <button
                                    type="button"
                                    className="btn btn-sm btn-outline-secondary px-2 py-1"
                                    style={{ fontSize: '0.76rem' }}
                                    title="Reset all search filters"
                                    onClick={() => {
                                        setSearchTerm('');
                                        setStatusFilter('all');
                                        setMenuFilter('all');
                                        setCurrentPage(1);
                                    }}
                                >
                                    <i className="fa fa-refresh me-1"></i>Reset
                                </button>
                            )}
                        </div>
                    </div>

                    {/* Table View */}
                    <div className="table-responsive">
                        <table id="manageModuleTable" className="ks-table-custom">
                            <thead>
                                <tr>
                                    <th style={{ width: '45px' }} className="text-center">#</th>
                                    <th style={{ width: '60px' }} className="text-center">Icon</th>
                                    <th>Module Name</th>
                                    <th>Module URL</th>
                                    <th style={{ width: '110px' }} className="text-center">Menu Order</th>
                                    <th style={{ width: '100px' }} className="text-center">Is Menu</th>
                                    <th style={{ width: '130px' }} className="text-center">Status</th>
                                    <th style={{ width: '100px' }} className="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {paginatedModules.length === 0 ? (
                                    <tr>
                                        <td colSpan="8" className="text-center py-5 text-muted">
                                            <div className="d-flex flex-column align-items-center justify-content-center">
                                                <i className="fa fa-folder-open-o mb-2 text-secondary" style={{ fontSize: '2.5rem', opacity: 0.5 }}></i>
                                                <h6 className="fw-semibold text-dark mb-1">No modules found</h6>
                                                <p className="text-muted small mb-3">No module matches your search or active filter criteria.</p>
                                                <button
                                                    type="button"
                                                    className="btn btn-outline-primary btn-sm rounded-pill px-3"
                                                    onClick={() => {
                                                        setSearchTerm('');
                                                        setStatusFilter('all');
                                                        setMenuFilter('all');
                                                    }}
                                                >
                                                    Clear All Filters
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ) : (
                                    paginatedModules.map((item, index) => {
                                        const globalIndex = (currentPage - 1) * pageSize + index + 1;
                                        const isActive = Number(item.module_status) === 1;
                                        const isMenuYes = Number(item.is_menu) === 1;

                                        return (
                                            <tr key={item.id}>
                                                {/* S.No */}
                                                <td className="text-center text-muted fw-semibold" style={{ fontSize: '0.8rem' }}>
                                                    {globalIndex}
                                                </td>

                                                {/* Icon Preview */}
                                                <td className="text-center">
                                                    <span
                                                        className="ks-icon-avatar"
                                                        title={`Icon class: ${item.menu_icon || 'default'}`}
                                                    >
                                                        {renderIcon(item.menu_icon)}
                                                    </span>
                                                </td>

                                                {/* Module Name */}
                                                <td>
                                                    <div className="fw-bold text-dark" style={{ fontSize: '0.88rem' }}>
                                                        {item.module_name}
                                                    </div>
                                                    <div className="text-muted" style={{ fontSize: '0.73rem' }}>
                                                        ID: #{item.id}
                                                    </div>
                                                </td>

                                                {/* Module URL */}
                                                <td>
                                                    <code
                                                        className="px-2 py-1 rounded bg-light text-primary border"
                                                        style={{ fontSize: '0.78rem', wordBreak: 'break-all' }}
                                                    >
                                                        {item.module_url}
                                                    </code>
                                                </td>

                                                {/* Menu Order */}
                                                <td className="text-center">
                                                    <span
                                                        className="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded"
                                                        style={{ fontSize: '0.8rem', fontWeight: 700 }}
                                                    >
                                                        {item.menu_order ?? 0}
                                                    </span>
                                                </td>

                                                {/* Is Menu Badge */}
                                                <td className="text-center">
                                                    {isMenuYes ? (
                                                        <span className="ks-badge-menu-yes">
                                                            <i className="fa fa-check me-1"></i>Yes
                                                        </span>
                                                    ) : (
                                                        <span className="ks-badge-menu-no">
                                                            <i className="fa fa-times me-1"></i>No
                                                        </span>
                                                    )}
                                                </td>

                                                {/* Live Status Toggle */}
                                                <td className="text-center">
                                                    <div className="d-flex align-items-center justify-content-center gap-2">
                                                        <label
                                                            className="ks-switch"
                                                            title={`Click to ${isActive ? 'Deactivate' : 'Activate'} module`}
                                                        >
                                                            <input
                                                                type="checkbox"
                                                                checked={isActive}
                                                                onChange={() => handleToggleStatus(item)}
                                                            />
                                                            <span className="ks-slider"></span>
                                                        </label>
                                                        <span
                                                            style={{
                                                                fontSize: '0.74rem',
                                                                fontWeight: 700,
                                                                color: isActive ? '#16a34a' : '#94a3b8',
                                                                minWidth: '45px',
                                                                textAlign: 'left',
                                                            }}
                                                        >
                                                            {isActive ? 'Active' : 'Inactive'}
                                                        </span>
                                                    </div>
                                                </td>

                                                {/* Action Buttons */}
                                                <td className="text-center">
                                                    <div className="d-flex align-items-center justify-content-center gap-1">
                                                        <button
                                                            type="button"
                                                            className="ks-action-btn edit"
                                                            title="Edit Module details"
                                                            onClick={() => handleOpenEdit(item)}
                                                        >
                                                            <i className="fa fa-pencil"></i>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            className="ks-action-btn delete"
                                                            title="Delete Module"
                                                            onClick={() => handleOpenDelete(item)}
                                                        >
                                                            <i className="fa fa-trash-o"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })
                                )}
                            </tbody>
                        </table>
                    </div>

                    {/* Standardized Bottom Pagination Strip */}
                    <div className="ks-crud-header border-top border-bottom-0 py-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div className="text-muted" style={{ fontSize: '0.82rem' }}>
                            Showing <strong>{filteredModules.length > 0 ? (currentPage - 1) * pageSize + 1 : 0}</strong> to{' '}
                            <strong>{Math.min(currentPage * pageSize, filteredModules.length)}</strong> of{' '}
                            <strong>{filteredModules.length}</strong> entries
                        </div>

                        <nav aria-label="Module pagination">
                            <ul className="pagination pagination-sm mb-0">
                                <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                                    <button
                                        type="button"
                                        className="page-link"
                                        onClick={() => setCurrentPage(1)}
                                        disabled={currentPage === 1}
                                        title="First Page"
                                    >
                                        &laquo; First
                                    </button>
                                </li>
                                <li className={`page-item ${currentPage === 1 ? 'disabled' : ''}`}>
                                    <button
                                        type="button"
                                        className="page-link"
                                        onClick={() => setCurrentPage((p) => Math.max(p - 1, 1))}
                                        disabled={currentPage === 1}
                                        title="Previous Page"
                                    >
                                        &lsaquo; Prev
                                    </button>
                                </li>
                                {[...Array(totalPages)].map((_, idx) => {
                                    const pageNum = idx + 1;
                                    if (
                                        pageNum === 1 ||
                                        pageNum === totalPages ||
                                        (pageNum >= currentPage - 1 && pageNum <= currentPage + 1)
                                    ) {
                                        return (
                                            <li
                                                key={pageNum}
                                                className={`page-item ${currentPage === pageNum ? 'active' : ''}`}
                                            >
                                                <button
                                                    type="button"
                                                    className="page-link"
                                                    onClick={() => setCurrentPage(pageNum)}
                                                >
                                                    {pageNum}
                                                </button>
                                            </li>
                                        );
                                    }
                                    if (pageNum === currentPage - 2 || pageNum === currentPage + 2) {
                                        return (
                                            <li key={pageNum} className="page-item disabled">
                                                <span className="page-link">...</span>
                                            </li>
                                        );
                                    }
                                    return null;
                                })}
                                <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                                    <button
                                        type="button"
                                        className="page-link"
                                        onClick={() => setCurrentPage((p) => Math.min(p + 1, totalPages))}
                                        disabled={currentPage === totalPages}
                                        title="Next Page"
                                    >
                                        Next &rsaquo;
                                    </button>
                                </li>
                                <li className={`page-item ${currentPage === totalPages ? 'disabled' : ''}`}>
                                    <button
                                        type="button"
                                        className="page-link"
                                        onClick={() => setCurrentPage(totalPages)}
                                        disabled={currentPage === totalPages}
                                        title="Last Page"
                                    >
                                        Last &raquo;
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            {/* ========================================================
                MODAL 1: ADD NEW MODULE
               ======================================================== */}
            {isAddModalOpen && (
                <div
                    className="ks-modal-overlay"
                    onClick={(e) => {
                        if (e.target === e.currentTarget && !isSubmitting) setIsAddModalOpen(false);
                    }}
                >
                    <div className="ks-modal-card">
                        <div className="ks-modal-header">
                            <h5 className="ks-modal-title">
                                <span className="ks-icon-avatar" style={{ width: '30px', height: '30px', fontSize: '0.85rem' }}>
                                    <i className="fa fa-plus"></i>
                                </span>
                                <span>Add New Module / नया मॉड्यूल जोड़ें</span>
                            </h5>
                            <button
                                type="button"
                                className="ks-modal-close-btn"
                                onClick={() => !isSubmitting && setIsAddModalOpen(false)}
                            >
                                &times;
                            </button>
                        </div>

                        <form onSubmit={handleAddSubmit}>
                            <div className="ks-modal-body">
                                <div className="row g-3">
                                    {/* Module Name */}
                                    <div className="col-md-6">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Module Name <span className="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            className={`form-control form-control-sm ${addErrors.module_name ? 'is-invalid' : ''}`}
                                            placeholder="e.g. Master Management"
                                            value={addForm.module_name}
                                            onChange={(e) => setAddForm({ ...addForm, module_name: e.target.value })}
                                            required
                                        />
                                        {addErrors.module_name && (
                                            <div className="invalid-feedback" style={{ fontSize: '0.74rem' }}>
                                                {addErrors.module_name}
                                            </div>
                                        )}
                                    </div>

                                    {/* Module URL */}
                                    <div className="col-md-6">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Module URL / Route <span className="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            className={`form-control form-control-sm ${addErrors.module_url ? 'is-invalid' : ''}`}
                                            placeholder="e.g. admin/master-management"
                                            value={addForm.module_url}
                                            onChange={(e) => setAddForm({ ...addForm, module_url: e.target.value })}
                                            required
                                        />
                                        {addErrors.module_url && (
                                            <div className="invalid-feedback" style={{ fontSize: '0.74rem' }}>
                                                {addErrors.module_url}
                                            </div>
                                        )}
                                    </div>

                                    {/* Menu Order */}
                                    <div className="col-md-4">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Menu Order <span className="text-danger">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            className={`form-control form-control-sm ${addErrors.menu_order ? 'is-invalid' : ''}`}
                                            value={addForm.menu_order}
                                            onChange={(e) => setAddForm({ ...addForm, menu_order: e.target.value })}
                                            min="0"
                                            required
                                        />
                                        {addErrors.menu_order && (
                                            <div className="invalid-feedback" style={{ fontSize: '0.74rem' }}>
                                                {addErrors.menu_order}
                                            </div>
                                        )}
                                    </div>

                                    {/* Is Menu */}
                                    <div className="col-md-4">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Display in Menu <span className="text-danger">*</span>
                                        </label>
                                        <select
                                            className="form-select form-select-sm"
                                            value={addForm.is_menu}
                                            onChange={(e) => setAddForm({ ...addForm, is_menu: e.target.value })}
                                        >
                                            <option value="1">Yes (Visible in Sidebar)</option>
                                            <option value="2">No (Hidden / Sub-level)</option>
                                        </select>
                                    </div>

                                    {/* Module Status */}
                                    <div className="col-md-4">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Initial Status <span className="text-danger">*</span>
                                        </label>
                                        <select
                                            className="form-select form-select-sm"
                                            value={addForm.module_status}
                                            onChange={(e) => setAddForm({ ...addForm, module_status: e.target.value })}
                                        >
                                            <option value="1">Active (सक्रिय)</option>
                                            <option value="0">Inactive (निष्क्रिय)</option>
                                        </select>
                                    </div>

                                    {/* Visual Icon Picker */}
                                    <div className="col-12">
                                        <div className="d-flex align-items-center justify-content-between mb-2">
                                            <label className="form-label fw-bold text-dark mb-0" style={{ fontSize: '0.82rem' }}>
                                                Select Menu Icon <span className="text-danger">*</span>
                                            </label>
                                            <div className="d-flex align-items-center gap-2">
                                                <span className="text-muted" style={{ fontSize: '0.76rem' }}>Selected:</span>
                                                <span className="ks-icon-avatar" style={{ width: '28px', height: '28px', fontSize: '0.85rem' }}>
                                                    {renderIcon(addForm.menu_icon)}
                                                </span>
                                                <code className="text-muted small">{addForm.menu_icon}</code>
                                            </div>
                                        </div>

                                        <div className="ks-icon-picker-grid">
                                            {availableIcons.map((ic) => (
                                                <button
                                                    key={ic}
                                                    type="button"
                                                    className={`ks-icon-picker-btn ${addForm.menu_icon === ic ? 'active' : ''}`}
                                                    onClick={() => setAddForm({ ...addForm, menu_icon: ic })}
                                                    title={ic}
                                                >
                                                    {renderIcon(ic)}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="ks-modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-outline-secondary btn-sm px-3"
                                    onClick={() => setIsAddModalOpen(false)}
                                    disabled={isSubmitting}
                                >
                                    Cancel / रद्द करें
                                </button>
                                <button
                                    type="submit"
                                    className="btn btn-primary btn-sm px-4 fw-bold shadow-sm"
                                    disabled={isSubmitting}
                                >
                                    {isSubmitting ? (
                                        <>
                                            <i className="fa fa-spinner fa-spin me-1"></i> Saving...
                                        </>
                                    ) : (
                                        'Create Module / सुरक्षित करें'
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* ========================================================
                MODAL 2: EDIT MODULE
               ======================================================== */}
            {isEditModalOpen && (
                <div
                    className="ks-modal-overlay"
                    onClick={(e) => {
                        if (e.target === e.currentTarget && !isSubmitting) setIsEditModalOpen(false);
                    }}
                >
                    <div className="ks-modal-card">
                        <div className="ks-modal-header">
                            <h5 className="ks-modal-title">
                                <span className="ks-icon-avatar" style={{ width: '30px', height: '30px', fontSize: '0.85rem' }}>
                                    <i className="fa fa-pencil"></i>
                                </span>
                                <span>Edit Module / मॉड्यूल संपादित करें</span>
                            </h5>
                            <button
                                type="button"
                                className="ks-modal-close-btn"
                                onClick={() => !isSubmitting && setIsEditModalOpen(false)}
                            >
                                &times;
                            </button>
                        </div>

                        <form onSubmit={handleEditSubmit}>
                            <div className="ks-modal-body">
                                <div className="row g-3">
                                    {/* Module Name */}
                                    <div className="col-md-6">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Module Name <span className="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            className={`form-control form-control-sm ${editErrors.module_name ? 'is-invalid' : ''}`}
                                            value={editForm.module_name}
                                            onChange={(e) => setEditForm({ ...editForm, module_name: e.target.value })}
                                            required
                                        />
                                        {editErrors.module_name && (
                                            <div className="invalid-feedback" style={{ fontSize: '0.74rem' }}>
                                                {editErrors.module_name}
                                            </div>
                                        )}
                                    </div>

                                    {/* Module URL */}
                                    <div className="col-md-6">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Module URL / Route <span className="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            className={`form-control form-control-sm ${editErrors.module_url ? 'is-invalid' : ''}`}
                                            value={editForm.module_url}
                                            onChange={(e) => setEditForm({ ...editForm, module_url: e.target.value })}
                                            required
                                        />
                                        {editErrors.module_url && (
                                            <div className="invalid-feedback" style={{ fontSize: '0.74rem' }}>
                                                {editErrors.module_url}
                                            </div>
                                        )}
                                    </div>

                                    {/* Menu Order */}
                                    <div className="col-md-4">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Menu Order <span className="text-danger">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            className={`form-control form-control-sm ${editErrors.menu_order ? 'is-invalid' : ''}`}
                                            value={editForm.menu_order}
                                            onChange={(e) => setEditForm({ ...editForm, menu_order: e.target.value })}
                                            min="0"
                                            required
                                        />
                                        {editErrors.menu_order && (
                                            <div className="invalid-feedback" style={{ fontSize: '0.74rem' }}>
                                                {editErrors.menu_order}
                                            </div>
                                        )}
                                    </div>

                                    {/* Is Menu */}
                                    <div className="col-md-4">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Display in Menu <span className="text-danger">*</span>
                                        </label>
                                        <select
                                            className="form-select form-select-sm"
                                            value={editForm.is_menu}
                                            onChange={(e) => setEditForm({ ...editForm, is_menu: e.target.value })}
                                        >
                                            <option value="1">Yes (Visible in Sidebar)</option>
                                            <option value="2">No (Hidden / Sub-level)</option>
                                        </select>
                                    </div>

                                    {/* Module Status */}
                                    <div className="col-md-4">
                                        <label className="form-label fw-bold text-dark" style={{ fontSize: '0.82rem' }}>
                                            Module Status <span className="text-danger">*</span>
                                        </label>
                                        <select
                                            className="form-select form-select-sm"
                                            value={editForm.module_status}
                                            onChange={(e) => setEditForm({ ...editForm, module_status: e.target.value })}
                                        >
                                            <option value="1">Active (सक्रिय)</option>
                                            <option value="0">Inactive (निष्क्रिय)</option>
                                        </select>
                                    </div>

                                    {/* Visual Icon Picker */}
                                    <div className="col-12">
                                        <div className="d-flex align-items-center justify-content-between mb-2">
                                            <label className="form-label fw-bold text-dark mb-0" style={{ fontSize: '0.82rem' }}>
                                                Select Menu Icon <span className="text-danger">*</span>
                                            </label>
                                            <div className="d-flex align-items-center gap-2">
                                                <span className="text-muted" style={{ fontSize: '0.76rem' }}>Selected:</span>
                                                <span className="ks-icon-avatar" style={{ width: '28px', height: '28px', fontSize: '0.85rem' }}>
                                                    {renderIcon(editForm.menu_icon)}
                                                </span>
                                                <code className="text-muted small">{editForm.menu_icon}</code>
                                            </div>
                                        </div>

                                        <div className="ks-icon-picker-grid">
                                            {availableIcons.map((ic) => (
                                                <button
                                                    key={ic}
                                                    type="button"
                                                    className={`ks-icon-picker-btn ${editForm.menu_icon === ic ? 'active' : ''}`}
                                                    onClick={() => setEditForm({ ...editForm, menu_icon: ic })}
                                                    title={ic}
                                                >
                                                    {renderIcon(ic)}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="ks-modal-footer">
                                <button
                                    type="button"
                                    className="btn btn-outline-secondary btn-sm px-3"
                                    onClick={() => setIsEditModalOpen(false)}
                                    disabled={isSubmitting}
                                >
                                    Cancel / रद्द करें
                                </button>
                                <button
                                    type="submit"
                                    className="btn btn-primary btn-sm px-4 fw-bold shadow-sm"
                                    disabled={isSubmitting}
                                >
                                    {isSubmitting ? (
                                        <>
                                            <i className="fa fa-spinner fa-spin me-1"></i> Updating...
                                        </>
                                    ) : (
                                        'Update Module / बदलाव सुरक्षित करें'
                                    )}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            {/* ========================================================
                MODAL 3: DELETE CONFIRMATION
               ======================================================== */}
            {isDeleteModalOpen && deletingModule && (
                <div
                    className="ks-modal-overlay"
                    onClick={(e) => {
                        if (e.target === e.currentTarget && !isSubmitting) setIsDeleteModalOpen(false);
                    }}
                >
                    <div className="ks-modal-card ks-modal-card-sm">
                        <div className="ks-modal-header bg-danger-subtle border-danger-subtle">
                            <h5 className="ks-modal-title text-danger">
                                <i className="fa fa-exclamation-triangle"></i>
                                <span>Confirm Deletion</span>
                            </h5>
                            <button
                                type="button"
                                className="ks-modal-close-btn"
                                onClick={() => !isSubmitting && setIsDeleteModalOpen(false)}
                            >
                                &times;
                            </button>
                        </div>

                        <div className="ks-modal-body py-4 text-center">
                            <div
                                className="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                                style={{
                                    width: '56px',
                                    height: '56px',
                                    background: '#fee2e2',
                                    color: '#dc2626',
                                    fontSize: '1.5rem',
                                }}
                            >
                                <i className="fa fa-trash-o"></i>
                            </div>

                            <h6 className="fw-bold text-dark mb-1">
                                Delete &quot;{deletingModule.module_name}&quot;?
                            </h6>
                            <p className="text-muted small mb-0 px-2">
                                Are you sure you want to permanently delete this module? This action cannot be undone. Modules with assigned pages cannot be deleted.
                            </p>
                        </div>

                        <div className="ks-modal-footer bg-light justify-content-center">
                            <button
                                type="button"
                                className="btn btn-outline-secondary btn-sm px-3"
                                onClick={() => setIsDeleteModalOpen(false)}
                                disabled={isSubmitting}
                            >
                                Cancel / रद्द करें
                            </button>
                            <button
                                type="button"
                                className="btn btn-danger btn-sm px-4 fw-bold shadow-sm"
                                onClick={handleDeleteConfirm}
                                disabled={isSubmitting}
                            >
                                {isSubmitting ? (
                                    <>
                                        <i className="fa fa-spinner fa-spin me-1"></i> Deleting...
                                    </>
                                ) : (
                                    'Delete Module / हटाएँ'
                                )}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
