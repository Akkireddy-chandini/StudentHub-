window.addEventListener('scroll',()=>{

const cards=document.querySelectorAll('.feature-card,.floating-card');

cards.forEach(card=>{

const top=card.getBoundingClientRect().top;

if(top<window.innerHeight-100){
card.style.opacity='1';
card.style.transform='translateY(0px)';
}

})

})