// Sliding Boxes
const items=document.querySelectorAll('img');
const itemCount=items.length;
const nextItem=document.querySelector('.next');
const previousItem=document.querySelector('.previous');
let count=0;
function showNextItem(){
    items[count].classList.remove('active');

    if(count<itemCount-1){
        count++;
    }else{
        count=0;
    }

    items[count].classList.add('active');
    console.log(count);
}
function showPreviousItem(){
    items[count].classList.remove('active');

    if(count>0){
        count--;
    }else{
        count=itemCount-1;
    }

    items[count].classList.add('active');
    console.log(count)
}
function keyPress(e){
    e=e||window.event;
    if(e.keyCode=='37'){
        showPreviousItem();
    }else if(e.keyCode=='39'){
        showNextItem();
    }
}
nextItem.addEventListener('click',showNextItem);
previousItem.addEventListener('click',showPreviousItem);
document.addEventListener('keydown',keyPress);

// Image Slider
var swiper = new Swiper(".slide-content", {  
    slidesPerView: 3,  
    spaceBetween: 20,
    slidesPerGroup:3,
    loop: true,
    centerSlide:'true',
    fade:'true',
    grabCursor:'true',
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets:true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});



