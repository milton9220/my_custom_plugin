document.addEventListener('DOMContentLoaded',function(e){

    resetStarColors()
    let ratedIndex=-1;
    let rating;
    localStorage.setItem('ratedIndex', 0);
    let testimonial_form=document.getElementById('milton-testimonial-form');

    let star=testimonial_form.querySelectorAll('.rating li .dashicons');

    star.forEach(element => {
        element.addEventListener('mouseover',()=>{
            resetStarColors();
            var currentIndex = parseInt(element.getAttribute('data-index'));
            setStars(currentIndex);
        });
        element.addEventListener('mouseleave',()=>{
            resetStarColors();
            if (ratedIndex != -1)
                    setStars(ratedIndex);
        });
        element.addEventListener('click',()=>{
            ratedIndex = element.getAttribute('data-index');
            localStorage.setItem('ratedIndex', ratedIndex); 
            rating=localStorage.getItem('ratedIndex');
            rating=parseInt(rating)+1;
            document.querySelector('.rating-value').value=rating;
        });
    });
    
    testimonial_form.addEventListener('submit',(e)=>{
        e.preventDefault();
        resetMessage();
        rating=rating !=undefined ? rating:null;

        let data={
            name:testimonial_form.querySelector('[name=name]').value,
            email:testimonial_form.querySelector('[name=email]').value,
            message:testimonial_form.querySelector('[name=message]').value,
            rating:rating,
            nonce:testimonial_form.querySelector('[name=nonce]').value
        }
        console.log(data);
        
        if(! data.name){
            testimonial_form.querySelector('[data-error="InvalidName"]').classList.add('show');
            return;
        }

        if(! validateEmail(data.email)){
            testimonial_form.querySelector('[data-error="InvalidEmail"]').classList.add('show');
            return;
        }

        if(! data.message){
            testimonial_form.querySelector('[data-error="InvalidMessage"]').classList.add('show');
            return;
        }

        let url=testimonial_form.dataset.url;
        
        let params=new URLSearchParams(new FormData(testimonial_form));
       
        testimonial_form.querySelector('.js-form-submission').classList.add('show');

        fetch(url,{
            method:'POST',
            body:params
        }).then(res=>res.json())
            .catch(error=>{
                resetMessage();
                testimonial_form.querySelector('.js-form-error').classList.add('show');
            })
            .then(response=>{
                resetMessage();
                if(response===0 || response.status==='error'){
                    testimonial_form.querySelector('.js-form-error').classList.add('show');
                    return;
                }
                testimonial_form.querySelector('.js-form-success').classList.add('show');
                testimonial_form.reset();
            })
            
        
    });
});

function resetStarColors() {
    let star=document.querySelectorAll('.rating li .dashicons');
    star.forEach(element => {
        element.style.color='gray';
    });
}

function setStars(max) {

    for (var i=0; i <= max; i++){
        let starlist=document.getElementById('rating');
        let id="#star"+i;
        let grayStar=starlist.querySelector(id);
        if(grayStar !=undefined){
            grayStar.style.color='green';
        }
        
    }
        
}

function validateEmail(email){
    let pattern=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return pattern.test(String(email).toLowerCase());
}

function resetMessage(){
    const message=document.querySelectorAll('.field-msg');
    message.forEach(element => {
        element.classList.remove('show');
    });
}