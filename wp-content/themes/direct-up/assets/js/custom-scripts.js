class CustomScripts {

    constructor() {
        this._getSubCategories();
        this._addToFavourite();
        const chooseDepart = document.querySelector('.chooseDepart');
        if(chooseDepart){
            document.querySelector('.chooseDepart').click();
        }
    }


    _getSubCategories(){
        const categoriesList = document.querySelector('#choose-ad-cat');
        const subCategoriesDiv = document.querySelector('.sub_cat_popup');
        const finalParentCat= document.querySelector('.final_parent_cat');
        const finalSubCat= document.querySelector('.final_sub_cat');
        if(categoriesList){
        categoriesList.addEventListener('click',function (e) {
            subCategoriesDiv.innerHTML= '';
            const cat_id = e.target.getAttribute('data-cat-id');

        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: my_ajax_object.ajax_url,
            data: {
                action:'get_sub_categories_new_ad',
                category_id:cat_id
            },
            success: function(msg){
                msg.forEach(function (item) {
                    subCategoriesDiv.innerHTML += '<li class="border-bottom ptb_1" data-sub-cat-id="'+item.term_id+'" data="'+item.name+'">'+item.name+'</li>';
                    finalParentCat.setAttribute('value',cat_id);
                })
                $('.sub_cat_popup li').on('click', function () {
                    $('.sub_cat_popup li').removeClass('active');
                    $(this).addClass('active');
                    cat = $(this).attr('data')
                    var category_form=document.querySelector('#cat-'+cat_id);
                    category_form.classList.remove('d-none');
                    var subCatId= $(this).attr('data-sub-cat-id');
                    finalSubCat.setAttribute('value',subCatId);
                    $('#departmentModel').modal('hide');
                    $('.add-new-ad-form').removeClass('d-none')
                });
            }
         });
        });
        }
    }

    _addToFavourite(){
        const favouriteBtn = document.querySelectorAll('.add_to_wish');
        favouriteBtn.forEach(function (e) {
            e.addEventListener('click',function () {
                let userID = e.getAttribute('data-user-id');
                let postID = e.getAttribute('data-ad-id');
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: my_ajax_object.ajax_url,
                    data: {
                        action:'add_post_to_favorites',
                        postID : postID,
                        userID : userID,
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    }
}

new CustomScripts();