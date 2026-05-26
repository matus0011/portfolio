function parents(element, selector) {
    const parentsArray = [];
    let currentElement = element.parentElement;

    while (currentElement !== null) {
        if (currentElement.matches(selector)) {
            parentsArray.push(currentElement);
        }
        currentElement = currentElement.parentElement;
    }

    return parentsArray;
}


class peForm {

    constructor(DOM_el) {

        this.DOM = {
            el: null,
        };
        this.DOM.el = DOM_el;

        this.scope = parents(DOM_el, '.elementor-widget-peforms')[0];

        this.messages = JSON.parse(this.scope.dataset.settings);

        this.id = this.scope.dataset.id;
        this.valid = false;
        this.button = DOM_el.querySelector('.pe--button ');
        this.render();
    }

    validateField(field, showError = true) {
        const tagName = field.tagName.toLowerCase();
        const fieldType = field.getAttribute("type");
        const isRequired = field.hasAttribute("required") || field.getAttribute('aria-required') === "true";
        const name = field.getAttribute("name");

        let errorMessage = '';

        if (fieldType === "checkbox" && isRequired) {
            const checkboxes = this.DOM.el.querySelectorAll(`input[name="${name}"]`);
            const anyChecked = [...checkboxes].some(cb => cb.checked);
            if (!anyChecked) errorMessage = this.messages['required_checkbox_message'];
        }

        if (fieldType === "radio" && isRequired) {
            const radios = this.DOM.el.querySelectorAll(`input[name="${name}"]`);
            const anyChecked = [...radios].some(rb => rb.checked);
            if (!anyChecked) errorMessage = this.messages['multi_select_message'];
        }

        if (isRequired && !field.value.trim()) {
            errorMessage = this.messages['required_field_message'];
        }

        if (fieldType === "email" && field.value && !this.validateEmail(field.value)) {
            errorMessage = this.messages['invalid_email_message'];
        }

        if (fieldType === "tel" && field.value && !this.validatePhone(field.value)) {
            errorMessage = this.messages['invalid_tel_message'];
        }

        if (fieldType === "url" && field.value && !this.validateURL(field.value)) {
            errorMessage = this.messages['invalid_url_message'];
        }

        if (fieldType === "number" && field.value && isNaN(field.value)) {
            errorMessage = this.messages['invalid_number_message'];
        }

        if (fieldType === "file" && isRequired && field.files.length === 0) {
            errorMessage = this.messages['required_file_message'];
        }

        if (fieldType === "date" && field.value && isNaN(Date.parse(field.value))) {
            errorMessage = this.messages['date'];
        }

        if (tagName === "select" && isRequired && (field.value === "" || field.value === null)) {
            errorMessage = this.messages['multi_select_message'];
        }

        if (errorMessage) {
            if (showError) this.setError(field, errorMessage);
            return false;
        }

        if (showError) this.clearError(field);
        return true;
    }

    setError(field, message) {
        field.classList.add("field-error");

        let errorElem = field.nextElementSibling;
        if (!errorElem || !errorElem.classList.contains("error-message")) {
            errorElem = document.createElement("div");
            errorElem.classList.add("error-message");
            field.parentNode.insertBefore(errorElem, field.nextSibling);
        }
        errorElem.innerText = message;
    }

