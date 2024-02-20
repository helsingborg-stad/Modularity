document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        const dynamicTables = document.querySelectorAll('.acf-field-dynamic-table');
        dynamicTables.forEach((table) => {
            const tdsWithoutClass = table.querySelectorAll('td:not([class])');
            tdsWithoutClass.forEach((td) => {
                td.innerHTML += `
                    <div class="table-tools">
                        <ul>
                            <li data-action="bold"><i class="mce-ico mce-i-bold"></i></li>
                            <li data-action="italic"><i class="mce-ico mce-i-italic"></i></li>
                            <li data-action="strikethrough"><i class="mce-ico mce-i-strikethrough"></i></li>
                            <li data-action="link"><i class="mce-ico mce-i-link"></i></li>
                        </ul>
                    </div>
                `;
            });
        });
    }, 1000);


    /**
     * Handles click events in the table tools
     */
    document.body.addEventListener('click', (e) => {
        const targetAction = e.target.closest('.table-tools [data-action]');
        if (targetAction) {
            e.preventDefault();

            const input = targetAction.closest('td').querySelector('input');
            const action = targetAction.dataset.action;

            const selection = {
                start: input.selectionStart,
                end: input.selectionEnd
            };

            const val = input.value;
            let newVal = '';

            switch (action) {
                case 'bold':
                    newVal = `<strong>${val}</strong>`;
                    break;

                case 'italic':
                    newVal = `<span style="font-style:italic;">${val}</span>`;
                    break;

                case 'strikethrough':
                    newVal = `<s>${val}</s>`;
                    break;

                case 'link':
                    const linkUrl = window.prompt('What URL do you want to link to?', '#');
                    newVal = `<a href="${linkUrl}">${val}</a>`;
                    break;

                default:
                    return;
            }

            input.value = newVal;
        }
    });

    /**
     * Show or hide table tools
     */
    document.addEventListener('click', (e) => {
        const tableTools = document.querySelectorAll('.table-tools');
        tableTools.forEach((tool) => {
            tool.style.display = 'none';
        });

        const closestTd = e.target.closest('td');
        if (closestTd) {
            const tableTool = closestTd.querySelector('.table-tools');
            if (tableTool) {
                tableTool.style.display = 'block';
            }
        }
    });

    /**
     * Adds the table tools when clicking a row
     */
    document.body.addEventListener('click', (e) => {
        const targetTd = e.target.closest('.acf-field-dynamic-table td');
        if (targetTd) {
            const tableTool = targetTd.querySelector('.table-tools');
            if (tableTool) {
                const clonedTableTool = tableTool.cloneNode(true);
                tableTool.remove();
                targetTd.appendChild(clonedTableTool);
            }
        }
    });

    /**
     * Show the table tools when in focus
     */
    document.body.addEventListener('focus', (e) => {
        if (e.target.matches('.acf-field-dynamic-table td:not([class]) input')) {
            const tableTools = e.target.previousElementSibling;
            if (tableTools) {
                tableTools.style.display = 'block';
            }
        }
    });
});
