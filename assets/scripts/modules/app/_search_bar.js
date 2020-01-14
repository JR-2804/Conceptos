import Headroom  from 'headroom.js';

class SearchBar{
    constructor(){
        this.searchBar = document.querySelector(".search_bar");
        this.init();
    }

    init(){
        var headroom  = new Headroom(this.searchBar);
        headroom.init();
    }
}

export default SearchBar;
