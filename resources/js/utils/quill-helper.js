import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import QuillBetterTable from 'quill-better-table';
import 'quill-better-table/dist/quill-better-table.css';

// Register the table module
Quill.register('modules/better-table', QuillBetterTable);

/**
 * Create table picker UI (like CKEditor)
 */
function createTablePicker(quill) {
    const tableButton = document.querySelector('.ql-insertTable');
    if (!tableButton) return;

    // Create picker container
    const picker = document.createElement('div');
    picker.className = 'ql-table-picker';
    picker.style.cssText = `
        position: absolute;
        display: none;
        background: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        padding: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        z-index: 1000;
    `;

    // Create grid
    const maxRows = 10;
    const maxCols = 10;
    const grid = document.createElement('div');
    grid.className = 'table-grid';
    grid.style.cssText = `
        display: grid;
        grid-template-columns: repeat(${maxCols}, 18px);
        gap: 2px;
        margin-bottom: 8px;
    `;

    // Create cells
    for (let i = 0; i < maxRows * maxCols; i++) {
        const cell = document.createElement('div');
        cell.className = 'table-cell';
        cell.style.cssText = `
            width: 18px;
            height: 18px;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: all 0.2s;
        `;
        cell.dataset.row = Math.floor(i / maxCols);
        cell.dataset.col = i % maxCols;
        grid.appendChild(cell);
    }

    // Create label
    const label = document.createElement('div');
    label.className = 'table-label';
    label.style.cssText = `
        text-align: center;
        font-size: 12px;
        color: #666;
        padding: 4px 0;
    `;
    label.textContent = 'Select table size';

    picker.appendChild(grid);
    picker.appendChild(label);
    tableButton.appendChild(picker);

    // Handle hover
    grid.addEventListener('mouseover', (e) => {
        if (e.target.classList.contains('table-cell')) {
            const row = parseInt(e.target.dataset.row);
            const col = parseInt(e.target.dataset.col);

            // Highlight cells
            const cells = grid.querySelectorAll('.table-cell');
            cells.forEach((cell) => {
                const cellRow = parseInt(cell.dataset.row);
                const cellCol = parseInt(cell.dataset.col);

                if (cellRow <= row && cellCol <= col) {
                    cell.style.backgroundColor = '#3b82f6';
                    cell.style.borderColor = '#2563eb';
                } else {
                    cell.style.backgroundColor = 'white';
                    cell.style.borderColor = '#ddd';
                }
            });

            // Update label
            label.textContent = `${row + 1} × ${col + 1}`;
        }
    });

    // Handle click
    grid.addEventListener('click', (e) => {
        if (e.target.classList.contains('table-cell')) {
            const row = parseInt(e.target.dataset.row) + 1;
            const col = parseInt(e.target.dataset.col) + 1;

            // Insert table
            const tableModule = quill.getModule('better-table');
            tableModule.insertTable(row, col);

            // Hide picker
            picker.style.display = 'none';

            // Reset cells
            const cells = grid.querySelectorAll('.table-cell');
            cells.forEach((cell) => {
                cell.style.backgroundColor = 'white';
                cell.style.borderColor = '#ddd';
            });
            label.textContent = 'Select table size';
        }
    });

    // Toggle picker on button click
    tableButton.addEventListener('click', (e) => {
        e.stopPropagation();
        const isVisible = picker.style.display === 'block';
        picker.style.display = isVisible ? 'none' : 'block';
    });

    // Close picker when clicking outside
    document.addEventListener('click', (e) => {
        if (!tableButton.contains(e.target)) {
            picker.style.display = 'none';
        }
    });
}

/**
 * Initialize Quill Editor on a given selector
 * @param {string} selector - CSS selector for the textarea
 * @param {object} customConfig - Custom configuration (optional)
 * @returns {Promise} - Returns the editor instance
 */