    clearError(field) {
        field.classList.remove("field-error");
        let errorElem = field.nextElementSibling;
        if (errorElem && errorElem.classList.contains("error-message")) {
            errorElem.remove();
        }
    }

    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email.toLowerCase());
    }

    validatePhone(phone) {
        const re = /^[0-9+\-\s().]{6,20}$/;
        return re.test(phone);
    }

    validateURL(url) {
        try {
            new URL(url);
            return true;
        } catch (e) {
            return false;
        }
    }

    checkFormValidity() {
        this.valid = true;
        this.DOM.el.querySelectorAll("input, textarea, select").forEach((field) => {
            if (!this.validateField(field, false)) {
                this.valid = false;
            }
        });

        if (this.valid) {
            this.DOM.el.classList.add('form--valid');
        } else {
            this.DOM.el.classList.remove('form--valid');
        }
    }

    render() {

        const form = this.DOM.el.querySelector('form');
        var button = this.button;
        var form_id = this.id;
        var post_id = form.dataset.postId;
        var wrapper = this.DOM.el;
        var messageWrap = form.querySelector('.pe-form--submit--message p');


        (function ($) {
            "use strict";
            form.addEventListener("submit", (e) => {
                e.preventDefault();

                button.classList.add('wait');
                var formSend = true;

                form.querySelectorAll("input, textarea, select").forEach((field) => {
                    if (field.getAttribute('aria-required') && !field.value) {
                        formSend = false;
                    }
                });

                if (!formSend) {
                    button.classList.remove('wait');
                    return;
                }

                var formData = new FormData(form);
                formData.append('action', 'pe_contact_form');

                if (form.querySelector('.zeyna--file--upload')) {
                    let fileInput = form.querySelector('.zeyna--file--upload');
                    formData.append('allowed_file_types', fileInput.dataset.fileTypes);
                    formData.append('max_file_size', fileInput.dataset.maxSize);
                }

                // Nonce değerini her zaman ekliyoruz
                formData.append('security', pe_contact_form.nonce);
                formData.append('form_id', form_id);
                formData.append('post_id', post_id);

                if (form.dataset.recaptchaVersion) {

                    var recaptchaVersion = form.dataset.recaptchaVersion;
                    var siteKey = form.dataset.recaptchaSiteKey;

                    if (recaptchaVersion === 'v3') {
                        grecaptcha.ready(function () {
                            grecaptcha.execute(siteKey, { action: 'submit' }).then(function (token) {
                                formData.append('recaptcha_token', token);
                                formData.append('recaptcha_version', 'v3');
                                sendAjax(formData);
                            });
                        });
                    } else if (recaptchaVersion === 'v2') {
                        var v2Token = document.querySelector('[name="g-recaptcha-response"]').value;
                        formData.append('recaptcha_token', v2Token);
                        formData.append('recaptcha_version', 'v2');
                        sendAjax(formData);
                    }
                } else {
                    sendAjax(formData);
                }

                // Ortak ajax gönderim fonksiyonu
                function sendAjax(formData) {
                    $.ajax({
                        url: pe_contact_form.ajax_url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            console.log(response);
                            if (response.success) {

                                setTimeout(() => {

                                    button.classList.remove('wait');
                                    button.classList.add('success');

                                    // var emailTemplateHTML = response.data.replace(/\n/g, "<br>");
                                    // document.querySelector('.form--preview').innerHTML = emailTemplateHTML;
                                    if (messageWrap) {
                                        messageWrap.classList.remove('error')
                                        messageWrap.classList.add('success')
                                        messageWrap.innerHTML = response.data;
                                    }

                                    wrapper.classList.add('form--success')

                                }, 750);

                            } else {
                                if (messageWrap) {
                                    messageWrap.classList.remove('success')
                                    messageWrap.classList.add('error')
                                    messageWrap.innerHTML = response.data;
                                }


                                button.classList.remove('wait');
                                button.classList.add('error');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error(error);
                            button.classList.remove('wait');
                            button.classList.add('error');
                        }
                    });
                }
            });

        })(jQuery);


        this.DOM.el.querySelectorAll("input, textarea, select").forEach((field) => {
            field.addEventListener("input", () => {
                this.validateField(field, true);
                this.checkFormValidity();
            });

            field.addEventListener("blur", () => {
                this.validateField(field, true);
                this.checkFormValidity();
            });
        });

        this.DOM.el.addEventListener("submit", (e) => {
            let valid = true;

            this.DOM.el.querySelectorAll("input, textarea, select").forEach((field) => {
                const fieldValid = this.validateField(field, true);
                if (!fieldValid) {
                    valid = false;
                }
            });
            if (!valid) {
                e.preventDefault();
            }

        });

        if (this.DOM.el.querySelector('.zeyna--file--upload')) {
            this.DOM.el.querySelectorAll('.zeyna--file--upload input[type="file"]').forEach((input) => {
                const previewContainer = input.closest('.form-field').querySelector('.file-preview');

                input.addEventListener('change', function () {
                    previewContainer.innerHTML = '';

                    Array.from(this.files).forEach((file, index) => {
                        const fileReader = new FileReader();
                        const fileItem = document.createElement('div');
                        fileItem.classList.add('file-item');

                        if (file.type.startsWith('image/')) {
                            fileReader.onload = function (e) {
                                const img = document.createElement('img');
                                img.src = e.target.result;
                                img.alt = file.name;
                                fileItem.appendChild(img);
                            };
                            fileReader.readAsDataURL(file);
                        } else {
                            const fileName = document.createElement('span');
                            fileName.textContent = file.name;
                            fileItem.appendChild(fileName);
                        }

                        const removeBtn = document.createElement('button');
                        removeBtn.textContent = 'X';
                        removeBtn.type = 'button';
                        removeBtn.addEventListener('click', function () {
                            removeFile(input, index);
                        });

                        fileItem.appendChild(removeBtn);
                        previewContainer.appendChild(fileItem);
                    });
                });

                function removeFile(input, indexToRemove) {
                    const dt = new DataTransfer();
                    Array.from(input.files).forEach((file, index) => {
                        if (index !== indexToRemove) {
                            dt.items.add(file);
                        }
                    });
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        }
    }
};