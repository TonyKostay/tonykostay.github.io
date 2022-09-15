function getParams(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp(regexS);
    var results = regex.exec(window.location.href);
    if (results == null)
        return "1";
    else
        return results[1];
}
function deleteReview(target){
    let idReview = $(target).attr('data-id');
    $.ajax({
        url: `/api/feedbacks/${idReview}/delete`,
        type: 'GET',
        dataType: 'json',
        success: (data)=>{
            $(`.card[data-id="${idReview}"]`).remove();
        }
    })
}

function createDomContent(data,page) {
    const oldPagesNav = document.querySelectorAll('.page-item');
    const oldReviews = document.querySelectorAll('.card');
    if (oldPagesNav.length != 0) {
        oldPagesNav.forEach((e) => {
            e.remove();
        });
    }
    if (oldReviews.length != 0) {
        oldReviews.forEach((e) => {
            e.remove();
        });
    }
    data['rows'].forEach((element) => {
        $('.main__rightSide-content').append($(`
            <div class="card" data-id=${element['review_id']}>
            <div class="card-body">
            <button type="button" class="btn-close delete-review" data-id=${element['review_id']} aria-label="Close"></button>
            <h5 class="card-title">${element['review_user']}</h5>
            <p class="card-text">${element['review_text']}</p>
            </div>
            </div>`));
    });

    let countPages = Math.ceil(data['count'] / data['limit']);

    for (let count = 1; count <= countPages; count++) {
        if (count == +page) {
            $('.pagination').append($(`<li class="page-item active"><a class="page-link" href="/home?page=${count}">${count}</a></li>`));
        } else {
            $('.pagination').append($(`<li class="page-item"><a class="page-link" href="/home?page=${count}">${count}</a></li>`));
        }
    }
    const btnDeleteReview = document.querySelectorAll('.delete-review');
    btnDeleteReview.forEach((element) => {
        element.addEventListener('click', (event) => {
            deleteReview(event.target);
        })
    })
}

function createReviewsRequest(){
    let page = getParams('page');
    $.ajax({
        url: `/api/feedbacks?page=${page}`,
        type: 'GET',
        dataType: 'json',
        success: (data)=> {
            createDomContent(data,page)
        }
    });
}

function listenForm(){
    $('#sendForm').on('click',()=>{
        let userName = $("[name='userName']").val(),
            userReview = $("[name='userReview']").val();
        if (userReview.length<5){
            $('.errors').text('Слишком короткий отзыв');
        }else if(userName == ''){
            $('.errors').text('Укажите имя');
        }else{
            userName = userName.replace( /<script[^>]*?>.*?</g, "");
            userReview = userReview.replace( /<script[^>]*?>.*?</g, "");
            let data = {
                usernameKey: userName,
                userReviewKey: userReview
            };
            $.ajax({
                url: '/api/feedbacks/add',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json; charset=utf-8',
                dataType: 'json',
                beforeSend: ()=>{
                    $('#sendForm').prop('disableSd', true);
                },
                success: (data)=>{
                    console.log(data);
                    createReviewsRequest();
                    $('#sendForm').prop('disabled', false);
                    $("[name='reviewSend']").trigger('reset');
                    $('.errors').text(data['status']);
                }
            })
        }
    });
}

document.addEventListener('DOMContentLoaded',()=>{
    createReviewsRequest()
    listenForm();
});