export function initQuillEditor(selector, customConfig = {}) {
    return new Promise((resolve, reject) => {
        try {
            // Get the textarea element
            const textarea = document.querySelector(selector);
            if (!textarea) {
                reject(new Error(`Element ${selector} not found`));
                return;
            }

            // Create container for Quill
            const container = document.createElement('div');
            container.id = `quill-container-${Date.now()}`;
            textarea.style.display = 'none';
            textarea.parentNode.insertBefore(container, textarea);

            // Import Quill's built-in icons
            const icons = Quill.import('ui/icons');

            // Replace Quill's built-in toolbar icons with Tabler icons
            icons['bold'] = '<i class="ti ti-bold fs-lg"></i>';
            icons['italic'] = '<i class="ti ti-italic fs-lg"></i>';
            icons['underline'] = '<i class="ti ti-underline fs-lg"></i>';
            icons['strike'] = '<i class="ti ti-strikethrough fs-lg"></i>';
            icons['list'] = '<i class="ti ti-list fs-lg"></i>';
            icons['bullet'] = '<i class="ti ti-list-ul fs-lg"></i>';
            icons['indent'] = '<i class="ti ti-indent-increase fs-lg"></i>';
            icons['outdent'] = '<i class="ti ti-indent-decrease fs-lg"></i>';
            icons['link'] = '<i class="ti ti-link fs-lg"></i>';
            icons['image'] = '<i class="ti ti-photo fs-lg"></i>';
            icons['video'] = '<i class="ti ti-video fs-lg"></i>';
            icons['code-block'] = '<i class="ti ti-code fs-lg"></i>';
            icons['clean'] = '<i class="ti ti-trash fs-lg"></i>';
            icons['color'] = '<i class="ti ti-paint fs-lg"></i>';
            icons['background'] = '<i class="ti ti-background fs-lg"></i>';
            icons['script']['super'] = '<i class="ti ti-superscript fs-lg"></i>';
            icons['script']['sub'] = '<i class="ti ti-subscript fs-lg"></i>';
            icons['blockquote'] = '<i class="ti ti-blockquote fs-lg"></i>';
            icons['align'][''] = '<i class="ti ti-align-left fs-lg"></i>';
            icons['align']['center'] = '<i class="ti ti-align-center fs-lg"></i>';
            icons['align']['right'] = '<i class="ti ti-align-right fs-lg"></i>';
            icons['align']['justify'] = '<i class="ti ti-align-justified fs-lg"></i>';
            icons['header']['1'] = '<i class="ti ti-h-1 fs-lg"></i>';
            icons['header']['2'] = '<i class="ti ti-h-2 fs-lg"></i>';
            icons['header']['3'] = '<i class="ti ti-h-3 fs-lg"></i>';
            icons['header'][''] = '<i class="ti ti-letter-t fs-lg"></i>';

            // Default configuration
            const defaultConfig = {
                theme: 'snow',
                modules: {
                    table: false,
                    'better-table': {
                        operationMenu: {
                            items: {
                                unmergeCells: {
                                    text: 'Pisahkan Cell'
                                },
                                insertColumnRight: {
                                    text: 'Sisipkan Kolom Kanan'
                                },
                                insertColumnLeft: {
                                    text: 'Sisipkan Kolom Kiri'
                                },
                                insertRowUp: {
                                    text: 'Sisipkan Baris Atas'
                                },
                                insertRowDown: {
                                    text: 'Sisipkan Baris Bawah'
                                },
                                mergeCells: {
                                    text: 'Gabung Cell'
                                },
                                deleteColumn: {
                                    text: 'Hapus Kolom'
                                },
                                deleteRow: {
                                    text: 'Hapus Baris'
                                },
                                deleteTable: {
                                    text: 'Hapus Table'
                                }
                            }
                        }
                    },
                    keyboard: {
                        bindings: QuillBetterTable.keyboardBindings
                    },
                    toolbar: {
                        container: [
                            [{ 'font': [] }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'script': 'super' }, { 'script': 'sub' }],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'indent': '-1' }, { 'indent': '+1' }],
                            [{ 'align': [] }],
                            ['link', 'image', 'video'],
                            ['insertTable'],
                            ['clean']
                        ],
                        handlers: {
                            'insertTable': function() {
                                // Handler akan dioverride oleh picker
                                return false;
                            }
                        }
                    }
                },
                placeholder: customConfig.placeholder || 'Tulis konten di sini...',
                ...customConfig
            };

            // Initialize Quill
            const quill = new Quill(container, defaultConfig);

            // Set icon for table button
            const tableButton = document.querySelector('.ql-insertTable');
            if (tableButton) {
                tableButton.innerHTML = '<i class="ti ti-table fs-lg"></i>';
                tableButton.style.position = 'relative';

                // Create table picker after a short delay to ensure button is ready
                setTimeout(() => {
                    createTablePicker(quill);
                }, 100);
            }

            // Set initial content from textarea
            const initialContent = textarea.value;
            if (initialContent) {
                quill.root.innerHTML = initialContent;
            }

            // Sync Quill content to textarea on change
            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });

            // Set custom height if provided
            if (customConfig.height) {
                container.querySelector('.ql-editor').style.minHeight = customConfig.height;
                container.querySelector('.ql-editor').style.maxHeight = customConfig.height;
                container.querySelector('.ql-editor').style.overflowY = 'auto';
            }

            console.log('Quill Editor initialized successfully on', selector);
            resolve(quill);
        } catch (error) {
            console.error('Error initializing Quill Editor:', error);
            reject(error);
        }
    });
}

/**
 * Get content from Quill Editor
 * @param {object} quill - Quill instance
 * @returns {string} - HTML content
 */
export function getQuillContent(quill) {
    if (quill) {
        return quill.root.innerHTML;
    }
    return '';
}

/**
 * Set content to Quill Editor
 * @param {object} quill - Quill instance
 * @param {string} content - HTML content to set
 */
export function setQuillContent(quill, content) {
    if (quill) {
        quill.root.innerHTML = content;
    }
}

/**
 * Destroy Quill Editor instance
 * @param {object} quill - Quill instance
 */
export function destroyQuillEditor(quill) {
    if (quill) {
        const container = quill.container;
        const textarea = container.nextSibling;

        // Remove Quill container
        container.remove();

        // Show textarea again
        if (textarea) {
            textarea.style.display = '';
        }

        console.log('Quill Editor destroyed successfully');
    }
}
