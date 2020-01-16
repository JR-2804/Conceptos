export function setCookie(value, name='products_cart' , days=1) {
    let date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    let expires = "; expires="+date.toGMTString ();
    document.cookie = name + "=" + value +
        expires + "; path=/";
}

export function getCookie(name) {
    return  document.cookie.includes(name);
}