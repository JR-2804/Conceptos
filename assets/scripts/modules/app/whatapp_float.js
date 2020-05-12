import {setCookie, getCookie} from "./_utils";

class WhatsappFloat{
    constructor(){
        this.closeBtn = document.querySelector('.whatsapp_float__close');
        this.float = document.querySelector('.whatsapp_float');

        this.is_closed = getCookie('whatsapp_float_close');

        if (this.is_closed) {
            this.float.classList.add('whatsapp_float--small');
        }
        else
            this.events();
    }

    events(){
        if (this.closeBtn != null)
            this.closeBtn.addEventListener('click', this.close.bind(this));
    }

    close(){
        setCookie(1, 'whatsapp_float_close');
        this.float.classList.add('whatsapp_float--small');
    }
}

export default WhatsappFloat;