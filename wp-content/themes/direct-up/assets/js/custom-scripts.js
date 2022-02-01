class CustomScripts {

    constructor() {
        this._getSubCategories();
        this._addToFavourite();
        const chooseDepart = document.querySelector('.chooseDepart');
        if(chooseDepart){
            document.querySelector('.chooseDepart').click();
        }
        this._refreshPageWhenCloseModal();
        this._deletePost();
        this._closeMobileMenuWhenMenuLinkClicked();
    }

    _deletePost(){
        const deleteBtn= document.querySelectorAll('#delete_ad_btn');
        deleteBtn.forEach(function (e) {
            e.addEventListener('click',function (v) {
                v.preventDefault();
                var postID = e.getAttribute('data-post-id');
                jQuery.ajax({
                    type: "post",
                    dataType: "json",
                    url: my_ajax_object.ajax_url,
                    data: {
                        action:'delete_draft_post',
                        post_id:postID
                    },
                    success: function(response){
                        if (response.deleted == 'yes'){
                           location.reload();
                        }else{
                        }
                    }
                });
            })
        })
    }

    _refreshPageWhenCloseModal(){
        const closeNewAdModal= document.querySelector('#modal-new-ad-close');
        const closeNewAdModalX= document.querySelector('#modal-new-ad-close-x');
        if(closeNewAdModal) {
            closeNewAdModal.addEventListener('click', function (e) {
                location.replace('http://direct-up.com/');
            });
            closeNewAdModalX.addEventListener('click', function (e) {
                location.replace('http://direct-up.com/');
            });
        }
    }


    _getSubCategories(){
        const categoriesList = document.querySelector('#choose-ad-cat');
        const subCategoriesDiv = document.querySelectorAll('.sub_cat_list');
        if(categoriesList){
        categoriesList.addEventListener('click',function (e) {
            subCategoriesDiv.forEach(function (e) {
               e.style.display='none';
            });
            var parantCatID = e.target.getAttribute('data-cat-id');
            var subCategoriesList = document.querySelector('#sub-categories-list-'+parantCatID);
            subCategoriesList.style.display='block';
            subCategoriesList.addEventListener('click',function (v) {
                v.preventDefault();
                jQuery('#departmentModel').modal('hide');
                jQuery('.add-new-ad-form').removeClass('d-none')
                var category_form=document.querySelector('#cat-'+parantCatID);
                category_form.classList.remove('d-none');
                // final cat input
                var finalCategoryInput = document.createElement('input');
                finalCategoryInput.setAttribute('name','final_parent_cat');
                finalCategoryInput.setAttribute('class','final_parent_cat');
                finalCategoryInput.setAttribute('value',parantCatID);
                finalCategoryInput.hidden=true;

                // final subcat input
                var finalSubCategoryInput = document.createElement('input');
                finalSubCategoryInput.setAttribute('name','final_sub_cat');
                finalSubCategoryInput.setAttribute('class','final_sub_cat');
                finalSubCategoryInput.setAttribute('value',v.target.getAttribute('data-sub-cat-id'));
                finalSubCategoryInput.hidden=true;

                category_form.appendChild(finalCategoryInput);
                category_form.appendChild(finalSubCategoryInput);
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

    _closeMobileMenuWhenMenuLinkClicked(){
        const menuLink = document.querySelectorAll('.nav-link');
        const navbarMobile = document.querySelector('#navbarSupportedContent');
        if(navbarMobile){
            menuLink.forEach(function (e) {
                e.addEventListener('click',function (event) {
                 navbarMobile.classList.remove('show');
                })
            })
        }
    }
}

new CustomScripts();