$(document).ready(function(){
  $('.wd-carousel').owlCarousel({
    items: 1,
    loop: true,
    dots: true
  });

  $('.event-img-carousel').owlCarousel({
    items: 1,
    loop: true,
    dots: true
  });

  $('.recommend-carousel').owlCarousel({
    items: 2,
    loop: true,
    dots: false,
    nav: true
  });

  $('.news-carousel').owlCarousel({
    items: 4,
    dots: false,
    nav: true,
    // loop: true,
    rewind:true,
    responsive : {
        0 : {
            items: 2
        },
        768 : {
            items: 3
        },
        960 : {
            items: 4
        }
    }
  });


  var $filterCarousel = $('.filter-date-carousel');

  $filterCarousel.owlCarousel({
    items: 7,
    loop: true,
    dots: false,
      responsive : {
          0 : {
              items: 5
          },
          620 : {
              nav: false,
              items: 5

          },
          960 : {
              items: 7,
              nav: true
          }
      }
  });

  $filterCarousel.find('.owl-item').on('click', function(e){
    e.preventDefault();
    $filterCarousel.find('.owl-item').removeClass('selected');
    $(this).addClass('selected');
  })
});

// detect location
if (window.location.href.indexOf('location') === -1) {
    navigator.geolocation.getCurrentPosition(function(position) {
        window.location.href += '?location=' + (position.coords.latitude + ',' + position.coords.longitude);
    });
}
