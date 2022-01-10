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
        const subCategoriesDiv = document.querySelectorAll('.sub_cat_list');
        const finalParentCat= document.querySelector('.final_parent_cat');
        const finalSubCat= document.querySelector('.final_sub_cat');
        if(categoriesList){
        categoriesList.addEventListener('click',function (e) {
            subCategoriesDiv.forEach(function (e) {
               e.style.display='none';
            });
            var parantCatID = e.target.getAttribute('data-cat-id');
            var subCategoriesList = document.querySelector('#sub-categories-list-'+parantCatID);
            subCategoriesList.style.display='block';
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