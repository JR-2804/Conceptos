import {setCookie, getCookie} from "./_utils";

class WhatsappFloat{
    constructor(){
        this.closeBtn = document.querySelector('.whatsapp_float__close');
        this.float = document.querySelector('.whatsapp_float');

        this.is_closed = getCookie('whatsapp_float_close');

        if (this.is_closed) {
            this.float.classList.add('whatsapp_float--close');
        }
        else
            this.events();
    }

    events(){
        if (this.closeBtn != null) {
            this.closeBtn.addEventListener('click', this.small.bind(this));
            console.log('hola');
        }
    }

    close(){
        setCookie(1, 'whatsapp_float_close', -1);
        this.float.classList.add('whatsapp_float--close');
    }

    small(){
        if (this.float.classList.contains('whatsapp_float--small'))
            this.close();
        else
            this.float.classList.add('whatsapp_float--small');
    }
}

export default WhatsappFloat;