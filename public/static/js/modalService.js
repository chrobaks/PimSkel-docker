class ModalService
{
    config;

    /**
     * @return void
     */
    static init () {
        const dataObj = document.querySelector('[data-modal-request]');
        if (dataObj) {
            const requestUrl = dataObj.dataset?.modalRequest;
            this.config = AppModal.instanceModalForm();
            this.setViewId();
            this.config.btnSave.addEventListener('click', () => {
                // ModalService.sendFormData(requestUrl);
                this.sendFormFetch(requestUrl);
            });
        }
    }

    /**
     * @return void
     */
    static setView ()
    {
        this.config = AppModal.instanceModalForm();
        this.config.btnSave.style.display = 'block';
        this.setViewId();
    }

    /**
     * @return void
     */
    static setViewId ()
    {
        const dataObj = document.querySelector('[data-modal-id]');
        this.config.viewId = dataObj.dataset?.modalId;
    }

    /**
     * @return <Promise> void
     */
    static async sendFormFetch(requestUrl) {
        const formData = new FormData(this.config.modalForm)
        try {
            const response = await fetch(requestUrl, {
                method: 'POST',
                body: formData,
                headers: {'X-Requested-With': 'XMLHttpRequest'}
            });
            if (response.ok) {
                const responseData = await response.text();
                this.renderResponseMessage(responseData);
                this.renderListUpdate();
            } else {
                this.renderResponseMessage('Form error. Status: ' + response.status);
            }
        } catch (error) {
            this.renderResponseMessage('An error occurred while sending the form data.');
        }
    }

    /**
     * @return void
     */
    static renderResponseMessage (msg)
    {
        this.config.modalMsg.innerText = msg;
    }

    /**
     * @return void
     */
    static renderListUpdate ()
    {
        const dataObj = document.querySelector('tr[data-row-id="' + this.config.viewId + '"]');
        if (dataObj) {
            const cells = dataObj.querySelectorAll('[data-form-key]');
            if (cells.length) {
                [...cells].map(cell => {
                    const key = cell.dataset.formKey;
                    const input = this.config.modalForm.querySelector('[id="' + key + '"]');
                    cell.innerText = input.value;
                });
            }
        }
    }
}
ModalService.init();