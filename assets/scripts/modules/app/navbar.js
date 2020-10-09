import Headroom from "headroom.js";
import {setCookie, getCookie} from "./_utils";

class NavBar{

    constructor() {
        this.body = document.querySelector('body');
        this.navbar = document.querySelector('.new_navbar');
        this.menuIcon = document.querySelector('.new_navbar__content__menu');
        this.menuIconClose = document.querySelector('.lateral_menu__close');
        this.menuContainer = document.querySelector('.lateral_menu');
        this.menu = document.querySelector('.lateral_menu__content');
        this.menuItems = this.menu.querySelectorAll('li a[data-next]');
        this.level = 0;

        this.init();
        this.events();
    }

    init(){
        let headroom = new Headroom(this.navbar);
        headroom.init();
    }

    events(){

        this.menuIcon.addEventListener('click', this.toggleMenu.bind(this));
        this.menuIconClose.addEventListener('click', this.toggleMenu.bind(this));

        this.menuItems.forEach((menuItem)=>{
            menuItem.addEventListener('click', this.move.bind(this));
        });
    }

    toggleMenu(){
        this.menuContainer.classList.toggle('lateral_menu--visible');
        this.body.classList.toggle('no-scroll');

    }

    move(e){
        e.preventDefault();
        let item = e.target;
         console.log(item);
        let next = item.getAttribute('data-next');

        if (next === 'next')
            this.level += 1;
        else
            this.level -= 1;

        if (this.level === 0){
            this.menu.classList.remove('lateral_menu__content--level_1');
            this.menu.classList.remove('lateral_menu__content--level_2');
        }
        else if (this.level === 1){
            this.menu.classList.add('lateral_menu__content--level_1');
            this.menu.classList.remove('lateral_menu__content--level_2');
        }
        else{
            this.menu.classList.remove('lateral_menu__content--level_1');
            this.menu.classList.add('lateral_menu__content--level_2');
        }

        if('data-show' in item.attributes) {

            let subMenus = document.querySelectorAll('ul.sub ul.sub');
            subMenus.forEach((submenu)=>{
                submenu.classList.remove('show');
            });

            let subMenuId = item.getAttribute('data-show');
            let subMenu = document.querySelector(`#${subMenuId}`);
            subMenu.classList.add('show');
        }
    }

}

export default NavBar;