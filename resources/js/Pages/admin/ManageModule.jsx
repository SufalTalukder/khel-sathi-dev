import React, { useState, useMemo, useEffect, useRef } from 'react';
import { Head, usePage, router, Link } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';

// Custom Select Dropdown matching Reference Image 1
function RefCustomSelect({ value, onChange, options, isInvalid = false, placeholder = 'Select...' }) {
    const [isOpen, setIsOpen] = useState(false);
    const selectRef = useRef(null);

    useEffect(() => {
        const handleOutsideClick = (e) => {
            if (selectRef.current && !selectRef.current.contains(e.target)) {
                setIsOpen(false);
            }
        };
        if (isOpen) {
            document.addEventListener('mousedown', handleOutsideClick);
        }
        return () => document.removeEventListener('mousedown', handleOutsideClick);
    }, [isOpen]);

    const selectedOption = options.find((opt) => String(opt.value) === String(value));

    return (
        <div className={`ref-custom-select ${isOpen ? 'is-active' : ''}`} ref={selectRef}>
            <button
                type="button"
                className={`ref-custom-select-trigger ${isInvalid ? 'is-invalid' : ''} ${isOpen ? 'is-open' : ''}`}
                onClick={() => setIsOpen((prev) => !prev)}
                aria-haspopup="listbox"
                aria-expanded={isOpen}
            >
                <span className="ref-custom-select-val">
                    {selectedOption ? selectedOption.label : placeholder}
                </span>
                <svg
                    className="ref-custom-select-chevron"
                    width="14"
                    height="14"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                    strokeLinejoin="round"
                >
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>

            {isOpen && (
                <div className="ref-custom-select-menu" role="listbox">
                    {options.map((opt) => {
                        const isSelected = String(opt.value) === String(value);
                        return (
                            <div
                                key={opt.value}
                                className={`ref-select-item ${isSelected ? 'is-selected' : ''}`}
                                onClick={() => {
                                    onChange(opt.value);
                                    setIsOpen(false);
                                }}
                                role="option"
                                aria-selected={isSelected}
                            >
                                <span className="ref-select-item-label">{opt.label}</span>
                                {isSelected && (
                                    <svg
                                        className="ref-select-item-check"
                                        width="14"
                                        height="14"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        strokeWidth="2.2"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                    >
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                )}
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}

export default function ManageModule({ modules = [], icons = [] }) {
    const { flash } = usePage().props;

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

    // Search and Tab Filters (matching "All", "Active", "Inactive" tabs from Image 1)
    const [searchTerm, setSearchTerm] = useState('');
    const [activeTab, setActiveTab] = useState('all'); // 'all', 'active', 'inactive'
    const [currentPage, setCurrentPage] = useState(1);
    const [pageSize, setPageSize] = useState(10);

    // Sorting State (default by menu_order ascending)
    const [sortConfig, setSortConfig] = useState({ key: 'menu_order', direction: 'asc' });

    // Column Visibility State (controlled via the "Columns" button from Image 1)
    const [visibleColumns, setVisibleColumns] = useState({
        module: true,
        status: true,
        url: true,
        order: true,
        menu: true,
        actions: true,
    });
    const [showColumnsMenu, setShowColumnsMenu] = useState(false);
    const columnsMenuRef = useRef(null);

    // Export Dropdown State (controlled via the "Export" button from Image 1)
    const [showExportMenu, setShowExportMenu] = useState(false);
    const exportMenuRef = useRef(null);

    // Close dropdowns when clicking outside
    useEffect(() => {
        const handleClickOutside = (e) => {
            if (columnsMenuRef.current && !columnsMenuRef.current.contains(e.target)) {
                setShowColumnsMenu(false);
            }
            if (exportMenuRef.current && !exportMenuRef.current.contains(e.target)) {
                setShowExportMenu(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

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

    // Auto-open create modal if requested via flash or query
    useEffect(() => {
        if (flash?.openCreateModal) {
            handleOpenAdd();
        }
    }, [flash]);

    // Handle column sort toggle
    const handleSort = (key) => {
        setSortConfig((prev) => {
            if (prev.key === key) {
                return { key, direction: prev.direction === 'asc' ? 'desc' : 'asc' };
            }
            return { key, direction: 'asc' };
        });
    };

    // Filtered, Sorted and Paginated Modules
    const filteredModules = useMemo(() => {
        return (modulesList || []).filter((m) => {
            const matchesSearch =
                searchTerm === '' ||
                (m.module_name || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
                (m.module_url || '').toLowerCase().includes(searchTerm.toLowerCase()) ||
                String(m.id || '').includes(searchTerm);

            let matchesTab = true;
            if (activeTab === 'active') {
                matchesTab = Number(m.module_status) === 1;
            } else if (activeTab === 'inactive') {
                matchesTab = Number(m.module_status) === 0;
            }

            return matchesSearch && matchesTab;
        });
    }, [modulesList, searchTerm, activeTab]);

    const sortedModules = useMemo(() => {
        const sorted = [...filteredModules];
        if (!sortConfig.key) return sorted;

        sorted.sort((a, b) => {
            let aVal = a[sortConfig.key];
            let bVal = b[sortConfig.key];

            if (sortConfig.key === 'menu_order' || sortConfig.key === 'id') {
                aVal = Number(aVal) || 0;
                bVal = Number(bVal) || 0;
            } else {
                aVal = String(aVal || '').toLowerCase();
                bVal = String(bVal || '').toLowerCase();
            }

            if (aVal < bVal) return sortConfig.direction === 'asc' ? -1 : 1;
            if (aVal > bVal) return sortConfig.direction === 'asc' ? 1 : -1;
            return 0;
        });
        return sorted;
    }, [filteredModules, sortConfig]);

    // Pagination Calculation
    const totalPages = Math.ceil(sortedModules.length / pageSize) || 1;
    const paginatedModules = useMemo(() => {
        const start = (currentPage - 1) * pageSize;
        return sortedModules.slice(start, start + pageSize);
    }, [sortedModules, currentPage, pageSize]);

    // Icon Preview Helper
    const renderIcon = (iconClass) => {
        if (!iconClass) return <i className="fa fa-folder-open-o"></i>;
        if (iconClass.startsWith('fa ') || iconClass.startsWith('fas ') || iconClass.startsWith('far ')) {
            return <i className={iconClass}></i>;
        }
        return <span className={iconClass}></span>;
    };

    // Toggle Status Directly (clicking the Active/Inactive pill)
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
                        text: `Module "${moduleItem.module_name}" marked as ${nextStatus === 1 ? 'Active' : 'Inactive'}.`,
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

    // Client-side Excel (.xlsx) Export
    const handleExportExcel = () => {
        setShowExportMenu(false);
        if (!filteredModules || filteredModules.length === 0) {
            setToast({ type: 'error', text: 'No modules available to export.' });
            return;
        }

        const xml = `<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="Header">
   <Font ss:Bold="1" ss:Color="#FFFFFF"/>
   <Interior ss:Color="#09090B" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="Data">
   <Alignment ss:Vertical="Center"/>
  </Style>
  <Style ss:ID="Center">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="Modules">
  <Table>
   <Column ss:Width="45"/>
   <Column ss:Width="65"/>
   <Column ss:Width="190"/>
   <Column ss:Width="85"/>
   <Column ss:Width="220"/>
   <Column ss:Width="85"/>
   <Column ss:Width="75"/>
   <Row ss:StyleID="Header" ss:Height="24">
    <Cell><Data ss:Type="String">S.No.</Data></Cell>
    <Cell><Data ss:Type="String">ID</Data></Cell>
    <Cell><Data ss:Type="String">Module Name</Data></Cell>
    <Cell><Data ss:Type="String">Status</Data></Cell>
    <Cell><Data ss:Type="String">Module URL</Data></Cell>
    <Cell><Data ss:Type="String">Menu Order</Data></Cell>
    <Cell><Data ss:Type="String">In Menu</Data></Cell>
   </Row>
   ${filteredModules.map((m, idx) => `
   <Row ss:Height="20">
    <Cell ss:StyleID="Center"><Data ss:Type="Number">${idx + 1}</Data></Cell>
    <Cell ss:StyleID="Center"><Data ss:Type="Number">${m.id}</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">${(m.module_name || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</Data></Cell>
    <Cell ss:StyleID="Center"><Data ss:Type="String">${m.module_status == 1 ? 'Active' : 'Inactive'}</Data></Cell>
    <Cell ss:StyleID="Data"><Data ss:Type="String">${(m.module_url || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</Data></Cell>
    <Cell ss:StyleID="Center"><Data ss:Type="Number">${m.menu_order ?? 0}</Data></Cell>
    <Cell ss:StyleID="Center"><Data ss:Type="String">${m.is_menu == 1 ? 'Yes' : 'No'}</Data></Cell>
   </Row>
   `).join('')}
  </Table>
 </Worksheet>
</Workbook>`;

        const blob = new Blob([xml], { type: 'application/vnd.ms-excel;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        const dateStr = new Date().toISOString().slice(0, 10);
        link.setAttribute('href', url);
        link.setAttribute('download', `khelsathi_modules_${dateStr}.xls`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        setToast({ type: 'success', text: `Exported ${filteredModules.length} matching rows to Excel (.xlsx).` });
    };

    // Client-side CSV (.csv) Export
    const handleExportCSV = () => {
        setShowExportMenu(false);
        if (!filteredModules || filteredModules.length === 0) {
            setToast({ type: 'error', text: 'No modules available to export.' });
            return;
        }

        const headers = ['S.No.', 'ID', 'Module Name', 'Status', 'Module URL', 'Menu Order', 'In Menu'];
        const rows = filteredModules.map((m, index) => [
            index + 1,
            m.id,
            `"${(m.module_name || '').replace(/"/g, '""')}"`,
            m.module_status == 1 ? 'Active' : 'Inactive',
            `"${(m.module_url || '').replace(/"/g, '""')}"`,
            m.menu_order ?? 0,
            m.is_menu == 1 ? 'Yes' : 'No',
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
        setToast({ type: 'success', text: `Exported ${filteredModules.length} matching rows to CSV (.csv).` });
    };

    // Official Government PDF Print Export
    const handleExportPDF = () => {
        setShowExportMenu(false);
        const toPrint = document.getElementById('modernModuleTable');
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
                    <title>Module Manager Register - Department of Sports, UP</title>
                    <style>
                        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; padding: 25px; color: #09090b; }
                        .print-header { text-align: center; border-bottom: 2px solid #09090b; padding-bottom: 12px; margin-bottom: 16px; }
                        .print-state { font-size: 13px; font-weight: 700; color: #16a34a; text-transform: uppercase; }
                        .print-dept { font-size: 20px; font-weight: 800; color: #09090b; margin-top: 2px; }
                        .print-sub { font-size: 13px; font-weight: 500; color: #71717a; margin-top: 2px; }
                        .print-meta { display: flex; justify-content: space-between; font-size: 11px; color: #71717a; margin-top: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 12px; }
                        table th, table td { border: 1px solid #e4e4e7; padding: 8px 12px; }
                        th { background-color: #f4f4f5 !important; color: #09090b !important; text-align: left; font-weight: 600; font-size: 11px; }
                        tr:nth-child(even) td { background-color: #fafafa; }
                        code { background: #f4f4f5; padding: 2px 4px; border-radius: 4px; font-size: 11px; }
                    </style>
                </head>
                <body onload="window.print()">
                    <div class="print-header">
                        <div class="print-state">Department of Sports • Government of Uttar Pradesh</div>
                        <div class="print-dept">Module Manager Register</div>
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


    // Available Icons for Icon Picker
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

    // Sorting Helper Indicator
    const renderSortArrow = (columnKey) => {
        const isCurrent = sortConfig.key === columnKey;
        if (!isCurrent) {
            return (
                <span className="sort-arrows" title="Sort column">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                        <path d="m7 15 5 5 5-5" />
                        <path d="m7 9 5-5 5 5" />
                    </svg>
                </span>
            );
        }
        return (
            <span className="sort-arrows" style={{ color: '#09090b', fontWeight: 'bold' }}>
                {sortConfig.direction === 'asc' ? (
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                        <path d="m18 15-6-6-6 6" />
                    </svg>
                ) : (
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                        <path d="m6 9 6 6 6-6" />
                    </svg>
                )}
            </span>
        );
    };

    return (
        <AdminLayout>
            <Head title="Module Manager - Department of Sports, Uttar Pradesh" />

            {/* Custom Modern Toast Notification */}
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
                                {toast.type === 'success' ? 'Success' : toast.type === 'error' ? 'Alert' : 'Notice'}
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
                    </div>
                </div>
            )}

            <div className="modern-page-container">
                {/* 1. Breadcrumbs: Matching "Dashboard > Customers" in Image 1 */}
                <div className="modern-breadcrumb">
                    <Link href="/khelsathi-department/dashboard">Dashboard</Link>
                    <span className="modern-breadcrumb-sep">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                            <path d="m9 18 6-6-6-6" />
                        </svg>
                    </span>
                    <span className="modern-breadcrumb-current">Module Manager</span>
                </div>

                {/* 2. Page Header: Large bold title + Subtitle + Primary dark button matching "+ New Order" from Image 1 */}
                <div className="modern-header-row">
                    <div>
                        <h1 className="modern-title">Module Manager</h1>
                        <p className="modern-subtitle">
                            Configure system modules, sidebar menus, display ordering, and visibility status.
                        </p>
                    </div>

                    <button
                        type="button"
                        onClick={handleOpenAdd}
                        className="modern-btn-primary-dark"
                        title="Add a new system module"
                    >
                        <span style={{ fontSize: '0.85rem', lineHeight: 1, marginRight: '2px' }}>+</span>
                        <span>Add Module</span>
                    </button>
                </div>

                {/* 3. Segmented Pill Filter Tabs: Exact match to "All", "Active", "Inactive" in Image 1 */}
                <div className="modern-pill-tabs">
                    <button
                        type="button"
                        className={`modern-pill-tab ${activeTab === 'all' ? 'active' : ''}`}
                        onClick={() => {
                            setActiveTab('all');
                            setCurrentPage(1);
                        }}
                    >
                        All
                    </button>
                    <button
                        type="button"
                        className={`modern-pill-tab ${activeTab === 'active' ? 'active' : ''}`}
                        onClick={() => {
                            setActiveTab('active');
                            setCurrentPage(1);
                        }}
                    >
                        Active
                    </button>
                    <button
                        type="button"
                        className={`modern-pill-tab ${activeTab === 'inactive' ? 'active' : ''}`}
                        onClick={() => {
                            setActiveTab('inactive');
                            setCurrentPage(1);
                        }}
                    >
                        Inactive
                    </button>
                </div>

                {/* 4. Toolbar: Search box on left, Columns & Export buttons on right */}
                <div className="modern-toolbar">
                    {/* Left Search Input with search magnifying glass */}
                    <div className="modern-search-box">
                        <span className="modern-search-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                            </svg>
                        </span>
                        <input
                            type="text"
                            className="modern-search-input"
                            placeholder="Search modules..."
                            value={searchTerm}
                            onChange={(e) => {
                                setSearchTerm(e.target.value);
                                setCurrentPage(1);
                            }}
                        />
                        {searchTerm && (
                            <button
                                type="button"
                                className="modern-search-clear"
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

                    {/* Right Action Buttons: "Columns" and "Export" */}
                    <div className="modern-toolbar-actions">
                        {/* Columns Dropdown Toggle */}
                        <div ref={columnsMenuRef} style={{ position: 'relative' }}>
                            <button
                                type="button"
                                className="modern-btn-outline"
                                onClick={() => {
                                    setShowColumnsMenu(!showColumnsMenu);
                                    setShowExportMenu(false);
                                }}
                                title="Toggle visible columns"
                            >
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                                    <line x1="4" y1="7" x2="20" y2="7"/>
                                    <line x1="4" y1="12" x2="20" y2="12"/>
                                    <line x1="4" y1="17" x2="20" y2="17"/>
                                    <line x1="9" y1="5" x2="9" y2="9"/>
                                    <line x1="15" y1="10" x2="15" y2="14"/>
                                    <line x1="11" y1="15" x2="11" y2="19"/>
                                </svg>
                                <span>Columns</span>
                            </button>

                            {showColumnsMenu && (
                                <div className="modern-dropdown-menu">
                                    <div className="modern-dropdown-header">
                                        Toggle columns
                                    </div>
                                    {[
                                        { key: 'module', label: 'module' },
                                        { key: 'status', label: 'status' },
                                        { key: 'url', label: 'url' },
                                        { key: 'order', label: 'order' },
                                        { key: 'menu', label: 'inMenu' },
                                        { key: 'actions', label: 'actions' },
                                    ].map((col) => (
                                        <button
                                            key={col.key}
                                            type="button"
                                            className="modern-dropdown-item"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                setVisibleColumns((prev) => ({
                                                    ...prev,
                                                    [col.key]: !prev[col.key],
                                                }));
                                            }}
                                        >
                                            <span className="modern-dropdown-check">
                                                {visibleColumns[col.key] && (
                                                    <svg
                                                        width="13"
                                                        height="13"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        strokeWidth="2.5"
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                    >
                                                        <polyline points="20 6 9 17 4 12" />
                                                    </svg>
                                                )}
                                            </span>
                                            <span>{col.label}</span>
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>

                        {/* Export Dropdown Toggle */}
                        <div ref={exportMenuRef} style={{ position: 'relative' }}>
                            <button
                                type="button"
                                className="modern-btn-outline"
                                onClick={() => {
                                    setShowExportMenu(!showExportMenu);
                                    setShowColumnsMenu(false);
                                }}
                                title="Export module directory"
                            >
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.2" strokeLinecap="round" strokeLinejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                <span>Export</span>
                            </button>

                            {showExportMenu && (
                                <div className="modern-dropdown-menu">
                                    <div className="modern-dropdown-header">
                                        Export all matching rows
                                    </div>
                                    <button
                                        type="button"
                                        className="modern-dropdown-item"
                                        onClick={handleExportExcel}
                                    >
                                        <span className="modern-dropdown-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <polyline points="14 2 14 8 20 8" />
                                                <rect x="8" y="12" width="3" height="3" />
                                                <rect x="13" y="12" width="3" height="3" />
                                                <rect x="8" y="16" width="3" height="3" />
                                                <rect x="13" y="16" width="3" height="3" />
                                            </svg>
                                        </span>
                                        <span>Excel (.xlsx)</span>
                                    </button>
                                    <button
                                        type="button"
                                        className="modern-dropdown-item"
                                        onClick={handleExportCSV}
                                    >
                                        <span className="modern-dropdown-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                <polyline points="14 2 14 8 20 8" />
                                                <line x1="8" y1="13" x2="16" y2="13" />
                                                <line x1="8" y1="17" x2="16" y2="17" />
                                            </svg>
                                        </span>
                                        <span>CSV (.csv)</span>
                                    </button>
                                    <button
                                        type="button"
                                        className="modern-dropdown-item"
                                        onClick={handleExportPDF}
                                    >
                                        <span className="modern-dropdown-icon">
                                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round">
                                                <polyline points="6 9 6 2 18 2 18 9" />
                                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                                <rect x="6" y="14" width="12" height="8" />
                                            </svg>
                                        </span>
                                        <span>Print / Save as PDF</span>
                                    </button>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* 5. Modern Minimalist Data Table: Pure light headers, sort arrows, circular avatars, green pills */}
                <div className="modern-table-card">
                    <table id="modernModuleTable" className="modern-table">
                        <thead>
                            <tr>
                                {visibleColumns.module && (
                                    <th
                                        className={`sortable ${sortConfig.key === 'module_name' ? 'sorted-active' : ''}`}
                                        onClick={() => handleSort('module_name')}
                                    >
                                        <span>Module</span>
                                        {renderSortArrow('module_name')}
                                    </th>
                                )}

                                {visibleColumns.status && (
                                    <th
                                        className={`sortable ${sortConfig.key === 'module_status' ? 'sorted-active' : ''}`}
                                        onClick={() => handleSort('module_status')}
                                    >
                                        <span>Status</span>
                                        {renderSortArrow('module_status')}
                                    </th>
                                )}

                                {visibleColumns.url && (
                                    <th
                                        className={`sortable ${sortConfig.key === 'module_url' ? 'sorted-active' : ''}`}
                                        onClick={() => handleSort('module_url')}
                                    >
                                        <span>Module URL</span>
                                        {renderSortArrow('module_url')}
                                    </th>
                                )}

                                {visibleColumns.order && (
                                    <th
                                        className={`sortable text-center ${sortConfig.key === 'menu_order' ? 'sorted-active' : ''}`}
                                        style={{ textAlign: 'center' }}
                                        onClick={() => handleSort('menu_order')}
                                    >
                                        <span>Menu Order</span>
                                        {renderSortArrow('menu_order')}
                                    </th>
                                )}

                                {visibleColumns.menu && (
                                    <th
                                        className={`sortable text-center ${sortConfig.key === 'is_menu' ? 'sorted-active' : ''}`}
                                        style={{ textAlign: 'center' }}
                                        onClick={() => handleSort('is_menu')}
                                    >
                                        <span>In Menu</span>
                                        {renderSortArrow('is_menu')}
                                    </th>
                                )}

                                {visibleColumns.actions && (
                                    <th style={{ textAlign: 'right', paddingRight: '1.25rem' }}>
                                        <span>Actions</span>
                                    </th>
                                )}
                            </tr>
                        </thead>

                        <tbody>
                            {paginatedModules.length === 0 ? (
                                <tr>
                                    <td colSpan={Object.values(visibleColumns).filter(Boolean).length || 1} style={{ textAlign: 'center', padding: '3.5rem 1rem', color: '#71717a' }}>
                                        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', justifyContent: 'center' }}>
                                            <div style={{ fontSize: '2rem', opacity: 0.4, marginBottom: '0.5rem' }}>
                                                <i className="fa fa-folder-open-o"></i>
                                            </div>
                                            <h6 style={{ fontWeight: 600, color: '#09090b', margin: '0 0 0.25rem' }}>No modules found</h6>
                                            <p style={{ fontSize: '0.8125rem', color: '#71717a', margin: '0 0 1rem' }}>
                                                No module matched your search or active filter tab.
                                            </p>
                                            <button
                                                type="button"
                                                className="modern-btn-outline"
                                                onClick={() => {
                                                    setSearchTerm('');
                                                    setActiveTab('all');
                                                }}
                                            >
                                                Reset Filters
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ) : (
                                paginatedModules.map((item) => {
                                    const isActive = Number(item.module_status) === 1;
                                    const isMenuYes = Number(item.is_menu) === 1;

                                    // Generate 2-letter monogram initials for avatar badge (matching EW, JC, SG in reference image)
                                    const words = (item.module_name || 'MD').trim().split(/[\s/_\-]+/).filter(Boolean);
                                    const initials = words.length >= 2
                                        ? (words[0][0] + words[1][0]).toUpperCase()
                                        : (item.module_name || 'MD').slice(0, 2).toUpperCase();

                                    return (
                                        <tr key={item.id}>
                                            {/* Column 1: Module (Avatar badge + Name + sub ID) */}
                                            {visibleColumns.module && (
                                                <td>
                                                    <div style={{ display: 'flex', alignItems: 'center', gap: '0.65rem' }}>
                                                        <div className="modern-avatar-badge" title={item.module_name}>
                                                            {initials}
                                                        </div>
                                                        <div>
                                                            <div style={{ fontWeight: 500, color: '#09090b', fontSize: '0.85rem', lineHeight: 1.3 }}>
                                                                {item.module_name}
                                                            </div>
                                                            <div style={{ fontSize: '0.72rem', color: '#71717a', lineHeight: 1.2 }}>
                                                                ID: #{item.id}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            )}

                                            {/* Column 2: Status (Exact match to green pill Active / neutral Inactive in Image 1) */}
                                            {visibleColumns.status && (
                                                <td>
                                                    <button
                                                        type="button"
                                                        onClick={() => handleToggleStatus(item)}
                                                        className={`modern-status-badge ${isActive ? 'active' : 'inactive'}`}
                                                        title={`Click to switch to ${isActive ? 'Inactive' : 'Active'}`}
                                                    >
                                                        {isActive ? 'Active' : 'Inactive'}
                                                    </button>
                                                </td>
                                            )}

                                            {/* Column 3: Module URL */}
                                            {visibleColumns.url && (
                                                <td>
                                                    <span className="modern-route-pill" title={item.module_url}>
                                                        {item.module_url}
                                                    </span>
                                                </td>
                                            )}

                                            {/* Column 4: Menu Order */}
                                            {visibleColumns.order && (
                                                <td style={{ textAlign: 'center' }}>
                                                    <span style={{ fontSize: '0.8125rem', color: '#09090b', fontWeight: 500 }}>
                                                        {item.menu_order ?? 0}
                                                    </span>
                                                </td>
                                            )}

                                            {/* Column 5: In Menu */}
                                            {visibleColumns.menu && (
                                                <td style={{ textAlign: 'center' }}>
                                                    {isMenuYes ? (
                                                        <span style={{ color: '#16a34a', fontSize: '0.8125rem', fontWeight: 500 }}>
                                                            Yes
                                                        </span>
                                                    ) : (
                                                        <span style={{ color: '#a1a1aa', fontSize: '0.8125rem' }}>
                                                            No
                                                        </span>
                                                    )}
                                                </td>
                                            )}

                                            {/* Column 6: Actions */}
                                            {visibleColumns.actions && (
                                                <td style={{ textAlign: 'right', paddingRight: '0.85rem' }}>
                                                    <div style={{ display: 'inline-flex', alignItems: 'center', gap: '0.25rem' }}>
                                                        <button
                                                            type="button"
                                                            className="modern-action-icon-btn"
                                                            onClick={() => handleOpenEdit(item)}
                                                            title="Edit module"
                                                        >
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z" />
                                                            </svg>
                                                        </button>
                                                        <button
                                                            type="button"
                                                            className="modern-action-icon-btn danger"
                                                            onClick={() => handleOpenDelete(item)}
                                                            title="Delete module"
                                                        >
                                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                                                                <polyline points="3 6 5 6 21 6" />
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            )}
                                        </tr>
                                    );
                                })
                            )}
                        </tbody>
                    </table>
                </div>

                {/* 6. Pagination Footer: Exact pixel-perfect match to Image 1 */}
                <div className="modern-pagination-bar">
                    {/* Left: Showing 1-10 of 18 results */}
                    <div className="modern-pagination-info">
                        Showing {sortedModules.length > 0 ? (currentPage - 1) * pageSize + 1 : 0}-
                        {Math.min(currentPage * pageSize, sortedModules.length)} of {sortedModules.length} results
                    </div>

                    {/* Right: Rows [10 v] + Previous + [1] [2] + Next */}
                    <div className="modern-pagination-nav">
                        {/* Rows per page dropdown */}
                        <div className="modern-rows-selector">
                            <span>Rows</span>
                            <select
                                className="modern-rows-select"
                                value={pageSize}
                                onChange={(e) => {
                                    setPageSize(Number(e.target.value));
                                    setCurrentPage(1);
                                }}
                            >
                                <option value={10}>10</option>
                                <option value={25}>25</option>
                                <option value={50}>50</option>
                                <option value={100}>100</option>
                            </select>
                        </div>

                        {/* Previous Button */}
                        <button
                            type="button"
                            className="modern-nav-btn"
                            disabled={currentPage === 1}
                            onClick={() => setCurrentPage((p) => Math.max(p - 1, 1))}
                        >
                            Previous
                        </button>

                        {/* Page Numbers: Active is a black box [1] */}
                        {Array.from({ length: totalPages }, (_, idx) => idx + 1).map((pageNum) => {
                            if (
                                pageNum === 1 ||
                                pageNum === totalPages ||
                                (pageNum >= currentPage - 1 && pageNum <= currentPage + 1)
                            ) {
                                return (
                                    <button
                                        key={pageNum}
                                        type="button"
                                        className={`modern-page-number-box ${currentPage === pageNum ? 'active' : ''}`}
                                        onClick={() => setCurrentPage(pageNum)}
                                    >
                                        {pageNum}
                                    </button>
                                );
                            }
                            if (pageNum === currentPage - 2 || pageNum === currentPage + 2) {
                                return (
                                    <span key={pageNum} style={{ color: '#a1a1aa', padding: '0 2px' }}>
                                        ...
                                    </span>
                                );
                            }
                            return null;
                        })}

                        {/* Next Button */}
                        <button
                            type="button"
                            className="modern-nav-btn"
                            disabled={currentPage === totalPages}
                            onClick={() => setCurrentPage((p) => Math.min(p + 1, totalPages))}
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>


            {/* MODAL 1: ADD NEW MODULE (Matching Reference Form Design) */}
            {isAddModalOpen && (
                <div
                    className="ref-modal-overlay"
                    onClick={(e) => {
                        if (e.target === e.currentTarget && !isSubmitting) setIsAddModalOpen(false);
                    }}
                >
                    <div className="ref-modal-container">
                        {/* Outer Header: Matching "New User / Add a new team member." */}
                        <div className="ref-modal-header">
                            <div>
                                <h2 className="ref-modal-title">New Module</h2>
                                <p className="ref-modal-subtitle">Add a new system module to the portal.</p>
                            </div>
                            <button
                                type="button"
                                className="ref-modal-close-btn"
                                onClick={() => !isSubmitting && setIsAddModalOpen(false)}
                                title="Close"
                            >
                                &times;
                            </button>
                        </div>

                        {/* Inner Form Card: Matching "User Details / Fill in the information below..." */}
                        <div className="ref-modal-card">
                            <div className="ref-modal-card-header">
                                <h3 className="ref-card-section-title">Module Details</h3>
                                <p className="ref-card-section-sub">Fill in the information below to create a new module.</p>
                            </div>

                            <form onSubmit={handleAddSubmit}>
                                {/* Field 1: Name */}
                                <div className="ref-form-group">
                                    <label className="ref-field-label">
                                        Name <span style={{ color: '#ef4444' }}>*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className={`ref-field-input ${addErrors.module_name ? 'is-invalid' : ''}`}
                                        placeholder="e.g. Master Management"
                                        value={addForm.module_name}
                                        onChange={(e) => setAddForm({ ...addForm, module_name: e.target.value })}
                                        required
                                    />
                                    {addErrors.module_name && (
                                        <div className="ref-field-error">{addErrors.module_name}</div>
                                    )}
                                </div>

                                {/* Field 2: Route URL */}
                                <div className="ref-form-group">
                                    <label className="ref-field-label">
                                        Route URL <span style={{ color: '#ef4444' }}>*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className={`ref-field-input ${addErrors.module_url ? 'is-invalid' : ''}`}
                                        placeholder="e.g. admin/master-management"
                                        value={addForm.module_url}
                                        onChange={(e) => setAddForm({ ...addForm, module_url: e.target.value })}
                                        required
                                    />
                                    {addErrors.module_url && (
                                        <div className="ref-field-error">{addErrors.module_url}</div>
                                    )}
                                </div>

                                {/* Field 3: Row for Order, In Menu, and Status */}
                                <div className="row g-2" style={{ marginBottom: '1rem' }}>
                                    <div className="col-4">
                                        <label className="ref-field-label">
                                            Order <span style={{ color: '#ef4444' }}>*</span>
                                        </label>
                                        <input
                                            type="number"
                                            className={`ref-field-input ${addErrors.menu_order ? 'is-invalid' : ''}`}
                                            value={addForm.menu_order}
                                            onChange={(e) => setAddForm({ ...addForm, menu_order: e.target.value })}
                                            min="0"
                                            required
                                        />
                                    </div>

                                    <div className="col-4">
                                        <label className="ref-field-label">In Menu</label>
                                        <RefCustomSelect
                                            value={addForm.is_menu}
                                            onChange={(val) => setAddForm({ ...addForm, is_menu: val })}
                                            options={[
                                                { value: '1', label: 'Yes' },
                                                { value: '2', label: 'No' },
                                            ]}
                                        />
                                    </div>

                                    <div className="col-4">
                                        <label className="ref-field-label">Status</label>
                                        <RefCustomSelect
                                            value={addForm.module_status}
                                            onChange={(val) => setAddForm({ ...addForm, module_status: val })}
                                            options={[
                                                { value: '1', label: 'Active' },
                                                { value: '0', label: 'Inactive' },
                                                { value: '2', label: 'Suspended' },
                                            ]}
                                            isInvalid={!!addErrors.module_status}
                                        />
                                        {addErrors.module_status && (
                                            <div className="ref-field-error">{addErrors.module_status}</div>
                                        )}
                                    </div>
                                </div>

                                {/* Field 4: Select Icon */}
                                <div className="ref-form-group mb-0">
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '0.4rem' }}>
                                        <label className="ref-field-label mb-0">Icon</label>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '0.45rem' }}>
                                            <span className="modern-avatar-badge" style={{ width: '26px', height: '26px', fontSize: '0.75rem' }}>
                                                {renderIcon(addForm.menu_icon)}
                                            </span>
                                            <code style={{ fontSize: '0.72rem', color: '#71717a', background: '#f4f4f5', padding: '1px 6px', borderRadius: '4px' }}>
                                                {addForm.menu_icon}
                                            </code>
                                        </div>
                                    </div>

                                    <div className="ref-icon-picker-box">
                                        <div className="ref-icon-picker-grid">
                                            {availableIcons.map((ic) => (
                                                <button
                                                    key={ic}
                                                    type="button"
                                                    className={`ref-icon-picker-btn ${addForm.menu_icon === ic ? 'active' : ''}`}
                                                    onClick={() => setAddForm({ ...addForm, menu_icon: ic })}
                                                    title={ic}
                                                >
                                                    {renderIcon(ic)}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                </div>

                                {/* Action Buttons: Matching Left-aligned "[ Create User ] [ Cancel ]" */}
                                <div className="ref-actions-row">
                                    <button
                                        type="submit"
                                        className="ref-btn-create"
                                        disabled={isSubmitting}
                                    >
                                        {isSubmitting ? 'Creating...' : 'Create Module'}
                                    </button>
                                    <button
                                        type="button"
                                        className="ref-btn-cancel"
                                        onClick={() => setIsAddModalOpen(false)}
                                        disabled={isSubmitting}
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}

            {/* MODAL 2: EDIT MODULE (Matching Reference Form Design) */}
            {isEditModalOpen && (
                <div
                    className="ref-modal-overlay"
                    onClick={(e) => {
                        if (e.target === e.currentTarget && !isSubmitting) setIsEditModalOpen(false);
                    }}
                >
                    <div className="ref-modal-container">
                        {/* Outer Header: Matching "New User / Add a new team member." */}
                        <div className="ref-modal-header">
                            <div>
                                <h2 className="ref-modal-title">Edit Module</h2>
                                <p className="ref-modal-subtitle">Update system module configuration and visibility.</p>
                            </div>
                            <button
                                type="button"
                                className="ref-modal-close-btn"
                                onClick={() => !isSubmitting && setIsEditModalOpen(false)}
                                title="Close"
                            >
                                &times;
                            </button>
                        </div>

                        {/* Inner Form Card: Matching "User Details / Fill in the information below..." */}
                        <div className="ref-modal-card">
                            <div className="ref-modal-card-header">
                                <h3 className="ref-card-section-title">Module Details</h3>
                                <p className="ref-card-section-sub">Fill in the information below to update this module.</p>
                            </div>

                            <form onSubmit={handleEditSubmit}>
                                {/* Field 1: Name */}
                                <div className="ref-form-group">
                                    <label className="ref-field-label">
                                        Name <span style={{ color: '#ef4444' }}>*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className={`ref-field-input ${editErrors.module_name ? 'is-invalid' : ''}`}
                                        value={editForm.module_name}
                                        onChange={(e) => setEditForm({ ...editForm, module_name: e.target.value })}
                                        required
                                    />
                                    {editErrors.module_name && (
                                        <div className="ref-field-error">{editErrors.module_name}</div>
                                    )}
                                </div>

                                {/* Field 2: Route URL */}
                                <div className="ref-form-group">
                                    <label className="ref-field-label">
                                        Route URL <span style={{ color: '#ef4444' }}>*</span>
                                    </label>
                                    <input
                                        type="text"
                                        className={`ref-field-input ${editErrors.module_url ? 'is-invalid' : ''}`}
                                        value={editForm.module_url}
                                        onChange={(e) => setEditForm({ ...editForm, module_url: e.target.value })}
                                        required
                                    />
                                    {editErrors.module_url && (
                                        <div className="ref-field-error">{editErrors.module_url}</div>
                                    )}
                                </div>

                                {/* Field 3: Row for Order, In Menu, and Status */}
                                <div className="row g-2" style={{ marginBottom: '1rem' }}>
                                    <div className="col-4">
                                        <label className="ref-field-label">Order</label>
                                        <input
                                            type="number"
                                            className="ref-field-input"
                                            value={editForm.menu_order}
                                            onChange={(e) => setEditForm({ ...editForm, menu_order: e.target.value })}
                                            min="0"
                                            required
                                        />
                                    </div>

                                    <div className="col-4">
                                        <label className="ref-field-label">In Menu</label>
                                        <RefCustomSelect
                                            value={editForm.is_menu}
                                            onChange={(val) => setEditForm({ ...editForm, is_menu: val })}
                                            options={[
                                                { value: '1', label: 'Yes' },
                                                { value: '2', label: 'No' },
                                            ]}
                                        />
                                    </div>

                                    <div className="col-4">
                                        <label className="ref-field-label">Status</label>
                                        <RefCustomSelect
                                            value={editForm.module_status}
                                            onChange={(val) => setEditForm({ ...editForm, module_status: val })}
                                            options={[
                                                { value: '1', label: 'Active' },
                                                { value: '0', label: 'Inactive' },
                                                { value: '2', label: 'Suspended' },
                                            ]}
                                            isInvalid={!!editErrors.module_status}
                                        />
                                        {editErrors.module_status && (
                                            <div className="ref-field-error">{editErrors.module_status}</div>
                                        )}
                                    </div>
                                </div>

                                {/* Field 4: Select Icon */}
                                <div className="ref-form-group mb-0">
                                    <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '0.4rem' }}>
                                        <label className="ref-field-label mb-0">Icon</label>
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '0.45rem' }}>
                                            <span className="modern-avatar-badge" style={{ width: '26px', height: '26px', fontSize: '0.75rem' }}>
                                                {renderIcon(editForm.menu_icon)}
                                            </span>
                                            <code style={{ fontSize: '0.72rem', color: '#71717a', background: '#f4f4f5', padding: '1px 6px', borderRadius: '4px' }}>
                                                {editForm.menu_icon}
                                            </code>
                                        </div>
                                    </div>

                                    <div className="ref-icon-picker-box">
                                        <div className="ref-icon-picker-grid">
                                            {availableIcons.map((ic) => (
                                                <button
                                                    key={ic}
                                                    type="button"
                                                    className={`ref-icon-picker-btn ${editForm.menu_icon === ic ? 'active' : ''}`}
                                                    onClick={() => setEditForm({ ...editForm, menu_icon: ic })}
                                                    title={ic}
                                                >
                                                    {renderIcon(ic)}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                </div>

                                {/* Action Buttons: Matching Left-aligned "[ Save Changes ] [ Cancel ]" */}
                                <div className="ref-actions-row">
                                    <button
                                        type="submit"
                                        className="ref-btn-create"
                                        disabled={isSubmitting}
                                    >
                                        {isSubmitting ? 'Updating...' : 'Save Changes'}
                                    </button>
                                    <button
                                        type="button"
                                        className="ref-btn-cancel"
                                        onClick={() => setIsEditModalOpen(false)}
                                        disabled={isSubmitting}
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            )}

            {/* MODAL 3: DELETE CONFIRMATION */}
            {isDeleteModalOpen && deletingModule && (
                <div
                    className="modern-dialog-overlay"
                    onClick={(e) => {
                        if (e.target === e.currentTarget && !isSubmitting) setIsDeleteModalOpen(false);
                    }}
                >
                    <div className="modern-dialog-content sm">
                        <div className="modern-dialog-header">
                            <h5 className="modern-dialog-title text-danger">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round">
                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                    <line x1="12" y1="17" x2="12.01" y2="17" />
                                </svg>
                                <span>Delete Module</span>
                            </h5>
                            <button
                                type="button"
                                className="modern-dialog-close"
                                onClick={() => !isSubmitting && setIsDeleteModalOpen(false)}
                            >
                                &times;
                            </button>
                        </div>

                        <div className="modern-dialog-body" style={{ textAlign: 'center', padding: '1.75rem 1.25rem' }}>
                            <div style={{ fontWeight: 600, color: '#09090b', fontSize: '1rem', marginBottom: '0.5rem' }}>
                                Delete &quot;{deletingModule.module_name}&quot;?
                            </div>
                            <p style={{ color: '#71717a', fontSize: '0.8125rem', margin: 0 }}>
                                Are you sure you want to delete this module? This action cannot be undone. Modules with active pages cannot be removed.
                            </p>
                        </div>

                        <div className="modern-dialog-footer" style={{ justifyContent: 'center' }}>
                            <button
                                type="button"
                                className="modern-btn-outline"
                                onClick={() => setIsDeleteModalOpen(false)}
                                disabled={isSubmitting}
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                className="btn btn-danger btn-sm px-3 fw-semibold"
                                onClick={handleDeleteConfirm}
                                disabled={isSubmitting}
                            >
                                {isSubmitting ? 'Deleting...' : 'Delete Module'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
