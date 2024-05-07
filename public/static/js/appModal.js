class AppModal
{
    /**
     *
     * @returns {{myModal: bootstrap.Modal, modalData: HTMLElement}}
     */
    static instanceModal ()
    {
        const myModal = bootstrap.Modal.getOrCreateInstance('#myModal', {focus: true});
        const modalData = document.getElementById('modalData');
        const btnSave = document.querySelector('.modal-footer .btn-save');
        btnSave.style.display = 'none';
        return { myModal, modalData };
    }

    /**
     *
     * @returns {{myModal: *, btnSave: Element, modalMsg: HTMLElement, modalForm: Element}}
     */
    static instanceModalForm ()
    {
        const myModal = bootstrap.Modal.getOrCreateInstance('#myModal', {focus: true});
        const modalForm = document.querySelector('.modal-form');
        const modalMsg = document.getElementById('modal-body-msg');
        const btnSave = document.querySelector('.modal-footer .btn-save');
        btnSave.style.display = 'block';
        return { myModal, modalForm, modalMsg, btnSave };
    }

    /**
     *
     * @param url string
     * @param method string
     * @param handler function
     * @returns void
     */
    static setModalRequest (url, method, handler)
    {
        const { myModal, modalData } = this.instanceModal();

        fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Required for Symfony to identify AJAX requests
                'Content-Type': 'application/json' // Specify content type as JSON
            }
        }).then(function(response) {
            modalData.innerHTML = (response.ok) ? 'Product deleted successfully.' : 'Failed to delete product.';
            myModal.show();

            if (response.ok && typeof handler === 'function') {
                handler();
            }
        }).catch(function(error) {
            console.error('An error occurred:', error);
        });
    }

    /**
     *
     * @param url string
     * @returns void
     */
    static setModalForm (url)
    {
        const { myModal, modalData } = this.instanceModal();

        fetch(url, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Required for Symfony to identify AJAX requests
                'Content-Type': 'text/html' // Specify content type as html
            }
        })
        .then(response => response.text())
        .then(html => {
            modalData.innerHTML = html;
            this.setModalService(modalData);
            myModal.show();
        }).catch(function(error) {
            console.error('An error occurred:', error);
        });
    }

    /**
     *
     * @param modalData HTMLElement
     * @returns void
     */
    static setModalService (modalData)
    {
        const scriptModal = modalData.querySelector('[data-modal-js]');
        if (scriptModal) {
            const scriptSrc = scriptModal.dataset?.modalJs;
            const serviceName = scriptModal.dataset?.modalService;
            if (scriptSrc && serviceName) {
                const Service = (serviceName && eval("typeof (" + serviceName + ")") !== 'undefined')
                    ? eval(serviceName)
                    : null;
                if (Service === null) { // modal js load
                    let script = document.createElement('script');
                    script.src = scriptSrc + '?d=' + Date.now();
                    document.body.appendChild(script);
                } else {
                    if(Service) {
                        Service.setView();
                    }
                }
            }
        }
    }
}