window.addEventListener('load',function(){
    var i;
    var tabs=this.document.querySelectorAll('ul.nav-tabs > li');
    for(i=0;i<tabs.length;i++){
        tabs[i].addEventListener("click",switchTab);
    }
    function switchTab(event){
        event.preventDefault();
        
        document.querySelector("ul.nav-tabs li.active").classList.remove("active");
        document.querySelector(".tab-pane.active").classList.remove("active");

        var clickedTab=event.currentTarget;
        var anchor=event.target;
        var activePanel=anchor.getAttribute("href");

        clickedTab.classList.add("active");
        document.querySelector(activePanel).classList.add("active");
    }
});