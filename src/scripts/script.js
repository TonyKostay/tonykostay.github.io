document.addEventListener('DOMContentLoaded', ()=>{
  const navMobileButton = document.querySelector('.main-header__nav-mobile');
  const navItems = document.querySelector('.main-header__nav-bar_items');
  let flag = true;
  navMobileButton.addEventListener('click', ()=>{
    if (flag){
      navMobileButton.firstElementChild.src = 'src/img/icons/menu-arrow.svg';
      navItems.style.transform = 'translate(-150px, 0px)';
      flag = false;
      document.body.style.overflowY = 'hidden';
    }else{
      navMobileButton.firstElementChild.src = 'src/img/icons/menu-mobile.svg';
      navItems.style.transform = 'translate(300px, 0px)';
      document.body.style.overflowY = 'visible';
      flag = true;
    }
  });
  const anchors = document.querySelectorAll('a[href*="#"]');
  for (let anchor of anchors) {
    anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const blockID = anchor.getAttribute('href').substr(1);
    
    document.getElementById(blockID).scrollIntoView({
      behavior: 'smooth',
      block: 'nearest'
      });
    });
  }

  const showSlider = new Swiper('.swiper',{
    effect: 'coverflow',
    grabCursor: true,
    loop: true,
    speed: 1800, 
    centeredSlides: true,
    slidesPerView: 'auto',
    coverflowEffect:{
      rotate:50,
      stretch:0,
      depth:100,
      modifier:1,
      slideShadows:true,
    }
  });

  $(document).ready(function() {
    $('.questions__accordeon .acc-head').on('click', f_acc);
  });
   
  function f_acc(){
    $('.questions__accordeon .acc-body').not($(this).next()).slideUp(300);
    $(this).next().slideToggle(300);
  }

});
